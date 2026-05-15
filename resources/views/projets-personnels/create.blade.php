@extends('layouts.app')
@section('title', 'Nouveau projet personnel – DevHub')
@section('secondary_nav', true)

@section('styles')
<style>
    .create-pp-wrap {
        max-width: 700px; margin: 0 auto;
    }
    .create-pp-header {
        display: flex; align-items: center; gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .create-pp-back {
        width: 36px; height: 36px; border-radius: 10px;
        background: var(--gray-100); color: var(--gray-500);
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; font-size: 0.9rem; transition: background 0.15s;
        flex-shrink: 0;
    }
    .create-pp-back:hover { background: var(--gray-200); }
    .create-pp-title {
        font-family: 'Urbanist', sans-serif;
        font-size: 1.1rem; font-weight: 600; color: var(--gray-900);
    }
    .create-pp-sub { font-size: 0.82rem; color: var(--gray-400); margin-top: 0.1rem; }

    /* Bannière info mentor */
    .mentor-info-banner {
        background: linear-gradient(135deg, #EDE9FE, #DDD6FE);
        border: 1px solid #C4B5FD;
        border-radius: 14px; padding: 1.1rem 1.25rem;
        display: flex; align-items: center; gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .mentor-info-icon {
        width: 42px; height: 42px; border-radius: 12px;
        background: var(--purple); color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .mentor-info-text h3 {
        font-family: 'Urbanist',sans-serif; font-size: 0.9rem;
        font-weight: 700; color: var(--purple); margin-bottom: 0.15rem;
    }
    .mentor-info-text p { font-size: 0.78rem; color: var(--gray-600); margin: 0; line-height: 1.5; }

    /* Formulaire */
    .form-card {
        background: white; border: 1.5px solid var(--gray-200);
        border-radius: 14px; padding: 1.5rem;
    }
    .form-group { margin-bottom: 1.1rem; }
    .form-label {
        display: block; font-size: 0.82rem; font-weight: 700;
        color: var(--gray-700); margin-bottom: 0.45rem;
        font-family: 'Urbanist', sans-serif;
    }
    .form-required { color: #DC2626; }
    .form-input, .form-textarea {
        width: 100%; padding: 0.7rem 1rem;
        border: 1.5px solid var(--gray-200); border-radius: 10px;
        font-size: 0.875rem; font-family: 'DM Sans', sans-serif;
        color: var(--gray-900); outline: none;
        transition: border-color 0.2s;
    }
    .form-input:focus, .form-textarea:focus { border-color: var(--purple); }
    .form-textarea { resize: vertical; line-height: 1.6; }
    .form-hint { font-size: 0.72rem; color: var(--gray-400); margin-top: 0.3rem; }
    .form-error { font-size: 0.75rem; color: #DC2626; margin-top: 0.3rem; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; }
    @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }

    /* Tags technologies */
    .tech-tags-display {
        display: flex; flex-wrap: wrap; gap: 0.4rem;
        margin-top: 0.5rem; min-height: 28px;
    }
    .tech-tag-pill {
        background: var(--purple-light); color: var(--purple);
        padding: 0.2rem 0.6rem; border-radius: 7px;
        font-size: 0.75rem; font-weight: 600;
        display: flex; align-items: center; gap: 0.3rem;
    }
    .tech-tag-pill button {
        background: none; border: none; cursor: pointer;
        color: var(--purple); padding: 0; line-height: 1;
        font-size: 0.65rem;
    }

    /* Upload images */
    .upload-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; }
    .upload-slot {
        aspect-ratio: 4/3; border: 2px dashed var(--gray-200);
        border-radius: 10px; cursor: pointer;
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; gap: 0.3rem;
        background: var(--gray-50); transition: border-color 0.2s, background 0.2s;
        overflow: hidden; position: relative;
    }
    .upload-slot:hover { border-color: var(--purple); background: var(--purple-light); }
    .upload-slot.filled { border-style: solid; border-color: var(--purple); }
    .upload-slot img { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; }
    .upload-slot-label { font-size: 0.72rem; color: var(--gray-400); text-align: center; }

    /* Submit */
    .btn-submit-pp {
        width: 100%; padding: 0.85rem; background: var(--purple); color: white;
        border: none; border-radius: 12px; font-size: 0.95rem; font-weight: 700;
        cursor: pointer; transition: background 0.2s; font-family: 'DM Sans', sans-serif;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        margin-top: 1.25rem;
    }
    .btn-submit-pp:hover { background: var(--purple-dark); }
    .btn-submit-pp:disabled { background: var(--gray-200); color: var(--gray-400); cursor: not-allowed; }
</style>
@endsection

@section('content')
<main class="main-content">

    <div class="create-pp-header">
        <a href="{{ route('projets-personnels.index') }}" class="create-pp-back">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="create-pp-title">Nouveau projet personnel</h1>
            <p class="create-pp-sub">Décrivez votre projet - un mentor vous sera attribué automatiquement</p>
        </div>
    </div>

    {{-- Bannière mentor déjà actif --}}
    @if($relationActive)
        <div class="mentor-info-banner" style="background:linear-gradient(135deg,#D1FAE5,#A7F3D0);border-color:#6EE7B7;">
            <div class="mentor-info-icon" style="background:#065F46;">
                <i class="fas fa-check"></i>
            </div>
            <div class="mentor-info-text">
                <h3 style="color:#065F46;">Vous avez déjà un mentor actif</h3>
                <p>{{ $relationActive->mentor?->prenom }} {{ $relationActive->mentor?->nom }} vous accompagne déjà. Vous pouvez quand même créer autant de projets que vous souhaitez.</p>
            </div>
        </div>
    @else
        <div class="mentor-info-banner">
            <div class="mentor-info-icon"><i class="fas fa-robot"></i></div>
            <div class="mentor-info-text">
                <h3>Attribution automatique de mentor</h3>
                <p>Après la création de votre projet, DevHub recherche automatiquement un mentor disponible selon les technologies que vous utilisez. Vous serez notifié par email et sur la plateforme.</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('projets-personnels.store') }}" enctype="multipart/form-data" id="ppForm">
        @csrf
        <div class="form-card">

            {{-- Titre --}}
            <div class="form-group">
                <label class="form-label">Titre du projet <span class="form-required">*</span></label>
                <input type="text" name="titre" class="form-input"
                       placeholder="Ex: Portfolio personnel, App de gestion de tâches..."
                       value="{{ old('titre') }}"
                       required maxlength="200">
                @error('titre')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Description --}}
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="contenu" class="form-textarea" rows="4"
                          placeholder="Décrivez votre projet : objectif, fonctionnalités, ce que vous souhaitez apprendre..."
                          maxlength="2000">{{ old('contenu') }}</textarea>
                <div class="form-hint">Max 2000 caractères</div>
            </div>

            {{-- Technologies --}}
            <div class="form-group">
                <label class="form-label">Technologies utilisées</label>
                <div style="position:relative;">
                    <input type="text" id="techInput" class="form-input"
                           placeholder="Tapez une technologie et appuyez sur Entrée (ex: Laravel, React, MySQL...)"
                           autocomplete="off">
                    <input type="hidden" name="technologies" id="techHidden" value="{{ old('technologies') }}">
                </div>
                <div class="tech-tags-display" id="techTagsDisplay"></div>
                <div class="form-hint">Appuyez sur Entrée ou virgule pour ajouter · Ces technologies servent à trouver votre mentor</div>
            </div>

            {{-- Liens --}}
            <div class="form-group form-row">
                <div>
                    <label class="form-label">Lien dépôt (GitHub, GitLab...)</label>
                    <input type="url" name="url_repo" class="form-input"
                           placeholder="https://github.com/..."
                           value="{{ old('url_repo') }}">
                </div>
                <div>
                    <label class="form-label">Lien démonstration</label>
                    <input type="url" name="url_demo" class="form-input"
                           placeholder="https://mon-projet.vercel.app"
                           value="{{ old('url_demo') }}">
                </div>
            </div>

            {{-- Images --}}
            <div class="form-group">
                <label class="form-label">
                    Images du projet
                    <span style="font-weight:400;color:var(--gray-400);">(1 à 3 — optionnel)</span>
                </label>
                <div class="upload-grid">
                    @foreach([1,2,3] as $i)
                        <div class="upload-slot" id="slot{{ $i }}"
                             onclick="document.getElementById('imgSlot{{ $i }}').click()">
                            <input type="file" id="imgSlot{{ $i }}" name="images[]"
                                   accept="image/*" style="display:none;"
                                   onchange="previewSlot(this, {{ $i }})">
                            <i class="fas fa-plus" style="font-size:1.1rem;color:var(--gray-300);"></i>
                            <div class="upload-slot-label">Image {{ $i }}</div>
                        </div>
                    @endforeach
                </div>
                @error('images.*')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-submit-pp" id="btnSubmit">
                <i class="fas fa-rocket"></i>
                Créer mon projet
            </button>

            <p style="text-align:center;font-size:0.75rem;color:var(--gray-400);margin-top:0.75rem;">
                <i class="fas fa-shield-alt me-1"></i>
                Votre projet sera publié et un mentor vous sera automatiquement attribué si disponible.
            </p>

        </div>
    </form>

</main>
@endsection

@section('scripts')
<script>
    // ─── Tag technologies ───────────────────────────────────────
    var tags = [];
    var initialVal = document.getElementById('techHidden').value;
    if (initialVal.trim()) {
        tags = initialVal.split(',').map(function(t) { return t.trim(); }).filter(Boolean);
        renderTags();
    }

    document.getElementById('techInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            var val = this.value.trim().replace(/,$/, '');
            if (val && !tags.includes(val)) {
                tags.push(val);
                renderTags();
            }
            this.value = '';
        }
    });

    function removeTag(tag) {
        tags = tags.filter(function(t) { return t !== tag; });
        renderTags();
    }

    function renderTags() {
        var display = document.getElementById('techTagsDisplay');
        var hidden  = document.getElementById('techHidden');
        display.innerHTML = '';
        tags.forEach(function(tag) {
            var pill = document.createElement('span');
            pill.className = 'tech-tag-pill';
            pill.innerHTML = tag + ' <button type="button" onclick="removeTag(\'' + tag.replace("'", "\\'") + '\')"><i class="fas fa-times"></i></button>';
            display.appendChild(pill);
        });
        hidden.value = tags.join(',');
    }

    // ─── Preview images ─────────────────────────────────────────
    function previewSlot(input, slotNum) {
        if (!input.files || !input.files[0]) return;
        var slot = document.getElementById('slot' + slotNum);
        var reader = new FileReader();
        reader.onload = function(e) {
            slot.classList.add('filled');
            var existing = slot.querySelector('img');
            if (existing) existing.remove();
            var img = document.createElement('img');
            img.src = e.target.result;
            slot.appendChild(img);
        };
        reader.readAsDataURL(input.files[0]);
    }

    // ─── Submit protection ──────────────────────────────────────
    document.getElementById('ppForm').addEventListener('submit', function() {
        var btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création en cours...';
    });
</script>
@endsection
