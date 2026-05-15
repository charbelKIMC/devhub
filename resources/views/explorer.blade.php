<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorer publication – DevHub</title>
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
                    <a href="{{ route('register') }}"    class="btn-custom">
                        <i class="fas fa-user-plus"></i> Rejoindre la communauté
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- HERO BANDEAU -->
    <div class="explorer-container">
        <div class="explorer-header">
            <h2 style="font-family:'Urbanist',sans-serif;font-size:1.3rem;font-weight:600;">Explorer les publications</h2>
            <div class="explorer-filters">
                <a href="{{ route('explorer') }}" class="filter-pill {{ !request('type') ? 'active' : '' }}">Tout</a>
                <a href="{{ route('explorer') }}?type=article" class="filter-pill {{ request('type')==='article' ? 'active' : '' }}">Articles</a>
                <a href="{{ route('explorer') }}?type=idee" class="filter-pill {{ request('type')==='idee' ? 'active' : '' }}">Idées</a>
                <a href="{{ route('explorer') }}?type=projet_personnel" class="filter-pill {{ request('type')==='projet_personnel' ? 'active' : '' }}">Projets</a>
                <a href="{{ route('explorer') }}?type=projet_collaboratif" class="filter-pill {{ request('type')==='projet_collaboratif' ? 'active' : '' }}">Collaboratifs</a>
            </div>
        </div>

        <div class="explorer-grid">
            @forelse($publications as $pub)
                @php
                    $tc = [
                        'article'=>['label'=>'Article','class'=>'pub-type-article','icon'=>'fa-newspaper'],
                        'idee'=>['label'=>'Idée','class'=>'pub-type-idee','icon'=>'fa-lightbulb'],
                        'projet_personnel'=>['label'=>'Projet','class'=>'pub-type-perso','icon'=>'fa-folder'],
                        'projet_collaboratif'=>['label'=>'Collaboratif','class'=>'pub-type-collaboratif','icon'=>'fa-users'],
                    ][$pub->type] ?? ['label'=>$pub->type,'class'=>'','icon'=>'fa-file'];
                @endphp
                <a href="{{ route('publications.public', $pub->id) }}" class="pub-recente-card">
                    <div class="pub-recente-type {{ $tc['class'] }}">
                        <i class="fas {{ $tc['icon'] }}"></i> {{ $tc['label'] }}
                    </div>
                    <div class="pub-recente-title">{{ $pub->titre }}</div>
                    @if($pub->contenu)
                        <p class="pub-recente-desc">{{ Str::limit(strip_tags($pub->contenu), 120) }}</p>
                    @endif
                    <div class="pub-recente-footer">
                        <div class="pub-recente-avatar">{{ strtoupper(substr($pub->auteur->prenom, 0, 1)) }}</div>
                        <span>{{ $pub->auteur->prenom }} {{ $pub->auteur->nom }}</span>
                        <span class="ms-auto"><i class="fas fa-eye me-1"></i>{{ $pub->nb_vues }}</span>
                    </div>
                </a>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:4rem;color:#999;">
                    <i class="fas fa-search" style="font-size:3rem;margin-bottom:1rem;display:block;color:#ddd;"></i>
                    <p>Aucune publication pour le moment.</p>
                </div>
            @endforelse
        </div>

        @if($publications->hasPages())
            <div style="display:flex;justify-content:center;">{{ $publications->links() }}</div>
        @endif
    </div>

</body>
</html>