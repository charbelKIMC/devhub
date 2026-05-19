<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devenir mentor – DevHub</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@300;400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mentor-candidature.css') }}">
</head>
<body>

<!-- HEADER -->
<header class="header">
    <div class="header-inner">
        <div class="header-left">
            <a href="{{ route('home') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Retour à l'accueil
            </a>
        </div>
        <div class="header-auth">
            <a href="{{ route('register') }}" class="btn-fill">Continuer en tant que développeur</a>
        </div>
    </div>
</header>

<!-- HERO BANDEAU -->
<div class="mentor-hero">
    <div class="mentor-hero-badge">
        <i class="fas fa-chalkboard-teacher"></i> Programme Mentorat DevHub
    </div>
    <div class="mentor-hero-title">Postuler comme mentor</div>
    <p class="mentor-hero-sub">
        Partagez votre expertise et accompagnez les développeurs juniors dans leur progression.
        Remplissez ce formulaire - notre équipe examinera votre candidature et vous contactera par email.
    </p>
</div>

<!-- FORMULAIRE PLEINE PAGE -->
<div class="form-wrapper">

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
        </div>
    @endif

    <div class="form-page-title">Formulaire de candidature mentor</div>
    <p class="form-page-sub">Tous les champs sont obligatoires sauf mention contraire.</p>

    <form method="POST" action="{{ route('mentor.externe.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">

            <!-- ─── COLONNE GAUCHE ─── -->
            <div>

                <!-- Identité -->
                <div class="form-section-title">Informations personnelles</div>

                <div class="field-row">
                    <div class="field-group">
                        <label>Prénom <span class="required-star">*</span></label>
                        <input type="text" name="prenom" class="field-input"
                               value="{{ old('prenom') }}" placeholder="Votre prénom" required>
                    </div>
                    <div class="field-group">
                        <label>Nom <span class="required-star">*</span></label>
                        <input type="text" name="nom" class="field-input"
                               value="{{ old('nom') }}" placeholder="Votre nom" required>
                    </div>
                </div>

                <div class="field-group">
                    <label>Adresse email <span class="required-star">*</span></label>
                    <input type="email" name="email" class="field-input"
                           value="{{ old('email') }}" placeholder="votre@email.com" required>
                </div>

                <div class="field-group">
                    <label>Numéro de téléphone <span class="required-star">*</span></label>
                    <input type="tel" name="telephone" class="field-input"
                           value="{{ old('telephone') }}" placeholder="+229 01 XX XX XX XX" required>
                </div>

                <div class="field-group">
                    <label>Localisation <span class="required-star">*</span></label>
                    <input type="text" name="localisation" class="field-input"
                           value="{{ old('localisation') }}" placeholder="Ex: Cotonou, Bénin" required>
                </div>

                <!-- Pièce d'identité -->
                <div class="form-section-title" style="margin-top:2rem;">Pièce d'identité</div>

                <div class="field-group">
                    <label>Type de pièce <span class="required-star">*</span></label>
                    <select name="type_identite" id="typeIdentite" class="field-input" required>
                        <option value="">Sélectionnez le type</option>
                        <option value="cni"      {{ old('type_identite') === 'cni'       ? 'selected' : '' }}>Carte nationale d'identité</option>
                        <option value="passeport"{{ old('type_identite') === 'passeport' ? 'selected' : '' }}>Passeport</option>
                        <option value="cip"      {{ old('type_identite') === 'cip'       ? 'selected' : '' }}>Certificat d'identification personnel</option>
                        <option value="autre"    {{ old('type_identite') === 'autre'     ? 'selected' : '' }}>Autre document officiel</option>
                    </select>
                </div>

                <div class="field-group">
                    <label>Numéro de la pièce <span class="required-star">*</span></label>
                    <input type="text" name="numero_identite" id="numeroIdentite" class="field-input"
                           value="{{ old('numero_identite') }}" placeholder="Sélectionnez d'abord le type de pièce" required>
                    <small id="hintNumero" style="color:#6B7280;font-size:0.78rem;margin-top:0.25rem;display:block;"></small>
                </div>

                <div class="field-group">
                    <label>Scan de la pièce d'identité <span class="required-star">*</span></label>
                    <div class="upload-zone" id="zoneIdentite">
                        <input type="file" name="piece_identite" id="fileIdentite"
                               accept=".pdf,.jpg,.jpeg,.png" required>
                        <div class="upload-icon"><i class="fas fa-id-card"></i></div>
                        <div class="upload-label">Cliquez pour uploader</div>
                        <div class="upload-hint">PDF, JPG ou PNG – Max 5MB</div>
                        <div class="upload-filename" id="nameIdentite"></div>
                    </div>
                </div>

            </div>

            <!-- ─── COLONNE DROITE ─── -->
            <div>

                <!-- Profil professionnel -->
                <div class="form-section-title">Profil professionnel</div>

                <div class="field-group">
                    <label>Spécialité principale <span class="required-star">*</span></label>
                    <select name="specialite" class="field-input" required>
                        <option value="">Sélectionnez votre spécialité</option>
                        <option value="Développeur Frontend">Développeur Frontend</option>
                        <option value="Développeur Backend">Développeur Backend</option>
                        <option value="Développeur Full-Stack">Développeur Full-Stack</option>
                        <option value="Développeur Mobile">Développeur Mobile</option>
                        <option value="Data Scientist">Data Scientist</option>
                        <option value="DevOps / Cloud">DevOps / Cloud</option>
                        <option value="UI/UX Designer">UI/UX Designer</option>
                        <option value="Cybersécurité">Cybersécurité</option>
                        <option value="Intelligence Artificielle">Intelligence Artificielle</option>
                        <option value="Architecte logiciel">Architecte logiciel</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>

                <div class="field-group">
                    <label>Années d'expérience <span class="required-star">*</span></label>
                    <select name="annees_experience" class="field-input" required>
                        <option value="">Sélectionnez</option>
                        <option value="1-3 ans">1 à 3 ans</option>
                        <option value="3-5 ans">3 à 5 ans</option>
                        <option value="5-10 ans">5 à 10 ans</option>
                        <option value="10+ ans">Plus de 10 ans</option>
                    </select>
                </div>

                <div class="field-group">
                    <label>Résumé de votre parcours <span class="required-star">*</span></label>
                    <textarea name="experience" class="field-input" rows="4"
                              placeholder="Décrivez brièvement votre parcours professionnel : entreprises, projets marquants, technologies maîtrisées..." required>{{ old('experience') }}</textarea>
                </div>

                <!-- Liens -->
                <div class="form-section-title" style="margin-top:2rem;">Liens professionnels</div>

                <div class="field-group">
                    <label>Portfolio / Site web <span class="required-star">*</span></label>
                    <input type="url" name="portfolio" class="field-input"
                           value="{{ old('portfolio') }}" placeholder="https://votre-portfolio.com" required>
                </div>

                <div class="field-group">
                    <label>Profil LinkedIn <span class="required-star">*</span></label>
                    <input type="url" name="linkedin" class="field-input"
                           value="{{ old('linkedin') }}" placeholder="https://linkedin.com/in/..." required>
                </div>

                <!-- Documents -->
                <div class="form-section-title" style="margin-top:2rem;">Documents à fournir</div>

                <div class="field-group">
                    <label>CV (Curriculum Vitae) <span class="required-star">*</span></label>
                    <div class="upload-zone" id="zoneCv">
                        <input type="file" name="cv" id="fileCv"
                               accept=".pdf,.doc,.docx" required>
                        <div class="upload-icon"><i class="fas fa-file-alt"></i></div>
                        <div class="upload-label">Cliquez pour uploader votre CV</div>
                        <div class="upload-hint">PDF, DOC ou DOCX – Max 5MB</div>
                        <div class="upload-filename" id="nameCv"></div>
                    </div>
                </div>

                <!-- Motivation -->
                <div class="form-section-title" style="margin-top:2rem;">Motivation</div>

                <div class="field-group">
                    <label>Pourquoi voulez-vous devenir mentor ? <span class="required-star">*</span></label>
                    <textarea name="motivation" class="field-input" rows="5"
                              placeholder="Expliquez votre motivation, ce que vous souhaitez apporter aux développeurs juniors et votre vision du mentorat..."
                              required maxlength="1500">{{ old('motivation') }}</textarea>
                </div>

                <!-- Références professionnelles -->
                <div class="form-section-title" style="margin-top:2rem;">Références professionnelles</div>

                <div class="field-group">
                    <label>Références <span class="required-star">*</span></label>
                    <textarea name="references" class="field-input" rows="4"
                              placeholder="Indiquez les entreprises où vous avez travaillé et un numéro de contact joignable pour chaque référence.&#10;Ex : Orange Bénin — +229 01 XX XX XX XX&#10;     StartupXYZ — +229 01 YY YY YY YY"
                              required maxlength="1000">{{ old('references') }}</textarea>
                    <small style="color:#9CA3AF;font-size:0.78rem;">
                        <i class="fas fa-info-circle me-1"></i>
                        Minimum 1 référence avec numéro de téléphone vérifiable.
                    </small>
                </div>

            </div>
        </div>

        <!-- Submit -->
        <div class="submit-section">
            <p class="submit-note">
                <i class="fas fa-shield-alt me-1" style="color:var(--purple);"></i>
                Vos informations sont traitées de manière confidentielle. Notre équipe examinera votre dossier
                et vous contactera par email dans un délai de 5 à 10 jours ouvrables.
            </p>
            <button type="submit" class="btn-submit-final">
                <i class="fas fa-paper-plane"></i> Envoyer ma candidature
            </button>
        </div>

    </form>
</div>

<script>
// Preview noms de fichiers uploadés
function setupUpload(inputId, nameId, zoneId) {
    const input = document.getElementById(inputId);
    const name  = document.getElementById(nameId);
    const zone  = document.getElementById(zoneId);

    if (!input) return;

    input.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            name.textContent = this.files[0].name;
            name.style.display = 'block';
            zone.style.borderColor = 'var(--purple)';
            zone.style.background = 'var(--purple-light)';
        }
    });
}

setupUpload('fileCv',       'nameCv',       'zoneCv');
setupUpload('fileIdentite', 'nameIdentite', 'zoneIdentite');

// Validation dynamique du numéro selon le type de pièce
(function () {
    const typeSelect  = document.getElementById('typeIdentite');
    const numeroInput = document.getElementById('numeroIdentite');
    const hint        = document.getElementById('hintNumero');

    const configs = {
        passeport : { pattern: '[A-Za-z0-9]{6,9}', maxlength: '9',  placeholder: 'Ex: AB1234567',       hint: 'Lettres et chiffres – 6 à 9 caractères',  inputmode: 'text' },
        cni       : { pattern: '[0-9]{1,10}',       maxlength: '10', placeholder: 'Ex: 0123456789',      hint: 'Chiffres uniquement – max 10 caractères', inputmode: 'numeric' },
        cip       : { pattern: '[0-9]{1,10}',       maxlength: '10', placeholder: 'Ex: 0123456789',      hint: 'Chiffres uniquement – max 10 caractères', inputmode: 'numeric' },
        autre     : { pattern: '[A-Za-z0-9]{1,10}', maxlength: '10', placeholder: 'Numéro du document',  hint: 'Lettres ou chiffres – max 10 caractères', inputmode: 'text' },
    };

    function applyConfig() {
        const cfg = configs[typeSelect.value];
        if (!cfg) {
            numeroInput.removeAttribute('pattern');
            numeroInput.removeAttribute('maxlength');
            numeroInput.setAttribute('placeholder', 'Sélectionnez d\'abord le type de pièce');
            numeroInput.setAttribute('inputmode', 'text');
            hint.textContent = '';
            return;
        }
        numeroInput.setAttribute('pattern',     cfg.pattern);
        numeroInput.setAttribute('maxlength',   cfg.maxlength);
        numeroInput.setAttribute('placeholder', cfg.placeholder);
        numeroInput.setAttribute('inputmode',   cfg.inputmode);
        hint.textContent = cfg.hint;
        numeroInput.value = '';
    }

    typeSelect.addEventListener('change', applyConfig);

    // Majuscules automatiques pour le passeport
    numeroInput.addEventListener('input', function () {
        if (typeSelect.value === 'passeport') {
            const pos = this.selectionStart;
            this.value = this.value.toUpperCase();
            this.setSelectionRange(pos, pos);
        }
    });

    // Appliquer si old() a restauré une valeur au rechargement
    if (typeSelect.value) applyConfig();
})();
</script>

</body>
</html>