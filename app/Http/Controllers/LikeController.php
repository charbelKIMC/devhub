<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Publication;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Request $request, $publication_id)
    {
        $userId = Auth::id();

        $like = Like::where('utilisateur_id', $userId)
                    ->where('publication_id', $publication_id)
                    ->first();

        if ($like) {
            // Supprimer via query au lieu de delete() sur le modèle
            Like::where('utilisateur_id', $userId)
                ->where('publication_id', $publication_id)
                ->delete();
            $liked = false;
        } else {
            Like::create([
                'utilisateur_id' => $userId,
                'publication_id' => $publication_id,
                'date_like'      => now(),
            ]);
            $liked = true;

            // Notification
            $pub = Publication::find($publication_id);
            if ($pub && $pub->auteur_id !== $userId) {
                $notif = Notification::create([
                    'destinataire_id' => $pub->auteur_id,
                    'type'            => 'nouveau_like',
                    'contenu'         => Auth::user()->pseudo . ' a aimé votre publication "' . $pub->titre . '".',
                    'lien'            => route('publications.show', $pub->id),
                    'est_lue'         => false,
                    'date_creation'   => now(),
                ]);
                broadcast(new \App\Events\NotificationCreee($notif));
            }
        }

        // Recalcule depuis la vraie table
        $count = Like::where('publication_id', $publication_id)->count();
        Publication::where('id', $publication_id)->update(['nb_likes' => $count]);

        return response()->json(['liked' => $liked, 'count' => $count]);
    }
}