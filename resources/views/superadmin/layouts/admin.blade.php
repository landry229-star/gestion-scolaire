<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UAC • Super Administration')</title>

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        :root{
            --sidebar-width: 270px;

            --primary: #111827;
            --primary-light: #1f2937;

            --accent: #2563eb;
            --accent-light: #eff6ff;

            --success: #16a34a;
            --warning: #f59e0b;
            --danger: #dc2626;

            --bg: #f8fafc;
            --white: #ffffff;

            --text: #111827;
            --muted: #6b7280;

            --border: #e5e7eb;
        }

        body{
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        /* =====================================================
           SIDEBAR
        ===================================================== */

        .sidebar{
            position: fixed;
            top:0;
            left:0;

            width: var(--sidebar-width);
            height:100vh;

            background: linear-gradient(
                180deg,
                #0f172a 0%,
                #111827 100%
            );

            color:white;

            display:flex;
            flex-direction:column;

            z-index:1000;

            border-right:1px solid rgba(255,255,255,0.05);
        }

        .sidebar-header{
            padding:24px 22px;
            border-bottom:1px solid rgba(255,255,255,0.06);
        }

        .brand{
            display:flex;
            align-items:center;
            gap:14px;
        }

        .brand-logo{
            width:45px;
            height:45px;

            border-radius:12px;

            background: linear-gradient(
                135deg,
                #2563eb,
                #3b82f6
            );

            display:flex;
            align-items:center;
            justify-content:center;

            color:white;
            font-size:18px;

            box-shadow:0 10px 20px rgba(37,99,235,0.25);
        }

        .brand-title{
            font-size:16px;
            font-weight:700;
            margin-bottom:2px;
        }

        .brand-subtitle{
            font-size:11px;
            letter-spacing:1px;
            text-transform:uppercase;
            color:rgba(255,255,255,0.45);
        }

        /* =====================================================
           NAVIGATION
        ===================================================== */

        .sidebar-nav{
            padding:20px 14px;
            overflow-y:auto;
            flex:1;
        }

        .nav-section{
            margin-bottom:28px;
        }

        .nav-section-title{
            font-size:11px;
            text-transform:uppercase;
            letter-spacing:1.5px;
            color:rgba(255,255,255,0.35);

            padding:0 14px;
            margin-bottom:10px;
        }

        .sidebar-link{
            display:flex;
            align-items:center;
            gap:12px;

            text-decoration:none;

            color:rgba(255,255,255,0.68);

            padding:12px 14px;

            border-radius:12px;

            transition:all .2s ease;

            font-size:14px;
            font-weight:500;

            margin-bottom:4px;

            position:relative;
        }

        .sidebar-link i{
            width:18px;
            text-align:center;
            font-size:15px;
        }

        .sidebar-link:hover{
            background:rgba(255,255,255,0.06);
            color:white;
            transform:translateX(2px);
        }

        .sidebar-link.active{
            background: linear-gradient(
                90deg,
                rgba(37,99,235,0.22),
                rgba(37,99,235,0.08)
            );

            color:white;

            border:1px solid rgba(59,130,246,0.2);
        }

        .sidebar-link.active::before{
            content:"";
            position:absolute;
            left:0;
            top:10px;

            width:4px;
            height:24px;

            border-radius:20px;

            background:#3b82f6;
        }

        .nav-badge{
            margin-left:auto;

            background:#dc2626;
            color:white;

            font-size:10px;
            font-weight:700;

            padding:3px 7px;

            border-radius:20px;
        }

        /* =====================================================
           USER FOOTER
        ===================================================== */

        .sidebar-footer{
            padding:18px 14px;
            border-top:1px solid rgba(255,255,255,0.06);
        }

        .user-box{
            display:flex;
            align-items:center;
            gap:12px;

            padding:10px;
            margin-bottom:12px;
        }

        .user-avatar{
            width:42px;
            height:42px;

            border-radius:50%;

            background:linear-gradient(
                135deg,
                #2563eb,
                #3b82f6
            );

            display:flex;
            align-items:center;
            justify-content:center;

            font-weight:700;
            font-size:14px;
            color:white;
        }

        .user-name{
            font-size:13px;
            font-weight:600;
            color:white;
        }

        .user-role{
            font-size:11px;
            color:rgba(255,255,255,0.45);
        }

        .btn-logout{
            width:100%;

            border:none;

            background:rgba(220,38,38,0.12);
            color:#fca5a5;

            padding:11px 14px;

            border-radius:10px;

            font-size:13px;
            font-weight:600;

            transition:.2s;

            display:flex;
            align-items:center;
            justify-content:center;
            gap:8px;
        }

        .btn-logout:hover{
            background:rgba(220,38,38,0.20);
            color:white;
        }

        /* =====================================================
           MAIN
        ===================================================== */

        .main-wrapper{
            margin-left:var(--sidebar-width);
            min-height:100vh;
        }

        /* =====================================================
           TOPBAR
        ===================================================== */

        .topbar{
            height:78px;

            background:white;

            border-bottom:1px solid var(--border);

            display:flex;
            align-items:center;
            justify-content:space-between;

            padding:0 32px;

            position:sticky;
            top:0;

            z-index:100;
        }

        .page-title{
            font-size:22px;
            font-weight:700;
            color:var(--text);
            margin-bottom:2px;
        }

        .page-subtitle{
            font-size:13px;
            color:var(--muted);
        }

        .topbar-right{
            display:flex;
            align-items:center;
            gap:14px;
        }

        .date-box{
            padding:9px 14px;

            border-radius:10px;

            border:1px solid var(--border);

            background:#f9fafb;

            font-size:13px;
            color:var(--muted);
        }

        /* =====================================================
           CONTENT
        ===================================================== */

        .content{
            padding:30px;
        }

        /* =====================================================
           CARDS
        ===================================================== */

        .card-modern{
            border:none;
            border-radius:18px;

            background:white;

            box-shadow:
                0 4px 10px rgba(15,23,42,0.03),
                0 1px 2px rgba(15,23,42,0.04);

            overflow:hidden;
        }

        .card-modern .card-header{
            background:white;
            border-bottom:1px solid var(--border);

            padding:18px 22px;

            font-weight:600;
        }

        .card-modern .card-body{
            padding:22px;
        }

        /* =====================================================
           TABLE
        ===================================================== */

        .table{
            margin-bottom:0;
        }

        .table thead{
            background:#f9fafb;
        }

        .table thead th{
            font-size:12px;
            text-transform:uppercase;
            letter-spacing:.5px;

            color:#6b7280;

            border:none;

            padding:14px 16px;
        }

        .table tbody td{
            padding:16px;
            vertical-align:middle;

            border-color:#f1f5f9;
        }

        /* =====================================================
           BUTTONS
        ===================================================== */

        .btn-primary-custom{
            background:linear-gradient(
                135deg,
                #2563eb,
                #3b82f6
            );

            border:none;

            color:white;

            padding:10px 18px;

            border-radius:10px;

            font-weight:600;
            font-size:14px;
        }

        .btn-primary-custom:hover{
            opacity:.92;
            color:white;
        }

        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media(max-width:992px){

            .sidebar{
                transform:translateX(-100%);
            }

            .main-wrapper{
                margin-left:0;
            }

        }

    </style>

    @stack('styles')

</head>

<body>

    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

    <aside class="sidebar">

        <div class="sidebar-header">

            <div class="brand">

                <div class="brand-logo">
                    <i class="fas fa-university"></i>
                </div>

                <div>
                    <div class="brand-title">UAC CONTROL</div>
                    <div class="brand-subtitle">
                        Super Administration
                    </div>
                </div>

            </div>

        </div>

        <div class="sidebar-nav">

            <div class="nav-section">

                <div class="nav-section-title">
                    Principal
                </div>

                <a href="{{ route('superadmin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">

                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>

                </a>

                <a href="{{ route('superadmin.inscriptions') }}"
                   class="sidebar-link {{ request()->routeIs('superadmin.inscriptions*') ? 'active' : '' }}">

                    <i class="fas fa-file-signature"></i>
                    <span>Inscriptions</span>

                    @isset($stats)
                        @if(($stats['inscriptions_attente'] ?? 0) > 0)
                            <span class="nav-badge">
                                {{ $stats['inscriptions_attente'] }}
                            </span>
                        @endif
                    @endisset

                </a>

            </div>

            <div class="nav-section">

                <div class="nav-section-title">
                    Gestion académique
                </div>

                <a href="{{ route('superadmin.enseignants') }}"
                   class="sidebar-link {{ request()->routeIs('superadmin.enseignants*') ? 'active' : '' }}">

                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Enseignants</span>

                </a>

                <a href="{{ route('superadmin.matieres') }}"
                   class="sidebar-link {{ request()->routeIs('superadmin.matieres*') ? 'active' : '' }}">

                    <i class="fas fa-book-open"></i>
                    <span>Matières & UE</span>

                </a>

                <a href="{{ route('superadmin.notes') }}"
                   class="sidebar-link {{ request()->routeIs('superadmin.notes*') ? 'active' : '' }}">

                    <i class="fas fa-graduation-cap"></i>
                    <span>Notes</span>

                </a>

                <a href="{{ route('superadmin.academics') }}"
                   class="sidebar-link {{ request()->routeIs('superadmin.academics*') ? 'active' : '' }}">

                    <i class="fas fa-sitemap"></i>
                    <span>Départements & Filières</span>

                </a>

                <a href="{{ route('superadmin.etudiants') }}"
                   class="sidebar-link {{ request()->routeIs('superadmin.etudiants*') ? 'active' : '' }}">

                    <i class="fas fa-users"></i>
                    <span>Étudiants</span>

                </a>

            </div>

        </div>

        <div class="sidebar-footer">

            <div class="user-box">

                <div class="user-avatar">

                    {{ strtoupper(substr(Auth::user()->nom,0,1)) }}
                    {{ strtoupper(substr(Auth::user()->prenom,0,1)) }}

                </div>

                <div>

                    <div class="user-name">
                        {{ Auth::user()->prenom }}
                        {{ Auth::user()->nom }}
                    </div>

                    <div class="user-role">
                        Super Administrateur
                    </div>

                </div>

            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </button>

            </form>

        </div>

    </aside>

    {{-- =====================================================
         MAIN CONTENT
    ====================================================== --}}

    <div class="main-wrapper">

        {{-- TOPBAR --}}
        <div class="topbar">

            <div>

                <div class="page-title">
                    @yield('page-title', 'Dashboard')
                </div>

                <div class="page-subtitle">
                    Université d’Abomey-Calavi • Administration
                </div>

            </div>

            <div class="topbar-right">

                <div class="date-box">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ now()->translatedFormat('d F Y') }}
                </div>

            </div>

        </div>

        {{-- PAGE CONTENT --}}
        <div class="content">

            {{-- ALERTS --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>
                </div>
            @endif

            @yield('content')

        </div>

    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>
