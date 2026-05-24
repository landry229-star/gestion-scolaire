<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Étudiant') — UAC</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:      #1a3a5c;
            --primary-lt:   #e8f0f8;
            --accent:       #c8872a;
            --accent-lt:    #fdf3e3;
            --success:      #1a7a4a;
            --success-lt:   #e6f5ed;
            --danger:       #b33030;
            --danger-lt:    #fdeaea;
            --warning:      #a06010;
            --warning-lt:   #fef3df;
            --neutral:      #5a6478;
            --neutral-lt:   #f0f2f5;
            --bg:           #f5f6fa;
            --surface:      #ffffff;
            --border:       #e2e6ed;
            --text:         #1a2235;
            --text-muted:   #6b7690;
            --sidebar-w:    255px;
            --radius:       10px;
            --radius-lg:    16px;
            --shadow:       0 1px 3px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.04);
            --shadow-md:    0 4px 12px rgba(0,0,0,.10), 0 8px 32px rgba(0,0,0,.06);
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            font-size: 14px;
            line-height: 1.6;
        }

        /* ── Sidebar Moderne ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: #0f243a; /* Un bleu plus profond de nuit */
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow: hidden;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo-icon {
            background: linear-gradient(135deg, var(--accent), #e59d38);
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 18px; font-weight: bold;
        }

        .sidebar-brand .school-name {
            font-family: 'DM Serif Display', serif;
            font-size: 16px;
            color: #ffffff;
            line-height: 1.2;
            letter-spacing: .01em;
        }

        .sidebar-brand .school-sub {
            font-size: 10px;
            color: rgba(255,255,255,.4);
            margin-top: 1px;
            text-transform: uppercase;
            letter-spacing: .06em;
            font-weight: 600;
        }

        /* Profil étudiant sidebar */
        .sidebar-profile {
            padding: 20px;
            background: rgba(0, 0, 0, 0.15);
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-profile .avatar {
            width: 44px; height: 44px;
            border-radius: 50%;
            background: rgba(255,255,255,.1);
            border: 2px solid rgba(255,255,255,.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; color: #fff; font-weight: 600;
            overflow: hidden; flex-shrink: 0;
        }

        .sidebar-profile .avatar img {
            width: 100%; height: 100%; object-fit: cover;
        }

        .sidebar-profile .profile-meta {
            min-width: 0;
        }

        .sidebar-profile .name {
            font-size: 13.5px; font-weight: 600; color: #fff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .sidebar-profile .matricule {
            font-size: 11px; color: rgba(255,255,255,.45);
            margin-top: 2px;
            font-family: monospace;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .nav-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: rgba(255,255,255,.3);
            padding: 14px 12px 6px;
            font-weight: 600;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            color: rgba(255,255,255,.65);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all .2s ease;
            margin-bottom: 3px;
        }

        .nav-link i { font-size: 18px; width: 22px; text-align: center; flex-shrink: 0; color: rgba(255,255,255,.4); }

        .nav-link:hover {
            background: rgba(255,255,255,.05);
            color: #fff;
        }
        .nav-link:hover i { color: rgba(255,255,255,.8); }

        .nav-link.active {
            background: var(--accent);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(200, 135, 42, 0.25);
        }
        .nav-link.active i { color: #fff; }

        .nav-link .badge-nav {
            margin-left: auto;
            background: var(--success);
            color: #fff;
            font-size: 9.5px;
            padding: 2px 7px;
            border-radius: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .02em;
        }

        /* ── Main content ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            letter-spacing: -0.01em;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* ── Page content ── */
        .page-content {
            padding: 32px;
            flex: 1;
        }

        /* ── Cards ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
        }

        .card-header {
            padding: 18px 22px 14px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title i { color: var(--primary); font-size: 18px; }

        .card-body { padding: 20px 22px; }

        /* ── Alerts ── */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius);
            font-size: 13.5px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 24px;
            border: 1px solid transparent;
            box-shadow: 0 2px 4px rgba(0,0,0,0.01);
        }

        .alert-success { background: var(--success-lt); color: var(--success); border-color: #b8dfc9; }
        .alert-error   { background: var(--danger-lt);  color: var(--danger);  border-color: #f0c0c0; }
        .alert-warning { background: var(--warning-lt); color: var(--warning); border-color: #f0d8a0; }
        .alert-info    { background: var(--primary-lt); color: var(--primary); border-color: #b8cfe8; }
        .alert i { font-size: 20px; flex-shrink: 0; margin-top: -1px; }

        /* ── Status badges ── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.valide       { background: var(--success-lt); color: var(--success); }
        .status-badge.en_attente   { background: var(--warning-lt); color: var(--warning); }
        .status-badge.rejete       { background: var(--danger-lt);  color: var(--danger);  }
        .status-badge.non_soumis   { background: var(--neutral-lt); color: var(--neutral); }

        /* ── Boutons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 20px;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all .15s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }

        .btn:hover  { filter: brightness(.94); transform: translateY(-1px); box-shadow: 0 4px 8px rgba(0,0,0,0.06); }
        .btn:active { transform: translateY(0); }

        .btn-primary { background: var(--primary); color: #fff; }
        .btn-success { background: var(--success); color: #fff; }
        .btn-accent  { background: var(--accent);  color: #fff; }
        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 1.5px solid var(--border);
            box-shadow: none;
        }

        .btn-sm { padding: 6px 14px; font-size: 12.5px; border-radius: 6px; }

        /* ── Form elements ── */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            color: var(--text);
            background: var(--surface);
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,58,92,.08);
        }

        .form-control:disabled {
            background: var(--bg);
            color: var(--text-muted);
            cursor: not-allowed;
        }

        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

        .invalid-feedback { font-size: 12px; color: var(--danger); margin-top: 4px; }
        .is-invalid { border-color: var(--danger) !important; }

        /* ── Tables ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--text-muted);
            background: var(--neutral-lt);
            border-bottom: 1px solid var(--border);
            font-weight: 600;
        }

        tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            font-size: 13.5px;
            color: var(--text);
        }

        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #fafbfc; }

        /* ── File upload zone ── */
        .file-zone {
            border: 2px dashed var(--border);
            border-radius: 10px;
            padding: 18px;
            text-align: center;
            cursor: pointer;
            transition: border-color .15s, background .15s;
        }

        .file-zone:hover { border-color: var(--primary); background: var(--primary-lt); }
        .file-zone input[type="file"] { display: none; }
        .file-zone .file-label { font-size: 12.5px; color: var(--text-muted); }
        .file-zone .file-name { font-size: 12px; color: var(--primary); margin-top: 4px; font-weight: 500; }
        .file-zone .existing { font-size: 11.5px; color: var(--success); display: flex; align-items: center; gap: 4px; justify-content: center; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform .25s; }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .topbar { padding: 0 20px; }
            .page-content { padding: 20px; }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- ── SIDEBAR MULTI-STATUT ── --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo-icon">U</div>
            <div>
                <div class="school-name">Abomey-Calavi</div>
                <div class="school-sub">Portail Étudiant</div>
            </div>
        </div>

        @php
            $etudiant = auth()->user()?->etudiant ?? null;
            // Récupération globale sécurisée pour éviter le crash sur les pages sans variable $inscription
            $etatDossier = $etudiant ? \App\Models\Inscription::where('id_etudiant', $etudiant->id_etudiant)->latest()->first() : null;
        @endphp

        <div class="sidebar-profile">
            <div class="avatar">
                @if($etudiant?->photo)
                    <img src="{{ asset('storage/' . $etudiant->photo) }}" alt="Photo">
                @else
                    {{ strtoupper(substr($etudiant?->prenom ?? 'E', 0, 1)) }}
                @endif
            </div>
            <div class="profile-meta">
                <div class="name">{{ $etudiant ? ($etudiant->nom . ' ' . $etudiant->prenom) : auth()->user()->name }}</div>
                <div class="matricule">{{ $etudiant?->matricule ?? 'Sans Matricule' }}</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Mon Cursus</div>

            <a href="{{ route('etudiant.dashboard') }}"
               class="nav-link {{ request()->routeIs('etudiant.dashboard') ? 'active' : '' }}">
                <i class="ti ti-layout-dashboard"></i> Tableau de bord
            </a>

            <a href="{{ route('etudiant.dossier') }}"
               class="nav-link {{ request()->routeIs('etudiant.dossier') ? 'active' : '' }}">
                <i class="ti ti-folder-open"></i> Mon dossier d'inscription
            </a>

            <a href="{{ route('etudiant.notes') }}"
               class="nav-link {{ request()->routeIs('etudiant.notes') ? 'active' : '' }}">
                <i class="ti ti-chart-bar"></i> Mes notes & Crédits
            </a>

            <a href="{{ route('etudiant.examens') }}"
               class="nav-link {{ request()->routeIs('etudiant.examens') ? 'active' : '' }}">
                <i class="ti ti-calendar-event"></i> Horaires Examens
            </a>

            <div class="nav-label">Secrétariat Numérique</div>

            @if($etatDossier && $etatDossier->statut_dossier !== 'non_soumis')

    {{-- 1. Fiche de Pré-inscription : Toujours accessible si le dossier est EN COURS ou VALIDÉ --}}
    @if($etatDossier->statut_dossier === 'en_cours' || $etatDossier->statut_dossier === 'valide')
        <a href="{{ route('etudiant.pdf.pre_inscription') }}" class="nav-link" target="_blank">
            <i class="ti ti-download" style="color: var(--accent);"></i> Fiche Pré-inscription
            @if($etatDossier->statut_dossier === 'valide')
                <span class="badge-nav" style="background: var(--success-lt); color: var(--success);">Archivée</span>
            @else
                <span class="badge-nav" style="background: var(--warning-lt); color: var(--warning);">Provisoire</span>
            @endif
        </a>
    @endif

    {{-- 2. Documents Officiels : Débloqués uniquement si VALIDÉ --}}
    @if($etatDossier->statut_dossier === 'valide')
        <a href="{{ route('etudiant.pdf.inscription_definitive') }}" class="nav-link" target="_blank">
            <i class="ti ti-file-certificate" style="color: var(--success);"></i> Fiche Inscription
            <span class="badge-nav" style="background: var(--success-lt); color: var(--success);">Validée</span>
        </a>

        {{-- CORRIGÉ ICI : Utilisation du nom de route exact défini précédemment --}}
        <a href="{{ route('etudiant.releve.pdf') }}" class="nav-link" target="_blank">
            <i class="ti ti-file-text"></i> Relevé de notes provisoire
        </a>
    @endif

@else
    {{-- 3. CAS VIDE / NON SOUMIS --}}
    <span class="nav-link" style="opacity: 0.5; cursor: not-allowed;">
        <i class="ti ti-lock"></i> Aucun document éditable
    </span>
@endif

            <div class="nav-label">Sécurité</div>

            <a href="{{ route('etudiant.parametres') }}"
               class="nav-link {{ request()->routeIs('etudiant.parametres') ? 'active' : '' }}">
                <i class="ti ti-settings"></i> Paramètres du compte
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="width:100%; background: none; text-align:left; cursor:pointer; border: none;">
                    <i class="ti ti-logout"></i> Fermer la session
                </button>
            </form>
        </nav>
    </aside>

    {{-- ── MAIN AREA ── --}}
    <div class="main">
        <header class="topbar">
            <div class="topbar-title">@yield('page-title', 'Tableau de bord')</div>
            <div class="topbar-right">
                @yield('topbar-actions')
            </div>
        </header>

        <main class="page-content">
            {{-- Système d'alertes unifié --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="ti ti-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="ti ti-alert-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <i class="ti ti-alert-circle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        // Traitement automatique des libellés de fichiers d'envoi
        document.querySelectorAll('.file-zone').forEach(zone => {
            const input = zone.querySelector('input[type="file"]');
            const label = zone.querySelector('.file-name');
            if (input && label) {
                zone.addEventListener('click', () => input.click());
                input.addEventListener('change', () => {
                    label.textContent = input.files[0]?.name ?? '';
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
