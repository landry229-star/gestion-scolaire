@extends('layouts.superadmin')

@section('title', 'Liste des Étudiants')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Cabinet+Grotesk:wght@700;800;900&display=swap" rel="stylesheet">

<style>
:root {
    --bg-main:       #080c14;
    --bg-card:       #111827;
    --bg-card-hover: #151e2e;
    --bg-input:      rgba(255,255,255,0.03);
    --border:        rgba(255,255,255,0.06);
    --border-hover:  rgba(255,255,255,0.12);
    --border-focus:  rgba(59,130,246,0.5);

    --text-heading:  #f0f4ff;
    --text-body:     #8b9ab5;
    --text-muted:    #4e5f7a;

    --blue:     #3b82f6;
    --blue-g:   rgba(59,130,246,0.12);
    --emerald:  #34d399;
    --emerald-g:rgba(52,211,153,0.1);
    --red:      #f87171;
    --red-g:    rgba(248,113,113,0.1);
    --purple:   #a78bfa;
    --amber:    #fbbf24;
    --amber-g:  rgba(251,191,36,0.1);

    --radius-xl: 20px;
    --radius-lg: 16px;
    --radius-md: 12px;
    --radius-sm: 8px;

    --font-display: 'Cabinet Grotesk', sans-serif;
    --font-body:    'DM Sans', sans-serif;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body, .container-fluid {
    background: var(--bg-main);
    font-family: var(--font-body);
    color: var(--text-body);
}

/* ─── Header ─── */
.top-header-bar {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
    position: relative;
    overflow: hidden;
}
.top-header-bar::before {
    content: '';
    position: absolute;
    top: -50px; right: -50px;
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(59,130,246,0.06) 0%, transparent 70%);
    pointer-events: none;
}

.page-eyebrow {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--blue);
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 6px;
}
.page-eyebrow::before {
    content: '';
    width: 6px; height: 6px;
    background: var(--blue);
    border-radius: 50%;
    box-shadow: 0 0 8px var(--blue);
}

.page-title {
    font-family: var(--font-display);
    font-size: 1.75rem;
    font-weight: 900;
    color: var(--text-heading);
    letter-spacing: -0.03em;
    line-height: 1;
}
.page-subtitle {
    color: var(--text-muted);
    font-size: 0.82rem;
    margin-top: 5px;
}

.btn-add {
    background: var(--blue);
    color: #fff;
    border: none;
    padding: 0.75rem 1.4rem;
    border-radius: var(--radius-md);
    font-family: var(--font-body);
    font-weight: 700;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
}
.btn-add::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.08), transparent);
}
.btn-add:hover {
    background: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(59,130,246,0.3);
    color: #fff;
}

/* ─── Filter Bar ─── */
.filter-bar {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
}

.filter-form {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.filter-inputs-group {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-grow: 1;
    flex-wrap: wrap;
}

.search-wrapper {
    position: relative;
    flex-grow: 1;
    max-width: 340px;
}
.search-wrapper i {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 0.8rem;
    pointer-events: none;
}

.filter-input, .filter-select {
    background: var(--bg-input);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    font-family: var(--font-body);
    font-size: 0.875rem;
    color: var(--text-heading);
    transition: all 0.2s ease;
    color-scheme: dark;
}
.filter-input::placeholder { color: var(--text-muted); }

.filter-input {
    width: 100%;
    padding: 0.65rem 0.9rem 0.65rem 2.25rem;
}
.filter-select {
    padding: 0.65rem 0.9rem;
    min-width: 160px;
    cursor: pointer;
}
.filter-input:focus, .filter-select:focus {
    outline: none;
    border-color: var(--border-focus);
    background: rgba(59,130,246,0.04);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.07);
}

.btn-reset {
    background: rgba(255,255,255,0.04);
    border: 1px solid var(--border);
    color: var(--text-body);
    padding: 0.65rem 1rem;
    border-radius: var(--radius-sm);
    font-family: var(--font-body);
    font-size: 0.82rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.btn-reset:hover {
    border-color: var(--border-hover);
    color: var(--text-heading);
    background: rgba(255,255,255,0.06);
}
.d-none { display: none !important; }

/* ─── Table Card ─── */
.table-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    overflow: hidden;
}

.table-responsive { overflow-x: auto; }

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table thead tr {
    border-bottom: 1px solid var(--border);
    background: rgba(255,255,255,0.02);
}

.modern-table th {
    padding: 0.85rem 1.1rem;
    text-align: left;
    font-size: 0.67rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
    white-space: nowrap;
}

/* N° column */
.modern-table th:first-child,
.modern-table td:first-child {
    width: 52px;
    text-align: center;
    padding-left: 0.75rem;
    padding-right: 0.75rem;
}

.modern-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background 0.15s ease;
}
.modern-table tbody tr:last-child { border-bottom: none; }
.modern-table tbody tr:hover { background: rgba(255,255,255,0.02); }

.modern-table td {
    padding: 1rem 1.1rem;
    font-size: 0.875rem;
    vertical-align: middle;
}

/* Row number */
.row-num {
    font-family: var(--font-display);
    font-size: 0.78rem;
    font-weight: 800;
    color: var(--text-muted);
    background: rgba(255,255,255,0.04);
    border: 1px solid var(--border);
    width: 30px; height: 30px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: auto;
}

/* Matricule */
.matricule-tag {
    font-family: 'DM Mono', 'Courier New', monospace;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--blue);
    background: var(--blue-g);
    border: 1px solid rgba(59,130,246,0.18);
    padding: 3px 9px;
    border-radius: var(--radius-sm);
    letter-spacing: 0.04em;
    white-space: nowrap;
}

/* Name / email cells */
.cell-name {
    font-weight: 700;
    color: var(--text-heading);
    font-size: 0.88rem;
}
.cell-prenom {
    color: var(--text-body);
    font-size: 0.88rem;
}
.cell-email {
    color: var(--text-muted);
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 5px;
}
.cell-email i { font-size: 0.7rem; }

/* Filière */
.filiere-tag {
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--text-body);
    background: rgba(255,255,255,0.04);
    border: 1px solid var(--border);
    padding: 3px 9px;
    border-radius: var(--radius-sm);
    white-space: nowrap;
    max-width: 140px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
}

/* Niveau */
.level-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    font-size: 0.72rem;
    font-weight: 700;
    border-radius: 100px;
    background: var(--blue-g);
    color: var(--blue);
    border: 1px solid rgba(59,130,246,0.18);
    white-space: nowrap;
}

/* Statut */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    font-size: 0.72rem;
    font-weight: 700;
    border-radius: 100px;
    white-space: nowrap;
}
.status-pill.actif  { background: var(--emerald-g); color: var(--emerald); border: 1px solid rgba(52,211,153,0.2); }
.status-pill.bloque { background: var(--red-g);     color: var(--red);     border: 1px solid rgba(248,113,113,0.2); }
.status-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; flex-shrink: 0; }

/* Actions */
.action-group { display: flex; gap: 0.5rem; justify-content: flex-end; }

.btn-action {
    padding: 7px 10px;
    border-radius: var(--radius-sm);
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    text-decoration: none;
    border: 1px solid var(--border);
    transition: all 0.2s ease;
    cursor: pointer;
    background: rgba(255,255,255,0.03);
    color: var(--text-body);
    white-space: nowrap;
}
.btn-action:hover {
    border-color: var(--border-hover);
    background: rgba(255,255,255,0.06);
    color: var(--text-heading);
}
.btn-action.danger {
    color: var(--red);
    border-color: rgba(248,113,113,0.2);
    background: var(--red-g);
}
.btn-action.danger:hover {
    background: rgba(248,113,113,0.16);
    border-color: rgba(248,113,113,0.35);
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-muted);
}
.empty-state i { font-size: 2.5rem; display: block; margin-bottom: 1rem; opacity: 0.3; }
.empty-state p { font-size: 0.9rem; }

/* ─── Pagination ─── */
#paginationContainer {
    display: flex;
    justify-content: flex-end;
    margin-top: 1.5rem;
}
#paginationContainer .pagination {
    display: flex;
    gap: 4px;
    list-style: none;
    padding: 0;
}
#paginationContainer .page-item .page-link {
    background: var(--bg-card);
    border: 1px solid var(--border);
    color: var(--text-body);
    padding: 7px 13px;
    border-radius: var(--radius-sm);
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}
#paginationContainer .page-item.active .page-link {
    background: var(--blue);
    border-color: var(--blue);
    color: #fff;
}
#paginationContainer .page-item .page-link:hover {
    border-color: var(--border-hover);
    background: var(--bg-card-hover);
    color: var(--text-heading);
}

/* ─── Loading ─── */
.table-loading { opacity: 0.4; pointer-events: none; transition: opacity 0.15s ease; }

/* ─── Animations ─── */
@keyframes slideDown { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="container-fluid py-4 px-3 px-md-4">

    {{-- Header --}}
    <div class="top-header-bar">
        <div>
            <div class="page-eyebrow">Gestion académique</div>
            <h2 class="page-title">Liste des Étudiants</h2>
            <p class="page-subtitle">Filtres avancés, accès et affectations aux filières</p>
        </div>
        <a href="{{ route('superadmin.etudiants.create') }}" class="btn-add">
            <i class="fas fa-plus"></i> Ajouter un étudiant
        </a>
    </div>

    {{-- Filtres --}}
    <div class="filter-bar">
        <form id="ajaxFilterForm" action="{{ route('superadmin.etudiants.index') }}" method="GET" class="filter-form">
            <div class="filter-inputs-group">

                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInp" name="search" class="filter-input"
                           placeholder="Nom, matricule, email..."
                           value="{{ request('search') }}">
                </div>

                <select id="levelSel" name="niveau" class="filter-select">
                    <option value="">Tous les niveaux</option>
                    <option value="Licence 1" {{ request('niveau') == 'Licence 1' ? 'selected' : '' }}>Licence 1</option>
                    <option value="Licence 2" {{ request('niveau') == 'Licence 2' ? 'selected' : '' }}>Licence 2</option>
                    <option value="Licence 3" {{ request('niveau') == 'Licence 3' ? 'selected' : '' }}>Licence 3</option>
                    <option value="Master 1"  {{ request('niveau') == 'Master 1'  ? 'selected' : '' }}>Master 1</option>
                    <option value="Master 2"  {{ request('niveau') == 'Master 2'  ? 'selected' : '' }}>Master 2</option>
                </select>

                <select id="statusSel" name="statut" class="filter-select">
                    <option value="">Tous les statuts</option>
                    <option value="actif"  {{ request('statut') == 'actif'  ? 'selected' : '' }}>Actifs</option>
                    <option value="bloque" {{ request('statut') == 'bloque' ? 'selected' : '' }}>Bloqués</option>
                </select>

            </div>

            <a href="{{ route('superadmin.etudiants.index') }}" id="btnReset"
               class="btn-reset {{ !request('search') && !request('niveau') && !request('statut') ? 'd-none' : '' }}">
                <i class="fas fa-times"></i> Réinitialiser
            </a>
        </form>
    </div>

    {{-- Tableau --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Filière</th>
                        <th>Niveau</th>
                        <th>Statut</th>
                        <th style="text-align:right;padding-right:1.5rem">Actions</th>
                    </tr>
                </thead>
                <tbody id="studentsTableBody">
                    @include('superadmin.etudiants.partials.table-rows')
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div id="paginationContainer">
        @if(method_exists($etudiants, 'links'))
            {{ $etudiants->appends(request()->query())->links() }}
        @endif
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInp          = document.getElementById('searchInp');
    const levelSel           = document.getElementById('levelSel');
    const statusSel          = document.getElementById('statusSel');
    const tableBody          = document.getElementById('studentsTableBody');
    const paginationContainer= document.getElementById('paginationContainer');
    const btnReset           = document.getElementById('btnReset');

    let debounceTimer;
    const BASE_URL = "{{ route('superadmin.etudiants.index') }}";

    function doFetch(extraParams = {}) {
        tableBody.classList.add('table-loading');

        const params = new URLSearchParams({
            search: searchInp.value,
            niveau: levelSel.value,
            statut: statusSel.value,
            ajax: 1,
            ...extraParams
        });

        // Bouton reset
        (searchInp.value || levelSel.value || statusSel.value)
            ? btnReset.classList.remove('d-none')
            : btnReset.classList.add('d-none');

        fetch(`${BASE_URL}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            tableBody.innerHTML          = data.html;
            paginationContainer.innerHTML = data.pagination;
            tableBody.classList.remove('table-loading');

            const cleanParams = new URLSearchParams({
                search: searchInp.value,
                niveau: levelSel.value,
                statut: statusSel.value,
                ...extraParams
            });
            window.history.pushState({}, '', `${BASE_URL}?${cleanParams}`);
        })
        .catch(err => {
            console.error('AJAX error:', err);
            tableBody.classList.remove('table-loading');
        });
    }

    searchInp.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => doFetch(), 300);
    });

    levelSel.addEventListener('change',  () => doFetch());
    statusSel.addEventListener('change', () => doFetch());

    paginationContainer.addEventListener('click', function (e) {
        const link = e.target.closest('a');
        if (!link) return;
        e.preventDefault();
        const page = new URL(link.href).searchParams.get('page');
        if (page) doFetch({ page });
    });
});
</script>

@endsection
