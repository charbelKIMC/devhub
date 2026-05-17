@extends('layouts.mentor')
@section('title', 'Mon espace mentor – DevHub')
@section('page_title', 'Tableau de bord')

@section('styles')
<style>
:root { --purple:#7C3AED; --purple-dark:#6D28D9; --purple-light:#EDE9FE; --purple-xlight:#F5F3FF; --purple-border:#DDD6FE; --gray-50:#F9FAFB; --gray-100:#F3F4F6; --gray-200:#E5E7EB; --gray-400:#9CA3AF; --gray-500:#6B7280; --gray-700:#374151; --gray-900:#111827; --online:#22C55E; }

.kpi-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:0.85rem; margin-bottom:1.5rem; }
.kpi-card { background:white; border:1px solid var(--gray-200); border-radius:14px; padding:1.1rem 1.25rem; display:flex; align-items:center; gap:1rem; transition:all 0.18s; }
.kpi-card:hover { box-shadow:0 4px 16px rgba(0,0,0,0.06); transform:translateY(-2px); }
.kpi-icon { width:44px; height:44px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
.kpi-val { font-family:'Urbanist',sans-serif; font-size:1.5rem; font-weight:800; color:var(--gray-900); line-height:1; }
.kpi-label { font-size:0.75rem; color:var(--gray-400); margin-top:0.2rem; }

.section-title { font-family:'Urbanist',sans-serif; font-size:0.82rem; font-weight:700; color:var(--gray-500); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.85rem; display:flex; align-items:center; gap:0.5rem; }

.mentore-card {
    background:white; border:1px solid var(--gray-200); border-radius:14px;
    padding:1.1rem 1.25rem; margin-bottom:0.75rem;
    display:grid; grid-template-columns:auto 1fr auto;
    gap:1rem; align-items:center; transition:all 0.18s;
}
.mentore-card:hover { border-color:var(--purple); box-shadow:0 4px 16px rgba(124,58,237,0.08); }

.mentore-ava {
    width:50px; height:50px; border-radius:50%;
    background:var(--purple-light); color:var(--purple);
    display:flex; align-items:center; justify-content:center;
    font-size:1.1rem; font-weight:700; flex-shrink:0; overflow:hidden;
    position:relative;
}
.mentore-ava img { width:100%; height:100%; object-fit:cover; }
.mentore-ava .online-dot { position:absolute; bottom:1px; right:1px; width:11px; height:11px; border-radius:50%; background:var(--online); border:2px solid white; }

.mentore-name { font-family:'Urbanist',sans-serif; font-size:0.9rem; font-weight:700; color:var(--gray-900); }
.mentore-spec { font-size:0.75rem; color:var(--purple); font-weight:600; margin-top:0.1rem; }
.mentore-since { font-size:0.72rem; color:var(--gray-400); margin-top:0.25rem; display:flex; align-items:center; gap:0.35rem; flex-wrap:wrap; }

.mentore-progress { margin-top:0.5rem; }
.progress-bar-wrap { height:5px; background:var(--gray-100); border-radius:3px; overflow:hidden; margin-top:0.25rem; }
.progress-bar-fill { height:100%; background:var(--purple); border-radius:3px; transition:width 0.6s ease; }
.progress-label { font-size:0.68rem; color:var(--gray-400); display:flex; justify-content:space-between; }

.mentore-actions { display:flex; flex-direction:column; gap:0.4rem; flex-shrink:0; }
.btn-contact { display:inline-flex; align-items:center; gap:0.35rem; padding:0.45rem 0.9rem; background:var(--purple); color:white; border:none; border-radius:8px; font-size:0.78rem; font-weight:600; cursor:pointer; text-decoration:none; transition:background 0.2s; font-family:'DM Sans',sans-serif; white-space:nowrap; }
.btn-contact:hover { background:var(--purple-dark); color:white; }
.btn-profil-link { display:inline-flex; align-items:center; gap:0.35rem; padding:0.4rem 0.85rem; background:var(--purple-light); color:var(--purple); border:1.5px solid var(--purple-border); border-radius:8px; font-size:0.78rem; font-weight:600; text-decoration:none; transition:all 0.18s; white-space:nowrap; }
.btn-profil-link:hover { background:var(--purple); color:white; }

.deadline-badge { display:inline-flex; align-items:center; gap:0.25rem; padding:0.15rem 0.5rem; border-radius:5px; font-size:0.68rem; font-weight:700; }
.deadline-ok     { background:#D1FAE5; color:#065F46; }
.deadline-proche { background:#FEF9C3; color:#854D0E; }
.deadline-expire { background:#FEE2E2; color:#DC2626; }

.empty-state { text-align:center; padding:3.5rem 2rem; background:white; border:1px solid var(--gray-200); border-radius:14px; }
.empty-state i { font-size:2.5rem; color:var(--gray-200); display:block; margin-bottom:0.75rem; }
.empty-state h3 { font-family:'Urbanist',sans-serif; font-size:1rem; font-weight:700; color:var(--gray-600); margin-bottom:0.4rem; }

@media(max-width:992px) { .kpi-grid{grid-template-columns:1fr 1fr;} }
@media(max-width:768px) { .mentore-card{grid-template-columns:auto 1fr;} .mentore-actions{flex-direction:row; grid-column:1/-1;} }
</style>
@endsection

@section('content')

@php
    $user     = Auth::user();
    $mentores = \App\Models\RelationMentorat::with('mentore')
        ->where('mentor_id', $user->id)
        ->where('statut', 'active')
        ->latest('date_debut')
        ->get();

    $totalMessages = 0;
    foreach($mentores as $r) {
        $conv = \App\Models\Conversation::where('type_conversation','privee')
            ->whereHas('participants', fn($q) => $q->where('utilisateurs.id', $r->mentore_id))
            ->whereHas('participants', fn($q) => $q->where('utilisateurs.id', $user->id))
            ->first();
        if ($conv) $totalMessages += $conv->messages()->count();
    }
@endphp

<!-- KPI -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon" style="background:var(--purple-light);color:var(--purple);">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <div class="kpi-val">{{ $mentores->count() }}</div>
            <div class="kpi-label">Mentorés actifs</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:#D1FAE5;color:#065F46;">
            <i class="fas fa-comment-dots"></i>
        </div>
        <div>
            <div class="kpi-val">{{ $totalMessages }}</div>
            <div class="kpi-label">Messages échangés</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:#FEF9C3;color:#854D0E;">
            <i class="fas fa-star"></i>
        </div>
        <div>
            @php
                $pubIds = $user->publications()->pluck('id');
                $note   = \App\Models\Avis::whereIn('publication_id', $pubIds)->avg('note');
            @endphp
            <div class="kpi-val">{{ $note ? number_format($note,1) : '—' }}</div>
            <div class="kpi-label">Note moyenne</div>
        </div>
    </div>
</div>

<!-- Liste mentorés -->
<div class="section-title">
    <i class="fas fa-user-graduate" style="color:var(--purple);"></i>
    Mes mentorés
</div>

@forelse($mentores as $relation)
    @php
        $mentore    = $relation->mentore;
        $estEnLigne = $mentore->derniere_connexion
            && $mentore->derniere_connexion->diffInMinutes(now()) < 5;
        $nbPubs     = $mentore->publications()->where('statut','publié')->count();

        // Jours restants
        $dateFin     = $relation->date_fin ? \Carbon\Carbon::parse($relation->date_fin) : null;
        $joursRestants = $dateFin ? now()->diffInDays($dateFin, false) : null;

        // Conversation privée
        $conv = \App\Models\Conversation::where('type_conversation','privee')
            ->whereHas('participants', fn($q) => $q->where('utilisateurs.id', $mentore->id))
            ->whereHas('participants', fn($q) => $q->where('utilisateurs.id', $user->id))
            ->first();

        // Progression (basée sur le nombre de publications)
        $progressPct = min(100, $nbPubs * 5);
    @endphp

    <div class="mentore-card">
        <!-- Avatar -->
        <div class="mentore-ava">
            @if($mentore->photo)
                <img src="{{ $mentore->photo_url }}" alt="">
            @else
                {{ strtoupper(substr($mentore->prenom, 0, 1)) }}
            @endif
            @if($estEnLigne)
                <span class="online-dot"></span>
            @endif
        </div>

        <!-- Infos -->
        <div>
            <div class="mentore-name">{{ $mentore->prenom }} {{ $mentore->nom }}</div>
            <div class="mentore-spec">{{ $mentore->specialite ?? 'Développeur' }}</div>
            <div class="mentore-since">
                <i class="fas fa-calendar-alt"></i>
                Depuis {{ $relation->date_debut ? \Carbon\Carbon::parse($relation->date_debut)->diffForHumans() : '—' }}

                @if($dateFin)
                    @if($joursRestants < 0)
                        <span class="deadline-badge deadline-expire">
                            <i class="fas fa-exclamation-circle"></i> Expiré
                        </span>
                    @elseif($joursRestants <= 14)
                        <span class="deadline-badge deadline-proche">
                            <i class="fas fa-clock"></i> {{ $joursRestants }}j restants
                        </span>
                    @else
                        <span class="deadline-badge deadline-ok">
                            <i class="fas fa-check"></i> Jusqu'au {{ $dateFin->format('d/m/Y') }}
                        </span>
                    @endif
                @endif

                @if($estEnLigne)
                    <span style="color:var(--online);font-weight:600;">
                        <i class="fas fa-circle" style="font-size:0.5rem;"></i> En ligne
                    </span>
                @elseif($mentore->derniere_connexion)
                    <span>Vu {{ $mentore->derniere_connexion->diffForHumans() }}</span>
                @endif
            </div>

            <!-- Barre progression -->
            <div class="mentore-progress">
                <div class="progress-label">
                    <span>Activité</span>
                    <span>{{ $nbPubs }} publication{{ $nbPubs > 1 ? 's' : '' }}</span>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar-fill" style="width:{{ $progressPct }}%;"></div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mentore-actions">
            @if($conv)
                <a href="{{ route('messagerie.show', $conv->id) }}" class="btn-contact">
                    <i class="fas fa-comment"></i> Contacter
                </a>
            @else
                <form action="{{ route('messagerie.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="destinataire_id" value="{{ $mentore->id }}">
                    <button type="submit" class="btn-contact">
                        <i class="fas fa-comment"></i> Contacter
                    </button>
                </form>
            @endif
            <a href="{{ route('profil.show', $mentore->id) }}" class="btn-profil-link">
                <i class="fas fa-user"></i> Profil
            </a>
        </div>
    </div>
@empty
    <div class="empty-state">
        <i class="fas fa-user-graduate"></i>
        <h3>Aucun mentoré pour l'instant</h3>
        <p style="font-size:0.85rem;color:var(--gray-400);">
            L'administrateur vous attribuera des mentorés prochainement.
        </p>
    </div>
@endforelse

@endsection