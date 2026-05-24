<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Enseignant') — GestionNotes</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome 6 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #0d0f14;
            --sidebar-border: rgba(255,255,255,.06);
            --topbar-height: 60px;
            --body-bg: #12151c;
            --card-bg: #181c26;
            --accent: #4f8ef7;
            --accent-soft: rgba(79,142,247,.12);
            --accent-green: #2ecc8f;
            --accent-orange: #f7964f;
            --text-main: #e8eaf0;
            --text-muted: #5a6070;
            --border: rgba(255,255,255,.07);
            --font-display: 'Syne', sans-serif;
            --font-body: 'DM Sans', sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            background: var(--body-bg);
            color: var(--text-main);
            font-family: var(--font-body);
            font-size: 0.925rem;
        }

        /* ── WRAPPER ─────────────────────────────── */
        .layout-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ─────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: transform .28s cubic-bezier(.4,0,.2,1);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 22px 16px;
            border-bottom: 1px solid var(--sidebar-border);
            text-decoration: none;
        }
        .sidebar-brand .brand-icon {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; color: #fff;
            flex-shrink: 0;
        }
        .sidebar-brand .brand-text {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.05rem;
            color: var(--text-main);
            line-height: 1.1;
        }
        .sidebar-brand .brand-sub {
            font-size: 0.68rem;
            color: var(--text-muted);
            font-weight: 400;
            letter-spacing: .04em;
        }

        /* Profil mini dans sidebar */
        .sidebar-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 22px;
            border-bottom: 1px solid var(--sidebar-border);
        }
        .sidebar-profile .avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            border: 2px solid var(--accent);
        }
        .sidebar-profile .avatar-placeholder {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: var(--accent-soft);
            border: 2px solid var(--accent);
            display: flex; align-items: center; justify-content: center;
            color: var(--accent);
            font-size: 1rem;
            flex-shrink: 0;
        }
        .sidebar-profile .profile-name {
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 0.83rem;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-profile .profile-grade {
            font-size: 0.7rem;
            color: var(--accent);
            white-space: nowrap;
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 14px 12px;
        }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--sidebar-border); border-radius: 2px; }

        .nav-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 14px 10px 6px;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 12px;
            border-radius: 9px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all .18s ease;
            margin-bottom: 2px;
            position: relative;
        }
        .nav-item-link i {
            width: 18px;
            text-align: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        .nav-item-link:hover {
            background: var(--accent-soft);
            color: var(--text-main);
        }
        .nav-item-link.active {
            background: var(--accent-soft);
            color: var(--accent);
            font-weight: 600;
        }
        .nav-item-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }

        /* Badge dans nav */
        .nav-badge {
            margin-left: auto;
            background: var(--accent);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 20px;
        }

        /* Pied sidebar */
        .sidebar-footer {
            padding: 14px 12px;
            border-top: 1px solid var(--sidebar-border);
        }
        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 9px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            transition: all .18s;
        }
        .sidebar-footer a:hover {
            background: rgba(231,76,60,.1);
            color: #e74c3c;
        }

        /* ── TOPBAR ──────────────────────────────── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: rgba(18,21,28,.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 1030;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .topbar-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
        }
        .topbar-toggle:hover { color: var(--text-main); }

        .breadcrumb-custom {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            color: var(--text-muted);
        }
        .breadcrumb-custom .bc-current {
            color: var(--text-main);
            font-weight: 600;
            font-family: var(--font-display);
        }
        .breadcrumb-custom .bc-sep { opacity: .4; }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .topbar-icon-btn {
            position: relative;
            width: 36px; height: 36px;
            border-radius: 9px;
            background: rgba(255,255,255,.05);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted);
            cursor: pointer;
            transition: all .18s;
            text-decoration: none;
        }
        .topbar-icon-btn:hover {
            background: var(--accent-soft);
            color: var(--accent);
            border-color: var(--accent);
        }
        .topbar-icon-btn .notif-dot {
            position: absolute;
            top: 5px; right: 5px;
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--accent-orange);
            border: 1.5px solid var(--body-bg);
        }

        /* Session lock banner */
        .session-lock-banner {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            background: linear-gradient(90deg, rgba(247,150,79,.15), rgba(247,150,79,.05));
            border-bottom: 1px solid rgba(247,150,79,.3);
            padding: 8px 24px;
            font-size: 0.82rem;
            color: var(--accent-orange);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── CONTENU PRINCIPAL ───────────────────── */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            min-height: calc(100vh - var(--topbar-height));
            padding: 28px 28px 40px;
            width: calc(100% - var(--sidebar-width));
        }

        /* ── OVERLAY MOBILE ──────────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.55);
            z-index: 1039;
        }

        /* ── RESPONSIVE ──────────────────────────── */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(calc(-1 * var(--sidebar-width)));
            }
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 8px 0 32px rgba(0,0,0,.5);
            }
            .sidebar-overlay.open { display: block; }
            .topbar { left: 0; }
            .topbar-toggle { display: flex; align-items: center; justify-content: center; }
            .main-content { margin-left: 0; width: 100%; }
            .session-lock-banner { margin-left: 0; }
        }

        @media (max-width: 575px) {
            .main-content { padding: 18px 14px 32px; }
        }

        /* ── UTILITAIRES GLOBAUX ─────────────────── */
        .text-accent { color: var(--accent) !important; }
        .card { background: var(--card-bg); border: 1px solid var(--border); }
        .table-dark { --bs-table-bg: transparent; }
        .badge { font-weight: 600; letter-spacing: .02em; }

        /* Animations page */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .main-content > * {
            animation: fadeUp .3s ease both;
        }
        .main-content > *:nth-child(1) { animation-delay: .04s; }
        .main-content > *:nth-child(2) { animation-delay: .09s; }
        .main-content > *:nth-child(3) { animation-delay: .14s; }
        .main-content > *:nth-child(4) { animation-delay: .19s; }

        /* Flash messages */
        .flash-zone { position: fixed; bottom: 24px; right: 24px; z-index: 2000; width: 340px; }
        .flash-toast {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 18px;
            margin-top: 10px;
            display: flex; align-items: center; gap: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,.4);
            animation: fadeUp .3s ease;
        }
        .flash-toast.success { border-left: 3px solid var(--accent-green); }
        .flash-toast.error   { border-left: 3px solid #e74c3c; }
        .flash-toast.warning { border-left: 3px solid var(--accent-orange); }
        .flash-toast.info    { border-left: 3px solid var(--accent); }
    </style>

    @stack('styles')
</head>
<body>

<div class="layout-wrapper">

    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar" id="sidebar">

        {{-- Logo --}}
        <a href="{{ route('enseignant.index') }}" class="sidebar-brand">
            <div class="brand-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div>
                <div class="brand-text">GestionNotes</div>
                <div class="brand-sub">Espace Enseignant</div>
            </div>
        </a>

        {{-- Profil mini --}}
        @auth
        <div class="sidebar-profile">
            @if(auth()->user()->enseignant?->photo)
                <img src="{{ asset('storage/' . auth()->user()->enseignant->photo) }}"
                     class="avatar" alt="Photo">
            @else
                <div class="avatar-placeholder">
                    <i class="fas fa-user-tie"></i>
                </div>
            @endif
            <div style="overflow: hidden;">
                <div class="profile-name">
                    {{ auth()->user()->enseignant?->nom ?? auth()->user()->name }}
                    {{ auth()->user()->enseignant?->prenom ?? '' }}
                </div>
                <div class="profile-grade">
                    {{ auth()->user()->enseignant?->grade_enseignant ?? 'Enseignant' }}
                </div>
            </div>
        </div>
        @endauth

        {{-- Navigation --}}
        <nav class="sidebar-nav">
            <div class="nav-label">Principal</div>

            <a href="{{ route('enseignant.index') }}"
               class="nav-item-link {{ request()->routeIs('enseignant.index') ? 'active' : '' }}">
                <i class="fas fa-gauge-high"></i>
                Tableau de bord
            </a>

            <a href="#"
               class="nav-item-link {{ request()->routeIs('enseignant.notes.*') ? 'active' : '' }}">
                <i class="fas fa-pen-to-square"></i>
                Saisie des notes
            </a>

            <a href="#"
               class="nav-item-link {{ request()->routeIs('enseignant.etudiants.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                Mes étudiants
            </a>

            <div class="nav-label">Documents</div>

            <a href="#" class="nav-item-link">
                <i class="fas fa-file-excel"></i>
                Listes d'appel
            </a>

            <a href="#" class="nav-item-link" onclick="window.print(); return false;">
                <i class="fas fa-calendar-days"></i>
                Emploi du temps
            </a>

            <div class="nav-label">Compte</div>

            <a href="#" class="nav-item-link">
                <i class="fas fa-user-pen"></i>
                Mon profil
            </a>
        </nav>

        {{-- Footer sidebar --}}
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="fas fa-right-from-bracket"></i>
                    Déconnexion
                </a>
            </form>
        </div>
    </aside>

    {{-- Overlay mobile --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- ── TOPBAR ── --}}
    <header class="topbar">
        <div class="topbar-left">
            <button class="topbar-toggle" id="sidebarToggle" aria-label="Menu">
                <i class="fas fa-bars"></i>
            </button>
            <div class="breadcrumb-custom">
                <span>Enseignant</span>
                <span class="bc-sep">/</span>
                <span class="bc-current">@yield('page_title', 'Tableau de bord')</span>
            </div>
        </div>
        <div class="topbar-right">
            {{-- Notifications --}}
            <a href="#" class="topbar-icon-btn" title="Notifications">
                <i class="fas fa-bell" style="font-size: .9rem;"></i>
                <span class="notif-dot"></span>
            </a>
            {{-- Aide --}}
            <a href="#" class="topbar-icon-btn" title="Aide">
                <i class="fas fa-circle-question" style="font-size: .9rem;"></i>
            </a>
        </div>
    </header>

    {{-- Bannière session verrouillée (si applicable) --}}
    @if(isset($sessionVerrouillee) && $sessionVerrouillee)
    <div class="session-lock-banner">
        <i class="fas fa-lock"></i>
        <strong>Session clôturée</strong> — La saisie et la modification des notes sont désactivées.
    </div>
    @endif

    {{-- ── CONTENU ── --}}
    <main class="main-content">
        @yield('content')
    </main>

</div>

{{-- ── FLASH MESSAGES ── --}}
<div class="flash-zone" id="flashZone">
    @if(session('success'))
        <div class="flash-toast success">
            <i class="fas fa-circle-check text-success"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flash-toast error">
            <i class="fas fa-circle-xmark" style="color:#e74c3c;"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if(session('warning'))
        <div class="flash-toast warning">
            <i class="fas fa-triangle-exclamation" style="color:var(--accent-orange);"></i>
            <span>{{ session('warning') }}</span>
        </div>
    @endif
    @if(session('info'))
        <div class="flash-toast info">
            <i class="fas fa-circle-info" style="color:var(--accent);"></i>
            <span>{{ session('info') }}</span>
        </div>
    @endif
</div>

{{-- Bootstrap 5 JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // ── Sidebar toggle mobile ──────────────────────
    const sidebar        = document.getElementById('sidebar');
    const overlay        = document.getElementById('sidebarOverlay');
    const toggleBtn      = document.getElementById('sidebarToggle');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('open');
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    }

    toggleBtn?.addEventListener('click', () => {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });
    overlay?.addEventListener('click', closeSidebar);

    // ── Auto-dismiss flash toasts ──────────────────
    document.querySelectorAll('.flash-toast').forEach(toast => {
        setTimeout(() => {
            toast.style.transition = 'opacity .4s ease, transform .4s ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(8px)';
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    });
</script>

@stack('scripts')
</body>
</html>
