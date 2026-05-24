<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Matières & UE — UAC</title>
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
  --purple: #8b5cf6; --purple-soft: #f5f3ff;   --purple-dark: #4c1d95;
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
.breadcrumb { font-size: 12px; color: var(--muted); margin-top: 1px; }
.breadcrumb span { color: var(--accent); }
.count-badge { font-size: 12px; color: var(--muted); background: var(--bg); padding: 5px 12px; border-radius: 20px; border: 1px solid var(--border); font-family: 'DM Mono', monospace; }
.content { padding: 28px 32px; flex: 1; }

/* ── ALERTS ── */
.alert { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 10px; font-size: 13.5px; margin-bottom: 18px; border: 1px solid; }
.alert-success { background: var(--green-soft); color: var(--green-dark); border-color: #a7f3d0; }
.alert-error   { background: var(--red-soft);   color: var(--red-dark);   border-color: #fca5a5; }

/* ── LAYOUT ── */
.page-grid { display: grid; grid-template-columns: 300px 1fr; gap: 24px; align-items: start; }
@media(max-width:960px){ .page-grid { grid-template-columns: 1fr; } }

/* ── CARD ── */
.card { background: var(--white); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: var(--shadow-sm); }
.card-header { padding: 16px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.card-header-left { display: flex; align-items: center; gap: 10px; }
.card-header-icon { width: 32px; height: 32px; background: var(--accent-soft); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 14px; }
.card-title { font-size: 14px; font-weight: 600; }
.card-body  { padding: 22px; }

/* ── FORM ── */
.form-group { margin-bottom: 14px; }
.form-label { display: block; font-size: 11px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 6px; }
input[type=text], input[type=number], select {
  width: 100%; padding: 10px 13px;
  border: 1px solid var(--border); border-radius: 8px;
  font-size: 13.5px; font-family: inherit; color: var(--text);
  background: var(--white); transition: border 0.15s, box-shadow 0.15s;
}
input:focus, select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.error-msg { color: #ef4444; font-size: 11.5px; margin-top: 4px; }
.btn-submit-full {
  width: 100%; padding: 11px;
  background: var(--accent); color: white;
  border: none; border-radius: 8px;
  font-size: 14px; font-weight: 600;
  cursor: pointer; font-family: inherit;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  margin-top: 6px; transition: background 0.15s;
}
.btn-submit-full:hover { background: var(--accent-hover); }

/* ── TABLE ── */
.table-topbar { display: flex; align-items: center; justify-content: space-between; padding: 14px 22px; border-bottom: 1px solid var(--border); gap: 12px; flex-wrap: wrap; }
.search-wrap { position: relative; }
.search-wrap i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 13px; pointer-events: none; }
.search-wrap input { padding-left: 33px; width: 200px; font-size: 13px; }
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
thead th { padding: 10px 18px; text-align: left; font-size: 10.5px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.6px; background: var(--bg); border-bottom: 1px solid var(--border); }
tbody td { padding: 13px 18px; border-bottom: 1px solid var(--border); vertical-align: middle; }
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover td { background: #fafbfd; }
.matiere-name { font-weight: 600; font-size: 13px; }
.matiere-code { font-size: 11px; color: var(--muted); margin-top: 2px; font-family: 'DM Mono', monospace; }
.pill { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 500; }
.pill-blue   { background: var(--blue-soft);   color: var(--blue-dark); }
.credit-chip {
  display: inline-flex; align-items: center; gap: 4px;
  background: var(--purple-soft); color: var(--purple-dark);
  padding: 4px 10px; border-radius: 6px;
  font-weight: 600; font-family: 'DM Mono', monospace;
}
.semestre-chip {
  display: inline-flex; align-items: center;
  background: var(--bg); color: var(--muted);
  border: 1px solid var(--border);
  padding: 4px 8px; border-radius: 6px;
  font-size: 11.5px; font-weight: 500; font-family: 'DM Mono', monospace;
}
.btn-del { background: none; border: 1px solid var(--border); color: var(--muted); border-radius: 7px; padding: 6px 11px; font-size: 12px; cursor: pointer; font-family: inherit; transition: all 0.15s; }
.btn-del:hover { background: var(--red-soft); border-color: #fca5a5; color: var(--red); }
.empty { text-align: center; padding: 40px; color: var(--muted); font-size: 13px; }
.empty i { font-size: 28px; margin-bottom: 10px; display: block; opacity: 0.35; }
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
    <a href="{{ route('superadmin.matieres') }}" class="nav-link active"><i class="fas fa-book-open"></i> Matières & UE</a>
    <a href="{{ route('superadmin.notes') }}" class="nav-link"><i class="fas fa-graduation-cap"></i> Notes</a>
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
      <div class="page-title">Matières & UE</div>
      <div class="breadcrumb">UAC &rsaquo; <span>Matières & Unités d'enseignement</span></div>
    </div>
    <span class="count-badge">
      <i class="fas fa-book" style="margin-right:6px;font-size:11px;"></i>{{ $matieres->count() }} matière(s)
    </span>
  </header>

  <div class="content">

    @if(session('success'))
      <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    <div class="page-grid">

      <!-- ── FORMULAIRE ── -->
      <div class="card">
        <div class="card-header">
          <div class="card-header-left">
            <div class="card-header-icon"><i class="fas fa-plus"></i></div>
            <span class="card-title">Nouvelle matière</span>
          </div>
        </div>
        <div class="card-body">
          <form action="{{ route('superadmin.matieres.store') }}" method="POST">
            @csrf

            <div class="form-group">
              <label class="form-label">Nom de la matière</label>
              <input type="text" name="nom_matiere" placeholder="Ex : Probabilités et Statistiques"
                     value="{{ old('nom_matiere') }}" required>
              @error('nom_matiere')<p class="error-msg">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
              <label class="form-label">Code matière</label>
              <input type="text" name="code_matiere" placeholder="Ex : MATH301"
                     value="{{ old('code_matiere') }}" required>
              @error('code_matiere')<p class="error-msg">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
              <label class="form-label">Filière</label>
              <select name="id_filiere" required>
                <option value="" disabled selected>Choisir une filière...</option>
                @foreach($filieres as $f)
                  <option value="{{ $f->id_filiere }}" {{ old('id_filiere') == $f->id_filiere ? 'selected' : '' }}>
                    {{ $f->nom_filiere }}
                  </option>
                @endforeach
              </select>
              @error('id_filiere')<p class="error-msg">{{ $message }}</p>@enderror
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Crédits</label>
                <input name="credit_matiere" type="number" min="1"
                       value="{{ old('credit_matiere') }}" placeholder="3" required>
                @error('credit_matiere')<p class="error-msg">{{ $message }}</p>@enderror
              </div>

              <div class="form-group">
                <label class="form-label">Semestre</label>
                <select name="id_semestre">
                  <option value="">— Optionnel —</option>
                  @foreach($semestres as $s)
                    <option value="{{ $s->id_semestre }}"
                            {{ old('id_semestre') == $s->id_semestre ? 'selected' : '' }}>
                      {{ $s->code_semestre }}
                    </option>
                  @endforeach
                </select>
                @error('id_semestre')<p class="error-msg">{{ $message }}</p>@enderror
              </div>
            </div>

            <button type="submit" class="btn-submit-full">
              <i class="fas fa-save"></i> Enregistrer la matière
            </button>
          </form>
        </div>
      </div>

      <!-- ── TABLE ── -->
      <div class="card">
        <div class="table-topbar">
          <span class="card-title">Matières enregistrées</span>
          <div class="search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Rechercher...">
          </div>
        </div>

        @if($matieres->isEmpty())
          <div class="empty">
            <i class="fas fa-book-open"></i>
            Aucune matière enregistrée pour le moment.
          </div>
        @else
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Matière</th>
                <th>Filière</th>
                <th>Crédits</th>
                <th>Semestre</th>
                <th></th>
              </tr>
            </thead>
            <tbody id="matiereTable">
              @foreach($matieres as $m)
              <tr>
                <td>
                  <div class="matiere-name">{{ $m->nom_matiere }}</div>
                  @if($m->code_matiere)
                    <div class="matiere-code">{{ $m->code_matiere }}</div>
                  @endif
                </td>

                <td>
                  <span class="pill pill-blue">{{ $m->filiere->nom_filiere ?? '—' }}</span>
                </td>

                {{-- Fix : credit_matiere affiché directement depuis la colonne, pas de relation --}}
                <td>
                  @if($m->credit_matiere)
                    <span class="credit-chip">★ {{ $m->credit_matiere }}</span>
                  @else
                    <span style="color:#94a3b8;">—</span>
                  @endif
                </td>

                {{-- Fix : $m->semestre->code_semestre (plus de ->id_semestre->scode_semestre) --}}
                <td>
                  @if($m->semestre)
                    <span class="semestre-chip">{{ $m->semestre->code_semestre }}</span>
                  @else
                    <span style="color:#94a3b8;">—</span>
                  @endif
                </td>

                <td>
                  <form action="{{ route('superadmin.matieres.delete', $m->id_matiere) }}" method="POST"
                        onsubmit="return confirm('Supprimer « {{ $m->nom_matiere }} » ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-del"><i class="fas fa-trash-alt"></i></button>
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
document.getElementById('searchInput').addEventListener('keyup', function () {
  const val = this.value.toLowerCase();
  document.querySelectorAll('#matiereTable tr').forEach(row => {
    row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
  });
});
</script>
</body>
</html>
