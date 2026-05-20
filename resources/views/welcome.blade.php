<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevHub – Valorise tes compétences. Connecte-toi. Collabore.</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@300;400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/w-styles.css') }}">
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
                <a href="#section-features" class="nav-link">Nos services</a>
                <a href="#section-stats" class="nav-link">Statistiques</a>
            </div>

            <div class="header-right">
                <div class="header-buttons">
                    <a href="{{ route('mentor.externe') }}" class="btn-outline-custom">
                        <i class="fas fa-paper-plane"></i> Postuler comme mentor
                    </a>
                    <a href="{{ route('login') }}"    class="btn-custom">Se connecter</a>
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
            <div class="mobile-section-title">Autres</div>
            <a href="#section-features" class="mobile-link"><i class="fas fa-info-circle me-2"></i>Nos services</a>
            <a href="#section-stats" class="mobile-link"><i class="fas fa-chart-bar me-2"></i>Statistiques</a>
        </div>
        <div class="mobile-buttons">
            <a href="{{ route('mentor.externe') }}" class="btn-outline-custom w-100 text-center mb-2">
                <i class="fas fa-paper-plane me-2"></i>Postuler comme mentor
            </a>
            <a href="{{ route('login') }}"    class="btn-custom w-100 text-center">Se connecter</a>
        </div>
    </div>
</div>

<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <div class="hero-title">
                    Vos compétences méritent d'être <span class="highlight">vues</span>
                </div>
                <p class="hero-subtitle">
                    Rejoignez la communauté où chaque ligne de code compte.
                    Partagez vos projets, collaborez avec des talents et construisez votre portfolio professionnel.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn-custom me-2">
                        <i class="fas fa-rocket me-2"></i>Rejoindre gratuitement
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="problem-section">
    <div class="conteneur">
        <div class="row align-items-center">
            <div class="col-lg-6 problem-left">
                <div class="problem-content">
                    <div class="section-title">Pourquoi DevHub existe</div>
                    <div class="problem-item mt-5">
                        <div class="problem-number">01</div>
                        <div class="problem-text">
                            <h3>Difficile de montrer ses compétences ?</h3>
                            <p>Un portfolio dispersé ne rend pas justice à votre talent. Centralisez toutes vos réalisations en un seul endroit professionnel.</p>
                        </div>
                    </div>
                    <div class="problem-item">
                        <div class="problem-number">02</div>
                        <div class="problem-text">
                            <h3>Manque de visibilité ?</h3>
                            <p>Vos projets méritent d'être découverts par la bonne audience : recruteurs, collaborateurs et partenaires potentiels.</p>
                        </div>
                    </div>
                    <div class="problem-item">
                        <div class="problem-number">03</div>
                        <div class="problem-text">
                            <h3>Peu d'espaces pour collaborer ?</h3>
                            <p>Les meilleures idées naissent de la collaboration. Trouvez des co-créateurs qui partagent votre vision.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 problem-right">
                <div class="solution-box">
                    <div class="solution-icon"><i class="fas fa-rocket"></i></div>
                    <h3>DevHub résout tout ça</h3>
                    <p>Une plateforme pensée pour valoriser votre travail, connecter les talents et transformer vos compétences en opportunités concrètes.</p>
                    <a href="{{ route('register') }}" class="btn-custom mt-4">Découvrir comment</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="talents-section" id="section-stats">
    <div class="conteneur">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="section-title">Un Hub pensé pour révéler les talents</div>
                <p class="talents-description mt-3">Rejoignez une communauté de développeurs béninois passionnés qui partagent leurs projets, collaborent sur des idées innovantes et construisent leur présence dans l'écosystème tech.</p>
                <div class="talents-stats">
                    @php
                        $totalDevs   = \App\Models\Utilisateur::where('statut','actif')->count();
                        $totalPubs   = \App\Models\Publication::where('statut','publié')->count();
                        $totalCollab = \App\Models\MembreProjet::count();
                    @endphp
                    <div class="stat-item">
                        <div class="stat-number">{{ $totalDevs > 0 ? '+'.$totalDevs : '0' }}</div>
                        <div class="stat-label">Talents inscrits</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $totalPubs > 0 ? '+'.$totalPubs : '0' }}</div>
                        <div class="stat-label">Projets publiés</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $totalCollab > 0 ? '+'.$totalCollab : '0' }}</div>
                        <div class="stat-label">Collaborations</div>
                    </div>
                </div>
                <a href="{{ route('developpeurs') }}" class="btn-custom mt-4 text-center">Découvrir les talents</a>
            </div>
            <div class="col-lg-6">
                <div class="talents-circle-container">
                    <div class="talent-card" data-position="1"><div class="profile-avatar"><i class="fas fa-user"></i></div><div class="profile-info"><h4>Développeur Web</h4><p>React & Node.js</p></div></div>
                    <div class="talent-card" data-position="2"><div class="profile-avatar"><i class="fas fa-user"></i></div><div class="profile-info"><h4>Mobile Dev</h4><p>Flutter & Dart</p></div></div>
                    <div class="talent-card" data-position="3"><div class="profile-avatar"><i class="fas fa-user"></i></div><div class="profile-info"><h4>Backend Expert</h4><p>Laravel & PHP</p></div></div>
                    <div class="talent-card" data-position="4"><div class="profile-avatar"><i class="fas fa-user"></i></div><div class="profile-info"><h4>UI/UX Designer</h4><p>Figma & Design</p></div></div>
                    <div class="talent-card" data-position="5"><div class="profile-avatar"><i class="fas fa-user"></i></div><div class="profile-info"><h4>Full-Stack Dev</h4><p>MERN Stack</p></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="devs-section" id="section-devs">
    <div class="conteneur">
        <div class="pub-recentes-banner mb-4">
            <div class="pub-recente-left">
                <div class="section-title">Développeurs à découvrir</div>
                <p class="section-subtitle mt-2">Rejoignez une communauté de talents passionnés</p>
            </div>
            <div class="pub-recente-right">
                <a href="{{ route('developpeurs') }}" class="btn-outline-custom me-3">
                    <i class="fas fa-users me-2"></i>Voir tous les développeurs
                </a>
            </div>
        </div>

        <div class="devs-grid">
            @php
                $devs = \App\Models\Utilisateur::where('statut','actif')
                    ->whereIn('role',['développeur'])
                    ->latest('date_inscription')
                    ->take(6)->get();
            @endphp

            @forelse($devs as $dev)
                <a href="{{ route('profil.public', $dev->id) }}" class="dev-card">

                    {{-- Image ou placeholder --}}
                    @if($dev->photo)
                        <img src="{{ $dev->photo_url }}" alt="{{ $dev->prenom }}" class="dev-card-bg">
                    @else
                        <div class="dev-card-placeholder">
                            {{ strtoupper(substr($dev->prenom, 0, 1)) }}
                        </div>
                    @endif

                    {{-- Badge mentor --}}
                    @if($dev->role === 'mentor')
                        <div class="dev-card-badge">
                            <i class="fas fa-star me-1"></i>Mentor
                        </div>
                    @endif

                    {{-- Compétences visibles au hover --}}
                    @if($dev->competences->isNotEmpty())
                        <div class="dev-card-skills">
                            @foreach($dev->competences->take(3) as $comp)
                                <span class="dev-skill-pill">{{ $comp->nom }}</span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Dégradé --}}
                    <div class="dev-card-gradient"></div>

                    {{-- Infos --}}
                    <div class="dev-card-info">
                        <div class="dev-card-name">{{ $dev->prenom }} {{ $dev->nom }}</div>
                        <div class="dev-card-spec">{{ $dev->specialite ?? ucfirst($dev->role) }}</div>
                        <div class="dev-card-stats">
                            <span><i class="fas fa-folder"></i> {{ $dev->publications()->where('statut','publié')->count() }}</span>
                            <span><i class="fas fa-eye"></i> {{ number_format($dev->publications()->sum('nb_vues')) }}</span>
                            <span><i class="fas fa-heart"></i> {{ $dev->publications()->sum('nb_likes') }}</span>
                        </div>
                    </div>

                </a>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:3rem;color:#999;">
                    <p>La communauté grandit. Soyez parmi les premiers !</p>
                </div>
            @endforelse
        </div>
    </div>
</section>


<section class="features-section" id="section-features">
    <div class="conteneur">
        <div class="section-title text-center mb-2">Comment se déroulent nos services</div>
        <div class="stack-wrapper">
            <div class="stack-viewport">
                <div class="stack-card" data-index="0"><div class="card-inner"><div class="icon-square"><i class="fas fa-user-plus"></i></div><div class="card-text"><div class="card-num">01</div><h3 class="card-heading">Créez votre profil de talents</h3><p class="card-desc">Présentez vos compétences, vos technologies maîtrisées et votre parcours. Un profil complet qui met en valeur votre expertise.</p></div></div></div>
                <div class="stack-card" data-index="1"><div class="card-inner"><div class="icon-square"><i class="fas fa-folder-plus"></i></div><div class="card-text"><div class="card-num">02</div><h3 class="card-heading">Publiez vos projets et idées</h3><p class="card-desc">Partagez vos réalisations avec des liens vers vos repositories et descriptions détaillées.</p></div></div></div>
                <div class="stack-card" data-index="2"><div class="card-inner"><div class="icon-square"><i class="fas fa-users"></i></div><div class="card-text"><div class="card-num">03</div><h3 class="card-heading">Collaborez en équipe</h3><p class="card-desc">Trouvez des partenaires pour vos projets, formez des équipes et travaillez ensemble sur des initiatives innovantes.</p></div></div></div>
                <div class="stack-card" data-index="3"><div class="card-inner"><div class="icon-square"><i class="fas fa-comments"></i></div><div class="card-text"><div class="card-num">04</div><h3 class="card-heading">Recevez des avis constructifs</h3><p class="card-desc">Obtenez des retours de la communauté, des pairs et des professionnels pour progresser.</p></div></div></div>
                <div class="stack-card" data-index="4"><div class="card-inner"><div class="icon-square"><i class="fas fa-chalkboard-teacher"></i></div><div class="card-text"><div class="card-num">05</div><h3 class="card-heading">Bénéficiez du mentorat</h3><p class="card-desc">Faites-vous accompagner par des développeurs expérimentés pour accélérer votre progression.</p></div></div></div>
                <div class="stack-card" data-index="5"><div class="card-inner"><div class="icon-square"><i class="fas fa-chart-line"></i></div><div class="card-text"><div class="card-num">06</div><h3 class="card-heading">Gagnez en visibilité</h3><p class="card-desc">Soyez découvert par des recruteurs et des entreprises. Votre profil devient votre vitrine professionnelle.</p></div></div></div>
            </div>
            <div class="side-gradient gradient-left"></div>
            <div class="side-gradient gradient-right"></div>
            <button class="stack-arrow arrow-left"  id="stackPrev"><i class="fas fa-chevron-left"></i></button>
            <button class="stack-arrow arrow-right" id="stackNext"><i class="fas fa-chevron-right"></i></button>
        </div>
        <div class="stack-dots" id="stackDots"></div>
        <div class="cta-wrap">
            <a href="{{ route('register') }}" class="btn-custom">Commencer maintenant</a>
        </div>
    </div>
</section>

<section class="categories-section">
    <div class="conteneur">
        <div class="text-center ">
            <div class="section-title text-white">Trouvez des talents par catégorie</div>
            <p class="section-subtitle text-white">Explorez notre communauté diverse de développeurs et créateurs</p>
        </div>
        <div class="categories-grid-dark">
            <div class="category-column">
                <ul class="category-list">
                    <li><a href="{{ route('register') }}">Design de sites internet</a></li>
                    <li><a href="{{ route('register') }}">Application Android</a></li>
                    <li><a href="{{ route('register') }}">Application iPhone</a></li>
                    <li><a href="{{ route('register') }}">Sites de logiciels</a></li>
                    <li><a href="{{ route('register') }}">Design graphique</a></li>
                    <li><a href="{{ route('register') }}">Design de logo</a></li>
                </ul>
            </div>
            <div class="category-column">
                <ul class="category-list">
                    <li><a href="{{ route('register') }}">HTML / CSS</a></li>
                    <li><a href="{{ route('register') }}">PHP & Laravel</a></li>
                    <li><a href="{{ route('register') }}">JavaScript & React</a></li>
                    <li><a href="{{ route('register') }}">Python & Django</a></li>
                    <li><a href="{{ route('register') }}">Java & Spring</a></li>
                    <li><a href="{{ route('register') }}">DevOps & Cloud</a></li>
                </ul>
            </div>
            <div class="category-column">
                <ul class="category-list">
                    <li><a href="{{ route('register') }}">Intelligence Artificielle</a></li>
                    <li><a href="{{ route('register') }}">Machine Learning</a></li>
                    <li><a href="{{ route('register') }}">Cybersécurité</a></li>
                    <li><a href="{{ route('register') }}">Marketing digital</a></li>
                    <li><a href="{{ route('register') }}">SEO & Référencement</a></li>
                    <li><a href="{{ route('register') }}">Rédaction créative</a></li>
                </ul>
            </div>
            <div class="category-column">
                <ul class="category-list">
                    <li><a href="{{ route('register') }}">Illustration numérique</a></li>
                    <li><a href="{{ route('register') }}">Bases de données</a></li>
                    <li><a href="{{ route('register') }}">Modélisation 3D</a></li>
                    <li><a href="{{ route('register') }}">Blockchain</a></li>
                    <li><a href="{{ route('register') }}">eCommerce</a></li>
                    <li class="voir-plus"><a href="{{ route('register') }}">Voir plus →</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="conteneur text-center">
        <div class="cta-title">Rejoignez une communauté où les compétences comptent vraiment</div>
        <p class="cta-subtitle">Des développeurs béninois nous font déjà confiance pour valoriser leur talent</p>
        <div class="cta-buttons">
            <a href="{{ route('register') }}" class="btn-custom me-2">
                <i class="fas fa-user-plus me-2"></i>Créer un compte
            </a>
        </div>
    </div>
</section>

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
</body>
</html>