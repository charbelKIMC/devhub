@extends('layouts.app')

@section('title', 'Mes publications - DevHub')
@section('secondary_nav', true)

@section('styles')
<style>
    .filters-bar {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 14px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
    }
    .filters-bar-title {
        font-size: .78rem; font-weight: 700; color: var(--gray-400);
        text-transform: uppercase; letter-spacing: .5px; white-space: nowrap; flex-shrink: 0;
    }
    .filter-sep { width: 1px; height: 20px; background: var(--gray-200); flex-shrink: 0; }
    .filter-group { display: flex; align-items: center; gap: .4rem; flex-shrink: 0; }
    .filter-label { font-size: .75rem; font-weight: 600; color: var(--gray-500); white-space: nowrap; }
    .filter-select, .filter-date {
        padding: .4rem .75rem; border: 1.5px solid var(--gray-200); border-radius: 8px;
        font-size: .82rem; color: var(--gray-700); background: var(--gray-50);
        cursor: pointer; outline: none; transition: border-color .2s;
    }
    .filter-select:focus, .filter-date:focus { border-color: var(--purple); }
    .filter-date { padding: .38rem .75rem; }
    .filter-range { display: flex; align-items: center; gap: .3rem; }
    .filter-range span { font-size: .72rem; color: var(--gray-400); }
    .btn-filter {
        padding: .4rem 1rem; background: var(--purple); color: white; border: none;
        border-radius: 8px; font-size: .82rem; font-weight: 600; cursor: pointer;
        transition: background .2s; white-space: nowrap;
    }
    .btn-filter:hover { background: var(--purple-dark); }
    .btn-reset {
        padding: .4rem .85rem; background: transparent; color: var(--gray-400);
        border: 1px solid var(--gray-200); border-radius: 8px; font-size: .82rem;
        font-weight: 500; cursor: pointer; transition: all .2s; text-decoration: none;
        display: inline-flex; align-items: center; gap: .3rem;
    }
    .btn-reset:hover { background: var(--gray-100); color: var(--gray-700); }
    .active-filters { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; margin-bottom: 1rem; }
    .filter-tag {
        display: inline-flex; align-items: center; gap: .35rem; padding: .25rem .65rem;
        background: var(--purple-light); color: var(--purple); border-radius: 20px;
        font-size: .75rem; font-weight: 600;
    }
    .filter-tag button { background: none; border: none; cursor: pointer; color: var(--purple); font-size: .7rem; padding: 0; line-height: 1; }
    .results-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; flex-wrap: wrap; gap: .75rem; }
    .results-count { font-size: .82rem; color: var(--gray-500); }
    .results-count strong { color: var(--gray-900); font-weight: 700; }
    .pub-table-wrap { background: white; border: 1px solid var(--gray-200); border-radius: 14px; overflow: hidden; }
    .pub-table { width: 100%; border-collapse: collapse; }
    .pub-table thead { background: var(--gray-50); border-bottom: 1px solid var(--gray-200); }
    .pub-table th {
        padding: .75rem 1rem; text-align: left; font-size: .72rem; font-weight: 700;
        color: var(--gray-400); text-transform: uppercase; letter-spacing: .5px; white-space: nowrap;
    }
    .pub-table td {
        padding: .85rem 1rem; font-size: .875rem; border-bottom: 1px solid var(--gray-100);
        color: var(--gray-700); vertical-align: middle;
    }
    .pub-table tr:last-child td { border-bottom: none; }
    .pub-table tr:hover td { background: var(--gray-50); }
    .pub-title-cell { display: flex; align-items: center; gap: .65rem; }
    .pub-type-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .82rem; flex-shrink: 0; }
    .icon-article { background: #DBEAFE; color: #1D4ED8; }
    .icon-idee    { background: #FEF9C3; color: #854D0E; }
    .icon-projet  { background: var(--purple-light); color: var(--purple); }
    .icon-collab  { background: #D1FAE5; color: #065F46; }
    .pub-name { font-weight: 600; color: var(--gray-900); text-decoration: none; font-size: .875rem; display: block; line-height: 1.3; transition: color .15s; }
    .pub-name:hover { color: var(--purple); }
    .pub-name-sub { font-size: .72rem; color: var(--gray-400); margin-top: .1rem; }
    .statut-badge { display: inline-flex; align-items: center; gap: .3rem; padding: .25rem .65rem; border-radius: 6px; font-size: .75rem; font-weight: 600; white-space: nowrap; }
    .statut-badge .dot { width: 6px; height: 6px; border-radius: 50%; }
    .badge-publie    { background: #D1FAE5; color: #065F46; } .badge-publie .dot    { background: #22C55E; }
    .badge-brouillon { background: var(--gray-100); color: var(--gray-500); } .badge-brouillon .dot { background: var(--gray-400); }
    .badge-archive   { background: #FEF9C3; color: #854D0E; } .badge-archive .dot   { background: #F59E0B; }
    .vues-cell { display: flex; align-items: center; gap: .5rem; }
    .vues-bar { width: 60px; height: 5px; background: var(--gray-100); border-radius: 3px; overflow: hidden; flex-shrink: 0; }
    .vues-bar-fill { height: 100%; background: var(--purple); border-radius: 3px; transition: width .6s ease; }
    .vues-val { font-weight: 600; color: var(--gray-700); font-size: .82rem; }
    .actions-cell { display: flex; align-items: center; gap: .4rem; }
    .act-btn {
        width: 30px; height: 30px; border-radius: 7px; display: flex; align-items: center;
        justify-content: center; font-size: .78rem; cursor: pointer; transition: all .18s;
        text-decoration: none; border: 1px solid var(--gray-200); background: transparent; color: var(--gray-500);
    }
    .act-btn:hover { background: var(--purple-light); color: var(--purple); border-color: var(--purple); }
    .act-btn.danger:hover { background: #FEE2E2; color: #DC2626; border-color: #FCA5A5; }
    .pagination-zone {
        display: flex; align-items: center; justify-content: space-between;
        padding: .85rem 1.25rem; border-top: 1px solid var(--gray-100); flex-wrap: wrap; gap: .5rem;
    }
    .pagination-info { font-size: .78rem; color: var(--gray-400); }
    .pub-empty { padding: 4rem 2rem; text-align: center; color: var(--gray-400); }
    .pub-empty i { font-size: 3rem; color: var(--gray-200); display: block; margin-bottom: .75rem; }
    .pub-empty h3 { font-size: 1rem; font-weight: 700; color: var(--gray-600); margin-bottom: .4rem; }
    .page-header-pub { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: .75rem; }
    .page-header-pub h1 { font-size: 1.1rem; font-weight: 800; color: var(--gray-900); }
    .btn-publish {
        display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1.1rem;
        background: var(--purple); color: white; border: none; border-radius: 9px;
        font-size: .875rem; font-weight: 600; cursor: pointer; transition: background .2s; text-decoration: none;
    }
    .btn-publish:hover { background: var(--purple-dark); color: white; }
    .quick-stats { display: grid; grid-template-columns: repeat(4,1fr); gap: .75rem; margin-bottom: 1.25rem; }
    .qs-card { background: white; border: 1px solid var(--gray-200); border-radius: 12px; padding: .85rem 1rem; display: flex; align-items: center; gap: .75rem; }
    .qs-icon { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: .9rem; flex-shrink: 0; }
    .qs-val { font-size: 1.15rem; font-weight: 600; color: var(--gray-900); line-height: 1; }
    .qs-label { font-size: .72rem; color: var(--gray-400); margin-top: .1rem; }
    @media (max-width: 992px) { .quick-stats { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 768px) {
        .pub-table th:nth-child(4), .pub-table td:nth-child(4) { display: none; }
        .quick-stats { grid-template-columns: repeat(2,1fr); }
    }
    @media (max-width: 576px) {
        .pub-table th:nth-child(5), .pub-table td:nth-child(5),
        .pub-table th:nth-child(6), .pub-table td:nth-child(6) { display: none; }
    }
</style>
@endsection

@section('content')
    <main class="main-content">

        <div class="page-header-pub">
            <p style="font-size:.82rem;color:var(--gray-400);">Gerez et suivez toutes vos publications</p>
            <button class="btn-publish" data-bs-toggle="modal" data-bs-target="#modalPublication">
                <i class="fas fa-plus"></i> <span class="mtitre">Nouvelle publication</span> 
            </button>
        </div>

        @php
            $totalPubs  = Auth::user()->publications()->count();
            $publiees   = Auth::user()->publications()->where('statut','publie')->count();
            $totalVues  = Auth::user()->publications()->sum('nb_vues');
            $totalLikes = Auth::user()->publications()->sum('nb_likes');
        @endphp

        <div class="quick-stats">
            <div class="qs-card">
                <div class="qs-icon" style="background:var(--purple-light);color:var(--purple);"><i class="fas fa-layer-group"></i></div>
                <div><div class="qs-val">{{ $totalPubs }}</div><div class="qs-label">Total</div></div>
            </div>
            <div class="qs-card">
                <div class="qs-icon" style="background:#D1FAE5;color:#065F46;"><i class="fas fa-globe"></i></div>
                <div><div class="qs-val">{{ $publiees }}</div><div class="qs-label">Publiees</div></div>
            </div>
            <div class="qs-card">
                <div class="qs-icon" style="background:#DBEAFE;color:#1D4ED8;"><i class="fas fa-eye"></i></div>
                <div><div class="qs-val">{{ number_format($totalVues) }}</div><div class="qs-label">Vues</div></div>
            </div>
            <div class="qs-card">
                <div class="qs-icon" style="background:#FCE7F3;color:#BE185D;"><i class="fas fa-heart"></i></div>
                <div><div class="qs-val">{{ number_format($totalLikes) }}</div><div class="qs-label">Likes</div></div>
            </div>
        </div>

        <form method="GET" action="{{ route('dashboard.publications') }}" id="filterForm">
            <div class="filters-bar">
                <span class="filters-bar-title"><i class="fas fa-filter me-1"></i>Filtres</span>
                <div class="filter-sep"></div>

                <div class="filter-group">
                    <span class="filter-label">Type</span>
                    <select name="type" class="filter-select">
                        <option value="">Tous</option>
                        <option value="article"             {{ request('type') == 'article'             ? 'selected' : '' }}>Article</option>
                        <option value="idee"                {{ request('type') == 'idee'                ? 'selected' : '' }}>Idee</option>
                        <option value="projet_personnel"    {{ request('type') == 'projet_personnel'    ? 'selected' : '' }}>Projet perso</option>
                        <option value="projet_collaboratif" {{ request('type') == 'projet_collaboratif' ? 'selected' : '' }}>Collaboratif</option>
                    </select>
                </div>
                <div class="filter-sep"></div>

                <div class="filter-group">
                    <span class="filter-label">Statut</span>
                    <select name="statut" class="filter-select">
                        <option value="">Tous</option>
                        <option value="publie"    {{ request('statut') == 'publie'    ? 'selected' : '' }}>Publie</option>
                        <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                        <option value="archive"   {{ request('statut') == 'archive'   ? 'selected' : '' }}>Archive</option>
                    </select>
                </div>
                <div class="filter-sep"></div>

                <div class="filter-group">
                    <span class="filter-label">Periode</span>
                    <div class="filter-range">
                        <input type="date" name="date_debut" class="filter-date" value="{{ request('date_debut') }}">
                        <span>-</span>
                        <input type="date" name="date_fin"   class="filter-date" value="{{ request('date_fin') }}">
                    </div>
                </div>
                <div class="filter-sep"></div>

                <div class="filter-group">
                    <span class="filter-label">Vues min.</span>
                    <select name="vues_min" class="filter-select">
                        <option value="">Toutes</option>
                        <option value="10"  {{ request('vues_min') == '10'  ? 'selected' : '' }}>+10</option>
                        <option value="50"  {{ request('vues_min') == '50'  ? 'selected' : '' }}>+50</option>
                        <option value="100" {{ request('vues_min') == '100' ? 'selected' : '' }}>+100</option>
                        <option value="500" {{ request('vues_min') == '500' ? 'selected' : '' }}>+500</option>
                    </select>
                </div>

                <button type="submit" class="btn-filter"><i class="fas fa-search me-1"></i> Filtrer</button>

                @if(request()->hasAny(['type','statut','date_debut','date_fin','vues_min']))
                    <a href="{{ route('dashboard.publications') }}" class="btn-reset">
                        <i class="fas fa-times"></i> Reinitialiser
                    </a>
                @endif
            </div>

            <input type="hidden" name="sort" id="hiddenSort" value="{{ request('sort', 'date_desc') }}">

            @if(request()->hasAny(['type','statut','date_debut','date_fin','vues_min']))
                <div class="active-filters">
                    <span style="font-size:.72rem;color:var(--gray-400);font-weight:600;">Actifs :</span>
                    @if(request('type'))
                        <div class="filter-tag">
                            Type : {{ request('type') }}
                            <button type="button" onclick="removeFilter('type')"><i class="fas fa-times"></i></button>
                        </div>
                    @endif
                    @if(request('statut'))
                        <div class="filter-tag">
                            Statut : {{ request('statut') }}
                            <button type="button" onclick="removeFilter('statut')"><i class="fas fa-times"></i></button>
                        </div>
                    @endif
                    @if(request('date_debut') || request('date_fin'))
                        <div class="filter-tag">
                            {{ request('date_debut','?') }} - {{ request('date_fin','?') }}
                            <button type="button" onclick="removeFilter('date_debut');removeFilter('date_fin')"><i class="fas fa-times"></i></button>
                        </div>
                    @endif
                    @if(request('vues_min'))
                        <div class="filter-tag">
                            +{{ request('vues_min') }} vues
                            <button type="button" onclick="removeFilter('vues_min')"><i class="fas fa-times"></i></button>
                        </div>
                    @endif
                </div>
            @endif
        </form>

        <div class="results-bar">
            <div class="results-count">
                <strong>{{ $publications->total() }}</strong> publication{{ $publications->total() > 1 ? 's' : '' }}
            </div>
            <div style="display:flex;align-items:center;gap:.5rem;">
                <span style="font-size:.78rem;color:var(--gray-400);">Trier par</span>
                <select class="filter-select" id="sortSelect" onchange="changeSort(this.value)">
                    <option value="date_desc"  {{ request('sort','date_desc') == 'date_desc'  ? 'selected' : '' }}>Plus recents</option>
                    <option value="date_asc"   {{ request('sort') == 'date_asc'   ? 'selected' : '' }}>Plus anciens</option>
                    <option value="vues_desc"  {{ request('sort') == 'vues_desc'  ? 'selected' : '' }}>Plus vus</option>
                    <option value="likes_desc" {{ request('sort') == 'likes_desc' ? 'selected' : '' }}>Plus aimes</option>
                    <option value="titre_asc"  {{ request('sort') == 'titre_asc'  ? 'selected' : '' }}>Titre A-Z</option>
                </select>
            </div>
        </div>

        @php
            $maxVues = Auth::user()->publications()->max('nb_vues') ?: 1;
            $typeIcons  = ['article' => 'fa-newspaper', 'idee' => 'fa-lightbulb', 'projet_personnel' => 'fa-folder', 'projet_collaboratif' => 'fa-users'];
            $typeLabels = ['article' => 'Article', 'idee' => 'Idee', 'projet_personnel' => 'Projet', 'projet_collaboratif' => 'Collab.'];
            $typeClasses = ['article' => 'icon-article', 'idee' => 'icon-idee', 'projet_personnel' => 'icon-projet', 'projet_collaboratif' => 'icon-collab'];
        @endphp

        <div class="pub-table-wrap">
            @if($publications->isNotEmpty())
                <table class="pub-table">
                    <thead>
                        <tr>
                            <th style="width:38%;">Publication</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Vues</th>
                            <th>Likes</th>
                            <th>Date</th>
                            <th style="width:100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($publications as $pub)
                            @php
                                $pct = min(100, round(($pub->nb_vues / $maxVues) * 100));
                                $ic  = $typeClasses[$pub->type] ?? 'icon-article';
                            @endphp
                            <tr>
                                <td>
                                    <div class="pub-title-cell">
                                        <div class="pub-type-icon {{ $ic }}">
                                            <i class="fas {{ $typeIcons[$pub->type] ?? 'fa-file' }}"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('publications.show', $pub->id) }}" class="pub-name">
                                                {{ Str::limit($pub->titre, 16) }}
                                            </a>
                                            <div class="pub-name-sub">{{ $pub->nb_commentaires }} commentaire{{ $pub->nb_commentaires > 1 ? 's' : '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size:.78rem;color:var(--gray-500);font-weight:500;">
                                        {{ $typeLabels[$pub->type] ?? $pub->type }}
                                    </span>
                                </td>
                                <td>
                                    @if($pub->statut === 'publie' || $pub->statut === 'publié')
                                        <span class="statut-badge badge-publie"><span class="dot"></span> Publie</span>
                                    @elseif($pub->statut === 'brouillon')
                                        <span class="statut-badge badge-brouillon"><span class="dot"></span> Brouillon</span>
                                    @else
                                        <span class="statut-badge badge-archive"><span class="dot"></span> Archive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="vues-cell">
                                        <div class="vues-bar">
                                            <div class="vues-bar-fill" style="width:{{ $pct }}%;"></div>
                                        </div>
                                        <span class="vues-val">{{ number_format($pub->nb_vues) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size:.82rem;font-weight:600;color:var(--gray-700);">
                                        <i class="fas fa-heart me-1" style="color:#E11D48;font-size:.75rem;"></i>
                                        {{ $pub->nb_likes }}
                                    </span>
                                </td>
                                <td style="font-size:.78rem;color:var(--gray-400);white-space:nowrap;">
                                    {{ $pub->date_publication->format('d M Y') }}
                                    <div style="font-size:.7rem;margin-top:.1rem;">{{ $pub->date_publication->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        <a href="{{ route('publications.show', $pub->id) }}" class="act-btn" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('publications.edit', $pub->id) }}" class="act-btn" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('publications.destroy', $pub->id) }}" method="POST"
                                            data-confirm="La publication {{ $pub->titre }} sera définitivement supprimé."
                                            data-title="Supprimer cette publication ?"
                                            data-type="danger" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="act-btn danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($publications->hasPages())
                    <div class="pagination-zone">
                        <div class="pagination-info">
                            {{ $publications->firstItem() }}-{{ $publications->lastItem() }} sur {{ $publications->total() }}
                        </div>
                        {{ $publications->appends(request()->query())->links() }}
                    </div>
                @endif

            @else
                <div class="pub-empty">
                    <i class="fas fa-folder-open"></i>
                    <h3>
                        @if(request()->hasAny(['type','statut','date_debut','date_fin','vues_min']))
                            Aucun resultat pour ces filtres
                        @else
                            Aucune publication pour l instant
                        @endif
                    </h3>
                    <p style="font-size:.85rem;margin-top:.25rem;">
                        @if(request()->hasAny(['type','statut','date_debut','date_fin','vues_min']))
                            <a href="{{ route('dashboard.publications') }}" style="color:var(--purple);font-weight:600;">Reinitialiser les filtres</a>
                        @else
                            Creez votre premiere publication.
                        @endif
                    </p>
                </div>
            @endif
        </div>

    </main>
@endsection

@section('scripts')
    <script>
        function removeFilter(name) {
            var url = new URL(window.location.href);
            url.searchParams.delete(name);
            window.location.href = url.toString();
        }

        function changeSort(val) {
            var url = new URL(window.location.href);
            url.searchParams.set('sort', val);
            window.location.href = url.toString();
        }

        document.addEventListener('DOMContentLoaded', function() {
            var bars = document.querySelectorAll('.vues-bar-fill');
            if (!bars.length) return;
            var obs = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var el = entry.target;
                        var w = el.style.width;
                        el.style.width = '0';
                        setTimeout(function() { el.style.width = w; }, 100);
                        obs.unobserve(el);
                    }
                });
            }, { threshold: 0.1 });
            bars.forEach(function(b) { obs.observe(b); });
        });
    </script>
@endsection