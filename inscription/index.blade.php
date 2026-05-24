<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation des Inscriptions — UAC</title>
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
        .nav-badge { margin-left: auto; background: #ef4444; color: white; font-size: 10px; font-weight: 600; padding: 2px 6px; border-radius: 20px; }
        .sidebar-footer { margin-top: auto; padding: 16px 12px; border-top: 1px solid rgba(255,255,255,0.08); }
        .user-pill { display: flex; align-items: center; gap: 10px; padding: 8px; margin-bottom: 10px; }
        .user-avatar { width: 34px; height: 34px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 600; flex-shrink: 0; }
        .user-name { color: white; font-size: 13px; font-weight: 500; }
        .user-role { color: rgba(255,255,255,0.4); font-size: 11px; }
        .btn-logout { display: flex; align-items: center; gap: 8px; width: 100%; padding: 9px 12px; background: rgba(239,68,68,0.12); color: #fca5a5; border: none; border-radius: 8px; font-size: 13px; cursor: pointer; font-family: inherit; transition: background 0.15s; }
        .btn-logout:hover { background: rgba(239,68,68,0.22); }

        /* ── MAIN ── */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
        .topbar { background: var(--white); border-bottom: 1px solid var(--border); padding: 16px 32px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
        .topbar-left .page-title { font-size: 18px; font-weight: 600; }
        .topbar-left .page-sub   { font-size: 12px; color: var(--muted); margin-top: 2px; }
        .content { padding: 28px 32px; flex: 1; }

        /* ── ALERTS ── */
        .alert { padding: 12px 16px; border-radius: 8px; font-size: 13.5px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── STATS ── */
        .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 22px; }
        .stat-mini { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 14px 18px; display: flex; align-items: center; gap: 12px; }
        .stat-mini-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
        .stat-mini-val  { font-size: 20px; font-weight: 700; line-height: 1; }
        .stat-mini-lbl  { font-size: 11px; color: var(--muted); margin-top: 2px; }

        /* ── CARD ── */
        .card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
        .card-header { padding: 14px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .card-title { font-size: 14px; font-weight: 600; }
        .count-pill { font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 500; }
        .pill-warn { background: #fef9c3; color: #854d0e; border: 1px solid #fde68a; }

        /* ── TABLE ── */
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th { padding: 10px 16px; text-align: left; font-size: 11px; font-weight: 500; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; background: var(--bg); border-bottom: 1px solid var(--border); }
        tbody td { padding: 13px 16px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #fafafa; }

        .cand-info { display: flex; align-items: center; gap: 10px; }
        .cand-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--accent-soft); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; flex-shrink: 0; }
        .cand-name  { font-weight: 500; font-size: 13px; }
        .cand-email { font-size: 11px; color: var(--muted); margin-top: 1px; }

        .pill { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; }
        .pill-blue { background: #eff6ff; color: #1d4ed8; }

        .date-text { font-size: 12px; color: var(--text); }
        .date-sub  { font-size: 11px; color: var(--muted); margin-top: 1px; }

        /* Pièces */
        .doc-btns { display: flex; gap: 6px; flex-wrap: wrap; }
        .btn-doc { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 11px; color: var(--muted); text-decoration: none; background: var(--white); transition: all 0.15s; white-space: nowrap; }
        .btn-doc:hover { background: var(--bg); border-color: var(--accent); color: var(--accent); }
        .btn-doc i { font-size: 11px; }

        /* Actions */
        .action-btns { display: flex; gap: 6px; justify-content: flex-end; }
        .btn-approve { display: flex; align-items: center; gap: 5px; padding: 6px 14px; background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; border-radius: 7px; font-size: 12px; font-weight: 500; cursor: pointer; font-family: inherit; transition: all 0.15s; }
        .btn-approve:hover { background: #dcfce7; border-color: #86efac; }
        .btn-reject  { display: flex; align-items: center; gap: 5px; padding: 6px 14px; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 7px; font-size: 12px; font-weight: 500; cursor: pointer; font-family: inherit; transition: all 0.15s; }
        .btn-reject:hover { background: #fee2e2; border-color: #fca5a5; }

        .empty { text-align: center; padding: 52px; color: var(--muted); font-size: 13px; }
        .empty i { font-size: 32px; margin-bottom: 12px; display: block; color: #22c55e; }

        /* ── MODAL ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 999; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal-box { background: var(--white); border-radius: 14px; width: 90%; max-width: 440px; border: 1px solid var(--border); overflow: hidden; }
        .modal-header { padding: 14px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .modal-header-left { display: flex; align-items: center; gap: 10px; }
        .modal-icon { width: 30px; height: 30px; background: #fef2f2; border-radius: 7px; display: flex; align-items: center; justify-content: center; color: #dc2626; font-size: 13px; }
        .modal-title { font-size: 15px; font-weight: 600; }
        .modal-sub   { font-size: 11px; color: var(--muted); margin-top: 2px; }
        .modal-close { background: none; border: none; color: var(--muted); cursor: pointer; font-size: 16px; padding: 4px; border-radius: 4px; }
        .modal-close:hover { background: var(--bg); }
        .modal-body { padding: 20px; }
        .modal-footer { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: var(--bg); }
        .form-label { display: block; font-size: 11px; font-weight: 500; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        textarea { width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 13.5px; font-family: inherit; resize: vertical; min-height: 90px; }
        textarea:focus { outline: none; border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,0.1); }
        .btn-confirm-reject { padding: 9px 20px; background: #dc2626; color: white; border: none; border-radius: 8px; font-size: 13.5px; font-weight: 500; cursor: pointer; font-family: inherit; display: flex; align-items: center; gap: 7px; }
        .btn-confirm-reject:hover { background: #b91c1c; }
        .btn-cancel { padding: 9px 14px; background: none; color: var(--muted); border: 1px solid var(--border); border-radius: 8px; font-size: 13.5px; cursor: pointer; font-family: inherit; }
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
        <a href="{{ route('superadmin.inscriptions') }}" class="nav-link active">
            <i class="fas fa-file-signature"></i> Inscriptions
            @if($inscriptions->count() > 0)
                <span class="nav-badge">{{ $inscriptions->count() }}</span>
            @endif
        </a>
    </div>
    <div class="nav-section">
        <div class="nav-label">Gestion</div>
        <a href="{{ route('superadmin.enseignants') }}" class="nav-link"><i class="fas fa-chalkboard-teacher"></i> Enseignants</a>
        <a href="{{ route('superadmin.matieres') }}" class="nav-link"><i class="fas fa-book-open"></i> Matières & UE</a>
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
            <div class="page-title">Validation des Inscriptions</div>
            <div class="page-sub">Examen des dossiers et approbation des nouveaux étudiants</div>
        </div>
    </div>

    <div class="content">

        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        {{-- Stats --}}
        <div class="stats-row">
            <div class="stat-mini">
                <div class="stat-mini-icon" style="background:#fef9c3;">
                    <i class="fas fa-hourglass-half" style="color:#ca8a04;"></i>
                </div>
                <div>
                    <div class="stat-mini-val" style="color:#ca8a04;">{{ $inscriptions->count() }}</div>
                    <div class="stat-mini-lbl">En attente</div>
                </div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-icon" style="background:#f0fdf4;">
                    <i class="fas fa-check-circle" style="color:#16a34a;"></i>
                </div>
                <div>
                    <div class="stat-mini-val" style="color:#16a34a;">
                        {{ \App\Models\Inscription::where('statut_dossier','valide')->count() }}
                    </div>
                    <div class="stat-mini-lbl">Validés</div>
                </div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-icon" style="background:#fef2f2;">
                    <i class="fas fa-times-circle" style="color:#dc2626;"></i>
                </div>
                <div>
                    <div class="stat-mini-val" style="color:#dc2626;">
                        {{ \App\Models\Inscription::where('statut_dossier','rejete')->count() }}
                    </div>
                    <div class="stat-mini-lbl">Rejetés</div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Dossiers en attente de validation</span>
                @if($inscriptions->count() > 0)
                    <span class="count-pill pill-warn">
                        <i class="fas fa-clock" style="margin-right:4px;font-size:10px;"></i>
                        {{ $inscriptions->count() }} dossier(s)
                    </span>
                @endif
            </div>

            @if($inscriptions->isEmpty())
                <div class="empty">
                    <i class="fas fa-check-circle"></i>
                    <p style="font-weight:500;color:var(--text);">Tout est à jour !</p>
                    <p style="margin-top:4px;">Aucun dossier en attente de validation.</p>
                </div>
            @else
            <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Candidat</th>
                        <th>Filière</th>
                        <th>Soumis le</th>
                        <th style="text-align:center;">Pièces jointes</th>
                        <th style="text-align:right;padding-right:20px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscriptions as $ins)
                    <tr>
                        {{-- Candidat --}}
                        <td>
                            <div class="cand-info">
                                <div class="cand-avatar">
                                    {{ strtoupper(substr($ins->etudiant->nom ?? 'E', 0, 1)) }}{{ strtoupper(substr($ins->etudiant->prenom ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="cand-name">{{ $ins->etudiant->nom ?? '—' }} {{ $ins->etudiant->prenom ?? '' }}</div>
                                    <div class="cand-email">{{ $ins->etudiant->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Filière --}}
                        <td>
                            <span class="pill pill-blue">{{ $ins->filiere->nom_filiere ?? '—' }}</span>
                        </td>

                        {{-- Date --}}
                        <td>
                            <div class="date-text">{{ $ins->created_at->format('d/m/Y') }}</div>
                            <div class="date-sub">{{ $ins->created_at->format('H:i') }}</div>
                        </td>

                        {{-- Pièces jointes --}}
                        <td style="text-align:center;">
                            <div class="doc-btns" style="justify-content:center;">
                                @if($ins->etudiant->document_cip)
                                    <a href="{{ asset('storage/'.$ins->etudiant->document_acte_naissance) }}" target="_blank" class="btn-doc">
                                        <i class="fas fa-file-alt"></i> Acte
                                    </a>
                                @endif
                                @if($ins->etudiant->diplome_bac)
                                    <a href="{{ asset('storage/'.$ins->etudiant->diplome_bac) }}" target="_blank" class="btn-doc">
                                        <i class="fas fa-graduation-cap"></i> BAC
                                    </a>
                                @endif
                                @if($ins->etudiant->photo)
                                    <a href="{{ asset('storage/'.$ins->etudiant->photo) }}" target="_blank" class="btn-doc">
                                        <i class="fas fa-image"></i> Photo
                                    </a>
                                @endif
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td style="text-align:right;padding-right:16px;">
                            <div class="action-btns">
                                {{-- Valider --}}
                                <form action="{{ route('superadmin.inscriptions.valider', $ins->id_inscription) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-approve">
                                        <i class="fas fa-check"></i> Approuver
                                    </button>
                                </form>
                                {{-- Rejeter --}}
                                <button class="btn-reject" onclick="openRejet({{ $ins->id_inscription }}, '{{ addslashes($ins->etudiant->nom ?? '') }} {{ addslashes($ins->etudiant->prenom ?? '') }}')">
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
</main>

{{-- ═══ MODAL REJET ═══ --}}
<div class="modal-overlay" id="rejetModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-header-left">
                <div class="modal-icon"><i class="fas fa-times"></i></div>
                <div>
                    <div class="modal-title">Rejeter le dossier</div>
                    <div class="modal-sub" id="rejetNom"></div>
                </div>
            </div>
            <button class="modal-close" onclick="closeRejet()"><i class="fas fa-times"></i></button>
        </div>
        <form id="rejetForm" method="POST">
            @csrf
            <div class="modal-body">
                <label class="form-label">Motif du rejet</label>
                <textarea name="motif_rejet" placeholder="Ex : Diplôme illisible, photo non conforme, acte de naissance manquant..." required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeRejet()">Annuler</button>
                <button type="submit" class="btn-confirm-reject">
                    <i class="fas fa-times-circle"></i> Confirmer le rejet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejet(id, nom) {
        document.getElementById('rejetForm').action = '/super-admin/inscriptions/rejeter/' + id;
        document.getElementById('rejetNom').textContent  = nom;
        document.getElementById('rejetModal').classList.add('open');
    }
    function closeRejet() {
        document.getElementById('rejetModal').classList.remove('open');
    }
    document.getElementById('rejetModal').addEventListener('click', function(e) {
        if (e.target === this) closeRejet();
    });
</script>
</body>
</html>
