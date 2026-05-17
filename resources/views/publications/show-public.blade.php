<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publication detaillée – DevHub</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@300;400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/s-publicshow.css') }}">
</head>
<body>

    <!-- BANNIÈRE LECTURE SEULE -->
    <div class="readonly-banner conteneur">
        <div class="readonly-banner-left">
            <i class="fas fa-eye"></i>
            <span>Vous êtes en <strong>mode lecture</strong> — connectez-vous pour liker, commenter, collaborer et accéder à toutes les fonctionnalités.</span>
        </div>
        <div class="readonly-banner-right">
            <a href="{{ route('home') }}"style="padding:0.4rem 1rem;border:1px solid #C4B5FD;border-radius:8px;font-size:0.82rem;color:#5B21B6;text-decoration:none;">
                <i class="fas fa-arrow-left"></i> Retour à l'accueil
            </a>
        </div>
    </div>

    <!-- LAYOUT PRINCIPAL -->
    <div class="pub-layout">

        <!-- ─── COLONNE PRINCIPALE ─── -->
        <div>
            <div class="pub-content-card">

                <!-- En-tête publication -->
                <div class="pub-content-header">
                    @php
                        $typeConfig = [
                            'article'             => ['label'=>'Article',       'class'=>'pub-type-article',      'icon'=>'fa-newspaper'],
                            'idee'                => ['label'=>'Idée',          'class'=>'pub-type-idee',         'icon'=>'fa-lightbulb'],
                            'projet_personnel'    => ['label'=>'Projet perso',  'class'=>'pub-type-perso',        'icon'=>'fa-folder'],
                            'projet_collaboratif' => ['label'=>'Collaboratif',  'class'=>'pub-type-collaboratif', 'icon'=>'fa-users'],
                        ];
                        $tc = $typeConfig[$publication->type] ?? ['label'=>$publication->type,'class'=>'','icon'=>'fa-file'];
                    @endphp

                    <div class="pub-type-pill {{ $tc['class'] }}">
                        <i class="fas {{ $tc['icon'] }}"></i> {{ $tc['label'] }}
                    </div>

                    <h1 class="pub-main-title">{{ $publication->titre }}</h1>

                    <div class="pub-author-row">
                        <div class="pub-author-avatar">
                            @if($publication->auteur->photo)
                                <img src="{{ $publication->auteur->photo_url }}" alt="">
                            @else
                                {{ strtoupper(substr($publication->auteur->prenom, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="pub-author-name">
                                {{ $publication->auteur->prenom }} {{ $publication->auteur->nom }}
                            </div>
                            <div class="pub-author-meta">
                                {{ $publication->auteur->specialite ?? ucfirst($publication->auteur->role) }}
                                · {{ $publication->date_publication->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Corps de la publication -->
                @if($publication->contenu)
                    <div class="pub-content-body">{{ $publication->contenu }}</div>
                @endif

                <!-- Liens projet -->
                @if($publication->projetPersonnel || $publication->projetCollaboratif)
                    @php $projet = $publication->projetPersonnel ?? $publication->projetCollaboratif; @endphp
                    @if(($projet->url_repo ?? null) || ($projet->url_demo ?? null))
                        <div class="pub-links-row">
                            @if($projet->url_repo)
                                <a href="{{ $projet->url_repo }}" target="_blank" class="pub-link-btn">
                                    <i class="fab fa-github"></i> GitHub
                                </a>
                            @endif
                            @if(isset($projet->url_demo) && $projet->url_demo)
                                <a href="{{ $projet->url_demo }}" target="_blank" class="pub-link-btn">
                                    <i class="fas fa-external-link-alt"></i> Démo en ligne
                                </a>
                            @endif
                        </div>
                    @endif
                @endif

                <!-- Stats -->
                <div class="pub-stats-bar">
                    <div class="pub-stat">
                        <i class="fas fa-eye"></i>
                        {{ $publication->nb_vues }} vues
                    </div>
                    <div class="pub-stat">
                        <i class="fas fa-heart"></i>
                        {{ $publication->nb_likes }} likes
                    </div>
                    <div class="pub-stat">
                        <i class="fas fa-comment"></i>
                        {{ $publication->nb_commentaires }} commentaires
                    </div>
                    @if($publication->avis->count() > 0)
                        <div class="pub-stat">
                            <i class="fas fa-star" style="color:#F59E0B;"></i>
                            {{ number_format($publication->avis->avg('note'), 1) }}/5
                        </div>
                    @endif
                </div>

            </div>

            <!-- ─── COMMENTAIRES (lecture seule) ─── -->
            <div class="comments-card">
                <div class="comments-title">
                    <i class="fas fa-comments" style="color:#7C3AED;"></i>
                    Commentaires
                    <span class="comment-count-badge">{{ $publication->commentaires->count() }}</span>
                </div>

                @forelse($publication->commentaires->whereNull('id_parent') as $comment)
                    <div class="comment-item">
                        <div class="comment-avatar">
                            {{ strtoupper(substr($comment->utilisateur->prenom, 0, 1)) }}
                        </div>
                        <div style="flex:1;">
                            <div>
                                <span class="comment-author">
                                    {{ $comment->utilisateur->prenom }} {{ $comment->utilisateur->nom }}
                                </span>
                                <span class="comment-date">
                                    {{ $comment->date_commentaire->diffForHumans() }}
                                </span>
                            </div>
                            <div class="comment-text">{{ $comment->contenu }}</div>

                            <!-- Réponses -->
                            @foreach($comment->reponses as $reponse)
                                <div class="comment-item" style="margin-top:0.75rem;padding-bottom:0;border-bottom:none;">
                                    <div class="comment-avatar" style="width:30px;height:30px;font-size:0.72rem;">
                                        {{ strtoupper(substr($reponse->utilisateur->prenom, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="comment-author">{{ $reponse->utilisateur->prenom }}</span>
                                        <span class="comment-date">{{ $reponse->date_commentaire->diffForHumans() }}</span>
                                        <div class="comment-text">{{ $reponse->contenu }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:2rem;color:#9CA3AF;font-size:0.875rem;">
                        <i class="fas fa-comment-slash" style="font-size:2rem;color:#E5E7EB;display:block;margin-bottom:0.75rem;"></i>
                        Aucun commentaire pour le moment.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ─── SIDEBAR ─── -->
        <aside class="pub-sidebar">

            <!-- Profil auteur -->
            <div class="sidebar-card">
                <div class="sidebar-card-title">L'auteur</div>
                <div class="author-profile">
                    <div class="author-profile-avatar">
                        @if($publication->auteur->photo)
                            <img src="{{ $publication->auteur->photo_url }}" alt="">
                        @else
                            {{ strtoupper(substr($publication->auteur->prenom, 0, 1)) }}
                        @endif
                    </div>
                    <div class="author-profile-name">
                        {{ $publication->auteur->prenom }} {{ $publication->auteur->nom }}
                    </div>
                    <div class="author-profile-spec">
                        {{ $publication->auteur->specialite ?? ucfirst($publication->auteur->role) }}
                    </div>
                    @if($publication->auteur->bio)
                        <p style="font-size:0.8rem;color:#6B7280;line-height:1.5;text-align:center;">
                            {{ Str::limit($publication->auteur->bio, 100) }}
                        </p>
                    @endif
                    <div class="author-stats">
                        <div class="author-stat">
                            <div class="author-stat-val">
                                {{ $publication->auteur->publications()->where('statut','publié')->count() }}
                            </div>
                            <div class="author-stat-lbl">Publi.</div>
                        </div>
                        <div class="author-stat">
                            <div class="author-stat-val">
                                {{ number_format($publication->auteur->publications()->sum('nb_vues')) }}
                            </div>
                            <div class="author-stat-lbl">Vues</div>
                        </div>
                        <div class="author-stat">
                            <div class="author-stat-val">
                                {{ $publication->auteur->publications()->sum('nb_likes') }}
                            </div>
                            <div class="author-stat-lbl">Likes</div>
                        </div>
                    </div>
                    <a href="{{ route('login') }}" class="btn-view-profile">
                        <i class="fas fa-user me-1"></i>Voir le profil complet
                    </a>
                </div>
            </div>

            <!-- Avis si projet -->
            @if($publication->avis->count() > 0)
                <div class="sidebar-card">
                    <div class="sidebar-card-title">
                        Avis ({{ $publication->avis->count() }})
                    </div>
                    <div style="text-align:center;margin-bottom:1rem;">
                        <div style="font-family:'Urbanist',sans-serif;font-size:2rem;font-weight:800;color:#7C3AED;">
                            {{ number_format($publication->avis->avg('note'), 1) }}
                        </div>
                        <div style="color:#F59E0B;font-size:0.9rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= round($publication->avis->avg('note')) ? '' : '-o' }}"
                                style="{{ $i <= round($publication->avis->avg('note')) ? 'color:#F59E0B;' : 'color:#E5E7EB;' }}"></i>
                            @endfor
                        </div>
                    </div>
                    @foreach($publication->avis->take(3) as $avis)
                        <div class="avis-item">
                            <div>
                                <div class="avis-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star" style="{{ $i <= $avis->note ? 'color:#F59E0B;' : 'color:#E5E7EB;' }}"></i>
                                    @endfor
                                </div>
                                @if($avis->commentaire)
                                    <div class="avis-text">"{{ Str::limit($avis->commentaire, 80) }}"</div>
                                @endif
                                <div style="font-size:0.72rem;color:#9CA3AF;margin-top:0.2rem;">
                                    — {{ $avis->auteur->prenom }} {{ $avis->auteur->nom }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- CTA rejoindre -->
            <div class="sidebar-cta">
                <h4>Rejoignez DevHub</h4>
                <p>Créez un compte pour interagir avec la communauté, partager vos projets et collaborer.</p>
                <a href="{{ route('register') }}" class="btn-cta-white">
                    <i class="fas fa-rocket me-1"></i>Créer un compte gratuit
                </a>
                <a href="{{ route('login') }}" class="btn-cta-ghost">
                    Déjà membre ? Se connecter
                </a>
            </div>

        </aside>
    </div>

</body>
</html>