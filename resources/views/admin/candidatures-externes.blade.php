@extends('layouts.admin')
@section('title', 'Candidatures externes – Admin DevHub')
@section('page_title', 'Candidatures mentor externes')

@section('content')
<div style="background:white;border:1px solid var(--gray-200);border-radius:14px;overflow:hidden;">
    @forelse($candidatures as $c)
        <div style="padding:1.25rem;border-bottom:1px solid var(--gray-100);display:grid;grid-template-columns:1fr auto;gap:1rem;align-items:center;">
            <div>
                <div style="font-family:'Urbanist',sans-serif;font-weight:700;color:var(--gray-900);">
                    {{ $c->prenom }} {{ $c->nom }}
                </div>
                <div style="font-size:0.78rem;color:var(--gray-400);margin-top:0.2rem;">
                    {{ $c->email }} · {{ $c->specialite }} · {{ $c->annees_experience }} ans d'exp.
                </div>
                <div style="font-size:0.75rem;color:var(--gray-500);margin-top:0.35rem;">
                    {{ Str::limit($c->motivation, 120) }}
                </div>
                <div style="display:flex;gap:0.5rem;margin-top:0.5rem;flex-wrap:wrap;">
                    @if($c->chemin_cv)
                        <a href="{{ asset('storage/'.$c->chemin_cv) }}" target="_blank"
                           style="font-size:0.72rem;color:var(--purple);background:var(--purple-light);padding:0.2rem 0.6rem;border-radius:5px;text-decoration:none;font-weight:600;">
                            <i class="fas fa-file-pdf me-1"></i>CV
                        </a>
                    @endif
                    @if($c->chemin_identite)
                        <a href="{{ asset('storage/'.$c->chemin_identite) }}" target="_blank"
                           style="font-size:0.72rem;color:var(--purple);background:var(--purple-light);padding:0.2rem 0.6rem;border-radius:5px;text-decoration:none;font-weight:600;">
                            <i class="fas fa-id-card me-1"></i>Pièce d'identité
                        </a>
                    @endif
                    <span style="font-size:0.72rem;color:var(--gray-400);padding:0.2rem 0.6rem;">
                        Déposée le {{ $c->created_at?->format('d/m/Y') ?? '—' }}
                    </span>
                </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:0.4rem;flex-shrink:0;">
                @if($c->statut === 'en_attente')
                
                    <form action="{{ route('admin.candidatures.externes.valider', $c->id) }}" method="POST"
                        data-confirm="La candidature de {{ $c->prenom }} {{ $c->nom }} sera validée."
                        data-title="Valider cette candidature ?"
                        data-type="success">
                        @csrf @method('PUT')
                        <button type="submit" style="padding:0.4rem 0.85rem;background:#D1FAE5;color:#065F46;border:1.5px solid #6EE7B7;border-radius:8px;font-size:0.78rem;font-weight:700;cursor:pointer;width:100%;">
                            <i class="fas fa-check me-1"></i>Valider
                        </button>
                    </form>
                    <form action="{{ route('admin.candidatures.externes.refuser', $c->id) }}" method="POST"
                          data-confirm="La candidature de {{ $c->prenom }} {{ $c->nom }} sera refusée."
                          data-title="Refuser cette candidature ?"
                          data-type="danger">
                        @csrf @method('PUT')
                        <button type="submit" style="padding:0.4rem 0.85rem;background:#FEE2E2;color:#DC2626;border:1.5px solid #FCA5A5;border-radius:8px;font-size:0.78rem;font-weight:700;cursor:pointer;width:100%;">
                            <i class="fas fa-times me-1"></i>Refuser
                        </button>
                    </form>
                    <a href="{{ route('admin.candidatures.externes.detail', $c->id) }}"style="padding:0.4rem 0.85rem;background:var(--purple-light);color:var(--purple);border:1px solid var(--purple-border);border-radius:8px;font-size:0.78rem;font-weight:600;text-decoration:none;">
                        <i class="fas fa-eye me-1"></i>Détails
                    </a>
                @else
                    <span style="padding:0.3rem 0.75rem;border-radius:8px;font-size:0.78rem;font-weight:700;
                        background: {{ $c->statut==='acceptée' ? '#D1FAE5' : '#FEE2E2' }};
                        color: {{ $c->statut==='acceptée' ? '#065F46' : '#DC2626' }};">
                        {{ ucfirst($c->statut) }}
                    </span>
                @endif
            </div>
        </div>
    @empty
        <div style="text-align:center;padding:3rem;color:var(--gray-400);">
            <i class="fas fa-file-alt" style="font-size:2rem;color:var(--gray-200);display:block;margin-bottom:0.5rem;"></i>
            Aucune candidature externe
        </div>
    @endforelse
</div>

@if($candidatures->hasPages())
    <div style="margin-top:1rem;">{{ $candidatures->links() }}</div>
@endif
@endsection