@extends('layouts.app')
@section('title', 'Statistiques – DevHub')
@section('secondary_nav', true)

@section('styles')
<style>
    .stats-page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;
    }
    .stats-page-title {
        font-family: 'Urbanist', sans-serif;
        font-size: 1.1rem; font-weight: 800; color: var(--gray-900);
    }
    .stats-page-sub { font-size: 0.82rem; color: var(--gray-400); margin-top: 0.15rem; }

    /* ─── KPI GRID ─── */
    .kpi-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 0.85rem; margin-bottom: 1.25rem;
    }

    .kpi-card {
        background: white; border: 1px solid var(--gray-200);
        border-radius: 14px; padding: 1.1rem 1.25rem;
        display: flex; align-items: center; gap: 1rem;
        transition: all 0.18s;
    }
    .kpi-card:hover { border-color: var(--purple); box-shadow: 0 4px 16px rgba(124,58,237,0.08); }

    .kpi-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }

    .kpi-data { flex: 1; min-width: 0; }

    .kpi-val {
        font-family: 'Urbanist', sans-serif;
        font-size: 1.6rem; font-weight: 600; color: var(--gray-900); line-height: 1;
    }

    .kpi-label { font-size: 0.78rem; color: var(--gray-500); margin-top: 0.2rem; }

    .kpi-trend {
        font-size: 0.72rem; font-weight: 600; margin-top: 0.25rem;
        display: flex; align-items: center; gap: 0.25rem;
    }

    /* ─── SECTION TITRE ─── */
    .section-title {
        font-family: 'Urbanist', sans-serif; font-size: 0.9rem; font-weight: 700;
        color: var(--gray-700); margin-bottom: 0.85rem;
        display: flex; align-items: center; gap: 0.5rem;
    }

    /* ─── TOP PUBLICATIONS ─── */
    .top-pubs-wrap {
        background: white; border: 1px solid var(--gray-200);
        border-radius: 14px; overflow: hidden; margin-bottom: 1.25rem;
    }

    .top-pub-row {
        display: flex; align-items: center; gap: 1rem;
        padding: 0.9rem 1.25rem; border-bottom: 1px solid var(--gray-100);
        transition: background 0.15s;
    }
    .top-pub-row:last-child { border-bottom: none; }
    .top-pub-row:hover { background: var(--gray-50); }

    .top-rank {
        font-family: 'Urbanist', sans-serif; font-size: 1.1rem; font-weight: 600;
        color: var(--gray-200); width: 28px; flex-shrink: 0; text-align: center;
    }
    .top-pub-row:nth-child(1) .top-rank { color: #F59E0B; }
    .top-pub-row:nth-child(2) .top-rank { color: var(--gray-400); }
    .top-pub-row:nth-child(3) .top-rank { color: #CD7F32; }

    .top-pub-info { flex: 1; min-width: 0; }

    .top-pub-title {
        font-size: 0.875rem; font-weight: 600; color: var(--gray-900);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        text-decoration: none; display: block; transition: color 0.15s;
    }
    .top-pub-title:hover { color: var(--purple); }

    .top-pub-meta {
        display: flex; align-items: center; gap: 0.65rem;
        margin-top: 0.2rem; flex-wrap: wrap;
    }
    .top-pub-meta-item { font-size: 0.72rem; color: var(--gray-400); display: flex; align-items: center; gap: 0.25rem; }

    .top-pub-bar-wrap {
        width: 120px; flex-shrink: 0;
    }
    .top-pub-bar {
        height: 6px; background: var(--gray-100);
        border-radius: 3px; overflow: hidden; margin-bottom: 0.25rem;
    }
    .top-pub-bar-fill {
        height: 100%; background: var(--purple);
        border-radius: 3px; transition: width 0.6s ease;
    }
    .top-pub-vues { font-size: 0.72rem; color: var(--gray-500); font-weight: 600; text-align: right; }

    /* ─── RÉPARTITION TYPES ─── */
    .types-grid {
        display: grid; grid-template-columns: repeat(4,1fr);
        gap: 0.75rem; margin-bottom: 1.25rem;
    }

    .type-card {
        background: white; border: 1px solid var(--gray-200);
        border-radius: 12px; padding: 1rem;
        display: flex; flex-direction: column; align-items: center;
        gap: 0.5rem; text-align: center;
        transition: all 0.18s;
    }
    .type-card:hover { border-color: var(--purple); transform: translateY(-2px); }

    .type-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
    }
    .type-val { font-family: 'Urbanist',sans-serif; font-size: 1.25rem; font-weight: 600; color: var(--gray-900); }
    .type-label { font-size: 0.72rem; color: var(--gray-400); }
    .type-bar {
        width: 100%; height: 4px; background: var(--gray-100);
        border-radius: 2px; overflow: hidden;
    }
    .type-bar-fill { height: 100%; border-radius: 2px; transition: width 0.6s ease; }

    /* ─── ENGAGEMENT ─── */
    .engagement-grid {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 0.85rem; margin-bottom: 1.25rem;
    }

    .engagement-card {
        background: white; border: 1px solid var(--gray-200);
        border-radius: 14px; padding: 1.1rem 1.25rem;
    }

    .engagement-big {
        font-family: 'Urbanist', sans-serif;
        font-size: 2rem; font-weight: 600; color: var(--gray-900);
        line-height: 1; margin: 0.5rem 0 0.25rem;
    }
    .engagement-label { font-size: 0.78rem; color: var(--gray-500); }
    .engagement-sub { font-size: 0.72rem; color: var(--gray-400); margin-top: 0.25rem; }

    @media (max-width: 992px) { .kpi-grid { grid-template-columns: repeat(2,1fr); } .types-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 768px)  { .kpi-grid { grid-template-columns: 1fr; } .engagement-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<main class="main-content">

    @php
        // Répartition par type
        $pubParType = \App\Models\Publication::where('auteur_id', Auth::id())
            ->where('statut', 'publié')
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $totalPub = $pubParType->sum();

        $typeConfig = [
            'article'             => ['Article',      '#DBEAFE', '#1D4ED8', 'fa-newspaper'],
            'idee'                => ['Idée',          '#FEF9C3', '#854D0E', 'fa-lightbulb'],
            'projet_personnel'    => ['Projet perso',  '#EDE9FE', '#7C3AED', 'fa-folder'],
            'projet_collaboratif' => ['Collaboratif',  '#D1FAE5', '#065F46', 'fa-users'],
        ];

        // Taux engagement
        $tauxLike    = $stats['total_vues'] > 0 ? round(($stats['total_likes'] / $stats['total_vues']) * 100, 1) : 0;
        $tauxComment = $stats['total_vues'] > 0 ? round(($stats['total_commentaires'] / $stats['total_vues']) * 100, 1) : 0;

        // Moyenne vues par publication
        $moyenneVues = $stats['total_publications'] > 0
            ? round($stats['total_vues'] / $stats['total_publications'])
            : 0;
    @endphp

    <!-- KPI Grid -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon" style="background:var(--purple-light);color:var(--purple);">
                <i class="fas fa-folder-open"></i>
            </div>
            <div class="kpi-data">
                <div class="kpi-val">{{ $stats['total_publications'] }}</div>
                <div class="kpi-label">Publications totales</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#DBEAFE;color:#1D4ED8;">
                <i class="fas fa-eye"></i>
            </div>
            <div class="kpi-data">
                <div class="kpi-val">{{ number_format($stats['total_vues']) }}</div>
                <div class="kpi-label">Vues cumulées</div>
                <div class="kpi-trend" style="color:var(--gray-400);">
                    <i class="fas fa-chart-bar"></i> {{ $moyenneVues }} moy. par publication
                </div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#FCE7F3;color:#BE185D;">
                <i class="fas fa-heart"></i>
            </div>
            <div class="kpi-data">
                <div class="kpi-val">{{ number_format($stats['total_likes']) }}</div>
                <div class="kpi-label">Likes reçus</div>
                <div class="kpi-trend" style="color:#BE185D;">
                    <i class="fas fa-arrow-up"></i> {{ $tauxLike }}% taux d'engagement
                </div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#EDE9FE;color:#7C3AED;">
                <i class="fas fa-comment-dots"></i>
            </div>
            <div class="kpi-data">
                <div class="kpi-val">{{ $stats['total_commentaires'] }}</div>
                <div class="kpi-label">Commentaires reçus</div>
                <div class="kpi-trend" style="color:var(--purple);">
                    {{ $tauxComment }}% des vues commentées
                </div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#D1FAE5;color:#065F46;">
                <i class="fas fa-handshake"></i>
            </div>
            <div class="kpi-data">
                <div class="kpi-val">{{ $stats['total_collaborations'] }}</div>
                <div class="kpi-label">Collaborations actives</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#FEF9C3;color:#854D0E;">
                <i class="fas fa-star"></i>
            </div>
            <div class="kpi-data">
                <div class="kpi-val">
                    {{ $stats['note_moyenne'] > 0 ? number_format($stats['note_moyenne'], 1) : '—' }}
                </div>
                <div class="kpi-label">Note moyenne</div>
                @if($stats['note_moyenne'] > 0)
                    <div class="kpi-trend" style="color:#F59E0B;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star" style="font-size:0.65rem;color:{{ $i <= round($stats['note_moyenne']) ? '#F59E0B' : '#E5E7EB' }};"></i>
                        @endfor
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Répartition types -->
    <div class="section-title">
        <i class="fas fa-layer-group" style="color:var(--purple);"></i>
        Répartition par type
    </div>
    <div class="types-grid" style="margin-bottom:1.5rem;">
        @foreach($typeConfig as $type => $cfg)
            @php $count = $pubParType[$type] ?? 0; $pct = $totalPub > 0 ? round(($count/$totalPub)*100) : 0; @endphp
            <div class="type-card">
                <div class="type-icon" style="background: {{ $cfg[1] }};color: {{ $cfg[2] }};">
                    <i class="fas {{ $cfg[3] }}"></i>
                </div>
                <div class="type-val">{{ $count }}</div>
                <div class="type-label">{{ $cfg[0] }}</div>
                <div class="type-bar">
                    <div class="type-bar-fill" style="width: {{ $pct }}%;background: {{ $cfg[2] }};"></div>
                </div>
                <div style="font-size:0.7rem;color:var(--gray-400);">{{ $pct }}% du total</div>
            </div>
        @endforeach
    </div>

    <!-- Top publications -->
    @if($topPublications->isNotEmpty())
        <div class="section-title">
            <i class="fas fa-trophy" style="color:#F59E0B;"></i>
            Publications les plus consultées
        </div>
        @php $maxVues = $topPublications->max('nb_vues') ?: 1; @endphp
        <div class="top-pubs-wrap">
            @foreach($topPublications as $index => $pub)
                <div class="top-pub-row">
                    <div class="top-rank">{{ $index + 1 }}</div>
                    <div class="top-pub-info">
                        <a href="{{ route('publications.show', $pub->id) }}" class="top-pub-title">
                            {{ Str::limit($pub->titre, 20) }}
                        </a>
                        <div class="top-pub-meta">
                            @php $tc = $typeConfig[$pub->type] ?? ['', '#F3F4F6', '#6B7280', 'fa-file']; @endphp
                            <span style="background: {{ $tc[1] }};color: {{ $tc[2] }};font-size:0.68rem;font-weight:600;padding:0.1rem 0.45rem;border-radius:4px;">
                                {{ $tc[0] }}
                            </span>
                            <span class="top-pub-meta-item">
                                <i class="fas fa-heart" style="color:#E11D48;"></i> {{ $pub->nb_likes }}
                            </span>
                            <span class="top-pub-meta-item">
                                <i class="fas fa-comment"></i> {{ $pub->nb_commentaires }}
                            </span>
                        </div>
                    </div>
                    <div class="top-pub-bar-wrap">
                        <div class="top-pub-bar">
                            <div class="top-pub-bar-fill"
                                 style="width: {{ round(($pub->nb_vues/$maxVues)*100) }}%;"></div>
                        </div>
                        <div class="top-pub-vues">{{ number_format($pub->nb_vues) }} vues</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Engagement -->
    <div class="section-title">
        <i class="fas fa-fire" style="color:#EF4444;"></i>
        Taux d'engagement
    </div>
    <div class="engagement-grid">
        <div class="engagement-card">
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:#FCE7F3;color:#BE185D;display:flex;align-items:center;justify-content:center;font-size:0.85rem;">
                    <i class="fas fa-heart"></i>
                </div>
                <span style="font-size:0.82rem;color:var(--gray-500);font-weight:600;">Taux de like</span>
            </div>
            <div class="engagement-big" style="color:#BE185D;">{{ $tauxLike }}%</div>
            <div class="engagement-label">des visiteurs ont aimé vos publications</div>
            <div style="height:6px;background:var(--gray-100);border-radius:3px;margin-top:0.75rem;overflow:hidden;">
                <div style="height:100%;width: {{ min($tauxLike * 5, 100) }}%;background:#BE185D; border-radius:3px;transition:width .6s ease;"></div>
            </div>
        </div>
        <div class="engagement-card">
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:var(--purple-light);color:var(--purple);display:flex;align-items:center;justify-content:center;font-size:0.85rem;">
                    <i class="fas fa-comment"></i>
                </div>
                <span style="font-size:0.82rem;color:var(--gray-500);font-weight:600;">Taux de commentaire</span>
            </div>
            <div class="engagement-big" style="color:var(--purple);">{{ $tauxComment }}%</div>
            <div class="engagement-label">des visiteurs ont commenté vos publications</div>
            <div style="height:6px;background:var(--gray-100);border-radius:3px;margin-top:0.75rem;overflow:hidden;">
                <div style="height:100%;width: {{ min($tauxComment * 10, 100) }}%;background:var(--purple);border-radius:3px;transition:width .6s ease;"></div>
            </div>
        </div>
    </div>

</main>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation barres au chargement
    var fills = document.querySelectorAll('.top-pub-bar-fill, .type-bar-fill, .engagement-card div[style*="transition"]');
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                var el = entry.target;
                var w = el.style.width;
                el.style.width = '0';
                setTimeout(function() { el.style.width = w; }, 100);
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.2 });
    fills.forEach(function(f) { observer.observe(f); });
});
</script>
@endsection