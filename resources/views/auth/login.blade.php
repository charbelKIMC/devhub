<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion – DevHub</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/s-login.css') }}">
</head>
<body>
    <div class="background-image"></div>
    <div class="gradient-overlay"></div>
    <a href="{{ route('home') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
    <div class="main-container">
        <div class="left-section">
            <div class="login-form">

                <div class="titre">Connexion</div>

                @if($errors->any())
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                    </div>
                @endif

                @if(session('success'))
                    <div style="background:#D1FAE5;color:#065F46;border:1px solid #A7F3D0;border-radius:8px;padding:10px 14px;font-size:0.88rem;margin-bottom:1rem;">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="votre@email.com"
                                class="{{ $errors->has('email') ? 'input-error' : '' }}"
                                required
                            >
                        </div>
                        @error('email')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="mot_de_passe">Mot de passe</label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input
                                type="password"
                                id="mot_de_passe"
                                name="mot_de_passe"
                                placeholder="••••••••"
                                class="{{ $errors->has('mot_de_passe') ? 'input-error' : '' }}"
                                required
                            >
                        </div>
                        @error('mot_de_passe')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            Se souvenir de moi
                        </label>
                        <a href="{{ route('password.request') }}" class="forgot-password">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    <button type="submit" class="btn-login">Se connecter</button>

                    <div class="signup-link">
                        Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="right-section">
            <div class="cards-container">
                <div class="card card-1">
                    <h3>Rapide</h3>
                    <p>Accédez à votre compte en quelques secondes avec une interface optimisée pour la performance.</p>
                </div>
                <div class="card card-2">
                    <h3>Sécurisé</h3>
                    <p>Vos données sont protégées avec les dernières technologies de cryptage et d'authentification.</p>
                </div>
                <div class="card card-3">
                    <h3>Collaboratif</h3>
                    <p>Rejoignez une communauté de développeurs béninois passionnés et progressez ensemble.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>