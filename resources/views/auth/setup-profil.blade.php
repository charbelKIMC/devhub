<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Compléter mon profil – DevHub</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@300;400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/setup-profil.css') }}">
</head>
<body>

<div class="setup-container">

    <!-- Header -->
    <div class="setup-header">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-icon"><i class="fas fa-code"></i></span>
            <span class="brand-name">DevHub</span>
        </a>
        <a href="{{ route('dashboard') }}" class="skip-link">
            Passer cette étape <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>

    <!-- Progression -->
    <div class="progress-wrapper">
        <div class="progress-bar-track">
            <div class="progress-bar-fill" id="progressFill" style="width:50%"></div>
        </div>
        <div class="progress-steps">
            <div class="progress-step active" id="step-indicator-1">
                <div class="step-circle">1</div>
                <div class="step-label">Informations</div>
            </div>
            <div class="progress-step-line"></div>
            <div class="progress-step" id="step-indicator-2">
                <div class="step-circle">2</div>
                <div class="step-label">Liens & Compétences</div>
            </div>
        </div>
    </div>

    <!-- Corps -->
    <div class="setup-body">

        {{-- Erreurs de validation --}}
        @if($errors->any())
        <div id="validationErrors" style="background:#FEE2E2;border:1px solid #FCA5A5;border-radius:10px;padding:.85rem 1.1rem;margin-bottom:1.25rem;font-size:.85rem;color:#DC2626;">
            <div style="font-weight:700;margin-bottom:.35rem;display:flex;align-items:center;gap:.4rem;">
                <i class="fas fa-exclamation-circle"></i> Certaines informations sont invalides :
            </div>
            <ul style="margin:0;padding-left:1.2rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('profil.setup.store') }}" enctype="multipart/form-data" id="setupForm">
            @csrf

            <!-- ─── ÉTAPE 1 ─── -->
            <div class="step-content active" id="step-1">

                <div class="step-title">Parlez-nous de vous</div>
                <p class="step-subtitle">Ces informations aideront les autres développeurs à mieux vous connaître.</p>

                <!-- Photo -->
                <div class="photo-upload-section">
                    <div class="photo-preview" id="photoPreview">
                        <div id="photoPlaceholder">{{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}</div>
                        <img src="" alt="" class="photo-img" id="photoImg" style="display:none;">
                    </div>
                    <div>
                        <div class="photo-upload-title">Photo de profil professionnelle</div>
                        <p class="photo-upload-hint">
                            <i class="fas fa-info-circle me-1"></i>
                            Utilisez une photo professionnelle — pas de selfie informel. Format JPG ou PNG, max 2MB.
                        </p>
                        <label for="photo" class="btn-upload">
                            <i class="fas fa-upload me-2"></i>Choisir une photo
                        </label>
                        <input type="file" id="photo" name="photo" accept="image/jpeg,image/png" style="display:none;">
                    </div>
                </div>

                <!-- Spécialité -->
                <div class="section-label">Votre spécialité principale</div>
                <input type="hidden" name="specialite" id="specialiteInput" value="{{ old('specialite') }}">
                <div class="tags-grid" id="specialiteTags">
                    @foreach([
                        'Développeur Frontend',
                        'Développeur Backend',
                        'Développeur Full-Stack',
                        'Développeur Mobile',
                        'Data Scientist',
                        'DevOps / Cloud',
                        'UI/UX Designer',
                        'Cybersécurité',
                        'Intelligence Artificielle',
                        'Étudiant en informatique',
                        'Autre'
                    ] as $spec)
                        <div class="tag-option" data-value="{{ $spec }}">{{ $spec }}</div>
                    @endforeach
                </div>

                <!-- Bio -->
                <div class="section-label">Courte biographie</div>
                <div class="form-group-setup">
                    <textarea
                        name="bio"
                        class="input-setup textarea-setup"
                        placeholder="Décrivez qui vous êtes, ce que vous faites et ce qui vous passionne dans le développement..."
                        rows="3"
                        maxlength="300"
                        id="bioInput"
                    >{{ old('bio') }}</textarea>
                    <div class="char-count"><span id="bioCount">0</span>/300</div>
                </div>

                <!-- Localisation -->
                <div class="section-label">Localisation</div>
                <div class="form-group-setup">
                    <input type="text" name="localisation" class="input-setup" placeholder="Ex : Cotonou, Bénin" value="{{ old('localisation') }}">
                </div>

                <div class="step-actions">
                    <div></div>
                    <button type="button" class="btn-next" id="goToStep2">
                        Suivant <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>

            <!-- ─── ÉTAPE 2 ─── -->
            <div class="step-content" id="step-2">

                <div class="step-title">Liens & Compétences</div>
                <p class="step-subtitle">Ajoutez vos liens professionnels et sélectionnez vos compétences.</p>

                <!-- Liens -->
                <div class="section-label">Liens professionnels</div>
                <div class="form-row">
                    <div class="form-group-setup">
                        <label class="label-setup"><i class="fab fa-github me-1"></i> GitHub</label>
                        <input type="url" name="github" class="input-setup" placeholder="https://github.com/pseudo" value="{{ old('github') }}">
                    </div>
                    <div class="form-group-setup">
                        <label class="label-setup"><i class="fab fa-linkedin me-1"></i> LinkedIn</label>
                        <input type="url" name="linkedin" class="input-setup" placeholder="https://linkedin.com/in/profil" value="{{ old('linkedin') }}">
                    </div>
                </div>
                <div class="form-group-setup">
                    <label class="label-setup"><i class="fas fa-globe me-1"></i> Portfolio</label>
                    <input type="url" name="portfolio" class="input-setup" placeholder="https://votre-portfolio.com" value="{{ old('portfolio') }}">
                </div>

                <!-- Compétences -->
                <div class="section-label">Vos compétences <span style="text-transform:none;letter-spacing:0;font-weight:400;font-size:0.75rem;">(sélectionnez au moins 1)</span></div>
                <div class="competences-wrapper">
                    @foreach($competences as $categorie => $liste)
                        <div class="competence-category">
                            <div class="competence-category-title">{{ $categorie }}</div>
                            <div class="tags-grid">
                                @foreach($liste as $comp)
                                    <label class="tag-option {{ in_array($comp->id, old('competences', [])) ? 'selected' : '' }}">
                                        <input type="checkbox" name="competences[]" value="{{ $comp->id }}"
                                            {{ in_array($comp->id, old('competences', [])) ? 'checked' : '' }}
                                            style="display:none;">
                                        {{ $comp->nom }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="step-actions">
                    <button type="button" class="btn-prev" id="goToStep1">
                        <i class="fas fa-arrow-left me-2"></i> Retour
                    </button>
                    <button type="submit" class="btn-finish">
                        <i class="fas fa-check me-2"></i> Terminer et accéder à DevHub
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>

@php
    $step2HasErrors = $errors->hasAny(['github','linkedin','portfolio','competences']);
    $oldSpecialite  = old('specialite', '');
@endphp
<script>
    window.SETUP_OLD_SPECIALITE = @json($oldSpecialite);
    window.SETUP_HAS_ERRORS     = @json($errors->any());
    window.SETUP_STEP2_ERRORS   = @json($step2HasErrors);
</script>
<script src="{{ asset('js/setup-profil.js') }}"></script>
</body>
</html>