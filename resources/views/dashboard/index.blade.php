@extends('layouts.app')

@section('title', 'Tableau de bord – DevHub')
@section('secondary_nav', true)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/s-dashboard.css') }}">
@endsection

@section('content')


<main class="main-content">
<div id="newPostBanner" style="display:none;background:var(--purple-light);color:var(--purple);border:1px solid var(--purple-mid);border-radius:10px;padding:.65rem 1rem;margin-bottom:1rem;font-size:.85rem;font-weight:500;align-items:center;gap:.5rem;cursor:pointer;" onclick="window.location.reload()">
    <i class="fas fa-sync-alt"></i>
    <span id="newPostBannerText"></span>
    <span style="margin-left:auto;font-size:.75rem;text-decoration:underline;">Actualiser</span>
</div>
    <div class="dashboard-layout">

        <!-- ─── SIDEBAR GAUCHE ─── -->
        <aside class="dashboard-sidebar-left">

            <!-- Développeurs suggérés -->
            <div class="sidebar-widget">
                <div class="sidebar-widget-title">À découvrir</div>
                @foreach($suggestions as $dev)
                    <div class="dev-suggest-item">
                        <div class="dev-suggest-avatar">
                            @if($dev->photo)
                                <img src="{{ $dev->photo_url }}" alt="">
                            @else
                                {{ strtoupper(substr($dev->prenom, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('profil.show', $dev->id) }}" class="dev-suggest-name">
                                {{ $dev->prenom }} {{ $dev->nom }}
                            </a>
                            <div class="dev-suggest-spec">{{ $dev->specialite ?? 'Développeur' }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Projets ouverts -->
            <div class="sidebar-widget">
                <div class="sidebar-widget-title">Projets ouverts</div>
                @forelse($projetsOuverts as $projet)
                    @if($projet->publication)
                        <div class="projet-ouvert-item">
                            <a href="{{ route('publications.show', $projet->publication->id) }}" class="projet-ouvert-title">
                                {{ Str::limit($projet->publication->titre, 35) }}
                            </a>
                            <div class="projet-ouvert-meta">
                                <i class="fas fa-users me-1"></i>
                                {{ $projet->taille_equipe_actuelle }}/{{ $projet->taille_equipe_max }} membres
                            </div>
                        </div>
                    @endif
                @empty
                    <p style="font-size:0.82rem;color:var(--gray-400);">Aucun projet ouvert.</p>
                @endforelse
            </div>
        </aside>

        <!-- ─── FEED CENTRAL ─── -->
        <div class="publications-feed">

            @forelse($publications as $pub)
                <article class="publication-card"onclick="window.location='{{ route('publications.show', $pub->id) }}'"style="cursor:pointer;">

                    {{-- Header auteur --}}
                    <div class="publication-header" onclick="event.stopPropagation();">
                        <div >
                            @if($pub->auteur->photo)
                                <img src="{{ $pub->auteur->photo_url }}"
                                    alt="" class="author-avatar">
                            @else
                                <div class="author-avatar-text">
                                    {{ strtoupper(substr($pub->auteur->prenom, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="author-info">
                            <a href="{{ route('profil.show', $pub->auteur->id) }}"
                            class="author-name" onclick="event.stopPropagation();">
                                {{ $pub->auteur->prenom }} {{ $pub->auteur->nom }}
                            </a>
                            <div class="publication-meta">
                                @php
                                    $icons  = ['article'=>'fa-newspaper','idee'=>'fa-lightbulb','projet_personnel'=>'fa-folder','projet_collaboratif'=>'fa-users'];
                                    $labels = ['article'=>'Article','idee'=>'Idée','projet_personnel'=>'Projet perso','projet_collaboratif'=>'Collaboratif'];
                                    $statutProjet = match($pub->type) {
                                        'projet_personnel'    => $pub->projetPersonnel?->statut_projet,
                                        'projet_collaboratif' => $pub->projetCollaboratif?->phase,
                                        default => null,
                                    };
                                    $statutConfig = match($statutProjet) {
                                        'en_cours'  => ['bg'=>'#D1FAE5','color'=>'#065F46','label'=>'En cours'],
                                        'terminé'   => ['bg'=>'#DBEAFE','color'=>'#1D4ED8','label'=>'Terminé'],
                                        'abandonné' => ['bg'=>'#FEE2E2','color'=>'#DC2626','label'=>'Abandonné'],
                                        'ouvert'    => ['bg'=>'#EDE9FE','color'=>'#7C3AED','label'=>'Ouvert'],
                                        default     => null,
                                    };
                                @endphp
                                <span class="publication-type">
                                    <i class="fas {{ $icons[$pub->type] ?? 'fa-file' }} me-1"></i>
                                    <span class="type-label">{{ $labels[$pub->type] ?? $pub->type }}</span>
                                </span>
                                @if($statutConfig)
                                    <span style="font-size:0.65rem;font-weight:700;padding:0.15rem 0.45rem;border-radius:5px;background:{{ $statutConfig['bg'] }};color:{{ $statutConfig['color'] }};">
                                        {{ $statutConfig['label'] }}
                                    </span>
                                @endif
                                · <span class="pub-date" data-date="{{ $pub->date_publication }}">{{ $pub->date_publication->diffForHumans() }}</span>
                            </div>
                        </div>
                        {{-- Collaborer (projet collab uniquement) --}}
                        @if($pub->type === 'projet_collaboratif' && $pub->auteur_id !== Auth::id())
                            @if(in_array($pub->id, $mesMembresIds ?? []))
                                <span class="pub-btn" style="background:#D1FAE5;color:#065F46;border:1.5px solid #6EE7B7;cursor:default;font-size:0.72rem;font-weight:700;padding:0.35rem 0.7rem;border-radius:8px;">
                                    <i class="fas fa-check-circle"></i> Membre
                                </span>
                            @elseif(in_array($pub->id, $mesCandidaturesIds ?? []))
                                <span class="pub-btn" style="background:#FEF9C3;color:#92400E;border:1.5px solid #FCD34D;cursor:default;font-size:0.72rem;font-weight:700;padding:0.35rem 0.7rem;border-radius:8px;">
                                    <i class="fas fa-clock"></i> Candidature envoyée
                                </span>
                            @else
                                <button class="pub-btn pub-btn-collab collaboration-btn"
                                        data-id="{{ $pub->id }}"
                                        data-titre="{{ $pub->titre }}"
                                        title="Rejoindre ce projet">
                                    <i class="fas fa-handshake"></i>
                                    <span>Collaborer</span>
                                </button>
                            @endif
                        @endif
                    </div>

                    {{-- IMAGE EN HAUT — pleine largeur avant la description --}}
                    @if($pub->medias->isNotEmpty())
                        @if($pub->type === 'projet_personnel' && $pub->medias->count() >= 3)
                            <div class="pub-media-grid">
                                @foreach($pub->medias->take(3) as $media)
                                    <img src="{{ $media->url }}"
                                        alt="image projet" class="pub-media-img">
                                @endforeach
                            </div>
                        @else
                            <div class="pub-media-single">
                                <img src="{{ $pub->medias->first()->url }}"
                                    alt="illustration" class="pub-media-cover">
                            </div>
                        @endif
                    @endif

                    {{-- Contenu texte --}}
                    <div class="publication-content">
                        <div class="publication-title">{{ $pub->titre }}</div>
                        @if($pub->contenu)
                            <p class="publication-description">
                                {{ Str::limit(strip_tags($pub->contenu), 180) }}
                            </p>
                        @endif

                        {{-- Stats équipe pour les projets collaboratifs --}}
                        @if($pub->type === 'projet_collaboratif' && $pub->projetCollaboratif)
                            @php
                                $pc = $pub->projetCollaboratif;
                                $nbCandidats = $pc->nb_candidats_en_attente ?? 0;
                            @endphp
                            <div style="display:flex;align-items:center;gap:0.75rem;margin-top:0.6rem;flex-wrap:wrap;">
                                <span style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.72rem;font-weight:600;color:#065F46;background:#D1FAE5;padding:0.2rem 0.55rem;border-radius:6px;">
                                    <i class="fas fa-users"></i>
                                    {{ $pc->taille_equipe_actuelle }}/{{ $pc->nombre_collaborateurs }} membres
                                </span>
                                <span style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.72rem;font-weight:600;color:#1D4ED8;background:#DBEAFE;padding:0.2rem 0.55rem;border-radius:6px;">
                                    <i class="fas fa-inbox"></i>
                                    {{ $nbCandidats }} candidature{{ $nbCandidats > 1 ? 's' : '' }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Barre d'interactions — tout sur une ligne --}}
                    <div class="pub-interactions" onclick="event.stopPropagation();">

                        {{-- Like --}}
                        <button class="pub-btn like-btn {{ $pub->likes->contains('utilisateur_id', Auth::id()) ? 'active' : '' }}"
                                data-id="{{ $pub->id }}" title="J'aime">
                            <i class="{{ $pub->likes->contains('utilisateur_id', Auth::id()) ? 'fas' : 'far' }} fa-heart"></i>
                            <span class="like-count">{{ $pub->nb_likes }}</span>
                        </button>

                        {{-- Commenter --}}
                        <button class="pub-btn comment-toggle-btn"
                                data-id="{{ $pub->id }}" title="Commenter">
                            <i class="far fa-comment"></i>
                            <span>{{ $pub->nb_commentaires }}</span>
                        </button>

                        {{-- Favori --}}
                        <button class="pub-btn favori-btn {{ $pub->favoris->contains('utilisateur_id', Auth::id()) ? 'active' : '' }}"
                                data-id="{{ $pub->id }}" title="Sauvegarder">
                            <i class="{{ $pub->favoris->contains('utilisateur_id', Auth::id()) ? 'fas' : 'far' }} fa-bookmark"></i>
                        </button>

                        {{-- Vues — à droite --}}
                        <span class="pub-views">
                            <i class="far fa-eye me-1"></i>{{ $pub->nb_vues }}
                        </span>

                    </div>

                    {{-- Commentaires inline --}}
                    <div class="comment-inline-zone" id="commentForm{{ $pub->id }}" style="display:none;">
                        <form action="{{ route('commentaires.store') }}" method="POST"
                            class="comment-inline-form" onclick="event.stopPropagation();">
                            @csrf
                            <input type="hidden" name="publication_id" value="{{ $pub->id }}">
                            <div class="author-avatar-text comment-inline-avatar">
                                {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}
                            </div>
                            <textarea name="contenu"
                                    rows="1"
                                    class="comment-inline-input"
                                    placeholder="Écrire un commentaire..."
                                    required></textarea>
                            <button type="submit" class="comment-inline-send">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>

                </article>
            @empty
                <div class="empty-state">
                    <i class="fas fa-stream empty-icon"></i>
                    <div class="empty-title">Aucune publication pour l'instant</div>
                    <p class="empty-description">Soyez le premier à partager quelque chose sur DevHub.</p>
                    <a href="#" class="btn-empty-action"
                       data-bs-toggle="modal" data-bs-target="#modalPublication">
                        <i class="fas fa-plus me-2"></i>Faire une publication
                    </a>
                </div>
            @endforelse

            @if($publications->hasPages())
                <div class="pagination-wrapper">{{ $publications->links() }}</div>
            @endif

        </div>

        <!-- ─── SIDEBAR DROITE ─── -->
        <aside class="dashboard-sidebar-right">

            <div class="discussion-widget">

                <!-- Header -->
                <div class="discussion-header">
                    <div class="discussion-title">
                        <i class="fas fa-comment-dots me-2" style="color:var(--purple);"></i>
                        Messages rapides
                    </div>
                    <a href="{{ route('messagerie.index') }}"
                    style="font-size:0.75rem;color:var(--purple);text-decoration:none;font-weight:600;">
                        Tout voir
                    </a>
                </div>

                <!-- Liste conversations -->
                <div class="discussion-list" id="quickChatList">
                    @forelse($conversations as $conv)
                        @php
                            $estGroupe   = $conv->type_conversation === 'groupe_projet';
                            $estMentorat = !is_null($conv->relation_mentorat_id);
                            $autre       = $conv->participants->firstWhere('id', '!=', Auth::id());
                            $dernierMsg  = $conv->messages->first();
                            $nonLus      = $conv->non_lus ?? 0;
                            $estEnLigne  = !$estGroupe && !$estMentorat && $autre && $autre->derniere_connexion
                                && $autre->derniere_connexion->diffInMinutes(now()) < 5;

                            if ($estGroupe) {
                                $affNom = $conv->nom ?? 'Groupe projet';
                            } elseif ($estMentorat) {
                                $affNom = $conv->nom ?? 'Conversation mentorat';
                            } else {
                                $affNom = $autre ? $autre->prenom . ' ' . $autre->nom : 'Conversation';
                            }
                        @endphp

                        @if($autre || $estGroupe || $estMentorat)
                            <div class="discussion-item quick-chat-contact {{ $nonLus > 0 ? 'has-unread' : '' }}"
                                data-conv-id="{{ $conv->id }}"
                                data-name="{{ $affNom }}"
                                style="cursor:pointer;position:relative;">

                                <!-- Avatar -->
                                <div style="position:relative;flex-shrink:0;">
                                    <div class="discussion-avatar" style="
                                        {{ $estGroupe   ? 'background:#EDE9FE;color:#7C3AED;border-radius:10px;display:flex;align-items:center;justify-content:center;' : '' }}
                                        {{ $estMentorat ? 'background:#FEF3C7;color:#92400E;border-radius:10px;display:flex;align-items:center;justify-content:center;' : '' }}
                                    ">
                                        @if($estGroupe)
                                            <i class="fas fa-users" style="font-size:0.9rem;"></i>
                                        @elseif($estMentorat)
                                            <i class="fas fa-crown" style="font-size:0.9rem;color:#92400E;"></i>
                                        @elseif($autre && $autre->photo)
                                            <img src="{{ $autre->photo_url }}"
                                                style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                                        @elseif($autre)
                                            {{ strtoupper(substr($autre->prenom, 0, 1)) }}
                                        @endif
                                    </div>
                                    @if($estEnLigne)
                                        <span style="position:absolute;bottom:0;right:0;
                                                    width:9px;height:9px;border-radius:50%;
                                                    background:#22C55E;border:2px solid white;">
                                        </span>
                                    @endif
                                </div>

                                <!-- Infos -->
                                <div class="discussion-content">
                                    <div class="discussion-author" style="{{ $nonLus > 0 ? 'font-weight:700;color:var(--gray-900);' : '' }}">
                                        {{ $affNom }}
                                    </div>
                                    @if($dernierMsg)
                                        <div class="discussion-text"
                                            style="{{ $nonLus > 0 ? 'color:var(--gray-700);font-weight:500;' : '' }}">
                                            {{ $dernierMsg->expediteur_id === Auth::id() ? 'Vous : ' : '' }}
                                            {{ Str::limit($dernierMsg->contenu, 30) }}
                                        </div>
                                    @else
                                        <div class="discussion-text" style="font-style:italic;">
                                            Démarrer la conversation
                                        </div>
                                    @endif
                                </div>

                                <!-- Meta -->
                                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:0.25rem;flex-shrink:0;">
                                    @if($dernierMsg)
                                        <span class="discussion-time">
                                            {{ $dernierMsg->date_envoi->diffForHumans(null, true) }}
                                        </span>
                                    @endif
                                    @if($nonLus > 0)
                                        <span style="background:var(--purple);color:white;
                                                    font-size:0.6rem;font-weight:700;
                                                    min-width:18px;height:18px;border-radius:9px;
                                                    display:flex;align-items:center;justify-content:center;
                                                    padding:0 4px;">
                                            {{ $nonLus }}
                                        </span>
                                    @elseif($dernierMsg && $dernierMsg->expediteur_id === Auth::id())
                                        <span style="font-size:0.62rem;color:{{ $dernierMsg->est_lu ? 'var(--purple)' : 'var(--gray-300)' }};">
                                            <i class="fas fa-check-double"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @empty
                        <div style="padding:1.5rem;text-align:center;color:var(--gray-400);font-size:0.82rem;">
                            <i class="fas fa-comment-slash" style="font-size:1.5rem;color:var(--gray-200);display:block;margin-bottom:0.5rem;"></i>
                            Aucune conversation
                        </div>
                    @endforelse
                </div>

                {{-- Zone de saisie rapide --}}
                <div id="quickChatBox" style="display:none;border-top:1px solid var(--gray-100);padding:0.85rem;">
                    <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.65rem;">
                        <div style="position:relative;flex-shrink:0;">
                            <div class="discussion-avatar" id="quickChatAvatar"
                                style="background:var(--purple-light);color:var(--purple);">?</div>
                            <span id="quickChatOnlineDot"
                                style="display:none;position:absolute;bottom:0;right:0;
                                        width:9px;height:9px;border-radius:50%;
                                        background:#22C55E;border:2px solid white;"></span>
                        </div>
                        <div>
                            <span id="quickChatName"
                                style="font-size:0.85rem;font-weight:600;color:var(--gray-900);display:block;"></span>
                            <span id="quickChatStatus"
                                style="font-size:0.7rem;color:var(--gray-400);"></span>
                        </div>
                        <button onclick="closeQuickChat()"
                                style="margin-left:auto;background:none;border:none;color:var(--gray-400);cursor:pointer;font-size:0.8rem;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="quickChatMessages"
                        style="max-height:150px;overflow-y:auto;margin-bottom:0.65rem;
                                display:flex;flex-direction:column;gap:0.4rem;scrollbar-width:none;">
                    </div>
                    <form id="quickChatForm" style="display:flex;gap:0.5rem;">
                        @csrf
                        <input type="hidden" id="quickChatConvId" value="">
                        <input type="text"
                            id="quickChatInput"
                            placeholder="Écrire un message..."
                            autocomplete="off"
                            style="flex:1;padding:0.5rem 0.85rem;border:1.5px solid var(--gray-200);
                                    border-radius:20px;font-size:0.82rem;font-family:'DM Sans',sans-serif;
                                    outline:none;transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='var(--purple)'"
                            onblur="this.style.borderColor='var(--gray-200)'">
                        <button type="submit"
                                style="background:var(--purple);color:white;border:none;
                                    width:34px;height:34px;border-radius:50%;cursor:pointer;
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;
                                    transition:background 0.2s;"
                                onmouseover="this.style.background='var(--purple-dark)'"
                                onmouseout="this.style.background='var(--purple)'">
                            <i class="fas fa-paper-plane" style="font-size:0.75rem;"></i>
                        </button>
                    </form>
                </div>

                <!-- Bouton nouveau message -->
                <div style="padding:0.75rem 1.25rem;border-top:1px solid var(--gray-100);">
                    <a href="{{ route('messagerie.index') }}"
                    style="display:flex;align-items:center;justify-content:center;gap:0.5rem;
                            padding:0.5rem;background:var(--purple-light);color:var(--purple);
                            border-radius:8px;text-decoration:none;font-size:0.82rem;font-weight:600;
                            transition:background 0.2s;"
                    onmouseover="this.style.background='var(--purple)';this.style.color='white'"
                    onmouseout="this.style.background='var(--purple-light)';this.style.color='var(--purple)'">
                        <i class="fas fa-edit"></i> Nouveau message
                    </a>
                </div>
            </div>

        </aside>
    </div>

</main>

@endsection

@section('scripts')
<script>
// Formater les dates pour mobile
function formatDateForMobile(date) {
    const now = new Date();
    const diff = Math.floor((now - date) / 1000); // différence en secondes

    if (diff < 60) return 'à l\'instant';
    if (diff < 3600) {
        const mins = Math.floor(diff / 60);
        return mins + ' min';
    }
    if (diff < 86400) {
        const hours = Math.floor(diff / 3600);
        return hours + ' h';
    }
    if (diff < 604800) {
        const days = Math.floor(diff / 86400);
        return days + ' j';
    }

    const months = Math.floor(diff / 2592000);
    if (months < 12) return months + ' mois';

    const years = Math.floor(diff / 31536000);
    return years + ' an' + (years > 1 ? 's' : '');
}

// Appliquer le formatage sur mobile
if (window.innerWidth <= 768) {
    document.querySelectorAll('.pub-date').forEach(elem => {
        const dateStr = elem.getAttribute('data-date');
        if (dateStr) {
            const date = new Date(dateStr);
            elem.textContent = formatDateForMobile(date);
        }
    });
}

// Redéfinir au resize
let resizeTimeout;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
        const isMobile = window.innerWidth <= 768;
        document.querySelectorAll('.pub-date').forEach(elem => {
            const dateStr = elem.getAttribute('data-date');
            if (dateStr) {
                const date = new Date(dateStr);
                if (isMobile) {
                    elem.textContent = formatDateForMobile(date);
                } else {
                    // Remettre le format original si desktop
                    elem.textContent = elem.textContent; // Peut être amélioré avec une date formatée
                }
            }
        });
    }, 250);
});
</script>
@endsection
