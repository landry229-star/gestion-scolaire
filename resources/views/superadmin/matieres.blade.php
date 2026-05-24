@extends('layouts.superadmin')

@section('title', 'Matières')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght=400;500;600&display=swap');

.page {
    font-family: 'DM Sans', sans-serif;
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
    color: #1a1a1a;
}

/* ── HEADER ── */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1.5px solid #e8e4da;
}
.page-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #0f172a;
    margin: 0;
}
.page-title span { color: #6366f1; }

.count-badge {
    background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe;
    border-radius: 999px; padding: 0.25rem 0.85rem; font-size: 0.8rem; font-weight: 500;
}

/* ── CARD & FORM GRID ── */
.card { background: #fff; border: 1px solid #e8e4da; border-radius: 14px; padding: 1.5rem; margin-bottom: 1.75rem; }
.card-title { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #94a3b8; margin: 0 0 1.1rem; }

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 0.75rem;
    align-items: end;
}
.form-group { display: flex; flex-direction: column; gap: 0.35rem; }
.form-group label { font-size: 0.72rem; font-weight: 500; color: #64748b; }
.form-group input, .form-group select {
    height: 40px; padding: 0 0.75rem; border: 1px solid #dde1e7; border-radius: 8px;
    font-size: 0.875rem; color: #1a1a1a; background: #fafafa; outline: none;
    width: 100%;
    box-sizing: border-box;
}
.form-group input:focus, .form-group select:focus { border-color: #6366f1; background: #fff; }

/* ── FILTRES (LISTE DÉROULANTE) ── */
.filter-container {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    background: #f8fafc;
    padding: 1rem;
    border-radius: 10px;
    border: 1px solid #e8e4da;
}

/* ── BUTTONS ── */
.btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem;
    padding: 0 1.2rem; height: 40px; border-radius: 8px; font-size: 0.85rem; font-weight: 500;
    cursor: pointer; border: none; text-decoration: none; transition: 0.15s;
}
.btn-primary { background: #6366f1; color: #fff; }
.btn-primary:hover { background: #4f46e5; }
.btn-sm { height: 32px; padding: 0 0.6rem; font-size: 0.75rem; border-radius: 6px; }
.btn-warning { background: #f59e0b; color: white; }
.btn-warning:hover { background: #d97706; }
.btn-danger { background: #ef4444; color: white; }
.btn-danger:hover { background: #dc2626; }
.btn-secondary { background: #e2e8f0; color: #475569; }

/* ── TABLE ── */
.table-wrap { background: #fff; border: 1px solid #e8e4da; border-radius: 14px; overflow: hidden; }
table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
thead { background: #f8fafc; border-bottom: 1px solid #e8e4da; }
thead th { padding: 0.75rem 1.1rem; text-align: left; font-size: 0.72rem; font-weight: 600; color: #64748b; text-transform: uppercase; }
tbody tr { border-bottom: 1px solid #f1f5f9; }
tbody tr:hover { background-color: #f8fafc; }
tbody td { padding: 0.8rem 1.1rem; vertical-align: middle; }

/* ── CHIPS ── */
.code-chip { background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; border-radius: 6px; padding: 0.15rem 0.6rem; font-family: monospace; font-weight: 600; }
.credit-chip { background: #fdf4ff; color: #7e22ce; border: 1px solid #e9d5ff; border-radius: 999px; padding: 0.2rem 0.65rem; font-weight: 500; }
.filiere-chip { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; border-radius: 6px; padding: 0.15rem 0.6rem; }
.semestre-chip { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; border-radius: 6px; padding: 0.15rem 0.6rem; }
.dept-chip { background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; border-radius: 6px; padding: 0.15rem 0.6rem; }

/* ── MODAL SYSTEM AMÉLIORÉ (SCROLLABLE & FLUIDE) ── */
.modal {
    display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000;
    padding: 1rem; box-sizing: border-box;
}
.modal-content {
    background: white;
    padding: 2rem;
    border-radius: 14px;
    width: 100%;
    max-width: 550px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    max-height: 85vh;
    overflow-y: auto; /* Permet de scroller à l'intérieur si l'écran est petit */
}
.modal-grid { display: flex; flex-direction: column; gap: 1.1rem; margin-top: 1.2rem; }
</style>

<div class="page">

    @if(session('success'))
        <div style="color: #16a34a; background: #f0fdf4; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <h2 class="page-title">Gestion des <span>matières</span></h2>
        <div>
            <a href="{{ route('superadmin.attributions') }}" class="btn btn-secondary" style="margin-right: 0.5rem;">🔗 Attributions Enseignants</a>
            <span class="count-badge">{{ count($matieres) }} matière(s)</span>
        </div>
    </div>

    {{-- FORMULAIRE DE CRÉATION --}}
    <div class="card">
        <p class="card-title">&#43; Nouvelle matière</p>
        <form method="POST" action="{{ route('superadmin.matieres.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Département</label>
                    <select name="id_departement" required>
                        <option value="">— Choisir —</option>
                        @foreach($departements as $d)
                            <option value="{{ $d->id_departement }}">{{ $d->nom_departement }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Filière</label>
                    <select name="id_filiere" required>
                        <option value="">— Choisir —</option>
                        @foreach($filieres as $f)
                            <option value="{{ $f->id_filiere }}">{{ $f->nom_filiere }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Code</label>
                    <input name="code_matiere" value="{{ old('code_matiere') }}" placeholder="INF101" required>
                </div>
                <div class="form-group">
                    <label>Nom de la matière</label>
                    <input name="nom_matiere" value="{{ old('nom_matiere') }}" placeholder="Algorithmique" required>
                </div>
                <div class="form-group">
                    <label>Crédits</label>
                    <input name="credit_matiere" type="number" min="1" value="{{ old('credit_matiere') }}" placeholder="3" required>
                </div>
                <div class="form-group">
                    <label>Semestre</label>
                    <select name="id_semestre">
                        <option value="">— Optionnel —</option>
                        @foreach($semestres as $s)
                            <option value="{{ $s->id_semestre }}">{{ $s->code_semestre }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Créer</button>
            </div>
        </form>
    </div>

    {{-- BARRE DE FILTRES DYNAMIQUES (LISTES DÉROULANTES) --}}
    <div class="filter-container">
        <div class="form-group" style="flex: 1;">
            <label>Filtrer par Département</label>
            <select id="tableFilterDepartement" onchange="filterTable()">
                <option value="">Tous les départements</option>
                @foreach($departements as $d)
                    <option value="{{ $d->nom_departement }}">{{ $d->nom_departement }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="flex: 1;">
            <label>Filtrer par Filière</label>
            <select id="tableFilterFiliere" onchange="filterTable()">
                <option value="">Toutes les filières</option>
                @foreach($filieres as $f)
                    <option value="{{ $f->nom_filiere }}">{{ $f->nom_filiere }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="flex: 1;">
            <label>Filtrer par Semestre</label>
            <select id="tableFilterSemestre" onchange="filterTable()">
                <option value="">Tous les semestres</option>
                @foreach($semestres as $s)
                    <option value="{{ $s->code_semestre }}">{{ $s->code_semestre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- TABLEAU DYNAMIQUE DANS L'ORDRE DE SAISIE --}}
    <div class="table-wrap">
        <table id="matieresTable">
            <thead>
                <tr>
                    <th>Département</th>
                    <th>Filière</th>
                    <th>Code</th>
                    <th>Nom</th>
                    <th>Crédits</th>
                    <th>Semestre</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($matieres as $m)
                <tr data-departement="{{ $m->departement ? $m->departement->nom_departement : '' }}"
                    data-filiere="{{ $m->filiere ? $m->filiere->nom_filiere : '' }}"
                    data-semestre="{{ $m->semestre ? $m->semestre->code_semestre : '' }}">

                    {{-- 1. Département --}}
                    <td>
                        @if($m->departement)
                            <span class="dept-chip">{{ $m->departement->nom_departement }}</span>
                        @else
                            <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>

                    {{-- 2. Filière --}}
                    <td>
                        @if($m->filiere)
                            <span class="filiere-chip">{{ $m->filiere->nom_filiere }}</span>
                        @else
                            <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>

                    {{-- 3. Code --}}
                    <td><span class="code-chip">{{ $m->code_matiere }}</span></td>

                    {{-- 4. Nom --}}
                    <td style="font-weight:500;">{{ $m->nom_matiere }}</td>

                    {{-- 5. Crédits --}}
                    <td><span class="credit-chip">★ {{ $m->credit_matiere }}</span></td>

                    {{-- 6. Semestre --}}
                    <td>
                        @if($m->semestre)
                            <span class="semestre-chip">{{ $m->semestre->code_semestre }}</span>
                        @else
                            <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 0.4rem;">
                            <button type="button" class="btn btn-sm btn-warning"
                                    onclick="openEditModal({{ json_encode($m) }})">
                                ✏️ Modifier
                            </button>

                            <a href="{{ route('superadmin.matieres.delete', $m->id_matiere) }}" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Confirmer la suppression ?')">
                                🗑️ Supprimer
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="noDataRow"><td colspan="7" style="text-align:center; padding:2rem; color:#94a3b8;">Aucune matière trouvée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── MODAL DE MODIFICATION SÉCURISÉ & SCROLLABLE ── --}}
<div id="editModal" class="modal">
    <div class="modal-content">
        <h3 style="margin-top:0; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem; color: #0f172a;">Modifier la matière</h3>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="modal-grid">
                 <div class="form-group">
                    <label>Département</label>
                    <select id="edit_departement" name="id_departement" required>
                        <option value="">— Choisir —</option>
                        @foreach($departements as $d)
                            <option value="{{ $d->id_departement }}">{{ $d->nom_departement }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Filière</label>
                    <select id="edit_filiere" name="id_filiere" required>
                        @foreach($filieres as $f)
                            <option value="{{ $f->id_filiere }}">{{ $f->nom_filiere }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Code de la matière</label>
                    <input type="text" id="edit_code" name="code_matiere" required>
                </div>

                <div class="form-group">
                    <label>Nom de la matière</label>
                    <input type="text" id="edit_nom" name="nom_matiere" required>
                </div>

                <div class="form-group">
                    <label>Crédits</label>
                    <input type="number" id="edit_credit" name="credit_matiere" min="1" required>
                </div>

                <div class="form-group">
                    <label>Semestre</label>
                    <select id="edit_semestre" name="id_semestre">
                        <option value="">— Choisir —</option>
                        @foreach($semestres as $s)
                            <option value="{{ $s->id_semestre }}">{{ $s->code_semestre }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.5rem; margin-top:1.5rem; border-top: 1px solid #f1f5f9; padding-top: 1rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Filtrage dynamique avec les listes déroulantes
function filterTable() {
    const deptFilter = document.getElementById('tableFilterDepartement').value.toLowerCase();
    const filiereFilter = document.getElementById('tableFilterFiliere').value.toLowerCase();
    const semestreFilter = document.getElementById('tableFilterSemestre').value.toLowerCase();

    const rows = document.querySelectorAll('#matieresTable tbody tr:not(#noDataRow)');
    let visibleRowsCount = 0;

    rows.forEach(row => {
        const rowDept = (row.getAttribute('data-departement') || '').toLowerCase();
        const rowFiliere = (row.getAttribute('data-filiere') || '').toLowerCase();
        const rowSemestre = (row.getAttribute('data-semestre') || '').toLowerCase();

        const matchDept = !deptFilter || rowDept === deptFilter;
        const matchFiliere = !filiereFilter || rowFiliere === filiereFilter;
        const matchSemestre = !semestreFilter || rowSemestre === semestreFilter;

        if (matchDept && matchFiliere && matchSemestre) {
            row.style.display = '';
            visibleRowsCount++;
        } else {
            row.style.display = 'none';
        }
    });

    let noDataRow = document.getElementById('dynamicNoDataRow');
    if (visibleRowsCount === 0) {
        if (!noDataRow) {
            noDataRow = document.createElement('tr');
            noDataRow.id = 'dynamicNoDataRow';
            noDataRow.innerHTML = '<td colspan="7" style="text-align:center; padding:2rem; color:#94a3b8;">Aucune matière ne correspond à ces filtres.</td>';
            document.querySelector('#matieresTable tbody').appendChild(noDataRow);
        }
    } else if (noDataRow) {
        noDataRow.remove();
    }
}

// Remplissage dynamique et ouverture du modal
function openEditModal(matiere) {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editForm');

    form.action = `/super-admin/matieres/${matiere.id_matiere}`;

    document.getElementById('edit_departement').value = matiere.id_departement || '';
    document.getElementById('edit_filiere').value = matiere.id_filiere;
    document.getElementById('edit_code').value = matiere.code_matiere;
    document.getElementById('edit_nom').value = matiere.nom_matiere;
    document.getElementById('edit_credit').value = matiere.credit_matiere;
    document.getElementById('edit_semestre').value = matiere.id_semestre || '';

    modal.style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>
@endsection
