<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\ProjetPersonnel;
use App\Models\RelationMentorat;
use App\Models\Utilisateur;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjetPersonnelController extends Controller
{
    private const MAX_MENTORES = 3;
    private const DUREE_MOIS   = 3;

    public function index()
    {
        $projets = Publication::with(['projetPersonnel', 'medias', 'likes', 'commentaires'])
            ->where('auteur_id', Auth::id())
            ->where('type', 'projet_personnel')
            ->latest('date_publication')
            ->get();

        $relationActive = RelationMentorat::with('mentor')
            ->where('mentore_id', Auth::id())
            ->where('statut', 'active')
            ->first();

        $enAttente = Auth::user()->en_attente_mentor;

        return view('projets-personnels.index', compact('projets', 'relationActive', 'enAttente'));
    }

    public function create()
    {
        // Vérifier si le dev a déjà un mentor actif (info pour la vue)
        $relationActive = RelationMentorat::with('mentor')
            ->where('mentore_id', Auth::id())
            ->where('statut', 'active')
            ->first();

        return view('projets-personnels.create', compact('relationActive'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'        => 'required|string|max:200',
            'contenu'      => 'nullable|string|max:2000',
            'technologies' => 'nullable|string|max:500',
            'url_repo'     => 'nullable|url|max:255',
            'url_demo'     => 'nullable|url|max:255',
            'images.*'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $publication = Publication::create([
            'auteur_id' => Auth::id(),
            'titre'     => $request->titre,
            'type'      => 'projet_personnel',
            'contenu'   => $request->contenu,
            'statut'    => 'brouillon',
        ]);

        ProjetPersonnel::create([
            'publication_id' => $publication->id,
            'technologies'   => $request->technologies
                ? array_map('trim', explode(',', $request->technologies))
                : [],
            'url_demo'       => $request->url_demo,
            'url_repo'       => $request->url_repo,
            'statut_projet'  => 'en_cours',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $chemin = $image->store('publications', 'public');
                \App\Models\PublicationMedia::create([
                    'publication_id' => $publication->id,
                    'chemin'         => $chemin,
                    'ordre'          => $index + 1,
                ]);
            }
        }

        $this->autoAttribuerMentor(Auth::user(), $request->technologies ?? '');

        return redirect()->route('projets-personnels.index')
            ->with('success', 'Projet "' . $publication->titre . '" créé avec succès !');
    }

    public function terminer($id)
    {
        $publication = Publication::where('auteur_id', Auth::id())
            ->where('type', 'projet_personnel')
            ->findOrFail($id);

        $projet = $publication->projetPersonnel;

        if (!$projet || $projet->statut_projet !== 'en_cours') {
            return redirect()->route('projets-personnels.index')
                ->with('error', 'Ce projet ne peut pas être marqué comme terminé.');
        }

        $publication->update(['statut' => 'publié']);
        $projet->update(['statut_projet' => 'terminé']);

        try { broadcast(new \App\Events\PublicationCreee($publication)); } catch (\Throwable) {}

        // Vérifier s'il reste des projets personnels en cours
        $autreProjetEnCours = Publication::where('auteur_id', Auth::id())
            ->where('type', 'projet_personnel')
            ->where('id', '!=', $publication->id)
            ->whereHas('projetPersonnel', fn($q) => $q->where('statut_projet', 'en_cours'))
            ->exists();

        if (!$autreProjetEnCours) {
            // Fermer la relation de mentorat active
            $relation = RelationMentorat::where('mentore_id', Auth::id())
                ->where('statut', 'active')
                ->first();

            if ($relation) {
                $relation->update(['statut' => 'terminée', 'date_fin' => now()]);

                // Désactiver la conversation de mentorat liée
                \App\Models\Conversation::where('relation_mentorat_id', $relation->id)
                    ->orWhere(function($q) use ($relation) {
                        $q->where('est_mentorat', true)
                          ->whereHas('participants', fn($p) => $p->where('utilisateur_id', $relation->mentor_id))
                          ->whereHas('participants', fn($p) => $p->where('utilisateur_id', Auth::id()));
                    })
                    ->update(['est_active' => false]);

                // Notifier le mentor
                $notif = Notification::create([
                    'destinataire_id' => $relation->mentor_id,
                    'type'            => 'projet_termine',
                    'contenu'         => Auth::user()->prenom . ' ' . Auth::user()->nom . ' a terminé tous ses projets. La relation de mentorat est clôturée.',
                    'lien'            => route('mentor.espace'),
                    'est_lue'         => false,
                    'date_creation'   => now(),
                ]);
                try { broadcast(new \App\Events\NotificationCreee($notif)); } catch (\Throwable) {}
            }

            // Retirer le flag en_attente_mentor
            Auth::user()->update(['en_attente_mentor' => false]);
        }

        return redirect()->route('projets-personnels.index')
            ->with('success', 'Projet "' . $publication->titre . '" publié dans le feed !');
    }

    private function autoAttribuerMentor(Utilisateur $developpeur, string $technologies): void
    {
        // Développeur a déjà un mentor actif
        $dejaActif = RelationMentorat::where('mentore_id', $developpeur->id)
            ->where('statut', 'active')
            ->exists();

        if ($dejaActif) return;

        // Mentors disponibles : actifs + moins de MAX_MENTORES mentorés actifs
        $mentorsDispo = Utilisateur::where('role', 'mentor')
            ->where('statut', 'actif')
            ->withCount(['mentores as nb_actifs' => fn($q) => $q->where('statut', 'active')])
            ->having('nb_actifs', '<', self::MAX_MENTORES)
            ->get();

        if ($mentorsDispo->isEmpty()) {
            $developpeur->update(['en_attente_mentor' => 1]);
            $this->notifierAdminSansMatch($developpeur);
            return;
        }

        // Préférer un mentor dont la spécialité contient une des technologies
        $mentor = null;
        $techs  = array_filter(array_map('trim', explode(',', strtolower($technologies))));

        if (!empty($techs)) {
            $mentor = $mentorsDispo->first(function ($m) use ($techs) {
                $spec = strtolower($m->specialite ?? '');
                foreach ($techs as $t) {
                    if ($t !== '' && str_contains($spec, $t)) return true;
                }
                return false;
            });
        }

        // Sinon, mentor aléatoire parmi les disponibles
        $mentor ??= $mentorsDispo->random();

        $this->creerRelationEtNotifier($developpeur, $mentor);
    }

    private function creerRelationEtNotifier(Utilisateur $developpeur, Utilisateur $mentor): void
    {
        $relation = RelationMentorat::create([
            'mentor_id'  => $mentor->id,
            'mentore_id' => $developpeur->id,
            'statut'     => 'active',
            'date_debut' => now(),
            'date_fin'   => now()->addMonths(self::DUREE_MOIS),
        ]);

        $convId = DB::table('conversations')->insertGetId([
            'type_conversation'    => 'privee',
            'nom'                  => 'Mentorat — ' . $mentor->prenom . ' & ' . $developpeur->prenom,
            'est_mentorat'         => 1,
            'relation_mentorat_id' => $relation->id,
            'statut_acceptation'   => 'acceptée',
            'date_creation'        => now(),
        ]);

        DB::table('participants_conversation')->insert([
            ['conversation_id' => $convId, 'utilisateur_id' => $mentor->id],
            ['conversation_id' => $convId, 'utilisateur_id' => $developpeur->id],
        ]);

        $developpeur->update(['en_attente_mentor' => 0]);

        $notifDev = Notification::create([
            'destinataire_id' => $developpeur->id,
            'type'            => 'candidature_acceptee',
            'contenu'         => 'Un mentor vous a été attribué : ' . $mentor->prenom . ' ' . $mentor->nom . '. Vous pouvez maintenant échanger via la messagerie.',
            'lien'            => '/messagerie/' . $convId,
            'est_lue'         => false,
            'date_creation'   => now(),
        ]);
        try { broadcast(new \App\Events\NotificationCreee($notifDev)); } catch (\Throwable) {}

        $notifMentor = Notification::create([
            'destinataire_id' => $mentor->id,
            'type'            => 'candidature_acceptee',
            'contenu'         => 'Nouveau mentoré attribué automatiquement : ' . $developpeur->prenom . ' ' . $developpeur->nom . '.',
            'lien'            => '/mentor/espace',
            'est_lue'         => false,
            'date_creation'   => now(),
        ]);
        try { broadcast(new \App\Events\NotificationCreee($notifMentor)); } catch (\Throwable) {}

        try {
            \Illuminate\Support\Facades\Mail::to($developpeur->email)
                ->send(new \App\Mail\MentorAutoAttribue($developpeur, $mentor));
        } catch (\Throwable $e) { \Log::error('Mail mentor auto attribue dev: ' . $e->getMessage()); }

        try {
            \Illuminate\Support\Facades\Mail::to($mentor->email)
                ->send(new \App\Mail\NouveauMentoreAttribue($mentor, $developpeur));
        } catch (\Throwable $e) { \Log::error('Mail mentor auto attribue mentor: ' . $e->getMessage()); }
    }

    private function notifierAdminSansMatch(Utilisateur $developpeur): void
    {
        $admin = Utilisateur::where('role', 'admin')->first();
        if (!$admin) return;

        $notif = Notification::create([
            'destinataire_id' => $admin->id,
            'type'            => 'nouvelle_candidature',
            'contenu'         => $developpeur->prenom . ' ' . $developpeur->nom . ' a créé un projet personnel mais aucun mentor n\'était disponible. Attribution manuelle requise.',
            'lien'            => '/admin/dashboard',
            'est_lue'         => false,
            'date_creation'   => now(),
        ]);
        try { broadcast(new \App\Events\NotificationCreee($notif)); } catch (\Throwable) {}
    }
}
