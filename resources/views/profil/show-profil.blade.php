<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $utilisateur->prenom }} {{ $utilisateur->nom }} – DevHub</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@300;400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/w-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profilshow.css') }}">
</head>
<body>

<!-- BANDEAU LECTURE SEULE -->
<div class="readonly-bar">
    <div>
        <i class="fas fa-eye me-2"></i>
        Vous consultez ce profil en <strong>mode lecture</strong> — connectez-vous pour envoyer un message ou collaborer.
    </div>
    <div style="display:flex;gap:0.5rem;">
        <a href="{{ route('home') }}"style="padding:0.4rem 1rem;border:1px solid #C4B5FD;border-radius:8px;font-size:0.82rem;color:#5B21B6;text-decoration:none;">
            <i class="fas fa-arrow-left"></i> Retour à l'accueil
        </a>
    </div>
</div>

<!-- COUVERTURE -->
<div class="profile-cover">
    <div class="profile-cover-overlay"></div>
</div>

<!-- ZONE AVATAR + NOM -->
<div class="profile-main-zone">
    <div class="profile-avatar-zone">
        <div class="profile-avatar-big">
            @if($utilisateur->photo)
                <img src="{{ $utilisateur->photo_url }}" alt="{{ $utilisateur->prenom }}">
            @else
                {{ strtoupper(substr($utilisateur->prenom, 0, 1)) }}
            @endif
        </div>
        <div class="profile-identity">
            <div class="profile-pub-name">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</div>
            <div class="profile-pub-pseudo">{{ '@' . $utilisateur->pseudo }}</div>
            <div class="profile-pub-spec">
                @if($utilisateur->role === 'mentor')
                    <i class="fas fa-star me-1"></i>Mentor DevHub ·
                @endif
                {{ $utilisateur->specialite ?? ucfirst($utilisateur->role) }}
            </div>
        </div>
    </div>
</div>

<!-- LAYOUT PRINCIPAL -->
<div class="profile-pub-layout">

    <!-- ─── SIDEBAR ─── -->
    <aside class="profile-pub-sidebar">

        <!-- Stats -->
        <div class="sidebar-widget">
            <div class="widget-title">Statistiques</div>
            <div class="stats-row">
                <div class="stat-box">
                    <div class="stat-val">{{ $utilisateur->publications()->where('statut','publié')->count() }}</div>
                    <div class="stat-lbl">Publications</div>
                </div>
                <div class="stat-box">
                    <div class="stat-val">{{ number_format($utilisateur->publications()->sum('nb_vues')) }}</div>
                    <div class="stat-lbl">Vues</div>
                </div>
                <div class="stat-box">
                    <div class="stat-val">{{ $utilisateur->publications()->sum('nb_likes') }}</div>
                    <div class="stat-lbl">Likes</div>
                </div>
                <div class="stat-box">
                    @php
                        $pubIds = $utilisateur->publications()->pluck('id');
                        $note   = \App\Models\Avis::whereIn('publication_id', $pubIds)->avg('note');
                    @endphp
                    <div class="stat-val">{{ $note ? number_format($note, 1) : '—' }}</div>
                    <div class="stat-lbl">Note moy.</div>
                </div>
            </div>
        </div>

        <!-- Bio -->
        @if($utilisateur->bio)
            <div class="sidebar-widget">
                <div class="widget-title">À propos</div>
                <p class="bio-text">{{ $utilisateur->bio }}</p>
            </div>
        @endif

        <!-- Infos -->
        <div class="sidebar-widget">
            <div class="widget-title">Informations</div>
            @if($utilisateur->localisation)
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $utilisateur->localisation }}
                </div>
            @endif
            <div class="info-item">
                <i class="fas fa-calendar-alt"></i>
                Membre depuis {{ $utilisateur->date_inscription->format('M Y') }}
            </div>
            @if($utilisateur->github)
                <div class="info-item">
                    <i class="fab fa-github"></i>
                    <a href="{{ $utilisateur->github }}" target="_blank">GitHub</a>
                </div>
            @endif
            @if($utilisateur->linkedin)
                <div class="info-item">
                    <i class="fab fa-linkedin"></i>
                    <a href="{{ $utilisateur->linkedin }}" target="_blank">LinkedIn</a>
                </div>
            @endif
            @if($utilisateur->portfolio)
                <div class="info-item">
                    <i class="fas fa-globe"></i>
                    <a href="{{ $utilisateur->portfolio }}" target="_blank">Portfolio</a>
                </div>
            @endif
        </div>

        <!-- Compétences -->
        @if($utilisateur->competences->isNotEmpty())
            <div class="sidebar-widget">
                <div class="widget-title">Compétences</div>
                <div class="skills-wrap">
                    @foreach($utilisateur->competences as $comp)
                        <span class="skill-pill">{{ $comp->nom }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- CTA -->
        <div class="sidebar-cta">
            <h4>Rejoignez DevHub</h4>
            <p>Créez un compte pour collaborer avec {{ $utilisateur->prenom }} et d'autres développeurs.</p>
            <a href="{{ route('register') }}" class="cta-w">
                <i class="fas fa-rocket me-1"></i>Créer un compte gratuit
            </a>
            <a href="{{ route('login') }}" class="cta-g">Déjà membre ? Se connecter</a>
        </div>

    </aside>

    <!-- ─── RÉCAP ACTIVITÉS ─── -->
    <div>
        <div class="pub-section-title">Activités de {{ $utilisateur->prenom }}</div>

        @php
            $nbArticles   = $utilisateur->publications()->where('statut','publié')->where('type','article')->count();
            $nbIdees      = $utilisateur->publications()->where('statut','publié')->where('type','idee')->count();
            $nbProjPerso  = $utilisateur->publications()->where('statut','publié')->where('type','projet_personnel')->count();
            $nbProjCollab = $utilisateur->publications()->where('statut','publié')->where('type','projet_collaboratif')->count();
            $nbCollabs    = \App\Models\MembreProjet::where('utilisateur_id', $utilisateur->id)->count();
            $totalVues    = $utilisateur->publications()->sum('nb_vues');
            $totalLikes   = $utilisateur->publications()->sum('nb_likes');
        @endphp

        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:0.85rem;margin-bottom:1.5rem;">
            @foreach([
                ['fa-newspaper','#EDE9FE','#7C3AED', $nbArticles,   'Articles'],
                ['fa-lightbulb','#FEF9C3','#854D0E', $nbIdees,      'Idées'],
                ['fa-folder',   '#DBEAFE','#1D4ED8', $nbProjPerso,  'Projets personnels'],
                ['fa-users',    '#D1FAE5','#065F46', $nbProjCollab, 'Projets collaboratifs'],
                ['fa-handshake','#FCE7F3','#BE185D', $nbCollabs,    'Participations'],
                ['fa-eye',      '#F3F4F6','#374151', $totalVues,    'Vues totales'],
                ['fa-heart',    '#FEE2E2','#DC2626', $totalLikes,   'Likes reçus'],
            ] as [$icon, $bg, $color, $val, $label])
                <div style="background:#fff;border:1px solid #F3F4F6;border-radius:12px;padding:1.1rem 1.25rem;display:flex;align-items:center;gap:0.85rem;">
                    <div style="width:38px;height:38px;border-radius:10px;background:{{ $bg }};color:{{ $color }};display:flex;align-items:center;justify-content:center;font-size:0.9rem;flex-shrink:0;">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <div>
                        <div style="font-family:'Urbanist',sans-serif;font-weight:800;font-size:1.3rem;color:#111827;line-height:1;">{{ number_format($val) }}</div>
                        <div style="font-size:0.73rem;color:#9CA3AF;margin-top:0.15rem;font-weight:500;">{{ $label }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="background:#F9FAFB;border:1px solid #F3F4F6;border-radius:12px;padding:1.25rem;text-align:center;">
            <p style="font-size:0.85rem;color:#9CA3AF;margin:0;">
                Pour accéder aux publications de {{ $utilisateur->prenom }}, <a href="{{ route('register') }}" style="color:#7C3AED;font-weight:600;text-decoration:none;">créez un compte</a> ou <a href="{{ route('login') }}" style="color:#7C3AED;font-weight:600;text-decoration:none;">connectez-vous</a>.
            </p>
        </div>
    </div>

</div>

<script src="{{ asset('js/welcome.js') }}"></script>
</body>
</html>