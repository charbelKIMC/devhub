@extends('layouts.admin')
@section('title', 'Détail candidature – Admin DevHub')
@section('page_title', 'Détail de la candidature')

@section('styles')
<style>
    .cand-detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 500px) {
        .cand-detail-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<div style="max-width:780px;">

    <!-- Header -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:0.75rem;">
        <a href="{{ route('admin.candidatures.externes') }}"
           style="display:inline-flex;align-items:center;gap:0.5rem;font-size:0.82rem;color:var(--gray-500);text-decoration:none;padding:0.4rem 0.85rem;border:1px solid var(--gray-200);border-radius:8px;background:white;">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
        <span style="background: {{ $candidature->statut==='en_attente' ? '#FEF9C3' : ($candidature->statut==='validée' ? '#D1FAE5' : '#FEE2E2') }};
                     color: {{ $candidature->statut==='en_attente' ? '#854D0E' : ($candidature->statut==='validée' ? '#065F46' : '#DC2626') }};
                     padding:0.3rem 0.85rem;border-radius:20px;font-size:0.78rem;font-weight:700;">
            {{ ucfirst($candidature->statut) }}
        </span>
    </div>

    <!-- Identité -->
    <div style="background:white;border:1px solid var(--gray-200);border-radius:14px;padding:1.5rem;margin-bottom:1rem;">
        <div style="font-family:'Urbanist',sans-serif;font-size:0.82rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:1rem;">
            <i class="fas fa-user me-2" style="color:var(--purple);"></i>Informations personnelles
        </div>
        <div class="cand-detail-grid">
            <div>
                <div style="font-size:0.72rem;color:var(--gray-400);margin-bottom:0.2rem;">Nom complet</div>
                <div style="font-weight:700;color:var(--gray-900);">{{ $candidature->prenom }} {{ $candidature->nom }}</div>
            </div>
            <div>
                <div style="font-size:0.72rem;color:var(--gray-400);margin-bottom:0.2rem;">Email</div>
                <div style="font-weight:600;color:var(--gray-700);">{{ $candidature->email }}</div>
            </div>
            <div>
                <div style="font-size:0.72rem;color:var(--gray-400);margin-bottom:0.2rem;">Téléphone</div>
                <div style="color:var(--gray-700);">{{ $candidature->telephone }}</div>
            </div>
            <div>
                <div style="font-size:0.72rem;color:var(--gray-400);margin-bottom:0.2rem;">Localisation</div>
                <div style="color:var(--gray-700);">{{ $candidature->localisation }}</div>
            </div>
            <div>
                <div style="font-size:0.72rem;color:var(--gray-400);margin-bottom:0.2rem;">Type pièce d'identité</div>
                <div style="color:var(--gray-700);">{{ $candidature->type_identite }}</div>
            </div>
            <div>
                <div style="font-size:0.72rem;color:var(--gray-400);margin-bottom:0.2rem;">Numéro</div>
                <div style="color:var(--gray-700);">{{ $candidature->numero_identite }}</div>
            </div>
        </div>
    </div>

    <!-- Expertise -->
    <div style="background:white;border:1px solid var(--gray-200);border-radius:14px;padding:1.5rem;margin-bottom:1rem;">
        <div style="font-family:'Urbanist',sans-serif;font-size:0.82rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:1rem;">
            <i class="fas fa-code me-2" style="color:var(--purple);"></i>Expertise professionnelle
        </div>
        <div class="cand-detail-grid" style="margin-bottom:1rem;">
            <div>
                <div style="font-size:0.72rem;color:var(--gray-400);margin-bottom:0.2rem;">Spécialité</div>
                <div style="font-weight:600;color:var(--gray-700);">{{ $candidature->specialite }}</div>
            </div>
            <div>
                <div style="font-size:0.72rem;color:var(--gray-400);margin-bottom:0.2rem;">Années d'expérience</div>
                <div style="font-weight:600;color:var(--gray-700);">{{ $candidature->annees_experience }} ans</div>
            </div>
        </div>
        <div>
            <div style="font-size:0.72rem;color:var(--gray-400);margin-bottom:0.4rem;">Expérience détaillée</div>
            <div style="font-size:0.875rem;color:var(--gray-600);line-height:1.7;background:var(--gray-50);padding:0.85rem;border-radius:8px;">
                {{ $candidature->experience }}
            </div>
        </div>
    </div>

    <!-- Motivation -->
    <div style="background:white;border:1px solid var(--gray-200);border-radius:14px;padding:1.5rem;margin-bottom:1rem;">
        <div style="font-family:'Urbanist',sans-serif;font-size:0.82rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:0.75rem;">
            <i class="fas fa-heart me-2" style="color:#E11D48;"></i>Lettre de motivation
        </div>
        <div style="font-size:0.875rem;color:var(--gray-600);line-height:1.8;background:var(--gray-50);padding:1rem;border-radius:8px;border-left:3px solid var(--purple);">
            {{ $candidature->motivation }}
        </div>
    </div>

    <!-- Liens + Documents -->
    <div style="background:white;border:1px solid var(--gray-200);border-radius:14px;padding:1.5rem;margin-bottom:1.5rem;">
        <div style="font-family:'Urbanist',sans-serif;font-size:0.82rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:1rem;">
            <i class="fas fa-link me-2" style="color:var(--purple);"></i>Liens et documents
        </div>
        <div style="display:flex;flex-direction:column;gap:0.5rem;">
            @if($candidature->portfolio)
                <a href="{{ $candidature->portfolio }}" target="_blank"
                   style="display:flex;align-items:center;gap:0.5rem;padding:0.55rem 0.85rem;border:1px solid var(--gray-200);border-radius:9px;text-decoration:none;color:var(--gray-700);font-size:0.82rem;font-weight:500;transition:all 0.15s;"
                   onmouseover="this.style.borderColor='var(--purple)';this.style.color='var(--purple)'"
                   onmouseout="this.style.borderColor='var(--gray-200)';this.style.color='var(--gray-700)'">
                    <i class="fas fa-globe" style="width:14px;color:var(--purple);"></i>
                    Portfolio — {{ $candidature->portfolio }}
                </a>
            @endif
            @if($candidature->linkedin)
                <a href="{{ $candidature->linkedin }}" target="_blank"
                   style="display:flex;align-items:center;gap:0.5rem;padding:0.55rem 0.85rem;border:1px solid var(--gray-200);border-radius:9px;text-decoration:none;color:var(--gray-700);font-size:0.82rem;font-weight:500;transition:all 0.15s;"
                   onmouseover="this.style.borderColor='#0A66C2';this.style.color='#0A66C2'"
                   onmouseout="this.style.borderColor='var(--gray-200)';this.style.color='var(--gray-700)'">
                    <i class="fab fa-linkedin" style="width:14px;color:#0A66C2;"></i>
                    LinkedIn
                </a>
            @endif
            @if($candidature->chemin_cv)
                <a href="{{ asset('storage/'.$candidature->chemin_cv) }}" target="_blank"
                   style="display:flex;align-items:center;gap:0.5rem;padding:0.55rem 0.85rem;border:1px solid #FECACA;border-radius:9px;text-decoration:none;color:#DC2626;font-size:0.82rem;font-weight:600;">
                    <i class="fas fa-file-pdf" style="width:14px;"></i>
                    Télécharger le CV
                </a>
            @endif
            @if($candidature->chemin_identite)
                <a href="{{ asset('storage/'.$candidature->chemin_identite) }}" target="_blank"
                   style="display:flex;align-items:center;gap:0.5rem;padding:0.55rem 0.85rem;border:1px solid var(--gray-200);border-radius:9px;text-decoration:none;color:var(--gray-700);font-size:0.82rem;font-weight:600;">
                    <i class="fas fa-id-card" style="width:14px;color:var(--purple);"></i>
                    Voir la pièce d'identité
                </a>
            @endif
        </div>
    </div>

    <!-- Actions -->
    @if($candidature->statut === 'en_attente')
        <div style="display:flex;gap:0.75rem;">
            <form action="{{ route('admin.candidatures.externes.valider', $candidature->id) }}" method="POST" style="flex:1;"
                  data-confirm="La candidature de {{ $candidature->prenom }} {{ $candidature->nom }} sera validée."
                  data-title="Valider cette candidature ?"
                  data-type="success">
                @csrf @method('PUT')
                <button type="submit"
                        style="width:100%;padding:0.85rem;background:#D1FAE5;color:#065F46;border:1.5px solid #6EE7B7;border-radius:12px;font-size:0.95rem;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .2s;"
                        onmouseover="this.style.background='#065F46';this.style.color='white'"
                        onmouseout="this.style.background='#D1FAE5';this.style.color='#065F46'">
                    <i class="fas fa-check me-2"></i>Valider la candidature
                </button>
            </form>
            <form action="{{ route('admin.candidatures.externes.refuser', $candidature->id) }}" method="POST" style="flex:1;"
                  data-confirm="La candidature de {{ $candidature->prenom }} {{ $candidature->nom }} sera refusée."
                  data-title="Refuser cette candidature ?"
                  data-type="danger">
                @csrf @method('PUT')
                <button type="submit"
                        style="width:100%;padding:0.85rem;background:#FEE2E2;color:#DC2626;border:1.5px solid #FCA5A5;border-radius:12px;font-size:0.95rem;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .2s;"
                        onmouseover="this.style.background='#DC2626';this.style.color='white'"
                        onmouseout="this.style.background='#FEE2E2';this.style.color='#DC2626'">
                    <i class="fas fa-times me-2"></i>Refuser la candidature
                </button>
            </form>
        </div>
    @endif

</div>
@endsection