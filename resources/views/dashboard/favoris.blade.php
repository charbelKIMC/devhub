@extends('layouts.app')
@section('title', 'Mes favoris – DevHub')
@section('secondary_nav', true)

@section('styles')
    <style>
        /* ─── HEADER ─── */
        .fav-page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;
        }
        .fav-page-title {
            font-family: 'Urbanist', sans-serif;
            font-size: 1.1rem; font-weight: 800; color: var(--gray-900);
        }
        .fav-page-sub { font-size: 0.82rem; color: var(--gray-400); margin-top: 0.15rem; }

        /* ─── STATS ─── */
        .fav-stats {
            display: grid; grid-template-columns: repeat(4,1fr);
            gap: 0.75rem; margin-bottom: 1.25rem;
        }
        .fs-card {
            background: white; border: 1px solid var(--gray-200);
            border-radius: 12px; padding: 0.85rem 1rem;
            display: flex; align-items: center; gap: 0.75rem;
        }
        .fs-icon {
            width: 36px; height: 36px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; flex-shrink: 0;
        }
        .fs-val { font-family: 'Urbanist', sans-serif; font-size: 1.15rem; font-weight: 800; color: var(--gray-900); line-height: 1; }
        .fs-label { font-size: 0.72rem; color: var(--gray-400); margin-top: 0.1rem; }

        /* ─── TABS ─── */
        .fav-tabs {
            display: flex; gap: 0.35rem; margin-bottom: 1rem; flex-wrap: wrap;
        }
        .fav-tab {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.4rem 0.9rem; background: transparent; color: var(--gray-500);
            border: 1.5px solid var(--gray-200); border-radius: 8px;
            font-size: 0.82rem; font-weight: 500; cursor: pointer;
            transition: all 0.18s; font-family: 'DM Sans', sans-serif;
        }
        .fav-tab:hover { border-color: var(--purple); color: var(--purple); background: var(--purple-light); }
        .fav-tab.active { background: var(--purple-light); color: var(--purple); border-color: var(--purple); font-weight: 700; }
        .fav-tab .tab-count {
            background: var(--purple); color: white; font-size: 0.6rem; font-weight: 700;
            min-width: 16px; height: 16px; border-radius: 8px;
            display: inline-flex; align-items: center; justify-content: center; padding: 0 4px;
        }
        .fav-tab:not(.active) .tab-count { background: var(--gray-300); }

        /* ─── GRILLE ─── */
        .fav-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.85rem;
        }

        /* ─── CARD ─── */
        .fav-card {
            background: white; border: 1px solid var(--gray-200);
            border-radius: 14px; overflow: hidden;
            transition: all 0.2s; display: flex; flex-direction: column;
            cursor: pointer;
        }
        .fav-card:hover {
            border-color: var(--purple);
            box-shadow: 0 6px 20px rgba(124,58,237,0.1);
            transform: translateY(-2px);
        }

        /* Image cover */
        .fav-card-img {
            width: 100%; height: 140px; object-fit: cover;
            display: block; transition: transform 0.3s ease;
        }
        .fav-card:hover .fav-card-img { transform: scale(1.03); }

        .fav-card-img-placeholder {
            width: 100%; height: 140px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem;
        }

        /* Corps */
        .fav-card-body { padding: 0.9rem 1rem; flex: 1; display: flex; flex-direction: column; gap: 0.5rem; }

        .fav-card-top {
            display: flex; align-items: center; justify-content: space-between;
        }

        .fav-type-badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.2rem 0.55rem; border-radius: 6px;
            font-size: 0.7rem; font-weight: 600;
        }
        .badge-article  { background: #DBEAFE; color: #1D4ED8; }
        .badge-idee     { background: #FEF9C3; color: #854D0E; }
        .badge-personal { background: var(--purple-light); color: var(--purple); }
        .badge-collab   { background: #D1FAE5; color: #065F46; }

        .btn-unsave {
            width: 28px; height: 28px; border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            background: transparent; border: 1px solid var(--gray-200);
            color: var(--gray-400); cursor: pointer; transition: all 0.18s;
            font-size: 0.78rem;
        }
        .btn-unsave:hover { background: #FEE2E2; color: #DC2626; border-color: #FCA5A5; }

        .fav-card-title {
            font-family: 'Urbanist', sans-serif;
            font-size: 0.9rem; font-weight: 700; color: var(--gray-900);
            line-height: 1.3; display: -webkit-box;
            -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }

        .fav-card-desc {
            font-size: 0.78rem; color: var(--gray-500);
            line-height: 1.5; display: -webkit-box;
            -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }

        /* Auteur */
        .fav-author {
            display: flex; align-items: center; gap: 0.5rem;
            text-decoration: none;
        }
        .fav-author-ava {
            width: 24px; height: 24px; border-radius: 50%;
            background: var(--purple-light); color: var(--purple);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.6rem; font-weight: 700; flex-shrink: 0; overflow: hidden;
        }
        .fav-author-ava img { width: 100%; height: 100%; object-fit: cover; }
        .fav-author-name { font-size: 0.75rem; color: var(--gray-500); font-weight: 500; }

        /* Footer */
        .fav-card-footer {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.65rem 1rem;
            border-top: 1px solid var(--gray-100);
            margin-top: auto;
        }
        .fav-saved-date { font-size: 0.7rem; color: var(--gray-400); display: flex; align-items: center; gap: 0.3rem; }

        .btn-voir-fav {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.3rem 0.75rem; background: var(--purple-light);
            color: var(--purple); border-radius: 7px; font-size: 0.75rem;
            font-weight: 600; text-decoration: none; transition: all 0.18s;
        }
        .btn-voir-fav:hover { background: var(--purple); color: white; }

        /* Empty */
        .fav-empty {
            text-align: center; padding: 4rem 2rem;
            background: white; border: 1px solid var(--gray-200);
            border-radius: 14px; grid-column: 1/-1;
        }
        .fav-empty i { font-size: 3rem; color: var(--gray-200); display: block; margin-bottom: 0.75rem; }
        .fav-empty h3 { font-family: 'Urbanist',sans-serif; font-size: 1rem; font-weight: 700; color: var(--gray-600); margin-bottom: 0.4rem; }
        .btn-explorer {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.55rem 1.25rem; background: var(--purple); color: white;
            border-radius: 9px; font-size: 0.875rem; font-weight: 600;
            text-decoration: none; margin-top: 0.75rem; transition: background 0.2s;
            white-space: nowrap;
        }
        .btn-explorer i { font-size: 0.82rem; }
        .btn-explorer:hover { background: var(--purple-dark); color: white; }

        @media (max-width: 992px) { .fav-grid { grid-template-columns: repeat(2,1fr); } .fav-stats { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 576px)  {
            .fav-grid { grid-template-columns: 1fr; }
            .btn-explorer { font-size: 0.82rem; padding: 0.5rem 1rem; gap: 0.35rem; }
            .btn-explorer i { font-size: 0.72rem; }
        }
    </style>
@endsection

@section('content')
    <main class="main-content">

        @php
            $total    = $favoris->count();
            $articles = $favoris->filter(fn($f) => $f->publication?->type === 'article')->count();
            $idees    = $favoris->filter(fn($f) => $f->publication?->type === 'idee')->count();
            $projets  = $favoris->filter(fn($f) => in_array($f->publication?->type, ['projet_personnel','projet_collaboratif']))->count();
            $collabs  = $favoris->filter(fn($f) => $f->publication?->type === 'projet_collaboratif')->count();
        @endphp

        <!-- Stats -->
        <div class="fav-stats">
            <div class="fs-card">
                <div class="fs-icon" style="background:var(--purple-light);color:var(--purple);">
                    <i class="fas fa-bookmark"></i>
                </div>
                <div><div class="fs-val">{{ $total }}</div><div class="fs-label">Total</div></div>
            </div>
            <div class="fs-card">
                <div class="fs-icon" style="background:#DBEAFE;color:#1D4ED8;">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div><div class="fs-val">{{ $articles }}</div><div class="fs-label">Articles</div></div>
            </div>
            <div class="fs-card">
                <div class="fs-icon" style="background:var(--purple-light);color:var(--purple);">
                    <i class="fas fa-folder"></i>
                </div>
                <div><div class="fs-val">{{ $projets }}</div><div class="fs-label">Projets</div></div>
            </div>
            <div class="fs-card">
                <div class="fs-icon" style="background:#FEF9C3;color:#854D0E;">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div><div class="fs-val">{{ $idees }}</div><div class="fs-label">Idées</div></div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="fav-tabs">
            <button class="fav-tab active" data-tab="all">
                Tous <span class="tab-count">{{ $total }}</span>
            </button>
            <button class="fav-tab" data-tab="article">
                Articles <span class="tab-count">{{ $articles }}</span>
            </button>
            <button class="fav-tab" data-tab="idee">
                Idées <span class="tab-count">{{ $idees }}</span>
            </button>
            <button class="fav-tab" data-tab="projet_personnel">
                Projets perso <span class="tab-count">{{ $favoris->filter(fn($f) => $f->publication?->type === 'projet_personnel')->count() }}</span>
            </button>
            <button class="fav-tab" data-tab="projet_collaboratif">
                Collaboratifs <span class="tab-count">{{ $collabs }}</span>
            </button>
        </div>

        <!-- État vide (caché si des favoris existent, révélé par JS) -->
        <div id="favEmptyState" style="{{ $favoris->count() > 0 ? 'display:none;' : '' }}padding:0;">
            <div class="fav-empty">
                <i class="fas fa-bookmark"></i>
                <h3>Aucun favori pour le moment</h3>
                <p style="font-size:0.85rem;color:var(--gray-400);">
                    Sauvegardez des publications depuis le tableau de bord.
                </p>
                <a href="{{ route('dashboard') }}" class="btn-explorer">
                    <i class="fas fa-search"></i> Explorer
                </a>
            </div>
        </div>

        <!-- Grille -->
        <div class="fav-grid" id="favGrid">

            @forelse($favoris as $fav)
                @php
                    $pub = $fav->publication;
                    $typeMap = [
                        'article'             => ['Article',      'badge-article',  'fa-newspaper',  '#DBEAFE', '#1D4ED8'],
                        'idee'                => ['Idée',         'badge-idee',     'fa-lightbulb',  '#FEF9C3', '#854D0E'],
                        'projet_personnel'    => ['Projet perso', 'badge-personal', 'fa-folder',     '#EDE9FE', '#7C3AED'],
                        'projet_collaboratif' => ['Collaboratif', 'badge-collab',   'fa-users',      '#D1FAE5', '#065F46'],
                    ];
                    $tc = $typeMap[$pub?->type] ?? [$pub?->type, '', 'fa-file', '#F3F4F6', '#6B7280'];
                    $media = $pub?->medias?->first();
                @endphp

                @if($pub)
                    <div class="fav-card fav-item" data-type="{{ $pub->type }}"
                        onclick="window.location='{{ route('publications.show', $pub->id) }}'">

                        <!-- Image ou placeholder coloré -->
                        @if($media)
                            <img src="{{ $media->url }}"
                                alt="" class="fav-card-img">
                        @else
                            <div class="fav-card-img-placeholder"
                                style="background: {{ $tc[3] }};color: {{ $tc[4] }};">
                                <i class="fas {{ $tc[2] }}"></i>
                            </div>
                        @endif

                        <div class="fav-card-body">
                            <!-- Type + bouton retirer -->
                            <div class="fav-card-top">
                                <span class="fav-type-badge {{ $tc[1] }}">
                                    <i class="fas {{ $tc[2] }}"></i> {{ $tc[0] }}
                                </span>
                                <button class="btn-unsave btn-retirer-fav" data-id="{{ $pub->id }}"
                                        title="Retirer des favoris" onclick="event.stopPropagation()" onmousedown="event.stopPropagation()">
                                    <i class="fas fa-bookmark"></i>
                                </button>
                            </div>

                            <!-- Titre -->
                            <div class="fav-card-title">{{ $pub->titre }}</div>

                            <!-- Description -->
                            @if($pub->contenu)
                                <div class="fav-card-desc">{{ strip_tags($pub->contenu) }}</div>
                            @endif

                            <!-- Auteur -->
                            <a href="{{ route('profil.show', $pub->auteur_id) }}"
                            class="fav-author" onclick="event.stopPropagation()">
                                <div class="fav-author-ava">
                                    @if($pub->auteur?->photo)
                                        <img src="{{ $pub->auteur->photo_url }}" alt="">
                                    @else
                                        {{ strtoupper(substr($pub->auteur?->prenom ?? '?', 0, 1)) }}
                                    @endif
                                </div>
                                <span class="fav-author-name">
                                    {{ $pub->auteur?->prenom }} {{ $pub->auteur?->nom }}
                                </span>
                            </a>
                        </div>

                        <!-- Footer -->
                        <div class="fav-card-footer">
                            <span class="fav-saved-date">
                                <i class="fas fa-bookmark"></i>
                                {{ $fav->date_ajout ? $fav->date_ajout->diffForHumans() : 'récemment' }}
                            </span>
                            <a href="{{ route('publications.show', $pub->id) }}"
                            class="btn-voir-fav" onclick="event.stopPropagation()">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </div>
                    </div>
                @endif
            @empty
            @endforelse
        </div>

    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.fav-tab').forEach(function(tab) {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.fav-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    var type = this.dataset.tab;
                    var visible = 0;

                    document.querySelectorAll('.fav-item').forEach(function(card) {
                        var show = type === 'all' || card.dataset.type === type;
                        card.style.display = show ? '' : 'none';
                        if (show) visible++;
                    });

                    // Afficher empty si rien
                    var empty = document.querySelector('.fav-empty');
                    if (empty) empty.style.display = visible === 0 ? 'block' : 'none';
                });
            });
        });
        // Retirer favori — masquer la card immédiatement
        document.querySelectorAll('.btn-retirer-fav').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                var card = this.closest('.fav-card');
                var id   = this.dataset.id;

                fetch(window.APP_URL + '/favoris/' + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.CSRF_TOKEN,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (!data.saved && card) {
                        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.95)';
                        setTimeout(function() {
                            card.remove();
                            var remaining = document.querySelectorAll('.fav-item').length;
                            if (remaining === 0) {
                                document.getElementById('favGrid').style.display = 'none';
                                document.getElementById('favEmptyState').style.display = '';
                            }
                        }, 300);
                    }
                })
                .catch(function() {});
            });
        });
    </script>
@endsection