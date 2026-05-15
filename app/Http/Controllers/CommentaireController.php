<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Publication;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentaireController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'publication_id' => 'required|exists:publications,id',
            'contenu'        => 'required|string|max:1000',
        ]);

        $commentaire = Commentaire::create([
            'publication_id'   => $request->publication_id,
            'utilisateur_id'   => Auth::id(),
            'contenu'          => $request->contenu,
            'date_commentaire' => now(),
        ]);

        // Remplace increment par recalcul
        $count = Commentaire::where('publication_id', $request->publication_id)->count();
        Publication::where('id', $request->publication_id)->update(['nb_commentaires' => $count]);

        // Notification à l'auteur
        $pub = Publication::find($request->publication_id);
        if ($pub && $pub->auteur_id !== Auth::id()) {
            $notif = Notification::create([
                'destinataire_id' => $pub->auteur_id,
                'type'            => 'nouveau_commentaire',
                'contenu'         => Auth::user()->pseudo . ' a commenté votre publication "' . $pub->titre . '".',
                'lien'            => route('publications.show', $pub->id),
                'est_lue'         => false,
                'date_creation'   => now(),
            ]);
            broadcast(new \App\Events\NotificationCreee($notif));
        }

        // ← Toujours retourner JSON si requête AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'comment' => [
                    'id'       => $commentaire->id,
                    'contenu'  => $commentaire->contenu,
                    'auteur'   => Auth::user()->prenom . ' ' . Auth::user()->nom,
                    'initiale' => strtoupper(substr(Auth::user()->prenom, 0, 1)),
                    'avatar'   => Auth::user()->photo,
                ]
            ]);
        }

        return back()->with('success', 'Commentaire ajouté.');
    }

    public function destroy($id)
    {
        $commentaire = Commentaire::where('id', $id)
                                  ->where('utilisateur_id', Auth::id())
                                  ->firstOrFail();
        $commentaire->delete();
        $count = Commentaire::where('publication_id', $commentaire->publication_id)->count();
        Publication::where('id', $commentaire->publication_id)->update(['nb_commentaires' => $count]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Commentaire supprimé.');
    }
}