<?php

namespace App\Http\Controllers;

use App\Models\RelationMentorat;
use App\Models\Notification;
use App\Models\Publication;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_utilisateurs' => Utilisateur::where('role', 'développeur')->count(),
            'total_mentors'      => Utilisateur::where('role', 'mentor')->count(),
            'total_publications' => Publication::where('statut', 'publié')->count(),
            'demandes_mentor'    => Utilisateur::where('statut', 'suspendu')
                                        ->where('role', 'développeur')
                                        ->where('demande_mentor', 1)
                                        ->count(),
            'total_attributions' => \App\Models\RelationMentorat::where('statut', 'active')->count(),
        ];

        $derniersUtilisateurs = Utilisateur::whereIn('role', ['développeur', 'mentor'])
            ->latest('date_inscription')->take(5)->get();

        $dernieresAttributions = \App\Models\RelationMentorat::with(['mentor', 'mentore'])
            ->where('statut', 'active')
            ->latest('date_debut')
            ->take(5)
            ->get();

        // Projets collaboratifs avec membres mais sans mentor attribué
        $projetsSansMentor = \App\Models\ProjetCollaboratif::with(['publication.auteur'])
            ->whereNull('mentor_id')
            ->where('taille_equipe_actuelle', '>', 0)
            ->orderByDesc('publication_id')
            ->get();

        $mentorsDisponibles = Utilisateur::where('role', 'mentor')
            ->where('statut', 'actif')
            ->withCount(['projetsEnMentorat as nb_actifs' => fn($q) =>
                $q->whereIn('phase', ['ouvert', 'en_cours'])
            ])
            ->having('nb_actifs', '<', 3)
            ->orderBy('nb_actifs')
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'derniersUtilisateurs', 'dernieresAttributions',
            'projetsSansMentor', 'mentorsDisponibles'
        ));
    }

    public function attribuerMentorProjet(Request $request, $id)
    {
        $request->validate(['mentor_id' => 'required|exists:utilisateurs,id']);

        $projetCol = \App\Models\ProjetCollaboratif::whereNull('mentor_id')->findOrFail($id);
        $pub       = Publication::findOrFail($projetCol->publication_id);
        $mentor    = Utilisateur::findOrFail($request->mentor_id);

        $conversation = \App\Models\Conversation::where('type_conversation', 'groupe_projet')
            ->where('projet_collaboratif_id', $projetCol->publication_id)
            ->first();

        \App\Jobs\AttribuerMentorJob::attribuer($mentor, $projetCol, $pub, $conversation);

        return back()->with('success', $mentor->prenom . ' ' . $mentor->nom . ' a été attribué comme mentor du projet "' . $pub->titre . '".');
    }

    public function mentors()
    {
        $demandesMentor = Utilisateur::where('statut', 'suspendu')
            ->where('role', 'développeur')
            ->where('demande_mentor', 1)
            ->orderBy('date_inscription', 'desc')
            ->get();

        // ← Ajouter : développeurs qui attendent un mentor
        $enAttenteMentor = Utilisateur::where('role', 'développeur')
            ->where('en_attente_mentor', 1)
            ->orderBy('date_inscription', 'desc')
            ->get();

        $mentorsActifs = Utilisateur::where('role', 'mentor')->get();

        $developpeurs = Utilisateur::where('role', 'développeur')
            ->where('statut', 'actif')
            ->orderBy('prenom')
            ->get();

        return view('admin.mentors', compact(
            'demandesMentor', 'mentorsActifs', 'developpeurs', 'enAttenteMentor'
        ));
    }

    public function utilisateurs(Request $request)
    {
        $query = Utilisateur::whereIn('role', ['developpeur', 'mentor']);

        if ($request->filled('q')) {
            $q = $request ->q;
            $query->where(function ($w) use ($q) {
                $w->where('prenom', 'like', "%$q%")
                  ->orWhere('nom', 'like', "%$q%")
                  ->orWhere('email', 'like', "%$q%")
                  ->orWhere('pseudo', 'like', "%$q%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $utilisateurs = $query->orderBy('date_inscription', 'desc')->paginate(15);

        return view('admin.utilisateurs', compact('utilisateurs'));
    }

    public function suspendre($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        $utilisateur->update(['statut' => 'suspendu']);

        return back()->with('success', 'Compte suspendu.');
    }

    public function activer($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        $utilisateur->update(['statut' => 'actif']);

        return back()->with('success', 'Compte activé.');
    }

    public function terminerRelation($id)
    {
        $relation = \App\Models\RelationMentorat::findOrFail($id);
        
        // Mettre à jour le statut et la date de fin réelle
        $relation->update([
            'statut'  => 'terminée',
            'date_fin'=> now(),
        ]);

        // Désactiver la conversation de mentorat liée
        \App\Models\Conversation::where('relation_mentorat_id', $relation->id)
            ->update(['statut_acceptation' => 'refusée']);

        return back()->with('success', 'Relation de mentorat terminée.');
    }

    public function publications(Request $request)
    {
        $publications = Publication::with('auteur')
                                   ->latest('date_publication')
                                   ->paginate(20);

        return view('admin.publications', compact('publications'));
    }

    public function supprimerPublication($id)
    {
        Publication::findOrFail($id)->delete();
        return back()->with('success', 'Publication supprimée.');
    }

    public function contratsNda()
    {
        $contrats = \App\Models\ContratNda::with([
            'utilisateur', 'publication.auteur', 'publication.projetCollaboratif',
        ])
        ->latest()
        ->paginate(20);

        return view('admin.contrats-nda', compact('contrats'));
    }

    public function telechargerNda($id)
    {
        $contrat = \App\Models\ContratNda::findOrFail($id);

        if (!$contrat->chemin_pdf || !\Illuminate\Support\Facades\Storage::disk('public')->exists($contrat->chemin_pdf)) {
            return back()->with('error', 'Fichier PDF introuvable.');
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->download(
            $contrat->chemin_pdf,
            'nda-' . $contrat->id . '-' . \Illuminate\Support\Str::slug($contrat->utilisateur->prenom . '-' . $contrat->utilisateur->nom) . '.pdf'
        );
    }

    public function attribuerMentor(Request $request)
    {
        $request->validate([
            'mentor_id'  => 'required|exists:utilisateurs,id',
            'mentore_id' => 'required|exists:utilisateurs,id',
            'duree_mois' => 'required|integer|min:1|max:24',
        ]);

        // Bloquer si le développeur a déjà un mentor actif
        $dejaActif = \App\Models\RelationMentorat::where('mentore_id', $request->mentore_id)
            ->where('statut', 'active')
            ->exists();

        if ($dejaActif) {
            return back()->with('error', 'Ce développeur a déjà un mentor actif.');
        }

        // Créer la nouvelle relation
        $relation = \App\Models\RelationMentorat::create([
            'mentor_id'  => $request->mentor_id,
            'mentore_id' => $request->mentore_id,
            'statut'     => 'active',
            'date_debut' => now(),
            'date_fin'   => now()->addMonths((int) $request->duree_mois),
        ]);

        $mentor  = Utilisateur::find($request->mentor_id);
        $mentore = Utilisateur::find($request->mentore_id);

        // Créer la conversation de mentorat
        $convId = DB::table('conversations')->insertGetId([
            'type_conversation'    => 'privee',
            'nom'                  => 'Mentorat — ' . $mentor->prenom . ' & ' . $mentore->prenom,
            'est_mentorat'         => 1,
            'relation_mentorat_id' => $relation->id,
            'statut_acceptation'   => 'acceptée',
            'date_creation'        => now(),
        ]);

        // Forcer est_mentorat à 1 après insertion
        DB::table('conversations')->where('id', $convId)->update(['est_mentorat' => 1]);

        DB::table('participants_conversation')->insert([
            ['conversation_id' => $convId, 'utilisateur_id' => $mentor->id],
            ['conversation_id' => $convId, 'utilisateur_id' => $mentore->id],
        ]);

        // Remettre le flag en_attente_mentor
        Utilisateur::where('id', $request->mentore_id)->update(['en_attente_mentor' => 0]);

        // Notification développeur
        $notif = \App\Models\Notification::create([
            'destinataire_id' => $request->mentore_id,
            'type'            => 'candidature_acceptee',
            'contenu'         => 'Un mentor vous a été attribué pour ' . $request->duree_mois . ' mois.',
            'lien'            => '/mentorat/demander-mentor',
            'est_lue'         => false,
            'date_creation'   => now(),
        ]);
        broadcast(new \App\Events\NotificationCreee($notif));

        // Email + notification mentor
        try {
            \Illuminate\Support\Facades\Mail::to($mentor->email)
                ->send(new \App\Mail\AttributionMentor($mentor, $mentore));
        } catch (\Throwable $e) { \Log::error('Mail attribution mentor: ' . $e->getMessage()); }

        $notifMentor = \App\Models\Notification::create([
            'destinataire_id' => $request->mentor_id,
            'type'            => 'candidature_acceptee',
            'contenu'         => 'Nouveau mentoré attribué : ' . $mentore->prenom . ' ' . $mentore->nom,
            'lien'            => '/mentor/espace',
            'est_lue'         => false,
            'date_creation'   => now(),
        ]);
        broadcast(new \App\Events\NotificationCreee($notifMentor));

        return back()->with('success', 'Mentor attribué avec succès pour ' . $request->duree_mois . ' mois.');
    }

    public function candidaturesExternes(\Illuminate\Http\Request $request)
    {
        $statut = $request->get('statut', 'en_attente');
        $query  = \App\Models\CandidatureMentorExterne::latest('date_candidature');
        if (in_array($statut, ['en_attente', 'acceptée', 'refusée'])) {
            $query->where('statut', $statut);
        }
        $candidatures = $query->paginate(12)->withQueryString();

        $all = \App\Models\CandidatureMentorExterne::selectRaw('statut')->get();
        $stats = (object)[
            'total'      => $all->count(),
            'en_attente' => $all->where('statut', 'en_attente')->count(),
            'acceptees'  => $all->where('statut', 'acceptée')->count(),
            'refusees'   => $all->where('statut', 'refusée')->count(),
        ];

        return view('admin.candidatures-externes', compact('candidatures', 'stats', 'statut'));
    }

    public function detailCandidatureExterne($id)
    {
        $candidature = \App\Models\CandidatureMentorExterne::findOrFail($id);
        return view('admin.candidature-externe-detail', compact('candidature'));
    }

    public function validerExterne($id)
    {
        $candidature = \App\Models\CandidatureMentorExterne::findOrFail($id);

        // Supprimer un éventuel token précédent pour ce email
        DB::table('mentors_en_attente_activation')->where('email', $candidature->email)->delete();

        // Générer token sécurisé
        $token = bin2hex(random_bytes(32));

        // Stocker les données en attente (sans mot de passe — il sera créé par le candidat)
        DB::table('mentors_en_attente_activation')->insert([
            'token'      => $token,
            'email'      => $candidature->email,
            'donnees'    => encrypt(json_encode([
                'prenom'      => $candidature->prenom,
                'nom'         => $candidature->nom,
                'email'       => $candidature->email,
                'telephone'   => $candidature->telephone,
                'specialite'  => $candidature->specialite,
                'localisation'=> $candidature->localisation,
                'portfolio'   => $candidature->portfolio,
                'linkedin'    => $candidature->linkedin,
            ])),
            'expire_a'   => now()->addHours(72),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $candidature->update(['statut' => 'acceptée']);

        // Email d'acceptation avec bouton "Continuer en tant que mentor"
        try {
            Mail::send('emails.mentor-accepte-externe', [
                'prenom' => $candidature->prenom,
                'lien'   => route('mentor.activation', ['token' => $token]),
            ], function ($mail) use ($candidature) {
                $mail->to($candidature->email)
                     ->subject('Candidature acceptée – Créez votre compte mentor DevHub');
            });
        } catch (\Throwable $e) { \Log::error('Mail mentor accepte externe: ' . $e->getMessage()); }

        return redirect()->route('admin.candidatures.externes')
            ->with('success', 'Candidature acceptée. Un email d\'activation a été envoyé au candidat.');
    }

    public function refuserExterne($id)
    {
        $candidature = \App\Models\CandidatureMentorExterne::findOrFail($id);
        $candidature->update(['statut' => 'refusée']);

        // Email de refus respectueux
        try {
            Mail::send('emails.mentor-refuse', [
                'prenom'  => $candidature->prenom,
                'externe' => true,
            ], function ($mail) use ($candidature) {
                $mail->to($candidature->email)
                     ->subject('Votre candidature mentor – DevHub');
            });
        } catch (\Throwable $e) { \Log::error('Mail mentor refuse: ' . $e->getMessage()); }

        return redirect()->route('admin.candidatures.externes')
            ->with('success', 'Candidature refusée. Un email a été envoyé au candidat.');
    }

    public function detailUtilisateur($id)
    {
        $utilisateur = Utilisateur::with('competences', 'publications')->findOrFail($id);

        $pubIds      = $utilisateur->publications()->pluck('id');
        $note        = \App\Models\Avis::whereIn('publication_id', $pubIds)->avg('note');
        $collabs     = \App\Models\Candidature::where('utilisateur_id', $id)->where('statut','acceptée')->count();

        return view('admin.utilisateur-detail', compact('utilisateur', 'note', 'collabs'));
    }

    public function supprimerUtilisateur($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);

        if ($utilisateur->role === 'admin') {
            return back()->with('error', 'Impossible de supprimer un administrateur.');
        }

        DB::transaction(function () use ($utilisateur) {

            $userId = $utilisateur->id;

            // ── 1. Publications créées par l'utilisateur et tout ce qui y est lié ──
            $pubIds = Publication::where('auteur_id', $userId)->pluck('id');

            if ($pubIds->isNotEmpty()) {
                // Conversations de groupe liées aux projets collaboratifs de cet auteur
                $convGroupeIds = \App\Models\Conversation::whereIn('projet_collaboratif_id', $pubIds)->pluck('id');
                if ($convGroupeIds->isNotEmpty()) {
                    DB::table('participants_conversation')->whereIn('conversation_id', $convGroupeIds)->delete();
                    \App\Models\Message::whereIn('conversation_id', $convGroupeIds)->delete();
                    \App\Models\Conversation::whereIn('id', $convGroupeIds)->delete();
                }

                // NDA, candidatures, membres liés aux projets collab
                DB::table('contrats_nda')->whereIn('publication_id', $pubIds)->delete();
                \App\Models\Candidature::whereIn('projet_collaboratif_id', $pubIds)->delete();
                \App\Models\MembreProjet::whereIn('projet_collaboratif_id', $pubIds)->delete();
                \App\Models\ProjetCollaboratif::whereIn('publication_id', $pubIds)->delete();
                \App\Models\ProjetPersonnel::whereIn('publication_id', $pubIds)->delete();

                // Interactions sur ces publications
                DB::table('likes')->whereIn('publication_id', $pubIds)->delete();
                DB::table('favoris')->whereIn('publication_id', $pubIds)->delete();
                DB::table('commentaires')->whereIn('publication_id', $pubIds)->delete();
                DB::table('avis')->whereIn('publication_id', $pubIds)->delete();
                DB::table('publication_medias')->whereIn('publication_id', $pubIds)->delete();

                Publication::whereIn('id', $pubIds)->delete();
            }

            // ── 2. Activité de l'utilisateur sur les publications des autres ──
            DB::table('likes')->where('utilisateur_id', $userId)->delete();
            DB::table('favoris')->where('utilisateur_id', $userId)->delete();
            DB::table('commentaires')->where('utilisateur_id', $userId)->delete();
            DB::table('avis')->where('auteur_id', $userId)->delete();

            // ── 3. Candidatures et NDA en tant que candidat ──
            DB::table('contrats_nda')->where('utilisateur_id', $userId)->delete();
            \App\Models\Candidature::where('utilisateur_id', $userId)->delete();
            \App\Models\MembreProjet::where('utilisateur_id', $userId)->delete();

            // ── 4. Relations de mentorat ──
            $relationsIds = \App\Models\RelationMentorat::where('mentor_id', $userId)
                ->orWhere('mentore_id', $userId)->pluck('id');

            if ($relationsIds->isNotEmpty()) {
                // Conversations de mentorat liées
                $convMentoratIds = \App\Models\Conversation::whereIn('relation_mentorat_id', $relationsIds)->pluck('id');
                if ($convMentoratIds->isNotEmpty()) {
                    DB::table('participants_conversation')->whereIn('conversation_id', $convMentoratIds)->delete();
                    \App\Models\Message::whereIn('conversation_id', $convMentoratIds)->delete();
                    \App\Models\Conversation::whereIn('id', $convMentoratIds)->delete();
                }
                \App\Models\RelationMentorat::whereIn('id', $relationsIds)->delete();
            }

            // ── 5. Conversations directes (1-1) où l'utilisateur est participant ──
            $convDirectesIds = DB::table('participants_conversation')
                ->where('utilisateur_id', $userId)
                ->pluck('conversation_id');

            if ($convDirectesIds->isNotEmpty()) {
                DB::table('participants_conversation')->whereIn('conversation_id', $convDirectesIds)->delete();
                \App\Models\Message::whereIn('conversation_id', $convDirectesIds)->delete();
                \App\Models\Conversation::whereIn('id', $convDirectesIds)->delete();
            }

            // ── 6. Messages envoyés dans d'autres conversations ──
            \App\Models\Message::where('expediteur_id', $userId)->delete();

            // ── 7. Notifications ──
            Notification::where('destinataire_id', $userId)->delete();

            // ── 8. Compétences liées ──
            DB::table('utilisateur_competences')->where('utilisateur_id', $userId)->delete();

            // ── 9. Inscriptions en attente éventuelles ──
            DB::table('inscriptions_en_attente')->where('email', $utilisateur->email)->delete();

            // ── 10. Photo de profil ──
            if ($utilisateur->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($utilisateur->photo);
            }

            // ── 11. Supprimer l'utilisateur ──
            $utilisateur->delete();
        });

        return redirect()->route('admin.utilisateurs')
            ->with('success', 'L\'utilisateur et toutes ses données ont été définitivement supprimés.');
    }
}