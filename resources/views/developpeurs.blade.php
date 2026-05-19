<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communauté – DevHub</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@300;400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/w-styles.css') }}">
</head>
<body>

    <!-- HEADER -->
    <header class="header">
        <div class="conteneur">
            <div class="navbar-welcome">
                <div class="header-left">
                    <a href="{{ route('home') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i> Retour à l'accueil
                    </a>
                </div>
                <div class="header-auth">
                    <a href="{{ route('mentor.externe') }}" class="btn-outline-custom me-2">
                        <i class="fas fa-paper-plane"></i> Postuler comme mentor
                    </a>
                    <a href="{{ route('register') }}" class="btn-custom">
                        <i class="fas fa-user-plus"></i> Rejoindre la communauté
                    </a>
                </div>
            </div>
        </div>
    </header>

<div style="max-width:1200px;margin:2rem auto;padding:0 1.5rem;">

    {{-- ========== SECTION DÉVELOPPEURS ========== --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:.5rem;">
        <div>
            <div style="font-size:1.35rem;font-weight:800;color:#111827;">Développeurs</div>
            <div style="font-size:0.82rem;color:#9CA3AF;margin-top:2px;">
                <i class="fas fa-code me-1"></i>{{ $developpeurs->total() }} membre(s)
            </div>
        </div>
    </div>

    <div class="devs-grid">
        @forelse($developpeurs as $dev)
            <a href="{{ route('profil.public', $dev->id) }}" class="dev-card">

                @if($dev->photo)
                    <img src="{{ $dev->photo_url }}" alt="{{ $dev->prenom }}" class="dev-card-bg">
                @else
                    <div class="dev-card-placeholder">
                        {{ strtoupper(substr($dev->prenom, 0, 1)) }}
                    </div>
                @endif

                @if($dev->competences->isNotEmpty())
                    <div class="dev-card-skills">
                        @foreach($dev->competences->take(3) as $comp)
                            <span class="dev-skill-pill">{{ $comp->nom }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="dev-card-gradient"></div>

                <div class="dev-card-info">
                    <div class="dev-card-name">{{ $dev->prenom }} {{ $dev->nom }}</div>
                    <div class="dev-card-spec">{{ $dev->specialite ?? 'Développeur' }}</div>
                    <div class="dev-card-stats">
                        <span><i class="fas fa-folder"></i> {{ $dev->publications()->where('statut','publié')->count() }}</span>
                        <span><i class="fas fa-eye"></i> {{ number_format($dev->publications()->sum('nb_vues')) }}</span>
                        <span><i class="fas fa-heart"></i> {{ $dev->publications()->sum('nb_likes') }}</span>
                    </div>
                </div>

            </a>
        @empty
            <div style="grid-column:1/-1;text-align:center;padding:4rem;background:white;border-radius:16px;border:1px solid #E5E7EB;">
                <i class="fas fa-code" style="font-size:2.5rem;color:#E5E7EB;display:block;margin-bottom:1rem;"></i>
                <p style="color:#9CA3AF;">Aucun développeur pour le moment.</p>
                <a href="{{ route('register') }}" class="btn-custom mt-3" style="display:inline-block;">Soyez le premier !</a>
            </div>
        @endforelse
    </div>

    @if($developpeurs->hasPages())
        <div style="display:flex;justify-content:center;margin-top:2rem;">
            {{ $developpeurs->links() }}
        </div>
    @endif

    {{-- ========== SECTION MENTORS ========== --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin:3rem 0 1.25rem;flex-wrap:wrap;gap:.5rem;">
        <div>
            <div style="font-size:1.35rem;font-weight:800;color:#111827;">Mentors</div>
            <div style="font-size:0.82rem;color:#9CA3AF;margin-top:2px;">
                <i class="fas fa-star me-1"></i>{{ $mentors->count() }} mentor(s) actif(s)
            </div>
        </div>
    </div>

    <div class="devs-grid">
        @forelse($mentors as $mentor)
            <a href="{{ route('profil.public', $mentor->id) }}" class="dev-card">

                @if($mentor->photo)
                    <img src="{{ $mentor->photo_url }}" alt="{{ $mentor->prenom }}" class="dev-card-bg">
                @else
                    <div class="dev-card-placeholder">
                        {{ strtoupper(substr($mentor->prenom, 0, 1)) }}
                    </div>
                @endif

                <div class="dev-card-gradient"></div>

                <div class="dev-card-info">
                    <div class="dev-card-name">{{ $mentor->prenom }} {{ $mentor->nom }}</div>
                    <div class="dev-card-spec">{{ $mentor->specialite ?? 'Mentor' }}</div>
                </div>

            </a>
        @empty
            <div style="grid-column:1/-1;text-align:center;padding:4rem;background:white;border-radius:16px;border:1px solid #E5E7EB;">
                <i class="fas fa-star" style="font-size:2.5rem;color:#E5E7EB;display:block;margin-bottom:1rem;"></i>
                <p style="color:#9CA3AF;">Aucun mentor disponible pour le moment.</p>
                <a href="{{ route('mentor.externe') }}" class="btn-custom mt-3" style="display:inline-block;">Postuler comme mentor</a>
            </div>
        @endforelse
    </div>

</div>

<script src="{{ asset('js/welcome.js') }}"></script>
</body>
</html>
