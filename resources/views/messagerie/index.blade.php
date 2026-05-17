<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie – DevHub</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <style>
        :root {
            --purple:         #7C3AED;
            --purple-dark:    #6D28D9;
            --purple-mid:     #5B21B6;
            --purple-light:   #EDE9FE;
            --purple-xlight:  #F5F3FF;
            --white:          #ffffff;
            --gray-50:        #F9FAFB;
            --gray-100:       #F3F4F6;
            --gray-200:       #E5E7EB;
            --gray-300:       #D1D5DB;
            --gray-400:       #9CA3AF;
            --gray-500:       #6B7280;
            --gray-600:       #4B5563;
            --gray-700:       #374151;
            --gray-900:       #111827;
            --online:         #22C55E;
            --away:           #F59E0B;
            --offline:        #9CA3AF;
            --sb-bg:          #F5F3FF;
            --sb-border:      #DDD6FE;
            --sb-hover:       #EDE9FE;
            --sb-active:      #DDD6FE;
            --rail-bg:        #4C1D95;
            --rail-active:    rgba(255,255,255,0.15);
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html, body { height:100%; font-family:'DM Sans',sans-serif; overflow:hidden; background:var(--white); }
        .msg-app { display:flex; height:100vh; overflow:hidden; }

        /* RAIL */
        .msg-rail { width:62px; background:var(--rail-bg); display:flex; flex-direction:column; align-items:center; padding:0.85rem 0; gap:0.2rem; flex-shrink:0; z-index:20; }
        .rail-logo { width:36px; height:36px; background:var(--white); border-radius:10px; display:flex; align-items:center; justify-content:center; color:white; font-size:0.95rem; margin-bottom:1.25rem; text-decoration:none; transition:background 0.2s; flex-shrink:0; }
        .rail-btn { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.45); cursor:pointer; transition:all 0.18s; text-decoration:none; font-size:0.95rem; position:relative; }
        .rail-btn:hover  { background:rgba(255,255,255,0.1); color:rgba(255,255,255,0.85); }
        .rail-btn.active { background:var(--rail-active); color:white; }
        .rail-notif-dot { position:absolute; top:7px; right:7px; width:7px; height:7px; border-radius:50%; background:#EF4444; border:1.5px solid var(--rail-bg); }
        .rail-spacer { flex:1; }
        .rail-user-avatar { width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,0.15); color:white; display:flex; align-items:center; justify-content:center; font-size:0.82rem; font-weight:700; overflow:hidden; cursor:pointer; text-decoration:none; margin-top:0.25rem; transition:opacity 0.2s; }
        .rail-user-avatar:hover { opacity:0.85; }
        .rail-user-avatar img { width:100%; height:100%; object-fit:cover; }

        /* SIDEBAR */
        .msg-sidebar { width:290px; background:var(--sb-bg); border-right:1px solid var(--sb-border); display:flex; flex-direction:column; flex-shrink:0; z-index:15; }
        .sb-head { padding:1.1rem 1.1rem 0.85rem; border-bottom:1px solid var(--sb-border); flex-shrink:0; }
        .sb-title { font-family:'Urbanist',sans-serif; font-size:1.05rem; font-weight:800; color:var(--gray-900); margin-bottom:0.75rem; }
        .sb-search { position:relative; }
        .sb-search input { width:100%; padding:0.5rem 0.75rem 0.5rem 2.1rem; background:var(--white); border:1.5px solid var(--gray-200); border-radius:10px; font-size:0.82rem; font-family:'DM Sans',sans-serif; color:var(--gray-700); outline:none; transition:border-color 0.2s; }
        .sb-search input::placeholder { color:var(--gray-400); }
        .sb-search input:focus { border-color:var(--purple); }
        .sb-search i { position:absolute; left:0.65rem; top:50%; transform:translateY(-50%); font-size:0.75rem; color:var(--gray-400); }
        .sb-section { font-size:0.65rem; font-weight:700; color:var(--gray-400); text-transform:uppercase; letter-spacing:0.7px; padding:0.75rem 1.1rem 0.2rem; }
        .sb-list { flex:1; overflow-y:auto; padding:0.35rem 0; scrollbar-width:thin; scrollbar-color:var(--sb-border) transparent; }
        .sb-list::-webkit-scrollbar { width:3px; }
        .sb-list::-webkit-scrollbar-thumb { background:var(--sb-border); border-radius:2px; }

        .conv-item { display:flex; align-items:center; gap:0.7rem; padding:0.6rem 1.1rem; cursor:pointer; transition:background 0.15s; text-decoration:none; position:relative; }
        .conv-item:hover  { background:var(--sb-hover); }
        .conv-item.active { background:var(--sb-active); }
        .conv-item.has-unread .ci-name { font-weight:700; color:var(--gray-900); }
        .conv-item.has-unread .ci-preview { color:var(--gray-700); font-weight:500; }

        .ci-avatar { width:42px; height:42px; border-radius:50%; background:var(--purple-light); color:var(--purple); display:flex; align-items:center; justify-content:center; font-size:0.9rem; font-weight:700; flex-shrink:0; overflow:hidden; position:relative; }
        .ci-avatar img { width:100%; height:100%; object-fit:cover; }
        .ci-status { position:absolute; bottom:1px; right:1px; width:11px; height:11px; border-radius:50%; border:2px solid var(--sb-bg); }
        .ci-status.online  { background:var(--online); }
        .ci-status.offline { background:var(--offline); }
        .ci-info { flex:1; min-width:0; }
        .ci-name { font-size:0.875rem; font-weight:600; color:var(--gray-900); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; line-height:1.3; }
        .ci-preview { font-size:0.75rem; color:var(--gray-400); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; margin-top:0.1rem; }
        .ci-meta { display:flex; flex-direction:column; align-items:flex-end; gap:0.25rem; flex-shrink:0; }
        .ci-time { font-size:0.65rem; color:var(--gray-400); }
        .ci-badge { background:var(--purple); color:white; font-size:0.6rem; font-weight:700; min-width:18px; height:18px; border-radius:9px; display:flex; align-items:center; justify-content:center; padding:0 4px; }
        .msg-lu-indicator { font-size:0.62rem; color:var(--purple); display:flex; align-items:center; gap:0.2rem; }
        .sb-empty { padding:2.5rem 1rem; text-align:center; color:var(--gray-400); font-size:0.82rem; }
        .sb-empty i { font-size:2rem; color:var(--gray-200); display:block; margin-bottom:0.5rem; }
        .sb-footer { padding:0.75rem 1.1rem; border-top:1px solid var(--sb-border); flex-shrink:0; }
        .btn-new-msg { display:flex; align-items:center; justify-content:center; gap:0.45rem; width:100%; padding:0.55rem; background:var(--purple); color:white; border:none; border-radius:10px; font-size:0.82rem; font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif; transition:background 0.2s; }
        .btn-new-msg:hover { background:var(--purple-dark); }

        /* ZONE CHAT */
        .msg-chat { flex:1; display:flex; flex-direction:column; overflow:hidden; background:var(--white); }
        .chat-top { height:62px; flex-shrink:0; display:flex; align-items:center; gap:0.85rem; padding:0 1.25rem; border-bottom:1px solid var(--gray-200); background:var(--white); }
        .chat-back-btn { display:none; width:34px; height:34px; border-radius:8px; background:var(--purple-light); color:var(--purple); border:none; cursor:pointer; align-items:center; justify-content:center; font-size:0.9rem; flex-shrink:0; transition:all 0.18s; }
        .chat-back-btn:hover { background:var(--purple); color:white; }
        .chat-top-avatar { width:38px; height:38px; border-radius:50%; position:relative; background:var(--purple-light); color:var(--purple); display:flex; align-items:center; justify-content:center; font-size:0.88rem; font-weight:700; overflow:hidden; flex-shrink:0; }
        .chat-top-avatar img { width:100%; height:100%; object-fit:cover; }
        .chat-top-avatar .online-dot { position:absolute; bottom:1px; right:1px; width:10px; height:10px; border-radius:50%; background:var(--online); border:2px solid white; }
        .chat-top-info { flex:1; }
        .chat-top-name { font-size:0.9rem; font-weight:700; color:var(--gray-900); line-height:1.2; }
        .chat-top-status { font-size:0.7rem; color:var(--gray-400); display:flex; align-items:center; gap:0.3rem; }
        .chat-top-status .dot { width:6px; height:6px; border-radius:50%; background:var(--gray-400); display:inline-block; }
        .chat-top-status.online .dot { background:var(--online); }
        .chat-top-status.online { color:var(--online); }
        .chat-top-actions { display:flex; gap:0.2rem; }
        .chat-top-btn { width:34px; height:34px; border-radius:8px; background:transparent; border:none; cursor:pointer; color:var(--gray-400); font-size:0.85rem; display:flex; align-items:center; justify-content:center; transition:all 0.18s; }
        .chat-top-btn:hover { background:var(--gray-100); color:var(--gray-900); }

        .chat-body { flex:1; overflow-y:auto; padding:1.25rem 1.5rem; display:flex; flex-direction:column; gap:0.4rem; scrollbar-width:thin; scrollbar-color:var(--gray-200) transparent; }
        .chat-body::-webkit-scrollbar { width:4px; }
        .chat-body::-webkit-scrollbar-thumb { background:var(--gray-200); border-radius:2px; }
        .date-sep { display:flex; align-items:center; gap:0.75rem; margin:0.75rem 0; font-size:0.7rem; color:var(--gray-400); }
        .date-sep::before, .date-sep::after { content:''; flex:1; height:1px; background:var(--gray-200); }

        .msg-row { display:flex; align-items:flex-end; gap:0.45rem; }
        .msg-row.me { flex-direction:row-reverse; }
        .msg-row + .msg-row { margin-top:0.1rem; }
        .msg-ava { width:24px; height:24px; border-radius:50%; background:var(--purple-light); color:var(--purple); display:flex; align-items:center; justify-content:center; font-size:0.6rem; font-weight:700; flex-shrink:0; overflow:hidden; }
        .msg-ava img { width:100%; height:100%; object-fit:cover; }
        .msg-ava.ghost { visibility:hidden; }
        .msg-bubble { max-width:100%; padding:0.55rem 0.85rem; font-size:0.875rem; line-height:1.5; word-break:break-word; }
        .msg-bubble.other { background:var(--gray-100); color:var(--gray-900); border-radius:4px 14px 14px 14px; }
        .msg-bubble.me { background:var(--purple); color:white; border-radius:14px 14px 4px 14px; }
        .msg-time { font-size:0.6rem; color:var(--gray-400); margin-top:0.15rem; padding:0 0.4rem; }
        .msg-time.me { text-align:right; display:flex; align-items:center; justify-content:flex-end; gap:0.3rem; }
        .msg-status { font-size:0.62rem; }
        .msg-status.lu { color:var(--purple); }
        .msg-status.envoye { color:var(--gray-400); }
        .msg-new { animation:msgIn 0.22s ease; }
        @keyframes msgIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }

        .typing-indicator { display:none; align-items:center; gap:0.45rem; padding:0.4rem 0; font-size:0.75rem; color:var(--gray-400); }
        .typing-dots { display:flex; gap:0.2rem; }
        .typing-dots span { width:5px; height:5px; border-radius:50%; background:var(--gray-300); animation:typingBounce 1.2s infinite; }
        .typing-dots span:nth-child(2) { animation-delay:0.2s; }
        .typing-dots span:nth-child(3) { animation-delay:0.4s; }
        @keyframes typingBounce { 0%,60%,100% { transform:translateY(0); } 30% { transform:translateY(-4px); } }

        .chat-placeholder { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; color:var(--gray-400); text-align:center; padding:2rem; gap:0.75rem; }
        .chat-placeholder-icon { width:72px; height:72px; border-radius:50%; background:var(--purple-xlight); display:flex; align-items:center; justify-content:center; font-size:1.75rem; color:var(--purple-light); margin-bottom:0.25rem; }
        .chat-placeholder h3 { font-family:'Urbanist',sans-serif; font-size:1rem; font-weight:700; color:var(--gray-600); }
        .chat-placeholder p { font-size:0.82rem; color:var(--gray-400); line-height:1.6; }

        .chat-input-bar { padding:0.85rem 1.5rem; border-top:1px solid var(--gray-200); background:var(--white); flex-shrink:0; }
        .chat-input-wrap { display:flex; align-items:flex-end; gap:0.6rem; background:var(--gray-50); border:1.5px solid var(--gray-200); border-radius:16px; padding:0.5rem 0.65rem; transition:all 0.2s; }
        .chat-input-wrap:focus-within { border-color:var(--purple); background:var(--white); box-shadow:0 0 0 3px rgba(124,58,237,0.07); }
        .input-extras { display:flex; gap:0.05rem; flex-shrink:0; }
        .input-extra-btn { width:28px; height:28px; border-radius:6px; background:transparent; border:none; cursor:pointer; color:var(--gray-400); font-size:0.82rem; display:flex; align-items:center; justify-content:center; transition:color 0.15s; }
        .input-extra-btn:hover { color:var(--purple); }
        .chat-textarea { flex:1; background:transparent; border:none; outline:none; font-size:0.875rem; font-family:'DM Sans',sans-serif; color:var(--gray-900); resize:none; max-height:110px; overflow-y:auto; line-height:1.5; padding:0.1rem 0; }
        .chat-textarea::placeholder { color:var(--gray-400); }
        .chat-send { width:34px; height:34px; border-radius:50%; background:var(--purple); color:white; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:0.8rem; flex-shrink:0; transition:all 0.18s; }
        .chat-send:hover { background:var(--purple-dark); transform:scale(1.07); }
        .chat-send:disabled { background:var(--gray-200); transform:none; cursor:not-allowed; }

        @media (max-width:1024px) { .msg-rail { display:none; } .msg-sidebar { width:270px; } }
        @media (max-width:768px) {
            html,body { overflow:auto; }
            .msg-app { position:relative; overflow:hidden; height:100vh; }
            .msg-sidebar { width:100%; position:absolute; top:0; left:0; bottom:0; z-index:30; transform:translateX(0); transition:transform 0.3s ease; }
            .msg-app.conv-open .msg-sidebar { transform:translateX(-100%); pointer-events:none; }
            .msg-chat { position:absolute; top:0; left:0; right:0; bottom:0; z-index:25; transform:translateX(100%); transition:transform 0.3s ease; }
            .msg-app.conv-open .msg-chat { transform:translateX(0); }
            .chat-back-btn { display:flex; }
            .msg-bubble { max-width:78%; }
            .chat-input-bar { padding:0.7rem 1rem; }
            .chat-body { padding:1rem; }
            .chat-top { padding:0 1rem; }
        }
    </style>
</head>
<body>

@php
    $hasConv        = isset($convActive) && isset($interlocuteur);
    $mentoratTermine = $mentoratTermine ?? false;

    $estEnLigne = false;
    if ($hasConv && $interlocuteur) {
        $estEnLigne = $interlocuteur->derniere_connexion
            && $interlocuteur->derniere_connexion->diffInMinutes(now()) < 5;
    }
@endphp

<div class="msg-app {{ $hasConv ? 'conv-open' : '' }}" id="msgApp">

    <!-- RAIL -->
    <div class="msg-rail">
        <a href="{{ Auth::user()->role === 'mentor' ? route('mentor.espace') : route('dashboard') }}" title="Accueil" class="name-page">
            <span><img src="{{ asset('img/logo-devhub.png') }}" alt="Logo de DevHub" class="rail-logo"></span>
        </a>
        <a href="{{ route('messagerie.index') }}" class="rail-btn active" title="Messagerie"><i class="fas fa-comment-dots"></i></a>
        @if(Auth::user()->role !== 'mentor')
        <a href="{{ route('notifications.index') }}" class="rail-btn" title="Notifications">
            <i class="fas fa-bell"></i>
            <span class="rail-notif-dot"></span>
        </a>
        @endif
        <div class="rail-spacer"></div>
        <a href="{{ route('profil.show', Auth::user()->id) }}" class="rail-user-avatar">
            @if(Auth::user()->photo)
                <img src="{{ Auth::user()->photo_url }}" alt="">
            @else
                {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}
            @endif
        </a>
    </div>

    <!-- SIDEBAR -->
    <div class="msg-sidebar" id="msgSidebar">
        <div class="sb-head">
            <div class="sb-title">Chats</div>
            <div class="sb-search">
                <i class="fas fa-search"></i>
                <input type="text" id="searchConv" placeholder="Rechercher...">
            </div>
        </div>

        <div class="sb-list" id="sbList">
            @php
                $conversations = Auth::user()->conversations()
                    ->with([
                        'messages'     => fn($q) => $q->orderBy('date_envoi','desc')->limit(1),
                        'participants'
                    ])
                    ->orderBy('dernier_message_date','desc')
                    ->get();
            @endphp

            @if($conversations->isNotEmpty())
                <div class="sb-section">Conversations</div>
            @endif

            @forelse($conversations as $conv)
                @php
                    $estGroupe    = $conv->type_conversation === 'groupe_projet';
                    $estMentorat = !is_null($conv->relation_mentorat_id);
                    $autre        = $conv->participants->firstWhere('id', '!=', Auth::id());
                    $dernierMsg   = $conv->messages->first();
                    $nonLus       = $conv->messages()
                        ->where('expediteur_id', '!=', Auth::id())
                        ->where('est_lu', false)
                        ->count();
                    $autreEnLigne = !$estGroupe && !$estMentorat && $autre && $autre->derniere_connexion
                        && $autre->derniere_connexion->diffInMinutes(now()) < 5;

                    if ($estGroupe) {
                        $affNom = $conv->nom ?? 'Groupe projet';
                    } elseif ($estMentorat) {
                        $affNom = $conv->nom ?? 'Conversation mentorat';
                    } else {
                        $affNom = $autre ? $autre->prenom . ' ' . $autre->nom : 'Conversation';
                    }
                @endphp

                @if($autre || $estGroupe || $estMentorat)
                    <a href="{{ route('messagerie.show', $conv->id) }}"
                       class="conv-item {{ $hasConv && $convActive->id == $conv->id ? 'active' : '' }} {{ $nonLus > 0 ? 'has-unread' : '' }}"
                       data-conv-id="{{ $conv->id }}">

                        {{-- Avatar --}}
                        <div class="ci-avatar" style="
                            {{ $estGroupe   ? 'background:#EDE9FE;color:#7C3AED;border-radius:12px;' : '' }}
                            {{ $estMentorat ? 'background:#FEF3C7;border-radius:12px;'               : '' }}
                        ">
                            @if($estGroupe)
                                <i class="fas fa-users" style="font-size:1rem;"></i>
                            @elseif($estMentorat)
                                <i class="fas fa-crown" style="font-size:1rem;color:#92400E;"></i>
                            @elseif($autre && $autre->photo)
                                <img src="{{ $autre->photo_url }}" alt="">
                            @elseif($autre)
                                {{ strtoupper(substr($autre->prenom, 0, 1)) }}
                            @endif
                            @if(!$estGroupe && !$estMentorat)
                                <span class="ci-status {{ $autreEnLigne ? 'online' : 'offline' }}"></span>
                            @endif
                        </div>

                        <div class="ci-info">
                            <span class="ci-name">{{ $affNom }}</span>
                            @if($dernierMsg)
                                <span class="ci-preview">
                                    {{ $dernierMsg->expediteur_id === Auth::id() ? 'Vous : ' : '' }}{{ Str::limit($dernierMsg->contenu, 35) }}
                                </span>
                            @else
                                <span class="ci-preview" style="font-style:italic;">Démarrer la conversation</span>
                            @endif
                        </div>

                        <div class="ci-meta">
                            @if($dernierMsg)
                                <span class="ci-time">{{ $dernierMsg->date_envoi->diffForHumans(null, true) }}</span>
                            @endif
                            @if($nonLus > 0)
                                <span class="ci-badge">{{ $nonLus }}</span>
                            @elseif($dernierMsg && $dernierMsg->expediteur_id === Auth::id() && $dernierMsg->est_lu)
                                <span class="msg-lu-indicator"><i class="fas fa-check-double"></i></span>
                            @endif
                        </div>
                    </a>
                @endif
            @empty
                <div class="sb-empty">
                    <i class="fas fa-comment-slash"></i>
                    Aucune conversation
                </div>
            @endforelse
        </div>

        <div class="sb-footer">
            <button class="btn-new-msg" id="btnNewMsg">
                <i class="fas fa-edit"></i> Nouveau message
            </button>
        </div>
    </div>

    <!-- ZONE CHAT -->
    <div class="msg-chat" id="msgChat">

        @if($hasConv)
            @php
                $estConvMentorat = !is_null($convActive->relation_mentorat_id);
                $estConvGroupe   = $convActive->type_conversation === 'groupe_projet';
            @endphp

            <!-- Header -->
            <div class="chat-top">
                <button class="chat-back-btn" id="chatBackBtn"><i class="fas fa-arrow-left"></i></button>

                {{-- Avatar header --}}
                <div class="chat-top-avatar" style="
                    {{ $estConvMentorat ? 'background:#FEF3C7;border-radius:12px;' : '' }}
                    {{ $estConvGroupe   ? 'background:#EDE9FE;border-radius:12px;' : '' }}
                ">
                    @if($estConvGroupe)
                        <i class="fas fa-users" style="font-size:1rem;color:#7C3AED;"></i>
                    @elseif($estConvMentorat)
                        <i class="fas fa-crown" style="font-size:1.1rem;color:#92400E;"></i>
                    @elseif($interlocuteur->photo)
                        <img src="{{ $interlocuteur->photo_url }}" alt="">
                    @else
                        {{ strtoupper(substr($interlocuteur->prenom, 0, 1)) }}
                    @endif
                    @if(!$estConvMentorat && !$estConvGroupe && $estEnLigne)
                        <span class="online-dot"></span>
                    @endif
                </div>

                <div class="chat-top-info">
                    {{-- Nom header --}}
                    <div class="chat-top-name">
                        @if($estConvMentorat && $convActive->nom)
                            {{ $convActive->nom }}
                        @elseif($estConvGroupe && $convActive->nom)
                            {{ $convActive->nom }}
                        @else
                            {{ $interlocuteur->prenom }} {{ $interlocuteur->nom }}
                        @endif
                    </div>
                    <div class="chat-top-status {{ !$estConvMentorat && !$estConvGroupe && $estEnLigne ? 'online' : '' }}">
                        <span class="dot"></span>
                        @if($estConvGroupe)
                            @php $nbMembres = $convActive->participants->count(); @endphp
                            {{ $nbMembres }} membre{{ $nbMembres > 1 ? 's' : '' }}
                        @elseif($estConvMentorat)
                            @if($mentoratTermine)
                                <span style="color:#DC2626;">Mentorat terminé</span>
                            @else
                                <span style="color:#065F46;">Mentorat actif</span>
                            @endif
                        @elseif($estEnLigne)
                            En ligne
                        @elseif($interlocuteur->derniere_connexion)
                            Vu {{ $interlocuteur->derniere_connexion->diffForHumans() }}
                        @else
                            {{ $interlocuteur->specialite ?? ucfirst($interlocuteur->role) }}
                        @endif
                    </div>
                </div>

                <div class="chat-top-actions">
                    @if(!$estConvMentorat && !$estConvGroupe && $interlocuteur)
                    <a href="{{ route('profil.show', $interlocuteur->id) }}" class="chat-top-btn" title="Voir profil">
                        <i class="fas fa-user"></i>
                    </a>
                    @endif

                    <div style="position:relative;">
                        <button class="chat-top-btn" id="chatOptionsBtn" onclick="toggleChatOptions(event)">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div id="chatOptionsPanel" style="display:none;position:absolute;top:calc(100% + 8px);right:0;width:220px;background:white;border:1px solid var(--gray-200);border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.12);z-index:100;overflow:hidden;">
                            @if($estConvGroupe)
                                <div style="padding:0.75rem 1rem;border-bottom:1px solid var(--gray-100);">
                                    <div style="font-size:0.72rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:0.5rem;">Membres du groupe</div>
                                    @foreach($convActive->participants as $p)
                                        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.35rem;">
                                            <div style="width:24px;height:24px;border-radius:50%;background:var(--purple-light);color:var(--purple);display:flex;align-items:center;justify-content:center;font-size:0.6rem;font-weight:700;flex-shrink:0;overflow:hidden;">
                                                @if($p->photo)
                                                    <img src="{{ $p->photo_url }}" style="width:100%;height:100%;object-fit:cover;">
                                                @else
                                                    {{ strtoupper(substr($p->prenom,0,1)) }}
                                                @endif
                                            </div>
                                            <span style="font-size:0.78rem;color:var(--gray-700);">{{ $p->prenom }} {{ $p->nom }}@if($p->id === Auth::id()) <span style="color:var(--gray-400);font-size:0.68rem;">(vous)</span>@endif</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <button onclick="supprimerMesMessages()" style="display:flex;align-items:center;gap:0.65rem;padding:0.7rem 1rem;width:100%;border:none;background:transparent;font-size:0.82rem;color:var(--gray-700);cursor:pointer;transition:background 0.15s;font-family:'DM Sans',sans-serif;" onmouseover="this.style.background='var(--gray-50)'" onmouseout="this.style.background='transparent'">
                                <i class="fas fa-eraser" style="width:14px;color:var(--gray-400);"></i> Supprimer mes messages
                            </button>
                            <div style="height:1px;background:var(--gray-100);margin:0.25rem 0;"></div>
                            <button onclick="supprimerDiscussion()" style="display:flex;align-items:center;gap:0.65rem;padding:0.7rem 1rem;width:100%;border:none;background:transparent;font-size:0.82rem;color:#DC2626;cursor:pointer;transition:background 0.15s;font-family:'DM Sans',sans-serif;" onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='transparent'">
                                <i class="fas fa-trash" style="width:14px;"></i> Supprimer la discussion
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="chat-body" id="chatBody">
                @php
                    $messages  = $convActive->messages()->orderBy('date_envoi','asc')->get();
                    $prevDate  = null;
                    $lastMsgId = $messages->last()?->id ?? 0;
                @endphp

                @forelse($messages as $msg)
                    @php
                        $dateStr    = $msg->date_envoi->format('d/m/Y');
                        $isMoi      = $msg->expediteur_id === Auth::id();
                        $expediteur = $convActive->participants->firstWhere('id', $msg->expediteur_id);
                    @endphp

                    @if($msg->est_systeme)
                        <div style="display:flex;justify-content:center;margin:0.75rem 0;">
                            <div style="background:#FEF9C3;border:1px solid #FCD34D;border-radius:10px;padding:0.55rem 1.1rem;font-size:0.78rem;color:#92400E;text-align:center;max-width:80%;">
                                {{ $msg->contenu }}
                            </div>
                        </div>
                        @continue
                    @endif

                    @if($dateStr !== $prevDate)
                        <div class="date-sep">
                            {{ $msg->date_envoi->isToday() ? "Aujourd'hui" : ($msg->date_envoi->isYesterday() ? 'Hier' : $msg->date_envoi->format('d M Y')) }}
                        </div>
                        @php $prevDate = $dateStr; @endphp
                    @endif

                    @if(!$isMoi && $estConvGroupe)
                        <div style="font-size:0.68rem;color:var(--gray-400);padding:0 0 0.1rem 2.25rem;margin-top:0.4rem;">
                            {{ $expediteur?->prenom ?? 'Membre' }}
                        </div>
                    @endif

                    <div class="msg-row {{ $isMoi ? 'me' : '' }}">
                        <div class="msg-ava {{ $isMoi ? 'ghost' : '' }}">
                            @if(!$isMoi)
                                @if($expediteur?->photo)
                                    <img src="{{ $expediteur->photo_url }}" alt="">
                                @else
                                    {{ strtoupper(substr($expediteur?->prenom ?? '?', 0, 1)) }}
                                @endif
                            @endif
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:{{ $isMoi ? 'flex-end' : 'flex-start' }};gap:0.15rem;">
                            <div class="msg-bubble {{ $isMoi ? 'me' : 'other' }}">{{ $msg->contenu }}</div>
                            <div class="msg-time {{ $isMoi ? 'me' : '' }}">
                                {{ $msg->date_envoi->format('H:i') }}
                                @if($isMoi)
                                    @if($msg->est_lu)
                                        <span class="msg-status lu"><i class="fas fa-check-double"></i></span>
                                    @else
                                        <span class="msg-status envoye"><i class="fas fa-check"></i></span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:3rem 1rem;color:var(--gray-400);font-size:0.85rem;">
                        <i class="fas fa-comment" style="font-size:2.5rem;color:var(--purple-light);display:block;margin-bottom:0.75rem;"></i>
                        Envoyez le premier message !
                    </div>
                @endforelse

                <div class="typing-indicator" id="typingIndicator">
                    <div class="msg-ava">
                        @if($interlocuteur?->photo)
                            <img src="{{ $interlocuteur->photo_url }}" alt="">
                        @else
                            {{ strtoupper(substr($interlocuteur?->prenom ?? '?', 0, 1)) }}
                        @endif
                    </div>
                    <div class="typing-dots"><span></span><span></span><span></span></div>
                </div>
            </div>

            <!-- Zone saisie -->
            @if($mentoratTermine || !$convActive->est_active)
                <div style="padding:0.85rem 1.5rem;border-top:1px solid var(--gray-200);background:#FEF9C3;display:flex;align-items:center;gap:0.65rem;font-size:0.82rem;color:#92400E;">
                    <i class="fas fa-lock"></i>
                    @if(!$convActive->est_active)
                        Ce projet est terminé. La discussion est en lecture seule.
                    @else
                        Cette conversation de mentorat est terminée. La messagerie est désactivée.
                    @endif
                </div>
            @else
                <div class="chat-input-bar">
                    <div class="chat-input-wrap">
                        <div class="input-extras">
                            <button class="input-extra-btn" title="Image"><i class="far fa-image"></i></button>
                            <button class="input-extra-btn" title="Emoji"><i class="far fa-smile"></i></button>
                        </div>
                        <textarea id="msgInput" class="chat-textarea" placeholder="Écrire un message..." rows="1"></textarea>
                        <button id="msgSend" class="chat-send"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            @endif

        @else
            <div class="chat-placeholder">
                <div class="chat-placeholder-icon"><i class="fas fa-comments"></i></div>
                <h3>Vos messages</h3>
                <p>Sélectionnez une conversation dans la liste<br>ou démarrez une nouvelle discussion.</p>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var APP_URL = "{{ url('') }}";
    var CSRF    = document.querySelector('meta[name="csrf-token"]').content;
    var AUTH_ID = {{ Auth::id() }};
    var CONV_ID = @if($hasConv) {{ $convActive->id }} @else null @endif;
    var LAST_MSG_ID = @if($hasConv) {{ $convActive->messages()->max('id') ?? 0 }} @else 0 @endif;

    var msgApp   = document.getElementById('msgApp');
    var chatBody = document.getElementById('chatBody');
    var msgInput = document.getElementById('msgInput');
    var msgSend  = document.getElementById('msgSend');

    if (chatBody) chatBody.scrollTop = chatBody.scrollHeight;

    document.getElementById('chatBackBtn')?.addEventListener('click', function() {
        msgApp.classList.remove('conv-open');
    });

    msgInput?.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 110) + 'px';
    });

    msgInput?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMsg(); }
    });
    msgSend?.addEventListener('click', sendMsg);

    // ─── Envoi de message ───────────────────────────────────────
    function sendMsg() {
        if (!CONV_ID || !msgInput) return;
        var contenu = msgInput.value.trim();
        if (!contenu) return;
        var timeStr = new Date().toLocaleTimeString('fr', {hour:'2-digit', minute:'2-digit'});
        msgSend.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        msgSend.disabled  = true;
        // Affichage optimiste immédiat
        appendMsg(contenu, true, timeStr, null, '', false);
        var sentContent = contenu;
        msgInput.value = '';
        msgInput.style.height = 'auto';

        fetch(APP_URL + '/messagerie/' + CONV_ID + '/messages', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ contenu: sentContent })
        })
        .then(function(r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(function(data) {
            if (data.id) LAST_MSG_ID = Math.max(LAST_MSG_ID, data.id);
        })
        .catch(function() {
            showToast('Échec de l\'envoi. Vérifiez votre connexion.', 'error');
        })
        .finally(function() {
            msgSend.innerHTML = '<i class="fas fa-paper-plane"></i>';
            msgSend.disabled  = false;
            msgInput.focus();
        });
    }

    // ─── Ajout d'un message dans le DOM ─────────────────────────
    function appendMsg(contenu, isMoi, time, avatar, initiale, estLu) {
        if (!chatBody) return;
        var emptyMsg = chatBody.querySelector('div[style*="padding:3rem"]');
        if (emptyMsg) emptyMsg.remove();
        var div = document.createElement('div');
        div.className = 'msg-row msg-new' + (isMoi ? ' me' : '');
        var avaContent = avatar ? '<img src="' + APP_URL + '/storage/' + avatar + '" alt="">' : (initiale || '');
        var statusHtml = isMoi ? '<span class="msg-status envoye"><i class="fas fa-check"></i></span>' : '';
        div.innerHTML = '<div class="msg-ava ' + (isMoi ? 'ghost' : '') + '">' + (isMoi ? '' : avaContent) + '</div>'
            + '<div style="display:flex;flex-direction:column;align-items:' + (isMoi ? 'flex-end' : 'flex-start') + ';gap:0.15rem;">'
            + '<div class="msg-bubble ' + (isMoi ? 'me' : 'other') + '">' + esc(contenu) + '</div>'
            + '<div class="msg-time ' + (isMoi ? 'me' : '') + '">' + time + statusHtml + '</div>'
            + '</div>';
        var typing = document.getElementById('typingIndicator');
        if (typing) { chatBody.insertBefore(div, typing); } else { chatBody.appendChild(div); }
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // ─── Polling — réception des nouveaux messages ──────────────
    var pollingTimer = null;
    var pusherActive = false;

    function startPolling() {
        if (pollingTimer || !CONV_ID) return;
        pollingTimer = setInterval(fetchNewMessages, 3000);
    }

    function fetchNewMessages() {
        if (!CONV_ID) return;
        fetch(APP_URL + '/messagerie/' + CONV_ID + '/messages/poll?last_id=' + LAST_MSG_ID, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (!data.messages || data.messages.length === 0) return;
            data.messages.forEach(function(msg) {
                if (msg.id > LAST_MSG_ID) {
                    LAST_MSG_ID = msg.id;
                    if (!msg.moi) {
                        appendMsg(msg.contenu, false, msg.date, msg.avatar, msg.initiale, false);
                        marquerLus();
                    }
                }
            });
        })
        .catch(function() {});
    }

    function marquerLus() {
        fetch(APP_URL + '/messagerie/' + CONV_ID + '/messages/lire', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        }).catch(function() {});
    }

    // ─── Pusher temps réel ──────────────────────────────────────
    window.addEventListener('load', function() {
        if (!CONV_ID || typeof Pusher === 'undefined') {
            startPolling();
            return;
        }
        try {
            var pusherClient = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
                cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
                forceTLS: true,
                authEndpoint: APP_URL + '/broadcasting/auth',
                auth: { headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } }
            });

            var channel = pusherClient.subscribe('private-conversation.' + CONV_ID);

            channel.bind('pusher:subscription_succeeded', function() {
                pusherActive = true;
            });

            channel.bind('pusher:subscription_error', function() {
                if (!pusherActive) startPolling();
            });

            channel.bind('message.envoye', function(data) {
                if (data.expediteur_id == AUTH_ID) return;
                if (data.id && data.id <= LAST_MSG_ID) return;
                if (data.id) LAST_MSG_ID = data.id;
                var time = data.date_envoi || new Date().toLocaleTimeString('fr', {hour:'2-digit', minute:'2-digit'});
                appendMsg(data.contenu, false, time, data.avatar, data.initiale, false);
                marquerLus();
            });

            // Démarrer le polling en secours si Pusher ne répond pas dans 6s
            setTimeout(function() {
                if (!pusherActive) startPolling();
            }, 6000);

        } catch(e) {
            startPolling();
        }
    });

    function hideTyping() { var el = document.getElementById('typingIndicator'); if (el) el.style.display = 'none'; }

    // ─── Recherche conversations ─────────────────────────────────
    document.getElementById('searchConv')?.addEventListener('input', function() {
        var q = this.value.toLowerCase();
        document.querySelectorAll('.conv-item').forEach(function(item) {
            var name = item.querySelector('.ci-name')?.textContent.toLowerCase() || '';
            item.style.display = name.includes(q) ? '' : 'none';
        });
    });

    function esc(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

    function toggleChatOptions(e) {
        e.stopPropagation();
        var panel = document.getElementById('chatOptionsPanel');
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    }
    document.addEventListener('click', function() {
        var panel = document.getElementById('chatOptionsPanel');
        if (panel) panel.style.display = 'none';
    });

    function supprimerMesMessages() {
        if (!confirm('Supprimer tous vos messages dans cette conversation ?')) return;
        fetch(APP_URL + '/messagerie/' + CONV_ID + '/messages/supprimer-miens', {
            method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        }).then(function(r) { return r.json(); }).then(function() { window.location.reload(); }).catch(function() {});
    }

    function supprimerDiscussion() {
        if (!confirm('Supprimer cette conversation définitivement ?')) return;
        fetch(APP_URL + '/messagerie/' + CONV_ID + '/supprimer', {
            method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        }).then(function(r) { return r.json(); }).then(function() { window.location.href = APP_URL + '/messagerie'; }).catch(function() {});
    }

    // ─── Toast notification ──────────────────────────────────────
    function showToast(msg, type) {
        var existing = document.getElementById('devhub-toast');
        if (existing) existing.remove();
        var t = document.createElement('div');
        t.id = 'devhub-toast';
        t.style.cssText = 'position:fixed;bottom:1.5rem;left:50%;transform:translateX(-50%);padding:0.65rem 1.25rem;border-radius:10px;font-size:0.85rem;font-weight:600;z-index:9999;box-shadow:0 4px 20px rgba(0,0,0,0.15);transition:opacity 0.3s;';
        t.style.background = type === 'error' ? '#FEE2E2' : '#D1FAE5';
        t.style.color      = type === 'error' ? '#DC2626'  : '#065F46';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(function() { t.style.opacity = '0'; setTimeout(function() { t.remove(); }, 300); }, 3500);
    }
</script>
</body>
</html>