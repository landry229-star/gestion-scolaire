<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Saisie des Notes — UAC</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --sidebar-w: 260px;
  --primary: #0f172a;
  --accent: #6366f1; --accent-hover: #4f46e5; --accent-soft: #eef2ff;
  --green: #10b981;  --green-soft: #d1fae5;   --green-dark: #065f46;
  --red: #ef4444;    --red-soft: #fee2e2;      --red-dark: #991b1b;
  --blue: #3b82f6;   --blue-soft: #dbeafe;     --blue-dark: #1e3a8a;
  --text: #0f172a;   --muted: #64748b;
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
.user-avatar { width: 34px; height: 34px; background: linear-gradient(135deg, var(--accent), #818cf8); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 600; flex-shrink: 0; }
.user-name { color: white; font-size: 13px; font-weight: 500; }
.user-role-text { color: rgba(255,255,255,0.35); font-size: 11px; }
.btn-logout { display: flex; align-items: center; gap: 8px; width: 100%; padding: 9px 12px; background: rgba(239,68,68,0.1); color: #fca5a5; border: none; border-radius: 8px; font-size: 13px; cursor: pointer; font-family: inherit; transition: background 0.15s; }
.btn-logout:hover { background: rgba(239,68,68,0.2); }

/* ── MAIN ── */
.main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
.topbar { background: var(--white); border-bottom: 1px solid var(--border); padding: 0 32px; height: 62px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
.page-title { font-size: 17px; font-weight: 600; letter-spacing: -0.3px; }
.breadcrumb-nav { font-size: 12px; color: var(--muted); margin-top: 2px; }
.breadcrumb-nav a { color: var(--accent); text-decoration: none; }
.breadcrumb-nav a:hover { text-decoration: underline; }
.breadcrumb-nav span { color: var(--text); font-weight: 500; }
.content { padding: 28px 32px; flex: 1; }

/* ── ALERTS ── */
.alert { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 10px; font-size: 13.5px; margin-bottom: 18px; border: 1px solid; }
.alert-success { background: var(--green-soft); color: var(--green-dark); border-color: #a7f3d0; }
.alert-error   { background: var(--red-soft);   color: var(--red-dark);   border-color: #fca5a5; }
.alert-info    { background: var(--blue-soft);  color: var(--blue-dark);  border-color: #93c5fd; border-left: 4px solid var(--blue); }

/* ── LAYOUT ── */
.page-grid { display: grid; grid-template-columns: 400px 1fr; gap: 24px; align-items: start; }
@media(max-width:1150px){ .page-grid { grid-template-columns: 1fr; } }

/* ── CARD ── */
.card { background: var(--white); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: var(--shadow-sm); }
.card-header { padding: 16px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.card-header-left { display: flex; align-items: center; gap: 10px; }
.card-header-icon { width: 32px; height: 32px; background: var(--accent-soft); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 14px; }
.card-title { font-size: 14px; font-weight: 600; }
.card-body  { padding: 24px; }
.session-badge { font-size: 11.5px; font-weight: 500; padding: 4px 11px; border-radius: 20px; background: var(--accent-soft); color: #4338ca; }

/* ── FORM ── */
.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: 11px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 6px; }
select, input[type=number], input[type=text] {
  width: 100%; padding: 10px 13px;
  border: 1px solid var(--border); border-radius: 8px;
  font-size: 14px; font-family: inherit; color: var(--text);
  background: var(--white); transition: border 0.15s, box-shadow 0.15s;
}
select:focus, input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
.note-wrap { position: relative; }
.note-wrap input { padding-right: 50px; font-size: 20px; font-weight: 600; text-align: center; letter-spacing: -0.5px; font-family: 'DM Mono', monospace; }
.note-suffix { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 13px; pointer-events: none; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.error-msg { color: #ef4444; font-size: 11.5px; margin-top: 4px; }
.form-divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }
.form-actions { display: flex; align-items: center; justify-content: space-between; }
.btn-back { display: flex; align-items: center; gap: 6px; color: var(--muted); text-decoration: none; font-size: 13px; transition: color 0.15s; }
.btn-back:hover { color: var(--text); }
.btn-submit { display: flex; align-items: center; gap: 8px; padding: 11px 24px; background: var(--accent); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; font-family: inherit; transition: background 0.15s; }
.btn-submit:hover { background: var(--accent-hover); }

/* ── NOTE SCORE PREVIEW ── */
.score-preview {
  margin: 16px 0;
  padding: 14px;
  background: var(--bg);
  border-radius: 10px;
  border: 1px solid var(--border);
  display: flex; align-items: center; gap: 12px;
}
.score-bar-wrap { flex: 1; height: 6px; background: var(--border); border-radius: 99px; overflow: hidden; }
.score-bar-fill { height: 100%; border-radius: 99px; background: var(--accent); transition: width 0.3s ease; }
.score-status { font-size: 12px; font-weight: 600; padding: 3px 9px; border-radius: 20px; white-space: nowrap; }
.score-pass { background: var(--green-soft); color: var(--green-dark); }
.score-fail { background: var(--red-soft);   color: var(--red-dark); }

/* ── DYNAMIC TABLE STYLES ── */
.table-topbar { display: flex; align-items: center; justify-content: space-between; padding: 14px 22px; border-bottom: 1px solid var(--border); gap: 12px; flex-wrap: wrap; }
.search-wrap { position: relative; }
.search-wrap i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 13px; pointer-events: none; }
.search-wrap input { padding-left: 33px; width: 220px; font-size: 13px; }
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
thead th { padding: 12px 18px; text-align: left; font-size: 10.5px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.6px; background: var(--bg); border-bottom: 1px solid var(--border); }
tbody td { padding: 12px 18px; border-bottom: 1px solid var(--border); vertical-align: middle; }
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover td { background: #fafbfd; }

.student-meta { display: flex; align-items: center; gap: 10px; }
.student-avatar { width: 30px; height: 30px; border-radius: 50%; background: var(--accent-soft); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; }
.student-name { font-weight: 600; font-size: 13px; }
.student-id { font-size: 10.5px; color: var(--muted); font-family: 'DM Mono', monospace; margin-top: 1px; }

.matiere-tag { font-weight: 500; color: var(--text); }
.filiere-sub { font-size: 11px; color: var(--muted); margin-top: 1px; }

.session-pill { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; text-transform: capitalize; }
.session-normale { background: var(--blue-soft); color: var(--blue-dark); }
.session-rattrapage { background: #fef3c7; color: #92400e; }

.note-badge { font-size: 13px; font-weight: 700; padding: 4px 10px; border-radius: 20px; font-family: 'DM Mono', monospace; display: inline-block; text-align: center; }
.note-pass { background: var(--green-soft); color: var(--green-dark); border: 1px solid #a7f3d0; }
.note-fail { background: var(--red-soft);   color: var(--red-dark);   border: 1px solid #fca5a5; }

.btn-del { background: none; border: 1px solid var(--border); color: var(--muted); border-radius: 7px; padding: 6px 10px; font-size: 12px; cursor: pointer; font-family: inherit; transition: all 0.15s; }
.btn-del:hover { background: var(--red-soft); border-color: #fca5a5; color: var(--red); }
.empty-table { text-align: center; padding: 44px; color: var(--muted); }
.empty-table i { font-size: 28px; margin-bottom: 10px; display: block; opacity: 0.35; }

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
      <div class="page-title">Saisie des Notes</div>
      <div class="breadcrumb-nav">
        <a href="{{ route('superadmin.dashboard') }}">Dashboard</a>
        &rsaquo; <a href="{{ route('superadmin.notes') }}">Notes</a>
        &rsaquo; <span>Nouvelle saisie</span>
      </div>
    </div>
  </header>

  <div class="content">

    @if(session('success'))
      <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    <div class="alert alert-info">
      <i class="fas fa-info-circle"></i>
      <span>Seuls les étudiants avec un dossier <strong>validé</strong> (matricule attribué) apparaissent dans la liste.</span>
    </div>

    <div class="page-grid">

      <!-- ── COLUMN 1: FORMULAIRE ── -->
      <div class="card">
        <div class="card-header">
          <div class="card-header-left">
            <div class="card-header-icon"><i class="fas fa-pen"></i></div>
            <span class="card-title">Nouvelle note académique</span>
          </div>
          <span class="session-badge">Session {{ date('Y') }}</span>
        </div>
        <div class="card-body">
          <form action="{{ route('superadmin.notes.store') }}" method="POST" id="noteForm">
            @csrf

            <div class="form-group">
              <label class="form-label">Étudiant</label>
              <select name="id_etudiant" required>
                <option value="" disabled selected>Sélectionner un étudiant...</option>
                @foreach($etudiants as $e)
                  <option value="{{ $e->id_etudiant }}" {{ old('id_etudiant') == $e->id_etudiant ? 'selected' : '' }}>
                    [{{ $e->matricule }}] {{ strtoupper($e->nom) }} {{ $e->prenom }}
                  </option>
                @endforeach
              </select>
              @error('id_etudiant')<p class="error-msg">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
              <label class="form-label">Matière / UE</label>
              <select name="id_matiere" required>
                <option value="" disabled selected>Choisir une unité d'enseignement...</option>
                @foreach($matieres as $m)
                  <option value="{{ $m->id_matiere }}" {{ old('id_matiere') == $m->id_matiere ? 'selected' : '' }}>
                    {{ $m->nom_matiere }}@if($m->filiere) — {{ $m->filiere->nom_filiere }}@endif
                  </option>
                @endforeach
              </select>
              @error('id_matiere')<p class="error-msg">{{ $message }}</p>@enderror
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Note (sur 20)</label>
                <div class="note-wrap">
                  <input type="number" name="valeur_note" id="noteInput"
                         step="0.25" min="0" max="20"
                         placeholder="0.00" value="{{ old('valeur_note') }}"
                         required oninput="updatePreview(this.value)">
                  <span class="note-suffix">/20</span>
                </div>
                @error('valeur_note')<p class="error-msg">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label class="form-label">Session</label>
                <select name="session" required>
                  <option value="normale"    {{ old('session') == 'normale'    ? 'selected' : '' }}>Normale</option>
                  <option value="rattrapage" {{ old('session') == 'rattrapage' ? 'selected' : '' }}>Rattrapage</option>
                </select>
                @error('session')<p class="error-msg">{{ $message }}</p>@enderror
              </div>
            </div>

            <!-- Barre de progression dynamique -->
            <div class="score-preview" id="scorePreview" style="display:none;">
              <div class="score-bar-wrap">
                <div class="score-bar-fill" id="scoreBar" style="width:0%;"></div>
              </div>
              <span class="score-status" id="scoreStatus"></span>
            </div>

            <hr class="form-divider">

            <div class="form-actions">
              <a href="{{ route('superadmin.notes.create') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Retour à la liste
              </a>
              <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Enregistrer
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- ── COLUMN 2: TABLEAU DYNAMIQUE COMPLET DES NOTES ── -->
      <div class="card">
        <div class="table-topbar">
          <span class="card-title"><i class="fas fa-list" style="margin-right:6px;color:var(--accent);"></i> Notes récemment saisies</span>
          <div class="search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="tableSearchInput" placeholder="Rechercher un étudiant, code...">
          </div>
        </div>

        @if($dernieres_notes->isEmpty())
          <div class="empty-table">
            <i class="fas fa-graduation-cap"></i>
            Aucune note n'a encore été saisie pour le moment.
          </div>
        @else
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Étudiant</th>
                  <th>Matière / Filière</th>
                  <th>Session</th>
                  <th>Note obtenu</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="notesTableBody">
                @foreach($dernieres_notes as $note)
                  <tr>
                    <td>
                      <div class="student-meta">
                        <div class="student-avatar">
                          {{ strtoupper(substr($note->etudiant->nom ?? 'E', 0, 1)) }}{{ strtoupper(substr($note->etudiant->prenom ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                          <div class="student-name">{{ strtoupper($note->etudiant->nom ?? '—') }} {{ $note->etudiant->prenom ?? '' }}</div>
                          <div class="student-id"><i class="far fa-id-badge"></i> {{ $note->etudiant->matricule ?? 'N/A' }}</div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="matiere-tag">{{ $note->matiere->nom_matiere ?? 'Matière inconnue' }}</div>
                      <div class="filiere-sub">{{ $note->matiere->filiere->nom_filiere ?? '—' }}</div>
                    </td>
                    <td>
                      <span class="session-pill session-{{ $note->session ?? 'normale' }}">
                        {{ $note->session ?? 'Normale' }}
                      </span>
                    </td>
                    <td>
                      <span class="note-badge {{ ($note->valeur_note ?? 0) >= 10 ? 'note-pass' : 'note-fail' }}">
                        {{ number_format($note->valeur_note ?? 0, 2) }} / 20
                      </span>
                    </td>
                    <td>
                      <form action="{{ route('superadmin.notes.delete', $note->id_note ?? 0) }}" method="POST"
                            onsubmit="return confirm('Voulez-vous vraiment supprimer cette note ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-del" title="Supprimer la note">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>

    </div>
  </div>
</main>

<script>
// Recherche en temps réel dans le tableau des notes
document.getElementById('tableSearchInput').addEventListener('keyup', function () {
  const query = this.value.toLowerCase();
  document.querySelectorAll('#notesTableBody tr').forEach(row => {
    row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
  });
});

// Aperçu de progression de la note saisie
function updatePreview(val) {
  const v = parseFloat(val);
  const preview = document.getElementById('scorePreview');
  const bar = document.getElementById('scoreBar');
  const status = document.getElementById('scoreStatus');
  if (isNaN(v) || val === '') { preview.style.display = 'none'; return; }
  const pct = Math.min(100, (v / 20) * 100);
  const pass = v >= 10;
  preview.style.display = 'flex';
  bar.style.width = pct + '%';
  bar.style.background = pass ? '#10b981' : '#ef4444';
  status.textContent = pass ? 'Admis ✓' : 'Ajourné';
  status.className = 'score-status ' + (pass ? 'score-pass' : 'score-fail');
}
</script>
</body>
</html>
