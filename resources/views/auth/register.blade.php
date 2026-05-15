<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription – DevHub</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/s-register.css') }}">
</head>
<body>
    <div class="background-image"></div>
    <div class="gradient-overlay"></div>
    <a href="{{ route('home') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
    <div class="main-container">
        <div class="left-section">
            <div class="cards-container">
                <div class="card card-1">
                    <h3>Communauté</h3>
                    <p>Rejoignez une communauté grandissante de développeurs béninois passionnés et innovateurs.</p>
                </div>
                <div class="card card-2">
                    <h3>Collaboration</h3>
                    <p>Formez des équipes, travaillez sur des projets réels et acquérez une expérience concrète.</p>
                </div>
                <div class="card card-3">
                    <h3>Mentorat</h3>
                    <p>Bénéficiez de l'accompagnement de développeurs expérimentés pour progresser rapidement.</p>
                </div>
            </div>
        </div>

        <div class="right-section">
            <div class="signup-form">

                <div class="titre">Inscription</div>

                @if($errors->any())
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="signupForm">
                    @csrf

                    <div class="form-row">
                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" placeholder="Jean" class="{{ $errors->has('prenom') ? 'input-error' : '' }}" required>
                            </div>
                            @error('prenom')<div class="error-text">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" id="nom" name="nom" value="{{ old('nom') }}" placeholder="AKAKPO" class="{{ $errors->has('nom') ? 'input-error' : '' }}" required>
                            </div>
                            @error('nom')<div class="error-text">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pseudo">Pseudo</label>
                        <div class="input-group">
                            <i class="fas fa-at"></i>
                            <input type="text" id="pseudo" name="pseudo" value="{{ old('pseudo') }}" placeholder="votre_pseudo" class="{{ $errors->has('pseudo') ? 'input-error' : '' }}" required>
                        </div>
                        @error('pseudo')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com" class="{{ $errors->has('email') ? 'input-error' : '' }}" required>
                        </div>
                        @error('email')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class=" form-group">
                        <label class="form-label-devhub">Numéro de téléphone</label>
                        <div class="input-group">
                            <i class="fas fa-phone"></i>
                            <input
                                type="tel"
                                name="telephone"
                                value="{{ old('telephone') }}"
                                class="form-control-devhub {{ $errors->has('telephone') ? 'input-error' : '' }}"
                                placeholder="+229 01 XX XX XX XX"
                                required
                            >
                        </div>
                        @error('telephone')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="mot_de_passe">Mot de passe</label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="••••••••" class="{{ $errors->has('mot_de_passe') ? 'input-error' : '' }}" required>
                        </div>
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <div class="password-strength" id="strengthText">Au moins 8 caractères</div>
                        @error('mot_de_passe')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="mot_de_passe_confirmation">Confirmer le mot de passe</label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="mot_de_passe_confirmation" name="mot_de_passe_confirmation" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="terms">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms" style="font-weight:400;">
                            J'accepte les <a href="#">conditions d'utilisation</a> et la <a href="#">politique de confidentialité</a>
                        </label>
                    </div>

                    <button type="submit" class="btn-signup">Créer mon compte</button>

                    <div class="login-link">
                        Vous avez déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const passwordInput = document.getElementById('mot_de_passe');
        const strengthFill  = document.getElementById('strengthFill');
        const strengthText  = document.getElementById('strengthText');

        passwordInput.addEventListener('input', function () {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;
            if (password.match(/[0-9]/)) strength += 25;
            if (password.match(/[^a-zA-Z0-9]/)) strength += 25;

            strengthFill.style.width = strength + '%';

            if (strength === 0)       { strengthText.textContent = 'Au moins 8 caractères'; strengthFill.style.background = '#D9D9D9'; }
            else if (strength <= 25)  { strengthText.textContent = 'Faible';    strengthFill.style.background = '#EF4444'; }
            else if (strength <= 50)  { strengthText.textContent = 'Moyen';     strengthFill.style.background = '#F59E0B'; }
            else if (strength <= 75)  { strengthText.textContent = 'Bon';       strengthFill.style.background = '#3B82F6'; }
            else                      { strengthText.textContent = 'Excellent'; strengthFill.style.background = '#7C3AED'; }
        });
    </script>
</body>
</html>