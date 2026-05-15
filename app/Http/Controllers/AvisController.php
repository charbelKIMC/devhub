<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Publication;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'publication_id' => 'required|exists:publications,id',
            'note'           => 'required|integer|min:1|max:5',
            'commentaire'    => 'nullable|string|max:500',
        ]);

        // Vérifier que c'est un projet terminé
        $pub = Publication::findOrFail($request->publication_id);
        if (!in_array($pub->type, ['projet_personnel', 'projet_collaboratif'])) {
            return back()->withErrors(['note' => 'Les avis ne sont possibles que sur les projets.']);
        }

        // Un seul avis par utilisateur par publication
        $existant = Avis::where('publication_id', $request->publication_id)
                        ->where('auteur_id', Auth::id())
                        ->first();

        if ($existant) {
            return back()->withErrors(['note' => 'Vous avez déjà donné un avis sur ce projet.']);
        }

        Avis::create([
            'publication_id' => $request->publication_id,
            'auteur_id'      => Auth::id(),
            'note'           => $request->note,
            'commentaire'    => $request->commentaire,
        ]);

        // Notification à l'auteur
        if ($pub->auteur_id !== Auth::id()) {
            $notif = Notification::create([
                'destinataire_id' => $pub->auteur_id,
                'type'            => 'nouvel_avis',
                'contenu'         => Auth::user()->pseudo . ' a donné un avis ' . $request->note . '/5 sur votre projet.',
                'lien' => route('publications.show', $pub->id),
            ]);
        }
        broadcast(new \App\Events\NotificationCreee($notif));
        return back()->with('success', 'Avis enregistré avec succès.');
    }
}