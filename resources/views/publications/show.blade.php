<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $publication->titre }} – DevHub</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --purple:       #7C3AED;
            --purple-dark:  #6D28D9;
            --purple-light: #EDE9FE;
            --gray-50:      #F9FAFB;
            --gray-100:     #F3F4F6;
            --gray-200:     #E5E7EB;
            --gray-400:     #9CA3AF;
            --gray-500:     #6B7280;
            --gray-600:     #4B5563;
            --gray-700:     #374151;
            --gray-900:     #111827;
            --white:        #ffffff;
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        html, body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--white);
            color: var(--gray-900);
        }

        /* ─── PAGE WRAPPER pleine hauteur ─── */
        
        .main-content { display: flex;
            flex-direction: column;
            
            height: 100vh;  }

        /* ─── HEADER ─── */
        .pub-header {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0 1.5rem;
            height: 62px;
            border-bottom: 1px solid var(--gray-200);
            background: var(--white);
            position: relative;
            z-index: 50;
        }

        .btn-back {
            display: flex; align-items: center; gap: 0.4rem;
            color: var(--gray-500); text-decoration: none;
            font-size: 0.875rem; font-weight: 500;
            padding: 0.4rem 0.75rem; border-radius: 8px;
            border: 1px solid var(--gray-200);
            transition: all 0.18s; flex-shrink: 0;
            background: var(--white);
        }
        .btn-back:hover { color:var(--purple); border-color:var(--purple); background:var(--purple-light); }

        .header-author {
            display: flex; align-items: center; gap: 0.75rem;
            text-decoration: none; flex-shrink: 0;
        }

        .header-avatar {
            width: 38px; height: 38px; border-radius: 50%;
            background: var(--purple-light); color: var(--purple);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; font-weight: 700; overflow: hidden; flex-shrink: 0;
        }
        .header-avatar img { width:100%; height:100%; object-fit:cover; }

        .header-author-name  { font-size:0.9rem; font-weight:700; color:var(--gray-900); line-height:1.2; }
        .header-author-spec  { font-size:0.75rem; color:var(--gray-400); }

        /* Séparateur vertical */
        .header-divider {
            width: 1px; height: 28px; background: var(--gray-200); flex-shrink: 0;
        }

        /* Titre tronqué au centre */
        .header-pub-title {
            flex: 1; font-size: 0.875rem; font-weight: 600;
            color: var(--gray-600); white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis;
            min-width: 0;
        }

        /* Nom auteur tronqué */
        .header-author-name {
            max-width: 120px; overflow: hidden;
            text-overflow: ellipsis; white-space: nowrap;
        }

        /* Logo mobile (caché sur desktop) */
        .header-logo-mobile { display: none; }

        /* Actions header droite */
        .header-actions { display:flex; gap:0.5rem; flex-shrink:0; align-items:center; }

        .header-btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.4rem 0.9rem; border-radius: 8px;
            font-size: 0.82rem; font-weight: 600; cursor: pointer;
            transition: all 0.18s; text-decoration: none;
            border: none; font-family: 'DM Sans', sans-serif;
        }

        .btn-collab {
            background: var(--purple-light); color: var(--purple); border: 1.5px solid var(--purple);
        }
        .btn-collab:hover { background: var(--purple); color: white; }

        .btn-avis {
            background: #FEF9C3; color: #854D0E; border: 1.5px solid #F59E0B;
        }
        .btn-avis:hover { background: #F59E0B; color: white; }

        .btn-edit {
            background: transparent; color: var(--gray-500);
            border: 1px solid var(--gray-200);
        }
        .btn-edit:hover { background: var(--gray-100); color: var(--gray-900); }

        .btn-delete {
            background: transparent; color: #DC2626;
            border: 1px solid #FCA5A5; padding: 0.4rem 0.65rem;
        }
        .btn-delete:hover { background: #FEE2E2; }

        /* ─── BODY PRINCIPAL ─── */
        .pub-body {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 380px;
            overflow: hidden;
        }

        /* ─── COLONNE GAUCHE ─── */
        .pub-left {
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            border-right: 1px solid var(--gray-200);
            scrollbar-width: thin;
            scrollbar-color: var(--gray-200) transparent;
        }
        .pub-left::-webkit-scrollbar { width: 4px; }
        .pub-left::-webkit-scrollbar-thumb { background: var(--gray-200); border-radius: 2px; }

        .pub-left-inner { padding: 2rem 2.5rem; flex: 1; }

        /* Type + date */
        .pub-meta-row {
            display: flex; align-items: center; gap: 0.75rem;
            margin-bottom: 1rem; flex-wrap: wrap;
        }

        .pub-type-badge {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.25rem 0.75rem; border-radius: 20px;
            font-size: 0.75rem; font-weight: 600;
        }
        .badge-article { background:#DBEAFE; color:#1D4ED8; }
        .badge-idee    { background:#FEF9C3; color:#854D0E; }
        .badge-perso   { background:var(--purple-light); color:var(--purple); }
        .badge-collab  { background:#D1FAE5; color:#065F46; }

        .pub-meta-sep { color: var(--gray-300); font-size: 0.75rem; }
        .pub-meta-text { font-size: 0.78rem; color: var(--gray-400); }

        /* Titre */
        .pub-title {
            font-family: 'Urbanist', sans-serif;
            font-size: 1.75rem; font-weight: 800;
            color: var(--gray-900); line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        /* Description */
        .pub-description {
            font-size: 0.95rem; color: var(--gray-600);
            line-height: 1.85; white-space: pre-line;
            margin-bottom: 1.5rem;
        }

        /* Liens */
        .pub-links { display:flex; gap:0.65rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .pub-link-btn {
            display:inline-flex; align-items:center; gap:0.4rem;
            padding:0.45rem 0.9rem; border:1.5px solid var(--gray-200);
            border-radius:8px; font-size:0.82rem; font-weight:600;
            color:var(--gray-700); text-decoration:none; transition:all 0.2s;
        }
        .pub-link-btn:hover { border-color:var(--purple); color:var(--purple); }

        /* Spacer pour pousser les interactions en bas */
        .pub-spacer { flex: 1; }

        /* Barre interactions — collée en bas */
        .pub-interactions-bar {
            border-top: 1px solid var(--gray-200);
            padding: 0.85rem 2.5rem;
            display: flex; align-items: center; gap: 0.35rem;
            background: var(--white);
            flex-shrink: 0;
        }

        .int-btn {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.45rem 0.75rem; border: none; background: transparent;
            color: var(--gray-500); font-size: 0.85rem; font-weight: 500;
            cursor: pointer; border-radius: 8px; transition: all 0.18s;
            font-family: 'DM Sans', sans-serif;
        }
        .int-btn:hover { background: var(--gray-100); color: var(--gray-800); }
        .int-btn.liked { color: #E11D48; }
        .int-btn.liked i { color: #E11D48; }
        .int-btn.saved { color: var(--purple); }
        .int-btn.saved i { color: var(--purple); }
        .int-views {
            margin-left: auto; font-size: 0.78rem; color: var(--gray-400);
            display: flex; align-items: center; gap: 0.3rem;
        }

        /* ─── COLONNE DROITE ─── */
        .pub-right {
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: var(--white);
        }

        /* Images en haut de la sidebar */
        .pub-images-zone {
            flex-shrink: 0;
            overflow: hidden;
            max-height: 260px;
        }

        .pub-images-zone.has-images {
            border-bottom: 1px solid var(--gray-200);
        }

        .pub-img-single {
            width: 100%; height: 240px; object-fit: cover;
            display: block; transition: transform 0.4s ease;
        }
        .pub-img-single:hover { transform: scale(1.02); }

        .pub-img-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2px; height: 240px;
        }

        .pub-img-grid img {
            width: 100%; height: 100%; object-fit: cover;
            display: block; transition: transform 0.3s ease;
        }
        .pub-img-grid img:hover { transform: scale(1.04); }

        /* Section commentaires */
        .comments-section {
            flex: 1; display: flex; flex-direction: column; overflow: hidden;
        }

        .comments-header-bar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--gray-200);
            flex-shrink: 0;
        }

        .comments-label {
            font-family: 'Urbanist', sans-serif; font-size: 0.82rem;
            font-weight: 700; color: var(--gray-500);
            text-transform: uppercase; letter-spacing: 0.5px;
            display: flex; align-items: center; gap: 0.5rem;
        }

        .comments-badge {
            background: var(--purple-light); color: var(--purple);
            font-size: 0.7rem; font-weight: 700;
            padding: 0.1rem 0.5rem; border-radius: 20px;
        }

        /* Liste scrollable */
        .comments-list {
            flex: 1; overflow-y: auto; padding: 0.5rem 0;
            scrollbar-width: thin;
            scrollbar-color: var(--gray-200) transparent;
        }
        .comments-list::-webkit-scrollbar { width: 3px; }
        .comments-list::-webkit-scrollbar-thumb { background: var(--gray-200); border-radius: 2px; }

        /* Item commentaire */
        .comment-item {
            display: flex; gap: 0.6rem;
            padding: 0.7rem 1.25rem;
            transition: background 0.15s;
        }
        .comment-item:hover { background: var(--gray-50); }
        .comment-item.comment-new { animation: slideDown 0.3s ease; }

        @keyframes slideDown {
            from { opacity:0; transform:translateY(-10px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .comment-ava {
            width: 28px; height: 28px; border-radius: 50%;
            background: var(--purple-light); color: var(--purple);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.68rem; font-weight: 700;
            flex-shrink: 0; overflow: hidden;
        }
        .comment-ava img { width:100%; height:100%; object-fit:cover; }

        .comment-bubble {
            flex: 1; background: var(--gray-50);
            border-radius: 0 10px 10px 10px;
            padding: 0.5rem 0.75rem;
        }

        .comment-top {
            display: flex; align-items: center;
            justify-content: space-between; margin-bottom: 0.2rem;
        }
        .comment-name { font-size: 0.78rem; font-weight: 700; color: var(--gray-900); }
        .comment-time { font-size: 0.65rem; color: var(--gray-400); }
        .comment-text { font-size: 0.8rem; color: var(--gray-600); line-height: 1.5; }

        .comment-del {
            background: none; border: none; color: var(--gray-300);
            font-size: 0.65rem; cursor: pointer; padding: 0 0.25rem;
            border-radius: 3px; transition: color 0.15s; margin-left: 0.3rem;
        }
        .comment-del:hover { color: #EF4444; }

        /* Empty state */
        .comments-empty-state {
            padding: 2rem 1.25rem; text-align: center;
            color: var(--gray-400); font-size: 0.82rem;
        }
        .comments-empty-state i { font-size: 1.75rem; color: var(--gray-200); display: block; margin-bottom: 0.5rem; }

        /* Formulaire commentaire */
        .comment-form-bar {
            padding: 0.75rem 1.25rem;
            border-top: 1px solid var(--gray-200);
            background: var(--white);
            flex-shrink: 0;
        }

        .comment-form-wrap {
            display: flex; align-items: flex-end; gap: 0.55rem;
        }

        .comment-form-ava {
            width: 28px; height: 28px; border-radius: 50%;
            background: var(--purple-light); color: var(--purple);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.68rem; font-weight: 700; flex-shrink: 0; overflow: hidden;
        }
        .comment-form-ava img { width:100%; height:100%; object-fit:cover; }

        .comment-form-input {
            flex: 1; padding: 0.5rem 0.85rem;
            border: 1.5px solid var(--gray-200);
            border-radius: 20px; font-size: 0.82rem;
            font-family: 'DM Sans', sans-serif;
            resize: none; outline: none;
            background: var(--gray-50);
            transition: all 0.2s;
            max-height: 80px; overflow-y: auto;
            line-height: 1.4;
        }
        .comment-form-input:focus {
            border-color: var(--purple); background: var(--white);
            box-shadow: 0 0 0 3px rgba(124,58,237,0.06);
        }

        .comment-form-send {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--purple); color: white; border: none;
            cursor: pointer; display: flex; align-items: center;
            justify-content: center; font-size: 0.75rem;
            flex-shrink: 0; transition: all 0.18s;
        }
        .comment-form-send:hover { background: var(--purple-dark); transform: scale(1.08); }

        .login-prompt {
            padding: 0.75rem 1.25rem; text-align: center;
            border-top: 1px solid var(--gray-200);
        }
        .login-prompt a { font-size: 0.82rem; color: var(--purple); font-weight: 600; text-decoration: none; }

        /* ─── MODAL AVIS ─── */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.4); z-index: 200;
            align-items: center; justify-content: center;
        }
        .modal-overlay.active { display: flex; }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: var(--white); border-radius: 16px;
            padding: 1.75rem; width: 440px; max-width: 95vw;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        .modal-title {
            font-family: 'Urbanist', sans-serif; font-size: 1.1rem;
            font-weight: 700; margin-bottom: 1.25rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-close { background: none; border: none; cursor: pointer; color: var(--gray-400); font-size: 1rem; }
        .stars-row { display: flex; gap: 0.4rem; margin-bottom: 1rem; }
        .star-btn { background: none; border: none; font-size: 1.5rem; color: var(--gray-300); cursor: pointer; transition: color 0.15s; padding: 0; }
        .star-btn.active { color: #F59E0B; }
        .avis-textarea {
            width: 100%; padding: 0.65rem 0.9rem;
            border: 1.5px solid var(--gray-200); border-radius: 10px;
            font-size: 0.875rem; font-family: 'DM Sans', sans-serif;
            resize: none; outline: none; height: 80px; margin-bottom: 1rem;
        }
        .avis-textarea:focus { border-color: var(--purple); }
        .avis-submit-btn {
            width: 100%; padding: 0.7rem; background: var(--purple);
            color: white; border: none; border-radius: 10px;
            font-size: 0.9rem; font-weight: 600; cursor: pointer;
            transition: background 0.2s;
        }
        .avis-submit-btn:hover { background: var(--purple-dark); }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 992px) {
            .page-wrapper { height: auto; overflow: visible; }
            .pub-body {
                grid-template-columns: 1fr;
                overflow: visible;
            }
            .pub-left { overflow: visible; border-right: none; border-bottom: 1px solid var(--gray-200); }
            .pub-left-inner { padding: 1.25rem 1.25rem 0; }
            .pub-interactions-bar { padding: 0.75rem 1.25rem; }
            .pub-right { max-height: none; }
            .pub-images-zone { max-height: none; }
            .pub-img-single { height: 200px; }
            .pub-img-grid { height: 180px; }
            .comments-section { max-height: 500px; }
            .comments-list { max-height: 300px; }
        }

        @media (max-width: 768px) {
            .pub-header { padding: 0 1rem; height: 56px; gap: 0.65rem; }
            .header-pub-title { display: none; }
            .header-divider { display: none; }
            .pub-title { font-size: 1.35rem; }
            .pub-left-inner { padding: 1rem; }
        }

        @media (max-width: 576px) {
            .pub-header { gap: 0.5rem; }
            .header-author { display: none; }
            .header-divider { display: none; }
            .btn-back span { display: none; }
            .btn-back { padding: 0.4rem 0.5rem; }
            .header-logo-mobile {
                display: flex; align-items: center; gap: 0.4rem;
                font-family: 'Urbanist', sans-serif; font-size: 1rem;
                font-weight: 800; color: var(--purple); flex: 1;
                text-decoration: none;
            }
            .header-logo-mobile img { height: 22px; }
        }
    </style>
</head>
<body>
    <div class="main-content">

        <!-- ─── HEADER ─── -->
        <header class="pub-header">

            {{-- Retour --}}
            <a href="{{ route('dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>

            {{-- Logo mobile uniquement --}}
            <a href="{{ route('dashboard') }}" class="header-logo-mobile">
                <img src="{{ asset('img/logo-devhub.png') }}" alt="DevHub">
            </a>

            {{-- Auteur --}}
            <a href="{{ route('profil.show', $publication->auteur->id) }}" class="header-author">
                <div class="header-avatar">
                    @if($publication->auteur->photo)
                        <img src="{{ $publication->auteur->photo_url }}" alt="">
                    @else
                        {{ strtoupper(substr($publication->auteur->prenom, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <div class="header-author-name">{{ $publication->auteur->prenom }} {{ $publication->auteur->nom }}</div>
                    <div class="header-author-spec">{{ $publication->auteur->specialite ?? ucfirst($publication->auteur->role) }}</div>
                </div>
            </a>

            <div class="header-divider"></div>

            {{-- Titre tronqué --}}
            <div class="header-pub-title">{{ $publication->titre }}</div>

            {{-- Actions droite --}}
            <div class="header-actions">

                {{-- Bouton Collaborer --}}
                @auth
                    @if($publication->type === 'projet_collaboratif' && $publication->auteur_id !== Auth::id())
                        @if($dejaMembre)
                            <span class="header-btn" style="background:#D1FAE5;color:#065F46;border:1.5px solid #6EE7B7;cursor:default;">
                                <i class="fas fa-check-circle"></i> Membre
                            </span>
                        @elseif($dejaCandidat)
                            <span class="header-btn" style="background:#FEF9C3;color:#92400E;border:1.5px solid #FCD34D;cursor:default;">
                                <i class="fas fa-clock"></i> Candidature envoyée
                            </span>
                        @else
                            <button class="header-btn btn-collab collaboration-btn"
                                    data-id="{{ $publication->id }}"
                                    data-titre="{{ $publication->titre }}">
                                <i class="fas fa-handshake"></i> Collaborer
                            </button>
                        @endif
                    @endif

                    {{-- Bouton Avis --}}
                    @if(in_array($publication->type, ['projet_personnel','projet_collaboratif']) && $publication->auteur_id !== Auth::id())
                        @php $dejaAvis = $publication->avis->where('auteur_id', Auth::id())->first(); @endphp
                        @if(!$dejaAvis)
                            <button class="header-btn btn-avis" id="btnOpenAvis">
                                <i class="fas fa-star"></i> Avis
                            </button>
                        @endif
                    @endif

                    {{-- Clôturer le projet collaboratif --}}
                    @if($publication->type === 'projet_collaboratif' && $publication->projetCollaboratif?->phase === 'en_cours' && $publication->auteur_id === Auth::id())
                        <button type="button" class="header-btn" id="btnTerminerCollab"
                            style="background:#FEE2E2;color:#DC2626;border:1.5px solid #FCA5A5;">
                            <i class="fas fa-flag-checkered"></i> Clôturer
                        </button>
                    @endif

                    {{-- Modifier / Supprimer --}}
                    @if($publication->auteur_id === Auth::id())
                        <a href="{{ route('publications.edit', $publication->id) }}" class="header-btn btn-edit">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <form action="{{ route('publications.destroy', $publication->id) }}" method="POST"
                            onsubmit="return confirm('Supprimer ?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="header-btn btn-delete">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </header>

        <!-- ─── BODY 2 COLONNES ─── -->
        <div class="pub-body">

            <!-- ─── GAUCHE : titre + description + interactions ─── -->
            <div class="pub-left">
                <div class="pub-left-inner">

                    {{-- Type + date + vues --}}
                    <div class="pub-meta-row">
                        @php
                            $types = [
                                'article'             => ['Article',      'badge-article', 'fa-newspaper'],
                                'idee'                => ['Idée',         'badge-idee',    'fa-lightbulb'],
                                'projet_personnel'    => ['Projet perso', 'badge-perso',   'fa-folder'],
                                'projet_collaboratif' => ['Collaboratif', 'badge-collab',  'fa-users'],
                            ];
                            $t = $types[$publication->type] ?? [$publication->type, '', 'fa-file'];
                        @endphp
                        <span class="pub-type-badge {{ $t[1] }}">
                            <i class="fas {{ $t[2] }}"></i> {{ $t[0] }}
                        </span>
                        <span class="pub-meta-sep">·</span>
                        <span class="pub-meta-text">{{ $publication->date_publication->format('d M Y') }}</span>
                        <span class="pub-meta-sep">·</span>
                        <span class="pub-meta-text">
                            <i class="far fa-eye me-1"></i>{{ $publication->nb_vues }} vues
                        </span>
                        @if($publication->type === 'projet_collaboratif' && $publication->projetCollaboratif)
                            @php
                                $pc = $publication->projetCollaboratif;
                                $nbCandidats = \App\Models\Candidature::where('projet_collaboratif_id', $publication->id)
                                    ->where('statut', 'en_attente')->count();
                            @endphp
                            <span class="pub-meta-sep">·</span>
                            <span class="pub-meta-text" title="Membres actuels / places totales">
                                <i class="fas fa-users me-1"></i>{{ $pc->taille_equipe_actuelle }}/{{ $pc->nombre_collaborateurs }} membres
                            </span>
                            <span class="pub-meta-sep">·</span>
                            <span class="pub-meta-text" title="Candidatures en attente">
                                <i class="fas fa-inbox me-1"></i>{{ $nbCandidats }} candidature{{ $nbCandidats > 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>

                    {{-- Titre --}}
                    <h1 class="pub-title">{{ $publication->titre }}</h1>

                    {{-- Contenu --}}
                    @if($publication->contenu)
                        <div class="pub-description">{{ $publication->contenu }}</div>
                    @endif

                    {{-- Liens projet + images --}}
                    @php
                        $projet = $publication->projetPersonnel ?? $publication->projetCollaboratif ?? null;
                        $hasLinks = $projet && (!empty($projet->url_repo) || !empty($projet->url_demo));
                        $hasMedias = $publication->medias->isNotEmpty();
                    @endphp
                    @if($hasLinks || $hasMedias)
                        <div class="pub-links">
                            @if($projet && !empty($projet->url_repo))
                                <a href="{{ $projet->url_repo }}" target="_blank" class="pub-link-btn">
                                    <i class="fas fa-code-branch"></i> Dépôt de projets
                                </a>
                            @endif
                            @if($hasMedias)
                                <button type="button" id="btnVoirImages" class="pub-link-btn"
                                        style="background:var(--purple-light);border-color:var(--purple);color:var(--purple);cursor:pointer;border:1.5px solid var(--purple);">
                                    <i class="fas fa-images"></i>
                                    Voir les images
                                    <span style="background:var(--purple);color:white;font-size:0.68rem;padding:0.1rem 0.45rem;border-radius:20px;margin-left:0.25rem;">
                                        {{ $publication->medias->count() }}
                                    </span>
                                </button>
                            @endif
                            @if($projet && !empty($projet->url_demo))
                                <a href="{{ $projet->url_demo }}" target="_blank" class="pub-link-btn">
                                    <i class="fas fa-external-link-alt"></i> Démo live
                                </a>
                            @endif
                        </div>
                    @endif
                    
                </div>

                {{-- Spacer --}}
                <div class="pub-spacer"></div>

                {{-- Barre interactions collée en bas --}}
                <div class="pub-interactions-bar">
                    @auth
                        <button class="int-btn like-btn {{ $publication->likes->contains('utilisateur_id', Auth::id()) ? 'liked' : '' }}"
                                data-id="{{ $publication->id }}">
                            <i class="{{ $publication->likes->contains('utilisateur_id', Auth::id()) ? 'fas' : 'far' }} fa-heart"></i>
                            <span class="like-count">{{ $publication->nb_likes }}</span>
                        </button>

                        <button class="int-btn favori-btn {{ $publication->favoris->contains('utilisateur_id', Auth::id()) ? 'saved' : '' }}"
                                data-id="{{ $publication->id }}">
                            <i class="{{ $publication->favoris->contains('utilisateur_id', Auth::id()) ? 'fas' : 'far' }} fa-bookmark"></i>
                        </button>

                        <button class="int-btn" onclick="document.getElementById('commentInput').focus()">
                            <i class="far fa-comment"></i>
                            <span id="commentCountBar">{{ $publication->nb_commentaires }}</span>
                        </button>
                    @endauth

                    <span class="int-views">
                        <i class="far fa-eye"></i> {{ $publication->nb_vues }}
                    </span>
                </div>
            </div>

            <!-- ─── DROITE : images + commentaires ─── -->
            <div class="pub-right">
                {{-- Commentaires --}}
                <div class="comments-section">

                    <div class="comments-header-bar">
                        <div class="comments-label">
                            <i class="fas fa-comments" style="color:var(--purple);"></i>
                            Commentaires
                            <span class="comments-badge" id="commentsCount">{{ $publication->commentaires->count() }}</span>
                        </div>
                    </div>

                    <div class="comments-list" id="commentsList">
                        @forelse($publication->commentaires->whereNull('id_parent')->sortByDesc('date_commentaire') as $c)
                            <div class="comment-item" id="c-{{ $c->id }}">
                                <div class="comment-ava">
                                    @if($c->utilisateur->photo)
                                        <img src="{{ $c->utilisateur->photo_url }}" alt="">
                                    @else
                                        {{ strtoupper(substr($c->utilisateur->prenom, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="comment-bubble">
                                    <div class="comment-top">
                                        <span class="comment-name">{{ $c->utilisateur->prenom }} {{ $c->utilisateur->nom }}</span>
                                        <div style="display:flex;align-items:center;">
                                            <span class="comment-time">{{ $c->date_commentaire->diffForHumans(null, true) }}</span>
                                            @auth
                                                @if($c->utilisateur_id === Auth::id())
                                                    <form action="{{ route('commentaires.destroy', $c->id) }}" method="POST"
                                                        onsubmit="return confirm('Supprimer ?')" style="display:inline;">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="comment-del" title="Supprimer">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                    </div>
                                    <div class="comment-text">{{ $c->contenu }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="comments-empty-state" id="commentsEmpty">
                                <i class="fas fa-comment-slash"></i>
                                Aucun commentaire — soyez le premier !
                            </div>
                        @endforelse
                    </div>

                    {{-- Formulaire commentaire --}}
                    @auth
                        <div class="comment-form-bar">
                            <div class="comment-form-wrap">
                                <div class="comment-form-ava">
                                    @if(Auth::user()->photo)
                                        <img src="{{ Auth::user()->photo_url }}" alt="">
                                    @else
                                        {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}
                                    @endif
                                </div>
                                <textarea id="commentInput"
                                        class="comment-form-input"
                                        placeholder="Ajouter un commentaire..."
                                        rows="1"></textarea>
                                <button type="button" id="commentSend" class="comment-form-send">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="login-prompt">
                            <a href="{{ route('login') }}">Connectez-vous pour commenter</a>
                        </div>
                    @endauth

                </div>
            </div>
        </div>
    </div>

    {{-- Modal Avis --}}
    @auth
    @if(in_array($publication->type, ['projet_personnel','projet_collaboratif']) && $publication->auteur_id !== Auth::id())
        @php $dejaAvis = $publication->avis->where('auteur_id', Auth::id())->first(); @endphp
        @if(!$dejaAvis)
            <div class="modal-overlay" id="avisOverlay">
                <div class="modal-box">
                    <div class="modal-title">
                        <span><i class="fas fa-star me-2" style="color:#F59E0B;"></i>Donner un avis</span>
                        <button class="modal-close" id="closeAvis"><i class="fas fa-times"></i></button>
                    </div>
                    <form action="{{ route('avis.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="publication_id" value="{{ $publication->id }}">
                        <input type="hidden" name="note" id="noteValue" value="0">
                        <div class="stars-row">
                            @for($i=1;$i<=5;$i++)
                                <button type="button" class="star-btn" data-note="{{ $i }}">
                                    <i class="fas fa-star"></i>
                                </button>
                            @endfor
                        </div>
                        <textarea name="commentaire" class="avis-textarea"
                                placeholder="Votre commentaire (optionnel)..."></textarea>
                        <button type="submit" class="avis-submit-btn">
                            <i class="fas fa-star me-2"></i>Soumettre l'avis
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @endif
    @endauth

    {{-- Modal Images --}}
    @if($publication->medias->isNotEmpty())
    <div class="modal-overlay" id="imagesOverlay">
        <div style="background:white;border-radius:16px;width:90vw;max-width:900px;max-height:90vh;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.25);display:flex;flex-direction:column;">

            <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.5rem;border-bottom:1px solid var(--gray-200);">
                <div style="font-family:'Urbanist',sans-serif;font-size:1rem;font-weight:700;color:var(--gray-900);">
                    <i class="fas fa-images me-2" style="color:var(--purple);"></i>
                    Images du projet
                    <span style="font-size:0.78rem;color:var(--gray-400);font-weight:400;margin-left:0.5rem;">
                        {{ $publication->medias->count() }} photo(s)
                    </span>
                </div>
                <button id="closeImages"
                        style="background:none;border:none;cursor:pointer;color:var(--gray-400);font-size:1.1rem;padding:0.25rem 0.5rem;border-radius:6px;transition:color 0.2s;"
                        onmouseover="this.style.color='var(--gray-900)'"
                        onmouseout="this.style.color='var(--gray-400)'">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div style="overflow-y:auto;padding:1.25rem;">
                @if($publication->medias->count() >= 3 && $publication->type === 'projet_personnel')
                    {{-- Grille 3 images --}}
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.75rem;">
                        @foreach($publication->medias as $media)
                            <div style="border-radius:12px;overflow:hidden;cursor:pointer;"
                                onclick="openSingleImage('{{ $media->url }}')">
                                <img src="{{ $media->url }}"
                                    alt="image {{ $loop->iteration }}"
                                    style="width:100%;height:200px;object-fit:cover;display:block;transition:transform 0.3s ease;"
                                    onmouseover="this.style.transform='scale(1.04)'"
                                    onmouseout="this.style.transform='scale(1)'">
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Image unique pleine largeur --}}
                    @foreach($publication->medias as $media)
                        <div style="border-radius:12px;overflow:hidden;margin-bottom:0.75rem;cursor:pointer;"
                            onclick="openSingleImage('{{ $media->url }}')">
                            <img src="{{ $media->url }}"
                                alt="image"
                                style="width:100%;max-height:500px;object-fit:contain;display:block;background:#f1f1f1;">
                        </div>
                    @endforeach
                @endif

                <p style="font-size:0.75rem;color:var(--gray-400);text-align:center;margin-top:0.5rem;">
                    Cliquez sur une image pour l'agrandir
                </p>
            </div>
        </div>
    </div>

    {{-- Viewer image unique --}}
    <div class="modal-overlay" id="singleImageViewer" style="z-index:600;"
        onclick="this.classList.remove('open')">
        <div style="position:relative;" onclick="event.stopPropagation()">
            <img id="singleImageSrc" src="" alt=""
                style="max-width:92vw;max-height:90vh;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,0.4);display:block;">
            <button onclick="document.getElementById('singleImageViewer').classList.remove('open')"
                    style="position:absolute;top:-14px;right:-14px;width:32px;height:32px;border-radius:50%;background:white;border:none;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,0.2);font-size:0.85rem;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Modal Candidature Collaboration -->
    <div class="modal fade" id="modalCollab" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
            <div class="modal-content" style="border-radius:16px;border:none;">
                <div class="modal-header" style="border-bottom:1px solid #F3F4F6;padding:1.25rem 1.5rem;">
                    <div>
                        <h5 style="font-family:'Urbanist',sans-serif;font-weight:700;font-size:1.05rem;margin:0;">Rejoindre ce projet</h5>
                        <p id="collabModalTitre" style="font-size:0.78rem;color:#9CA3AF;margin:0.2rem 0 0;"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:1.5rem;">
                    <label style="display:block;font-size:0.82rem;font-weight:600;color:#374151;margin-bottom:0.65rem;">
                        Quel poste souhaitez-vous occuper ? <span style="color:#EF4444;">*</span>
                    </label>
                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:0.5rem;margin-bottom:1.25rem;" id="collabTagsGrid">
                        @foreach([
                            ['Développeur Frontend','fa-laptop-code'],
                            ['Développeur Backend','fa-server'],
                            ['Développeur Full-Stack','fa-code'],
                            ['Designer UI/UX','fa-paint-brush'],
                            ['Chef de projet','fa-tasks'],
                            ['Testeur QA','fa-bug'],
                            ['Autre','fa-ellipsis-h'],
                        ] as $tag)
                            <div class="collab-tag" data-poste="{{ $tag[0] }}"
                                style="display:flex;align-items:center;gap:0.5rem;padding:0.6rem 0.85rem;border:2px solid #E5E7EB;border-radius:10px;cursor:pointer;font-size:0.82rem;font-weight:500;color:#4B5563;transition:all 0.18s;">
                                <i class="fas {{ $tag[1] }}"></i> {{ $tag[0] }}
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" id="collabPosteSelected" value="">
                    <div id="collabPosteError" style="display:none;color:#EF4444;font-size:0.75rem;margin-bottom:0.75rem;">
                        Veuillez sélectionner un poste.
                    </div>
                    <label style="display:block;font-size:0.82rem;font-weight:600;color:#374151;margin-bottom:0.4rem;">
                        Message de motivation <span style="font-weight:400;color:#9CA3AF;">(optionnel)</span>
                    </label>
                    <textarea id="collabMotivation" rows="4"
                            placeholder="Présentez vos compétences..."
                            style="width:100%;padding:0.7rem 1rem;border:1.5px solid #E5E7EB;border-radius:10px;font-size:0.875rem;resize:none;outline:none;"></textarea>
                    <div id="collabMessage" style="display:none;border-radius:8px;padding:0.65rem 1rem;font-size:0.85rem;margin-top:0.75rem;"></div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #F3F4F6;padding:1rem 1.5rem;">
                    <button type="button" data-bs-dismiss="modal"
                            style="padding:0.6rem 1.5rem;border:1.5px solid #E5E7EB;border-radius:10px;background:transparent;color:#6B7280;font-weight:500;cursor:pointer;">
                        Annuler
                    </button>
                    <button id="btnSubmitCollab"
                            style="padding:0.6rem 1.5rem;background:#7C3AED;color:white;border:none;border-radius:10px;font-weight:600;cursor:pointer;margin-left:0.75rem;">
                        <i class="fas fa-paper-plane"></i> Envoyer ma candidature
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const APP_URL = "{{ url('') }}";
        const CSRF    = document.querySelector('meta[name="csrf-token"]').content;
        const PUB_ID  = {{ $publication->id }};

        // ─── Like ───
        document.querySelector('.like-btn')?.addEventListener('click', function() {
            fetch(APP_URL + '/likes/' + this.dataset.id, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                this.classList.toggle('liked', data.liked);
                this.querySelector('i').className = data.liked ? 'fas fa-heart' : 'far fa-heart';
                this.querySelector('.like-count').textContent = data.count;
            }).catch(() => {});
        });

        // ─── Favori ───
        document.querySelector('.favori-btn')?.addEventListener('click', function() {
            fetch(APP_URL + '/favoris/' + this.dataset.id, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                this.classList.toggle('saved', data.saved);
                this.querySelector('i').className = data.saved ? 'fas fa-bookmark' : 'far fa-bookmark';
            }).catch(() => {});
        });

        // ─── Modal Avis ───
        const btnAvis    = document.getElementById('btnOpenAvis');
        const avisOverlay= document.getElementById('avisOverlay');
        const closeAvis  = document.getElementById('closeAvis');

        btnAvis?.addEventListener('click', () => avisOverlay?.classList.add('active'));
        closeAvis?.addEventListener('click', () => avisOverlay?.classList.remove('active'));
        avisOverlay?.addEventListener('click', e => { if (e.target === avisOverlay) avisOverlay.classList.remove('active'); });

        // Étoiles
        document.querySelectorAll('.star-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const note = parseInt(btn.dataset.note);
                document.getElementById('noteValue').value = note;
                document.querySelectorAll('.star-btn').forEach((s, i) => s.classList.toggle('active', i < note));
            });
        });

        // ─── Collaboration ───
        document.querySelector('.collaboration-btn')?.addEventListener('click', function() {
            if (typeof openCollabModal === 'function') {
                openCollabModal(this.dataset.id, this.dataset.titre);
            }
        });

        // ─── Commentaire AJAX ───
        const commentInput = document.getElementById('commentInput');
        const commentSend  = document.getElementById('commentSend');
        const commentsList = document.getElementById('commentsList');

        commentInput?.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 80) + 'px';
        });

        commentInput?.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendComment(); }
        });
        commentSend?.addEventListener('click', sendComment);

        function sendComment() {
            const contenu = commentInput?.value.trim();
            if (!contenu) return;

            commentSend.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            commentSend.disabled  = true;

            fetch(APP_URL + '/commentaires', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ publication_id: PUB_ID, contenu })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Supprimer l'état vide
                    document.getElementById('commentsEmpty')?.remove();

                    // Créer le commentaire et l'insérer EN HAUT
                    const div = document.createElement('div');
                    div.className = 'comment-item comment-new';
                    div.innerHTML = `
                        <div class="comment-ava">
                            ${data.comment.avatar
                                ? `<img src="${APP_URL}/storage/${data.comment.avatar}" alt="">`
                                : `<span>${data.comment.initiale}</span>`
                            }
                        </div>
                        <div class="comment-bubble">
                            <div class="comment-top">
                                <span class="comment-name">${data.comment.auteur}</span>
                                <span class="comment-time">à l'instant</span>
                            </div>
                            <div class="comment-text">${esc(data.comment.contenu)}</div>
                        </div>`;

                    commentsList.insertBefore(div, commentsList.firstChild);

                    // MAJ compteurs
                    const cnt = document.getElementById('commentsCount');
                    const bar = document.getElementById('commentCountBar');
                    if (cnt) cnt.textContent = (parseInt(cnt.textContent)||0) + 1;
                    if (bar) bar.textContent = (parseInt(bar.textContent)||0) + 1;

                    commentInput.value = '';
                    commentInput.style.height = 'auto';
                }
            })
            .catch(() => {})
            .finally(() => {
                commentSend.innerHTML = '<i class="fas fa-paper-plane"></i>';
                commentSend.disabled  = false;
            });
        }

        function esc(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        }
        // ─── Modal images ───
        document.getElementById('btnVoirImages')?.addEventListener('click',
            () => document.getElementById('imagesOverlay')?.classList.add('open'));

        document.getElementById('closeImages')?.addEventListener('click',
            () => document.getElementById('imagesOverlay')?.classList.remove('open'));

        document.getElementById('imagesOverlay')?.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('open');
        });

        function openSingleImage(src) {
            document.getElementById('singleImageSrc').src = src;
            document.getElementById('singleImageViewer').classList.add('open');
        }
        // ─── Modal Collaboration ───
        let collabPubId = null;

        function openCollabModal(id, titre) {
            collabPubId = id;
            document.getElementById('collabModalTitre').textContent = titre || 'ce projet';
            document.getElementById('collabPosteSelected').value = '';
            document.getElementById('collabMotivation').value = '';
            document.getElementById('collabPosteError').style.display = 'none';
            document.getElementById('collabMessage').style.display = 'none';
            document.querySelectorAll('.collab-tag').forEach(t => {
                t.style.borderColor = '#E5E7EB';
                t.style.background  = 'white';
                t.style.color       = '#4B5563';
            });
            const btn = document.getElementById('btnSubmitCollab');
            if (btn) { btn.innerHTML = '<i class="fas fa-paper-plane"></i> Envoyer ma candidature'; btn.disabled = false; }
            new bootstrap.Modal(document.getElementById('modalCollab')).show();
        }

        // Bouton collaborer dans le header
        document.querySelector('.collaboration-btn')?.addEventListener('click', function() {
            openCollabModal(this.dataset.id, this.dataset.titre);
        });

        // Sélection tag
        document.querySelectorAll('.collab-tag').forEach(tag => {
            tag.addEventListener('click', () => {
                document.querySelectorAll('.collab-tag').forEach(t => {
                    t.style.borderColor = '#E5E7EB';
                    t.style.background  = 'white';
                    t.style.color       = '#4B5563';
                });
                tag.style.borderColor = '#7C3AED';
                tag.style.background  = '#7C3AED';
                tag.style.color       = 'white';
                document.getElementById('collabPosteSelected').value = tag.dataset.poste;
                document.getElementById('collabPosteError').style.display = 'none';
            });
        });

        // Soumission
        document.getElementById('btnSubmitCollab')?.addEventListener('click', () => {
            const poste      = document.getElementById('collabPosteSelected').value;
            const motivation = document.getElementById('collabMotivation').value.trim();
            const msgBox     = document.getElementById('collabMessage');
            const btn        = document.getElementById('btnSubmitCollab');

            if (!poste) { document.getElementById('collabPosteError').style.display = 'block'; return; }
            if (!collabPubId) return;

            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...';
            btn.disabled  = true;

            fetch(APP_URL + '/candidatures', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    projet_collaboratif_id: collabPubId,
                    poste_souhaite:         poste,
                    motivation:             motivation,
                }),
            })
            .then(r => r.json())
            .then(data => {
                msgBox.style.display = 'block';
                if (data.success) {
                    msgBox.style.background = '#D1FAE5';
                    msgBox.style.color      = '#065F46';
                    msgBox.innerHTML        = '<i class="fas fa-check-circle me-2"></i>' + data.message;
                    setTimeout(() => bootstrap.Modal.getInstance(document.getElementById('modalCollab'))?.hide(), 2000);
                } else {
                    msgBox.style.background = '#FEE2E2';
                    msgBox.style.color      = '#991B1B';
                    msgBox.innerHTML        = '<i class="fas fa-exclamation-circle me-2"></i>' + (data.message || 'Erreur.');
                    btn.innerHTML           = '<i class="fas fa-paper-plane"></i> Envoyer ma candidature';
                    btn.disabled            = false;
                }
            })
            .catch(() => {
                msgBox.style.display    = 'block';
                msgBox.style.background = '#FEE2E2';
                msgBox.style.color      = '#991B1B';
                msgBox.innerHTML        = '<i class="fas fa-exclamation-circle me-2"></i>Erreur de connexion.';
                btn.innerHTML           = '<i class="fas fa-paper-plane"></i> Envoyer ma candidature';
                btn.disabled            = false;
            });
        });

    </script>

    {{-- Modal clôture projet collaboratif --}}
    @if($publication->type === 'projet_collaboratif' && $publication->projetCollaboratif?->phase === 'en_cours')
        @if($publication->auteur_id === Auth::id() || $publication->projetCollaboratif?->mentor_id === Auth::id())
        <div id="overlayTerminerCollab"
             style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:9999;align-items:center;justify-content:center;">
            <div style="background:white;border-radius:16px;padding:2rem;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                    <div style="width:40px;height:40px;border-radius:10px;background:#FEE2E2;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-flag-checkered" style="color:#DC2626;"></i>
                    </div>
                    <div>
                        <div style="font-family:'Urbanist',sans-serif;font-weight:800;font-size:1rem;color:#111827;">Clôturer le projet ?</div>
                        <div style="font-size:0.78rem;color:#9CA3AF;">Cette action est irréversible.</div>
                    </div>
                </div>
                <p style="font-size:0.85rem;color:#374151;line-height:1.6;margin-bottom:1.5rem;">
                    La discussion de groupe sera désactivée (lecture seule) et tous les membres seront notifiés. Le projet passera en statut <strong>Terminé</strong>.
                </p>
                <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                    <button onclick="document.getElementById('overlayTerminerCollab').style.display='none'"
                            style="padding:0.5rem 1.25rem;border:1px solid #E5E7EB;border-radius:8px;background:white;color:#6B7280;font-weight:600;cursor:pointer;">
                        Annuler
                    </button>
                    <form id="formTerminerCollab"
                          action="{{ route('publications.terminer-collab', $publication->id) }}"
                          method="POST" style="display:inline;">
                        @csrf @method('PUT')
                        <button type="submit"
                                style="padding:0.5rem 1.25rem;border:none;border-radius:8px;background:#DC2626;color:white;font-weight:600;cursor:pointer;">
                            <i class="fas fa-flag-checkered me-1"></i> Confirmer la clôture
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('btnTerminerCollab')?.addEventListener('click', function() {
                document.getElementById('overlayTerminerCollab').style.display = 'flex';
            });
        </script>
        @endif
    @endif
</body>
</html>