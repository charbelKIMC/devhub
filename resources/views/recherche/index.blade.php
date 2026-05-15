@extends('layouts.app')
@section('title', 'Recherche – DevHub')
@section('styles')
<style>
    .recherche-container { max-width: 900px; margin: 2rem auto; padding: 0 1.5rem; }
    .search-bar-main { display: flex; gap: 0.75rem; margin-bottom: 1.5rem; }
    .search-input-main { flex: 1; padding: 0.75rem 1.25rem; border: 2px solid var(--gray-200); border-radius: 12px; font-size: 1rem; font-family: 'DM Sans', sans-serif; outline: none; transition: border-color 0.2s; }
    .search-input-main:focus { border-color: var(--purple); }
    .btn-search { background: var(--purple); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
    .btn-search:hover { background: var(--purple-dark); }
    .type-filter { display: flex; gap: 0.5rem; margin-bottom: 2rem; }
    .type-filter-btn { padding: 0.4rem 1rem; border-radius: 20px; border: 1.5px solid var(--gray-200); background: white; color: var(--gray-500); font-size: 0.85rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .type-filter-btn:hover, .type-filter-btn.active { background: var(--purple); color: white; border-color: var(--purple); }
    .section-title-search { font-family: 'Urbanist', sans-serif; font-size: 1rem; font-weight: 700; color: var(--gray-700); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .result-pub-card { background: white; border: 1px solid var(--gray-200); border-radius: 12px; padding: 1.25rem; margin-bottom: 0.75rem; transition: all 0.2s; text-decoration: none; display: block; }
    .result-pub-card:hover { box-shadow: 0 4px 16px rgba(124,58,237,0.08); border-color: var(--purple); }
    .result-pub-title { font-family: 'Urbanist', sans-serif; font-size: 1rem; font-weight: 700; color: var(--gray-900); margin-bottom: 0.4rem; }
    .result-pub-meta { font-size: 0.82rem; color: var(--gray-400); }
    .result-pub-excerpt { font-size: 0.875rem; color: var(--gray-500); line-height: 1.5; margin-top: 0.5rem; }
    .result-dev-card { background: white; border: 1px solid var(--gray-200); border-radius: 12px; padding: 1rem; display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem; transition: all 0.2s; text-decoration: none; }
    .result-dev-card:hover { box-shadow: 0 4px 16px rgba(124,58,237,0.08); border-color: var(--purple); }
    .result-dev-avatar { width: 52px; height: 52px; border-radius: 50%; background: var(--purple-light); color: var(--purple); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 700; flex-shrink: 0; overflow: hidden; }
    .result-dev-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .result-dev-name { font-weight: 700; color: var(--gray-900); font-size: 0.95rem; }
    .result-dev-spec { font-size: 0.82rem; color: var(--gray-400); }
    .no-results { text-align: center; padding: 3rem 1rem; color: var(--gray-400); }
    .no-results i { font-size: 3rem; color: var(--gray-200); margin-bottom: 1rem; display: block; }
</style>
@endsection

@section('content')
<div class="recherche-container">

    <!-- Barre de recherche -->
    <form method="GET" action="{{ route('recherche.index') }}" class="search-bar-main">
        <input
            type="text"
            name="q"
            class="search-input-main"
            placeholder="Rechercher une publication, un développeur..."
            value="{{ $q }}"
            autofocus
        >
        <button type="submit" class="btn-search">
            <i class="fas fa-search me-2"></i>Rechercher
        </button>
    </form>

    <!-- Filtres -->
    <div class="type-filter">
        <a href="{{ route('recherche.index') }}?q={{ $q }}&type=all"
           class="type-filter-btn {{ $type === 'all' ? 'active' : '' }}">Tout</a>
        <a href="{{ route('recherche.index') }}?q={{ $q }}&type=publication"
           class="type-filter-btn {{ $type === 'publication' ? 'active' : '' }}">Publications</a>
        <a href="{{ route('recherche.index') }}?q={{ $q }}&type=utilisateur"
           class="type-filter-btn {{ $type === 'utilisateur' ? 'active' : '' }}">Développeurs</a>
    </div>

    @if($q)
        <!-- Publications -->
        @if($publications->isNotEmpty() && in_array($type, ['all', 'publication']))
            <div class="section-title-search">Publications ({{ $publications->count() }})</div>
            @foreach($publications as $pub)
                <a href="{{ route('publications.show', $pub->id) }}" class="result-pub-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="result-pub-title">{{ $pub->titre }}</div>
                        @php
                            $badges = ['article'=>['Article','badge-article'],'idee'=>['Idée','badge-idee'],'projet_personnel'=>['Projet','personal-badge'],'projet_collaboratif'=>['Collaboratif','collaborative-badge']];
                            $b = $badges[$pub->type] ?? [$pub->type,''];
                        @endphp
                        <span class="review-type-badge {{ $b[1] }}">{{ $b[0] }}</span>
                    </div>
                    <div class="result-pub-meta">
                        Par {{ $pub->auteur->prenom }} {{ $pub->auteur->nom }} · {{ $pub->date_publication->diffForHumans() }}
                    </div>
                    @if($pub->contenu)
                        <div class="result-pub-excerpt">{{ Str::limit(strip_tags($pub->contenu), 120) }}</div>
                    @endif
                </a>
            @endforeach
        @endif

        <!-- Développeurs -->
        @if($utilisateurs->isNotEmpty() && in_array($type, ['all', 'utilisateur']))
            <div class="section-title-search mt-3">Développeurs ({{ $utilisateurs->count() }})</div>
            @foreach($utilisateurs as $dev)
                <a href="{{ route('profil.show', $dev->id) }}" class="result-dev-card">
                    <div class="result-dev-avatar">
                        @if($dev->photo)
                            <img src="{{ asset('storage/' . $dev->photo) }}" alt="">
                        @else
                            {{ strtoupper(substr($dev->prenom, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <div class="result-dev-name">{{ $dev->prenom }} {{ $dev->nom }}</div>
                        <div class="result-dev-spec">{{ $dev->specialite ?? $dev->role }} · {{ '@' . $dev->pseudo }}</div>
                    </div>
                </a>
            @endforeach
        @endif

        @if($publications->isEmpty() && $utilisateurs->isEmpty())
            <div class="no-results">
                <i class="fas fa-search"></i>
                <p>Aucun résultat pour <strong>"{{ $q }}"</strong></p>
            </div>
        @endif

    @else
        <div class="no-results">
            <i class="fas fa-search"></i>
            <p>Entrez un mot-clé pour lancer la recherche.</p>
        </div>
    @endif

</div>
@endsection