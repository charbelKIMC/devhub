<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DevHub')</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@300;400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/s-app.css') }}"> @yield('styles')
    <script>
        window.APP_URL = "{{ url('') }}";
        window.CSRF_TOKEN = "{{ csrf_token() }}";
    </script>
</head>
<body>

    <!-- ─── HEADER ─── -->
    <header class="header">
        <div class="conteneur">
            <div class="navbar">

                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="name-page">
                    <span><img src="{{ asset('img/logo-devhub.png') }}" alt="Logo de DevHub" class="logo-icon"></span>
                    DevHub
                </a>

                <!-- Nav centrale -->
                <nav class="header-nav mx-3">

                    <!-- Gérer -->
                    <div class="nav-item dropdown-custom">
                        <button class="nav-btn" id="manageBtn">
                            <i class="fas fa-tasks me-2"></i>Gérer
                        </button>
                        <div class="dropdown-panel" id="managePanel">
                            <div class="dropdown-header">Vue d'ensemble</div>
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-number">{{ Auth::user()->publications()->where('statut','publié')->count() }}</div>
                                    <div class="stat-label">Publications</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">{{ Auth::user()->publications()->sum('nb_vues') }}</div>
                                    <div class="stat-label">Vues</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">{{ Auth::user()->publications()->sum('nb_likes') }}</div>
                                    <div class="stat-label">Likes</div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('dashboard.publications') }}" class="dropdown-link">
                                <i class="fas fa-eye me-2"></i>Voir mes publications
                            </a>
                            <a href="{{ route('dashboard.collaborations') }}" class="dropdown-link">
                                <i class="fas fa-handshake me-2"></i>Mes collaborations
                            </a>
                        </div>
                    </div>
                    
                    <!-- Recherche live -->
                    <div class="search-wrapper" id="searchWrapper">
                        <div class="search-input-wrap">
                            <i class="fas fa-search search-icon"></i>
                            <input
                                type="text"
                                id="searchInput"
                                class="search-input"
                                placeholder="Rechercher publications, développeurs..."
                                autocomplete="off"
                            >
                            <button class="search-clear" id="searchClear" style="display:none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Dropdown résultats -->
                        <div class="search-dropdown" id="searchDropdown" style="display:none;">
                            <div id="searchResults"></div>
                        </div>
                    </div>
                    <!-- Btn Publier -->
                    <button class="btn-publish" id="btnPublier" data-bs-toggle="modal" data-bs-target="#modalPublication">
                        <i class="fas fa-plus"></i> Faire une publication
                    </button>

                </nav>

                <!-- Notifications + User -->
                <div class="d-flex align-items-center gap-2 ms-auto">
                    <a href="{{ route('messagerie.index') }}" class="nav-btn {{ request()->routeIs('messagerie.*') ? 'active' : '' }}" style="position:relative;">
                        <div style="gap: 3px; display: flex; align-items: center;">
                            <i class="fas fa-comment-dots"></i> 
                            <span class="mtitre">Messages</span>                            
                        </div>
                        <span id="msgBadge" class="message_count">0</span>
                    </a>
                    <!-- Notifications -->
                    <div class="notif-wrapper dropdown-custom" style="position:relative;">
                        <button class="nav-btn" id="notifBtn" style="position:relative;">
                            <i class="fas fa-bell"></i>
                            <span id="notifBadge" class="notification_count">0</span>
                        </button>

                        <!-- Dropdown notifications -->
                        <div id="notifPanel"class="dropdown_notification">

                            <div style="display:flex;align-items:center;justify-content:space-between;
                                        padding:1rem 1.25rem;border-bottom:1px solid #F3F4F6;">
                                <div style="font-family:'Urbanist',sans-serif;font-weight:700;
                                            font-size:0.95rem;color:#111827;">
                                    Notifications
                                </div>
                                <button id="notifToutLire"
                                        style="background:none;border:none;cursor:pointer;
                                            font-size:0.75rem;color:#7C3AED;font-weight:600;
                                            font-family:'DM Sans',sans-serif;">
                                    Tout marquer comme lu
                                </button>
                            </div>

                            <!-- Liste notifications -->
                            <div id="notifList" style="max-height:340px;overflow-y:auto;
                                                        scrollbar-width:thin;">
                                <div style="padding:2rem;text-align:center;color:#9CA3AF;font-size:0.82rem;">
                                    <i class="fas fa-spinner fa-spin"></i> Chargement...
                                </div>
                            </div>

                            <!-- Footer -->
                            <div style="padding:0.75rem 1.25rem;border-top:1px solid #F3F4F6;text-align:center;">
                                <a href="{{ route('notifications.index') }}"
                                style="font-size:0.82rem;color:#7C3AED;font-weight:600;text-decoration:none;">
                                    Voir toutes les notifications
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User menu -->
                    <div class="dropdown-custom">
                        <button class="user-btn" id="userBtn">
                            @if(Auth::user()->photo)
                                <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="photo" class="user-avatar">
                            @else
                                <div class="user-avatar-text">{{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}</div>
                            @endif
                            <span class="user-name">{{ Auth::user()->pseudo }}</span>
                            <i class="fas fa-chevron-down" style="font-size:0.7rem; color:var(--gray-400);"></i>
                        </button>

                        <div class="dropdown-panel-user" id="userPanel">
                            <div class="user-info">
                                <div class="user-avatar-large">
                                    @if(Auth::user()->photo)
                                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="photo">
                                    @else
                                        {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="user-fullname">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                                    <div class="user-email">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('profil.show', Auth::user()->id) }}" class="dropdown-link">
                                <i class="fas fa-user me-2"></i>Mon profil
                            </a>
                            <a href="{{ route('dashboard') }}" class="dropdown-link">
                                <i class="fas fa-th-large me-2"></i>Tableau de bord
                            </a>
                            <a href="{{ route('notifications.index') }}" class="dropdown-link">
                                <i class="fas fa-bell me-2"></i>Notifications
                            </a>
                            @if(Auth::user()->isMentor())
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('mentorat.espace') }}" class="dropdown-link">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>Espace mentor
                                </a>
                            @endif
                            @if(Auth::user()->isAdmin())
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-link">
                                    <i class="fas fa-cog me-2"></i>Administration
                                </a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="button" class="dropdown-link text-danger w-100 border-0 bg-transparent text-start" onclick="confirmerDeconnexion()" 
                                        style="display:flex;align-items:center;gap:0.5rem;">
                                    <i class="fas fa-sign-out-alt"></i>Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <!-- ─── SECONDARY NAV (onglets dashboard) ─── -->
    @hasSection('secondary_nav')
        <nav class="secondary-nav mb-3">
            <div class="conteneur">
                <ul class="nav-tabs-custom">
                    <li>
                        <a href="{{ route('dashboard') }}"
                        class="nav-tab {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-th-large"></i> Tableau de bord
                            <span id="newPubsBadge"
                                style="display: {{ isset($newPubsCount) && $newPubsCount > 0 ? 'inline-flex' : 'none' }};
                                        background:#EF4444;color:white;font-size:0.6rem;font-weight:700;
                                        min-width:18px;height:18px;border-radius:9px;
                                        align-items:center;justify-content:center;
                                        padding:0 4px;margin-left:0.35rem;line-height:1;">
                                {{ isset($newPubsCount) && $newPubsCount > 0 ? ($newPubsCount > 9 ? '9+' : $newPubsCount) : '' }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.publications') }}"
                           class="nav-tab {{ request()->routeIs('dashboard.publications') ? 'active' : '' }}">
                            <i class="fas fa-folder"></i> Mes publications
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('projets-personnels.index') }}"
                           class="nav-tab {{ request()->routeIs('projets-personnels.*') ? 'active' : '' }}">
                            <i class="fas fa-folder-open"></i> Projets personnels
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.collaborations') }}"
                           class="nav-tab {{ request()->routeIs('dashboard.collaborations') ? 'active' : '' }}">
                            <i class="fas fa-handshake"></i> Mes collaborations
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.avis') }}"
                           class="nav-tab {{ request()->routeIs('dashboard.avis') ? 'active' : '' }}">
                            <i class="fas fa-star"></i> Avis reçus
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.statistiques') }}"
                           class="nav-tab {{ request()->routeIs('dashboard.statistiques') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i> Statistiques
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.favoris') }}"
                           class="nav-tab {{ request()->routeIs('dashboard.favoris') ? 'active' : '' }}">
                            <i class="fas fa-heart"></i> Favoris
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    @endif

    <!-- Alertes globales -->
    @if(session('success'))
        <div class="main-content" style="padding-bottom:0;">
            <div class="alert-success-devhub">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="main-content" style="padding-bottom:0;">
            <div class="alert-error-devhub">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Modal Publication -->
    <div class="modal fade" id="modalPublication" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px;border:none;">

                <div class="modal-header" style="border-bottom:1px solid var(--gray-100);padding:1.25rem 1.5rem;">
                    <h5 style="font-family:'Urbanist',sans-serif;font-weight:700;font-size:1.1rem;margin:0;">
                        Nouvelle publication
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="padding:1.5rem;">
                    <form method="POST"
                        action="{{ route('publications.store') }}"
                        id="modalPubForm"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Sélection du type -->
                        <input type="hidden" name="type" id="modalTypeInput" value="article">
                        <div style="display:flex;gap:0.65rem;margin-bottom:1.25rem;flex-wrap:wrap;">
                            @foreach([
                                ['article',             'fa-newspaper', 'Article'],
                                ['idee',                'fa-lightbulb', 'Idée'],
                                ['projet_collaboratif', 'fa-users',     'Collaboratif'],
                            ] as $t)
                                <div class="modal-type-opt {{ $t[0] === 'article' ? 'selected' : '' }}"
                                    data-type="{{ $t[0] }}"
                                    style="flex:1;min-width:90px;padding:0.75rem;border:2px solid var(--gray-200);border-radius:10px;cursor:pointer;text-align:center;transition:all 0.2s;">
                                    <i class="fas {{ $t[1] }}" style="color:var(--purple);font-size:1.2rem;display:block;margin-bottom:0.3rem;"></i>
                                    <span style="font-size:0.8rem;font-weight:600;color:var(--gray-700);">{{ $t[2] }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Titre -->
                        <div style="margin-bottom:1rem;">
                            <input type="text" name="titre"
                                placeholder="Titre de votre publication *"
                                style="width:100%;padding:0.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:10px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none;"
                                required>
                        </div>

                        <!-- Description -->
                        <div style="margin-bottom:1rem;">
                            <textarea name="contenu"
                                    placeholder="Décrivez votre publication..."
                                    rows="3"
                                    style="width:100%;padding:0.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:10px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none;resize:vertical;"></textarea>
                        </div>

                        <!-- Champs projet -->
                        <div id="modalProjetFields" style="display:none;">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:1rem;">
                                <input type="url" name="url_repo"
                                    placeholder="Lien dépôt (GitHub, GitLab...)"
                                    style="padding:0.65rem 1rem;border:1.5px solid var(--gray-200);border-radius:10px;font-size:0.875rem;font-family:'DM Sans',sans-serif;outline:none;">
                                <input type="url" name="url_demo"
                                    placeholder="Lien démo"
                                    style="padding:0.65rem 1rem;border:1.5px solid var(--gray-200);border-radius:10px;font-size:0.875rem;font-family:'DM Sans',sans-serif;outline:none;">
                            </div>
                            <input type="text" name="technologies"
                                placeholder="Technologies utilisées (ex: React, Laravel, MySQL...)"
                                style="width:100%;padding:0.65rem 1rem;border:1.5px solid var(--gray-200);border-radius:10px;font-size:0.875rem;font-family:'DM Sans',sans-serif;outline:none;margin-bottom:1rem;">
                        </div>

                        <!-- Champs collaboratif uniquement -->
                        <div id="modalCollabFields" style="display:none;">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:1rem;">
                                <div>
                                    <label style="font-size:0.78rem;font-weight:600;color:var(--gray-500);display:block;margin-bottom:0.3rem;">Taille équipe max</label>
                                    <input type="number" name="taille_equipe_max" value="5" min="2" max="20"
                                        style="width:100%;padding:0.65rem 1rem;border:1.5px solid var(--gray-200);border-radius:10px;font-size:0.875rem;font-family:'DM Sans',sans-serif;outline:none;">
                                </div>
                                <div>
                                    <label style="font-size:0.78rem;font-weight:600;color:var(--gray-500);display:block;margin-bottom:0.3rem;">Phase</label>
                                    <select name="phase" style="width:100%;padding:0.65rem 1rem;border:1.5px solid var(--gray-200);border-radius:10px;font-size:0.875rem;font-family:'DM Sans',sans-serif;outline:none;background:white;">
                                        <option value="ouvert">Ouvert aux candidatures</option>
                                        <option value="en_cours">En cours</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- ─── UPLOAD IMAGES ─── -->
                        <!-- Image unique (article, idée, collaboratif) -->
                        <div id="uploadSingle" style="display:block;margin-bottom:1rem;">
                            <label style="font-size:0.78rem;font-weight:600;color:var(--gray-500);display:block;margin-bottom:0.5rem;">
                                Image d'illustration <span style="font-weight:400;color:var(--gray-400);">(optionnel)</span>
                            </label>
                            <div class="upload-drop-zone" id="dropSingle" onclick="document.getElementById('imgSingle').click()">
                                <input type="file" id="imgSingle" name="images[]" accept="image/*" style="display:none;">
                                <div class="upload-drop-inner" id="dropSingleInner">
                                    <i class="fas fa-image" style="font-size:1.5rem;color:var(--purple);margin-bottom:0.4rem;"></i>
                                    <div style="font-size:0.82rem;font-weight:600;color:var(--gray-700);">Cliquez pour ajouter une image</div>
                                    <div style="font-size:0.72rem;color:var(--gray-400);">JPG, PNG ou WebP – Max 3MB</div>
                                </div>
                            </div>
                        </div>

                        <!-- Statut -->
                        <input type="hidden" name="statut" id="modalStatutInput" value="publié">
                        <div id="modalStatutOpts" style="display:flex;gap:0.75rem;margin-bottom:0.5rem;">
                            <div class="modal-statut-opt selected" data-statut="publié"
                                style="flex:1;padding:0.6rem;border:2px solid var(--purple);border-radius:10px;cursor:pointer;text-align:center;font-size:0.82rem;font-weight:600;color:var(--purple);background:var(--purple-light);transition:all 0.2s;">
                                <i class="fas fa-globe me-1"></i>Publier maintenant
                            </div>
                            <div class="modal-statut-opt" id="modalBrouillonOpt" data-statut="brouillon"
                                style="flex:1;padding:0.6rem;border:2px solid var(--gray-200);border-radius:10px;cursor:pointer;text-align:center;font-size:0.82rem;font-weight:600;color:var(--gray-500);transition:all 0.2s;">
                                <i class="fas fa-save me-1"></i>Brouillon
                            </div>
                        </div>

                        <!-- Message erreur/succès -->
                        <div id="modalPubMessage" style="display:none;border-radius:8px;padding:0.65rem 1rem;font-size:0.85rem;margin-top:0.75rem;"></div>

                    </form>
                </div>

                <div class="modal-footer" style="border-top:1px solid var(--gray-100);padding:1rem 1.5rem;">
                    <button type="button" data-bs-dismiss="modal"
                            style="padding:0.6rem 1.5rem;border:1.5px solid var(--gray-200);border-radius:10px;background:transparent;color:var(--gray-500);font-weight:500;cursor:pointer;">
                        Annuler
                    </button>
                    <button type="button" id="btnSubmitPub"
                            style="padding:0.6rem 1.5rem;background:var(--purple);color:white;border:none;border-radius:10px;font-weight:600;cursor:pointer;margin-left:0.75rem;display:flex;align-items:center;gap:0.5rem;">
                        <i class="fas fa-paper-plane"></i> Publier
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Candidature Collaboration -->
    <div class="modal fade" id="modalCollab" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
            <div class="modal-content" style="border-radius:16px;border:none;">

                <div class="modal-header" style="border-bottom:1px solid var(--gray-100);padding:1.25rem 1.5rem;">
                    <div>
                        <h5 style="font-family:'Urbanist',sans-serif;font-weight:700;font-size:1.05rem;margin:0;">
                            Rejoindre ce projet
                        </h5>
                        <p id="collabModalTitre" style="font-size:0.78rem;color:var(--gray-400);margin:0.2rem 0 0;"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="padding:1.5rem;">

                    <!-- Poste souhaité -->
                    <div style="margin-bottom:1.25rem;">
                        <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--gray-700);margin-bottom:0.65rem;">
                            Quel poste souhaitez-vous occuper ? <span style="color:#EF4444;">*</span>
                        </label>
                        <div class="collab-tags-grid" id="collabTagsGrid">
                            @foreach([
                                ['Développeur Frontend',  'fa-laptop-code'],
                                ['Développeur Backend',   'fa-server'],
                                ['Développeur Full-Stack', 'fa-code'],
                                ['Designer UI/UX',        'fa-paint-brush'],
                                ['Chef de projet',        'fa-tasks'],
                                ['Testeur QA',            'fa-bug'],
                                ['Autre',                 'fa-ellipsis-h'],
                            ] as $tag)
                                <div class="collab-tag" data-poste="{{ $tag[0] }}">
                                    <i class="fas {{ $tag[1] }}"></i>
                                    {{ $tag[0] }}
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" id="collabPosteSelected" value="">
                        <div id="collabPosteError"
                            style="display:none;color:#EF4444;font-size:0.75rem;margin-top:0.4rem;">
                            Veuillez sélectionner un poste.
                        </div>
                    </div>

                    <!-- Message de motivation -->
                    <div style="margin-bottom:0.5rem;">
                        <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--gray-700);margin-bottom:0.4rem;">
                            Message de motivation
                            <span style="font-weight:400;color:var(--gray-400);">(optionnel)</span>
                        </label>
                        <textarea id="collabMotivation"
                                rows="4"
                                placeholder="Présentez vos compétences et expliquez pourquoi vous souhaitez rejoindre ce projet..."
                                style="width:100%;padding:0.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:10px;font-size:0.875rem;font-family:'DM Sans',sans-serif;resize:none;outline:none;transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='var(--purple)'"
                                onblur="this.style.borderColor='var(--gray-200)'"></textarea>
                    </div>

                    <!-- Message retour -->
                    <div id="collabMessage" style="display:none;border-radius:8px;padding:0.65rem 1rem;font-size:0.85rem;margin-top:0.75rem;"></div>

                </div>

                <div class="modal-footer" style="border-top:1px solid var(--gray-100);padding:1rem 1.5rem;">
                    <button type="button" data-bs-dismiss="modal"
                            style="padding:0.6rem 1.5rem;border:1.5px solid var(--gray-200);border-radius:10px;background:transparent;color:var(--gray-500);font-weight:500;cursor:pointer;">
                        Annuler
                    </button>
                    <button id="btnSubmitCollab"
                            style="padding:0.6rem 1.5rem;background:var(--purple);color:white;border:none;border-radius:10px;font-weight:600;cursor:pointer;margin-left:0.75rem;display:flex;align-items:center;gap:0.5rem;">
                        <i class="fas fa-paper-plane"></i> Envoyer ma candidature
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal confirmation -->
    <div id="modalDeconnexion" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;display:none;align-items:center;justify-content:center;">
        <div style="background:white;border-radius:16px;padding:2rem;max-width:380px;width:90%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
            <div style="width:56px;height:56px;border-radius:50%;background:#FEE2E2;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <i class="fas fa-sign-out-alt" style="color:#DC2626;font-size:1.3rem;"></i>
            </div>
            <h3 style="font-family:'Urbanist',sans-serif;font-size:1rem;font-weight:700;color:#111827;margin-bottom:0.5rem;">
                Confirmer la déconnexion
            </h3>
            <p style="font-size:0.82rem;color:#6B7280;margin-bottom:1.5rem;line-height:1.6;">
                Êtes-vous sûr de vouloir vous déconnecter de DevHub ?
            </p>
            <div style="display:flex;gap:0.75rem;">
                <button onclick="fermerModalDeconnexion()" 
                        style="flex:1;padding:0.65rem;background:#F3F4F6;color:#374151;border:none;border-radius:10px;font-size:0.85rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;">
                    Annuler
                </button>
                <form action="{{ route('logout') }}" method="POST" style="flex:1;">
                    @csrf
                    <button type="submit" 
                            style="width:100%;padding:0.65rem;background:#DC2626;color:white;border:none;border-radius:10px;font-size:0.85rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;">
                        Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div id="confirmModal"
        style="display:none;position:fixed;inset:0;z-index:9999;
                align-items:center;justify-content:center;
                background:rgba(0,0,0,0.45);">
        <div style="background:white;border-radius:16px;padding:2rem;
                    max-width:400px;width:90%;
                    box-shadow:0 20px 60px rgba(0,0,0,0.2);text-align:center;">
            <div id="confirmIcon"
                style="width:56px;height:56px;border-radius:50%;
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.4rem;margin:0 auto 1rem;"></div>
            <h3 id="confirmTitle"
                style="font-family:'Urbanist',sans-serif;font-size:1rem;
                    font-weight:800;color:#111827;margin-bottom:0.5rem;"></h3>
            <p id="confirmMessage"
            style="font-size:0.82rem;color:#6B7280;
                    margin-bottom:1.5rem;line-height:1.6;"></p>
            <div style="display:flex;gap:0.75rem;">
                <button id="cancelBtn"
                        style="flex:1;padding:0.65rem;background:#F3F4F6;
                            color:#374151;border:none;border-radius:10px;
                            font-size:0.875rem;font-weight:600;cursor:pointer;
                            font-family:'DM Sans',sans-serif;">
                    Annuler
                </button>
                <button id="confirmBtn"
                        style="flex:1;padding:0.65rem;border:none;
                            border-radius:10px;font-size:0.875rem;
                            font-weight:600;cursor:pointer;
                            font-family:'DM Sans',sans-serif;color:white;">
                    Confirmer
                </button>
            </div>
        </div>
    </div>
   <!-- Contenu principal -->
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
        (function() {
            var modal       = document.getElementById('confirmModal');
            var confirmBtn  = document.getElementById('confirmBtn');
            var cancelBtn   = document.getElementById('cancelBtn');
            var pendingForm = null;

            // Ouvrir la modal
            function openConfirm(form) {
                pendingForm = form;

                document.getElementById('confirmTitle').textContent   = form.dataset.title   || 'Confirmer';
                document.getElementById('confirmMessage').textContent = form.dataset.confirm || 'Confirmer cette action ?';

                var type = form.dataset.type || 'info';
                var cfg  = {
                    danger:  { bg:'#FEE2E2', color:'#DC2626', icon:'fa-trash',        btn:'#DC2626' },
                    warning: { bg:'#FEF9C3', color:'#854D0E', icon:'fa-exclamation',   btn:'#F59E0B' },
                    success: { bg:'#D1FAE5', color:'#065F46', icon:'fa-check-circle',  btn:'#22C55E' },
                    info:    { bg:'#EDE9FE', color:'#7C3AED', icon:'fa-info-circle',   btn:'#7C3AED' },
                }[type] || { bg:'#EDE9FE', color:'#7C3AED', icon:'fa-info-circle', btn:'#7C3AED' };

                var iconEl = document.getElementById('confirmIcon');
                iconEl.style.background = cfg.bg;
                iconEl.style.color      = cfg.color;
                iconEl.innerHTML        = '<i class="fas ' + cfg.icon + '"></i>';
                confirmBtn.style.background = cfg.btn;

                modal.style.display = 'flex';
            }

            // Fermer la modal
            function closeConfirm() {
                modal.style.display = 'none';
                pendingForm = null;
            }

            // Confirmer → soumettre le formulaire
            confirmBtn.addEventListener('click', function() {
                if (pendingForm) {
                    var f = pendingForm;
                    closeConfirm();
                    f.submit();
                }
            });

            // Annuler
            cancelBtn.addEventListener('click', closeConfirm);

            // Clic en dehors
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeConfirm();
            });

            // Touche Echap
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeConfirm();
            });

            // ← Délégation d'événements sur document
            // Intercepte TOUS les submit de formulaires avec data-confirm
            document.addEventListener('submit', function(e) {
                var form = e.target;
                if (form.hasAttribute('data-confirm')) {
                    e.preventDefault();
                    e.stopPropagation();
                    openConfirm(form);
                }
            }, true); // ← capture phase, avant tout autre handler
        })();
    </script>
    @auth
    <script>
        // ─── Badge notifications ───────────────────────────────
        function updateNotifBadge(count) {
            var badge = document.getElementById('notifBadge');
            if (!badge) return;
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        // Chargement initial uniquement — pas de setInterval
        fetch('{{ route("notifications.poll") }}', {
            headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) { updateNotifBadge(data.count); })
        .catch(function() {});

        // ─── Dropdown notifications ────────────────────────────
        var notifBtn   = document.getElementById('notifBtn');
        var notifPanel = document.getElementById('notifPanel');
        var notifOpen  = false;

        function typeIcon(type) {
            var icons = {
                'nouveau_like':         { icon: 'fa-heart',        bg: '#FCE7F3', color: '#BE185D' },
                'nouveau_commentaire':  { icon: 'fa-comment',      bg: '#EDE9FE', color: '#7C3AED' },
                'nouveau_message':      { icon: 'fa-envelope',     bg: '#DBEAFE', color: '#1D4ED8' },
                'nouvelle_candidature': { icon: 'fa-handshake',    bg: '#D1FAE5', color: '#065F46' },
                'candidature_acceptee': { icon: 'fa-check-circle', bg: '#D1FAE5', color: '#065F46' },
                'candidature_refusee':  { icon: 'fa-times-circle', bg: '#FEE2E2', color: '#DC2626' },
                'nouvel_avis':          { icon: 'fa-star',         bg: '#FEF9C3', color: '#854D0E' },
                'bienvenue':            { icon: 'fa-hand-wave',    bg: '#EDE9FE', color: '#7C3AED' },
                'connexion':            { icon: 'fa-sign-in-alt',  bg: '#F3F4F6', color: '#6B7280' },
            };
            return icons[type] || { icon: 'fa-bell', bg: '#F3F4F6', color: '#6B7280' };
        }

        function loadNotifDropdown() {
            var list = document.getElementById('notifList');
            if (!list) return;
            fetch('{{ route("notifications.poll") }}', {
                headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Accept': 'application/json' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var notifs = data.notifications || [];
                if (!notifs.length) {
                    list.innerHTML = '<div style="padding:2rem;text-align:center;color:#9CA3AF;font-size:0.82rem;"><i class="fas fa-bell-slash" style="font-size:1.5rem;display:block;margin-bottom:0.5rem;color:#E5E7EB;"></i>Aucune notification</div>';
                    return;
                }
                var html = '';
                notifs.forEach(function(n) {
                    var ic = typeIcon(n.type);
                    html += '<a href="' + (n.lien || '#') + '" style="display:flex;align-items:flex-start;gap:0.75rem;padding:0.85rem 1.25rem;text-decoration:none;border-bottom:1px solid #F9FAFB;background:' + (n.est_lue ? 'white' : '#F5F3FF') + ';">'
                        + '<div style="width:34px;height:34px;border-radius:50%;background:' + ic.bg + ';color:' + ic.color + ';display:flex;align-items:center;justify-content:center;font-size:0.82rem;flex-shrink:0;">'
                        + '<i class="fas ' + ic.icon + '"></i></div>'
                        + '<div style="flex:1;min-width:0;">'
                        + '<div style="font-size:0.82rem;color:#374151;line-height:1.4;">' + n.contenu + '</div>'
                        + '<div style="font-size:0.7rem;color:#9CA3AF;margin-top:0.2rem;">' + n.date + '</div>'
                        + '</div>'
                        + (n.est_lue ? '' : '<div style="width:7px;height:7px;border-radius:50%;background:#7C3AED;flex-shrink:0;margin-top:4px;"></div>')
                        + '</a>';
                });
                list.innerHTML = html;
            })
            .catch(function() {
                list.innerHTML = '<div style="padding:1rem;text-align:center;color:#9CA3AF;font-size:0.82rem;">Erreur de chargement</div>';
            });
        }

        notifBtn?.addEventListener('click', function(e) {
            e.stopPropagation();
            notifOpen = !notifOpen;
            notifPanel.style.display = notifOpen ? 'block' : 'none';
            if (notifOpen) loadNotifDropdown();
        });

        document.getElementById('notifToutLire')?.addEventListener('click', function(e) {
            e.stopPropagation();
            fetch('{{ route("notifications.toutLire") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Accept': 'application/json' }
            })
            .then(function() {
                updateNotifBadge(0);
                loadNotifDropdown();
            });
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#notifBtn') && !e.target.closest('#notifPanel')) {
                notifPanel.style.display = 'none';
                notifOpen = false;
            }
        });

        // ─── Toast notification ────────────────────────────────
        function showNotifToast(message, lien) {
            var toast = document.createElement('div');
            toast.style.cssText = 'position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;background:#7C3AED;color:white;padding:.75rem 1.25rem;border-radius:12px;font-size:.85rem;font-weight:500;box-shadow:0 4px 20px rgba(124,58,237,0.3);display:flex;align-items:center;gap:.5rem;max-width:320px;cursor:pointer;';
            toast.innerHTML = '<i class="fas fa-bell"></i><span>' + message + '</span>';
            if (lien) toast.addEventListener('click', function() { window.location.href = lien; });
            document.body.appendChild(toast);
            setTimeout(function() { if (toast.parentNode) toast.remove(); }, 6000);
        }

        // ─── Badge messages — chargement initial uniquement ────
        function updateMsgBadge(count) {
            var badge = document.getElementById('msgBadge');
            if (!badge) return;
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        // Chargement initial uniquement — plus de setInterval
        fetch('{{ route("messages.nonLus") }}', {
            headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) { updateMsgBadge(data.count); })
        .catch(function() {});

        // ─── Pusher / Echo ─────────────────────────────────────
        window.addEventListener('load', function() {
            if (!window.Echo) return;

            // Notifications en temps réel
            window.Echo.private('notifications.{{ Auth::id() }}')
                .listen('.notification.creee', function(data) {
                    var badge = document.getElementById('notifBadge');
                    var current = parseInt(badge?.textContent) || 0;
                    updateNotifBadge(current + 1);
                    showNotifToast(data.contenu, data.lien);
                });

            // Messages en temps réel — badge mis à jour via messagerie
            // (géré dans messagerie/index.blade.php)

            // Feed public — nouvelles publications
            window.Echo.channel('feed.public')
                .listen('.publication.creee', function(data) {
                    var banner = document.getElementById('newPostBanner');
                    var text   = document.getElementById('newPostBannerText');
                    if (banner && text) {
                        text.textContent = data.auteur + ' vient de publier : ' + data.titre;
                        banner.style.display = 'flex';
                    }
                    var badge = document.getElementById('newPubsBadge');
                    if (badge) {
                        var current = parseInt(badge.textContent) || 0;
                        var newCount = current + 1;
                        badge.textContent = newCount > 9 ? '9+' : newCount;
                        badge.style.display = 'inline-flex';
                    }
                });
        });
        function confirmerDeconnexion() {
            document.getElementById('modalDeconnexion').style.display = 'flex';
        }
        function fermerModalDeconnexion() {
            document.getElementById('modalDeconnexion').style.display = 'none';
        }
        document.getElementById('modalDeconnexion')?.addEventListener('click', function(e) {
            if (e.target === this) fermerModalDeconnexion();
        });        
    </script>

    <style>
        @keyframes slideInToast {
            from { opacity:0; transform:translateY(20px); }
            to   { opacity:1; transform:translateY(0); }
        }
    </style>
    @endauth
</body>
</html>