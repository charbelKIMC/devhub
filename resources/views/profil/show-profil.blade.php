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

    <!-- ─── PUBLICATIONS ─── -->
    <div>
        <div class="pub-section-title">
            Publications de {{ $utilisateur->prenom }}
            <span style="font-size:0.8rem;font-weight:400;color:#9CA3AF;margin-left:0.5rem;">
                {{ $utilisateur->publications()->where('statut','publié')->count() }} publication(s)
            </span>
        </div>

        @php
            $typeConfig = [
                'article'             => ['label'=>'Article',      'class'=>'pub-type-article',      'icon'=>'fa-newspaper'],
                'idee'                => ['label'=>'Idée',         'class'=>'pub-type-idee',         'icon'=>'fa-lightbulb'],
                'projet_personnel'    => ['label'=>'Projet perso', 'class'=>'pub-type-perso',        'icon'=>'fa-folder'],
                'projet_collaboratif' => ['label'=>'Collaboratif', 'class'=>'pub-type-collaboratif', 'icon'=>'fa-users'],
            ];
        @endphp

        @forelse($utilisateur->publications()->where('statut','publié')->orderBy('date_publication','desc')->get() as $pub)
            @php $tc = $typeConfig[$pub->type] ?? ['label'=>$pub->type,'class'=>'','icon'=>'fa-file']; @endphp
            <a href="{{ route('publications.public', $pub->id) }}" class="pub-card-public">
                <div class="pub-type-pill-sm {{ $tc['class'] }}">
                    <i class="fas {{ $tc['icon'] }}"></i> {{ $tc['label'] }}
                </div>
                <div class="pub-card-title">{{ $pub->titre }}</div>
                @if($pub->contenu)
                    <p class="pub-card-excerpt">{{ Str::limit(strip_tags($pub->contenu), 150) }}</p>
                @endif
                <div class="pub-card-footer">
                    <span><i class="fas fa-eye"></i> {{ $pub->nb_vues }}</span>
                    <span><i class="fas fa-heart"></i> {{ $pub->nb_likes }}</span>
                    <span><i class="fas fa-comment"></i> {{ $pub->nb_commentaires }}</span>
                    <span class="ms-auto">{{ $pub->date_publication->format('d M Y') }}</span>
                </div>
            </a>
        @empty
            <div class="empty-pubs">
                <i class="fas fa-folder-open"></i>
                <p>{{ $utilisateur->prenom }} n'a pas encore de publication publique.</p>
            </div>
        @endforelse
    </div>

</div>

<script src="{{ asset('js/welcome.js') }}"></script>
</body>
</html>