<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Auth::user()->conversations()
            ->with([
                'messages'     => fn($q) => $q->orderBy('date_envoi','desc')->limit(1),
                'participants'
            ])
            ->orderBy('dernier_message_date','desc')
            ->get();

        return view('messagerie.index', compact('conversations'));
    }

    public function show($id)
    {
        $convActive = Auth::user()->conversations()
            ->with(['messages', 'participants'])
            ->findOrFail($id);

        $interlocuteur = $convActive->participants
            ->firstWhere('id', '!=', Auth::id());

        $conversations = Auth::user()->conversations()
            ->with([
                'messages'     => fn($q) => $q->orderBy('date_envoi','desc')->limit(1),
                'participants'
            ])
            ->orderBy('dernier_message_date','desc')
            ->get();
        // Marquer tous les messages non lus comme lus
        Message::where('conversation_id', $id)
            ->where('expediteur_id', '!=', Auth::id())
            ->where('est_lu', false)
            ->update(['est_lu' => true]);
        // Vérifier si conversation de mentorat désactivée
        $mentoratTermine = false;
        if ($convActive->relation_mentorat_id) {
            $relation = \App\Models\RelationMentorat::find($convActive->relation_mentorat_id);
            $mentoratTermine = $relation && $relation->statut === 'terminée';
        }

        return view('messagerie.index', compact('conversations', 'convActive', 'interlocuteur', 'mentoratTermine'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'destinataire_id' => 'required|exists:utilisateurs,id',
        ]);

        // Vérifier si conversation directe déjà existante
        $existing = DB::table('participants_conversation as p1')
            ->join('participants_conversation as p2', 'p1.conversation_id', '=', 'p2.conversation_id')
            ->join('conversations as c', 'c.id', '=', 'p1.conversation_id')
            ->where('p1.utilisateur_id', Auth::id())
            ->where('p2.utilisateur_id', $request->destinataire_id)
            ->where('c.type_conversation', 'privee')
            ->where('c.est_mentorat', 0)
            ->value('p1.conversation_id');

        if ($existing) {
            return redirect()->route('messagerie.show', $existing);
        }

        $conversation = Conversation::create([
            'type_conversation'  => 'privee',
            'statut_acceptation' => 'acceptée',
        ]);

        DB::table('participants_conversation')->insert([
            ['conversation_id' => $conversation->id, 'utilisateur_id' => Auth::id()],
            ['conversation_id' => $conversation->id, 'utilisateur_id' => $request->destinataire_id],
        ]);

        return redirect()->route('messagerie.show', $conversation->id);
    }

    public function supprimer($id)
    {
        $conversation = Conversation::findOrFail($id);

        // Vérifier que l'utilisateur est participant
        if (!$conversation->participants->contains('id', Auth::id())) {
            abort(403);
        }

        // Supprimer messages + participants + conversation
        \App\Models\Message::where('conversation_id', $id)->delete();
        \DB::table('participants_conversation')->where('conversation_id', $id)->delete();
        $conversation->delete();

        return response()->json(['success' => true]);
    }
}