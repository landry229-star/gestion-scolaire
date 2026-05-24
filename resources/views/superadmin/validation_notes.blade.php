<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approbation des Notes — UAC</title>
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
        .page-title { font-size: 18px; font-weight: 600; }
        .page-sub   { font-size: 12px; color: var(--muted); margin-top: 2px; }
        .content { padding: 28px 32px; flex: 1; }

        /* ── ALERTS ── */
        .alert { padding: 12px 16px; border-radius: 8px; font-size: 13.5px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border: 1px solid transparent; }
        .alert-success { background: #f0fdf4; color: #166534; border-color: #bbf7d0; border-left: 4px solid #22c55e; }
        .alert-danger { background: #fef2f2; color: #991b1b; border-color: #fca5a5; border-left: 4px solid #ef4444; }
        .alert-info    { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; border-left: 4px solid var(--accent); }

        /* ── CARD ── */
        .card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
        .card-header { padding: 14px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .card-header-left { display: flex; align-items: center; gap: 10px; }
        .card-icon { width: 30px; height: 30px; border-radius: 7px; display: flex; align-items: center; justify-content: center; font-size: 13px; background: #eff6ff; color: var(--accent); }
        .card-title { font-size: 14px; font-weight: 600; }
        .count-pill { font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 500; background: #fef9c3; color: #854d0e; border: 1px solid #fde68a; }

        /* ── TABLE ── */
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th { padding: 10px 16px; text-align: left; font-size: 11px; font-weight: 500; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; background: var(--bg); border-bottom: 1px solid var(--border); }
        tbody td { padding: 13px 16px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #fafafa; }

        .matiere-name { font-weight: 500; }
        .matiere-code { font-size: 11px; color: var(--muted); margin-top: 2px; }

        .pill { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; }
        .pill-blue   { background: #eff6ff; color: #1d4ed8; }
        .pill-purple { background: #faf5ff; color: #6d28d9; }

        .ens-info { display: flex; align-items: center; gap: 8px; }
        .ens-avatar { width: 28px; height: 28px; border-radius: 50%; background: var(--accent-soft); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 600; flex-shrink: 0; }
        .ens-name  { font-size: 13px; font-weight: 500; }

        .btn-approve {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 16px; background: #f0fdf4; color: #16a34a;
            border: 1px solid #bbf7d0; border-radius: 7px;
            font-size: 12px; font-weight: 500; cursor: pointer;
            font-family: inherit; transition: all 0.15s; white-space: nowrap;
        }
        .btn-approve:hover { background: #dcfce7; border-color: #86efac; }

        /* ── EMPTY ── */
        .empty { text-align: center; padding: 60px 20px; }
        .empty-icon { width: 64px; height: 64px; border-radius: 50%; background: #f0fdf4; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
        .empty-icon i { font-size: 26px; color: #22c55e; }
        .empty-title { font-size: 15px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .empty-sub   { font-size: 13px; color: var(--muted); }
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
        <a href="{{ route('superadmin.matieres') }}" class="nav-link"><i class="fas fa-book-open"></i> Matières & UE</a>
        <a href="{{ route('superadmin.notes') }}" class="nav-link active">
            <i class="fas fa-graduation-cap"></i> Notes
            @if(isset($matieres) && $matieres->count() > 0)
                <span class="nav-badge">{{ $matieres->count() }}</span>
            @endif
        </a>
        <a href="{{ route('superadmin.academics') }}" class="nav-link"><i class="fas fa-sitemap"></i> Départements & Filières</a>
    </div>
    <div class="sidebar-footer">
        <div class="user-pill">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->nom ?? 'A', 0, 1)) }}{{ strtoupper(substr(Auth::user()->prenom ?? 'A', 0, 1)) }}
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
        <div>
            <div class="page-title">Approbation des Notes</div>
            <div class="page-sub">Validation et publication des notes par matière</div>
        </div>
    </div>

    <div class="content">

        {{-- Messages de Notification --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Une fois approuvées, les notes seront <strong>immédiatement visibles</strong> par les étudiants concernés.
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon"><i class="fas fa-clock"></i></div>
                    <span class="card-title">Matières en attente d'approbation</span>
                </div>
                @if($matieres->count() > 0)
                    <span class="count-pill">
                        <i class="fas fa-hourglass-half" style="margin-right:4px;font-size:10px;"></i>
                        {{ $matieres->count() }} en attente
                    </span>
                @endif
            </div>

            @if($matieres->isEmpty())
                <div class="empty">
                    <div class="empty-icon">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div class="empty-title">Tout est approuvé !</div>
                    <div class="empty-sub">Aucune matière en attente de validation pour le moment.</div>
                </div>
            @else
            <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Matière</th>
                        <th>Filière</th>
                        <th>Enseignant</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matieres as $matiere)
                    <tr>
                        <td>
                            <div class="matiere-name">{{ $matiere->nom_matiere }}</div>
                            @if($matiere->code_matiere)
                                <div class="matiere-code">{{ $matiere->code_matiere }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="pill pill-blue">{{ $matiere->filiere->nom_filiere ?? '—' }}</span>
                        </td>
                        <td>
                            @if($matiere->enseignant)
                                <div class="ens-info">
                                    <div class="ens-avatar">
                                        {{ strtoupper(substr($matiere->enseignant->nom ?? 'E', 0, 1)) }}{{ strtoupper(substr($matiere->enseignant->prenom ?? 'N', 0, 1)) }}
                                    </div>
                                    <div class="ens-name">{{ $matiere->enseignant->nom }} {{ $matiere->enseignant->prenom }}</div>
                                </div>
                            @else
                                <span style="color:var(--muted);font-size:12px;">Non assigné</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <form action="{{ route('superadmin.notes.approve', $matiere->id_matiere) }}" method="POST"
                                  onsubmit="return confirm('Approuver et publier les notes de « {{ addslashes($matiere->nom_matiere) }} » ? Les étudiants y auront accès immédiatement.')">
                                @csrf
                                <button type="submit" class="btn-approve">
                                    <i class="fas fa-check-circle"></i> Approuver & Publier
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
</main>

</body>
</html>
