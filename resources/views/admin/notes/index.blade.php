<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestion des Notes — UAC</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --sidebar-w: 260px;
  --primary: #0f172a;
  --accent: #6366f1;
  --accent-hover: #4f46e5;
  --accent-soft: #eef2ff;
  --green: #10b981; --green-soft: #d1fae5; --green-dark: #065f46;
  --red: #ef4444;   --red-soft: #fee2e2;   --red-dark: #991b1b;
  --yellow: #f59e0b;--yellow-soft: #fef3c7;--yellow-dark: #78350f;
  --blue: #3b82f6;  --blue-soft: #dbeafe;  --blue-dark: #1e3a8a;
  --text: #0f172a; --muted: #64748b;
  --border: #e2e8f0; --bg: #f8fafc; --white: #ffffff;
  --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
  --shadow: 0 4px 16px rgba(0,0,0,0.08);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; font-size: 14px; }

/* ── SIDEBAR ── */
.sidebar { width: var(--sidebar-w); background: var(--primary); position: fixed; top: 0; left: 0; height: 100vh; display: flex; flex-direction: column; z-index: 100; }
.sidebar-brand { padding: 24px 20px 18px; border-bottom: 1px solid rgba(255,255,255,0.07); display: flex; align-items: center; gap: 12px; }
.brand-mark { width: 38px; height: 38px; background: linear-gradient(135deg, var(--accent), #818cf8); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 17px; color: white; flex-shrink: 0; }
.brand-name { color: white; font-size: 15px; font-weight: 600; }
.brand-role { color: rgba(255,255,255,0.38); font-size: 10px; text-transform: uppercase; letter-spacing: 1.2px; }
.nav-group { padding: 18px 12px 6px; }
.nav-label { color: rgba(255,255,255,0.28); font-size: 9.5px; text-transform: uppercase; letter-spacing: 1.8px; padding: 0 10px; margin-bottom: 6px; }
.nav-link { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; color: rgba(255,255,255,0.55); text-decoration: none; font-size: 13.5px; transition: all 0.15s; margin-bottom: 2px; }
.nav-link i { width: 18px; font-size: 14px; text-align: center; }
.nav-link:hover { background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.9); }
.nav-link.active { background: rgba(99,102,241,0.2); color: #a5b4fc; }
.sidebar-footer { margin-top: auto; padding: 14px 12px; border-top: 1px solid rgba(255,255,255,0.07); }
.user-row { display: flex; align-items: center; gap: 10px; padding: 8px; margin-bottom: 8px; }
.user-avatar { width: 34px; height: 34px; background: linear-gradient(135deg, var(--accent), #818cf8); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 600; }
.user-name { color: white; font-size: 13px; font-weight: 500; }
.user-role-text { color: rgba(255,255,255,0.35); font-size: 11px; }
.btn-logout { display: flex; align-items: center; gap: 8px; width: 100%; padding: 9px 12px; background: rgba(239,68,68,0.1); color: #fca5a5; border: none; border-radius: 8px; font-size: 13px; cursor: pointer; font-family: inherit; transition: background 0.15s; }
.btn-logout:hover { background: rgba(239,68,68,0.2); }

/* ── MAIN ── */
.main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
.topbar { background: var(--white); border-bottom: 1px solid var(--border); padding: 0 32px; height: 62px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
.page-title { font-size: 17px; font-weight: 600; letter-spacing: -0.3px; }
.breadcrumb { font-size: 12px; color: var(--muted); margin-top: 1px; }
.breadcrumb span { color: var(--accent); }
.topbar-right { display: flex; align-items: center; gap: 10px; }
.count-pill { font-size: 12px; color: var(--muted); background: var(--bg); padding: 5px 12px; border-radius: 20px; border: 1px solid var(--border); font-family: 'DM Mono', monospace; }
.btn-add-note {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 8px 16px;
  background: var(--accent); color: white;
  border: none; border-radius: 8px;
  font-size: 13.5px; font-weight: 600;
  cursor: pointer; font-family: inherit;
  text-decoration: none;
  transition: background 0.15s;
}
.btn-add-note:hover { background: var(--accent-hover); }

.content { padding: 28px 32px; flex: 1; }

/* ── ALERTS ── */
.alert { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 10px; font-size: 13.5px; margin-bottom: 18px; border: 1px solid; }
.alert-success { background: var(--green-soft); color: var(--green-dark); border-color: #a7f3d0; }
.alert-error   { background: var(--red-soft);   color: var(--red-dark);   border-color: #fca5a5; }

/* ── CARD ── */
.card { background: var(--white); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: var(--shadow-sm); margin-bottom: 22px; }
.card-header { padding: 16px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
.card-header-left { display: flex; align-items: center; gap: 10px; }
.card-header-icon { width: 32px; height: 32px; background: var(--accent-soft); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 14px; }
.card-title { font-size: 14px; font-weight: 600; }
.card-body { padding: 20px 22px; }

/* ── FILTER FORM ── */
.filter-grid { display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; align-items: end; }
@media(max-width:700px){ .filter-grid { grid-template-columns: 1fr; } }
.form-group { display: flex; flex-direction: column; gap: 5px; }
.form-label { font-size: 11px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.6px; }
input[type=text], select {
  padding: 10px 13px; border: 1px solid var(--border); border-radius: 8px;
  font-size: 13.5px; font-family: inherit; color: var(--text); background: var(--white);
  transition: border 0.15s, box-shadow 0.15s; width: 100%;
}
input:focus, select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
.filter-actions { display: flex; gap: 8px; align-items: flex-end; }
.btn-filter { display: flex; align-items: center; gap: 7px; padding: 10px 18px; background: var(--accent); color: white; border: none; border-radius: 8px; font-size: 13.5px; font-weight: 600; cursor: pointer; font-family: inherit; transition: background 0.15s; white-space: nowrap; }
.btn-filter:hover { background: var(--accent-hover); }
.btn-reset { display: flex; align-items: center; gap: 6px; padding: 10px 14px; background: none; color: var(--muted); border: 1px solid var(--border); border-radius: 8px; font-size: 13px; cursor: pointer; font-family: inherit; transition: all 0.15s; text-decoration: none; white-space: nowrap; }
.btn-reset:hover { background: var(--bg); color: var(--text); }

/* ── TABLE ── */
.table-topbar { display: flex; align-items: center; justify-content: space-between; padding: 14px 22px; border-bottom: 1px solid var(--border); flex-wrap: wrap; gap: 10px; }
.filter-tag { display: inline-flex; align-items: center; gap: 5px; font-size: 11.5px; font-weight: 500; padding: 4px 10px; border-radius: 20px; background: var(--yellow-soft); color: var(--yellow-dark); border: 1px solid #fcd34d; }
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
thead th { padding: 10px 18px; text-align: left; font-size: 10.5px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.6px; background: var(--bg); border-bottom: 1px solid var(--border); }
tbody td { padding: 14px 18px; border-bottom: 1px solid var(--border); vertical-align: middle; }
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover td { background: #fafbfd; }

.etu-name { font-weight: 600; font-size: 13px; }
.etu-mat  { font-size: 11px; color: var(--muted); margin-top: 2px; font-family: 'DM Mono', monospace; }

.pill { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 500; }
.pill-blue   { background: var(--blue-soft);   color: var(--blue-dark); }
.pill-orange { background: #fff7ed; color: #c2410c; }
.pill-green  { background: var(--green-soft);  color: var(--green-dark); }

.note-val  { font-size: 15px; font-weight: 600; font-family: 'DM Mono', monospace; }
.note-pass { color: var(--green); }
.note-fail { color: var(--red); }
.note-denom { font-size: 11px; font-weight: 400; color: var(--muted); }

.note-bar { width: 80px; height: 4px; background: var(--border); border-radius: 99px; margin-top: 5px; overflow: hidden; }
.note-bar-fill { height: 100%; border-radius: 99px; }

.btn-edit { background: none; border: 1px solid var(--border); color: var(--muted); border-radius: 7px; padding: 6px 12px; font-size: 12px; font-weight: 500; cursor: pointer; font-family: inherit; transition: all 0.15s; display: flex; align-items: center; gap: 5px; }
.btn-edit:hover { background: var(--accent-soft); border-color: var(--accent); color: var(--accent); }

.empty { text-align: center; padding: 52px; color: var(--muted); font-size: 13px; }
.empty i { font-size: 32px; margin-bottom: 10px; display: block; opacity: 0.35; }

/* ── PAGINATION ── */
.pagination-wrap { padding: 14px 22px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; }
.pagination { display: flex; gap: 4px; list-style: none; }
.page-link { padding: 7px 13px; border: 1px solid var(--border); border-radius: 7px; font-size: 12.5px; color: var(--text); text-decoration: none; transition: all 0.15s; }
.page-link:hover { background: var(--accent-soft); border-color: var(--accent); color: var(--accent); }
.page-item.active .page-link { background: var(--accent); color: white; border-color: var(--accent); }

/* ── MODAL ── */
.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.55); z-index: 999; align-items: center; justify-content: center; backdrop-filter: blur(3px); }
.modal-overlay.open { display: flex; }
.modal-box { background: var(--white); border-radius: 16px; padding: 28px; width: 92%; max-width: 420px; border: 1px solid var(--border); box-shadow: 0 24px 64px rgba(0,0,0,0.18); animation: slideUp 0.2s ease; }
@keyframes slideUp { from { transform: translateY(12px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
.modal-header { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
.modal-icon-box { width: 38px; height: 38px; background: var(--accent-soft); border-radius: 9px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 16px; }
.modal-title { font-size: 15px; font-weight: 600; }
.modal-form-group { margin-bottom: 15px; }
.modal-label { display: block; font-size: 11px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 6px; }
.modal-input { width: 100%; padding: 10px 13px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; transition: border 0.15s; }
.modal-input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
.modal-actions { display: flex; gap: 10px; margin-top: 6px; }
.btn-save { flex: 1; padding: 10px; background: var(--accent); color: white; border: none; border-radius: 8px; font-size: 13.5px; font-weight: 600; cursor: pointer; font-family: inherit; display: flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.15s; }
.btn-save:hover { background: var(--accent-hover); }
.btn-cancel { flex: 1; padding: 10px; background: none; color: var(--muted); border: 1px solid var(--border); border-radius: 8px; font-size: 13.5px; cursor: pointer; font-family: inherit; transition: background 0.15s; }
.btn-cancel:hover { background: var(--bg); }

@media(max-width:768px){ .main { margin-left: 0; } .content { padding: 18px 16px; } .topbar { padding: 0 16px; } }
</style>
</head>
<body>

<!-- ════ SIDEBAR ════ -->
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="brand-mark"><i class="fas fa-university"></i></div>
    <div>
      <div class="brand-name">UAC HUB</div>
      <div class="brand-role">Super Administration</div>
    </div>
  </div>
  <div class="nav-group">
    <div class="nav-label">Principal</div>
    <a href="{{ route('superadmin.dashboard') }}" class="nav-link"><i class="fas fa-chart-pie"></i> Tableau de bord</a>
    <a href="{{ route('superadmin.inscriptions') }}" class="nav-link"><i class="fas fa-file-signature"></i> Inscriptions</a>
  </div>
  <div class="nav-group">
    <div class="nav-label">Gestion</div>
    <a href="{{ route('superadmin.enseignants') }}" class="nav-link"><i class="fas fa-chalkboard-teacher"></i> Enseignants</a>
    <a href="{{ route('superadmin.matieres') }}" class="nav-link"><i class="fas fa-book-open"></i> Matières & UE</a>
    <a href="{{ route('superadmin.notes') }}" class="nav-link active"><i class="fas fa-graduation-cap"></i> Notes</a>
    <a href="{{ route('superadmin.academics') }}" class="nav-link"><i class="fas fa-sitemap"></i> Départements & Filières</a>
  </div>
  <div class="sidebar-footer">
    <div class="user-row">
      <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nom,0,1)) }}{{ strtoupper(substr(Auth::user()->prenom,0,1)) }}</div>
      <div>
        <div class="user-name">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
        <div class="user-role-text">Super Administrateur</div>
      </div>
    </div>
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
    </form>
  </div>
</aside>

<!-- ════ MAIN ════ -->
<main class="main">
  <header class="topbar">
    <div>
      <div class="page-title">Gestion des Notes</div>
      <div class="breadcrumb">UAC &rsaquo; <span>Supervision des notes</span></div>
    </div>
    <div class="topbar-right">
      <span class="count-pill"><i class="fas fa-list" style="margin-right:6px;font-size:11px;"></i>{{ $notes->total() }} résultat(s)</span>
      <a href="{{ route('superadmin.notes.create') }}" class="btn-add-note">
        <i class="fas fa-plus"></i> Nouvelle note
      </a>
    </div>
  </header>

  <div class="content">

    @if(session('success'))
      <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    <!-- FILTRES -->
    <div class="card">
      <div class="card-header">
        <div class="card-header-left">
          <div class="card-header-icon"><i class="fas fa-filter"></i></div>
          <span class="card-title">Filtrer les notes</span>
        </div>
      </div>
      <div class="card-body">
        <form action="{{ route('superadmin.notes') }}" method="GET">
          <div class="filter-grid">
            <div class="form-group">
              <label class="form-label">Matricule étudiant</label>
              <input type="text" name="matricule" value="{{ request('matricule') }}" placeholder="Ex : 20231042">
            </div>
            <div class="form-group">
              <label class="form-label">Matière</label>
              <select name="id_matiere">
                <option value="">Toutes les matières</option>
                @foreach($matieres as $m)
                  <option value="{{ $m->id_matiere }}" {{ request('id_matiere') == $m->id_matiere ? 'selected' : '' }}>
                    {{ $m->nom_matiere }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="filter-actions">
              <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filtrer</button>
              @if(request('matricule') || request('id_matiere'))
                <a href="{{ route('superadmin.notes') }}" class="btn-reset"><i class="fas fa-times"></i> Réinitialiser</a>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- TABLE DES NOTES -->
    <div class="card">
      <div class="table-topbar">
        <span class="card-title">Résultats</span>
        @if(request('matricule') || request('id_matiere'))
          <span class="filter-tag"><i class="fas fa-filter" style="font-size:10px;"></i> Filtre actif</span>
        @endif
      </div>

      @if($notes->isEmpty())
        <div class="empty">
          <i class="fas fa-graduation-cap"></i>
          Aucune note trouvée pour ces critères.
        </div>
      @else
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Étudiant</th>
              <th>Matière</th>
              <th>Note</th>
              <th>Session</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($notes as $n)
            @php $pct = ($n->valeur_note / 20) * 100; @endphp
            <tr>
              <td>
                <div class="etu-name">{{ $n->etudiant->nom ?? '—' }} {{ $n->etudiant->prenom ?? '' }}</div>
                <div class="etu-mat">Matricule : {{ $n->etudiant->matricule ?? 'N/A' }}</div>
              </td>
              <td><span class="pill pill-blue">{{ $n->matiere->nom_matiere ?? '—' }}</span></td>
              <td>
                <span class="note-val {{ $n->valeur_note >= 10 ? 'note-pass' : 'note-fail' }}">
                  {{ number_format($n->valeur_note, 2) }}<span class="note-denom">/20</span>
                </span>
                <div class="note-bar">
                  <div class="note-bar-fill" style="width:{{ $pct }}%;background:{{ $n->valeur_note >= 10 ? '#10b981' : '#ef4444' }};"></div>
                </div>
              </td>
              <td>
                <span class="pill {{ $n->session === 'rattrapage' ? 'pill-orange' : 'pill-blue' }}">
                  {{ ucfirst($n->session) }}
                </span>
              </td>
              <td>
                <button class="btn-edit" onclick="openEdit({{ $n->id_note }}, {{ $n->valeur_note }}, '{{ $n->session }}')">
                  <i class="fas fa-pen"></i> Modifier
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @if($notes->hasPages())
        <div class="pagination-wrap">
          {{ $notes->appends(request()->query())->links() }}
        </div>
      @endif
      @endif
    </div>

  </div>
</main>

<!-- ════ MODAL ÉDITION ════ -->
<div class="modal-overlay" id="editModal">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-icon-box"><i class="fas fa-pen"></i></div>
      <div class="modal-title">Modifier la note</div>
    </div>
    <form id="editForm" method="POST">
      @csrf @method('PUT')
      <div class="modal-form-group">
        <label class="modal-label">Nouvelle valeur (sur 20)</label>
        <input type="number" name="valeur_note" id="editNoteVal" class="modal-input" min="0" max="20" step="0.25" required>
      </div>
      <div class="modal-form-group">
        <label class="modal-label">Session</label>
        <select name="session" id="editSession" class="modal-input">
          <option value="normale">Normale</option>
          <option value="rattrapage">Rattrapage</option>
        </select>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-cancel" onclick="closeEdit()">Annuler</button>
        <button type="submit" class="btn-save"><i class="fas fa-save"></i> Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEdit(id, note, session) {
  document.getElementById('editForm').action = '/super-admin/notes/' + id;
  document.getElementById('editNoteVal').value = note;
  document.getElementById('editSession').value = session;
  document.getElementById('editModal').classList.add('open');
}
function closeEdit() { document.getElementById('editModal').classList.remove('open'); }
document.getElementById('editModal').addEventListener('click', function(e) {
  if (e.target === this) closeEdit();
});
</script>
</body>
</html>
