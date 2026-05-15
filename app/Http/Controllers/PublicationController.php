<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Article;
use App\Models\Idee;
use App\Models\ProjetPersonnel;
use App\Models\ProjetCollaboratif;
use App\Models\MembreProjet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PublicationController extends Controller
{
    // Feed principal — Dashboard
    public function feed(Request $request)
    {
        $query = Publication::with([
            'auteur', 'likes', 'favoris', 'commentaires', 'medias', 'projetPersonnel',
            'projetCollaboratif' => fn($q) => $q->withCount([
                'candidatures as nb_candidats_en_attente' => fn($q) => $q->where('statut', 'en_attente'),
            ]),
        ])
            ->where('statut', 'publié')
            ->where(function ($q) {
                $q->where('type', '!=', 'projet_collaboratif')
                  ->orWhereHas('projetCollaboratif', fn($sq) => $sq->where('phase', 'ouvert'));
            })
            ->orderBy('date_publication', 'desc');

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $publications = $query->paginate(10);

        // IDs des projets collab où l'utilisateur a déjà une candidature active ou est membre
        $mesCandidaturesIds = [];
        $mesMembresIds      = [];
        if (Auth::check()) {
            $mesCandidaturesIds = \App\Models\Candidature::where('utilisateur_id', Auth::id())
                ->whereIn('statut', ['en_attente', 'acceptée'])
                ->pluck('projet_collaboratif_id')
                ->toArray();
            $mesMembresIds = \App\Models\MembreProjet::where('utilisateur_id', Auth::id())
                ->pluck('projet_collaboratif_id')
                ->toArray();
        }

        // Nouvelles pubs depuis la dernière visite du dashboard
        $derniereVisite = session('dashboard_last_visit', now()->subMinutes(30));
        $newPubsCount   = Publication::where('statut', 'publié')
            ->where('auteur_id', '!=', Auth::id())
            ->where('date_publication', '>', $derniereVisite)
            ->count();
        session(['dashboard_last_visit' => now()]);

        // Données sidebar — chargées ici une seule fois
        $suggestions = \App\Models\Utilisateur::where('id', '!=', Auth::id())
            ->where('role', 'développeur')
            ->where('statut', 'actif')
            ->latest('date_inscription')
            ->take(4)
            ->get();

        $projetsOuverts = ProjetCollaboratif::with('publication')
            ->where('phase', 'ouvert')
            ->take(4)
            ->get();

        $conversations = Auth::user()->conversations()
            ->with([
                'messages'     => fn($q) => $q->orderBy('date_envoi', 'desc')->limit(1),
                'participants',
            ])
            ->withCount([
                'messages as non_lus' => fn($q) => $q
                    ->where('expediteur_id', '!=', Auth::id())
                    ->where('est_lu', false),
            ])
            ->orderBy('dernier_message_date', 'desc')
            ->take(6)
            ->get();

        return view('dashboard.index', compact(
            'publications', 'newPubsCount', 'mesCandidaturesIds', 'mesMembresIds',
            'suggestions', 'projetsOuverts', 'conversations'
        ));
    }

    // Affiche le formulaire de création
    public function create(Request $request)
    {
        return view('publications.create', ['type' => $request->type]);
    }

    // Affiche une publication
    public function show($id)
    {
        $publication = Publication::with([
            'auteur',
            'commentaires.utilisateur',
            'likes',
            'avis.auteur',
            'projetPersonnel',
            'projetCollaboratif',
        ])->findOrFail($id);

        // Incrémenter les vues
        // Incrémenter une seule fois par utilisateur par session
        $sessionKey = 'vue_pub_' . $id . '_' . Auth::id();
        if ($publication->statut === 'publié' && !session()->has($sessionKey)) {
            Publication::where('id', $id)->increment('nb_vues');
            session()->put($sessionKey, true);
        }

        $dejaCandidat = false;
        $dejaMembre   = false;
        if (Auth::check() && $publication->type === 'projet_collaboratif') {
            $dejaCandidat = \App\Models\Candidature::where('projet_collaboratif_id', $publication->id)
                ->where('utilisateur_id', Auth::id())
                ->whereIn('statut', ['en_attente', 'acceptée'])
                ->exists();
            $dejaMembre = \App\Models\MembreProjet::where('projet_collaboratif_id', $publication->id)
                ->where('utilisateur_id', Auth::id())
                ->exists();
        }

        return view('publications.show', compact('publication', 'dejaCandidat', 'dejaMembre'));
    }
    // Liste toutes les publications
    public function index()
    {
        $publications = Publication::with('auteur')
            ->where('statut', 'publié')
            ->latest()
            ->paginate(10);

        return view('explorer', compact('publications'));
    }

    // Enregistre une nouvelle publication
    public function store(Request $request)
    {
        $request->validate([
            'titre'   => 'required|string|max:200',
            'type'    => 'required|in:article,idee,projet_collaboratif',
            'contenu' => 'nullable|string',
            'statut'  => 'required|in:brouillon,publié',
            'images.*'=> 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $publication = Publication::create([
            'auteur_id' => Auth::id(),
            'titre'     => $request->titre,
            'type'      => $request->type,
            'contenu'   => $request->contenu,
            'statut'    => $request->statut,
        ]);

        if ($publication->statut === 'publié') {
            broadcast(new \App\Events\PublicationCreee($publication));
        }
        switch ($request->type) {
            case 'article':
                \App\Models\Article::create([
                    'publication_id' => $publication->id,
                    'categorie'      => $request->categorie,
                    'temps_lecture'  => $request->temps_lecture,
                    'tags'           => $request->tags ? explode(',', $request->tags) : [],
                ]);
                break;

            case 'idee':
                \App\Models\Idee::create([
                    'publication_id' => $publication->id,
                    'domaine'        => $request->domaine,
                    'complexite'     => $request->complexite ?? 'simple',
                ]);
                break;

            case 'projet_collaboratif':
                \App\Models\ProjetCollaboratif::create([
                    'publication_id'         => $publication->id,
                    'technologies'           => $request->technologies ? explode(',', $request->technologies) : [],
                    'url_repo'               => $request->url_repo,
                    'date_debut'             => now(),
                    'phase'                  => $request->phase ?? 'ouvert',
                    'taille_equipe_max'      => $request->taille_equipe_max ?? 5,
                    'taille_equipe_actuelle' => 1,
                ]);
                \App\Models\MembreProjet::create([
                    'projet_collaboratif_id' => $publication->id,
                    'utilisateur_id'         => Auth::id(),
                    'role'                   => 'createur',
                ]);
                break;
        }

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

        return response()->json([
            'success' => true,
            'message' => 'Publication créée avec succès.',
            'id'      => $publication->id,
        ]);
    }

    public function edit($id)
    {
        $publication = Publication::where('auteur_id', auth()->id())
            ->findOrFail($id);

        return view('publications.edit', compact('publication'));
    }

    // Met à jour une publication
    public function update(Request $request, $id)
    {
        $request->validate([
            'titre'  => 'required|string|max:200',
            'statut' => 'required|in:brouillon,publié,archivé',
        ]);

        $publication = Publication::findOrFail($id);

        if ($publication->auteur_id !== Auth::id()) abort(403);

        $publication->update([
            'titre'   => $request->titre,
            'contenu' => $request->contenu,
            'statut'  => $request->statut,
        ]);

        // Mise à jour des données projet si applicable
        if ($publication->projetPersonnel) {
            $publication->projetPersonnel->update([
                'url_repo'      => $request->url_repo,
                'url_demo'      => $request->url_demo,
                'technologies'  => $request->technologies
                    ? explode(',', $request->technologies)
                    : [],
            ]);
        }

        if ($publication->projetCollaboratif) {
            $publication->projetCollaboratif->update([
                'url_repo' => $request->url_repo,
                'phase'    => $request->phase,
            ]);
        }

        // ← Redirection vers le dashboard avec message succès
        return redirect()->route('dashboard')
            ->with('success', 'Publication "' . $publication->titre . '" mise à jour avec succès.');
    }
    public function terminerCollab($id)
    {
        $publication = Publication::with(['projetCollaboratif'])->findOrFail($id);
        $projetCol   = $publication->projetCollaboratif;

        if (!$projetCol) abort(404);

        if ($publication->auteur_id !== Auth::id()) abort(403);

        if ($projetCol->phase !== 'en_cours') {
            return back()->with('error', 'Ce projet ne peut pas être clôturé dans son état actuel.');
        }

        // Clôturer le projet et le rendre public
        $projetCol->update(['phase' => 'terminé']);
        $publication->update(['statut' => 'publié']);

        // Désactiver la conversation de groupe
        \App\Models\Conversation::where('projet_collaboratif_id', $publication->id)
            ->update(['est_active' => false]);

        // Notifier tous les membres
        $membres = \App\Models\MembreProjet::where('projet_collaboratif_id', $publication->id)
            ->pluck('utilisateur_id');

        foreach ($membres as $membreId) {
            if ($membreId === Auth::id()) continue;
            $notif = \App\Models\Notification::create([
                'destinataire_id' => $membreId,
                'type'            => 'projet_termine',
                'contenu'         => 'Le projet collaboratif "' . $publication->titre . '" a été clôturé. La discussion est désormais en lecture seule.',
                'lien'            => route('publications.show', $publication->id),
                'est_lue'         => false,
                'date_creation'   => now(),
            ]);
            try { broadcast(new \App\Events\NotificationCreee($notif)); } catch (\Throwable) {}
        }

        return back()->with('success', 'Projet clôturé. La discussion est maintenant en lecture seule.');
    }

    // Supprime une publication
    public function destroy($id)
    {
        $publication = Publication::where('auteur_id', auth()->id())
            ->findOrFail($id);

        $publication->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Publication supprimée.');
    }

    // Affiche la page d'exploration
    public function explorer(Request $request)
    {
        $query = Publication::with('auteur')
            ->where('statut', 'publié')
            ->orderBy('date_publication', 'desc');

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $publications = $query->paginate(12);
        return view('explorer', compact('publications'));
    }

    public function showPublic($id)
    {
        $publication = Publication::with([
            'auteur',
            'commentaires.utilisateur',
            'likes',
            'avis.auteur',
            'projetPersonnel',
            'projetCollaboratif',
        ])->where('statut', 'publié')->findOrFail($id);

        // Incrémenter les vues
        $sessionKey = 'vue_pub_' . $id . '_visiteur';
        if (!session()->has($sessionKey)) {
            Publication::where('id', $id)->increment('nb_vues');
            session()->put($sessionKey, true);
        }

        return view('publications.show-public', compact('publication'));
    }
}