@extends('layouts.app')
@section('title', 'Avis reçus – DevHub')
@section('secondary_nav', true)

@section('styles')
    <style>
        /* ─── HEADER ─── */
        .avis-page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;
        }
        .avis-page-title {
            font-family: 'Urbanist', sans-serif;
            font-size: 1.1rem; font-weight: 800; color: var(--gray-900);
        }
        .avis-page-sub { font-size: 0.82rem; color: var(--gray-400); margin-top: 0.15rem; }

        /* ─── STATS ─── */
        .avis-stats {
            display: grid; grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 0.75rem; margin-bottom: 1.25rem;
        }

        .as-card {
            background: white; border: 1px solid var(--gray-200);
            border-radius: 12px; padding: 0.85rem 1rem;
            display: flex; align-items: center; gap: 0.75rem;
        }
        .as-icon {
            width: 36px; height: 36px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; flex-shrink: 0;
        }
        .as-val {
            font-family: 'Urbanist', sans-serif;
            font-size: 1.15rem; font-weight: 800; color: var(--gray-900); line-height: 1;
        }
        .as-label { font-size: 0.72rem; color: var(--gray-400); margin-top: 0.1rem; }

        /* Moyenne inline header */
        .moyenne-card {
            background: white; border: 1px solid var(--gray-200);
            border-radius: 10px; padding: 0.6rem 1rem;
            display: flex; align-items: center; gap: 0.9rem;
            flex-shrink: 0;
        }
        .moyenne-number {
            font-family: 'Urbanist', sans-serif;
            font-size: 1.5rem; font-weight: 800; line-height: 1; color: var(--gray-900);
        }
        .moyenne-stars { display: flex; gap: 0.15rem; margin: 0.15rem 0; }
        .moyenne-stars i { font-size: 0.72rem; }
        .moyenne-label { font-size: 0.68rem; color: var(--gray-400); }

        /* Distribution étoiles */
        .star-dist { min-width: 120px; }
        .star-dist-row {
            display: flex; align-items: center; gap: 0.4rem;
            margin-bottom: 0.22rem;
        }
        .star-dist-label { font-size: 0.65rem; color: var(--gray-500); width: 16px; text-align: right; flex-shrink: 0; }
        .star-dist-bar {
            flex: 1; height: 4px; background: var(--gray-100);
            border-radius: 2px; overflow: hidden;
        }
        .star-dist-fill {
            height: 100%; background: #F59E0B;
            border-radius: 2px; transition: width 0.6s ease;
        }
        .star-dist-count { font-size: 0.63rem; color: var(--gray-400); width: 16px; flex-shrink: 0; }

        /* ─── TABS ─── */
        .avis-tabs {
            display: flex; gap: 0.35rem; margin-bottom: 1rem; flex-wrap: wrap;
        }
        .avis-tab {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.4rem 0.9rem; background: transparent; color: var(--gray-500);
            border: 1.5px solid var(--gray-200); border-radius: 8px;
            font-size: 0.82rem; font-weight: 500; cursor: pointer;
            transition: all 0.18s; font-family: 'DM Sans', sans-serif;
        }
        .avis-tab:hover { border-color: var(--purple); color: var(--purple); background: var(--purple-light); }
        .avis-tab.active { background: var(--purple-light); color: var(--purple); border-color: var(--purple); font-weight: 700; }

        /* ─── CARDS AVIS ─── */
        .avis-list { display: flex; flex-direction: column; gap: 0.75rem; }

        .avis-card {
            background: white; border: 1px solid var(--gray-200);
            border-radius: 14px; padding: 1.1rem 1.25rem;
            transition: all 0.18s; display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1rem; align-items: start;
        }
        .avis-card:hover { border-color: var(--purple); box-shadow: 0 4px 16px rgba(124,58,237,0.08); }

        /* Avatar auteur */
        .avis-avatar {
            width: 44px; height: 44px; border-radius: 50%;
            background: var(--purple-light); color: var(--purple);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 700; flex-shrink: 0; overflow: hidden;
        }
        .avis-avatar img { width: 100%; height: 100%; object-fit: cover; }

        /* Corps */
        .avis-body { flex: 1; min-width: 0; }

        .avis-top {
            display: flex; align-items: center; gap: 0.65rem;
            margin-bottom: 0.35rem; flex-wrap: wrap;
        }

        .avis-author-name {
            font-size: 0.875rem; font-weight: 700; color: var(--gray-900);
            text-decoration: none;
        }
        .avis-author-name:hover { color: var(--purple); }

        .avis-time { font-size: 0.72rem; color: var(--gray-400); }

        /* Étoiles */
        .avis-stars { display: flex; gap: 0.15rem; align-items: center; }
        .avis-stars i { font-size: 0.82rem; }
        .avis-score { font-size: 0.78rem; font-weight: 700; color: var(--gray-600); margin-left: 0.3rem; }

        /* Projet ref */
        .avis-projet-ref {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.2rem 0.6rem; border-radius: 6px;
            font-size: 0.72rem; font-weight: 600;
            text-decoration: none; margin-bottom: 0.5rem;
            transition: all 0.15s;
        }
        .avis-projet-ref.perso { background: var(--purple-light); color: var(--purple); }
        .avis-projet-ref.collab { background: #D1FAE5; color: #065F46; }
        .avis-projet-ref:hover { opacity: 0.8; }

        /* Commentaire */
        .avis-comment {
            font-size: 0.875rem; color: var(--gray-600);
            line-height: 1.6; font-style: italic;
            padding: 0.5rem 0.75rem;
            background: var(--gray-50); border-left: 3px solid #F59E0B;
            border-radius: 0 8px 8px 0; margin-top: 0.4rem;
        }

        /* Note visuelle côté droit */
        .avis-note-circle {
            width: 44px; height: 44px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Urbanist', sans-serif; font-size: 1rem; font-weight: 800;
            flex-shrink: 0;
        }
        .note-5 { background: #D1FAE5; color: #065F46; }
        .note-4 { background: #DBEAFE; color: #1D4ED8; }
        .note-3 { background: #FEF9C3; color: #854D0E; }
        .note-2 { background: #FEE2E2; color: #DC2626; }
        .note-1 { background: #FEE2E2; color: #DC2626; }

        /* Empty */
        .avis-empty {
            text-align: center; padding: 4rem 2rem;
            background: white; border: 1px solid var(--gray-200);
            border-radius: 14px;
        }
        .avis-empty i { font-size: 3rem; color: var(--gray-200); display: block; margin-bottom: 0.75rem; }
        .avis-empty h3 { font-family: 'Urbanist',sans-serif; font-size: 1rem; font-weight: 700; color: var(--gray-600); margin-bottom: 0.4rem; }

        @media (max-width: 768px) {
            .avis-stats { grid-template-columns: repeat(2,1fr); }
            .moyenne-card { flex-wrap: wrap; gap: 0.85rem; }
            .avis-card { grid-template-columns: auto 1fr; }
            .avis-note-circle { display: none; }
        }
    </style>
@endsection

@section('content')
    <main class="main-content">

        @if($avis->count() > 0)

            @php
                $totalAvis      = $avis->count();
                $moyenneGlobale = $avis->avg('note');
                $avisPerso      = $avis->filter(fn($a) => optional(\App\Models\Publication::find($a->publication_id))->type === 'projet_personnel');
                $avisCollab     = $avis->filter(fn($a) => optional(\App\Models\Publication::find($a->publication_id))->type === 'projet_collaboratif');
                $dist = [];
                for ($i = 5; $i >= 1; $i--) { $dist[$i] = $avis->where('note', $i)->count(); }
            @endphp

            <!-- Header + carte moyenne sur la même ligne -->
            <div class="avis-page-header">
                <div>
                    <h1 class="avis-page-title">
                        <i class="fas fa-star me-2" style="color:#F59E0B;"></i>Avis reçus
                    </h1>
                    <p class="avis-page-sub">Les retours de la communauté sur vos projets</p>
                </div>

                <div class="moyenne-card">
                    <div style="text-align:center;flex-shrink:0;padding-right:0.9rem;border-right:1px solid var(--gray-100);">
                        <div class="moyenne-number">{{ number_format($moyenneGlobale, 1) }}</div>
                        <div class="moyenne-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($moyenneGlobale))
                                    <i class="fas fa-star" style="color:#F59E0B;"></i>
                                @elseif($i - $moyenneGlobale < 1)
                                    <i class="fas fa-star-half-alt" style="color:#F59E0B;"></i>
                                @else
                                    <i class="far fa-star" style="color:#E5E7EB;"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="moyenne-label">{{ $totalAvis }} avis</div>
                    </div>

                    <div class="star-dist">
                        @for($i = 5; $i >= 1; $i--)
                            <div class="star-dist-row">
                                <div class="star-dist-label">{{ $i }}<i class="fas fa-star ms-1" style="font-size:0.55rem;color:#F59E0B;"></i></div>
                                <div class="star-dist-bar">
                                    <div class="star-dist-fill" style="width:{{ $totalAvis > 0 ? round(($dist[$i]/$totalAvis)*100) : 0 }}%;"></div>
                                </div>
                                <div class="star-dist-count">{{ $dist[$i] }}</div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Tabs filtre -->
            <div class="avis-tabs">
                <button class="avis-tab active" data-tab="all">
                    Tous <span style="background:var(--purple);color:white;font-size:0.6rem;font-weight:700;min-width:16px;height:16px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;padding:0 4px;">{{ $totalAvis }}</span>
                </button>
                <button class="avis-tab" data-tab="personal">
                    Projets perso <span style="background:var(--gray-300);color:white;font-size:0.6rem;font-weight:700;min-width:16px;height:16px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;padding:0 4px;">{{ $avisPerso->count() }}</span>
                </button>
                <button class="avis-tab" data-tab="collaborative">
                    Collaboratifs <span style="background:var(--gray-300);color:white;font-size:0.6rem;font-weight:700;min-width:16px;height:16px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;padding:0 4px;">{{ $avisCollab->count() }}</span>
                </button>
                @for($i = 5; $i >= 1; $i--)
                    @if($dist[$i] > 0)
                        <button class="avis-tab" data-tab="note-{{ $i }}">
                            {{ $i }} <i class="fas fa-star" style="color:#F59E0B;font-size:0.75rem;"></i>
                            <span style="background:var(--gray-300);color:white;font-size:0.6rem;font-weight:700;min-width:16px;height:16px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;padding:0 4px;">{{ $dist[$i] }}</span>
                        </button>
                    @endif
                @endfor
            </div>

            <!-- Liste avis -->
            <div class="avis-list" id="avisList">
                @foreach($avis as $a)
                    @php
                        $pub = \App\Models\Publication::find($a->publication_id);
                        $type = $pub?->type;
                        $tabType = $type === 'projet_collaboratif' ? 'collaborative' : 'personal';
                    @endphp
                    @if($pub)
                        <div class="avis-card review-card-v2"
                            data-type="{{ $tabType }}"
                            data-note="{{ $a->note }}">

                            <!-- Avatar -->
                            <div class="avis-avatar">
                                @if($a->auteur?->photo)
                                    <img src="{{ $a->auteur->photo_url }}" alt="">
                                @else
                                    {{ strtoupper(substr($a->auteur?->prenom ?? '?', 0, 1)) }}
                                @endif
                            </div>

                            <!-- Corps -->
                            <div class="avis-body">
                                <!-- Auteur + date -->
                                <div class="avis-top">
                                    <a href="{{ route('profil.show', $a->auteur_id) }}" class="avis-author-name">
                                        {{ $a->auteur?->prenom }} {{ $a->auteur?->nom }}
                                    </a>
                                    <span class="avis-time">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $a->date_avis ? $a->date_avis->diffForHumans() : 'récemment' }}
                                    </span>
                                </div>

                                <!-- Étoiles -->
                                <div class="avis-stars" style="margin-bottom:0.5rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star" style="color:{{ $i <= $a->note ? '#F59E0B' : '#E5E7EB' }};"></i>
                                    @endfor
                                    <span class="avis-score">{{ $a->note }}/5</span>
                                </div>

                                <!-- Projet -->
                                <a href="{{ route('publications.show', $pub->id) }}"
                                class="avis-projet-ref {{ $type === 'projet_collaboratif' ? 'collab' : 'perso' }}">
                                    <i class="fas {{ $type === 'projet_collaboratif' ? 'fa-users' : 'fa-folder' }}"></i>
                                    {{ Str::limit($pub->titre, 50) }}
                                </a>

                                <!-- Commentaire -->
                                @if($a->commentaire)
                                    <div class="avis-comment">
                                        {{ $a->commentaire }}
                                    </div>
                                @endif
                            </div>

                            <!-- Note cercle -->
                            <div class="avis-note-circle note-{{ $a->note }}">
                                {{ $a->note }}
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

        @else
            <div class="avis-empty">
                <i class="fas fa-star"></i>
                <h3>Aucun avis reçu</h3>
                <p style="font-size:0.85rem;color:var(--gray-400);">
                    Publiez des projets personnels ou collaboratifs pour recevoir des avis.
                </p>
            </div>
        @endif

    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.avis-tab').forEach(function(tab) {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.avis-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    var tabVal = this.dataset.tab;

                    document.querySelectorAll('.avis-card').forEach(function(card) {
                        if (tabVal === 'all') {
                            card.style.display = '';
                        } else if (tabVal === 'personal' || tabVal === 'collaborative') {
                            card.style.display = card.dataset.type === tabVal ? '' : 'none';
                        } else if (tabVal.startsWith('note-')) {
                            var note = tabVal.replace('note-', '');
                            card.style.display = card.dataset.note === note ? '' : 'none';
                        }
                    });
                });
            });
        });
    </script>
@endsection