<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Mentor – DevHub')</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        window.APP_URL    = "{{ url('') }}";
        window.CSRF_TOKEN = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    <style>
        :root {
            --purple:        #7C3AED;
            --purple-dark:   #6D28D9;
            --purple-light:  #EDE9FE;
            --purple-xlight: #F5F3FF;
            --purple-border: #DDD6FE;
            --white:         #ffffff;
            --gray-50:       #F9FAFB;
            --gray-100:      #F3F4F6;
            --gray-200:      #E5E7EB;
            --gray-400:      #9CA3AF;
            --gray-500:      #6B7280;
            --gray-700:      #374151;
            --gray-900:      #111827;
            --online:        #22C55E;
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html, body { height:100%; font-family:'DM Sans',sans-serif; background:var(--while); color:var(--gray-900); }

        .mentor-wrapper { display:flex; min-height:100vh; }

        /* ─── SIDEBAR ─── */
        .mentor-sidebar {
            width: 255px; background: white;
            border-right: 1px solid var(--purple-border);
            display: flex; flex-direction: column;
            flex-shrink: 0; position: fixed;
            top: 0; left: 0; bottom: 0; z-index: 100;
        }

        .mentor-logo {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.70rem 1.25rem;
            border-bottom: 1px solid var(--purple-border);
            text-decoration: none;
            background: var(--purple-xlight);
        }
        .mentor-logo-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            background: var(--white); font-size: 0.9rem;
        }
        .mentor-logo-text { font-family:'Urbanist',sans-serif; font-size:1rem; font-weight:800; color:var(--purple); }
        .mentor-logo-badge {
            margin-left: auto; background: #D1FAE5; color: #065F46;
            font-size: 0.58rem; font-weight: 700;
            padding: 0.15rem 0.5rem; border-radius: 4px; letter-spacing: 0.5px;
        }

        /* Info mentor */
        .mentor-user-info {
            display: flex; align-items: center; gap: 0.65rem;
            padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--gray-100);
        }
        .mentor-user-ava {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--purple-light); color: var(--purple);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.82rem; font-weight: 700; flex-shrink: 0; overflow: hidden;
            position: relative;
        }
        .mentor-user-ava img { width:100%; height:100%; object-fit:cover; }
        .mentor-online-dot {
            position: absolute; bottom: 0; right: 0;
            width: 9px; height: 9px; border-radius: 50%;
            background: var(--online); border: 2px solid white;
        }
        .mentor-user-name { font-size:0.82rem; font-weight:700; color:var(--gray-900); line-height:1.2; }
        .mentor-user-role { font-size:0.68rem; color:#065F46; font-weight:600; margin-top:0.1rem; }

        /* Nav */
        .mentor-nav { flex:1; overflow-y:auto; padding:0.65rem 0; }
        .mentor-nav-section {
            font-size:0.62rem; font-weight:700; color:var(--gray-400);
            text-transform:uppercase; letter-spacing:0.7px;
            padding:0.65rem 1.25rem 0.3rem;
        }
        .mentor-nav-item {
            display:flex; align-items:center; gap:0.65rem;
            padding:0.6rem 1.25rem; color:var(--gray-500);
            text-decoration:none; font-size:0.875rem; font-weight:500;
            transition:all 0.15s; margin:0 0.5rem; border-radius:9px;
        }
        .mentor-nav-item:hover { background:var(--purple-xlight); color:var(--purple); }
        .mentor-nav-item.active { background:var(--purple-light); color:var(--purple); font-weight:700; }
        .mentor-nav-item i { width:16px; text-align:center; font-size:0.82rem; flex-shrink:0; }

        .mentor-nav-badge {
            margin-left:auto; font-size:0.6rem; font-weight:700;
            min-width:18px; height:18px; border-radius:9px;
            display:flex; align-items:center; justify-content:center; padding:0 4px;
            background:var(--purple-light); color:var(--purple);
        }

        /* Footer */
        .mentor-sidebar-footer {
            padding:0.85rem 1.25rem; border-top:1px solid var(--gray-100);
            display: flex; flex-direction: column; gap: 0.4rem;
        }
        .btn-mentor-dashboard {
            display:flex; align-items:center; gap:0.6rem;
            width:100%; padding:0.55rem 0.85rem;
            background:var(--purple-light); color:var(--purple);
            border:1px solid var(--purple-border); border-radius:9px;
            font-size:0.82rem; font-weight:600; cursor:pointer;
            font-family:'DM Sans',sans-serif; transition:all 0.18s;
            text-decoration: none;
        }
        .btn-mentor-dashboard:hover { background:var(--purple); color:white; }
        .btn-mentor-logout {
            display:flex; align-items:center; gap:0.6rem;
            width:100%; padding:0.55rem 0.85rem;
            background:#FEE2E2; color:#DC2626;
            border:1px solid #FECACA; border-radius:9px;
            font-size:0.82rem; font-weight:600; cursor:pointer;
            font-family:'DM Sans',sans-serif; transition:all 0.18s;
        }
        .btn-mentor-logout:hover { background:#DC2626; color:white; border-color:#DC2626; }

        /* ─── MAIN ─── */
        .mentor-main { margin-left:255px; flex:1; display:flex; flex-direction:column; min-height:100vh; }

        /* Topbar */
        .mentor-topbar {
            height: 60px;
            background: var(--purple-xlight);
            border-bottom: 1px solid var(--gray-200);
            display: flex; align-items: center;
            padding: 0 1.5rem; gap: 1rem;
            position: sticky; top: 0; z-index: 50;
        }
        .mentor-topbar-title {
            font-family:'Urbanist',sans-serif;
            font-size:0.95rem; font-weight:700; color:var(--gray-900); flex:1;
        }
        .mentor-topbar-right { display:flex; align-items:center; gap:0.65rem; }
        .mentor-topbar-btn {
            width:34px; height:34px; border-radius:8px;
            background:var(--gray-100); color:var(--gray-500);
            display:flex; align-items:center; justify-content:center;
            font-size:0.82rem; cursor:pointer; border:none; transition:all 0.18s;
            text-decoration:none; position:relative;
        }
        .mentor-topbar-btn:hover { background:var(--purple-light); color:var(--purple); }

        /* Contenu */
        .mentor-content { flex:1; padding:1.5rem; }

        /* Alertes */
        .mentor-alert { border-radius:10px; padding:0.75rem 1rem; margin-bottom:1rem; font-size:0.875rem; display:flex; align-items:center; gap:0.6rem; }
        .mentor-alert.success { background:#D1FAE5; color:#065F46; border:1px solid #A7F3D0; }
        .mentor-alert.error   { background:#FEE2E2; color:#991B1B; border:1px solid #FECACA; }

        /* Mobile */
        .mentor-sidebar-toggle { display:none; background:none; border:none; color:var(--gray-500); font-size:1.1rem; cursor:pointer; }

        @media (max-width:992px) {
            .mentor-sidebar { transform:translateX(-100%); transition:transform 0.3s ease; }
            .mentor-sidebar.open { transform:translateX(0); }
            .mentor-main { margin-left:0; }
            .mentor-sidebar-toggle { display:flex; align-items:center; }
        }
    </style>
    @yield('styles')
</head>
<body>

<div class="mentor-wrapper">

    <!-- Sidebar -->
    <aside class="mentor-sidebar" id="mentorSidebar">

        <a href="{{ route('mentor.espace') }}" class="mentor-logo">
            <span><img src="{{ asset('img/logo-devhub.png') }}" alt="Logo de DevHub" class="mentor-logo-icon"></span>
            <span class="mentor-logo-text">DevHub</span>
            <span class="mentor-logo-badge">MENTOR</span>
        </a>

        <div class="mentor-user-info">
            <div class="mentor-user-ava">
                @if(Auth::user()->photo)
                    <img src="{{ Auth::user()->photo_url }}" alt="">
                @else
                    {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}
                @endif
                <span class="mentor-online-dot"></span>
            </div>
            <div>
                <div class="mentor-user-name">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                <div class="mentor-user-role"><i class="fas fa-circle" style="font-size:0.5rem;"></i> Mentor certifié</div>
            </div>
        </div>

        <nav class="mentor-nav">
            <div class="mentor-nav-section">Mon espace</div>

            <a href="{{ route('mentor.espace') }}"
               class="mentor-nav-item {{ request()->routeIs('mentor.espace') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Tableau de bord
            </a>

            <a href="{{ route('messagerie.index') }}"
               class="mentor-nav-item {{ request()->routeIs('messagerie.*') ? 'active' : '' }}">
                <i class="fas fa-comment-dots"></i> Messagerie
                @php
                    $nonLusMentor = \App\Models\Message::whereHas('conversation', function($q) {
                        $q->whereHas('participants', function($q2) {
                            $q2->where('utilisateurs.id', Auth::id());
                        });
                    })->where('expediteur_id', '!=', Auth::id())->where('est_lu', false)->count();
                @endphp
                @if($nonLusMentor > 0)
                    <span class="mentor-nav-badge">{{ $nonLusMentor }}</span>
                @endif
            </a>

            <div class="mentor-nav-section">Compte</div>

            <a href="{{ route('profil.show', Auth::id()) }}"
               class="mentor-nav-item">
                <i class="fas fa-user"></i> Mon profil
            </a>
        </nav>

        <div class="mentor-sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="button" class="btn-mentor-logout" onclick="confirmerDeconnexion()" 
                        style="...garder le style existant...">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="mentor-main">
        <header class="mentor-topbar">
            <button class="mentor-sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="mentor-topbar-title">@yield('page_title', 'Espace Mentor')</div>
            <div class="mentor-topbar-right">
                <span style="font-size:0.75rem;color:var(--gray-400);" id="mentorClock"></span>
                <a href="{{ route('messagerie.index') }}" class="mentor-topbar-btn" title="Messagerie" style="position:relative;">
                    <i class="fas fa-comment-dots"></i>
                    @if($nonLusMentor > 0)
                        <span style="position:absolute;top:4px;right:4px;width:8px;height:8px;border-radius:50%;background:#DC2626;border:1.5px solid white;"></span>
                    @endif
                </a>
            </div>
        </header>

        <main class="mentor-content">
            @if(session('success'))
                <div class="mentor-alert success"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mentor-alert error"><i class="fas fa-exclamation-circle"></i>{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateClock() {
            var el = document.getElementById('mentorClock');
            if (!el) return;
            var now = new Date();
            el.textContent = now.toLocaleDateString('fr',{weekday:'short',day:'numeric',month:'short'})
                + ' · ' + now.toLocaleTimeString('fr',{hour:'2-digit',minute:'2-digit'});
        }
        updateClock();
        setInterval(updateClock, 60000);

        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('mentorSidebar').classList.toggle('open');
        });
    </script>
    <script>
        window.addEventListener('load', function() {
            if (!window.Echo) return;
            window.Echo.private('notifications.{{ Auth::id() }}')
                .listen('.notification.creee', function(data) {
                    // Toast notification
                    var toast = document.createElement('div');
                    toast.style.cssText = 'position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;background:#7C3AED;color:white;padding:.75rem 1.25rem;border-radius:12px;font-size:.85rem;font-weight:500;box-shadow:0 4px 20px rgba(124,58,237,0.3);display:flex;align-items:center;gap:.5rem;max-width:320px;cursor:pointer;';
                    toast.innerHTML = '<i class="fas fa-bell"></i><span>' + data.contenu + '</span>';
                    if (data.lien) toast.addEventListener('click', function() { window.location.href = data.lien; });
                    document.body.appendChild(toast);
                    setTimeout(function() { if (toast.parentNode) toast.remove(); }, 6000);
                });
        });
    </script>
@yield('scripts')
</body>
</html>