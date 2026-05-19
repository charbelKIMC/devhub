<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') – DevHub</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@300;400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/w-styles.css') }}">
    @yield('styles')
</head>
<body>

<header class="header">
    <div class="conteneur">
        <nav class="navbar-welcome">
            <a href="{{ route('home') }}" class="name-page text-black">
                <span><img src="{{ asset('img/logo-devhub.png') }}" alt="Logo de DevHub" class="logo-icon"></span>
                DevHub
            </a>
            <div class="nav-center" id="navCenter">
                <div class="nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">Communauté <i class="fas fa-chevron-down nav-arrow"></i></a>
                    <div class="nav-dropdown-menu">
                        <a href="{{ route('developpeurs') }}"><i class="fas fa-code me-2"></i>Développeurs</a>
                        <a href="{{ route('developpeurs') }}?role=mentor"><i class="fas fa-chalkboard-teacher me-2"></i>Mentors</a>
                    </div>
                </div>
                <a href="{{ route('home') }}#section-features" class="nav-link">Nos services</a>
                <a href="{{ route('home') }}#section-stats" class="nav-link">Statistiques</a>
            </div>
            <div class="header-right">
                <div class="header-buttons">
                    <a href="{{ route('mentor.externe') }}" class="btn-outline-custom">
                        <i class="fas fa-paper-plane"></i> Postuler comme mentor
                    </a>
                    <a href="{{ route('login') }}" class="btn-custom">Se connecter</a>
                </div>
                <button class="hamburger" id="hamburger" aria-label="Menu">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
            </div>
        </nav>
    </div>
</header>

<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-inner">
        <div class="mobile-section">
            <div class="mobile-section-title">Communauté</div>
            <a href="{{ route('developpeurs') }}" class="mobile-link"><i class="fas fa-code me-2"></i>Développeurs</a>
            <a href="{{ route('mentor.externe') }}" class="mobile-link"><i class="fas fa-star me-2"></i>Devenir mentor</a>
        </div>
        <div class="mobile-section">
            <div class="mobile-section-title">Légal</div>
            <a href="{{ route('contact') }}" class="mobile-link"><i class="fas fa-envelope me-2"></i>Contact</a>
            <a href="{{ route('mentions-legales') }}" class="mobile-link"><i class="fas fa-file-alt me-2"></i>Mentions légales</a>
        </div>
        <div class="mobile-buttons">
            <a href="{{ route('mentor.externe') }}" class="btn-outline-custom w-100 text-center mb-2">
                <i class="fas fa-paper-plane me-2"></i>Postuler comme mentor
            </a>
            <a href="{{ route('login') }}" class="btn-custom w-100 text-center">Se connecter</a>
        </div>
    </div>
</div>

@yield('content')

<footer class="footer">
    <div class="conteneur">

        {{-- Newsletter --}}
        <div class="footer-newsletter">
            <div class="footer-newsletter-text">
                <div class="footer-newsletter-title">Restez informé</div>
                <p class="footer-newsletter-sub">Recevez les actualités et projets phares de la communauté DevHub.</p>
            </div>
            @if(session('newsletter_success'))
                <div class="footer-newsletter-ok">
                    <i class="fas fa-check-circle me-2"></i>{{ session('newsletter_success') }}
                </div>
            @else
                <form class="footer-newsletter-form" action="{{ route('newsletter.abonner') }}" method="POST">
                    @csrf
                    <input type="email" name="email" class="footer-newsletter-input" placeholder="votre@email.com" required>
                    <button type="submit" class="footer-newsletter-btn">
                        <i class="fas fa-paper-plane me-1"></i> S'abonner
                    </button>
                </form>
                @error('email')<div class="footer-newsletter-err">{{ $message }}</div>@enderror
            @endif
        </div>
        <div class="footer-newsletter-divider"></div>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="footer-brand">
                    <a href="{{ route('home') }}" class="name-page text-white mb-3">
                        <span><img src="{{ asset('img/logo-devhub.png') }}" alt="Logo de DevHub" class="logo-icon bg-white"></span>
                        DevHub
                    </a>
                    <p class="footer-tagline">Valoriser les talents. Connecter les idées.</p>
                    <p class="footer-description">La plateforme collaborative dédiée à la valorisation et au réseau des développeurs.</p>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 mb-4">
                <h4 class="footer-title">Navigation</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Accueil</a></li>
                    <li><a href="{{ route('developpeurs') }}">Développeurs</a></li>
                    <li><a href="{{ route('mentor.externe') }}">Devenir mentor</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-4 mb-4">
                <h4 class="footer-title">Communauté</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('register') }}">Rejoindre</a></li>
                    <li><a href="{{ route('register') }}">Publier un projet</a></li>
                    <li><a href="{{ route('mentor.externe') }}">Programme mentor</a></li>
                    <li><a href="{{ route('login') }}">Se connecter</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-4 mb-4">
                <h4 class="footer-title">Légal</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('mentions-legales') }}">Mentions légales</a></li>
                    <li><a href="{{ route('politique-confidentialite') }}">Politique de confidentialité</a></li>
                    <li><a href="{{ route('conditions-utilisation') }}">Conditions d'utilisation</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025–2026 DevHub – Plateforme collaborative de développeurs</p>
        </div>
    </div>
</footer>

<script src="{{ asset('js/welcome.js') }}"></script>
@yield('scripts')
</body>
</html>
