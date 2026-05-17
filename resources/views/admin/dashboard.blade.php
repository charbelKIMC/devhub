@extends('layouts.admin')
@section('title', 'Tableau de bord – Admin DevHub')
@section('page_title', 'Tableau de bord')

@section('content')
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#EDE9FE;color:#7C3AED;"><i class="fas fa-users"></i></div>
            <div>
                <div class="kpi-val">{{ $stats['total_utilisateurs'] }}</div>
                <div class="kpi-label">Développeurs</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#D1FAE5;color:#065F46;"><i class="fas fa-chalkboard-teacher"></i></div>
            <div>
                <div class="kpi-val">{{ $stats['total_mentors'] }}</div>
                <div class="kpi-label">Mentors actifs</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#DBEAFE;color:#1D4ED8;"><i class="fas fa-folder-open"></i></div>
            <div>
                <div class="kpi-val">{{ $stats['total_publications'] }}</div>
                <div class="kpi-label">Publications</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#FEF9C3;color:#854D0E;"><i class="fas fa-clock"></i></div>
            <div>
                <div class="kpi-val">{{ $stats['demandes_mentor'] }}</div>
                <div class="kpi-label">Demandes mentor</div>
            </div>
        </div>
        <!-- Remplace la grille 4 colonnes par 5 colonnes ou ajoute une 2ème ligne -->
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#DBEAFE;color:#1D4ED8;">
                <i class="fas fa-link"></i>
            </div>
            <div>
                <div class="kpi-val">{{ $stats['total_attributions'] }}</div>
                <div class="kpi-label">Attributions mentor actives</div>
            </div>
        </div>
    </div>

    <div class="bottom-grid">

        <div class="recent-section">
            <div class="recent-header">
                <span class="recent-title"><i class="fas fa-user-plus me-2" style="color:var(--purple);"></i>Derniers inscrits</span>
                <a href="{{ route('admin.utilisateurs') }}" class="recent-link">Voir tous</a>
            </div>
            @php
                $derniers = \App\Models\Utilisateur::where('role','développeur')->latest('date_inscription')->take(5)->get();
            @endphp
            @forelse($derniers as $u)
                <div class="recent-item">
                    <div class="recent-ava">
                        @if($u->photo)<img src="{{ $u->photo_url }}" alt="">
                        @else {{ strtoupper(substr($u->prenom,0,1)) }} @endif
                    </div>
                    <div>
                        <div class="recent-name">{{ $u->prenom }} {{ $u->nom }}</div>
                        <div class="recent-sub">{{ $u->specialite ?? 'Développeur' }}</div>
                    </div>
                    <span class="recent-time">{{ $u->date_inscription->diffForHumans() }}</span>
                </div>
            @empty
                <div style="padding:2rem;text-align:center;color:var(--gray-400);font-size:0.82rem;">Aucun utilisateur</div>
            @endforelse
        </div>

        <!-- Mentors actifs -->
        <div class="recent-section">
            <div class="recent-header">
                <span class="recent-title"><i class="fas fa-chalkboard-teacher me-2" style="color:#065F46;"></i>Mentors enregistrés</span>
                <a href="{{ route('admin.candidatures.externes') }}" class="recent-link">Voir tous</a>
            </div>
            @php
                $mentors = \App\Models\Utilisateur::where('role','mentor')->where('statut','actif')->take(5)->get();
            @endphp
            @forelse($mentors as $m)
                <div class="recent-item">
                    <div class="recent-ava" style="background:#D1FAE5;color:#065F46;">
                        @if($m->photo)<img src="{{ $m->photo_url }}" alt="">
                        @else {{ strtoupper(substr($m->prenom,0,1)) }} @endif
                    </div>
                    <div>
                        <div class="recent-name">{{ $m->prenom }} {{ $m->nom }}</div>
                        <div class="recent-sub">{{ $m->specialite ?? 'Mentor' }}</div>
                    </div>
                    @php
                        $nbMentores = \App\Models\RelationMentorat::where('mentor_id',$m->id)->where('statut','active')->count();
                    @endphp
                    <span style="font-size:0.7rem;background:#D1FAE5;color:#065F46;padding:0.15rem 0.5rem;border-radius:6px;margin-left:auto;font-weight:700;">
                        {{ $nbMentores }} mentoré{{ $nbMentores > 1 ? 's' : '' }}
                    </span>
                </div>
            @empty
                <div style="padding:2rem;text-align:center;color:var(--gray-400);font-size:0.82rem;">Aucun mentor actif</div>
            @endforelse
        </div>

        <!-- Dernières attributions -->
        <div class="recent-section" style="grid-column:1/-1;">
            <div class="recent-header">
                <span class="recent-title">
                    <i class="fas fa-link me-2" style="color:var(--purple);"></i>Dernières attributions
                </span>
                <a href="{{ route('admin.candidatures.externes') }}" class="recent-link">Gérer</a>
            </div>

            @forelse($dernieresAttributions as $rel)
                <div class="recent-item">
                    <!-- Mentoré -->
                    <div class="recent-ava" style="background:var(--purple-light);color:var(--purple);">
                        @if($rel->mentore?->photo)
                            <img src="{{ $rel->mentore->photo_url }}" alt="">
                        @else
                            {{ strtoupper(substr($rel->mentore?->prenom ?? '?', 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <div class="recent-name">{{ $rel->mentore?->prenom }} {{ $rel->mentore?->nom }}</div>
                        <div class="recent-sub" style="color:var(--gray-400);">Développeur</div>
                    </div>

                    <!-- Flèche -->
                    <div style="margin:0 0.5rem;color:var(--purple);font-size:0.85rem;">
                        <i class="fas fa-arrow-right"></i>
                    </div>

                    <!-- Mentor -->
                    <div class="recent-ava" style="background:#D1FAE5;color:#065F46;">
                        @if($rel->mentor?->photo)
                            <img src="{{ $rel->mentor->photo_url }}" alt="">
                        @else
                            {{ strtoupper(substr($rel->mentor?->prenom ?? '?', 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <div class="recent-name">{{ $rel->mentor?->prenom }} {{ $rel->mentor?->nom }}</div>
                        <div class="recent-sub">
                            <span style="background:#D1FAE5;color:#065F46;padding:0.1rem 0.4rem;border-radius:4px;font-size:0.65rem;font-weight:700;">Mentor</span>
                        </div>
                    </div>

                    <span class="recent-time">
                        {{ $rel->date_debut ? $rel->date_debut->diffForHumans() : '—' }}
                    </span>
                </div>
            @empty
                <div style="padding:2rem;text-align:center;color:var(--gray-400);font-size:0.82rem;">
                    <i class="fas fa-link" style="font-size:1.5rem;color:var(--gray-200);display:block;margin-bottom:0.5rem;"></i>
                    Aucune attribution pour le moment
                </div>
            @endforelse
        </div>

        {{-- Projets collaboratifs sans mentor --}}
        @if($projetsSansMentor->isNotEmpty())
        <div class="recent-section" style="grid-column:1/-1;margin-top:0.5rem;">
            <div class="recent-header">
                <span class="recent-title">
                    <i class="fas fa-exclamation-triangle me-2" style="color:#D97706;"></i>
                    Projets collaboratifs sans mentor
                    <span style="background:#FEF3C7;color:#92400E;font-size:0.7rem;font-weight:700;padding:0.15rem 0.55rem;border-radius:10px;margin-left:0.4rem;">{{ $projetsSansMentor->count() }}</span>
                </span>
            </div>

            @if($mentorsDisponibles->isEmpty())
                <div style="padding:1rem;font-size:0.82rem;color:var(--gray-400);text-align:center;">
                    <i class="fas fa-info-circle me-1"></i>
                    Aucun mentor disponible — le message a déjà été envoyé dans chaque conversation concernée.
                </div>
            @endif

            @foreach($projetsSansMentor as $pc)
                @php $pub = $pc->publication; @endphp
                <div style="display:flex;align-items:center;gap:1rem;padding:0.85rem 0;border-bottom:1px solid var(--gray-100);">
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;font-size:0.875rem;color:var(--gray-900);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $pub?->titre ?? '—' }}
                        </div>
                        <div style="font-size:0.75rem;color:var(--gray-400);margin-top:0.15rem;">
                            Par {{ $pub?->auteur?->prenom }} {{ $pub?->auteur?->nom }} &mdash;
                            {{ $pc->taille_equipe_actuelle }} membre{{ $pc->taille_equipe_actuelle > 1 ? 's' : '' }}
                        </div>
                    </div>

                    @if($mentorsDisponibles->isNotEmpty())
                        <form action="{{ route('admin.projets.attribuer.mentor', $pc->publication_id) }}" method="POST"
                              style="display:flex;align-items:center;gap:0.5rem;flex-shrink:0;"
                              data-confirm="Attribuer ce mentor à « {{ $pub?->titre }} » ?"
                              data-title="Confirmer l'attribution" data-type="success">
                            @csrf
                            <select name="mentor_id"
                                    style="padding:0.35rem 0.65rem;border:1.5px solid var(--gray-200);border-radius:8px;font-size:0.78rem;font-family:'DM Sans',sans-serif;background:white;outline:none;">
                                @foreach($mentorsDisponibles as $m)
                                    <option value="{{ $m->id }}">{{ $m->prenom }} {{ $m->nom }} ({{ $m->nb_actifs }}/3)</option>
                                @endforeach
                            </select>
                            <button type="submit"
                                    style="padding:0.35rem 0.85rem;background:#D1FAE5;color:#065F46;border:1.5px solid #6EE7B7;border-radius:8px;font-size:0.78rem;font-weight:700;cursor:pointer;white-space:nowrap;">
                                <i class="fas fa-check me-1"></i>Attribuer
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
        @endif

    </div>
@endsection