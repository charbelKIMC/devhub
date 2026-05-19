@extends('layouts.mentor')
@section('title', 'Mon profil – DevHub Mentor')
@section('page_title', 'Mon profil')

@section('styles')
<style>
:root { --purple:#7C3AED; --purple-dark:#6D28D9; --purple-light:#EDE9FE; --purple-xlight:#F5F3FF; --purple-border:#DDD6FE; --gray-50:#F9FAFB; --gray-100:#F3F4F6; --gray-200:#E5E7EB; --gray-400:#9CA3AF; --gray-500:#6B7280; --gray-700:#374151; --gray-900:#111827; }

/* ─── HEADER PROFIL ─── */
.profil-cover {
    height: 140px;
    background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%);
    border-radius: 16px; position: relative; overflow: hidden;
    margin-bottom: 0;
}
.profil-cover::after {
    content:''; position:absolute; inset:0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.profil-head-card {
    background: white; border: 1px solid var(--gray-200);
    border-radius: 16px; padding: 0 1.5rem 1.25rem;
    margin-bottom: 1.25rem; position: relative;
}

.profil-avatar-wrap {
    display: flex; align-items: flex-end; justify-content: space-between;
    margin-top: -48px; padding-bottom: 0.85rem;
    flex-wrap: wrap; gap: 0.75rem;
}

.profil-avatar {
    width: 96px; height: 96px; border-radius: 50%;
    border: 4px solid white; object-fit: cover;
    box-shadow: 0 4px 16px rgba(0,0,0,0.12); flex-shrink: 0;
}
.profil-avatar-text {
    width: 96px; height: 96px; border-radius: 50%;
    border: 4px solid white;
    background: var(--purple-light); color: var(--purple);
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; font-weight: 800;
    box-shadow: 0 4px 16px rgba(0,0,0,0.12); flex-shrink: 0;
}
.profil-avatar-edit {
    position: relative; display: inline-block;
}
.avatar-cam {
    position: absolute; bottom: 4px; right: 4px;
    width: 24px; height: 24px; border-radius: 50%;
    background: var(--purple); color: white; border: 2px solid white;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.6rem; cursor: pointer; text-decoration: none;
}
.avatar-cam:hover { background: var(--purple-dark); }

.mentor-badge-cert {
    display: inline-flex; align-items: center; gap: 0.35rem;
    background: #D1FAE5; color: #065F46;
    padding: 0.25rem 0.75rem; border-radius: 20px;
    font-size: 0.75rem; font-weight: 700; margin-top: 0.35rem;
}

/* Stats bar */
.profil-stats {
    display: grid; grid-template-columns: repeat(3, 1fr);
    border-top: 1px solid var(--gray-100); margin-top: 0.5rem;
}
.profil-stat {
    text-align: center; padding: 0.75rem 0.5rem;
    border-right: 1px solid var(--gray-100);
}
.profil-stat:last-child { border-right: none; }
.profil-stat-val { font-family:'Urbanist',sans-serif; font-size:1.15rem; font-weight:800; color:var(--gray-900); line-height:1; }
.profil-stat-lbl { font-size:0.7rem; color:var(--gray-400); margin-top:0.2rem; }

/* ─── ONGLETS ─── */
.profil-tabs { display:flex; gap:0; border-bottom:2px solid var(--gray-200); margin-bottom:1.1rem; }
.profil-tab {
    display:inline-flex; align-items:center; gap:0.4rem;
    padding:0.65rem 1.1rem; font-size:0.875rem; font-weight:600;
    color:var(--gray-500); cursor:pointer; border:none;
    background:transparent; border-bottom:2px solid transparent;
    margin-bottom:-2px; transition:all 0.2s; font-family:'DM Sans',sans-serif;
}
.profil-tab:hover { color:var(--purple); }
.profil-tab.active { color:var(--purple); border-bottom-color:var(--purple); }
.tab-panel { display:none; }
.tab-panel.active { display:block; }

/* Mentorés */
.mentore-card {
    background:white; border:1px solid var(--gray-200); border-radius:14px;
    padding:1rem 1.25rem; margin-bottom:0.75rem;
    display:flex; align-items:center; gap:1rem; transition:all 0.18s;
}
.mentore-card:hover { border-color:var(--purple); box-shadow:0 4px 16px rgba(124,58,237,0.08); }
.mentore-ava {
    width:46px; height:46px; border-radius:50%;
    background:var(--purple-light); color:var(--purple);
    display:flex; align-items:center; justify-content:center;
    font-size:1rem; font-weight:700; flex-shrink:0; overflow:hidden;
}
.mentore-ava img { width:100%; height:100%; object-fit:cover; }
.mentore-name { font-family:'Urbanist',sans-serif; font-size:0.875rem; font-weight:700; color:var(--gray-900); }
.mentore-spec { font-size:0.72rem; color:var(--purple); font-weight:600; margin-top:0.1rem; }

.btn-msg { display:inline-flex; align-items:center; gap:0.35rem; padding:0.4rem 0.85rem; background:var(--purple); color:white; border:none; border-radius:8px; font-size:0.75rem; font-weight:600; cursor:pointer; text-decoration:none; transition:background 0.2s; margin-left:auto; flex-shrink:0; }
.btn-msg:hover { background:var(--purple-dark); color:white; }

/* Skills */
.skill-chip { display:inline-flex; align-items:center; padding:0.28rem 0.7rem; background:var(--purple-light); color:var(--purple); border-radius:6px; font-size:0.75rem; font-weight:600; }

/* Section empty */
.empty-block { text-align:center; padding:3rem 2rem; background:white; border:1px solid var(--gray-200); border-radius:14px; }
.empty-block i { font-size:2.5rem; color:var(--gray-200); display:block; margin-bottom:0.75rem; }
.empty-block p { font-size:0.85rem; color:var(--gray-400); }

/* ─── SETTINGS ─── */
.settings-wrap { display:grid; grid-template-columns:180px 1fr; gap:1rem; }
.settings-nav { display:flex; flex-direction:column; gap:0.25rem; }
.settings-nav-item {
    display:flex; align-items:center; gap:0.6rem;
    padding:0.6rem 0.85rem; border-radius:9px;
    font-size:0.82rem; font-weight:500; color:var(--gray-600);
    cursor:pointer; transition:all 0.18s; border:none;
    background:transparent; font-family:'DM Sans',sans-serif; text-align:left;
}
.settings-nav-item:hover { background:var(--gray-100); }
.settings-nav-item.active { background:var(--purple-light); color:var(--purple); font-weight:700; }
.settings-body { background:white; border:1px solid var(--gray-200); border-radius:14px; padding:1.5rem; }
.settings-section { display:none; }
.settings-section.active { display:block; }
.settings-section-title { font-family:'Urbanist',sans-serif; font-size:0.95rem; font-weight:700; color:var(--gray-900); margin-bottom:0.25rem; }
.settings-section-sub { font-size:0.78rem; color:var(--gray-400); margin-bottom:1.25rem; }

.form-row { display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; }
.form-group { margin-bottom:0.85rem; }
.form-label { display:block; font-size:0.78rem; font-weight:600; color:var(--gray-600); margin-bottom:0.35rem; }
.form-input {
    width:100%; padding:0.55rem 0.85rem;
    border:1.5px solid var(--gray-200); border-radius:9px;
    font-size:0.875rem; font-family:'DM Sans',sans-serif;
    color:var(--gray-900); outline:none; transition:border-color 0.2s;
    background:var(--gray-50);
}
.form-input:focus { border-color:var(--purple); background:white; }
.form-textarea { resize:vertical; min-height:80px; }
.btn-save { padding:0.6rem 1.5rem; background:var(--purple); color:white; border:none; border-radius:9px; font-size:0.875rem; font-weight:600; cursor:pointer; transition:background 0.2s; font-family:'DM Sans',sans-serif; }
.btn-save:hover { background:var(--purple-dark); }
.photo-wrap { display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem; }
.photo-preview { width:72px; height:72px; border-radius:50%; overflow:hidden; background:var(--purple-light); color:var(--purple); display:flex; align-items:center; justify-content:center; font-size:1.25rem; font-weight:700; flex-shrink:0; }
.photo-preview img { width:100%; height:100%; object-fit:cover; }
.btn-upload { display:inline-flex; align-items:center; gap:0.4rem; padding:0.4rem 0.85rem; border:1.5px solid var(--gray-200); border-radius:8px; font-size:0.78rem; font-weight:600; color:var(--gray-600); cursor:pointer; transition:all 0.18s; }
.btn-upload:hover { border-color:var(--purple); color:var(--purple); }

.comp-category { margin-bottom:1rem; }
.comp-category-title { font-size:0.72rem; font-weight:700; color:var(--gray-400); text-transform:uppercase; letter-spacing:0.4px; margin-bottom:0.4rem; }
.comp-tags-grid { display:flex; flex-wrap:wrap; gap:0.4rem; }
.comp-tag { display:inline-flex; align-items:center; padding:0.28rem 0.7rem; border:1.5px solid var(--gray-200); border-radius:6px; font-size:0.78rem; font-weight:500; color:var(--gray-600); cursor:pointer; transition:all 0.15s; user-select:none; }
.comp-tag:hover { border-color:var(--purple); color:var(--purple); }
.comp-tag.selected { background:var(--purple-light); color:var(--purple); border-color:var(--purple); font-weight:600; }

@media (max-width:768px) {
    .settings-wrap { grid-template-columns:1fr; }
    .form-row { grid-template-columns:1fr; }
    .profil-stats { grid-template-columns:repeat(3,1fr); }
}
</style>
@endsection

@section('content')

@php
    $userCompIds = $user->competences->pluck('id')->toArray();
@endphp

<!-- Couverture -->
<div class="profil-cover"></div>

<!-- Carte principale -->
<div class="profil-head-card">

    <div class="profil-avatar-wrap">
        <div class="profil-avatar-edit">
            @if($user->photo)
                <img src="{{ $user->photo_url }}" class="profil-avatar" alt="">
            @else
                <div class="profil-avatar-text">{{ strtoupper(substr($user->prenom, 0, 1)) }}</div>
            @endif
            <a href="#" class="avatar-cam" onclick="event.preventDefault();document.getElementById('photoQuickInput').click();">
                <i class="fas fa-camera"></i>
            </a>
        </div>

        <div style="flex:1;min-width:0;padding-top:0.5rem;">
            <div style="font-family:'Urbanist',sans-serif;font-size:1.25rem;font-weight:800;color:var(--gray-900);">
                {{ $user->prenom }} {{ $user->nom }}
            </div>
            <div style="font-size:0.82rem;color:var(--gray-400);">{{ '@' . $user->pseudo }}</div>
            <div class="mentor-badge-cert">
                <i class="fas fa-check-circle"></i> Mentor certifié DevHub
            </div>
            @if($user->specialite)
                <div style="font-size:0.78rem;color:var(--gray-500);margin-top:0.3rem;">
                    <i class="fas fa-code me-1" style="color:var(--purple);"></i>{{ $user->specialite }}
                </div>
            @endif
        </div>
    </div>

    @if($user->bio)
        <p style="font-size:0.875rem;color:var(--gray-600);line-height:1.7;margin-bottom:0.85rem;">
            {{ $user->bio }}
        </p>
    @endif

    <!-- Infos -->
    <div style="display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap;font-size:0.78rem;color:var(--gray-500);margin-bottom:0.75rem;">
        @if($user->localisation)
            <span><i class="fas fa-map-marker-alt me-1" style="color:var(--gray-400);"></i>{{ $user->localisation }}</span>
        @endif
        <span><i class="fas fa-calendar-alt me-1" style="color:var(--gray-400);"></i>Mentor depuis {{ $user->date_inscription->format('M Y') }}</span>
        @if($user->github)
            <a href="{{ $user->github }}" target="_blank" style="color:var(--gray-500);text-decoration:none;"><i class="fab fa-github me-1"></i>GitHub</a>
        @endif
        @if($user->linkedin)
            <a href="{{ $user->linkedin }}" target="_blank" style="color:var(--gray-500);text-decoration:none;"><i class="fab fa-linkedin me-1"></i>LinkedIn</a>
        @endif
    </div>

    <!-- Stats -->
    <div class="profil-stats">
        <div class="profil-stat">
            <div class="profil-stat-val">{{ $mentores->count() }}</div>
            <div class="profil-stat-lbl">Mentorés actifs</div>
        </div>
        <div class="profil-stat">
            <div class="profil-stat-val">{{ $totalMentores }}</div>
            <div class="profil-stat-lbl">Total mentorés</div>
        </div>
        <div class="profil-stat">
            <div class="profil-stat-val">{{ $user->competences->count() }}</div>
            <div class="profil-stat-lbl">Compétences</div>
        </div>
    </div>
</div>

<!-- Onglets -->
<div class="profil-tabs">
    <button class="profil-tab active" data-tab="apercu">
        <i class="fas fa-th-large"></i> Aperçu
    </button>
    <button class="profil-tab" data-tab="settings">
        <i class="fas fa-cog"></i> Paramètres
    </button>
</div>

<!-- Onglet aperçu -->
<div class="tab-panel active" id="apercu">

    @if($user->competences->isNotEmpty())
        <div style="background:white;border:1px solid var(--gray-200);border-radius:14px;padding:1.1rem 1.25rem;margin-bottom:1rem;">
            <div style="font-family:'Urbanist',sans-serif;font-size:0.82rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:0.75rem;">
                <i class="fas fa-code me-2" style="color:var(--purple);"></i>Compétences
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:0.4rem;">
                @foreach($user->competences as $comp)
                    <span class="skill-chip">{{ $comp->nom }}</span>
                @endforeach
            </div>
        </div>
    @endif

    <div style="font-family:'Urbanist',sans-serif;font-size:0.82rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:0.75rem;">
        <i class="fas fa-users me-2" style="color:var(--purple);"></i>Mentorés actifs
        <span style="background:var(--purple);color:white;font-size:0.65rem;font-weight:700;padding:1px 7px;border-radius:10px;margin-left:0.4rem;">{{ $mentores->count() }}/3</span>
    </div>

    @forelse($mentores as $rel)
        <div class="mentore-card">
            <div class="mentore-ava">
                @if($rel->mentore?->photo)
                    <img src="{{ $rel->mentore->photo_url }}" alt="">
                @else
                    {{ strtoupper(substr($rel->mentore?->prenom ?? '?', 0, 1)) }}
                @endif
            </div>
            <div>
                <div class="mentore-name">{{ $rel->mentore?->prenom }} {{ $rel->mentore?->nom }}</div>
                <div class="mentore-spec">{{ $rel->mentore?->specialite ?? 'Développeur' }}</div>
                <div style="font-size:0.7rem;color:var(--gray-400);margin-top:0.2rem;">
                    <i class="fas fa-clock me-1"></i>Depuis {{ $rel->date_debut ? $rel->date_debut->diffForHumans() : '—' }}
                </div>
            </div>
            @php
                $conv = \App\Models\Conversation::whereHas('participants', fn($q) =>
                    $q->where('utilisateurs.id', $rel->mentore_id)
                )->whereHas('participants', fn($q) =>
                    $q->where('utilisateurs.id', Auth::id())
                )->where('type_conversation', 'direct')->first();
            @endphp
            @if($conv)
                <a href="{{ route('messagerie.show', $conv->id) }}" class="btn-msg">
                    <i class="fas fa-comment-dots"></i> Message
                </a>
            @endif
        </div>
    @empty
        <div class="empty-block">
            <i class="fas fa-user-graduate"></i>
            <p>Aucun mentoré actif pour le moment.</p>
        </div>
    @endforelse

</div>

<!-- Onglet paramètres -->
<div class="tab-panel" id="settings">
    <div class="settings-wrap">

        <div class="settings-nav">
            <button class="settings-nav-item active" data-setting="profil-form">
                <i class="fas fa-user"></i> Profil
            </button>
            <button class="settings-nav-item" data-setting="password-form">
                <i class="fas fa-lock"></i> Mot de passe
            </button>
        </div>

        <div class="settings-body">

            <!-- Profil -->
            <div class="settings-section active" id="profil-form">
                <div class="settings-section-title">Informations du profil</div>
                <div class="settings-section-sub">Visibles dans votre espace mentor.</div>

                <form method="POST" action="{{ route('mentor.profil.update') }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="photo-wrap">
                        <div class="photo-preview">
                            @if($user->photo)
                                <img src="{{ $user->photo_url }}" id="photoPreview" alt="">
                            @else
                                <span id="photoPlaceholder">{{ strtoupper(substr($user->prenom, 0, 1)) }}</span>
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
                            <input type="text" name="prenom" class="form-input" value="{{ old('prenom', $user->prenom) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-input" value="{{ old('nom', $user->nom) }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Spécialité</label>
                        <input type="text" name="specialite" class="form-input" value="{{ old('specialite', $user->specialite) }}" placeholder="Ex: Expert Back-End Laravel">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-input form-textarea" placeholder="Parlez de votre expérience et de votre approche du mentorat...">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Localisation</label>
                        <input type="text" name="localisation" class="form-input" value="{{ old('localisation', $user->localisation) }}" placeholder="Ex: Cotonou, Bénin">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-github me-1"></i>GitHub</label>
                            <input type="url" name="github" class="form-input" value="{{ old('github', $user->github) }}" placeholder="https://github.com/...">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-linkedin me-1"></i>LinkedIn</label>
                            <input type="url" name="linkedin" class="form-input" value="{{ old('linkedin', $user->linkedin) }}" placeholder="https://linkedin.com/in/...">
                        </div>
                    </div>

                    <!-- Compétences -->
                    <div class="form-group" style="margin-top:1rem;">
                        <label class="form-label" style="margin-bottom:0.75rem;"><i class="fas fa-code me-1"></i>Compétences</label>
                        @foreach($competences as $categorie => $liste)
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
            <div class="settings-section" id="password-form">
                <div class="settings-section-title">Changer le mot de passe</div>
                <div class="settings-section-sub">Minimum 8 caractères.</div>

                <form method="POST" action="{{ route('mentor.profil.mdp') }}">
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
                        <label class="form-label">Confirmer</label>
                        <input type="password" name="mot_de_passe_confirmation" class="form-input" placeholder="Répétez le mot de passe">
                    </div>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-lock me-1"></i> Changer le mot de passe
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Upload rapide photo (bouton caméra sur l'avatar) -->
<form id="quickPhotoForm" method="POST" action="{{ route('mentor.profil.update') }}"
      enctype="multipart/form-data" style="display:none;">
    @csrf @method('PUT')
    <input type="file" id="photoQuickInput" name="photo" accept="image/jpeg,image/png"
           onchange="this.form.submit()">
</form>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Onglets principaux
    document.querySelectorAll('.profil-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.profil-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            var target = document.getElementById(this.dataset.tab);
            if (target) target.classList.add('active');
        });
    });

    // Onglets settings
    document.querySelectorAll('.settings-nav-item').forEach(function (item) {
        item.addEventListener('click', function () {
            document.querySelectorAll('.settings-nav-item').forEach(i => i.classList.remove('active'));
            document.querySelectorAll('.settings-section').forEach(s => s.classList.remove('active'));
            this.classList.add('active');
            var target = document.getElementById(this.dataset.setting);
            if (target) target.classList.add('active');
        });
    });

    // Toggle compétences
    document.querySelectorAll('#profil-form .comp-tag').forEach(function (tag) {
        tag.addEventListener('click', function (e) {
            e.preventDefault();
            var cb = tag.querySelector('input[type="checkbox"]');
            cb.checked = !cb.checked;
            tag.classList.toggle('selected', cb.checked);
        });
    });

    // Preview photo
    document.getElementById('photoInput')?.addEventListener('change', function () {
        if (!this.files[0]) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            var preview = document.getElementById('photoPreview');
            var placeholder = document.getElementById('photoPlaceholder');
            if (preview) {
                preview.src = e.target.result;
            } else if (placeholder) {
                var img = document.createElement('img');
                img.src = e.target.result; img.id = 'photoPreview';
                img.style.cssText = 'width:100%;height:100%;object-fit:cover;';
                placeholder.parentNode.replaceChild(img, placeholder);
            }
        };
        reader.readAsDataURL(this.files[0]);
    });

    // Afficher paramètres si erreur de validation
    @if($errors->any())
        var settingsTab = document.querySelector('[data-tab="settings"]');
        if (settingsTab) settingsTab.click();
    @endif
});
</script>
@endsection
