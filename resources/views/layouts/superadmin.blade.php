<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin')</title>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>

/* ======================================================
   RESET & ROOT
====================================================== */
*, *::before, *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

:root {
    /* Sidebar */
    --sidebar-w: 260px;
    --sidebar-bg: #0d1117;
    --sidebar-border: rgba(255,255,255,0.06);
    --sidebar-text: #8b949e;
    --sidebar-text-active: #f0f6fc;
    --sidebar-hover: rgba(255,255,255,0.05);
    --sidebar-active: rgba(59,130,246,0.15);
    --sidebar-accent: #3b82f6;

    /* Layout */
    --topbar-h: 64px;
    --bg: #f4f6fb;
    --white: #ffffff;

    /* Text */
    --text-primary: #0f172a;
    --text-secondary: #64748b;
    --text-muted: #94a3b8;

    /* Borders */
    --border: #e2e8f0;
    --border-focus: #3b82f6;

    /* Accent */
    --accent: #3b82f6;
    --accent-dark: #1d4ed8;
    --accent-soft: #eff6ff;

    /* Status */
    --green: #16a34a;
    --green-soft: #dcfce7;
    --green-text: #166534;
    --red: #dc2626;
    --red-soft: #fee2e2;
    --red-text: #991b1b;
    --purple: #7c3aed;
    --purple-soft: #ede9fe;
    --purple-text: #5b21b6;

    /* Shadows */
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,0.08);
    --shadow-lg: 0 8px 32px rgba(0,0,0,0.10);

    /* Radius */
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 18px;
    --radius-full: 9999px;

    /* Transitions */
    --transition: 0.2s ease;
}

/* ======================================================
   BASE
====================================================== */
body {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    color: var(--text-primary);
    display: flex;
    min-height: 100vh;
    -webkit-font-smoothing: antialiased;
}

/* ======================================================
   SIDEBAR
====================================================== */
.sidebar {
    width: var(--sidebar-w);
    background: var(--sidebar-bg);
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    display: flex;
    flex-direction: column;
    z-index: 200;
    border-right: 1px solid var(--sidebar-border);
    overflow: hidden;
}

/* Brand */
.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 20px;
    height: var(--topbar-h);
    border-bottom: 1px solid var(--sidebar-border);
    flex-shrink: 0;
}

.sidebar-brand-icon {
    width: 36px;
    height: 36px;
    background: var(--accent);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: white;
    flex-shrink: 0;
}

.sidebar-brand-name {
    font-size: 15px;
    font-weight: 700;
    color: var(--sidebar-text-active);
    letter-spacing: -0.02em;
}

/* Nav */
.sidebar-nav {
    flex: 1;
    padding: 16px 12px;
    display: flex;
    flex-direction: column;
    gap: 2px;
    overflow-y: auto;
}

.sidebar-nav a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-radius: var(--radius-md);
    color: var(--sidebar-text);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: background var(--transition), color var(--transition);
    position: relative;
}

.sidebar-nav a i {
    width: 18px;
    text-align: center;
    font-size: 15px;
    flex-shrink: 0;
}

.sidebar-nav a:hover {
    background: var(--sidebar-hover);
    color: var(--sidebar-text-active);
}

.sidebar-nav a.active {
    background: var(--sidebar-active);
    color: var(--sidebar-accent);
}

.sidebar-nav a.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 20px;
    background: var(--sidebar-accent);
    border-radius: 0 3px 3px 0;
}

/* Section label */
.nav-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #3d4a5c;
    padding: 12px 14px 6px;
}

/* Footer */
.sidebar-footer {
    padding: 14px 12px;
    border-top: 1px solid var(--sidebar-border);
    flex-shrink: 0;
}

.btn-logout {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    background: transparent;
    border: 1px solid rgba(239,68,68,0.25);
    color: #f87171;
    padding: 10px 14px;
    border-radius: var(--radius-md);
    font-size: 14px;
    font-weight: 500;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: background var(--transition), border-color var(--transition), color var(--transition);
}

.btn-logout:hover {
    background: rgba(239,68,68,0.1);
    border-color: rgba(239,68,68,0.45);
    color: #fca5a5;
}

/* ======================================================
   MAIN AREA
====================================================== */
.main {
    margin-left: var(--sidebar-w);
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;

    /* Ajout de l'image en arrière-plan */

    background-image: linear-gradient(rgba(25, 230, 25, 0.85), rgba(204, 33, 99, 0.185)), url('ton-image.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed; /* L'image reste fixe pendant le défilement */
}

/* ======================================================
   TOPBAR
====================================================== */
.topbar {
    height: var(--topbar-h);
    background: var(--white);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 28px;
    position: sticky;
    top: 0;
    z-index: 100;
}

.topbar-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
}

/* ======================================================
   CONTENT
====================================================== */
.content {
    padding: 28px;
    flex: 1;
}

/* ======================================================
   PAGE HEADER
====================================================== */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 28px;
}

.page-title {
    font-size: 26px;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.page-sub {
    color: var(--text-secondary);
    margin-top: 5px;
    font-size: 14px;
}

/* ======================================================
   ALERTS
====================================================== */
.alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 18px;
    border-radius: var(--radius-md);
    margin-bottom: 20px;
    font-size: 14px;
    font-weight: 500;
}

.alert-success {
    background: var(--green-soft);
    color: var(--green-text);
    border: 1px solid #86efac;
}

.alert-error {
    background: var(--red-soft);
    color: var(--red-text);
    border: 1px solid #fca5a5;
}

/* ======================================================
   STATS GRID
====================================================== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 28px;
}

.stat-box {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 22px;
    display: flex;
    align-items: center;
    gap: 18px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border);
    transition: box-shadow var(--transition), transform var(--transition);
}

.stat-box:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-1px);
}

.stat-icon {
    width: 54px;
    height: 54px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: white;
    flex-shrink: 0;
}

.bg-blue   { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.bg-purple { background: linear-gradient(135deg, #a78bfa, #7c3aed); }
.bg-green  { background: linear-gradient(135deg, #4ade80, #16a34a); }
.bg-orange { background: linear-gradient(135deg, #fb923c, #ea580c); }

.stat-info h2 {
    font-size: 26px;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -0.03em;
    line-height: 1;
}

.stat-info p {
    margin-top: 5px;
    color: var(--text-secondary);
    font-size: 13px;
    font-weight: 500;
}

/* ======================================================
   CARDS
====================================================== */
.card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 24px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border);
}

.card h3 {
    margin-top: 0;
    margin-bottom: 20px;
    color: var(--text-primary);
    font-size: 16px;
    font-weight: 700;
    letter-spacing: -0.01em;
}

/* ======================================================
   GRID LAYOUTS
====================================================== */
.page-grid,
.grid {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 24px;
}

/* ======================================================
   FORMS
====================================================== */
.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary);
    letter-spacing: 0.01em;
}

.form-control,
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-md);
    outline: none;
    font-size: 14px;
    font-family: 'DM Sans', sans-serif;
    color: var(--text-primary);
    background: var(--white);
    transition: border-color var(--transition), box-shadow var(--transition);
}

.form-control:focus,
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: var(--border-focus);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
}

.form-control::placeholder,
.form-group input::placeholder {
    color: var(--text-muted);
}

/* ======================================================
   BUTTONS
====================================================== */
.btn-submit {
    width: 100%;
    border: none;
    padding: 13px;
    border-radius: var(--radius-md);
    background: var(--accent);
    color: white;
    font-size: 14px;
    font-weight: 600;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: background var(--transition), box-shadow var(--transition), transform var(--transition);
    letter-spacing: 0.01em;
}

.btn-submit:hover {
    background: var(--accent-dark);
    box-shadow: 0 4px 12px rgba(37,99,235,0.3);
    transform: translateY(-1px);
}

.btn-add {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--accent);
    color: white;
    border: none;
    padding: 11px 18px;
    border-radius: var(--radius-md);
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    font-family: 'DM Sans', sans-serif;
    text-decoration: none;
    transition: background var(--transition), box-shadow var(--transition), transform var(--transition);
}

.btn-add:hover {
    background: var(--accent-dark);
    box-shadow: 0 4px 12px rgba(37,99,235,0.3);
    transform: translateY(-1px);
}

.btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: none;
    background: var(--red-soft);
    color: var(--red);
    padding: 8px 12px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    font-family: 'DM Sans', sans-serif;
    transition: background var(--transition);
}

.btn-delete:hover {
    background: #fecaca;
}

/* ======================================================
   SEARCH BOX
====================================================== */
.search-box {
    margin-bottom: 18px;
    position: relative;
}

.search-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 14px;
}

.search-box input {
    width: 100%;
    padding: 11px 14px 11px 40px;
    border-radius: var(--radius-md);
    border: 1.5px solid var(--border);
    outline: none;
    font-size: 14px;
    font-family: 'DM Sans', sans-serif;
    background: var(--white);
    transition: border-color var(--transition), box-shadow var(--transition);
}

.search-box input:focus {
    border-color: var(--border-focus);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
}

/* ======================================================
   TABLE
====================================================== */
table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #f8fafc;
}

thead th {
    padding: 13px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 700;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    border-bottom: 1px solid var(--border);
}

tbody td {
    padding: 15px 16px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 14px;
    color: var(--text-primary);
    vertical-align: middle;
}

tbody tr:last-child td {
    border-bottom: none;
}

tbody tr {
    transition: background var(--transition);
}

tbody tr:hover {
    background: #f8fafc;
}

/* ======================================================
   BADGES
====================================================== */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 11px;
    border-radius: var(--radius-full);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.02em;
}

.badge-ma {
    background: var(--accent-soft);
    color: var(--accent-dark);
}

.badge-mc {
    background: var(--purple-soft);
    color: var(--purple-text);
}

.badge-pt {
    background: var(--green-soft);
    color: var(--green-text);
}

/* ======================================================
   ENSEIGNANT / AVATAR
====================================================== */
.enseignant-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    flex-shrink: 0;
}

/* ======================================================
   EMPTY STATE
====================================================== */
.empty {
    text-align: center;
    padding: 48px 20px;
    color: var(--text-muted);
}

.empty i {
    font-size: 40px;
    margin-bottom: 12px;
    display: block;
    opacity: 0.4;
}

.empty p {
    font-size: 14px;
}

/* ======================================================
   RESPONSIVE
====================================================== */
@media (max-width: 1024px) {
    .page-grid,
    .grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    :root {
        --sidebar-w: 0px;
    }

    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .main {
        margin-left: 0;
    }

    .stats-grid {
        grid-template-columns: 1fr 1fr;
    }

    .content {
        padding: 18px;
    }
}

@media (max-width: 500px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .page-header {
        flex-direction: column;
        gap: 14px;
    }
}

    </style>
</head>

<body>

<div class="sidebar">

    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="fas fa-shield-halved"></i>
        </div>
        <span class="sidebar-brand-name">Super Admin</span>
    </div>

    <nav class="sidebar-nav">

        <span class="nav-label">Navigation</span>

        <a href="{{ route('superadmin.dashboard') }}">
            <i class="fas fa-house"></i> Dashboard
        </a>

        <a href="{{ route('superadmin.inscriptions') }}">
            <i class="fas fa-user-plus"></i> Inscriptions
        </a>

        <a href="{{ route('superadmin.etudiants.index') }}">
            <i class="fas fa-users"></i> Étudiants
        </a>

        <a href="{{ route('superadmin.enseignants') }}">
            <i class="fas fa-chalkboard-teacher"></i> Enseignants
        </a>

        <span class="nav-label">Académique</span>

        <a href="{{ route('superadmin.notes') }}">
            <i class="fas fa-graduation-cap"></i> Notes
        </a>

        <a href="{{ route('superadmin.matieres') }}">
            <i class="fas fa-book-open"></i> Matières
        </a>

        <a href="{{ route('superadmin.academics') }}">
            <i class="fas fa-sitemap"></i> Structure académique
        </a>

    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-arrow-right-from-bracket"></i>
                Déconnexion
            </button>
        </form>
    </div>

</div>

<div class="main">

    <div class="topbar">
        <span class="topbar-title">@yield('title', 'Super Admin')</span>
    </div>

    <div class="content">
        @yield('content')
    </div>

</div>

</body>
</html>
