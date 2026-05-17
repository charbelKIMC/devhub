@extends('layouts.admin')
@section('title', 'Candidatures externes – Admin DevHub')
@section('page_title', 'Candidatures mentor externes')

@section('content')
<style>
.cand-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
.cand-stat-card { background:#fff; border:1px solid var(--gray-200); border-radius:12px; padding:1.1rem 1.25rem; }
.cand-stat-number { font-size:1.8rem; font-weight:800; font-family:'Urbanist',sans-serif; line-height:1; }
.cand-stat-label { font-size:0.75rem; color:var(--gray-400); margin-top:0.25rem; font-weight:500; text-transform:uppercase; letter-spacing:.04em; }

.cand-tabs { display:flex; gap:0.4rem; margin-bottom:1.25rem; background:#fff; border:1px solid var(--gray-200); border-radius:10px; padding:0.35rem; width:fit-content; }
.cand-tab { padding:0.4rem 1rem; border-radius:7px; font-size:0.82rem; font-weight:600; text-decoration:none; color:var(--gray-500); transition:all .15s; }
.cand-tab:hover { background:var(--gray-100); color:var(--gray-700); }
.cand-tab.active { background:var(--purple); color:#fff; }

.cand-grid { display:flex; flex-direction:column; gap:0.75rem; }
.cand-card { background:#fff; border:1px solid var(--gray-200); border-radius:12px; padding:1.25rem 1.5rem; display:grid; grid-template-columns:1fr auto; gap:1.5rem; align-items:start; transition:box-shadow .15s; }
.cand-card:hover { box-shadow:0 2px 12px rgba(0,0,0,.07); }
.cand-name { font-size:1rem; font-weight:700; font-family:'Urbanist',sans-serif; color:var(--gray-900); }
.cand-meta { font-size:0.78rem; color:var(--gray-400); margin-top:0.25rem; }
.cand-meta span { margin-right:0.6rem; }
.cand-excerpt { font-size:0.82rem; color:var(--gray-500); margin-top:0.6rem; line-height:1.5; }
.cand-docs { display:flex; gap:0.5rem; margin-top:0.75rem; flex-wrap:wrap; align-items:center; }
.cand-doc-link { display:inline-flex; align-items:center; gap:0.35rem; font-size:0.75rem; font-weight:600; padding:0.25rem 0.65rem; border-radius:6px; text-decoration:none; }
.cand-doc-link.cv { background:#EDE9FE; color:#7C3AED; }
.cand-doc-link.id { background:#FEF3C7; color:#92400E; }
.cand-date { font-size:0.73rem; color:var(--gray-400); }
.cand-actions { display:flex; flex-direction:column; gap:0.4rem; min-width:120px; }
.btn-valider { padding:0.45rem 0.9rem; background:#D1FAE5; color:#065F46; border:1.5px solid #6EE7B7; border-radius:8px; font-size:0.8rem; font-weight:700; cursor:pointer; width:100%; text-align:center; }
.btn-valider:hover { background:#A7F3D0; }
.btn-refuser { padding:0.45rem 0.9rem; background:#FEE2E2; color:#DC2626; border:1.5px solid #FCA5A5; border-radius:8px; font-size:0.8rem; font-weight:700; cursor:pointer; width:100%; text-align:center; }
.btn-refuser:hover { background:#FECACA; }
.btn-detail { padding:0.45rem 0.9rem; background:var(--purple-light); color:var(--purple); border:1px solid var(--purple-border); border-radius:8px; font-size:0.8rem; font-weight:600; text-decoration:none; width:100%; text-align:center; display:block; }
.btn-detail:hover { background:#EDE9FE; }
.badge-statut { display:inline-block; padding:0.3rem 0.8rem; border-radius:20px; font-size:0.78rem; font-weight:700; }
.badge-acceptee { background:#D1FAE5; color:#065F46; }
.badge-refusee { background:#FEE2E2; color:#DC2626; }
.cand-empty { text-align:center; padding:3.5rem; color:var(--gray-400); background:#fff; border:1px solid var(--gray-200); border-radius:12px; }
@media(max-width:700px){
    .cand-stats{grid-template-columns:repeat(2,1fr);}
    .cand-card{grid-template-columns:1fr;gap:1rem;}
    .cand-actions{flex-direction:row;flex-wrap:wrap;}
}
</style>

{{-- Stats --}}
<div class="cand-stats">
    <div class="cand-stat-card">
        <div class="cand-stat-number" style="color:var(--gray-800);">{{ $stats->total }}</div>
        <div class="cand-stat-label">Total reçues</div>
    </div>
    <div class="cand-stat-card">
        <div class="cand-stat-number" style="color:#D97706;">{{ $stats->en_attente }}</div>
        <div class="cand-stat-label">En attente</div>
    </div>
    <div class="cand-stat-card">
        <div class="cand-stat-number" style="color:#065F46;">{{ $stats->acceptees }}</div>
        <div class="cand-stat-label">Acceptées</div>
    </div>
    <div class="cand-stat-card">
        <div class="cand-stat-number" style="color:#DC2626;">{{ $stats->refusees }}</div>
        <div class="cand-stat-label">Refusées</div>
    </div>
</div>

{{-- Filtres --}}
<div class="cand-tabs">
    <a href="{{ route('admin.candidatures.externes', ['statut' => 'en_attente']) }}"
       class="cand-tab {{ $statut === 'en_attente' ? 'active' : '' }}">
        En attente <span style="opacity:.7;">({{ $stats->en_attente }})</span>
    </a>
    <a href="{{ route('admin.candidatures.externes', ['statut' => 'acceptée']) }}"
       class="cand-tab {{ $statut === 'acceptée' ? 'active' : '' }}">
        Acceptées
    </a>
    <a href="{{ route('admin.candidatures.externes', ['statut' => 'refusée']) }}"
       class="cand-tab {{ $statut === 'refusée' ? 'active' : '' }}">
        Refusées
    </a>
    <a href="{{ route('admin.candidatures.externes', ['statut' => 'toutes']) }}"
       class="cand-tab {{ $statut === 'toutes' ? 'active' : '' }}">
        Toutes
    </a>
</div>

{{-- Liste --}}
<div class="cand-grid">
    @forelse($candidatures as $c)
        <div class="cand-card">
            <div>
                <div class="cand-name">{{ $c->prenom }} {{ $c->nom }}</div>
                <div class="cand-meta">
                    <span><i class="fas fa-envelope" style="opacity:.5;"></i> {{ $c->email }}</span>
                    <span><i class="fas fa-code" style="opacity:.5;"></i> {{ $c->specialite }}</span>
                    <span><i class="fas fa-briefcase" style="opacity:.5;"></i> {{ $c->annees_experience }}</span>
                    @if($c->localisation)
                        <span><i class="fas fa-map-marker-alt" style="opacity:.5;"></i> {{ $c->localisation }}</span>
                    @endif
                </div>
                <div class="cand-excerpt">{{ Str::limit($c->motivation, 150) }}</div>
                <div class="cand-docs">
                    @if($c->chemin_cv)
                        <a href="{{ asset('storage/'.$c->chemin_cv) }}" target="_blank" class="cand-doc-link cv">
                            <i class="fas fa-file-pdf"></i> CV
                        </a>
                    @endif
                    @if($c->chemin_identite)
                        <a href="{{ asset('storage/'.$c->chemin_identite) }}" target="_blank" class="cand-doc-link id">
                            <i class="fas fa-id-card"></i> Pièce d'identité
                        </a>
                    @endif
                    <span class="cand-date">
                        <i class="fas fa-clock" style="opacity:.4;"></i>
                        {{ $c->created_at?->format('d/m/Y à H:i') ?? '—' }}
                    </span>
                </div>
            </div>
            <div class="cand-actions">
                @if($c->statut === 'en_attente')
                    <a href="{{ route('admin.candidatures.externes.detail', $c->id) }}" class="btn-detail">
                        <i class="fas fa-eye"></i> Voir le dossier
                    </a>
                    <form action="{{ route('admin.candidatures.externes.valider', $c->id) }}" method="POST"
                          data-confirm="Valider la candidature de {{ $c->prenom }} {{ $c->nom }} ?"
                          data-title="Valider cette candidature ?" data-type="success">
                        @csrf @method('PUT')
                        <button type="submit" class="btn-valider"><i class="fas fa-check"></i> Valider</button>
                    </form>
                    <form action="{{ route('admin.candidatures.externes.refuser', $c->id) }}" method="POST"
                          data-confirm="Refuser la candidature de {{ $c->prenom }} {{ $c->nom }} ?"
                          data-title="Refuser cette candidature ?" data-type="danger">
                        @csrf @method('PUT')
                        <button type="submit" class="btn-refuser"><i class="fas fa-times"></i> Refuser</button>
                    </form>
                @else
                    <a href="{{ route('admin.candidatures.externes.detail', $c->id) }}" class="btn-detail">
                        <i class="fas fa-eye"></i> Voir le dossier
                    </a>
                    <span class="badge-statut {{ $c->statut === 'acceptée' ? 'badge-acceptee' : 'badge-refusee' }}">
                        {{ ucfirst($c->statut) }}
                    </span>
                @endif
            </div>
        </div>
    @empty
        <div class="cand-empty">
            <i class="fas fa-folder-open" style="font-size:2.5rem;color:var(--gray-200);display:block;margin-bottom:0.75rem;"></i>
            <div style="font-weight:600;color:var(--gray-500);">Aucune candidature
                @if($statut !== 'toutes') {{ $statut === 'en_attente' ? 'en attente' : $statut }} @endif
            </div>
        </div>
    @endforelse
</div>

@if($candidatures->hasPages())
    <div style="margin-top:1.25rem;">{{ $candidatures->links() }}</div>
@endif
@endsection
