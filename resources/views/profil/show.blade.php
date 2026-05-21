@extends('layouts.app')
@section('title', $utilisateur->prenom . ' ' . $utilisateur->nom . ' – DevHub')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profil.css') }}">
<style>
    /* ─── COVER + AVATAR ─── */
    .profil-cover {
        height: 180px;
        background: linear-gradient(135deg, #F5F3FF 0%, #EDE9FE 100%);
        position: relative;
        overflow: hidden;
    }

    .profil-top-wrap {
        background: white;
        border-bottom: 1px solid var(--gray-200);
        position: relative;
        z-index: 1;
    }

    .profil-top-inner {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .profil-identity-row {
        display: flex; align-items: flex-end; justify-content: space-between;
        padding: 0 0 1.25rem;
        flex-wrap: wrap; gap: 1rem;
        margin-top: -60px;
    }

    .profil-avatar-zone { position: relative; flex-shrink: 0; }

    .profil-avatar {
        width: 128px; height: 128px; border-radius: 50%;
        border: 4px solid white;
        object-fit: cover;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }

    .profil-avatar-text {
        width: 128px; height: 128px; border-radius: 50%;
        border: 4px solid white;
        background: var(--purple-light); color: var(--purple);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.8rem; font-weight: 800;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }

    .avatar-cam-btn {
        position: absolute; bottom: 4px; right: 4px;
        width: 26px; height: 26px; border-radius: 50%;
        background: var(--purple); color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.65rem; cursor: pointer; border: 2px solid white;
        transition: background 0.2s; text-decoration: none;
    }
    .avatar-cam-btn:hover { background: var(--purple-dark); }

    .profil-name-zone { flex: 1; min-width: 0; padding-top: 0.5rem; }

    .profil-name {
        font-family: 'Urbanist', sans-serif;
        font-size: 1.4rem; font-weight: 800; color: var(--gray-900);
        line-height: 1.2;
    }
    .profil-pseudo { font-size: 0.85rem; color: var(--gray-400); margin-top: 0.1rem; }
    .profil-spec {
        display: inline-flex; align-items: center; gap: 0.35rem;
        background: var(--purple-light); color: var(--purple);
        padding: 0.2rem 0.65rem; border-radius: 6px;
        font-size: 0.78rem; font-weight: 600; margin-top: 0.4rem;
    }

    .profil-actions-zone { display: flex; align-items: flex-end; gap: 0.5rem; flex-shrink: 0; }

    .btn-edit-profil {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.45rem 1rem; background: transparent;
        color: var(--purple); border: 1.5px solid var(--purple);
        border-radius: 8px; font-size: 0.82rem; font-weight: 600;
        cursor: pointer; text-decoration: none; transition: all 0.18s;
    }
    .btn-edit-profil:hover { background: var(--purple-light); }

    .btn-message-profil {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.45rem 1.1rem; background: var(--purple); color: white;
        border: none; border-radius: 8px; font-size: 0.82rem; font-weight: 600;
        cursor: pointer; transition: background 0.2s; text-decoration: none;
    }
    .btn-message-profil:hover { background: var(--purple-dark); color: white; }

    /* ─── INFO ROW ─── */
    .profil-info-row {
        display: flex; align-items: center; gap: 1.25rem;
        padding: 0.75rem 0;
        border-top: 1px solid var(--gray-100);
        flex-wrap: wrap;
    }

    .profil-info-item {
        display: flex; align-items: center; gap: 0.4rem;
        font-size: 0.78rem; color: var(--gray-500);
    }
    .profil-info-item i { color: var(--gray-400); font-size: 0.75rem; }

    .profil-social-links { display: flex; gap: 0.4rem; margin-left: auto; }

    .social-link {
        width: 30px; height: 30px; border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--gray-200); color: var(--gray-500);
        text-decoration: none; font-size: 0.82rem; transition: all 0.18s;
    }
    .social-link:hover { border-color: var(--purple); color: var(--purple); background: var(--purple-light); }

    /* ─── STATS BAR ─── */
    .profil-stats-bar {
        display: flex; gap: 0; border-top: 1px solid var(--gray-100);
    }

    .profil-stat {
        flex: 1; text-align: center; padding: 0.85rem 0.5rem;
        border-right: 1px solid var(--gray-100);
    }
    .profil-stat:last-child { border-right: none; }

    .profil-stat-val {
        font-family: 'Urbanist', sans-serif;
        font-size: 1.2rem; font-weight: 800; color: var(--gray-900);
    }
    .profil-stat-label { font-size: 0.7rem; color: var(--gray-400); margin-top: 0.1rem; }

    /* ─── CONTENU ─── */
    .profil-content {
        max-width: 1100px;
        margin: 0 auto;
        padding: 1.25rem 1.5rem;
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 1.25rem;
    }

    /* Sidebar gauche */
    .profil-sidebar { display: flex; flex-direction: column; gap: 0.85rem; }

    .profil-widget {
        background: white; border: 1px solid var(--gray-200);
        border-radius: 14px; padding: 1.1rem 1.25rem;
    }

    .profil-widget-title {
        font-family: 'Urbanist', sans-serif; font-size: 0.82rem; font-weight: 700;
        color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.5px;
        margin-bottom: 0.85rem;
    }

    /* Bio */
    .profil-bio-text {
        font-size: 0.875rem; color: var(--gray-600); line-height: 1.7;
    }

    /* Compétences */
    .skills-wrap { display: flex; flex-wrap: wrap; gap: 0.4rem; }
    .skill-chip {
        display: inline-flex; align-items: center;
        padding: 0.25rem 0.65rem; background: var(--purple-light);
        color: var(--purple); border-radius: 6px;
        font-size: 0.75rem; font-weight: 600;
    }

    /* ─── ONGLETS ─── */
    .profil-tabs {
        display: flex; gap: 0; border-bottom: 2px solid var(--gray-200);
        margin-bottom: 1.1rem;
    }

    .profil-tab {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.65rem 1.1rem; font-size: 0.875rem; font-weight: 600;
        color: var(--gray-500); cursor: pointer; border: none;
        background: transparent; border-bottom: 2px solid transparent;
        margin-bottom: -2px; transition: all 0.2s;
        font-family: 'DM Sans', sans-serif;
    }
    .profil-tab:hover { color: var(--purple); }
    .profil-tab.active { color: var(--purple); border-bottom-color: var(--purple); }

    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* Cards publications */
    .pub-card-profil {
        background: white; border: 1px solid var(--gray-200);
        border-radius: 12px; padding: 1rem 1.25rem;
        margin-bottom: 0.75rem; transition: all 0.18s;
        cursor: pointer;
    }
    .pub-card-profil:hover { border-color: var(--purple); box-shadow: 0 4px 16px rgba(124,58,237,0.08); }

    .pub-card-top {
        display: flex; align-items: center; gap: 0.65rem;
        margin-bottom: 0.5rem;
    }

    .pub-type-dot {
        width: 28px; height: 28px; border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.72rem; flex-shrink: 0;
    }

    .pub-card-title-link {
        font-family: 'Urbanist', sans-serif;
        font-size: 0.9rem; font-weight: 700; color: var(--gray-900);
        text-decoration: none; flex: 1;
    }
    .pub-card-title-link:hover { color: var(--purple); }

    .pub-card-desc {
        font-size: 0.82rem; color: var(--gray-500); line-height: 1.5;
        margin-bottom: 0.6rem;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }

    .pub-card-footer {
        display: flex; align-items: center; gap: 1rem;
    }
    .pub-stat { font-size: 0.72rem; color: var(--gray-400); display: flex; align-items: center; gap: 0.25rem; }
    .pub-card-date { margin-left: auto; font-size: 0.72rem; color: var(--gray-400); }

    .pub-card-actions { display: flex; gap: 0.35rem; flex-shrink: 0; }
    .act-sm {
        width: 26px; height: 26px; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--gray-200); background: transparent;
        color: var(--gray-400); cursor: pointer; font-size: 0.7rem;
        transition: all 0.15s; text-decoration: none;
    }
    .act-sm:hover { background: var(--purple-light); color: var(--purple); border-color: var(--purple); }
    .act-sm.danger:hover { background: #FEE2E2; color: #DC2626; border-color: #FCA5A5; }

    /* Settings */
    .settings-wrap {
        display: grid; grid-template-columns: 200px 1fr; gap: 1rem;
    }
    .settings-nav { display: flex; flex-direction: column; gap: 0.25rem; }
    .settings-nav-item {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.6rem 0.85rem; border-radius: 9px;
        font-size: 0.82rem; font-weight: 500; color: var(--gray-600);
        cursor: pointer; transition: all 0.18s; border: none;
        background: transparent; font-family: 'DM Sans', sans-serif;
        text-align: left;
    }
    .settings-nav-item:hover { background: var(--gray-100); }
    .settings-nav-item.active { background: var(--purple-light); color: var(--purple); font-weight: 700; }

    .settings-body { background: white; border: 1px solid var(--gray-200); border-radius: 14px; padding: 1.5rem; }
    .settings-section { display: none; }
    .settings-section.active { display: block; }

    .settings-section-title {
        font-family: 'Urbanist', sans-serif; font-size: 0.95rem; font-weight: 700;
        color: var(--gray-900); margin-bottom: 0.25rem;
    }
    .settings-section-sub { font-size: 0.78rem; color: var(--gray-400); margin-bottom: 1.25rem; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .form-group { margin-bottom: 0.85rem; }
    .form-label { display: block; font-size: 0.78rem; font-weight: 600; color: var(--gray-600); margin-bottom: 0.35rem; }
    .form-input {
        width: 100%; padding: 0.55rem 0.85rem;
        border: 1.5px solid var(--gray-200); border-radius: 9px;
        font-size: 0.875rem; font-family: 'DM Sans', sans-serif;
        color: var(--gray-900); outline: none; transition: border-color 0.2s;
        background: var(--gray-50);
    }
    .form-input:focus { border-color: var(--purple); background: white; }
    .form-textarea { resize: vertical; min-height: 80px; }

    .btn-save {
        padding: 0.6rem 1.5rem; background: var(--purple); color: white;
        border: none; border-radius: 9px; font-size: 0.875rem; font-weight: 600;
        cursor: pointer; transition: background 0.2s; font-family: 'DM Sans', sans-serif;
    }
    .btn-save:hover { background: var(--purple-dark); }

    .photo-wrap { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem; }
    .photo-preview {
        width: 72px; height: 72px; border-radius: 50%;
        overflow: hidden; background: var(--purple-light); color: var(--purple);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; font-weight: 700; flex-shrink: 0;
    }
    .photo-preview img { width: 100%; height: 100%; object-fit: cover; }
    .btn-upload {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.4rem 0.85rem; border: 1.5px solid var(--gray-200);
        border-radius: 8px; font-size: 0.78rem; font-weight: 600;
        color: var(--gray-600); cursor: pointer; transition: all 0.18s;
    }
    .btn-upload:hover { border-color: var(--purple); color: var(--purple); }

    /* Empty */
    .profil-empty {
        text-align: center; padding: 3rem 2rem;
        background: white; border: 1px solid var(--gray-200); border-radius: 12px;
    }
    .profil-empty i { font-size: 2.5rem; color: var(--gray-200); display: block; margin-bottom: 0.75rem; }

    /* Compétences dans settings */
    .comp-category { margin-bottom: 1rem; }
    .comp-category-title {
        font-size: 0.72rem; font-weight: 700; color: var(--gray-400);
        text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 0.4rem;
    }
    .comp-tags-grid { display: flex; flex-wrap: wrap; gap: 0.4rem; }
    .comp-tag {
        display: inline-flex; align-items: center;
        padding: 0.28rem 0.7rem; border: 1.5px solid var(--gray-200);
        border-radius: 6px; font-size: 0.78rem; font-weight: 500;
        color: var(--gray-600); cursor: pointer; transition: all 0.15s;
        user-select: none;
    }
    .comp-tag:hover { border-color: var(--purple); color: var(--purple); }
    .comp-tag.selected { background: var(--purple-light); color: var(--purple); border-color: var(--purple); font-weight: 600; }

    @media (max-width: 992px) {
        .profil-content { grid-template-columns: 1fr; }
        .profil-sidebar { order: 2; }
    }
    @media (max-width: 768px) {
        .profil-identity-row {
            flex-direction: column; align-items: center; text-align: center;
        }
        .profil-name-zone { text-align: center; }
        .profil-actions-zone { justify-content: center; }
        .profil-info-row { justify-content: center; }
        .profil-social-links { margin-left: 0; }
        .profil-stats-bar { flex-wrap: wrap; }
        .profil-stat { min-width: 50%; }
        .settings-wrap { grid-template-columns: 1fr; }
        .form-row { grid-template-columns: 1fr; }
    }

    /* ─── MENTOR CENTERED HEADER ─── */
    .profil-mentor-header {
        display: flex; flex-direction: column; align-items: center;
        text-align: center; padding: 0 0 1.5rem;
        margin-top: -64px;
    }
    .profil-mentor-cam-wrap { position: relative; display: inline-block; margin-bottom: 0.85rem; }
    .profil-mentor-avatar {
        width: 128px; height: 128px; border-radius: 50%;
        border: 4px solid white; object-fit: cover;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        display: block;
    }
    .profil-mentor-avatar-text {
        width: 128px; height: 128px; border-radius: 50%;
        border: 4px solid white;
        background: var(--purple-light); color: var(--purple);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.8rem; font-weight: 800;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .profil-mentor-cam-btn {
        position: absolute; bottom: 4px; right: 4px;
        width: 26px; height: 26px; border-radius: 50%;
        background: var(--purple); color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.65rem; cursor: pointer; border: 2px solid white;
        transition: background 0.2s; text-decoration: none;
    }
    .profil-mentor-cam-btn:hover { background: var(--purple-dark); }
    .profil-mentor-badge {
        display: inline-flex; align-items: center; gap: 0.35rem;
        background: #EDE9FE; color: #7C3AED;
        padding: 0.22rem 0.7rem; border-radius: 6px;
        font-size: 0.78rem; font-weight: 600; margin-top: 0.35rem;
    }
    .profil-info-row.centered { justify-content: center; }
</style>
@endsection

@section('content')

<!-- Cover -->
<div class="profil-cover"></div>

<!-- Top card -->
<div class="profil-top-wrap">
    <div class="profil-top-inner">

        @if($utilisateur->role === 'mentor')
        <!-- ── Mentor : avatar centré ── -->
        <div class="profil-mentor-header">
            <div class="profil-mentor-cam-wrap">
                @if($utilisateur->photo)
                    <img src="{{ $utilisateur->photo_url }}" class="profil-mentor-avatar" alt="">
                @else
                    <div class="profil-mentor-avatar-text">
                        {{ strtoupper(substr($utilisateur->prenom, 0, 1)) }}
                    </div>
                @endif
                @if(Auth::id() === $utilisateur->id)
                    <a href="#" class="profil-mentor-cam-btn"
                       onclick="event.preventDefault();document.getElementById('photoQuickInput').click();">
                        <i class="fas fa-camera"></i>
                    </a>
                @endif
            </div>
            <div class="profil-name">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</div>
            <div class="profil-pseudo">{{ '@' . $utilisateur->pseudo }}</div>
            <div class="profil-mentor-badge">
                <i class="fas fa-star"></i>
                {{ $utilisateur->specialite ?? 'Mentor DevHub' }}
            </div>
            @if(Auth::id() === $utilisateur->id)
                <button class="btn-edit-profil" style="margin-top:0.9rem;"
                        onclick="document.querySelector('[data-tab=settings]').click()">
                    <i class="fas fa-edit"></i> Modifier mon profil
                </button>
            @else
                <form action="{{ route('messagerie.store') }}" method="POST" style="display:inline;margin-top:0.9rem;">
                    @csrf
                    <input type="hidden" name="destinataire_id" value="{{ $utilisateur->id }}">
                    <button type="submit" class="btn-message-profil">
                        <i class="fas fa-comment"></i> Message
                    </button>
                </form>
            @endif
        </div>
        @else
        <!-- ── Développeur : layout standard ── -->
        <div class="profil-identity-row">
            <div class="profil-avatar-zone">
                @if($utilisateur->photo)
                    <img src="{{ $utilisateur->photo_url }}" class="profil-avatar" alt="">
                @else
                    <div class="profil-avatar-text">
                        {{ strtoupper(substr($utilisateur->prenom, 0, 1)) }}
                    </div>
                @endif
                @if(Auth::id() === $utilisateur->id)
                    <a href="#" class="avatar-cam-btn"
                       onclick="event.preventDefault();document.getElementById('photoQuickInput').click();">
                        <i class="fas fa-camera"></i>
                    </a>
                @endif
            </div>

            <div class="profil-name-zone">
                <div class="profil-name">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</div>
                <div class="profil-pseudo">{{ '@' . $utilisateur->pseudo }}</div>
                @if($utilisateur->specialite)
                    <div class="profil-spec">
                        <i class="fas fa-code"></i> {{ $utilisateur->specialite }}
                    </div>
                @endif
            </div>

            <div class="profil-actions-zone">
                @if(Auth::id() === $utilisateur->id)
                    <button class="btn-edit-profil" onclick="document.querySelector('[data-tab=settings]').click()">
                        <i class="fas fa-edit"></i> Modifier
                    </button>
                @else
                    <form action="{{ route('messagerie.store') }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="destinataire_id" value="{{ $utilisateur->id }}">
                        <button type="submit" class="btn-message-profil">
                            <i class="fas fa-comment"></i> Message
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endif

        <!-- Infos + réseaux sociaux -->
        <div class="profil-info-row {{ $utilisateur->role === 'mentor' ? 'centered' : '' }}">
            @if($utilisateur->localisation)
                <div class="profil-info-item">
                    <i class="fas fa-map-marker-alt"></i> {{ $utilisateur->localisation }}
                </div>
            @endif
            <div class="profil-info-item">
                <i class="fas fa-calendar-alt"></i>
                Membre depuis {{ $utilisateur->date_inscription->format('M Y') }}
            </div>
            <div class="profil-info-item">
                <i class="fas fa-circle" style="color:#22C55E;font-size:0.5rem;"></i>
                Actif
            </div>

            <div class="profil-social-links">
                @if($utilisateur->github)
                    <a href="{{ $utilisateur->github }}" target="_blank" class="social-link" title="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                @endif
                @if($utilisateur->linkedin)
                    <a href="{{ $utilisateur->linkedin }}" target="_blank" class="social-link" title="LinkedIn">
                        <i class="fab fa-linkedin"></i>
                    </a>
                @endif
                @if($utilisateur->portfolio)
                    <a href="{{ $utilisateur->portfolio }}" target="_blank" class="social-link" title="Portfolio">
                        <i class="fas fa-globe"></i>
                    </a>
                @endif
            </div>
        </div>

        <!-- Stats bar (développeurs uniquement) -->
        @if($utilisateur->role !== 'mentor')
        @php
            $pubIds  = $utilisateur->publications()->pluck('id');
            $note    = \App\Models\Avis::whereIn('publication_id', $pubIds)->avg('note');
            $collabs = \App\Models\Candidature::where('utilisateur_id', $utilisateur->id)->where('statut','acceptée')->count();
        @endphp
        <div class="profil-stats-bar">
            <div class="profil-stat">
                <div class="profil-stat-val">{{ $utilisateur->publications()->where('statut','publié')->count() }}</div>
                <div class="profil-stat-label">Publications</div>
            </div>
            <div class="profil-stat">
                <div class="profil-stat-val">{{ number_format($utilisateur->publications()->sum('nb_vues')) }}</div>
                <div class="profil-stat-label">Vues</div>
            </div>
            <div class="profil-stat">
                <div class="profil-stat-val">{{ $utilisateur->publications()->sum('nb_likes') }}</div>
                <div class="profil-stat-label">Likes</div>
            </div>
            <div class="profil-stat">
                <div class="profil-stat-val">{{ $collabs }}</div>
                <div class="profil-stat-label">Collaborations</div>
            </div>
            <div class="profil-stat">
                <div class="profil-stat-val">{{ $note ? number_format($note,1) : '—' }}</div>
                <div class="profil-stat-label">Note moy.</div>
            </div>
        </div>
        @endif
    </div>
</div>



<!-- Contenu -->
<div class="profil-content">

    <!-- Sidebar gauche -->
    <div class="profil-sidebar">

        <!-- Bio -->
        @if($utilisateur->bio)
            <div class="profil-widget">
                <div class="profil-widget-title"><i class="fas fa-user me-1"></i>À propos</div>
                <div class="profil-bio-text">{{ $utilisateur->bio }}</div>
            </div>
        @endif

        <!-- Compétences -->
        @if($utilisateur->competences->isNotEmpty())
            <div class="profil-widget">
                <div class="profil-widget-title"><i class="fas fa-code me-1"></i>Compétences</div>
                <div class="skills-wrap">
                    @foreach($utilisateur->competences as $comp)
                        <span class="skill-chip">{{ $comp->nom }}</span>
                    @endforeach
                </div>
            </div>
        @endif


    </div>

    <!-- Contenu principal -->
    <div>
        <!-- Onglets -->
        <div class="profil-tabs">
            <button class="profil-tab active" data-tab="activity">
                @if($utilisateur->role === 'mentor')
                    <i class="fas fa-star"></i> Profil
                @else
                    <i class="fas fa-clock"></i> Activité
                    <span style="background:var(--purple-light);color:var(--purple);font-size:0.65rem;font-weight:700;min-width:16px;height:16px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;padding:0 4px;">
                        {{ $utilisateur->publications()->where('statut','publié')->count() }}
                    </span>
                @endif
            </button>
            @if(Auth::id() === $utilisateur->id)
                <button class="profil-tab" data-tab="settings">
                    <i class="fas fa-cog"></i> Paramètres
                </button>
            @endif
        </div>

        <!-- Onglet activité / profil -->
        <div class="tab-panel active" id="activity">
            @if($utilisateur->role === 'mentor')
                <div style="background:white;border:1px solid #F3F4F6;border-radius:16px;padding:2rem;text-align:center;">
                    <div style="width:52px;height:52px;border-radius:14px;background:#EDE9FE;color:#7C3AED;display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin:0 auto 1rem;">
                        <i class="fas fa-star"></i>
                    </div>
                    <div style="font-family:'Urbanist',sans-serif;font-weight:800;font-size:1rem;color:#111827;margin-bottom:0.4rem;">
                        Mentor DevHub
                    </div>
                    <p style="font-size:0.83rem;color:#9CA3AF;max-width:340px;margin:0 auto 1.25rem;line-height:1.6;">
                        {{ $utilisateur->specialite ?? 'Expert DevHub' }}
                    </p>
                    @if($utilisateur->competences->isNotEmpty())
                        <div style="display:flex;flex-wrap:wrap;gap:0.4rem;justify-content:center;">
                            @foreach($utilisateur->competences as $comp)
                                <span style="background:#F5F3FF;color:#7C3AED;padding:0.28rem 0.75rem;border-radius:6px;font-size:0.78rem;font-weight:600;">{{ $comp->nom }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
            @forelse($utilisateur->publications()->where('statut','publié')->orderBy('date_publication','desc')->get() as $pub)
                @php
                    $typeConfig = [
                        'article'             => ['Article',      '#DBEAFE', '#1D4ED8', 'fa-newspaper'],
                        'idee'                => ['Idée',          '#FEF9C3', '#854D0E', 'fa-lightbulb'],
                        'projet_personnel'    => ['Projet',        '#EDE9FE', '#7C3AED', 'fa-folder'],
                        'projet_collaboratif' => ['Collaboratif',  '#D1FAE5', '#065F46', 'fa-users'],
                    ];
                    $tc = $typeConfig[$pub->type] ?? ['', '#F3F4F6', '#6B7280', 'fa-file'];
                @endphp
                <div class="pub-card-profil" onclick="window.location='{{ route('publications.show', $pub->id) }}'">
                    <div class="pub-card-top">
                        <div class="pub-type-dot" style="background:{{ $tc[1] }};color:{{ $tc[2] }};">
                            <i class="fas {{ $tc[3] }}"></i>
                        </div>
                        <a href="{{ route('publications.show', $pub->id) }}" class="pub-card-title-link"
                           onclick="event.stopPropagation()">
                            {{ $pub->titre }}
                        </a>
                        @if(Auth::id() === $utilisateur->id)
                            <div class="pub-card-actions" onclick="event.stopPropagation()">
                                <a href="{{ route('publications.edit', $pub->id) }}" class="act-sm" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('publications.destroy', $pub->id) }}" method="POST"
                                      onsubmit="event.stopPropagation();return confirm('Supprimer ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="act-sm danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    @if($pub->contenu)
                        <div class="pub-card-desc">{{ strip_tags($pub->contenu) }}</div>
                    @endif
                    <div class="pub-card-footer">
                        <span class="pub-stat"><i class="fas fa-eye"></i> {{ number_format($pub->nb_vues) }}</span>
                        <span class="pub-stat"><i class="fas fa-heart" style="color:#E11D48;"></i> {{ $pub->nb_likes }}</span>
                        <span class="pub-stat"><i class="fas fa-comment" style="color:var(--purple);"></i> {{ $pub->nb_commentaires }}</span>
                        <span class="pub-card-date">{{ $pub->date_publication->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <div class="profil-empty">
                    <i class="fas fa-folder-open"></i>
                    <div style="font-family:'Urbanist',sans-serif;font-size:0.95rem;font-weight:700;color:var(--gray-600);margin-bottom:0.4rem;">Aucune publication</div>
                    @if(Auth::id() === $utilisateur->id)
                        <button class="btn-message-profil" data-bs-toggle="modal" data-bs-target="#modalPublication"
                                style="margin-top:0.75rem;">
                            <i class="fas fa-plus me-1"></i> Publier quelque chose
                        </button>
                    @endif
                </div>
            @endforelse
            @endif
        </div>

        <!-- Onglet paramètres -->
        @if(Auth::id() === $utilisateur->id)
        <div class="tab-panel" id="settings">
            <div class="settings-wrap">

                <!-- Nav settings -->
                <div class="settings-nav">
                    <button class="settings-nav-item active" data-setting="personal">
                        <i class="fas fa-user"></i> Profil
                    </button>
                    <button class="settings-nav-item" data-setting="password">
                        <i class="fas fa-lock"></i> Mot de passe
                    </button>
                </div>

                <div class="settings-body">

                    <!-- Profil -->
                    <div class="settings-section active" id="personal">
                        <div class="settings-section-title">Informations du profil</div>
                        <div class="settings-section-sub">Ces informations sont visibles par la communauté.</div>

                        <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
                            @csrf @method('PUT')

                            <!-- Photo -->
                            <div class="photo-wrap">
                                <div class="photo-preview">
                                    @if(Auth::user()->photo)
                                        <img src="{{ Auth::user()->photo_url }}" id="photoPreview" alt="">
                                    @else
                                        <span id="photoPlaceholder">{{ strtoupper(substr(Auth::user()->prenom,0,1)) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <label for="photoInput" class="btn-upload">
                                        <i class="fas fa-upload"></i> Changer la photo
                                    </label>
                                    <input type="file" id="photoInput" name="photo" accept="image/jpeg,image/png" style="display:none;">
                                    <p style="font-size:0.72rem;color:var(--gray-400);margin-top:0.3rem;">JPG ou PNG, max 2MB</p>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" name="prenom" class="form-input" value="{{ Auth::user()->prenom }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nom</label>
                                    <input type="text" name="nom" class="form-input" value="{{ Auth::user()->nom }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Spécialité</label>
                                <input type="text" name="specialite" class="form-input"
                                       value="{{ Auth::user()->specialite }}" placeholder="Ex: Développeur Full-Stack">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Bio</label>
                                <textarea name="bio" class="form-input form-textarea"
                                          placeholder="Parlez de vous...">{{ Auth::user()->bio }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Localisation</label>
                                <input type="text" name="localisation" class="form-input"
                                       value="{{ Auth::user()->localisation }}" placeholder="Ex: Cotonou, Bénin">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label"><i class="fab fa-github me-1"></i>GitHub</label>
                                    <input type="url" name="github" class="form-input"
                                           value="{{ Auth::user()->github }}" placeholder="https://github.com/...">
                                </div>
                                <div class="form-group">
                                    <label class="form-label"><i class="fab fa-linkedin me-1"></i>LinkedIn</label>
                                    <input type="url" name="linkedin" class="form-input"
                                           value="{{ Auth::user()->linkedin }}" placeholder="https://linkedin.com/in/...">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-globe me-1"></i>Portfolio</label>
                                <input type="url" name="portfolio" class="form-input"
                                       value="{{ Auth::user()->portfolio }}" placeholder="https://...">
                            </div>

                            <!-- Compétences -->
                            <div class="form-group" style="margin-top:1.25rem;">
                                <label class="form-label" style="margin-bottom:0.75rem;">
                                    <i class="fas fa-code me-1"></i>Compétences
                                </label>
                                @php $userCompIds = Auth::user()->competences->pluck('id')->toArray(); @endphp
                                @foreach($toutesCompetences as $categorie => $liste)
                                    <div class="comp-category">
                                        <div class="comp-category-title">{{ $categorie }}</div>
                                        <div class="comp-tags-grid">
                                            @foreach($liste as $comp)
                                                <label class="comp-tag {{ in_array($comp->id, $userCompIds) ? 'selected' : '' }}">
                                                    <input type="checkbox" name="competences[]" value="{{ $comp->id }}"
                                                        {{ in_array($comp->id, $userCompIds) ? 'checked' : '' }}
                                                        style="display:none;">
                                                    {{ $comp->nom }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn-save">
                                <i class="fas fa-save me-1"></i> Enregistrer
                            </button>
                        </form>
                    </div>

                    <!-- Mot de passe -->
                    <div class="settings-section" id="password">
                        <div class="settings-section-title">Changer le mot de passe</div>
                        <div class="settings-section-sub">Choisissez un mot de passe fort d'au moins 8 caractères.</div>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Mot de passe actuel</label>
                                <input type="password" name="current_password" class="form-input" placeholder="••••••••">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nouveau mot de passe</label>
                                <input type="password" name="mot_de_passe" class="form-input" placeholder="Minimum 8 caractères">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirmer le mot de passe</label>
                                <input type="password" name="mot_de_passe_confirmation" class="form-input" placeholder="••••••••">
                            </div>
                            <button type="submit" class="btn-save">
                                <i class="fas fa-lock me-1"></i> Changer le mot de passe
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Quick photo form -->
<form id="quickPhotoForm" method="POST" action="{{ route('profil.update') }}"
      enctype="multipart/form-data" style="display:none;">
    @csrf @method('PUT')
    <input type="file" id="photoQuickInput" name="photo" accept="image/jpeg,image/png"
           onchange="this.form.submit()">
</form>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Onglets principaux
    document.querySelectorAll('.profil-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.profil-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            var target = document.getElementById(this.dataset.tab);
            if (target) target.classList.add('active');
        });
    });

    // Onglets settings
    document.querySelectorAll('.settings-nav-item').forEach(function(item) {
        item.addEventListener('click', function() {
            document.querySelectorAll('.settings-nav-item').forEach(i => i.classList.remove('active'));
            document.querySelectorAll('.settings-section').forEach(s => s.classList.remove('active'));
            this.classList.add('active');
            var target = document.getElementById(this.dataset.setting);
            if (target) target.classList.add('active');
        });
    });

    // Toggle compétences dans settings
    document.querySelectorAll('#personal .comp-tag').forEach(function(tag) {
        tag.addEventListener('click', function(e) {
            e.preventDefault();
            var checkbox = tag.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            tag.classList.toggle('selected', checkbox.checked);
        });
    });

    // Preview photo
    document.getElementById('photoInput')?.addEventListener('change', function() {
        if (!this.files[0]) return;
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('photoPreview');
            var placeholder = document.getElementById('photoPlaceholder');
            if (preview) {
                preview.src = e.target.result;
            } else if (placeholder) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.id = 'photoPreview';
                img.style.cssText = 'width:100%;height:100%;object-fit:cover;';
                placeholder.parentNode.replaceChild(img, placeholder);
            }
        };
        reader.readAsDataURL(this.files[0]);
    });
});
</script>
@endsection