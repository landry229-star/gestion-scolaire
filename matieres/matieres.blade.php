<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue des Matières — UAC</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sidebar-w: 250px; --primary: #1a2332; --accent: #3b82f6;
            --accent-soft: #eff6ff; --text: #1e293b; --muted: #64748b;
            --border: #e2e8f0; --bg: #f8fafc; --white: #ffffff;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

        /* ── SIDEBAR ── */
        .sidebar { width: var(--sidebar-w); background: var(--primary); position: fixed; top: 0; left: 0; height: 100vh; display: flex; flex-direction: column; z-index: 100; }
        .sidebar-brand { padding: 24px 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); }
        .brand-logo { display: flex; align-items: center; gap: 10px; }
        .brand-icon { width: 36px; height: 36px; background: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px; }
        .brand-text { color: white; font-size: 15px; font-weight: 600; }
        .brand-sub  { color: rgba(255,255,255,0.4); font-size: 10px; text-transform: uppercase; letter-spacing: 1px; margin-top: 2px; }
        .nav-section { padding: 20px 12px 8px; }
        .nav-label { color: rgba(255,255,255,0.3); font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; padding: 0 8px; margin-bottom: 6px; }
        .nav-link { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; color: rgba(255,255,255,0.6); text-decoration: none; font-size: 13.5px; transition: all 0.15s; margin-bottom: 2px; }
        .nav-link i { width: 18px; font-size: 14px; text-align: center; }
        .nav-link:hover { background: rgba(255,255,255,0.07); color: white; }
        .nav-link.active { background: var(--accent); color: white; }
        .sidebar-footer { margin-top: auto; padding: 16px 12px; border-top: 1px solid rgba(255,255,255,0.08); }
        .user-pill { display: flex; align-items: center; gap: 10px; padding: 8px; margin-bottom: 10px; }
        .user-avatar { width: 34px; height: 34px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 600; flex-shrink: 0; }
        .user-name { color: white; font-size: 13px; font-weight: 500; }
        .user-role { color: rgba(255,255,255,0.4); font-size: 11px; }
        .btn-logout { display: flex; align-items: center; gap: 8px; width: 100%; padding: 9px 12px; background: rgba(239,68,68,0.12); color: #fca5a5; border: none; border-radius: 8px; font-size: 13px; cursor: pointer; font-family: inherit; transition: background 0.15s; }
        .btn-logout:hover { background: rgba(239,68,68,0.22); }

        /* ── MAIN ── */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
        .topbar { background: var(--white); border-bottom: 1px solid var(--border); padding: 16px 32px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        .topbar-left .page-title { font-size: 18px; font-weight: 600; }
        .topbar-left .page-sub   { font-size: 12px; color: var(--muted); margin-top: 2px; }
        .btn-new { display: flex; align-items: center; gap: 8px; padding: 9px 18px; background: var(--accent); color: white; border: none; border-radius: 8px; font-size: 13.5px; font-weight: 500; cursor: pointer; font-family: inherit; transition: background 0.15s; }
        .btn-new:hover { background: #2563eb; }
        .content { padding: 28px 32px; flex: 1; }

        /* ── ALERTS ── */
        .alert { padding: 12px 16px; border-radius: 8px; font-size: 13.5px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── FILTER CARD ── */
        .card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
        .filter-bar { display: flex; align-items: center; gap: 12px; padding: 14px 20px; flex-wrap: wrap; }
        select, input { padding: 9px 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 13.5px; font-family: inherit; color: var(--text); background: var(--white); transition: border 0.15s; }
        select:focus, input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        .filter-select { min-width: 220px; }
        .btn-filter { padding: 9px 18px; background: var(--bg); color: var(--text); border: 1px solid var(--border); border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; font-family: inherit; transition: all 0.15s; display: flex; align-items: center; gap: 6px; }
        .btn-filter:hover { background: var(--border); }
        .btn-reset { padding: 9px 14px; background: none; color: var(--muted); border: 1px solid var(--border); border-radius: 8px; font-size: 13px; cursor: pointer; font-family: inherit; text-decoration: none; display: flex; align-items: center; gap: 6px; }
        .btn-reset:hover { background: var(--bg); }
        .count-pill { margin-left: auto; font-size: 12px; color: var(--muted); background: var(--bg); padding: 4px 10px; border-radius: 20px; border: 1px solid var(--border); white-space: nowrap; }

        /* ── TABLE ── */
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th { padding: 10px 16px; text-align: left; font-size: 11px; font-weight: 500; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; background: var(--bg); border-bottom: 1px solid var(--border); }
        tbody td { padding: 13px 16px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #fafafa; }

        .code-badge { display: inline-block; padding: 3px 9px; background: #1e293b; color: white; border-radius: 5px; font-size: 11px; font-weight: 500; letter-spacing: 0.5px; }
        .matiere-name { font-weight: 500; }
        .pill { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; }
        .pill-blue   { background: #eff6ff; color: #1d4ed8; }
        .pill-green  { background: #f0fdf4; color: #166534; }
        .pill-purple { background: #faf5ff; color: #6d28d9; }

        .td-actions { display: flex; align-items: center; gap: 6px; }
        .btn-edit { background: none; border: 1px solid var(--border); color: var(--muted); border-radius: 6px; padding: 5px 10px; font-size: 12px; cursor: pointer; font-family: inherit; transition: all 0.15s; }
        .btn-edit:hover { background: var(--accent-soft); border-color: var(--accent); color: var(--accent); }
        .btn-del  { background: none; border: 1px solid var(--border); color: var(--muted); border-radius: 6px; padding: 5px 10px; font-size: 12px; cursor: pointer; font-family: inherit; transition: all 0.15s; }
        .btn-del:hover { background: #fef2f2; border-color: #fecaca; color: #ef4444; }

        .empty { text-align: center; padding: 48px; color: var(--muted); font-size: 13px; }
        .empty i { font-size: 28px; margin-bottom: 10px; display: block; }

        /* ── MODAL ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 999; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal-box { background: var(--white); border-radius: 14px; width: 90%; max-width: 480px; border: 1px solid var(--border); overflow: hidden; }
        .modal-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .modal-header-left { display: flex; align-items: center; gap: 10px; }
        .modal-icon { width: 32px; height: 32px; background: var(--accent-soft); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 14px; }
        .modal-title { font-size: 15px; font-weight: 600; }
        .modal-close { background: none; border: none; color: var(--muted); cursor: pointer; font-size: 16px; padding: 4px; border-radius: 4px; }
        .modal-close:hover { background: var(--bg); color: var(--text); }
        .modal-body { padding: 20px; }
        .modal-footer { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: var(--bg); }

        .form-group { margin-bottom: 14px; }
        .form-label { display: block; font-size: 11px; font-weight: 500; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        input[type=text], input[type=number] { width: 100%; }

        .btn-save   { padding: 10px 22px; background: var(--accent); color: white; border: none; border-radius: 8px; font-size: 13.5px; font-weight: 500; cursor: pointer; font-family: inherit; transition: background 0.15s; }
        .btn-save:hover { background: #2563eb; }
        .btn-cancel { padding: 10px 16px; background: none; color: var(--muted); border: 1px solid var(--border); border-radius: 8px; font-size: 13.5px; cursor: pointer; font-family: inherit; }
        .btn-cancel:hover { background: var(--border); }
    </style>
</head>
<body>

{{-- ═══ SIDEBAR ═══ --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <div class="brand-icon"><i class="fas fa-university"></i></div>
            <div>
                <div class="brand-text">UAC HUB</div>
                <div class="brand-sub">Super Administration</div>
            </div>
        </div>
    </div>
    <div class="nav-section">
        <div class="nav-label">Principal</div>
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link"><i class="fas fa-chart-pie"></i> Tableau de bord</a>
        <a href="{{ route('superadmin.inscriptions') }}" class="nav-link"><i class="fas fa-file-signature"></i> Inscriptions</a>
    </div>
    <div class="nav-section">
        <div class="nav-label">Gestion</div>
        <a href="{{ route('superadmin.enseignants') }}" class="nav-link"><i class="fas fa-chalkboard-teacher"></i> Enseignants</a>
        <a href="{{ route('superadmin.matieres') }}" class="nav-link active"><i class="fas fa-book-open"></i> Matières & UE</a>
        <a href="{{ route('superadmin.notes') }}" class="nav-link"><i class="fas fa-graduation-cap"></i> Notes</a>
        <a href="{{ route('superadmin.academics') }}" class="nav-link"><i class="fas fa-sitemap"></i> Départements & Filières</a>
    </div>
    <div class="sidebar-footer">
        <div class="user-pill">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}{{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}
            </div>
            <div>
                <div class="user-name">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                <div class="user-role">Super Administrateur</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
        </form>
    </div>
</aside>

{{-- ═══ MAIN ═══ --}}
<main class="main">

    <div class="topbar">
        <div class="topbar-left">
            <div class="page-title">Catalogue des Matières</div>
            <div class="page-sub">Unités d'enseignement et crédits par filière</div>
        </div>
        <button class="btn-new" onclick="openModal()">
            <i class="fas fa-plus"></i> Nouvelle matière
        </button>
    </div>

    <div class="content">

        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        {{-- ── FILTRE ── --}}
        <div class="card">
            <form action="{{ route('superadmin.matieres') }}" method="GET">
                <div class="filter-bar">
                    <select name="filiere_filter" class="filter-select">
                        <option value="">Toutes les filières</option>
                        @foreach($filieres ?? [] as $f)
                            <option value="{{ $f->id_filiere }}" {{ request('filiere_filter') == $f->id_filiere ? 'selected' : '' }}>
                                {{ $f->nom_filiere }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    @if(request('filiere_filter'))
                        <a href="{{ route('superadmin.matieres') }}" class="btn-reset">
                            <i class="fas fa-times"></i> Réinitialiser
                        </a>
                    @endif
                    <span class="count-pill">
                        <i class="fas fa-book" style="margin-right:4px;font-size:10px;"></i>
                        {{ $matieres->count() }} matière(s)
                    </span>
                </div>
            </form>
        </div>

        {{-- ── TABLE ── --}}
        <div class="card">
            @if($matieres->isEmpty())
                <div class="empty">
                    <i class="fas fa-book-open"></i>
                    Aucune matière enregistrée
                    @if(request('filiere_filter')) pour cette filière @endif.
                </div>
            @else
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Intitulé</th>
                        <th>Filière</th>
                        <th>Semestre</th>
                        <th style="text-align:center;">Crédits</th>
                        <th style="text-align:right; padding-right:20px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matieres ?? [] as $m)
                    <tr>
                        <td><span class="code-badge">{{ $m->code_matiere ?? '—' }}</span></td>
                        <td><span class="matiere-name">{{ $m->nom_matiere }}</span></td>
                        <td><span class="pill pill-blue">{{ $m->filiere->nom_filiere ?? '—' }}</span></td>
                        <td>
                            @if($m->semestre)
                                <span class="pill pill-purple">{{ $m->semestre }}</span>
                            @else
                                <span style="color:var(--muted);font-size:12px;">—</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <span class="pill pill-green">{{ $m->credits ?? '—' }} ECTS</span>
                        </td>
                        <td style="text-align:right; padding-right:16px;">
                            <div class="td-actions" style="justify-content:flex-end;">
                                <button class="btn-edit"
                                    onclick="openEdit({{ $m->id_matiere }}, '{{ addslashes($m->nom_matiere) }}', '{{ $m->code_matiere }}', {{ $m->credits ?? 3 }}, '{{ $m->semestre }}', {{ $m->id_filiere ?? 'null' }})">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <form action="{{ route('superadmin.matieres.delete', $m->id_matiere) }}" method="POST"
                                      onsubmit="return confirm('Supprimer « {{ $m->nom_matiere }} » ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

    </div>
</main>

{{-- ═══ MODAL AJOUT ═══ --}}
<div class="modal-overlay" id="matiereModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-header-left">
                <div class="modal-icon"><i class="fas fa-book"></i></div>
                <span class="modal-title" id="modalTitle">Nouvelle matière</span>
            </div>
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>

        <form id="matiereForm" method="POST">
            @csrf
            <span id="methodField"></span>

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Intitulé de la matière</label>
                    <input type="text" name="nom_matiere" id="f_nom" placeholder="Ex : Algèbre Fondamentale" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Code UE</label>
                    <input type="text" name="code_matiere" id="f_code" placeholder="Ex : MAT101" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Filière</label>
                    <select name="id_filiere" id="f_filiere" required style="width:100%;">
                        <option value="" disabled selected>Sélectionner une filière...</option>
                        @foreach($filieres as $f)
                            <option value="{{ $f->id_filiere }}">{{ $f->nom_filiere }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Crédits ECTS</label>
                        <input type="number" name="credits" id="f_credits" min="1" max="30" value="3" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Semestre</label>
                        <select name="semestre" id="f_semestre" style="width:100%;">
                            @foreach(['S1','S2','S3','S4','S5','S6'] as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Annuler</button>
                <button type="submit" class="btn-save"><i class="fas fa-save" style="margin-right:6px;"></i> Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modalTitle').textContent = 'Nouvelle matière';
        document.getElementById('matiereForm').action    = '{{ route("superadmin.matieres.store") }}';
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('f_nom').value     = '';
        document.getElementById('f_code').value    = '';
        document.getElementById('f_credits').value = 3;
        document.getElementById('f_semestre').value = 'S1';
        document.getElementById('f_filiere').value  = '';
        document.getElementById('matiereModal').classList.add('open');
    }

    function openEdit(id, nom, code, credits, semestre, idFiliere) {
        document.getElementById('modalTitle').textContent = 'Modifier la matière';
        document.getElementById('matiereForm').action    = '/super-admin/matieres/' + id;
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('f_nom').value     = nom;
        document.getElementById('f_code').value    = code;
        document.getElementById('f_credits').value = credits;
        document.getElementById('f_semestre').value = semestre;
        if (idFiliere) document.getElementById('f_filiere').value = idFiliere;
        document.getElementById('matiereModal').classList.add('open');
    }

    function closeModal() {
        document.getElementById('matiereModal').classList.remove('open');
    }

    document.getElementById('matiereModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
</body>
</html>
