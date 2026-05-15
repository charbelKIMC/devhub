<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Candidature;
use App\Models\Favori;
use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function publications(Request $request)
    {
        $query = Auth::user()->publications()->latest('date_publication');

        // Filtre type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filtre statut
        if ($request->statut) {
            $query->where('statut', $request->statut);
        }

        // Filtre date
        if ($request->date_debut) {
            $query->whereDate('date_publication', '>=', $request->date_debut);
        }
        if ($request->date_fin) {
            $query->whereDate('date_publication', '<=', $request->date_fin);
        }

        // Filtre vues min
        if ($request->vues_min) {
            $query->where('nb_vues', '>=', $request->vues_min);
        }

        // Tri
        switch ($request->sort) {
            case 'date_asc':   $query->reorder('date_publication', 'asc'); break;
            case 'vues_desc':  $query->reorder('nb_vues', 'desc'); break;
            case 'likes_desc': $query->reorder('nb_likes', 'desc'); break;
            case 'titre_asc':  $query->reorder('titre', 'asc'); break;
            default:           $query->reorder('date_publication', 'desc');
        }

        $publications = $query->paginate(15);

        return view('dashboard.publications', compact('publications'));
    }

    public function collaborations()
    {
        $user = Auth::user();

        // Projets créés par l'utilisateur
        $mesProjets = \App\Models\Publication::with([
            'projetCollaboratif.membres.utilisateur',
            'projetCollaboratif.candidatures'            => fn($q) => $q->latest('date_postulation'),
            'projetCollaboratif.candidatures.utilisateur',
            'projetCollaboratif.conversation'            => fn($q) => $q->where('type_conversation', 'groupe_projet'),
        ])
        ->where('auteur_id', $user->id)
        ->where('type', 'projet_collaboratif')
        ->latest('date_publication')
        ->get();

        // Participations de l'utilisateur
        $mesParticipations = \App\Models\Candidature::with([
            'projet.publication.auteur',
            'projet.conversation' => fn($q) => $q->where('type_conversation', 'groupe_projet'),
        ])
        ->where('utilisateur_id', $user->id)
        ->latest('date_postulation')
        ->get()
        ->filter(fn($c) => $c->projet?->publication !== null);

        $counts = [
            'projets'       => $mesProjets->count(),
            'participations'=> $mesParticipations->count(),
            'actives'       => $mesParticipations->where('statut','acceptée')->count(),
            'en_attente'    => $mesParticipations->where('statut','en_attente')->count(),
        ];

        return view('dashboard.collaborations', compact('mesProjets', 'mesParticipations', 'counts'));
    }

    public function avis()
    {
        $user = Auth::user();

        $pubIds = Publication::where('auteur_id', $user->id)->pluck('id');

        $avis = Avis::with(['auteur', 'publication'])
            ->whereIn('publication_id', $pubIds)
            ->latest('date_avis')
            ->get();

        $moyenneGlobale = $avis->avg('note') ?? 0;

        return view('dashboard.avis', compact('avis', 'moyenneGlobale'));
    }

    public function statistiques()
    {
        $user = Auth::user();
        $pubIds = Publication::where('auteur_id', $user->id)->pluck('id');

        $stats = [
            'total_publications'  => Publication::where('auteur_id', $user->id)->count(),
            'total_vues'          => Publication::where('auteur_id', $user->id)->sum('nb_vues'),
            'total_likes'         => Publication::where('auteur_id', $user->id)->sum('nb_likes'),
            'total_commentaires'  => Publication::where('auteur_id', $user->id)->sum('nb_commentaires'),
            'total_collaborations'=> Candidature::where('utilisateur_id', $user->id)->where('statut', 'acceptée')->count(),
            'note_moyenne'        => Avis::whereIn('publication_id', $pubIds)->avg('note') ?? 0,
        ];

        $topPublications = Publication::where('auteur_id', $user->id)
            ->where('statut', 'publié')
            ->orderBy('nb_vues', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.statistiques', compact('stats', 'topPublications'));
    }

    public function favoris()
    {
        $user = Auth::user();

        $favoris = Favori::with(['publication.auteur'])
            ->where('utilisateur_id', $user->id)
            ->latest('date_ajout')
            ->get();

        return view('dashboard.favoris', compact('favoris'));
    }
}