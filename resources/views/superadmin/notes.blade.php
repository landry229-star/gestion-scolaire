@extends('layouts.superadmin')

@section('title', 'Gestion des notes')

@section('content')

<style>
/* ══ RESET & BASE ══ */
*, *::before, *::after { box-sizing: border-box; }

.modal-overlay {
    display: none;
    position: fixed; top:0; left:0; width:100%; height:100%;
    background: rgba(15,23,42,0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
    padding: 1rem;
    backdrop-filter: blur(4px);
}
.modal-overlay.open { display: flex; animation: fadeIn 0.18s ease; }
@keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

.modal-box {
    background: #fff;
    border-radius: 20px;
    padding: 2rem;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 25px 80px rgba(0,0,0,0.22);
    animation: slideUp 0.22s cubic-bezier(.22,1,.36,1);
}
@keyframes slideUp { from { transform:translateY(24px); opacity:0; } to { transform:none; opacity:1; } }

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f1f5f9;
}
.modal-header h3 { margin:0; font-size:1.05rem; font-weight:700; color:#0f172a; }
.modal-close {
    width:32px; height:32px; border-radius:8px; border:none;
    background:#f1f5f9; color:#64748b; cursor:pointer; font-size:1.1rem;
    display:flex; align-items:center; justify-content:center; transition:all 0.15s;
}
.modal-close:hover { background:#e2e8f0; color:#1e293b; transform:scale(1.05); }

.modal-info-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1.25rem;
}
.modal-info-item { display:flex; flex-direction:column; gap:0.25rem; }
.modal-info-label {
    font-size:0.65rem; font-weight:700; text-transform:uppercase;
    color:#94a3b8; letter-spacing:0.08em;
}
.modal-info-value { font-size:0.875rem; font-weight:600; color:#1e293b; }

.modal-grid { display:flex; flex-direction:column; gap:1rem; }

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
}

/* ══ WRAP ══ */
.notes-wrap { max-width: 1400px; margin: 0 auto; }

/* ══ ALERTS ══ */
.alert {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.9rem 1.1rem; border-radius: 12px;
    margin-bottom: 1.25rem; font-size: 0.875rem; font-weight: 500;
}
.alert-success { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
.alert-error   { background:#fef2f2; color:#991b1b; border:1px solid #fca5a5; }
.alert-icon { font-size: 1rem; flex-shrink:0; }

/* ══ HEADER ══ */
.notes-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 2rem; padding-bottom: 1.25rem;
    border-bottom: 2px solid #f1f5f9;
    flex-wrap: wrap; gap: 1rem;
}
.notes-title {
    font-size: 1.65rem; font-weight: 800; letter-spacing: -0.04em;
    color: #0f172a; margin: 0; line-height: 1.2;
}
.notes-title span { color: #3b82f6; }
.notes-subtitle { font-size:0.8rem; color:#94a3b8; margin:0.25rem 0 0; font-weight:400; }
.header-actions { display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; }

/* ══ TABS ══ */
.tabs {
    display: flex; margin-bottom: 1.75rem;
    border-bottom: 2px solid #e2e8f0; gap: 0.25rem;
}
.tab-btn {
    display: inline-flex; align-items: center; gap: 0.45rem;
    padding: 0.7rem 1.35rem; font-size: 0.875rem; font-weight: 600;
    font-family: 'DM Sans', sans-serif; color: #64748b;
    background: none; border: none; border-bottom: 2.5px solid transparent;
    cursor: pointer; transition: color 0.15s, border-color 0.15s;
    margin-bottom: -2px; border-radius: 8px 8px 0 0;
}
.tab-btn:hover:not(.active) { color: #334155; background: #f8fafc; }
.tab-btn.active { color: #3b82f6; border-bottom-color: #3b82f6; background: #eff6ff; }
.tab-content { display: none; }
.tab-content.active { display: block; animation: fadeSlide 0.2s ease; }
@keyframes fadeSlide { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:none; } }

/* ══ CARD ══ */
.n-card {
    background:#fff; border:1px solid #e8ecf2;
    border-radius:16px; padding:1.5rem;
    margin-bottom:1.25rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.n-card-title {
    font-size:0.7rem; font-weight:700; letter-spacing:0.1em;
    text-transform:uppercase; color:#94a3b8; margin:0 0 1.25rem;
    display: flex; align-items: center; gap: 0.5rem;
}
.n-card-title::before {
    content:''; display:inline-block; width:3px; height:14px;
    background: linear-gradient(180deg, #3b82f6, #6366f1);
    border-radius:2px;
}

/* ══ FORM GRIDS ══ */
.form-row-4 { display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:0.75rem; }
.form-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; }
.n-form-group { display:flex; flex-direction:column; gap:0.4rem; }
.n-form-group label {
    font-size:0.72rem; font-weight:700; color:#475569;
    text-transform:uppercase; letter-spacing:0.05em;
}

.n-form-group select,
.n-form-group input[type="number"],
.n-form-group input[type="text"] {
    height:42px; padding:0 0.85rem;
    border:1.5px solid #e2e8f0; border-radius:10px;
    font-size:0.875rem; font-family:'DM Sans',sans-serif; color:#1a1a1a;
    background:#fafbfc; outline:none; width:100%;
    transition:border-color 0.15s, box-shadow 0.15s, background 0.15s;
    appearance:none; -webkit-appearance:none;
}
.n-form-group select {
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'%3E%3Cpath fill='%2394a3b8' d='M5 7L0.67 2h8.66z'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 0.85rem center;
    padding-right:2.2rem; cursor:pointer;
}
.n-form-group select:focus,
.n-form-group input:focus {
    border-color:#3b82f6;
    box-shadow:0 0 0 4px rgba(59,130,246,0.1);
    background:#fff;
}

/* ══ BUTTONS ══ */
.btn {
    display:inline-flex; align-items:center; justify-content:center; gap:0.4rem;
    padding:0 1rem; height:42px; border-radius:10px;
    font-size:0.85rem; font-weight:600;
    font-family:'DM Sans',sans-serif; border:none; cursor:pointer;
    text-decoration:none; transition:all 0.15s; white-space:nowrap;
    letter-spacing:-0.01em;
}
.btn:active { transform:scale(0.97); }
.btn-primary  { background:#3b82f6; color:#fff; box-shadow:0 2px 8px rgba(59,130,246,0.3); }
.btn-primary:hover  { background:#2563eb; box-shadow:0 4px 12px rgba(59,130,246,0.4); }
.btn-success  { background:#10b981; color:#fff; box-shadow:0 2px 8px rgba(16,185,129,0.3); }
.btn-success:hover  { background:#059669; }
.btn-outline  { background:#fff; color:#374151; border:1.5px solid #e2e8f0; }
.btn-outline:hover  { background:#f8fafc; border-color:#94a3b8; }
.btn-danger   { background:#fee2e2; color:#b91c1c; border:1px solid #fca5a5; }
.btn-danger:hover   { background:#fecaca; }
.btn-lock     { background:#fef3c7; color:#92400e; border:1px solid #fcd34d; }
.btn-lock:hover     { background:#fde68a; }
.btn-unlock   { background:#e0f2fe; color:#0369a1; border:1px solid #7dd3fc; }
.btn-unlock:hover   { background:#bae6fd; }
.btn-pdf      { background:#f3e8ff; color:#7c3aed; border:1px solid #c4b5fd; }
.btn-pdf:hover      { background:#ede9fe; }
.btn-sm { height:30px; padding:0 0.65rem; font-size:0.75rem; border-radius:7px; }

/* ══ BULK SECTION ══ */
#bulk-section { display:none; }
#bulk-section.visible { display:block; animation:fadeSlide 0.2s ease; }

.bulk-header {
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:1rem; flex-wrap:wrap; gap:0.75rem;
    background: linear-gradient(135deg,#eff6ff,#f0f9ff);
    border: 1px solid #bfdbfe; border-radius:12px; padding:0.9rem 1.1rem;
}
.matiere-badge {
    display:inline-flex; align-items:center; gap:0.5rem;
    color:#1d4ed8; font-size:0.875rem; font-weight:700;
}
.fill-bar { display:flex; align-items:center; gap:0.5rem; }
.fill-bar input {
    width:90px; height:34px; padding:0 0.5rem;
    border:1.5px solid #e2e8f0; border-radius:8px;
    font-size:0.82rem; font-family:'DM Sans',sans-serif;
    outline:none; text-align:center; background:#fff;
}
.fill-bar input:focus { border-color:#3b82f6; }
.fill-bar span { font-size:0.82rem; color:#475569; font-weight:500; }

/* ══ TABLES — Style unifié propre ══ */
.table-wrap {
    background:#fff;
    border:1px solid #e8ecf2;
    border-radius:16px;
    overflow:hidden;
    box-shadow: 0 1px 6px rgba(0,0,0,0.05);
}
.table-scroll { overflow-x: auto; }

table { width:100%; border-collapse:collapse; font-size:0.855rem; }

thead { background: linear-gradient(135deg, #f8fafc, #f1f5f9); }
thead th {
    padding:0.85rem 1rem;
    text-align:left;
    font-size:0.68rem; font-weight:700;
    letter-spacing:0.08em; text-transform:uppercase;
    color:#64748b; white-space:nowrap;
    border-bottom: 2px solid #e2e8f0;
}
thead th:first-child { border-radius:0; }

/* Colonne numéro */
thead th.col-num,
tbody td.col-num {
    text-align:center;
    width:52px;
    padding-left:0.75rem;
    padding-right:0.75rem;
}
tbody td.col-num {
    font-size:0.72rem; color:#cbd5e1; font-weight:700;
    font-variant-numeric: tabular-nums;
}

tbody tr {
    border-bottom:1px solid #f1f5f9;
    transition:background 0.12s;
}
tbody tr:last-child { border-bottom:none; }
tbody tr:hover { background:#f8fafc; }
tbody td { padding:0.7rem 1rem; color:#1e293b; vertical-align:middle; white-space:nowrap; }

/* ══ NOTE INPUT ══ */
.note-input {
    width:85px; height:36px; padding:0 0.5rem;
    border:1.5px solid #e2e8f0; border-radius:9px;
    font-size:0.875rem; font-family:'DM Sans',sans-serif;
    color:#1a1a1a; background:#fafbfc; outline:none;
    text-align:center; transition:all 0.15s;
}
.note-input:focus   { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); background:#fff; }
.note-input.valid   { border-color:#10b981; background:#f0fdf4; color:#065f46; }
.note-input.invalid { border-color:#ef4444; background:#fef2f2; color:#b91c1c; }
.note-input:disabled { background:#fef2f2; color:#ef4444; border-color:#fca5a5; cursor:not-allowed; opacity:0.7; }

/* ══ STUDENT CELL ══ */
.student-cell { display:flex; align-items:center; gap:0.6rem; }
.avatar {
    width:32px; height:32px; border-radius:50%; flex-shrink:0;
    background:linear-gradient(135deg,#dbeafe,#bfdbfe); color:#1d4ed8;
    font-size:0.62rem; font-weight:800;
    display:flex; align-items:center; justify-content:center;
    letter-spacing:0;
}
.avatar.av-green  { background:linear-gradient(135deg,#dcfce7,#bbf7d0); color:#166534; }
.avatar.av-purple { background:linear-gradient(135deg,#f3e8ff,#e9d5ff); color:#7c3aed; }
.avatar.av-orange { background:linear-gradient(135deg,#ffedd5,#fed7aa); color:#c2410c; }
.student-name { font-weight:600; color:#0f172a; }

/* ══ BADGES ══ */
.badge {
    display:inline-flex; align-items:center; gap:0.3rem;
    padding:0.22rem 0.65rem; border-radius:999px;
    font-size:0.7rem; font-weight:700; letter-spacing:0.02em;
}
.badge-dot {
    width:5px; height:5px; border-radius:50%;
    background:currentColor; flex-shrink:0;
}
.badge-published   { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
.badge-validated   { background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd; }
.badge-unpublished { background:#fff7ed; color:#9a3412; border:1px solid #ffedd5; }

.session-tag {
    display:inline-block; padding:0.2rem 0.6rem;
    border-radius:6px; font-size:0.7rem; font-weight:700;
    letter-spacing:0.03em;
}
.session-normale    { background:#eff6ff; color:#1d4ed8; }
.session-rattrapage { background:#fff7ed; color:#c2410c; }

/* Note value display */
.note-val { font-weight:700; font-size:0.9rem; }
.note-val .denom { font-weight:400; color:#94a3b8; font-size:0.75rem; }
.note-val.high { color:#10b981; }
.note-val.mid  { color:#f59e0b; }
.note-val.low  { color:#ef4444; }

/* Note bubble in list */
.note-bubble {
    display: inline-flex; align-items: baseline; gap: 1px;
    font-weight: 700; font-size: 0.9rem;
    padding: 0.2rem 0.6rem; border-radius: 8px;
}
.note-bubble.high { background:#f0fdf4; color:#10b981; }
.note-bubble.mid  { background:#fffbeb; color:#d97706; }
.note-bubble.low  { background:#fef2f2; color:#ef4444; }
.note-bubble .denom { font-weight:400; font-size:0.72rem; opacity:0.7; }

/* ══ CHIPS ══ */
.mat-chip {
    background:#f1f5f9; color:#334155;
    border:1px solid #e2e8f0; border-radius:7px;
    padding:0.15rem 0.55rem; font-size:0.72rem;
    font-family:monospace; font-weight:700;
    letter-spacing:0.02em;
}
.fil-chip {
    background:#f0fdf4; color:#166534;
    border:1px solid #bbf7d0; border-radius:7px;
    padding:0.15rem 0.55rem; font-size:0.72rem; font-weight:600;
}
.dep-chip {
    background:#eef2ff; color:#3730a3;
    border:1px solid #c7d2fe; border-radius:7px;
    padding:0.15rem 0.55rem; font-size:0.72rem; font-weight:600;
}

/* ══ ACTIONS CELL ══ */
.actions-cell {
    display: flex; flex-direction: row;
    align-items: center; gap: 0.3rem; flex-wrap: nowrap;
}
.actions-cell form { margin:0; padding:0; display:inline-flex; }

/* ══ FILTER BAR ══ */
.filter-bar {
    display:flex; gap:0.75rem; margin-bottom:1.25rem;
    background:#fff; border:1px solid #e8ecf2; border-radius:14px;
    padding:1.1rem; flex-wrap:wrap; align-items:flex-end;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.filter-bar .n-form-group { flex:1; min-width:130px; }

/* ══ PROGRESS ══ */
.progress-wrap { padding:0.85rem 0 0.35rem; }
.progress-bar-bg {
    background:#f1f5f9; border-radius:99px; height:7px; overflow:hidden;
}
.progress-bar-fill {
    height:100%;
    background:linear-gradient(90deg,#3b82f6,#6366f1,#8b5cf6);
    background-size: 200% 100%;
    border-radius:99px;
    transition:width 0.3s cubic-bezier(.22,1,.36,1);
    animation: shimmer 2s infinite linear;
}
@keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
.progress-label { font-size:0.75rem; color:#64748b; margin-top:0.5rem; font-weight:500; }

.bulk-footer { display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1.25rem; }

/* ══ LOADING SPINNER ══ */
.loading-row td { text-align:center; padding:2.5rem; color:#94a3b8; }
.spinner {
    display:inline-block; width:18px; height:18px;
    border:2px solid #e2e8f0; border-top-color:#3b82f6;
    border-radius:50%; animation:spin 0.6s linear infinite;
    margin-right:0.5rem; vertical-align:middle;
}
@keyframes spin { to { transform:rotate(360deg); } }

/* ══ EMPTY STATE ══ */
.empty-state {
    text-align:center; padding:3rem 2rem;
    color:#94a3b8;
}
.empty-state-icon { font-size:2.5rem; margin-bottom:0.75rem; opacity:0.4; }
.empty-state-text { font-size:0.875rem; font-weight:500; }

/* ══ TABLE STATS BAR ══ */
.table-stats-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.6rem 1rem 0.85rem;
    font-size: 0.75rem; color: #94a3b8; flex-wrap: wrap; gap: 0.5rem;
}

/* ══ RESPONSIVE ══ */
@media (max-width:1024px) { .form-row-4 { grid-template-columns:1fr 1fr; } }
@media (max-width:900px)  { .form-row-2 { grid-template-columns:1fr; } }
@media (max-width:600px)  {
    .notes-header { flex-direction:column; align-items:flex-start; gap:1rem; }
    .form-row-4   { grid-template-columns:1fr; }
    .filter-bar   { flex-direction:column; }
}
</style>

<div class="notes-wrap">

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success">
            <span class="alert-icon">✓</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">
            <span class="alert-icon">✗</span>
            {{ session('error') }}
        </div>
    @endif

    {{-- HEADER --}}
    <div class="notes-header">
        <div>
            <h2 class="notes-title">Gestion des <span>notes</span></h2>
            <p class="notes-subtitle">Saisie, validation et publication des résultats académiques</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('superadmin.notes.export.excel') }}" class="btn btn-outline">
                ↓ Export Excel
            </a>
            <form action="{{ route('superadmin.deliberations.generate') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-success">▶ Générer délibérations</button>
            </form>
        </div>
    </div>

    {{-- TABS --}}
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('bulk',this)">📋 Saisie par matière</button>
        <button class="tab-btn"        onclick="switchTab('single',this)">＋ Note individuelle</button>
        <button class="tab-btn"        onclick="switchTab('list',this)">☰ Toutes les notes</button>
    </div>

    {{-- ══ TAB 1 — SAISIE EN MASSE ══ --}}
    <div id="tab-bulk" class="tab-content active">

        <div class="n-card">
            <p class="n-card-title">Étape 1 — Filtrer et choisir la matière</p>
            <div class="form-row-4">

                {{-- Département --}}
                <div class="n-form-group">
                    <label>Département</label>
                    <select id="bulk_dept" onchange="filterBulkFilieres()">
                        <option value="">— Tous —</option>
                        @foreach($departements as $d)
                            <option value="{{ $d->id_departement }}">{{ $d->nom_departement }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filière --}}
                <div class="n-form-group">
                    <label>Filière</label>
                    <select id="bulk_filiere" onchange="filterBulkMatieres()">
                        <option value="">— Toutes —</option>
                        @foreach($filieres as $f)
                            <option value="{{ $f->id_filiere }}"
                                    data-dept="{{ $f->id_departement }}">
                                {{ $f->nom_filiere }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Matière --}}
                <div class="n-form-group">
                    <label>Matière</label>
                    <select id="bulk_matiere" onchange="loadBulkStudents()">
                        <option value="">— Sélectionner —</option>
                        @foreach($matieres as $m)
                            <option value="{{ $m->id_matiere }}"
                                    data-nom="{{ $m->nom_matiere }}"
                                    data-filiere="{{ $m->id_filiere ?? '' }}">
                                {{ $m->nom_matiere }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Session --}}
                <div class="n-form-group">
                    <label>Session</label>
                    <select id="bulk_session" onchange="loadBulkStudents()">
                        <option value="normale">Normale</option>
                        <option value="rattrapage">Rattrapage</option>
                    </select>
                </div>

            </div>
        </div>

        <div id="bulk-section">

            <div class="bulk-header">
                <span id="bulk-badge" class="matiere-badge">📚 —</span>
                <div class="fill-bar">
                    <span>Remplir tout :</span>
                    <input type="number" id="fill-value" min="0" max="20" step="0.01" placeholder="ex : 10">
                    <button type="button" class="btn btn-outline btn-sm" onclick="fillAll()">Appliquer</button>
                </div>
            </div>

            <form id="bulk-form" method="POST" action="{{ route('superadmin.notes') }}">
                @csrf
                <input type="hidden" name="id_matiere" id="hidden_matiere">
                <input type="hidden" name="session"    id="hidden_session">

                <div class="table-wrap">
                    <div class="table-scroll">
                        <table>
                            <thead>
                                <tr>
                                    <th class="col-num">#</th>
                                    <th>Matricule</th>
                                    <th>Étudiant</th>
                                    <th>Filière</th>
                                    <th>Département</th>
                                    <th style="width:120px;text-align:center;">Note /20</th>
                                    <th style="width:80px;text-align:center;">Absent</th>
                                </tr>
                            </thead>
                            <tbody id="bulk-tbody">
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">📋</div>
                                            <div class="empty-state-text">
                                                Sélectionnez une matière pour charger les étudiants.
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="progress-wrap">
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" id="progress-fill" style="width:0%"></div>
                    </div>
                    <p class="progress-label" id="progress-label">0 / 0 notes saisies</p>
                </div>

                <div class="bulk-footer">
                    <button type="button" class="btn btn-outline" onclick="clearAll()">↺ Tout effacer</button>
                    <button type="submit" id="bulk-submit" class="btn btn-primary" disabled>
                        ✓ Enregistrer les notes
                    </button>
                </div>
            </form>
        </div>

    </div>{{-- /tab-bulk --}}

    {{-- ══ TAB 2 — NOTE INDIVIDUELLE ══ --}}
    <div id="tab-single" class="tab-content">
        <div class="n-card">
            <p class="n-card-title">Ajouter une note individuelle</p>
            <form method="POST" action="{{ route('superadmin.notes.store') }}">
                @csrf
                <div class="form-row-2" style="gap:0.75rem;">

                    <div class="n-form-group">
                        <label>Département</label>
                        <select id="single_dept" onchange="filterFilieresSingle()">
                            <option value="">— Tous les départements —</option>
                            @foreach($departements as $d)
                                <option value="{{ $d->id_departement }}">{{ $d->nom_departement }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="n-form-group">
                        <label>Filière</label>
                        <select id="single_filiere" onchange="filterEtudiantsSingle(); filterMatieresSingle()">
                            <option value="">— Toutes les filières —</option>
                            @foreach($filieres as $f)
                                <option value="{{ $f->id_filiere }}"
                                        data-dept="{{ $f->id_departement }}">
                                    {{ $f->nom_filiere }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="n-form-group">
                        <label>Étudiant</label>
                        <select name="id_etudiant" id="single_etudiant" required>
                            <option value="">— Sélectionner —</option>
                            @foreach($etudiants as $e)
                                <option value="{{ $e->id_etudiant }}"
                                        data-filiere="{{ $e->id_filiere ?? '' }}">
                                    {{ $e->matricule ? '['.$e->matricule.'] ' : '' }}{{ $e->nom }} {{ $e->prenom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="n-form-group">
                        <label>Matière</label>
                        <select name="id_matiere" id="single_matiere" required>
                            <option value="">— Sélectionner —</option>
                            @foreach($matieres as $m)
                                <option value="{{ $m->id_matiere }}"
                                        data-filiere="{{ $m->id_filiere ?? '' }}">
                                    {{ $m->nom_matiere }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="n-form-group">
                        <label>Note /20</label>
                        <input type="number" name="valeur_note" step="0.01" min="0" max="20"
                               placeholder="0.00" required>
                    </div>

                    <div class="n-form-group">
                        <label>Session</label>
                        <select name="session">
                            <option value="normale">Normale</option>
                            <option value="rattrapage">Rattrapage</option>
                        </select>
                    </div>

                </div>

                <div style="display:flex; justify-content:flex-end; margin-top:1.25rem;">
                    <button type="submit" class="btn btn-primary">Enregistrer la note</button>
                </div>
            </form>
        </div>
    </div>{{-- /tab-single --}}

    {{-- ══ TAB 3 — TOUTES LES NOTES ══ --}}
    <div id="tab-list" class="tab-content">

        <div class="filter-bar">
            <div class="n-form-group">
                <label>Recherche</label>
                <input type="text" id="f-search" placeholder="Nom, prénom ou matricule…" oninput="filterNotes()">
            </div>
            <div class="n-form-group">
                <label>Filière</label>
                <select id="f-filiere" onchange="filterNotes()">
                    <option value="">Toutes</option>
                    @foreach($filieres as $f)
                        <option value="{{ strtolower($f->nom_filiere) }}">{{ $f->nom_filiere }}</option>
                    @endforeach
                </select>
            </div>
            <div class="n-form-group">
                <label>Département</label>
                <select id="f-dept" onchange="filterNotes()">
                    <option value="">Tous</option>
                    @foreach($departements as $d)
                        <option value="{{ strtolower($d->nom_departement) }}">{{ $d->nom_departement }}</option>
                    @endforeach
                </select>
            </div>
            <div class="n-form-group">
                <label>Session</label>
                <select id="f-session" onchange="filterNotes()">
                    <option value="">Toutes</option>
                    <option value="normale">Normale</option>
                    <option value="rattrapage">Rattrapage</option>
                </select>
            </div>
            <div class="n-form-group">
                <label>Statut</label>
                <select id="f-statut" onchange="filterNotes()">
                    <option value="">Tous</option>
                    <option value="publiée">Publiée</option>
                    <option value="validée">Validée</option>
                    <option value="attente">En attente</option>
                </select>
            </div>
            <button type="button" class="btn btn-outline btn-sm" onclick="resetFilters()" style="flex-shrink:0;align-self:flex-end;">
                ↺ Réinitialiser
            </button>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table id="notesTable">
                    <thead>
                        <tr>
                            <th class="col-num">#</th>
                            <th>Matricule</th>
                            <th>Étudiant</th>
                            <th>Filière</th>
                            <th>Département</th>
                            <th>Matière</th>
                            <th style="text-align:center;">Note</th>
                            <th style="text-align:center;">Session</th>
                            <th style="text-align:center;">Statut</th>
                            <th style="min-width:310px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notes as $n)
                        @php
                            $etudiant    = $n->etudiant;
                            $filiere     = $etudiant?->filiere ?? $n->matiere?->filiere ?? null;
                            $dept        = $filiere?->departement ?? $n->matiere?->departement ?? null;
                            $matricule   = $etudiant?->matricule ?? '—';
                            $v           = $n->valeur_note;
                            $statutLabel = $n->is_published ? 'publiée' : ($n->is_validated ? 'validée' : 'attente');
                            $nomComplet  = strtolower(($etudiant->nom ?? '').' '.($etudiant->prenom ?? '').' '.($etudiant->matricule ?? ''));
                            $noteClass   = $v >= 14 ? 'high' : ($v >= 10 ? 'mid' : 'low');
                            // Avatar color variation
                            $avClass = ['av-blue','av-green','av-purple','av-orange'][crc32($etudiant->nom ?? 'E') % 4];
                        @endphp
                        <tr
                            data-search="{{ $nomComplet }}"
                            data-filiere="{{ strtolower($filiere?->nom_filiere ?? '') }}"
                            data-dept="{{ strtolower($dept?->nom_departement ?? '') }}"
                            data-session="{{ $n->session }}"
                            data-statut="{{ $statutLabel }}"
                        >
                            {{-- # numéro (mis à jour par JS) --}}
                            <td class="col-num">
                                <span class="row-num" style="
                                    display:inline-flex; align-items:center; justify-content:center;
                                    width:24px; height:24px; border-radius:6px;
                                    background:#f1f5f9; color:#94a3b8;
                                    font-size:0.68rem; font-weight:700;
                                ">—</span>
                            </td>

                            <td>
                                <span class="mat-chip">{{ $matricule }}</span>
                            </td>

                            <td>
                                <div class="student-cell">
                                    <div class="avatar {{ $avClass }}">
                                        {{ strtoupper(substr($etudiant->nom ?? 'E',0,1)) }}{{ strtoupper(substr($etudiant->prenom ?? 'N',0,1)) }}
                                    </div>
                                    <span class="student-name">{{ $etudiant->nom ?? '—' }} {{ $etudiant->prenom ?? '' }}</span>
                                </div>
                            </td>

                            <td>
                                @if($filiere)
                                    <span class="fil-chip">{{ $filiere->nom_filiere }}</span>
                                @else
                                    <span style="color:#cbd5e1;">—</span>
                                @endif
                            </td>

                            <td>
                                @if($dept)
                                    <span class="dep-chip">{{ $dept->nom_departement }}</span>
                                @else
                                    <span style="color:#cbd5e1;">—</span>
                                @endif
                            </td>

                            <td style="color:#475569; font-weight:500;">{{ $n->matiere->nom_matiere ?? '—' }}</td>

                            <td style="text-align:center;">
                                <span class="note-bubble {{ $noteClass }}">
                                    {{ number_format($v,2) }}<span class="denom">/20</span>
                                </span>
                            </td>

                            <td style="text-align:center;">
                                <span class="session-tag session-{{ $n->session }}">{{ ucfirst($n->session) }}</span>
                            </td>

                            <td style="text-align:center;">
                                @if($n->is_published)
                                    <span class="badge badge-published"><span class="badge-dot"></span>Publiée</span>
                                @elseif($n->is_validated)
                                    <span class="badge badge-validated"><span class="badge-dot"></span>Validée</span>
                                @else
                                    <span class="badge badge-unpublished"><span class="badge-dot"></span>En attente</span>
                                @endif
                            </td>

                            <td>
                                <div class="actions-cell">

                                    @if(!$n->is_validated)
                                        <form action="{{ route('superadmin.notes.validate', $n->id_matiere) }}" method="POST"
                                              onsubmit="return confirm('Valider les notes pour cette matière ?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm"
                                                    style="color:#ea580c;border:1px solid #ffedd5;background:#fff7ed;font-weight:600;">
                                                ✓ Valider
                                            </button>
                                        </form>
                                    @endif

                                    <button type="button" class="btn btn-outline btn-sm"
                                            style="color:#6366f1;border-color:#e0e7ff;background:#eef2ff;font-weight:600;"
                                            onclick="openEditModal({
                                                id_note:          {{ $n->id_note }},
                                                etudiant_nom:     '{{ addslashes($etudiant->nom ?? '') }}',
                                                etudiant_prenom:  '{{ addslashes($etudiant->prenom ?? '') }}',
                                                matricule:        '{{ addslashes($matricule) }}',
                                                matiere_nom:      '{{ addslashes($n->matiere->nom_matiere ?? '') }}',
                                                valeur_note:      {{ $n->valeur_note }},
                                                session:          '{{ $n->session }}',
                                                statut:           '{{ $n->statut ?? 'brouillon' }}'
                                            })">
                                        ✏️ Modifier
                                    </button>

                                    @if($n->is_validated && !$n->is_published)
                                        <form action="{{ route('superadmin.notes.publish', $n->id_matiere) }}" method="POST"
                                              onsubmit="return confirm('Publier officiellement ces notes aux étudiants ?')">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                ↑ Publier
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('superadmin.notes.reject', $n->id_matiere) }}" method="POST"
                                          onsubmit="return confirm('Rejeter les notes de cette matière ?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">✗ Rejeter</button>
                                    </form>

                                    @if(!($n->matiere->notes_locked ?? false))
                                        <form action="{{ route('superadmin.notes.lock', $n->id_matiere) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-lock btn-sm">🔒</button>
                                        </form>
                                    @else
                                        <form action="{{ route('superadmin.notes.unlock', $n->id_matiere) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-unlock btn-sm">🔓</button>
                                        </form>
                                    @endif

                                    <a href="{{ route('superadmin.pdf.releves', $n->etudiant->id_etudiant ?? 0) }}"
                                       class="btn btn-pdf btn-sm">📄 PDF</a>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="noNotesRow">
                            <td colspan="10">
                                <div class="empty-state">
                                    <div class="empty-state-icon">📭</div>
                                    <div class="empty-state-text">Aucune note enregistrée pour le moment.</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-stats-bar">
                <span id="notes-count" style="font-weight:500; color:#64748b;"></span>
                <span style="color:#cbd5e1;">Mise à jour en temps réel</span>
            </div>
        </div>

        @if(method_exists($notes, 'links'))
            <div style="margin-top:1rem;">{{ $notes->links() }}</div>
        @endif

    </div>{{-- /tab-list --}}

</div>{{-- /notes-wrap --}}

{{-- ══ MODAL MODIFIER UNE NOTE ══ --}}
<div id="editNoteModal" class="modal-overlay" onclick="closeEditModal(event)">
    <div class="modal-box">

        <div class="modal-header">
            <h3>✏️ Modifier la note</h3>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>

        <div class="modal-info-row">
            <div class="modal-info-item">
                <span class="modal-info-label">Étudiant</span>
                <span class="modal-info-value" id="modal-etudiant-nom">—</span>
            </div>
            <div class="modal-info-item">
                <span class="modal-info-label">Matricule</span>
                <span class="modal-info-value" id="modal-matricule">—</span>
            </div>
            <div class="modal-info-item">
                <span class="modal-info-label">Matière</span>
                <span class="modal-info-value" id="modal-matiere-nom">—</span>
            </div>
            <div class="modal-info-item">
                <span class="modal-info-label">Session</span>
                <span class="modal-info-value" id="modal-session-label">—</span>
            </div>
        </div>

        <form id="editNoteForm" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="modal-grid">
                <div class="n-form-group">
                    <label>Note /20</label>
                    <input type="number" id="modal_valeur_note" name="valeur_note"
                           step="0.01" min="0" max="20" placeholder="0.00" required
                           style="height:48px;font-size:1.2rem;font-weight:700;text-align:center;">
                </div>
                <div class="n-form-group">
                    <label>Session</label>
                    <select id="modal_session" name="session">
                        <option value="normale">Normale</option>
                        <option value="rattrapage">Rattrapage</option>
                    </select>
                </div>
                <div class="n-form-group">
                    <label>Statut</label>
                    <select id="modal_statut" name="statut">
                        <option value="brouillon">Brouillon</option>
                        <option value="validé">Validé</option>
                        <option value="publié">Publié</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeEditModal()">Annuler</button>
                <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
/* ══ Exposition globale des fonctions (fix "is not defined") ══ */

window.switchTab = function(name, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
    if (name === 'list') renumberRows();
};

/* ══ TAB 1 — FILTRES EN CASCADE ══ */
window.filterBulkFilieres = function() {
    const deptId = document.getElementById('bulk_dept').value;
    const selFil = document.getElementById('bulk_filiere');
    Array.from(selFil.options).forEach(opt => {
        if (!opt.value) return;
        opt.hidden = !(!deptId || opt.dataset.dept === deptId);
    });
    if (selFil.selectedOptions[0]?.hidden) selFil.value = '';
    filterBulkMatieres();
};

window.filterBulkMatieres = function() {
    const filiereId = document.getElementById('bulk_filiere').value;
    const selMat    = document.getElementById('bulk_matiere');
    Array.from(selMat.options).forEach(opt => {
        if (!opt.value) return;
        opt.hidden = !(!filiereId || opt.dataset.filiere === filiereId);
    });
    if (selMat.selectedOptions[0]?.hidden) {
        selMat.value = '';
        resetBulkSection();
    }
};

function resetBulkSection() {
    document.getElementById('bulk-section').classList.remove('visible');
    document.getElementById('bulk-tbody').innerHTML = `
        <tr><td colspan="7">
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <div class="empty-state-text">Sélectionnez une matière pour charger les étudiants.</div>
            </div>
        </td></tr>`;
    document.getElementById('bulk-submit').disabled = true;
    document.getElementById('progress-fill').style.width = '0%';
    document.getElementById('progress-label').textContent = '0 / 0 notes saisies';
}

let currentStudentCount = 0;

window.loadBulkStudents = function() {
    const sel       = document.getElementById('bulk_matiere');
    const matiereId = sel.value;
    const session   = document.getElementById('bulk_session').value;
    const section   = document.getElementById('bulk-section');
    const tbody     = document.getElementById('bulk-tbody');
    const submitBtn = document.getElementById('bulk-submit');

    if (!matiereId) { section.classList.remove('visible'); return; }

    const nom = sel.options[sel.selectedIndex].dataset.nom || sel.options[sel.selectedIndex].text;
    document.getElementById('hidden_matiere').value = matiereId;
    document.getElementById('hidden_session').value  = session;
    document.getElementById('bulk-badge').textContent =
        '📚 ' + nom + '  •  Session ' + session.charAt(0).toUpperCase() + session.slice(1);

    section.classList.add('visible');
    submitBtn.disabled = true;
    tbody.innerHTML = `<tr class="loading-row"><td colspan="7"><span class="spinner"></span> Chargement…</td></tr>`;

    const csrfToken = document.querySelector('meta[name="csrf-token"]');

    fetch(`/super-admin/notes/etudiants?id_matiere=${matiereId}&session=${encodeURIComponent(session)}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept':           'application/json',
            'X-CSRF-TOKEN':     csrfToken ? csrfToken.content : ''
        }
    })
    .then(r => r.json().then(data => ({ ok: r.ok, status: r.status, data })))
    .then(({ ok, status, data }) => {
        if (!ok) {
            const msg = data.message || 'Erreur inconnue';
            const line = data.line ? ' (ligne ' + data.line + ')' : '';
            tbody.innerHTML = `
                <tr><td colspan="7">
                    <div class="empty-state">
                        <div class="empty-state-icon">⚠️</div>
                        <div class="empty-state-text" style="color:#ef4444;white-space:normal;max-width:600px;">
                            <strong>Erreur ${status} :</strong> ${msg}${line}
                        </div>
                    </div>
                </td></tr>`;
            return;
        }
        buildBulkTable(data);
    })
    .catch(err => {
        tbody.innerHTML = `
            <tr><td colspan="7">
                <div class="empty-state">
                    <div class="empty-state-icon">⚠️</div>
                    <div class="empty-state-text" style="color:#ef4444;">Erreur réseau : ${err.message}</div>
                </div>
            </td></tr>`;
    });
};

function buildBulkTable(data) {
    const tbody     = document.getElementById('bulk-tbody');
    const submitBtn = document.getElementById('bulk-submit');
    currentStudentCount = Array.isArray(data) ? data.length : 0;
    tbody.innerHTML = '';

    if (!data || data.length === 0) {
        tbody.innerHTML = `
            <tr><td colspan="7">
                <div class="empty-state">
                    <div class="empty-state-icon">👤</div>
                    <div class="empty-state-text">Aucun étudiant trouvé pour cette matière.</div>
                </div>
            </td></tr>`;
        return;
    }

    const colors = ['', 'av-green', 'av-purple', 'av-orange'];
    data.forEach((e, i) => {
        const initiales = ((e.nom||'E').charAt(0) + (e.prenom||'N').charAt(0)).toUpperCase();
        const avClass   = colors[i % colors.length];
        const noteVal   = (e.note_existante !== null && e.note_existante !== undefined) ? e.note_existante : '';
        const isAbsent  = !!e.is_absent;

        const tr = document.createElement('tr');
        tr.dataset.etudiantId = e.id_etudiant;
        tr.innerHTML = `
            <td class="col-num">
                <span style="display:inline-flex;align-items:center;justify-content:center;
                    width:24px;height:24px;border-radius:6px;background:#f1f5f9;
                    color:#94a3b8;font-size:0.68rem;font-weight:700;">${i+1}</span>
            </td>
            <td><span class="mat-chip">${e.matricule || '—'}</span></td>
            <td>
                <div class="student-cell">
                    <div class="avatar ${avClass}">${initiales}</div>
                    <span class="student-name">${e.nom||''} ${e.prenom||''}</span>
                </div>
            </td>
            <td>${e.filiere_nom ? `<span class="fil-chip">${e.filiere_nom}</span>` : '<span style="color:#cbd5e1;">—</span>'}</td>
            <td>${e.departement_nom ? `<span class="dep-chip">${e.departement_nom}</span>` : '<span style="color:#cbd5e1;">—</span>'}</td>
            <td style="text-align:center;" data-col="note">
                <input type="number" class="note-input"
                    name="notes[${e.id_etudiant}]"
                    min="0" max="20" step="0.01" placeholder="—"
                    value="${noteVal}" ${isAbsent ? 'disabled' : ''}>
            </td>
            <td style="text-align:center;" data-col="absent">
                <input type="checkbox"
                    data-etudiant="${e.id_etudiant}"
                    style="width:17px;height:17px;cursor:pointer;accent-color:#ef4444;"
                    ${isAbsent ? 'checked' : ''}>
                <input type="hidden" name="absent[${e.id_etudiant}]"
                    value="${isAbsent ? '1' : '0'}" class="absent-flag">
            </td>`;
        tbody.appendChild(tr);
    });

    // Attacher les événements après insertion dans le DOM
    tbody.querySelectorAll('.note-input').forEach(input => {
        input.addEventListener('input', () => { validateNote(input); updateProgress(); });
    });
    tbody.querySelectorAll('input[type="checkbox"][data-etudiant]').forEach(cb => {
        cb.addEventListener('change', () => toggleAbsent(cb));
    });

    submitBtn.disabled    = false;
    submitBtn.textContent = `✓ Enregistrer les ${data.length} notes`;
    updateProgress();
}

/* ══ TAB 2 ══ */
window.filterFilieresSingle = function() {
    const deptId = document.getElementById('single_dept').value;
    const selFil = document.getElementById('single_filiere');
    Array.from(selFil.options).forEach(opt => {
        if (!opt.value) return;
        opt.hidden = !(!deptId || opt.dataset.dept === deptId);
    });
    if (selFil.selectedOptions[0]?.hidden) selFil.value = '';
    filterEtudiantsSingle(); filterMatieresSingle();
};

window.filterEtudiantsSingle = function() {
    const filiereId = document.getElementById('single_filiere').value;
    const sel = document.getElementById('single_etudiant');
    Array.from(sel.options).forEach(opt => {
        if (!opt.value) return;
        opt.hidden = !(!filiereId || opt.dataset.filiere === filiereId);
    });
    if (sel.selectedOptions[0]?.hidden) sel.value = '';
};

window.filterMatieresSingle = function() {
    const filiereId = document.getElementById('single_filiere').value;
    const sel = document.getElementById('single_matiere');
    Array.from(sel.options).forEach(opt => {
        if (!opt.value) return;
        opt.hidden = !(!filiereId || opt.dataset.filiere === filiereId);
    });
    if (sel.selectedOptions[0]?.hidden) sel.value = '';
};

/* ══ HELPERS ══ */
function validateNote(input) {
    const v = parseFloat(input.value);
    input.classList.remove('valid','invalid');
    if (input.value === '') return;
    input.classList.add((!isNaN(v) && v >= 0 && v <= 20) ? 'valid' : 'invalid');
}

function updateProgress() {
    let filled = 0;
    document.querySelectorAll('#bulk-tbody .note-input').forEach(i => {
        if (i.value !== '' && !i.disabled) filled++;
    });
    document.querySelectorAll('#bulk-tbody .absent-flag').forEach(f => {
        if (f.value === '1') filled++;
    });
    const total = currentStudentCount;
    const pct   = total > 0 ? Math.round((filled / total) * 100) : 0;
    document.getElementById('progress-fill').style.width = pct + '%';
    document.getElementById('progress-label').textContent = filled + ' / ' + total + ' notes saisies (' + pct + '%)';
}

function toggleAbsent(cb) {
    const row   = cb.closest('tr');
    const input = row.querySelector('.note-input');
    const flag  = row.querySelector('.absent-flag');
    if (cb.checked) { input.value = ''; input.disabled = true; flag.value = '1'; }
    else            { input.disabled = false; flag.value = '0'; }
    input.classList.remove('valid','invalid');
    updateProgress();
}

window.fillAll = function() {
    const val = document.getElementById('fill-value').value;
    if (val === '') return;
    document.querySelectorAll('#bulk-tbody .note-input:not(:disabled)').forEach(i => {
        i.value = val; validateNote(i);
    });
    updateProgress();
};

window.clearAll = function() {
    document.querySelectorAll('#bulk-tbody .note-input').forEach(i => {
        i.value = ''; i.classList.remove('valid','invalid'); i.disabled = false;
    });
    document.querySelectorAll('#bulk-tbody input[type="checkbox"][data-etudiant]').forEach(cb => cb.checked = false);
    document.querySelectorAll('#bulk-tbody .absent-flag').forEach(f => f.value = '0');
    updateProgress();
};

/* ══ TAB 3 — FILTRAGE & NUMÉROTATION ══ */
window.filterNotes = function() {
    const search  = document.getElementById('f-search').value.toLowerCase().trim();
    const filiere = document.getElementById('f-filiere').value.toLowerCase();
    const dept    = document.getElementById('f-dept').value.toLowerCase();
    const session = document.getElementById('f-session').value.toLowerCase();
    const statut  = document.getElementById('f-statut').value.toLowerCase();

    const rows = document.querySelectorAll('#notesTable tbody tr:not(#noNotesRow):not(#dynamicNoNotesRow)');
    let visible = 0;

    rows.forEach(row => {
        const ok =
            (!search  || (row.dataset.search  || '').includes(search)) &&
            (!filiere || row.dataset.filiere === filiere)               &&
            (!dept    || row.dataset.dept    === dept)                  &&
            (!session || row.dataset.session === session)               &&
            (!statut  || row.dataset.statut  === statut);
        row.style.display = ok ? '' : 'none';
        if (ok) {
            visible++;
            const numEl = row.querySelector('.row-num');
            if (numEl) numEl.textContent = visible;
        }
    });

    let dynRow = document.getElementById('dynamicNoNotesRow');
    if (visible === 0) {
        if (!dynRow) {
            dynRow = document.createElement('tr');
            dynRow.id = 'dynamicNoNotesRow';
            dynRow.innerHTML = `<td colspan="10"><div class="empty-state">
                <div class="empty-state-icon">🔍</div>
                <div class="empty-state-text">Aucune note ne correspond à ces filtres.</div>
            </div></td>`;
            document.querySelector('#notesTable tbody').appendChild(dynRow);
        }
    } else if (dynRow) { dynRow.remove(); }

    const counter = document.getElementById('notes-count');
    if (counter) counter.textContent = visible + ' résultat' + (visible > 1 ? 's' : '') + ' affiché' + (visible > 1 ? 's' : '');
};

window.resetFilters = function() {
    document.getElementById('f-search').value = '';
    ['f-filiere','f-dept','f-session','f-statut'].forEach(id => {
        document.getElementById(id).selectedIndex = 0;
    });
    filterNotes();
};

function renumberRows() {
    let n = 1;
    document.querySelectorAll('#notesTable tbody tr:not(#noNotesRow):not(#dynamicNoNotesRow)').forEach(row => {
        if (row.style.display !== 'none') {
            const numEl = row.querySelector('.row-num');
            if (numEl) numEl.textContent = n++;
        }
    });
}

/* ══ MODAL ══ */
window.openEditModal = function(note) {
    document.getElementById('modal-etudiant-nom').textContent =
        (note.etudiant_nom || '—') + ' ' + (note.etudiant_prenom || '');
    document.getElementById('modal-matricule').textContent   = note.matricule   || '—';
    document.getElementById('modal-matiere-nom').textContent = note.matiere_nom || '—';
    document.getElementById('modal-session-label').textContent =
        note.session ? note.session.charAt(0).toUpperCase() + note.session.slice(1) : '—';

    document.getElementById('modal_valeur_note').value = note.valeur_note ?? '';
    document.getElementById('modal_session').value     = note.session     ?? 'normale';
    document.getElementById('modal_statut').value      = note.statut      ?? 'brouillon';
    document.getElementById('editNoteForm').action     = `/super-admin/notes/${note.id_note}`;

    document.getElementById('editNoteModal').classList.add('open');
    setTimeout(() => document.getElementById('modal_valeur_note').focus(), 100);
};

window.closeEditModal = function(event) {
    if (!event || event.target === document.getElementById('editNoteModal')) {
        document.getElementById('editNoteModal').classList.remove('open');
    }
};

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeEditModal(); });

document.addEventListener('DOMContentLoaded', () => {
    renumberRows();
    filterNotes();
});
</script>
@endsection
