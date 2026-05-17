<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration – DevHub')</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        window.APP_URL    = "{{ url('') }}";
        window.CSRF_TOKEN = "{{ csrf_token() }}";
    </script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('styles')
</head>
<body>

    <div class="admin-wrapper">

        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">

            <a href="{{ route('admin.dashboard') }}" class="admin-logo">
                <span><img src="{{ asset('img/logo-devhub.png') }}" alt="Logo de DevHub" class="admin-logo-icon"></span>
                <span class="admin-logo-text">DevHub</span>
                <span class="admin-logo-badge">ADMIN</span>
            </a>

            <div class="admin-user-info">
                <div class="admin-user-ava">
                    @if(Auth::user()->photo)
                        <img src="{{ Auth::user()->photo_url }}" alt="">
                    @else
                        {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <div class="admin-user-name">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                    <div class="admin-user-role">
                        <span class="dot"></span> Administrateur
                    </div>
                </div>
            </div>

            <nav class="admin-nav">
                <div class="admin-nav-section">Principal</div>

                <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Tableau de bord
                </a>

                <div class="admin-nav-section">Gestion</div>

                <a href="{{ route('admin.utilisateurs') }}"
                class="admin-nav-item {{ request()->routeIs('admin.utilisateurs') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Utilisateurs
                    @php $totalUsers = \App\Models\Utilisateur::whereIn('role',['développeur','mentor'])->where('statut','actif')->count(); @endphp
                    @if($totalUsers > 0)
                        <span class="admin-nav-badge green">{{ $totalUsers }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.candidatures.externes') }}"
                class="admin-nav-item {{ request()->routeIs('admin.candidatures.externes') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Candidatures externes
                    @php $pendingExternes = \App\Models\CandidatureMentorExterne::where('statut','en_attente')->count(); @endphp
                    @if($pendingExternes > 0)
                        <span class="admin-nav-badge orange">{{ $pendingExternes }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.publications') }}"
                class="admin-nav-item {{ request()->routeIs('admin.publications') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i> Publications
                </a>

                <div class="admin-nav-section">Système</div>

                <a href="{{ route('home') }}" target="_blank" class="admin-nav-item">
                    <i class="fas fa-external-link-alt"></i> Voir le site
                </a>
            </nav>

            <div class="admin-sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="button" onclick="confirmerDeconnexion()" class="btn-admin-logout">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main -->
        <div class="admin-main">
            <header class="admin-topbar">
                <button class="admin-sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="admin-topbar-title">
                    @yield('page_title', 'Administration')
                </div>
                <div class="admin-topbar-right">
                    <span style="font-size:0.75rem;color:var(--gray-400);" id="adminClock"></span>
                    <a href="{{ route('home') }}" target="_blank" class="admin-topbar-btn" title="Voir le site">
                        <i class="fas fa-globe"></i>
                    </a>
                </div>
            </header>

            <main class="admin-content">
                @if(session('success'))
                    <div class="admin-alert success"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="admin-alert error"><i class="fas fa-exclamation-circle"></i>{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <!-- ─── MODAL DE CONFIRMATION ─── -->
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

    <script>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateClock() {
            var el = document.getElementById('adminClock');
            if (!el) return;
            var now = new Date();
            el.textContent = now.toLocaleDateString('fr',{weekday:'short',day:'numeric',month:'short'})
                + ' · ' + now.toLocaleTimeString('fr',{hour:'2-digit',minute:'2-digit'});
        }
        updateClock();
        setInterval(updateClock, 60000);

        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.toggle('open');
        });
    </script>
    @yield('scripts')
</body>
</html>