@extends('layouts.superadmin')

@section('title', 'Attributions Enseignants')

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

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1.5px solid #e8e4da;
}
.page-title { font-size: 1.75rem; font-weight: 600; color: #0f172a; margin: 0; }
.page-title span { color: #6366f1; }

.card { background: #fff; border: 1px solid #e8e4da; border-radius: 14px; padding: 1.5rem; margin-bottom: 1.75rem; }
.card-title { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #94a3b8; margin: 0 0 1.1rem; }

.form-grid {
    display: grid;
    grid-template-columns: 2fr 2fr auto;
    gap: 1rem;
    align-items: end;
}
.form-grid-modal {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 1rem;
}
.form-group { display: flex; flex-direction: column; gap: 0.35rem; }
.form-group label { font-size: 0.72rem; font-weight: 500; color: #64748b; }
.form-group select {
    height: 40px; padding: 0 0.75rem; border: 1px solid #dde1e7; border-radius: 8px;
    font-size: 0.875rem; background: #fafafa; outline: none;
}

.btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem;
    padding: 0 1.2rem; height: 40px; border-radius: 8px; font-size: 0.85rem; font-weight: 500;
    cursor: pointer; border: none; text-decoration: none; transition: 0.15s;
}
.btn-primary { background: #6366f1; color: #fff; }
.btn-primary:hover { background: #4f46e5; }
.btn-secondary { background: #e2e8f0; color: #475569; }
.btn-sm { height: 28px; padding: 0 0.5rem; font-size: 0.75rem; border-radius: 6px; }
.btn-warning { background: #f59e0b; color: white; }
.btn-warning:hover { background: #d97706; }
.btn-danger { background: #ef4444; color: white; }
.btn-danger:hover { background: #dc2626; }

.table-wrap { background: #fff; border: 1px solid #e8e4da; border-radius: 14px; overflow: hidden; }
table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
thead { background: #f8fafc; border-bottom: 1px solid #e8e4da; }
thead th { padding: 0.75rem 1.1rem; text-align: left; font-size: 0.72rem; font-weight: 600; color: #64748b; text-transform: uppercase; }
tbody tr { border-bottom: 1px solid #f1f5f9; }
tbody td { padding: 1rem 1.1rem; vertical-align: middle; }

.teacher-name { font-weight: 600; color: #0f172a; font-size: 0.95rem; }
.subjects-list { display: flex; flex-direction: column; gap: 0.5rem; }

/* Nouvelle ligne d'attribution propre */
.attribution-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
}
.subject-info { font-size: 0.85rem; color: #334155; }
.subject-info strong { color: #4338ca; background: #eef2ff; padding: 0.1rem 0.4rem; border-radius: 4px; font-family: monospace; }

.actions-group { display: flex; gap: 0.3rem; }

/* ── MODAL SYSTEM ── */
.modal {
    display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.4); justify-content: center; align-items: center; z-index: 1000;
}
.modal-content { background: white; padding: 2rem; border-radius: 14px; width: 450px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
</style>

<div class="page">
    @if(session('success'))
        <div style="color: #16a34a; background: #f0fdf4; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <h2 class="page-title">Attribution des <span>matières aux enseignants</span></h2>
        <a href="{{ route('superadmin.matieres') }}" class="btn btn-secondary">⬅️ Retour aux matières</a>
    </div>

    {{-- FORMULAIRE D'AFFECTATION (CRÉATION) --}}
    <div class="card">
        <p class="card-title">Affecter une nouvelle matière</p>
        <form method="POST" action="{{ route('superadmin.attributions.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Enseignant</label>
                    <select name="id_enseignant" required>
                        <option value="">— Sélectionner l'enseignant —</option>
                        @foreach($enseignants as $e)
                            <option value="{{ $e->id_enseignant }}">{{ $e->nom }} {{ $e->prenom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Matière</label>
                    <select name="id_matiere" required>
                        <option value="">— Sélectionner la matière —</option>
                        @foreach($matieres_liste as $mat)
                            <option value="{{ $mat->id_matiere }}">[{{ $mat->code_matiere }}] {{ $mat->nom_matiere }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Assigner la matière</button>
            </div>
        </form>
    </div>

    {{-- TABLEAU D'AFFICHAGE --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width: 30%;">Enseignant</th>
                    <th style="width: 70%;">Matières assignées & Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enseignants as $e)
                <tr>
                    <td class="teacher-name">👨‍🏫 {{ $e->nom }} {{ $e->prenom }}</td>
                    <td>
                        <div class="subjects-list">
                            @if($e->matieres && $e->matieres->count() > 0)
                                @foreach($e->matieres as $matiere)
                                    <div class="attribution-row">
                                        <div class="subject-info">
                                            <strong>{{ $matiere->code_matiere }}</strong> — {{ $matiere->nom_matiere }}
                                        </div>

                                        {{-- Groupe d'actions par matière --}}
                                        <div class="actions-group">
                                            {{-- Bouton Modifier l'attribution --}}
                                            <button type="button" class="btn btn-sm btn-warning"
                                                    onclick="openEditAttributionModal({{ $e->id_enseignant }}, {{ $matiere->id_matiere }})">
                                                ✏️ Changer
                                            </button>

                                            {{-- Bouton Supprimer (Détacher) l'attribution --}}
                                            <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="triggerDetach({{ $e->id_enseignant }}, {{ $matiere->id_matiere }}, '{{ addslashes($matiere->nom_matiere) }}', '{{ addslashes($e->nom . " " . $e->prenom) }}')">
                                                🗑️ Retirer
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <span style="color:#94a3b8; font-style: italic;">Aucune matière assignée actuellement</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="2" style="text-align:center; padding:2rem; color:#94a3b8;">Aucun enseignant configuré dans le système.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── MODAL DE MODIFICATION DE L'ATTRIBUTION ── --}}
<div id="editAttributionModal" class="modal">
    <div class="modal-content">
        <h3 style="margin-top:0; color:#0f172a;">Modifier l'attribution</h3>
        <p style="font-size:0.8rem; color:#64748b; margin-top:-0.5rem;">Sélectionnez la nouvelle matière pour remplacer l'ancienne.</p>

        <form id="editAttributionForm" method="POST" action="{{ route('superadmin.attributions.store') }}">
            @csrf
            {{-- On passe l'identifiant de l'enseignant ciblé --}}
            <input type="hidden" id="modal_id_enseignant" name="id_enseignant">
            {{-- On passe l'ancien ID de la matière pour que le contrôleur sache laquelle supprimer avant d'ajouter la nouvelle --}}
            <input type="hidden" id="modal_old_id_matiere" name="old_id_matiere">

            <div class="form-grid-modal">
                <div class="form-group">
                    <label>Nouvelle Matière</label>
                    <select id="modal_id_matiere" name="id_matiere" required>
                        @foreach($matieres_liste as $mat)
                            <option value="{{ $mat->id_matiere }}">[{{ $mat->code_matiere }}] {{ $mat->nom_matiere }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.5rem; margin-top:1rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeEditAttributionModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- FORMULAIRE CACHÉ POUR LA SUPPRESSION (DETACH) --}}
<form id="detach-hidden-form" action="{{ route('superadmin.attributions.detach') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" id="detach-teacher-id" name="id_enseignant">
    <input type="hidden" id="detach-subject-id" name="id_matiere">
</form>

{{-- ── SCRIPT DYNAMIQUE ── --}}
<script>
// Gestion de la modification (Ouverture modal)
function openEditAttributionModal(teacherId, currentSubjectId) {
    document.getElementById('modal_id_enseignant').value = teacherId;
    document.getElementById('modal_old_id_matiere').value = currentSubjectId;
    document.getElementById('modal_id_matiere').value = currentSubjectId; // Sélectionne par défaut la matière actuelle

    document.getElementById('editAttributionModal').style.display = 'flex';
}

function closeEditAttributionModal() {
    document.getElementById('editAttributionModal').style.display = 'none';
}

// Gestion de la suppression (Formulaire fantôme inline)
function triggerDetach(teacherId, subjectId, subjectName, teacherName) {
    if (confirm(`Voulez-vous vraiment retirer la matière "${subjectName}" à l'enseignant ${teacherName} ?`)) {
        document.getElementById('detach-teacher-id').value = teacherId;
        document.getElementById('detach-subject-id').value = subjectId;
        document.getElementById('detach-hidden-form').submit();
    }
}
</script>
@endsection
