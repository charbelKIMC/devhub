<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request, $convId)
    {
        $conversation = Conversation::findOrFail($convId);

        if (!$conversation->est_active) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cette discussion est clôturée.'], 403);
            }
            return back()->withErrors(['contenu' => 'Cette discussion est clôturée.']);
        }

        $request->validate(['contenu' => 'required|string|max:1000']);

        $message = Message::create([
            'conversation_id' => $convId,
            'expediteur_id'   => Auth::id(),
            'contenu'         => $request->contenu,
            'date_envoi'      => now(),
        ]);

        // Mettre à jour la date du dernier message
        Conversation::where('id', $convId)->update([
            'dernier_message_date' => now()
        ]);

        try { broadcast(new \App\Events\MessageEnvoye($message))->toOthers(); } catch (\Throwable) {}

        $conversation = Conversation::find($convId);
        $destinataires = $conversation->participants->where('id', '!=', Auth::id());

        foreach ($destinataires as $destinataire) {
            $notif = Notification::create([
                'destinataire_id' => $destinataire->id,
                'type'            => 'nouveau_message',
                'contenu'         => Auth::user()->pseudo . ' vous a envoyé un message.',
                'lien'            => route('messagerie.show', $convId),
                'est_lue'         => false,
                'date_creation'   => now(),
            ]);
            try { broadcast(new \App\Events\NotificationCreee($notif)); } catch (\Throwable) {}
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'id' => $message->id]);
        }

        return back();
    }

    public function poll($id)
    {
        $conversation = Conversation::findOrFail($id);

        if (!$conversation->participants->contains('id', Auth::id())) {
            abort(403);
        }

        $lastId = request('last_id', 0);

            $messages = Message::with('expediteur')
                ->where('conversation_id', $id)
                ->where('id', '>', $lastId)
                ->orderBy('date_envoi')
                ->get()
                ->map(fn($m) => [
                    'id'       => $m->id,
                    'contenu'  => $m->contenu,
                    'moi'      => $m->expediteur_id === Auth::id(),
                    'date'     => $m->date_envoi->format('H:i'),
                    'avatar'   => $m->expediteur->photo,        // ← ajouter
                    'initiale' => strtoupper(substr($m->expediteur->prenom, 0, 1)), // ← ajouter
                ]);

        return response()->json(['messages' => $messages]);
    }

    public function countNonLus()
    {
        $count = \App\Models\Message::whereHas('conversation', function($q) {
            $q->whereHas('participants', function($q2) {
                $q2->where('utilisateurs.id', Auth::id());
            });
        })
        ->where('expediteur_id', '!=', Auth::id())
        ->where('est_lu', false)
        ->count();

        return response()->json(['count' => $count]);
    }
    public function marquerLus($id)
    {
        \App\Models\Message::where('conversation_id', $id)
            ->where('expediteur_id', '!=', Auth::id())
            ->where('est_lu', false)
            ->update(['est_lu' => true]);

        return response()->json(['success' => true]);
    }
    public function supprimerMiens($id)
    {
        \App\Models\Message::where('conversation_id', $id)
            ->where('expediteur_id', Auth::id())
            ->delete();

        return response()->json(['success' => true]);
    }
}