<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Espace Chef Scolarité — UAC</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --sidebar-w: 260px;
  --primary: #0f172a;
  --primary-mid: #1e293b;
  --accent: #6366f1;
  --accent-hover: #4f46e5;
  --accent-soft: #eef2ff;
  --green: #10b981;
  --green-soft: #d1fae5;
  --green-dark: #065f46;
  --red: #ef4444;
  --red-soft: #fee2e2;
  --red-dark: #991b1b;
  --yellow: #f59e0b;
  --yellow-soft: #fef3c7;
  --yellow-dark: #78350f;
  --blue: #3b82f6;
  --blue-soft: #dbeafe;
  --blue-dark: #1e3a8a;
  --text: #0f172a;
  --muted: #64748b;
  --border: #e2e8f0;
  --bg: #f8fafc;
  --white: #ffffff;
  --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
  --shadow: 0 4px 16px rgba(0,0,0,0.07), 0 2px 6px rgba(0,0,0,0.05);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'DM Sans', sans-serif;
  background: var(--bg);
  color: var(--text);
  display: flex;
  min-height: 100vh;
  font-size: 14px;
}

/* ━━━━━━━━━━━━━━━━━━━━━━ SIDEBAR ━━━━━━━━━━━━━━━━━━━━━━ */
.sidebar {
  width: var(--sidebar-w);
  background: var(--primary);
  position: fixed;
  top: 0; left: 0;
  height: 100vh;
  display: flex;
  flex-direction: column;
  z-index: 100;
  border-right: 1px solid rgba(255,255,255,0.05);
}

.sidebar-brand {
  padding: 24px 20px 18px;
  border-bottom: 1px solid rgba(255,255,255,0.07);
  display: flex;
  align-items: center;
  gap: 12px;
}

.brand-mark {
  width: 38px; height: 38px;
  background: linear-gradient(135deg, var(--accent), #818cf8);
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 17px; color: white;
  flex-shrink: 0;
}

.brand-text-wrap .brand-name {
  color: white; font-size: 15px; font-weight: 600; letter-spacing: -0.3px;
}
.brand-text-wrap .brand-role {
  color: rgba(255,255,255,0.38);
  font-size: 10.5px;
  text-transform: uppercase;
  letter-spacing: 1.2px;
  margin-top: 1px;
}

.nav-group { padding: 18px 12px 6px; }
.nav-label {
  color: rgba(255,255,255,0.28);
  font-size: 9.5px;
  text-transform: uppercase;
  letter-spacing: 1.8px;
  padding: 0 10px;
  margin-bottom: 6px;
}

.nav-link {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 12px;
  border-radius: 8px;
  color: rgba(255,255,255,0.55);
  text-decoration: none;
  font-size: 13.5px;
  font-weight: 400;
  transition: all 0.15s;
  margin-bottom: 2px;
  cursor: pointer;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
}
.nav-link i { width: 18px; font-size: 14px; text-align: center; }
.nav-link:hover { background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.9); }
.nav-link.active { background: rgba(99,102,241,0.2); color: #a5b4fc; }
.nav-link .badge {
  margin-left: auto;
  background: var(--accent);
  color: white;
  font-size: 10px;
  font-weight: 600;
  padding: 2px 7px;
  border-radius: 20px;
}

.sidebar-footer {
  margin-top: auto;
  padding: 14px 12px;
  border-top: 1px solid rgba(255,255,255,0.07);
}
.user-row {
  display: flex; align-items: center; gap: 10px;
  padding: 8px 10px;
  border-radius: 8px;
  margin-bottom: 8px;
}
.user-avatar {
  width: 34px; height: 34px;
  background: linear-gradient(135deg, var(--accent), #818cf8);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: white; font-size: 12px; font-weight: 600;
  flex-shrink: 0;
}
.user-info .user-name { color: white; font-size: 13px; font-weight: 500; }
.user-info .user-role { color: rgba(255,255,255,0.35); font-size: 11px; }
.btn-logout {
  display: flex; align-items: center; gap: 8px;
  width: 100%; padding: 9px 12px;
  background: rgba(239,68,68,0.1);
  color: #fca5a5;
  border: none; border-radius: 8px;
  font-size: 13px; cursor: pointer;
  font-family: inherit;
  transition: background 0.15s;
}
.btn-logout:hover { background: rgba(239,68,68,0.2); }

/* ━━━━━━━━━━━━━━━━━━━━━━ MAIN ━━━━━━━━━━━━━━━━━━━━━━ */
.main {
  margin-left: var(--sidebar-w);
  flex: 1;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.topbar {
  background: var(--white);
  border-bottom: 1px solid var(--border);
  padding: 0 32px;
  height: 62px;
  display: flex; align-items: center; justify-content: space-between;
  position: sticky; top: 0; z-index: 50;
}

.topbar-left .page-title { font-size: 17px; font-weight: 600; letter-spacing: -0.3px; }
.topbar-left .breadcrumb { font-size: 12px; color: var(--muted); margin-top: 1px; }
.breadcrumb span { color: var(--accent); }

.topbar-right { display: flex; align-items: center; gap: 10px; }
.notif-btn {
  width: 36px; height: 36px;
  border: 1px solid var(--border);
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  color: var(--muted); cursor: pointer;
  background: none;
  position: relative;
  transition: background 0.15s;
}
.notif-btn:hover { background: var(--bg); }
.notif-dot {
  position: absolute; top: 7px; right: 7px;
  width: 7px; height: 7px;
  background: var(--red); border-radius: 50%;
  border: 1.5px solid white;
}

/* ━━━━━━━━━━━━━━━━━━━━━━ CONTENT ━━━━━━━━━━━━━━━━━━━━━━ */
.content { padding: 28px 32px; flex: 1; }

/* ── ALERTS ── */
.alert {
  display: flex; align-items: center; gap: 10px;
  padding: 12px 16px;
  border-radius: 10px;
  font-size: 13.5px;
  margin-bottom: 18px;
  border: 1px solid;
}
.alert-success { background: var(--green-soft); color: var(--green-dark); border-color: #a7f3d0; }
.alert-error   { background: var(--red-soft);   color: var(--red-dark);   border-color: #fca5a5; }

/* ── STATS ── */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-bottom: 28px;
}
@media(max-width:900px){ .stats-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:500px){ .stats-grid { grid-template-columns: 1fr; } }

.stat-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  box-shadow: var(--shadow-sm);
  transition: box-shadow 0.2s, transform 0.2s;
}
.stat-card:hover { box-shadow: var(--shadow); transform: translateY(-1px); }
.stat-header { display: flex; align-items: center; justify-content: space-between; }
.stat-icon {
  width: 38px; height: 38px;
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  font-size: 16px;
}
.stat-trend {
  font-size: 11px; font-weight: 500;
  padding: 2px 8px; border-radius: 20px;
}
.stat-body .stat-value {
  font-size: 28px;
  font-weight: 600;
  letter-spacing: -0.5px;
  line-height: 1;
  font-family: 'DM Mono', monospace;
}
.stat-body .stat-label { font-size: 12.5px; color: var(--muted); margin-top: 4px; }

.stat-total  .stat-icon  { background: var(--blue-soft);   color: var(--blue); }
.stat-total  .stat-value { color: var(--blue); }
.stat-attente .stat-icon { background: var(--yellow-soft); color: var(--yellow); }
.stat-attente .stat-value{ color: var(--yellow); }
.stat-valide  .stat-icon { background: var(--green-soft);  color: var(--green); }
.stat-valide  .stat-value{ color: var(--green); }
.stat-rejete  .stat-icon { background: var(--red-soft);    color: var(--red); }
.stat-rejete  .stat-value{ color: var(--red); }

/* ── CARD BASE ── */
.card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 14px;
  overflow: hidden;
  box-shadow: var(--shadow-sm);
  margin-bottom: 24px;
}
.card-header {
  padding: 16px 22px;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
  gap: 10px;
}
.card-header-left { display: flex; align-items: center; gap: 10px; }
.card-header-icon {
  width: 32px; height: 32px;
  background: var(--accent-soft);
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  color: var(--accent); font-size: 14px;
}
.card-title { font-size: 14px; font-weight: 600; }
.card-body  { padding: 22px; }

/* ── TABS ── */
.tab-nav {
  display: flex; gap: 4px;
  background: var(--bg);
  padding: 5px;
  border-radius: 10px;
  margin-bottom: 22px;
  border: 1px solid var(--border);
  width: fit-content;
}
.tab-btn {
  padding: 8px 18px;
  border-radius: 7px;
  border: none;
  cursor: pointer;
  font-family: inherit;
  font-size: 13.5px;
  font-weight: 500;
  color: var(--muted);
  background: none;
  transition: all 0.15s;
  display: flex; align-items: center; gap: 7px;
}
.tab-btn.active {
  background: var(--white);
  color: var(--text);
  box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}
.tab-btn .tab-count {
  background: var(--accent-soft);
  color: var(--accent);
  font-size: 10.5px;
  font-weight: 600;
  padding: 1px 7px;
  border-radius: 20px;
}
.tab-btn.active .tab-count { background: var(--accent); color: white; }

.tab-content { display: none; }
.tab-content.active { display: block; }

/* ── TABLE ── */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
thead th {
  padding: 10px 16px;
  text-align: left;
  font-size: 10.5px;
  font-weight: 600;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: 0.6px;
  background: var(--bg);
  border-bottom: 1px solid var(--border);
}
tbody td {
  padding: 13px 16px;
  border-bottom: 1px solid var(--border);
  vertical-align: middle;
}
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover td { background: #fafbfd; }

.photo-thumb {
  width: 40px; height: 40px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid var(--border);
}
.avatar-placeholder {
  width: 40px; height: 40px;
  border-radius: 50%;
  background: var(--accent-soft);
  display: flex; align-items: center; justify-content: center;
  color: var(--accent); font-size: 16px;
}
.etu-name  { font-weight: 600; font-size: 13px; }
.etu-phone { font-size: 11px; color: var(--muted); margin-top: 2px; }
.etu-email { font-size: 13px; color: var(--muted); }

.pill {
  display: inline-flex; align-items: center;
  padding: 3px 10px; border-radius: 20px;
  font-size: 11.5px; font-weight: 500;
}
.pill-blue   { background: var(--blue-soft);   color: var(--blue-dark); }
.pill-green  { background: var(--green-soft);  color: var(--green-dark); }
.pill-yellow { background: var(--yellow-soft); color: var(--yellow-dark); }

.date-cell { font-size: 12.5px; color: var(--muted); font-family: 'DM Mono', monospace; }

.doc-btn {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 4px 10px;
  background: var(--bg);
  border: 1px solid var(--border);
  border-radius: 6px;
  font-size: 11.5px; font-weight: 500;
  color: var(--text);
  text-decoration: none;
  margin: 2px;
  transition: all 0.15s;
}
.doc-btn:hover { background: var(--accent-soft); border-color: var(--accent); color: var(--accent); }

.action-cell { display: flex; gap: 6px; }
.btn-action {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 7px 13px;
  border: none; border-radius: 7px;
  font-size: 12px; font-weight: 600;
  cursor: pointer; font-family: inherit;
  transition: all 0.15s;
}
.btn-valider { background: var(--green-soft); color: var(--green-dark); }
.btn-valider:hover { background: var(--green); color: white; }
.btn-rejeter { background: var(--red-soft); color: var(--red-dark); }
.btn-rejeter:hover { background: var(--red); color: white; }

/* ── FORM ── */
.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 14px;
  margin-top: 14px;
}
@media(max-width:600px){ .form-grid { grid-template-columns: 1fr; } }
.form-group { display: flex; flex-direction: column; gap: 5px; }
.form-label {
  font-size: 11px; font-weight: 600;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: 0.6px;
}
input, select, textarea {
  padding: 10px 13px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 13.5px;
  font-family: inherit;
  color: var(--text);
  background: var(--white);
  transition: border 0.15s, box-shadow 0.15s;
  width: 100%;
}
input:focus, select:focus, textarea:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
}
.btn-primary-full {
  width: 100%;
  padding: 11px;
  background: var(--accent);
  color: white;
  border: none; border-radius: 8px;
  font-size: 14px; font-weight: 600;
  cursor: pointer; font-family: inherit;
  margin-top: 6px;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  transition: background 0.15s;
}
.btn-primary-full:hover { background: var(--accent-hover); }

.btn-danger {
  background: var(--red-soft);
  color: var(--red-dark);
  border: none; border-radius: 7px;
  padding: 7px 12px;
  cursor: pointer; font-family: inherit;
  font-size: 12px; font-weight: 600;
  transition: all 0.15s;
}
.btn-danger:hover { background: var(--red); color: white; }

.divider { border: none; border-top: 1px solid var(--border); margin: 18px 0; }

/* ── EMPTY STATE ── */
.empty-state {
  text-align: center;
  padding: 48px;
  color: var(--muted);
}
.empty-state i { font-size: 32px; margin-bottom: 10px; display: block; opacity: 0.4; }
.empty-state p { font-size: 14px; }

/* ── ALERT WARNING ── */
.alert-warning {
  background: var(--yellow-soft);
  color: var(--yellow-dark);
  border: 1px solid #fcd34d;
  border-left: 4px solid var(--yellow);
  border-radius: 10px;
  padding: 14px 16px;
  font-size: 13.5px;
  display: flex; align-items: center; gap: 10px;
}

/* ━━━━━━━━━━━━━━━━━━━━━━ MODAL ━━━━━━━━━━━━━━━━━━━━━━ */
.modal-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(15,23,42,0.55);
  z-index: 999; align-items: center; justify-content: center;
  backdrop-filter: blur(3px);
}
.modal-overlay.open { display: flex; }
.modal-box {
  background: var(--white);
  border-radius: 16px;
  padding: 28px;
  width: 92%; max-width: 440px;
  border: 1px solid var(--border);
  box-shadow: 0 24px 64px rgba(0,0,0,0.18);
  animation: slideUp 0.2s ease;
}
@keyframes slideUp {
  from { transform: translateY(12px); opacity: 0; }
  to   { transform: translateY(0);    opacity: 1; }
}
.modal-box .modal-header {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 18px;
}
.modal-box .modal-icon {
  width: 38px; height: 38px;
  background: var(--red-soft); border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  color: var(--red); font-size: 16px;
}
.modal-box .modal-title { font-size: 15px; font-weight: 600; }
.modal-actions {
  display: flex; gap: 10px;
  margin-top: 16px;
}
.modal-actions .btn-cancel {
  flex: 1; padding: 10px;
  background: none; color: var(--muted);
  border: 1px solid var(--border); border-radius: 8px;
  font-size: 13.5px; cursor: pointer; font-family: inherit;
  transition: background 0.15s;
}
.modal-actions .btn-cancel:hover { background: var(--bg); }
.modal-actions .btn-confirm {
  flex: 1; padding: 10px;
  background: var(--red); color: white;
  border: none; border-radius: 8px;
  font-size: 13.5px; font-weight: 600;
  cursor: pointer; font-family: inherit;
  transition: background 0.15s;
}
.modal-actions .btn-confirm:hover { background: #dc2626; }

/* ━━━━━━━━━━━━━━━━━━━━━━ RESPONSIVE ━━━━━━━━━━━━━━━━━━━━━━ */
@media(max-width:768px) {
  .sidebar { transform: translateX(-100%); transition: transform 0.25s; }
  .sidebar.open { transform: translateX(0); }
  .main { margin-left: 0; }
  .content { padding: 18px 16px; }
  .topbar { padding: 0 16px; }
  .tab-nav { width: 100%; }
  .tab-btn { flex: 1; justify-content: center; }
}
</style>
</head>
<body>

<!-- ════ SIDEBAR ════ -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-mark"><i class="fas fa-university"></i></div>
    <div class="brand-text-wrap">
      <div class="brand-name">UAC HUB</div>
      <div class="brand-role">Chef de Scolarité</div>
    </div>
  </div>

  <div class="nav-group">
    <div class="nav-label">Navigation</div>
    <button class="nav-link active" onclick="showTab('dossiers', this)">
      <i class="fas fa-folder-open"></i> Dossiers
      <span class="badge" id="badge-dossiers">{{ $total_en_attente }}</span>
    </button>
    <button class="nav-link" onclick="showTab('matieres', this)">
      <i class="fas fa-book-open"></i> Matières
    </button>
    <button class="nav-link" onclick="showTab('notes', this)">
      <i class="fas fa-graduation-cap"></i> Notes
    </button>
  </div>

  <div class="sidebar-footer">
    <div class="user-row">
      <div class="user-avatar">{{ strtoupper(substr($user->nom,0,1)) }}{{ strtoupper(substr($user->prenom,0,1)) }}</div>
      <div class="user-info">
        <div class="user-name">{{ $user->prenom }} {{ $user->nom }}</div>
        <div class="user-role">Chef de Scolarité</div>
      </div>
    </div>
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="btn-logout">
        <i class="fas fa-sign-out-alt"></i> Déconnexion
      </button>
    </form>
  </div>
</aside>

<!-- ════ MAIN ════ -->
<main class="main">

  <!-- TOPBAR -->
  <header class="topbar">
    <div class="topbar-left">
      <div class="page-title" id="page-title">Dossiers en attente</div>
      <div class="breadcrumb">UAC &rsaquo; <span id="breadcrumb-active">Validation des dossiers</span></div>
    </div>
    <div class="topbar-right">
      <button class="notif-btn" title="Notifications">
        <i class="fas fa-bell" style="font-size:15px;"></i>
        <span class="notif-dot"></span>
      </button>
    </div>
  </header>

  <!-- CONTENT -->
  <div class="content">

    {{-- ALERTES --}}
    @if(session('success'))
      <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- STATS --}}
    <div class="stats-grid">
      <div class="stat-card stat-total">
        <div class="stat-header">
          <div class="stat-icon"><i class="fas fa-users"></i></div>
          <span class="stat-trend" style="background:var(--blue-soft);color:var(--blue-dark);">Total</span>
        </div>
        <div class="stat-body">
          <div class="stat-value">{{ $total_etudiants }}</div>
          <div class="stat-label">Étudiants inscrits</div>
        </div>
      </div>
      <div class="stat-card stat-attente">
        <div class="stat-header">
          <div class="stat-icon"><i class="fas fa-clock"></i></div>
          <span class="stat-trend" style="background:var(--yellow-soft);color:var(--yellow-dark);">En attente</span>
        </div>
        <div class="stat-body">
          <div class="stat-value">{{ $total_en_attente }}</div>
          <div class="stat-label">Dossiers à traiter</div>
        </div>
      </div>
      <div class="stat-card stat-valide">
        <div class="stat-header">
          <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
          <span class="stat-trend" style="background:var(--green-soft);color:var(--green-dark);">Validés</span>
        </div>
        <div class="stat-body">
          <div class="stat-value">{{ $total_valides }}</div>
          <div class="stat-label">Dossiers acceptés</div>
        </div>
      </div>
      <div class="stat-card stat-rejete">
        <div class="stat-header">
          <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
          <span class="stat-trend" style="background:var(--red-soft);color:var(--red-dark);">Rejetés</span>
        </div>
        <div class="stat-body">
          <div class="stat-value">{{ $total_rejetes }}</div>
          <div class="stat-label">Dossiers refusés</div>
        </div>
      </div>
    </div>

    {{-- TAB NAV --}}
    <div class="tab-nav">
      <button class="tab-btn active" id="tabBtn-dossiers" onclick="showTab('dossiers', this)">
        <i class="fas fa-folder-open"></i> Dossiers
        <span class="tab-count">{{ $total_en_attente }}</span>
      </button>
      <button class="tab-btn" id="tabBtn-matieres" onclick="showTab('matieres', this)">
        <i class="fas fa-book"></i> Matières
      </button>
      <button class="tab-btn" id="tabBtn-notes" onclick="showTab('notes', this)">
        <i class="fas fa-pen"></i> Notes
      </button>
    </div>

    {{-- ══ ONGLET 1 : DOSSIERS ══ --}}
    <div id="tab-dossiers" class="tab-content active">
      <div class="card">
        <div class="card-header">
          <div class="card-header-left">
            <div class="card-header-icon"><i class="fas fa-inbox"></i></div>
            <span class="card-title">Dossiers en attente de validation</span>
          </div>
          <span class="pill pill-yellow">{{ $total_en_attente }} dossier(s)</span>
        </div>

        @if($dossiers->isEmpty())
          <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>Aucun dossier en attente. Tout est à jour ✓</p>
          </div>
        @else
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Photo</th>
                  <th>Étudiant</th>
                  <th>Email</th>
                  <th>Filière</th>
                  <th>Déposé le</th>
                  <th>Documents</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($dossiers as $d)
                <tr>
                  <td>
                    @if($d->etudiant->photo)
                      <img src="{{ asset('storage/'.$d->etudiant->photo) }}" class="photo-thumb" alt="">
                    @else
                      <div class="avatar-placeholder"><i class="fas fa-user"></i></div>
                    @endif
                  </td>
                  <td>
                    <div class="etu-name">{{ $d->etudiant->nom }} {{ $d->etudiant->prenom }}</div>
                    <div class="etu-phone">{{ $d->etudiant->telephone }}</div>
                  </td>
                  <td><span class="etu-email">{{ $d->etudiant->email }}</span></td>
                  <td><span class="pill pill-blue">{{ $d->filiere->nom_filiere ?? '—' }}</span></td>
                  <td><span class="date-cell">{{ $d->created_at->format('d/m/Y') }}</span></td>
                  <td>
                    @if($d->etudiant->document_cip)
                      <a href="{{ asset('storage/'.$d->etudiant->document_cip) }}" target="_blank" class="doc-btn">
                        <i class="fas fa-file-alt"></i> Acte
                      </a>
                    @endif
                    @if($d->etudiant->Diplome_bac)
                      <a href="{{ asset('storage/'.$d->etudiant->Diplome_bac) }}" target="_blank" class="doc-btn">
                        <i class="fas fa-graduation-cap"></i> BAC
                      </a>
                    @endif
                  </td>
                  <td>
                    <div class="action-cell">
                      <form action="{{ route('admin.valider', $d->id_inscription) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-action btn-valider">
                          <i class="fas fa-check"></i> Valider
                        </button>
                      </form>
                      <button class="btn-action btn-rejeter" onclick="openModal({{ $d->id_inscription }})">
                        <i class="fas fa-times"></i> Rejeter
                      </button>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>

    {{-- ══ ONGLET 2 : MATIÈRES ══ --}}
    <div id="tab-matieres" class="tab-content">
      <div class="card">
        <div class="card-header">
          <div class="card-header-left">
            <div class="card-header-icon"><i class="fas fa-plus"></i></div>
            <span class="card-title">Ajouter une matière</span>
          </div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.storeMatiere') }}" method="POST">
            @csrf
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Nom de la matière</label>
                <input type="text" name="nom_matiere" placeholder="Ex : Mathématiques" required>
              </div>
              <div class="form-group">
                <label class="form-label">Filière</label>
                <select name="id_filiere" required>
                  <option value="">Sélectionner une filière...</option>
                  @foreach($filieres as $f)
                    <option value="{{ $f->id_filiere }}">{{ $f->nom_filiere }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Crédits</label>
                <input type="number" name="credits" min="1" max="10" placeholder="3" required>
              </div>
              <div class="form-group">
                <label class="form-label">Semestre</label>
                <select name="semestre">
                  <option value="">—</option>
                  <option value="S1">Semestre 1</option>
                  <option value="S2">Semestre 2</option>
                </select>
              </div>
            </div>
            <button type="submit" class="btn-primary-full">
              <i class="fas fa-plus"></i> Ajouter la matière
            </button>
          </form>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-header-left">
            <div class="card-header-icon"><i class="fas fa-list"></i></div>
            <span class="card-title">Liste des matières</span>
          </div>
          <span class="pill pill-blue">{{ $matieres->count() }} matière(s)</span>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Matière</th><th>Filière</th><th>Crédits</th><th>Semestre</th><th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($matieres as $m)
              <tr>
                <td><strong>{{ $m->nom_matiere }}</strong></td>
                <td><span class="pill pill-blue">{{ $m->filiere->nom_filiere ?? '—' }}</span></td>
                <td><span class="pill pill-green">{{ $m->credits ?? '—' }} ECTS</span></td>
                <td>{{ $m->semestre ?? '—' }}</td>
                <td>
                  <form action="{{ route('admin.destroyMatiere', $m->id_matiere) }}" method="POST"
                        onsubmit="return confirm('Supprimer cette matière ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" style="text-align:center;padding:32px;color:var(--muted);">
                  Aucune matière enregistrée.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- ══ ONGLET 3 : NOTES ══ --}}
    <div id="tab-notes" class="tab-content">
      <div class="card">
        <div class="card-header">
          <div class="card-header-left">
            <div class="card-header-icon"><i class="fas fa-pen"></i></div>
            <span class="card-title">Saisie des notes</span>
          </div>
        </div>
        <div class="card-body">
          @if(!$sessionVerrouillee)
          <form action="{{ route('admin.storeNote') }}" method="POST">
            @csrf
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Étudiant</label>
                <select name="id_etudiant" required>
                  <option value="">Sélectionner...</option>
                  @foreach($etudiants_valides as $e)
                    <option value="{{ $e->id_etudiant }}">{{ $e->nom }} {{ $e->prenom }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Matière</label>
                <select name="id_matiere" required>
                  <option value="">Sélectionner...</option>
                  @foreach($matieres as $m)
                    <option value="{{ $m->id_matiere }}">{{ $m->nom_matiere }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group" style="grid-column:span 2;">
                <label class="form-label">Note (sur 20)</label>
                <input type="number" name="valeur_note" min="0" max="20" step="0.25"
                       placeholder="0.00" required>
              </div>
            </div>
            <button type="submit" class="btn-primary-full">
              <i class="fas fa-save"></i> Enregistrer la note
            </button>
          </form>
          @else
          <div class="alert-warning">
            <i class="fas fa-lock"></i>
            <span>La session est <strong>verrouillée</strong>. La saisie des notes n'est plus possible pour cette période.</span>
          </div>
          @endif
        </div>
      </div>
    </div>

  </div><!-- /content -->
</main>

<!-- ════ MODAL REJET ════ -->
<div class="modal-overlay" id="modalRejet">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-icon"><i class="fas fa-times"></i></div>
      <div class="modal-title">Motif de rejet du dossier</div>
    </div>
    <form id="formRejet" method="POST">
      @csrf
      <div class="form-group">
        <label class="form-label" style="font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px;display:block;">
          Expliquez le motif
        </label>
        <textarea name="motif_rejet" rows="4" required
                  placeholder="Ex : Photo manquante, acte de naissance illisible..."></textarea>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-cancel" onclick="closeModal()">Annuler</button>
        <button type="submit" class="btn-confirm"><i class="fas fa-times"></i> Confirmer le rejet</button>
      </div>
    </form>
  </div>
</div>

<script>
const tabTitles = {
  dossiers: { title: 'Dossiers en attente', bc: 'Validation des dossiers' },
  matieres: { title: 'Gestion des Matières', bc: 'Matières & UE' },
  notes:    { title: 'Saisie des Notes',     bc: 'Notes académiques' },
};

function showTab(name, btn) {
  document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.nav-link').forEach(b => b.classList.remove('active'));

  document.getElementById('tab-' + name).classList.add('active');

  document.querySelectorAll('[id^="tabBtn-"]').forEach(b => {
    if (b.id === 'tabBtn-' + name) b.classList.add('active');
  });

  if (btn) btn.classList.add('active');

  const info = tabTitles[name];
  if (info) {
    document.getElementById('page-title').textContent = info.title;
    document.getElementById('breadcrumb-active').textContent = info.bc;
  }
}

function openModal(id) {
  document.getElementById('formRejet').action = '/admin/dossier/' + id + '/rejeter';
  document.getElementById('modalRejet').classList.add('open');
}
function closeModal() {
  document.getElementById('modalRejet').classList.remove('open');
}
document.getElementById('modalRejet').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});
</script>
</body>
</html>
