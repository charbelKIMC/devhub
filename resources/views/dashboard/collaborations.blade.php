@extends('layouts.app')
@section('title', 'Mes collaborations – DevHub')
@section('secondary_nav', true)

@section('styles')
    <style>
        .collab-page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;
        }
        .collab-page-title {
            font-family: 'Urbanist', sans-serif;
            font-size: 1.1rem; font-weight: 800; color: var(--gray-900);
        }
        .collab-page-sub { font-size: 0.82rem; color: var(--gray-400); margin-top: 0.15rem; }

        .btn-new-collab {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.5rem 1.1rem; background: var(--purple); color: white;
            border: none; border-radius: 9px; font-size: 0.875rem; font-weight: 600;
            cursor: pointer; text-decoration: none; transition: background 0.2s;
        }
        .btn-new-collab:hover { background: var(--purple-dark); color: white; }

        /* Stats */
        .collab-stats {
            display: grid; grid-template-columns: repeat(4,1fr);
            gap: 0.75rem; margin-bottom: 1.25rem;
        }
        .cs-card {
            background: white; border: 1px solid var(--gray-200);
            border-radius: 12px; padding: 0.85rem 1rem;
            display: flex; align-items: center; gap: 0.75rem;
        }
        .cs-icon {
            width: 36px; height: 36px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; flex-shrink: 0;
        }
        .cs-val { font-family: 'Urbanist', sans-serif; font-size: 1.15rem; font-weight: 600; color: var(--gray-900); line-height: 1; }
        .cs-label { font-size: 0.72rem; color: var(--gray-400); margin-top: 0.1rem; }

        /* Onglets principaux */
        .main-tabs {
            display: flex; gap: 0; margin-bottom: 1.25rem;
            border-bottom: 2px solid var(--gray-200);
        }
        .main-tab {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.75rem 1.25rem; font-size: 0.875rem; font-weight: 600;
            color: var(--gray-500); cursor: pointer; border: none;
            background: transparent; border-bottom: 2px solid transparent;
            margin-bottom: -2px; transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }
        .main-tab:hover { color: var(--purple); }
        .main-tab.active { color: var(--purple); border-bottom-color: var(--purple); }
        .main-tab .tab-badge {
            background: var(--purple-light); color: var(--purple);
            font-size: 0.65rem; font-weight: 700; min-width: 18px; height: 18px;
            border-radius: 9px; display: inline-flex; align-items: center;
            justify-content: center; padding: 0 4px;
        }
        .main-tab.active .tab-badge { background: var(--purple); color: white; }

        /* Sections */
        .tab-section { display: none; }
        .tab-section.active { display: block; }

        /* Section titre */
        .section-label {
            font-family: 'Urbanist', sans-serif; font-size: 0.85rem; font-weight: 700;
            color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.5px;
            margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;
        }

        /* Projet card */
        .projet-card {
            background: white; border: 1px solid var(--gray-200);
            border-radius: 14px; margin-bottom: 1rem; overflow: hidden;
            transition: all 0.18s;
        }
        .projet-card:hover { border-color: var(--purple); box-shadow: 0 4px 16px rgba(124,58,237,0.08); }

        .projet-card-header {
            padding: 1rem 1.25rem;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid var(--gray-100); flex-wrap: wrap; gap: 0.75rem;
        }

        .projet-card-title {
            font-family: 'Urbanist', sans-serif; font-size: 0.95rem;
            font-weight: 700; color: var(--gray-900);
        }

        .projet-card-meta {
            display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;
            margin-top: 0.25rem;
        }

        .projet-meta-item {
            font-size: 0.75rem; color: var(--gray-400);
            display: flex; align-items: center; gap: 0.3rem;
        }

        .projet-card-actions {
            display: flex; align-items: center; gap: 0.5rem;
        }

        .btn-voir-projet {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.4rem 0.85rem; background: var(--purple-light);
            color: var(--purple); border: 1.5px solid var(--purple);
            border-radius: 8px; font-size: 0.78rem; font-weight: 600;
            text-decoration: none; transition: all 0.18s; white-space: nowrap;
        }
        .btn-voir-projet:hover { background: var(--purple); color: white; }

        /* Membres */
        .membres-zone {
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid var(--gray-100);
            display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;
        }

        .membre-chip {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.25rem 0.65rem;
            background: var(--gray-50); border: 1px solid var(--gray-200);
            border-radius: 20px; font-size: 0.75rem; color: var(--gray-700); font-weight: 500;
        }
        .membre-ava {
            width: 20px; height: 20px; border-radius: 50%;
            background: var(--purple-light); color: var(--purple);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.55rem; font-weight: 700; overflow: hidden;
        }
        .membre-ava img { width: 100%; height: 100%; object-fit: cover; }

        /* Candidatures */
        .candidatures-zone { padding: 0; }

        .candidatures-header {
            padding: 0.65rem 1.25rem;
            background: var(--gray-50);
            font-size: 0.75rem; font-weight: 700; color: var(--gray-500);
            text-transform: uppercase; letter-spacing: 0.4px;
            display: flex; align-items: center; gap: 0.5rem;
        }

        .candidature-row {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 0.75rem 1.25rem;
            border-top: 1px solid var(--gray-100);
            transition: background 0.15s;
        }
        .candidature-row:hover { background: var(--gray-50); }

        .cand-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--purple-light); color: var(--purple);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.82rem; font-weight: 700; flex-shrink: 0; overflow: hidden;
        }
        .cand-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .cand-info { flex: 1; min-width: 0; }
        .cand-name { font-size: 0.875rem; font-weight: 600; color: var(--gray-900); }
        .cand-poste { font-size: 0.75rem; color: var(--gray-400); }
        .cand-motivation {
            font-size: 0.75rem; color: var(--gray-500); font-style: italic;
            margin-top: 0.15rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 300px;
        }

        .cand-statut {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.2rem 0.6rem; border-radius: 6px;
            font-size: 0.72rem; font-weight: 700; flex-shrink: 0;
        }
        .cand-statut .sdot { width: 6px; height: 6px; border-radius: 50%; }
        .cs-attente  { background: #FEF9C3; color: #854D0E; } .cs-attente .sdot  { background: #F59E0B; }
        .cs-acceptee { background: #D1FAE5; color: #065F46; } .cs-acceptee .sdot { background: #22C55E; }
        .cs-refusee  { background: #FEE2E2; color: #DC2626; } .cs-refusee .sdot  { background: #EF4444; }

        .cand-actions { display: flex; gap: 0.4rem; flex-shrink: 0; }

        .btn-accepter {
            padding: 0.35rem 0.75rem; background: #D1FAE5; color: #065F46;
            border: 1.5px solid #6EE7B7; border-radius: 7px;
            font-size: 0.75rem; font-weight: 700; cursor: pointer;
            font-family: 'DM Sans', sans-serif; transition: all 0.18s;
        }
        .btn-accepter:hover { background: #065F46; color: white; }

        .btn-refuser {
            padding: 0.35rem 0.75rem; background: #FEE2E2; color: #DC2626;
            border: 1.5px solid #FCA5A5; border-radius: 7px;
            font-size: 0.75rem; font-weight: 700; cursor: pointer;
            font-family: 'DM Sans', sans-serif; transition: all 0.18s;
        }
        .btn-refuser:hover { background: #DC2626; color: white; }

        .no-candidatures {
            padding: 1.25rem; text-align: center;
            font-size: 0.82rem; color: var(--gray-400);
            border-top: 1px solid var(--gray-100);
        }

        /* Participations */
        .participation-card {
            background: white; border: 1px solid var(--gray-200);
            border-radius: 14px; padding: 1rem 1.25rem;
            margin-bottom: 0.75rem;
            display: grid; grid-template-columns: 1fr auto;
            gap: 1rem; transition: all 0.18s;
        }
        .participation-card:hover { border-color: var(--purple); box-shadow: 0 4px 16px rgba(124,58,237,0.08); }

        .part-top { display: flex; align-items: center; gap: 0.65rem; flex-wrap: wrap; margin-bottom: 0.4rem; }
        .part-title { font-family: 'Urbanist', sans-serif; font-size: 0.95rem; font-weight: 700; color: var(--gray-900); }
        .part-desc { font-size: 0.82rem; color: var(--gray-500); line-height: 1.5; }
        .part-motivation {
            font-size: 0.78rem; color: var(--gray-500); font-style: italic;
            padding: 0.4rem 0.65rem; background: var(--gray-50);
            border-left: 3px solid var(--purple-light); border-radius: 0 6px 6px 0;
            margin-top: 0.4rem;
        }
        .part-meta { display: flex; align-items: center; gap: 0.85rem; flex-wrap: wrap; margin-top: 0.4rem; }
        .part-meta-item { font-size: 0.75rem; color: var(--gray-400); display: flex; align-items: center; gap: 0.3rem; }

        .part-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; flex-shrink: 0; }

        .btn-voir-part {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.4rem 0.85rem; background: var(--purple-light);
            color: var(--purple); border: 1.5px solid var(--purple);
            border-radius: 8px; font-size: 0.78rem; font-weight: 600;
            text-decoration: none; transition: all 0.18s; white-space: nowrap;
        }
        .btn-voir-part:hover { background: var(--purple); color: white; }

        .btn-retirer {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.35rem 0.75rem; background: transparent; color: #DC2626;
            border: 1px solid #FCA5A5; border-radius: 7px;
            font-size: 0.75rem; font-weight: 600; cursor: pointer;
            font-family: 'DM Sans', sans-serif; transition: all 0.18s;
        }
        .btn-retirer:hover { background: #FEE2E2; }

        .btn-msg-groupe {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.35rem 0.75rem; background: #DBEAFE; color: #1D4ED8;
            border: 1px solid #93C5FD; border-radius: 7px;
            font-size: 0.75rem; font-weight: 600; text-decoration: none;
            transition: all 0.18s; white-space: nowrap;
        }
        .btn-msg-groupe:hover { background: #1D4ED8; color: white; }

        /* Badges */
        .badge-statut-sm {
            display: inline-flex; align-items: center; gap: 0.25rem;
            padding: 0.2rem 0.55rem; border-radius: 6px;
            font-size: 0.7rem; font-weight: 700;
        }
        .badge-statut-sm .sdot { width: 5px; height: 5px; border-radius: 50%; }

        /* Empty */
        .collab-empty {
            text-align: center; padding: 3rem 2rem;
            background: white; border: 1px solid var(--gray-200); border-radius: 14px;
        }
        .collab-empty i { font-size: 2.5rem; color: var(--gray-200); display: block; margin-bottom: 0.75rem; }
        .collab-empty h3 { font-family: 'Urbanist',sans-serif; font-size: 1rem; font-weight: 700; color: var(--gray-600); margin-bottom: 0.4rem; }

        @media (max-width: 768px) {
            .collab-stats { grid-template-columns: repeat(2,1fr); }
            .participation-card { grid-template-columns: 1fr; }
            .part-actions { flex-direction: row; align-items: center; }
        }
    </style>
@endsection

@section('content')
    <main class="main-content">

        <!-- Header -->
        <div class="collab-page-header">
            <div>
                <p class="collab-page-sub">Gérez vos projets et vos participations</p>
            </div>
            <button class="btn-new-collab" data-bs-toggle="modal" data-bs-target="#modalPublication"
                    onclick="setTimeout(()=>{document.querySelector('[data-type=projet_collaboratif]')?.click()},300)">
                <i class="fas fa-plus"></i> <span class="mtitre">Nouveau projet</span>
            </button>
        </div>

        <!-- Stats rapides -->
        <div class="collab-stats">
            <div class="cs-card">
                <div class="cs-icon" style="background:var(--purple-light);color:var(--purple);">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div>
                    <div class="cs-val">{{ $counts['projets'] }}</div>
                    <div class="cs-label">Mes projets</div>
                </div>
            </div>
            <div class="cs-card">
                <div class="cs-icon" style="background:#D1FAE5;color:#065F46;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="cs-val">{{ $counts['participations'] }}</div>
                    <div class="cs-label">Participations</div>
                </div>
            </div>
            <div class="cs-card">
                <div class="cs-icon" style="background:#DBEAFE;color:#1D4ED8;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="cs-val">{{ $counts['actives'] }}</div>
                    <div class="cs-label">Actives</div>
                </div>
            </div>
            <div class="cs-card">
                <div class="cs-icon" style="background:#FEF9C3;color:#854D0E;">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="cs-val">{{ $counts['en_attente'] }}</div>
                    <div class="cs-label">En attente</div>
                </div>
            </div>
        </div>

        <!-- Onglets principaux -->
        <div class="main-tabs">
            <button class="main-tab active" data-target="tab-projets">
                <i class="fas fa-folder"></i> Mes projets
                <span class="tab-badge">{{ $counts['projets'] }}</span>
            </button>
            <button class="main-tab" data-target="tab-participations">
                <i class="fas fa-user-friends"></i> Mes participations
                <span class="tab-badge">{{ $counts['participations'] }}</span>
            </button>
        </div>

        <!-- ─── ONGLET 1 : MES PROJETS ─── -->
        <div class="tab-section active" id="tab-projets">
            @forelse($mesProjets as $pub)
                @php
                    $projet       = $pub->projetCollaboratif;
                    $membres      = $projet?->membres ?? collect();
                    $candidatures = $projet?->candidatures ?? collect();
                    $enAttente    = $candidatures->where('statut','en_attente');
                    $acceptees    = $candidatures->where('statut','acceptée');
                    $convGroupe   = $projet?->conversation;
                @endphp

                <div class="projet-card">
                    <!-- Header projet -->
                    <div class="projet-card-header">
                        <div>
                            <div class="projet-card-title">{{ $pub->titre }}</div>
                            <div class="projet-card-meta">
                                @if($projet)
                                    <span class="projet-meta-item">
                                        <i class="fas fa-users"></i>
                                        {{ $projet->taille_equipe_actuelle }}/{{ $projet->taille_equipe_max }} membres
                                    </span>
                                    <span class="projet-meta-item">
                                        <i class="fas fa-tag"></i>
                                        {{ $projet->phase === 'ouvert' ? 'Ouvert' : 'En cours' }}
                                    </span>
                                @endif
                                <span class="projet-meta-item">
                                    <i class="fas fa-calendar"></i>
                                    {{ $pub->date_publication->format('d M Y') }}
                                </span>
                                @if($enAttente->count() > 0)
                                    <span style="background:#FEF9C3;color:#854D0E;font-size:0.7rem;font-weight:700;padding:0.15rem 0.5rem;border-radius:6px;">
                                        {{ $enAttente->count() }} candidature{{ $enAttente->count() > 1 ? 's' : '' }} en attente
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="projet-card-actions">
                            @if($convGroupe)
                                <a href="{{ route('messagerie.show', $convGroupe->id) }}" class="btn-msg-groupe">
                                    <i class="fas fa-comments"></i> Groupe
                                </a>
                            @endif
                            <a href="{{ route('publications.show', $pub->id) }}" class="btn-voir-projet">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </div>
                    </div>

                    <!-- Membres actuels -->
                    @if($membres->count() > 0)
                        <div class="membres-zone">
                            <span style="font-size:0.72rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.4px;flex-shrink:0;">
                                Équipe :
                            </span>
                            @foreach($membres as $membre)
                                @if($membre->utilisateur)
                                    <div class="membre-chip">
                                        <div class="membre-ava">
                                            @if($membre->utilisateur->photo)
                                                <img src="{{ $membre->utilisateur->photo_url }}" alt="">
                                            @else
                                                {{ strtoupper(substr($membre->utilisateur->prenom,0,1)) }}
                                            @endif
                                        </div>
                                        {{ $membre->utilisateur->prenom }}
                                        <span style="font-size:0.65rem;color:var(--gray-400);">{{ ucfirst($membre->role) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <!-- Candidatures reçues -->
                    <div class="candidatures-zone">
                        <div class="candidatures-header">
                            <i class="fas fa-inbox" style="color:var(--purple);"></i>
                            Candidatures
                            @if($candidatures->count() > 0)
                                <span style="background:var(--purple);color:white;font-size:0.6rem;font-weight:700;min-width:16px;height:16px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;padding:0 4px;">
                                    {{ $candidatures->count() }}
                                </span>
                            @endif
                        </div>

                        @forelse($candidatures as $cand)
                            <div class="candidature-row">
                                <!-- Avatar candidat -->
                                <div class="cand-avatar">
                                    @if($cand->utilisateur?->photo)
                                        <img src="{{ $cand->utilisateur->photo_url }}" alt="">
                                    @else
                                        {{ strtoupper(substr($cand->utilisateur?->prenom ?? '?', 0, 1)) }}
                                    @endif
                                </div>

                                <!-- Info candidat -->
                                <div class="cand-info">
                                    <div class="cand-name">
                                        {{ $cand->utilisateur?->prenom }} {{ $cand->utilisateur?->nom }}
                                    </div>
                                    <div class="cand-poste">
                                        <i class="fas fa-briefcase me-1"></i>{{ $cand->poste_souhaite }}
                                    </div>
                                    @if($cand->motivation)
                                        <div class="cand-motivation">
                                            <i class="fas fa-quote-left me-1"></i>{{ Str::limit($cand->motivation, 80) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Statut -->
                                @php
                                    $sc = [
                                        'en_attente' => ['cs-attente', 'En attente'],
                                        'acceptée'   => ['cs-acceptee', 'Acceptée'],
                                        'refusée'    => ['cs-refusee', 'Refusée'],
                                    ][$cand->statut] ?? ['', $cand->statut];
                                @endphp
                                <span class="cand-statut {{ $sc[0] }}">
                                    <span class="sdot"></span> {{ $sc[1] }}
                                </span>

                                <!-- Date -->
                                <span style="font-size:0.7rem;color:var(--gray-400);flex-shrink:0;">
                                    {{ $cand->date_postulation ? $cand->date_postulation->diffForHumans() : 'récemment' }}
                                </span>

                                <!-- Actions -->
                                @if($cand->statut === 'en_attente')
                                    <div class="cand-actions">
                                        <form action="{{ route('candidatures.accepter', $cand->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn-accepter">
                                                <i class="fas fa-check me-1"></i>Accepter
                                            </button>
                                        </form>
                                        <form action="{{ route('candidatures.refuser', $cand->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn-refuser">
                                                <i class="fas fa-times me-1"></i>Refuser
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="no-candidatures">
                                <i class="fas fa-inbox me-2" style="color:var(--gray-300);"></i>
                                Aucune candidature reçue pour ce projet
                            </div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="collab-empty">
                    <i class="fas fa-folder-open"></i>
                    <h3>Aucun projet créé</h3>
                    <p style="font-size:0.85rem;color:var(--gray-400);">Créez votre premier projet collaboratif.</p>
                </div>
            @endforelse
        </div>

        <!-- ─── ONGLET 2 : MES PARTICIPATIONS ─── -->
        <div class="tab-section" id="tab-participations">
            @forelse($mesParticipations as $cand)
                @php
                    $pub = $cand->projet?->publication;
                    $statutMap = [
                        'acceptée'   => ['cs-acceptee', 'Acceptée', 'badge-acceptee'],
                        'en_attente' => ['cs-attente',  'En attente', 'badge-attente'],
                        'refusée'    => ['cs-refusee',  'Refusée', 'badge-refusee'],
                    ];
                    $sc         = $statutMap[$cand->statut] ?? ['', $cand->statut, ''];
                    $convGroupe = $cand->statut === 'acceptée' ? $cand->projet?->conversation : null;
                @endphp

                <div class="participation-card">
                    <div>
                        <!-- Top -->
                        <div class="part-top">
                            <span style="background:#D1FAE5;color:#065F46;font-size:0.72rem;font-weight:600;padding:0.2rem 0.6rem;border-radius:6px;">
                                <i class="fas fa-users me-1"></i>Collaboratif
                            </span>
                            <span class="cand-statut {{ $sc[0] }}">
                                <span class="sdot"></span> {{ $sc[1] }}
                            </span>
                        </div>

                        <!-- Titre -->
                        <div class="part-title">{{ $pub->titre }}</div>

                        <!-- Description -->
                        @if($pub->contenu)
                            <div class="part-desc">{{ Str::limit(strip_tags($pub->contenu), 100) }}</div>
                        @endif

                        <!-- Motivation -->
                        @if($cand->motivation)
                            <div class="part-motivation">
                                <i class="fas fa-quote-left me-1" style="color:var(--purple);"></i>
                                {{ Str::limit($cand->motivation, 100) }}
                            </div>
                        @endif

                        <!-- Meta -->
                        <div class="part-meta">
                            <span class="part-meta-item">
                                <i class="fas fa-user"></i>
                                <a href="{{ route('profil.show', $pub->auteur_id) }}"
                                style="color:var(--gray-400);text-decoration:none;">
                                    {{ $pub->auteur->prenom }} {{ $pub->auteur->nom }}
                                </a>
                            </span>
                            <span class="part-meta-item">
                                <i class="fas fa-briefcase"></i>
                                {{ $cand->poste_souhaite }}
                            </span>
                            <span class="part-meta-item">
                                <i class="fas fa-calendar"></i>
                                {{ $cand->date_postulation ? $cand->date_postulation->format('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="part-actions">
                        <a href="{{ route('publications.show', $pub->id) }}" class="btn-voir-part">
                            <i class="fas fa-eye"></i> Voir
                        </a>

                        @if($convGroupe)
                            <a href="{{ route('messagerie.show', $convGroupe->id) }}" class="btn-msg-groupe">
                                <i class="fas fa-comments"></i> Groupe
                            </a>
                        @endif

                        @if($cand->statut !== 'refusée')
                            <form action="{{ route('collaborations.retirer', $cand->id) }}"
                                method="POST"
                                onsubmit="return confirm('{{ $cand->statut === 'en_attente' ? 'Annuler cette candidature ?' : 'Se retirer du projet ?' }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-retirer">
                                    <i class="fas fa-{{ $cand->statut === 'en_attente' ? 'times' : 'sign-out-alt' }}"></i>
                                    {{ $cand->statut === 'en_attente' ? 'Annuler' : 'Se retirer' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="collab-empty">
                    <i class="fas fa-user-friends"></i>
                    <h3>Aucune participation</h3>
                    <p style="font-size:0.85rem;color:var(--gray-400);">
                        Explorez les projets et postulez pour rejoindre une équipe.
                    </p>
                </div>
            @endforelse
        </div>

    </main>
@endsection

@section('scripts')
    <script src="{{ asset('js/j-dashboard.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Onglets principaux
            document.querySelectorAll('.main-tab').forEach(function(tab) {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.main-tab').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.tab-section').forEach(s => s.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById(this.dataset.target)?.classList.add('active');
                });
            });
        });
    </script>
@endsection