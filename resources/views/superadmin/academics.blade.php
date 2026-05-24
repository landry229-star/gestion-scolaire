@extends('layouts.superadmin')

@section('title', 'Académique')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800&display=swap');

*, *::before, *::after { box-sizing: border-box; }

:root {
    --blue:       #2563eb;
    --blue-light: #eff6ff;
    --blue-border:#bfdbfe;
    --violet:     #7c3aed;
    --violet-light:#f5f3ff;
    --violet-border:#ddd6fe;
    --green:      #059669;
    --green-light:#f0fdf4;
    --green-border:#a7f3d0;
    --gray-50:    #f8fafc;
    --gray-100:   #f1f5f9;
    --gray-200:   #e2e8f0;
    --gray-400:   #94a3b8;
    --gray-600:   #475569;
    --gray-900:   #0f172a;
    --radius:     14px;
    --radius-sm:  8px;
    --ease:       cubic-bezier(.22,1,.36,1);
}

.page {
    font-family: 'DM Sans', sans-serif;
    max-width: 1140px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
    color: var(--gray-900);
}

/* ══ PAGE HEADER ══ */
.page-header {
    display: flex; align-items: flex-end; justify-content: space-between;
    margin-bottom: 2rem; padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--gray-200);
    flex-wrap: wrap; gap: 1rem;
}
.page-title-wrap {}
.page-title {
    font-size: 1.8rem; font-weight: 800;
    letter-spacing: -0.05em; color: var(--gray-900); margin: 0;
    line-height: 1.1;
}
.page-title span { color: var(--blue); }
.page-subtitle { font-size: 0.8rem; color: var(--gray-400); margin: 0.3rem 0 0; }

.stats-bar { display: flex; gap: 0.5rem; flex-wrap: wrap; }
.stat-chip {
    display: inline-flex; align-items: center; gap: 0.45rem;
    padding: 0.3rem 0.85rem; border-radius: 999px;
    font-size: 0.72rem; font-weight: 700; letter-spacing: 0.02em;
    border: 1.5px solid;
}
.stat-chip-blue   { background: var(--blue-light);   color: var(--blue);   border-color: var(--blue-border); }
.stat-chip-violet { background: var(--violet-light); color: var(--violet); border-color: var(--violet-border); }
.stat-chip-green  { background: var(--green-light);  color: var(--green);  border-color: var(--green-border); }

/* ══ FORMS CONTAINER ══ */
.forms-container {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 1.5rem; margin-bottom: 2.75rem;
}
@media (max-width: 700px) { .forms-container { grid-template-columns: 1fr; } }

/* ── Carte formulaire moderne ── */
.form-card {
    position: relative;
    background: #fff;
    border-radius: 18px;
    padding: 0;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06), 0 0 0 1.5px var(--gray-200);
    transition: box-shadow 0.2s, transform 0.2s;
}
.form-card:hover {
    box-shadow: 0 6px 24px rgba(0,0,0,0.09), 0 0 0 1.5px var(--gray-200);
    transform: translateY(-2px);
}

/* Bande colorée en haut */
.form-card-topbar {
    height: 4px; width: 100%;
}

.form-card-inner { padding: 1.5rem 1.5rem 1.6rem; }

/* Header de la carte */
.form-card-header {
    display: flex; align-items: center; gap: 0.85rem;
    margin-bottom: 1.35rem;
}
.form-card-icon {
    width: 42px; height: 42px; border-radius: 12px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem;
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
}
.form-card-icon-blue   { background: linear-gradient(135deg, #2563eb, #38bdf8); }
.form-card-icon-violet { background: linear-gradient(135deg, #7c3aed, #a78bfa); }

.form-card-title {
    font-size: 0.92rem; font-weight: 800;
    color: var(--gray-900); letter-spacing: -0.02em; margin: 0;
}
.form-card-sub {
    font-size: 0.71rem; color: var(--gray-400); margin-top: 0.1rem; font-weight: 400;
}

/* Séparateur */
.form-card-divider {
    height: 1px; background: var(--gray-100);
    margin: 0 0 1.25rem;
}

/* Groupes */
.form-row { display: flex; gap: 0.65rem; align-items: flex-end; flex-wrap: wrap; margin-bottom: 0.75rem; }
.form-row:last-child { margin-bottom: 0; }
.form-group { display: flex; flex-direction: column; gap: 0.38rem; flex: 1; min-width: 110px; }
.form-group label {
    font-size: 0.7rem; font-weight: 700; color: var(--gray-600);
    letter-spacing: 0.04em; text-transform: uppercase;
}

/* Inputs */
.form-group input,
.form-group select {
    height: 42px; padding: 0 0.9rem;
    border: 1.5px solid var(--gray-200); border-radius: 10px;
    font-size: 0.86rem; color: var(--gray-900); background: var(--gray-50);
    outline: none; font-family: inherit; width: 100%;
    transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
    appearance: none; -webkit-appearance: none;
}
.form-group select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'%3E%3Cpath fill='%2394a3b8' d='M5 7L.67 2h8.66z'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 0.9rem center;
    padding-right: 2.25rem; cursor: pointer;
}
.form-group input:focus, .form-group select:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    background: #fff;
}
.form-card:has(.form-card-icon-violet) .form-group input:focus,
.form-card:has(.form-card-icon-violet) .form-group select:focus {
    border-color: var(--violet);
    box-shadow: 0 0 0 3px rgba(124,58,237,0.1);
}
.form-group input::placeholder { color: #b8c0cc; font-style: italic; font-size: 0.82rem; }

/* Boutons */
.btn {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0 1.15rem; height: 42px; border-radius: 10px;
    font-size: 0.83rem; font-weight: 700; border: none;
    cursor: pointer; font-family: inherit; white-space: nowrap;
    transition: all 0.18s; letter-spacing: -0.01em; flex-shrink: 0;
}
.btn:active { transform: scale(0.96); }
.btn-blue {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: #fff; box-shadow: 0 3px 10px rgba(37,99,235,0.35);
}
.btn-blue:hover { background: linear-gradient(135deg, #1d4ed8, #2563eb); box-shadow: 0 4px 14px rgba(37,99,235,0.45); }
.btn-violet {
    background: linear-gradient(135deg, #7c3aed, #8b5cf6);
    color: #fff; box-shadow: 0 3px 10px rgba(124,58,237,0.35);
}
.btn-violet:hover { background: linear-gradient(135deg, #6d28d9, #7c3aed); box-shadow: 0 4px 14px rgba(124,58,237,0.45); }

/* ══ EXPLORER SECTION HEADER ══ */
.section-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;
}
.section-title {
    display: flex; align-items: center; gap: 0.75rem;
    font-size: 1rem; font-weight: 800; color: var(--gray-900);
    letter-spacing: -0.02em;
}
.section-title-icon {
    width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--blue), var(--violet));
    display: flex; align-items: center; justify-content: center;
    font-size: 0.95rem; box-shadow: 0 2px 8px rgba(37,99,235,0.25);
}

/* ══ ACCORDION TREE ══ */
.tree { display: flex; flex-direction: column; gap: 0.85rem; }

/* ── Département ── */
.dept-node {
    border-radius: var(--radius);
    overflow: hidden;
    border: 1.5px solid var(--blue-border);
    background: #fff;
    box-shadow: 0 1px 4px rgba(37,99,235,0.06);
    transition: box-shadow 0.2s var(--ease);
}
.dept-node.open {
    box-shadow: 0 4px 20px rgba(37,99,235,0.1);
    border-color: #93c5fd;
}

.dept-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.95rem 1.2rem;
    cursor: pointer; user-select: none;
    background: linear-gradient(105deg, var(--blue-light) 0%, #fff 55%);
    transition: background 0.15s;
    gap: 0.75rem;
}
.dept-row:hover { background: linear-gradient(105deg, #dbeafe 0%, #f8fafc 55%); }

.dept-row-left  { display: flex; align-items: center; gap: 0.85rem; flex: 1; min-width: 0; }
.dept-row-right { display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0; }

.dept-avatar {
    width: 42px; height: 42px; border-radius: 11px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--blue), #38bdf8);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; box-shadow: 0 3px 10px rgba(37,99,235,0.28);
}
.dept-info { min-width: 0; }
.dept-name {
    font-size: 0.95rem; font-weight: 800; color: var(--gray-900);
    letter-spacing: -0.02em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.dept-sub { font-size: 0.71rem; color: var(--gray-400); margin-top: 0.15rem; }

.caret {
    width: 28px; height: 28px; border-radius: 7px; flex-shrink: 0;
    background: var(--blue-light); border: 1.5px solid var(--blue-border);
    display: flex; align-items: center; justify-content: center;
    color: var(--blue); font-size: 0.55rem; font-weight: 900;
    transition: transform 0.25s var(--ease), background 0.15s;
    pointer-events: none;
}
.dept-node.open .dept-row .caret,
.fil-node.open  .fil-row  .caret  { transform: rotate(90deg); }

.dept-body {
    display: none;
    padding: 1rem 1.2rem 1.2rem;
    border-top: 1.5px solid var(--blue-border);
    background: #f7fbff;
}
.dept-node.open .dept-body {
    display: block;
    animation: fadeDown 0.22s var(--ease);
}
@keyframes fadeDown { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:none; } }

/* ── Filières list ── */
.fil-list { display: flex; flex-direction: column; gap: 0.6rem; }

.fil-node {
    border-radius: 11px; overflow: hidden;
    border: 1.5px solid var(--violet-border); background: #fff;
    box-shadow: 0 1px 3px rgba(124,58,237,0.06);
    transition: box-shadow 0.2s var(--ease);
}
.fil-node.open { box-shadow: 0 3px 12px rgba(124,58,237,0.1); border-color: #c4b5fd; }

.fil-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.8rem 1rem;
    cursor: pointer; user-select: none;
    background: linear-gradient(105deg, var(--violet-light) 0%, #fff 55%);
    transition: background 0.15s; gap: 0.75rem;
}
.fil-row:hover { background: linear-gradient(105deg, #ede9fe 0%, #fafafa 55%); }

.fil-row-left  { display: flex; align-items: center; gap: 0.75rem; flex: 1; min-width: 0; }
.fil-row-right { display: flex; align-items: center; gap: 0.4rem; flex-shrink: 0; }

.fil-avatar {
    width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--violet), #a78bfa);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem; box-shadow: 0 2px 7px rgba(124,58,237,0.25);
}
.fil-info { min-width: 0; }
.fil-name {
    font-size: 0.875rem; font-weight: 700; color: #1e1b4b;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.fil-sub { font-size: 0.68rem; color: var(--violet); margin-top: 0.1rem; display: flex; align-items: center; gap: 0.4rem; }

.code-badge {
    display: inline-block;
    background: var(--violet-light); color: var(--violet);
    border: 1px solid var(--violet-border);
    border-radius: 5px; padding: 0.08rem 0.45rem;
    font-size: 0.65rem; font-weight: 800; font-family: monospace;
    letter-spacing: 0.05em; flex-shrink: 0;
}

.fil-caret {
    width: 24px; height: 24px; border-radius: 6px; flex-shrink: 0;
    background: var(--violet-light); border: 1.5px solid var(--violet-border);
    display: flex; align-items: center; justify-content: center;
    color: var(--violet); font-size: 0.5rem; font-weight: 900;
    transition: transform 0.22s var(--ease); pointer-events: none;
}
.fil-node.open .fil-row .fil-caret { transform: rotate(90deg); }

.fil-body {
    display: none;
    padding: 1rem;
    border-top: 1.5px solid var(--violet-border);
    background: #fdfcff;
}
.fil-node.open .fil-body { display: block; animation: fadeDown 0.2s var(--ease); }

/* ── Semestres ── */
.semestres-wrap { display: flex; flex-direction: column; gap: 1rem; }

.sem-block {}
.sem-header {
    display: flex; align-items: center; gap: 0.6rem;
    margin-bottom: 0.6rem;
}
.sem-tag {
    display: inline-flex; align-items: center;
    padding: 0.18rem 0.75rem; border-radius: 6px;
    font-size: 0.72rem; font-weight: 800; letter-spacing: 0.06em;
    border: 1.5px solid;
}
.sem-count {
    font-size: 0.68rem; color: var(--gray-400); font-weight: 600;
}

/* ── Tableau matières ── */
.mat-table-wrap {
    border: 1.5px solid var(--green-border);
    border-radius: 10px; overflow: hidden;
    background: #fff;
}
.mat-table {
    width: 100%; border-collapse: collapse;
    font-size: 0.82rem;
}
.mat-table-wrap {
    border-radius: 10px; overflow: hidden;
    border: 1px solid var(--gray-200);
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.mat-table thead {
    background: var(--gray-50);
}
.mat-table thead th {
    padding: 0.65rem 1rem;
    text-align: left; white-space: nowrap;
    font-size: 0.66rem; font-weight: 700;
    letter-spacing: 0.08em; text-transform: uppercase;
    color: var(--gray-400);
    border-bottom: 1.5px solid var(--gray-200);
}
.mat-table thead th.th-center { text-align: center; }

.mat-table tbody tr {
    border-bottom: 1px solid var(--gray-100);
    transition: background 0.1s;
}
.mat-table tbody tr:last-child { border-bottom: none; }
.mat-table tbody tr:hover { background: #f8fbff; }

.mat-table tbody td {
    padding: 0.65rem 1rem;
    color: var(--gray-900); vertical-align: middle;
}

/* Numéro de ligne */
.mat-num {
    font-size: 0.68rem; color: var(--gray-400);
    font-weight: 700; font-variant-numeric: tabular-nums;
    min-width: 22px; text-align: center;
}

.mat-code-cell {
    display: inline-flex; align-items: center;
    font-family: 'Courier New', monospace;
    font-size: 0.73rem; font-weight: 800;
    background: var(--gray-100); color: var(--gray-600);
    border: 1px solid var(--gray-200);
    border-radius: 6px; padding: 0.18rem 0.6rem;
    letter-spacing: 0.04em; white-space: nowrap;
}

.mat-name-cell {
    display: flex; align-items: center; gap: 0.55rem;
}
.mat-name-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--green); flex-shrink: 0; opacity: 0.7;
}
.mat-name-text {
    font-weight: 600; color: var(--gray-900); font-size: 0.855rem;
}

.mat-credit-cell { text-align: center; }
.mat-credit-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 30px; height: 24px; border-radius: 6px;
    padding: 0 0.5rem;
    background: var(--green-light); color: var(--green);
    border: 1.5px solid var(--green-border);
    font-size: 0.72rem; font-weight: 800;
}
.mat-credit-none {
    color: var(--gray-400); font-size: 0.72rem;
}

/* ── Empty states ── */
.empty-msg {
    display: flex; align-items: center; gap: 0.65rem;
    padding: 0.9rem 1rem;
    background: var(--gray-50); border: 1.5px dashed var(--gray-200);
    border-radius: 9px; color: var(--gray-400);
    font-size: 0.8rem; font-style: italic;
}
.empty-msg-icon { font-size: 1rem; opacity: 0.5; }

/* ── Bouton supprimer ── */
.btn-del {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px; border-radius: 7px; flex-shrink: 0;
    background: transparent; border: 1.5px solid var(--gray-200);
    color: #c0c9d4; cursor: pointer; font-size: 0.75rem;
    transition: all 0.15s;
}
.btn-del:hover { background: #fee2e2; color: #b91c1c; border-color: #fca5a5; }

/* ══ CONFIRM ══ */
.confirm-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(15,23,42,0.5); backdrop-filter: blur(5px);
    z-index: 2000; align-items: center; justify-content: center;
}
.confirm-overlay.show { display: flex; }
.confirm-box {
    background: #fff; border-radius: 20px; padding: 2.25rem 2rem;
    max-width: 380px; width: 90%; text-align: center;
    box-shadow: 0 30px 80px rgba(0,0,0,0.2);
    animation: fadeDown 0.22s var(--ease);
}
.confirm-icon { font-size: 2.5rem; margin-bottom: 0.85rem; }
.confirm-box h4 { font-size: 1rem; font-weight: 800; color: var(--gray-900); margin: 0 0 0.5rem; }
.confirm-box p  { font-size: 0.84rem; color: var(--gray-400); margin: 0 0 1.75rem; line-height: 1.5; }
.confirm-actions { display: flex; gap: 0.75rem; justify-content: center; }
.btn-cancel {
    background: var(--gray-100); color: var(--gray-600); border: none;
    padding: 0 1.4rem; height: 40px; border-radius: 9px;
    font-size: 0.855rem; font-weight: 600; cursor: pointer;
    font-family: inherit; transition: background 0.15s;
}
.btn-cancel:hover { background: var(--gray-200); }
.btn-del-ok {
    background: #ef4444; color: #fff; border: none;
    padding: 0 1.4rem; height: 40px; border-radius: 9px;
    font-size: 0.855rem; font-weight: 700; cursor: pointer;
    font-family: inherit; box-shadow: 0 2px 8px rgba(239,68,68,0.3);
    transition: background 0.15s;
}
.btn-del-ok:hover { background: #dc2626; }
</style>

<div class="page">

    {{-- ══ HEADER ══ --}}
    <div class="page-header">
        <div class="page-title-wrap">
            <h2 class="page-title">Structure <span>académique</span></h2>
            <p class="page-subtitle">Gérez les départements, filières et matières par semestre</p>
        </div>
        <div class="stats-bar">
            <span class="stat-chip stat-chip-blue">🏛 {{ count($departements) }} département(s)</span>
            <span class="stat-chip stat-chip-violet">🎓 {{ count($filieres) }} filière(s)</span>
            <span class="stat-chip stat-chip-green">📚 {{ count($matieres) }} matière(s)</span>
        </div>
    </div>

    {{-- ══ FORMULAIRES ══ --}}
    <div class="forms-container">

        {{-- Nouveau Département --}}
        <div class="form-card">
            <div class="form-card-topbar" style="background:linear-gradient(90deg,#2563eb,#38bdf8);"></div>
            <div class="form-card-inner">
                <div class="form-card-header">
                    <div class="form-card-icon form-card-icon-blue">🏛️</div>
                    <div>
                        <p class="form-card-title">Nouveau département</p>
                        <p class="form-card-sub">Ajouter une unité d'enseignement</p>
                    </div>
                </div>
                <div class="form-card-divider"></div>
                <form method="POST" action="{{ route('superadmin.departement.store') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Intitulé du département</label>
                            <input name="nom_departement" placeholder="ex : Informatique" required>
                        </div>
                    </div>
                    <div class="form-row" style="justify-content:flex-end;">
                        <button type="submit" class="btn btn-blue">＋ Ajouter le département</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Nouvelle Filière --}}
        <div class="form-card">
            <div class="form-card-topbar" style="background:linear-gradient(90deg,#7c3aed,#a78bfa);"></div>
            <div class="form-card-inner">
                <div class="form-card-header">
                    <div class="form-card-icon form-card-icon-violet">🎓</div>
                    <div>
                        <p class="form-card-title">Nouvelle filière</p>
                        <p class="form-card-sub">Créer un parcours académique</p>
                    </div>
                </div>
                <div class="form-card-divider"></div>
                <form method="POST" action="{{ route('superadmin.filiere.store') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nom de la filière</label>
                            <input name="nom_filiere" placeholder="ex : Génie Logiciel" required>
                        </div>
                        <div class="form-group" style="max-width:100px;">
                            <label>Sigle</label>
                            <input name="code_filiere" placeholder="GL" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Description</label>
                            <input name="description" placeholder="Description courte…">
                        </div>
                        <div class="form-group">
                            <label>Département</label>
                            <select name="id_departement" required>
                                <option value="">— Choisir —</option>
                                @foreach($departements as $d)
                                    <option value="{{ $d->id_departement }}">{{ $d->nom_departement }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row" style="justify-content:flex-end;">
                        <button type="submit" class="btn btn-violet">＋ Ajouter la filière</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- ══ EXPLORATEUR ══ --}}
    <div class="section-header">
        <div class="section-title">
            <div class="section-title-icon">🗂️</div>
            Explorateur académique
        </div>
    </div>

    <div class="tree" id="academic-tree"></div>

</div>

{{-- ══ CONFIRM DIALOG ══ --}}
<div class="confirm-overlay" id="confirm-overlay">
    <div class="confirm-box">
        <div class="confirm-icon">🗑️</div>
        <h4 id="confirm-title">Confirmer la suppression</h4>
        <p id="confirm-msg">Cette action est irréversible.</p>
        <div class="confirm-actions">
            <button class="btn-cancel" onclick="closeConfirm()">Annuler</button>
            <form id="confirm-form" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-del-ok">Supprimer</button>
            </form>
        </div>
    </div>
</div>

<script>
/* ══ DONNÉES LARAVEL ══ */
const dataDepartements = @json($departements);
const dataFilieres     = @json($filieres);
const dataMatieres     = @json($matieres);
const dataPivot        = @json($pivot);
const dataSemestres    = @json($semestres);

const routeDelDept    = "{{ route('superadmin.departement.delete', ':id') }}";
const routeDelFiliere = "{{ route('superadmin.filiere.delete', ':id') }}";

/* ══ HELPERS ══ */
function esc(str) {
    return str ? String(str).replace(/&/g,'&amp;').replace(/'/g,"\\'").replace(/"/g,'&quot;') : '';
}
function escHtml(str) {
    return str ? String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;') : '';
}

// Couleurs des semestres (cycle)
const SEM_COLORS = [
    { bg:'#eff6ff', fg:'#1d4ed8', border:'#bfdbfe' }, // S1 bleu
    { bg:'#fdf4ff', fg:'#7e22ce', border:'#e9d5ff' }, // S2 violet
    { bg:'#f0fdf4', fg:'#166534', border:'#bbf7d0' }, // S3 vert
    { bg:'#fff7ed', fg:'#c2410c', border:'#fed7aa' }, // S4 orange
    { bg:'#fef2f2', fg:'#b91c1c', border:'#fecaca' }, // S5 rouge
    { bg:'#f0f9ff', fg:'#0369a1', border:'#bae6fd' }, // S6 cyan
];

function getSemColor(idSemestre) {
    const idx = (Number(idSemestre) - 1 + SEM_COLORS.length) % SEM_COLORS.length;
    return SEM_COLORS[idx] || { bg:'#f1f5f9', fg:'#475569', border:'#cbd5e1' };
}

function getSemLabel(idSemestre) {
    if (!idSemestre) return 'Sans semestre';
    const s = dataSemestres.find(s => s.id_semestre == idSemestre);
    return s ? (s.code_semestre || ('Semestre ' + idSemestre)) : ('Semestre ' + idSemestre);
}

/* ══ BUILD MATIÈRES TABLE ══ */
function buildMatieresTable(matieres) {
    if (!matieres.length) {
        return `<div class="empty-msg">
            <span class="empty-msg-icon">📚</span>
            Aucune matière enregistrée pour cette filière.
        </div>`;
    }

    // Grouper par id_semestre
    const bySem = {};
    matieres.forEach(m => {
        const key = m.id_semestre || '__none__';
        if (!bySem[key]) bySem[key] = [];
        bySem[key].push(m);
    });

    const keys = Object.keys(bySem).sort((a, b) => {
        if (a === '__none__') return 1;
        if (b === '__none__') return -1;
        return Number(a) - Number(b);
    });

    let html = '<div class="semestres-wrap">';

    keys.forEach(key => {
        const mats  = bySem[key];
        const label = key === '__none__' ? 'Sans semestre' : getSemLabel(key);
        const color = key === '__none__'
            ? { bg:'#f8fafc', fg:'#64748b', border:'#e2e8f0' }
            : getSemColor(key);

        // Header semestre
        html += `
        <div class="sem-block">
            <div class="sem-header">
                <span class="sem-tag" style="background:${color.bg};color:${color.fg};border-color:${color.border};">
                    ${escHtml(label)}
                </span>
                <span class="sem-count">${mats.length} matière${mats.length > 1 ? 's' : ''}</span>
            </div>
            <div class="mat-table-wrap">
                <table class="mat-table">
                    <thead>
                        <tr>
                            <th style="width:36px; text-align:center;">#</th>
                            <th style="width:120px;">Code</th>
                            <th>Nom de la matière</th>
                            <th class="th-center" style="width:80px;">Crédits</th>
                        </tr>
                    </thead>
                    <tbody>`;

        mats.forEach((m, ri) => {
            const credit = m.credit_matiere;
            html += `
                <tr>
                    <td class="mat-num">${ri + 1}</td>
                    <td>
                        <span class="mat-code-cell">${escHtml(m.code_matiere) || '—'}</span>
                    </td>
                    <td>
                        <div class="mat-name-cell">
                            <div class="mat-name-dot"></div>
                            <span class="mat-name-text">${escHtml(m.nom_matiere)}</span>
                        </div>
                    </td>
                    <td class="mat-credit-cell">
                        ${credit !== null && credit !== undefined
                            ? `<span class="mat-credit-badge">${credit}</span>`
                            : `<span class="mat-credit-none">—</span>`}
                    </td>
                </tr>`;
        });

        html += `</tbody></table></div></div>`;
    });

    html += '</div>';
    return html;
}

/* ══ RENDER TREE ══ */
function renderAcademicTree() {
    const container = document.getElementById('academic-tree');
    container.innerHTML = '';

    if (!dataDepartements.length) {
        container.innerHTML = `
            <div class="empty-msg" style="padding:2.5rem;justify-content:center;border-radius:14px;">
                <span class="empty-msg-icon">🏛️</span>
                Aucun département configuré pour le moment.
            </div>`;
        return;
    }

    dataDepartements.forEach((dept, di) => {
        const filieresDuDept = dataFilieres.filter(f => f.id_departement == dept.id_departement);
        const urlDept        = routeDelDept.replace(':id', dept.id_departement);

        /* ── Filières HTML ── */
        let htmlFils = '';

        if (!filieresDuDept.length) {
            htmlFils = `<div class="empty-msg">
                <span class="empty-msg-icon">🎓</span>
                Aucune filière rattachée à ce département.
            </div>`;
        } else {
            filieresDuDept.forEach(fil => {
                const urlFil       = routeDelFiliere.replace(':id', fil.id_filiere);
                // Filtrage double : pivot OU id_filiere direct sur la matiere
                const _pivotIds = dataPivot
                    .filter(p => String(p.id_filiere) === String(fil.id_filiere))
                    .map(p => String(p.id_matiere));

                const _seen = new Set();
                const matsFilUniq = dataMatieres.filter(m => {
                    const ok = _pivotIds.includes(String(m.id_matiere)) ||
                               String(m.id_filiere) === String(fil.id_filiere);
                    if (!ok || _seen.has(m.id_matiere)) return false;
                    _seen.add(m.id_matiere);
                    return true;
                });
                const totalMat = matsFilUniq.length;

                htmlFils += `
                <div class="fil-node" id="fil-${fil.id_filiere}">
                    <div class="fil-row" onclick="toggle('fil-${fil.id_filiere}')">
                        <div class="fil-row-left">
                            <div class="fil-avatar">🎓</div>
                            <div class="fil-info">
                                <div class="fil-name">${escHtml(fil.nom_filiere)}</div>
                                <div class="fil-sub">
                                    ${fil.code_filiere ? `<span class="code-badge">${escHtml(fil.code_filiere)}</span>` : ''}
                                    ${totalMat} matière${totalMat > 1 ? 's' : ''}
                                </div>
                            </div>
                        </div>
                        <div class="fil-row-right">
                            <button class="btn-del" title="Supprimer la filière"
                                onclick="event.stopPropagation();confirmDel('${urlFil}',
                                    'Supprimer cette filière ?',
                                    '« ${esc(fil.nom_filiere)} » sera supprimée définitivement.')">✕</button>
                            <div class="fil-caret caret">▶</div>
                        </div>
                    </div>
                    <div class="fil-body">
                        ${buildMatieresTable(matsFilUniq)}
                    </div>
                </div>`;
            });
        }

        /* ── Département node ── */
        const totalFil = filieresDuDept.length;
        const node = document.createElement('div');
        node.className = 'dept-node';
        node.id = 'dept-' + dept.id_departement;
        node.innerHTML = `
            <div class="dept-row" onclick="toggle('dept-${dept.id_departement}')">
                <div class="dept-row-left">
                    <div class="dept-avatar">🏛️</div>
                    <div class="dept-info">
                        <div class="dept-name">${escHtml(dept.nom_departement)}</div>
                        <div class="dept-sub">${totalFil} filière${totalFil > 1 ? 's' : ''}</div>
                    </div>
                </div>
                <div class="dept-row-right">
                    <button class="btn-del" title="Supprimer le département"
                        onclick="event.stopPropagation();confirmDel('${routeDelDept.replace(':id', dept.id_departement)}',
                            'Supprimer ce département ?',
                            '« ${esc(dept.nom_departement)} » et ses filières seront supprimés définitivement.')">✕</button>
                    <div class="caret">▶</div>
                </div>
            </div>
            <div class="dept-body">
                <div class="fil-list">${htmlFils}</div>
            </div>`;

        container.appendChild(node);

        // Premier département ouvert par défaut
        if (di === 0) toggle('dept-' + dept.id_departement);
    });
}

/* ══ TOGGLE ══ */
function toggle(id) {
    const el = document.getElementById(id);
    if (el) el.classList.toggle('open');
}

/* ══ CONFIRM DELETE ══ */
function confirmDel(action, title, msg) {
    document.getElementById('confirm-title').textContent = title;
    document.getElementById('confirm-msg').textContent   = msg;
    document.getElementById('confirm-form').action       = action;
    document.getElementById('confirm-overlay').classList.add('show');
}
function closeConfirm() {
    document.getElementById('confirm-overlay').classList.remove('show');
}
document.getElementById('confirm-overlay').addEventListener('click', e => {
    if (e.target === document.getElementById('confirm-overlay')) closeConfirm();
});

document.addEventListener('DOMContentLoaded', renderAcademicTree);
</script>

@endsection
