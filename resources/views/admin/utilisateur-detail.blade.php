@extends('layouts.admin')
@section('title', 'Détail utilisateur – Admin DevHub')
@section('page_title', 'Détail utilisateur')

@section('styles')
<style>
:root { --purple:#7C3AED; --purple-dark:#6D28D9; --purple-light:#EDE9FE; --purple-xlight:#F5F3FF; --purple-border:#DDD6FE; --gray-50:#F9FAFB; --gray-100:#F3F4F6; --gray-200:#E5E7EB; --gray-400:#9CA3AF; --gray-500:#6B7280; --gray-700:#374151; --gray-900:#111827; }

.detail-layout { display:grid; grid-template-columns:280px 1fr; gap:1.25rem; max-width:1000px; }

.profile-card { background:white; border:1px solid var(--gray-200); border-radius:14px; overflow:hidden; }
.profile-card-top { background:var(--purple-xlight); padding:1.5rem; text-align:center; border-bottom:1px solid var(--purple-border); }
.profile-ava { width:72px; height:72px; border-radius:50%; background:var(--purple-light); color:var(--purple); display:flex; align-items:center; justify-content:center; font-size:1.5rem; font-weight:800; margin:0 auto 0.75rem; overflow:hidden; border:3px solid white; box-shadow:0 4px 12px rgba(0,0,0,0.08); }
.profile-ava img { width:100%; height:100%; object-fit:cover; }
.profile-name { font-family:'Urbanist',sans-serif; font-size:1rem; font-weight:800; color:var(--gray-900); }
.profile-pseudo { font-size:0.78rem; color:var(--gray-400); margin-top:0.1rem; }
.profile-spec { display:inline-flex; align-items:center; gap:0.3rem; background:var(--purple-light); color:var(--purple); padding:0.2rem 0.65rem; border-radius:6px; font-size:0.72rem; font-weight:600; margin-top:0.5rem; }

.profile-info-list { padding:1rem 1.25rem; display:flex; flex-direction:column; gap:0.5rem; }
.profile-info-item { display:flex; align-items:center; gap:0.5rem; font-size:0.78rem; color:var(--gray-600); }
.profile-info-item i { width:14px; color:var(--gray-400); }

.profile-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:0; border-top:1px solid var(--gray-100); }
.pstat { text-align:center; padding:0.75rem 0.5rem; border-right:1px solid var(--gray-100); }
.pstat:last-child { border-right:none; }
.pstat-val { font-family:'Urbanist',sans-serif; font-size:1.1rem; font-weight:800; color:var(--gray-900); }
.pstat-label { font-size:0.62rem; color:var(--gray-400); margin-top:0.1rem; }

.profile-actions { padding:1rem 1.25rem; border-top:1px solid var(--gray-100); display:flex; flex-direction:column; gap:0.4rem; }
.btn-action { display:flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.55rem; border-radius:9px; font-size:0.82rem; font-weight:600; cursor:pointer; border:none; font-family:'DM Sans',sans-serif; transition:all .18s; }
.btn-suspend { background:#FEE2E2; color:#DC2626; }
.btn-suspend:hover { background:#DC2626; color:white; }
.btn-activate { background:#D1FAE5; color:#065F46; }
.btn-activate:hover { background:#065F46; color:white; }
.btn-delete { background:transparent; color:#DC2626; border:1px solid #FCA5A5; }
.btn-delete:hover { background:#DC2626; color:white; }

/* Compétences */
.skills-wrap { display:flex; flex-wrap:wrap; gap:0.4rem; padding:0.75rem 1.25rem; border-top:1px solid var(--gray-100); }
.skill-chip { background:var(--purple-light); color:var(--purple); padding:0.2rem 0.6rem; border-radius:6px; font-size:0.72rem; font-weight:600; }

/* Contenu droit */
.section-card { background:white; border:1px solid var(--gray-200); border-radius:14px; overflow:hidden; margin-bottom:1rem; }
.section-header { padding:0.85rem 1.25rem; border-bottom:1px solid var(--gray-100); display:flex; align-items:center; justify-content:space-between; }
.section-title { font-family:'Urbanist',sans-serif; font-size:0.82rem; font-weight:700; color:var(--gray-500); text-transform:uppercase; letter-spacing:0.5px; }

.pub-item { display:flex; align-items:center; gap:0.85rem; padding:0.85rem 1.25rem; border-bottom:1px solid var(--gray-50); }
.pub-item:last-child { border-bottom:none; }
.pub-type-dot { width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:0.75rem; flex-shrink:0; }
.pub-title { font-size:0.875rem; font-weight:600; color:var(--gray-900); text-decoration:none; display:block; }
.pub-title:hover { color:var(--purple); }
.pub-meta { font-size:0.72rem; color:var(--gray-400); margin-top:0.1rem; }
.pub-stats { display:flex; gap:0.65rem; margin-left:auto; flex-shrink:0; }
.pub-stat { font-size:0.72rem; color:var(--gray-400); display:flex; align-items:center; gap:0.2rem; }

.bio-text { padding:1rem 1.25rem; font-size:0.875rem; color:var(--gray-600); line-height:1.7; }

@media(max-width:992px) { .detail-layout { grid-template-columns:1fr; } }
</style>
@endsection

@section('content')

<!-- Retour -->
<div style="margin-bottom:1rem;">
    <a href="{{ route('admin.utilisateurs') }}"
       style="display:inline-flex;align-items:center;gap:0.5rem;font-size:0.82rem;color:var(--gray-500);text-decoration:none;padding:0.4rem 0.85rem;border:1px solid var(--gray-200);border-radius:8px;background:white;">
        <i class="fas fa-arrow-left"></i> Retour aux utilisateurs
    </a>
</div>

<div class="detail-layout">

    <!-- Sidebar gauche -->
    <div>
        <div class="profile-card">

            <!-- Top -->
            <div class="profile-card-top">
                <div class="profile-ava">
                    @if($utilisateur->photo)
                        <img src="{{ $utilisateur->photo_url }}" alt="">
                    @else
                        {{ strtoupper(substr($utilisateur->prenom, 0, 1)) }}
                    @endif
                </div>
                <div class="profile-name">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</div>
                <div class="profile-pseudo">{{ '@' . $utilisateur->pseudo }}</div>
                @if($utilisateur->specialite)
                    <div style="margin-top:0.35rem;">
                        <span class="profile-spec"><i class="fas fa-code"></i> {{ $utilisateur->specialite }}</span>
                    </div>
                @endif
                <!-- Statut -->
                <div style="margin-top:0.5rem;">
                    <span style="background:{{ $utilisateur->statut==='actif' ? '#D1FAE5' : '#FEE2E2' }};color:{{ $utilisateur->statut==='actif' ? '#065F46' : '#DC2626' }};padding:0.2rem 0.65rem;border-radius:20px;font-size:0.72rem;font-weight:700;">
                        <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;margin-right:3px;"></span>
                        {{ ucfirst($utilisateur->statut) }}
                    </span>
                    <span style="background:var(--purple-light);color:var(--purple);padding:0.2rem 0.65rem;border-radius:20px;font-size:0.72rem;font-weight:700;margin-left:0.35rem;">
                        {{ ucfirst($utilisateur->role) }}
                    </span>
                </div>
            </div>

            <!-- Stats -->
            <div class="profile-stats">
                <div class="pstat">
                    <div class="pstat-val">{{ $utilisateur->publications()->where('statut','publié')->count() }}</div>
                    <div class="pstat-label">Pubs</div>
                </div>
                <div class="pstat">
                    <div class="pstat-val">{{ $collabs }}</div>
                    <div class="pstat-label">Collabs</div>
                </div>
                <div class="pstat">
                    <div class="pstat-val">{{ $note ? number_format($note,1) : '—' }}</div>
                    <div class="pstat-label">Note</div>
                </div>
            </div>

            <!-- Infos -->
            <div class="profile-info-list">
                <div class="profile-info-item">
                    <i class="fas fa-envelope"></i> {{ $utilisateur->email }}
                </div>
                @if($utilisateur->telephone)
                    <div class="profile-info-item">
                        <i class="fas fa-phone"></i> {{ $utilisateur->telephone }}
                    </div>
                @endif
                @if($utilisateur->localisation)
                    <div class="profile-info-item">
                        <i class="fas fa-map-marker-alt"></i> {{ $utilisateur->localisation }}
                    </div>
                @endif
                <div class="profile-info-item">
                    <i class="fas fa-calendar-alt"></i>
                    Inscrit {{ $utilisateur->date_inscription->format('d/m/Y') }}
                </div>
                @if($utilisateur->derniere_connexion)
                    <div class="profile-info-item">
                        <i class="fas fa-clock"></i>
                        Vu {{ $utilisateur->derniere_connexion->diffForHumans() }}
                    </div>
                @endif
            </div>

            <!-- Liens sociaux -->
            @if($utilisateur->github || $utilisateur->linkedin || $utilisateur->portfolio)
                <div style="padding:0.5rem 1.25rem 0.75rem;border-top:1px solid var(--gray-100);display:flex;gap:0.4rem;">
                    @if($utilisateur->github)
                        <a href="{{ $utilisateur->github }}" target="_blank"
                           style="width:30px;height:30px;border-radius:7px;border:1px solid var(--gray-200);display:flex;align-items:center;justify-content:center;color:var(--gray-500);text-decoration:none;font-size:0.82rem;transition:all .15s;"
                           onmouseover="this.style.borderColor='var(--purple)';this.style.color='var(--purple)'"
                           onmouseout="this.style.borderColor='var(--gray-200)';this.style.color='var(--gray-500)'">
                            <i class="fab fa-github"></i>
                        </a>
                    @endif
                    @if($utilisateur->linkedin)
                        <a href="{{ $utilisateur->linkedin }}" target="_blank"
                           style="width:30px;height:30px;border-radius:7px;border:1px solid var(--gray-200);display:flex;align-items:center;justify-content:center;color:var(--gray-500);text-decoration:none;font-size:0.82rem;transition:all .15s;"
                           onmouseover="this.style.borderColor='#0A66C2';this.style.color='#0A66C2'"
                           onmouseout="this.style.borderColor='var(--gray-200)';this.style.color='var(--gray-500)'">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    @endif
                    @if($utilisateur->portfolio)
                        <a href="{{ $utilisateur->portfolio }}" target="_blank"
                           style="width:30px;height:30px;border-radius:7px;border:1px solid var(--gray-200);display:flex;align-items:center;justify-content:center;color:var(--gray-500);text-decoration:none;font-size:0.82rem;transition:all .15s;"
                           onmouseover="this.style.borderColor='var(--purple)';this.style.color='var(--purple)'"
                           onmouseout="this.style.borderColor='var(--gray-200)';this.style.color='var(--gray-500)'">
                            <i class="fas fa-globe"></i>
                        </a>
                    @endif
                </div>
            @endif

            <!-- Compétences -->
            @if($utilisateur->competences->isNotEmpty())
                <div class="skills-wrap">
                    @foreach($utilisateur->competences as $comp)
                        <span class="skill-chip">{{ $comp->nom }}</span>
                    @endforeach
                </div>
            @endif

            <!-- Actions -->
            <div class="profile-actions">
                @if($utilisateur->statut === 'actif')
                    <form action="{{ route('admin.utilisateurs.suspendre', $utilisateur->id) }}" method="POST"
                        data-confirm="Le compte de {{ $utilisateur->prenom }} {{ $utilisateur->nom }} sera suspendu et l'utilisateur ne pourra plus se connecter."
                        data-title="Suspendre ce compte ?"
                        data-type="warning">
                        @csrf @method('PUT')
                        <button type="submit" class="btn-action btn-suspend" style="width:100%;">
                            <i class="fas fa-ban"></i> Suspendre le compte
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.utilisateurs.activer', $utilisateur->id) }}" method="POST"
                        data-confirm="Le compte de {{ $utilisateur->prenom }} {{ $utilisateur->nom }} sera réactivé."
                        data-title="Activer ce compte ?"
                        data-type="success">
                        @csrf @method('PUT')
                        <button type="submit" class="btn-action btn-activate" style="width:100%;">
                            <i class="fas fa-check"></i> Activer le compte
                        </button>
                    </form>
                @endif
                <form action="{{ route('admin.utilisateurs.supprimer', $utilisateur->id) }}" method="POST"
                      data-confirm="Le compte de {{ $utilisateur->prenom }} {{ $utilisateur->nom }} sera définitivement supprimé."
                      data-title="Supprimer ce compte ?"
                      data-type="danger">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-action btn-delete" style="width:100%;">
                        <i class="fas fa-trash"></i> Supprimer le compte
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Contenu droit -->
    <div>

        <!-- Bio -->
        @if($utilisateur->bio)
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title"><i class="fas fa-user me-2" style="color:var(--purple);"></i>Bio</span>
                </div>
                <div class="bio-text">{{ $utilisateur->bio }}</div>
            </div>
        @endif

        <!-- Publications -->
        <div class="section-card">
            <div class="section-header">
                <span class="section-title">
                    <i class="fas fa-folder-open me-2" style="color:var(--purple);"></i>Publications
                </span>
                <span style="font-size:0.78rem;color:var(--gray-400);">
                    {{ $utilisateur->publications()->where('statut','publié')->count() }} publiée(s)
                </span>
            </div>

            @php
                $pubs = $utilisateur->publications()->where('statut','publié')->orderBy('date_publication','desc')->take(8)->get();
                $typeConfig = [
                    'article'             => ['Article',     '#DBEAFE','#1D4ED8','fa-newspaper'],
                    'idee'                => ['Idée',        '#FEF9C3','#854D0E','fa-lightbulb'],
                    'projet_personnel'    => ['Projet',      '#EDE9FE','#7C3AED','fa-folder'],
                    'projet_collaboratif' => ['Collab',      '#D1FAE5','#065F46','fa-users'],
                ];
            @endphp

            @forelse($pubs as $pub)
                @php $tc = $typeConfig[$pub->type] ?? ['','#F3F4F6','#6B7280','fa-file']; @endphp
                <div class="pub-item">
                    <div class="pub-type-dot" style="background:{{ $tc[1] }};color:{{ $tc[2] }};">
                        <i class="fas {{ $tc[3] }}"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <a href="{{ route('publications.show', $pub->id) }}" target="_blank" class="pub-title">
                            {{ Str::limit($pub->titre, 55) }}
                        </a>
                        <div class="pub-meta">
                            <span style="background:{{ $tc[1] }};color:{{ $tc[2] }};padding:0.1rem 0.4rem;border-radius:4px;font-size:0.65rem;font-weight:600;">{{ $tc[0] }}</span>
                            · {{ $pub->date_publication->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="pub-stats">
                        <span class="pub-stat"><i class="fas fa-eye"></i> {{ $pub->nb_vues }}</span>
                        <span class="pub-stat"><i class="fas fa-heart" style="color:#E11D48;"></i> {{ $pub->nb_likes }}</span>
                    </div>
                </div>
            @empty
                <div style="text-align:center;padding:2rem;color:var(--gray-400);font-size:0.82rem;">
                    Aucune publication publiée
                </div>
            @endforelse
        </div>

        <!-- Mentorat (si mentor) -->
        @if($utilisateur->role === 'mentor')
            @php
                $mentores = \App\Models\RelationMentorat::with('mentore')
                    ->where('mentor_id', $utilisateur->id)
                    ->where('statut','active')->get();
            @endphp
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title">
                        <i class="fas fa-users me-2" style="color:#065F46;"></i>Mentorés
                    </span>
                    <span style="font-size:0.78rem;color:var(--gray-400);">{{ $mentores->count() }}</span>
                </div>
                @forelse($mentores as $rel)
                    <div class="pub-item">
                        <div style="width:32px;height:32px;border-radius:50%;background:#D1FAE5;color:#065F46;display:flex;align-items:center;justify-content:center;font-size:0.78rem;font-weight:700;flex-shrink:0;">
                            {{ strtoupper(substr($rel->mentore->prenom,0,1)) }}
                        </div>
                        <div>
                            <div style="font-size:0.875rem;font-weight:600;color:var(--gray-900);">
                                {{ $rel->mentore->prenom }} {{ $rel->mentore->nom }}
                            </div>
                            <div style="font-size:0.72rem;color:var(--gray-400);">
                                Depuis {{ $rel->date_debut->diffForHumans() }}
                            </div>
                        </div>
                        <a href="{{ route('admin.utilisateurs.detail', $rel->mentore->id) }}"
                           style="margin-left:auto;font-size:0.75rem;color:var(--purple);text-decoration:none;padding:0.25rem 0.65rem;border:1px solid var(--purple-border);border-radius:6px;background:var(--purple-light);">
                            Voir
                        </a>
                    </div>
                @empty
                    <div style="text-align:center;padding:2rem;color:var(--gray-400);font-size:0.82rem;">Aucun mentoré</div>
                @endforelse
            </div>
        @endif

    </div>
</div>
@endsection