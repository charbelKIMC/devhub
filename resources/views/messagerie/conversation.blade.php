@extends('layouts.app')
@section('title', 'Messagerie – DevHub')
@section('styles')
<style>
    .messagerie-layout { display: grid; grid-template-columns: 300px 1fr; height: calc(100vh - 60px); }
    .conv-list { border-right: 1px solid var(--gray-200); overflow-y: auto; background: white; }
    .conv-list-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--gray-100); font-weight: 700; font-size: 0.95rem; }
    .conv-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--gray-100); cursor: pointer; text-decoration: none; transition: background 0.2s; }
    .conv-item:hover, .conv-item.active-conv { background: var(--purple-light); }
    .conv-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--purple-light); color: var(--purple); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; flex-shrink: 0; }
    .conv-name { font-weight: 600; font-size: 0.875rem; color: var(--gray-900); }
    .chat-area { display: flex; flex-direction: column; height: 100%; }
    .chat-header { padding: 1rem 1.5rem; border-bottom: 1px solid var(--gray-200); background: white; display: flex; align-items: center; gap: 0.75rem; }
    .chat-messages { flex: 1; overflow-y: auto; padding: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem; background: var(--gray-50); }
    .msg-bubble { max-width: 65%; padding: 0.65rem 1rem; border-radius: 16px; font-size: 0.9rem; line-height: 1.5; }
    .msg-moi { background: var(--purple); color: white; border-radius: 16px 16px 4px 16px; align-self: flex-end; }
    .msg-autre { background: white; color: var(--gray-800); border: 1px solid var(--gray-200); border-radius: 16px 16px 16px 4px; align-self: flex-start; }
    .msg-date { font-size: 0.7rem; opacity: 0.6; margin-top: 0.25rem; text-align: right; }
    .chat-input { padding: 1rem 1.5rem; border-top: 1px solid var(--gray-200); background: white; display: flex; gap: 0.75rem; }
    .chat-textarea { flex: 1; padding: 0.65rem 1rem; border: 1.5px solid var(--gray-200); border-radius: 12px; font-size: 0.9rem; font-family: 'DM Sans', sans-serif; outline: none; resize: none; max-height: 120px; transition: border-color 0.2s; }
    .chat-textarea:focus { border-color: var(--purple); }
    .btn-send { background: var(--purple); color: white; border: none; padding: 0.65rem 1.25rem; border-radius: 12px; cursor: pointer; font-size: 1rem; transition: background 0.2s; }
    .btn-send:hover { background: var(--purple-dark); }
</style>
@endsection

@section('content')
<div class="messagerie-layout">

    <!-- Liste conversations -->
    <div class="conv-list">
        <div class="conv-list-header">Messages</div>
        @foreach($conversations as $conv)
            @php $autre = $conv->participants->firstWhere('id', '!=', Auth::id()); @endphp
            @if($autre)
                <a href="{{ route('messagerie.show', $conv->id) }}"
                   class="conv-item {{ $conv->id == $conversation->id ? 'active-conv' : '' }}">
                    <div class="conv-avatar">{{ strtoupper(substr($autre->prenom, 0, 1)) }}</div>
                    <div class="conv-name">{{ $autre->prenom }} {{ $autre->nom }}</div>
                </a>
            @endif
        @endforeach
    </div>

    <!-- Zone chat -->
    <div class="chat-area">
        @php $interlocuteur = $conversation->participants->firstWhere('id', '!=', Auth::id()); @endphp

        <div class="chat-header">
            <div class="conv-avatar">{{ strtoupper(substr($interlocuteur->prenom, 0, 1)) }}</div>
            <div>
                <div style="font-weight:700;font-size:0.95rem;">{{ $interlocuteur->prenom }} {{ $interlocuteur->nom }}</div>
                <a href="{{ route('profil.show', $interlocuteur->id) }}" style="font-size:0.78rem;color:var(--purple);text-decoration:none;">Voir le profil</a>
            </div>
        </div>

        <div class="chat-messages" id="chatMessages">
            @foreach($conversation->messages->sortBy('date_envoi') as $msg)
                <div class="{{ $msg->expediteur_id === Auth::id() ? 'msg-moi' : 'msg-autre' }} msg-bubble">
                    {{ $msg->contenu }}
                    <div class="msg-date">{{ $msg->date_envoi->format('H:i') }}</div>
                </div>
            @endforeach
        </div>

        <form action="{{ route('messages.store', $conversation->id) }}" method="POST" class="chat-input" id="msgForm">
            @csrf
            <textarea name="contenu" class="chat-textarea" placeholder="Écrire un message..." rows="1" id="msgInput"></textarea>
            <button type="submit" class="btn-send"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>

</div>
@endsection

@section('scripts')
<script>
// Scroll bas
const chat = document.getElementById('chatMessages');
if (chat) chat.scrollTop = chat.scrollHeight;

// Enter pour envoyer
document.getElementById('msgInput')?.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('msgForm').submit();
    }
});

// Polling messages
let lastId = {{ $conversation->messages->last()?->id ?? 0 }};
setInterval(() => {
    fetch(`{{ route('messages.poll', $conversation->id) }}?last_id=${lastId}`, {
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(data => {
        data.messages.forEach(msg => {
            const div = document.createElement('div');
            div.className = (msg.moi ? 'msg-moi' : 'msg-autre') + ' msg-bubble';
            div.innerHTML = msg.contenu + `<div class="msg-date">${msg.date}</div>`;
            chat.appendChild(div);
            lastId = msg.id;
        });
        if (data.messages.length) chat.scrollTop = chat.scrollHeight;
    })
    .catch(() => {});
}, 5000);
</script>
@endsection