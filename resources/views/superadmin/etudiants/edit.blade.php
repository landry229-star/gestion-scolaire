@extends('layouts.superadmin')

@section('title', 'Modifier l\'Étudiant')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;500;600;700&family=Syne:wght=700&display=swap" rel="stylesheet">

<style>
:root {
    --bg-main: #f4f6fa;
    --card-bg: #ffffff;
    --text-heading: #090d16;
    --text-body: #475569;
    --text-muted: #94a3b8;
    --border-color: #f1f5f9;
    --border-focus: #3b82f6;
    --primary: #3b82f6;
    --success: #10b981;
    --danger: #ef4444;
}

body, .container-fluid {
    background-color: var(--bg-main);
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-body);
}

.top-header-bar {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    margin-bottom: 2rem;
}

.page-title-group {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.page-title {
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    color: var(--text-heading);
    font-size: 1.4rem;
    margin: 0;
    line-height: 1.2;
}

.page-subtitle {
    color: var(--text-muted);
    font-size: 0.8rem;
    margin: 0;
    font-weight: 500;
}

.layout-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 1.75rem;
    align-items: start;
}

@media (max-width: 992px) {
    .layout-grid {
        grid-template-columns: 1fr;
    }
}

.modern-card {
    background: var(--card-bg);
    border-radius: 16px;
    border: 1px solid var(--border-color);
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
    padding: 2rem;
}

.card-section-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--text-heading);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-group {
    margin-bottom: 1.25rem;
}

.field-label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-body);
    margin-bottom: 0.5rem;
}

.modern-input, .modern-select {
    width: 100%;
    padding: 10px 14px;
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--text-heading);
    background-color: #fafafa;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    transition: all 0.2s ease;
    outline: none;
}

.modern-input:focus, .modern-select:focus {
    background-color: #ffffff;
    border-color: var(--border-focus);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.btn-modern {
    padding: 10px 18px;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
    cursor: pointer;
}

.btn-modern-primary {
    background-color: var(--primary);
    color: #ffffff;
}

.btn-modern-primary:hover {
    background-color: #2563eb;
    transform: translateY(-1px);
}

.btn-modern-secondary {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    color: var(--text-body);
}

.btn-modern-secondary:hover {
    background-color: #f8fafc;
    color: var(--text-heading);
}

.status-picker {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.status-option {
    position: relative;
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #fafafa;
}

.status-option:hover {
    background: #f1f5f9;
}

.status-radio {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 12px;
}
.dot-actif { background-color: var(--success); }
.dot-bloque { background-color: var(--danger); }

.status-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-body);
    flex-grow: 1;
}

.status-option.active-actif {
    border-color: var(--success);
    background-color: rgba(16, 185, 129, 0.04);
}
.status-option.active-bloque {
    border-color: var(--danger);
    background-color: rgba(239, 68, 68, 0.04);
}
</style>

<div class="container-fluid py-4">

    <div class="top-header-bar">
        <div class="page-title-group">
            <h2 class="page-title">Modifier l'étudiant</h2>
            <p class="page-subtitle">Mise à jour des informations de la fiche académique</p>
        </div>
        <a href="{{ route('superadmin.etudiants.show', $etudiant->id_etudiant) }}" class="btn-modern btn-modern-secondary text-decoration-none">
            <i class="fas fa-arrow-left"></i> Retour au profil
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-4 mb-4 shadow-sm" style="background-color: #fef2f2; color: var(--danger);">
            <ul class="mb-0 small fw-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('superadmin.etudiants.update', $etudiant->id_etudiant) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="layout-grid">

            <div class="modern-card">

                <div class="card-section-title">
                    <i class="fas fa-user-circle text-primary"></i> Identité & Contact
                </div>

                <div class="row row-cols-1 row-cols-md-2 g-3 mb-4">
                    <div class="col">
                        <div class="form-group">
                            <label for="nom" class="field-label">Nom de famille</label>
                            <input type="text" class="modern-input" id="nom" name="nom" value="{{ old('nom', $etudiant->nom) }}" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="prenom" class="field-label">Prénom</label>
                            <input type="text" class="modern-input" id="prenom" name="prenom" value="{{ old('prenom', $etudiant->prenom) }}" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="email" class="field-label">Adresse email officielle</label>
                            <input type="email" class="modern-input" id="email" name="email" value="{{ old('email', $etudiant->email) }}" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="telephone" class="field-label">Numéro de téléphone</label>
                            <input type="text" class="modern-input" id="telephone" name="telephone" value="{{ old('telephone', $etudiant->telephone) }}">
                        </div>
                    </div>
                </div>

                <div class="card-section-title">
                    <i class="fas fa-graduation-cap text-primary"></i> Informations Civiles & Études
                </div>

                <div class="row row-cols-1 row-cols-md-2 g-3 mb-2">
                    <div class="col">
                        <div class="form-group">
                            <label for="date_naissance" class="field-label">Date de naissance</label>
                            <input type="date" class="modern-input" id="date_naissance" name="date_naissance" value="{{ old('date_naissance', $etudiant->date_naissance) }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="lieu_naissance" class="field-label">Lieu de naissance</label>
                            <input type="text" class="modern-input" id="lieu_naissance" name="lieu_naissance" value="{{ old('lieu_naissance', $etudiant->lieu_naissance) }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="nationalite" class="field-label">Nationalité</label>
                            <input type="text" class="modern-input" id="nationalite" name="nationalite" value="{{ old('nationalite', $etudiant->nationalite) }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="niveau" class="field-label">Niveau d'études actuel</label>
                            <select class="modern-select" id="niveau" name="niveau" required>
                                <option value="Licence 1" {{ old('niveau', $etudiant->niveau) == 'Licence 1' ? 'selected' : '' }}>Licence 1</option>
                                <option value="Licence 2" {{ old('niveau', $etudiant->niveau) == 'Licence 2' ? 'selected' : '' }}>Licence 2</option>
                                <option value="Licence 3" {{ old('niveau', $etudiant->niveau) == 'Licence 3' ? 'selected' : '' }}>Licence 3</option>
                                <option value="Master 1" {{ old('niveau', $etudiant->niveau) == 'Master 1' ? 'selected' : '' }}>Master 1</option>
                                <option value="Master 2" {{ old('niveau', $etudiant->niveau) == 'Master 2' ? 'selected' : '' }}>Master 2</option>
                            </select>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="id_departement" class="field-label">Département d'attache</label>
                            <select class="modern-select" id="id_departement" name="id_departement" required
                                    data-selected="{{ old('id_departement', $etudiant->filiere->id_departement ?? '') }}">
                                <option value="">Chargement des départements...</option>
                            </select>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="id_filiere" class="field-label">Filière / Spécialisation</label>
                            <select class="modern-select" id="id_filiere" name="id_filiere" required
                                    data-selected="{{ old('id_filiere', $etudiant->id_filiere) }}">
                                <option value="">Choisir d'abord un département...</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <div class="d-flex flex-column gap-3">

                <div class="modern-card p-3">
                    <div class="card-section-title mb-3" style="font-size: 0.85rem;">
                        <i class="fas fa-shield-alt text-primary"></i> Statut de l'accès
                    </div>

                    <div class="status-picker">
                        <label class="status-option id-status-actif" id="labelActif">
                            <input type="radio" name="statut_compte" value="actif" class="status-radio" id="radioActif" {{ old('statut_compte', $etudiant->statut_compte) == 'actif' ? 'checked' : '' }}>
                            <span class="status-dot dot-actif"></span>
                            <span class="status-label">Compte Actif</span>
                            <i class="fas fa-check-circle text-success check-icon" id="checkActif" style="display:none; font-size: 0.95rem;"></i>
                        </label>

                        <label class="status-option id-status-bloque" id="labelBloque">
                            <input type="radio" name="statut_compte" value="bloque" class="status-radio" id="radioBloque" {{ old('statut_compte', $etudiant->statut_compte) == 'bloque' ? 'checked' : '' }}>
                            <span class="status-dot dot-bloque"></span>
                            <span class="status-label">Accès Bloqué</span>
                            <i class="fas fa-times-circle text-danger check-icon" id="checkBloque" style="display:none; font-size: 0.95rem;"></i>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-modern btn-modern-primary w-100 justify-content-center py-2.5">
                    <i class="fas fa-cloud-upload-alt"></i> Enregistrer les modifications
                </button>

            </div>

        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── GESTION DE L'INTERFACE DES STATUTS (ACTIF / BLOQUÉ) ──
    const radioActif = document.getElementById('radioActif');
    const radioBloque = document.getElementById('radioBloque');
    const labelActif = document.getElementById('labelActif');
    const labelBloque = document.getElementById('labelBloque');
    const checkActif = document.getElementById('checkActif');
    const checkBloque = document.getElementById('checkBloque');

    function updateStatusUI() {
        if(radioActif.checked) {
            labelActif.className = "status-option active-actif";
            labelBloque.className = "status-option";
            checkActif.style.display = "block";
            checkBloque.style.display = "none";
        } else if(radioBloque.checked) {
            labelActif.className = "status-option";
            labelBloque.className = "active-bloque";
            checkActif.style.display = "none";
            checkBloque.style.display = "block";
        }
    }

    radioActif.addEventListener('change', updateStatusUI);
    radioBloque.addEventListener('change', updateStatusUI);
    updateStatusUI();


    // ── CONFIGURATION & LOGIQUE AJAX POUR LES DROPDOWNS ──
    /* ════════════════════════════════════════════════════════════════════
   AJAX : Département → Filières
════════════════════════════════════════════════════════════════════ */
$('dept-select').addEventListener('change', function () {
    const idDept  = this.value;
    const selFil  = $('filiere-select');
    const loader  = $('filiere-loader');

    /* Reset */
    selFil.innerHTML = '<option value="">Toutes les filières</option>';
    selFil.disabled  = true;
    applyFilters();

    if (!idDept) return;

    /* Loader visible */
    loader.style.display = 'inline-flex';
    selFil.innerHTML     = '<option value="">Chargement…</option>';

    // 1. Génération de l'URL de base avec un placeholder Blade
    const baseUrl = "{{ route('superadmin.getFilieres', '__ID__') }}";
    // 2. Remplacement dynamique du placeholder par l'ID sélectionné en JS
    const targetUrl = baseUrl.replace('__ID__', idDept);

    fetch(targetUrl)
        .then(r => {
            if (!r.ok) throw new Error('Réponse serveur invalide');
            return r.json();
        })
        .then(data => {
            selFil.innerHTML = '<option value="">Toutes les filières</option>';

            if (data.length === 0) {
                selFil.innerHTML = '<option value="">Aucune filière trouvée</option>';
            } else {
                data.forEach(f => {
                    const opt       = document.createElement('option');
                    opt.value       = f.nom_filiere;   // filtrage côté DOM par nom
                    opt.textContent = f.nom_filiere;
                    selFil.appendChild(opt);
                });
                selFil.disabled = false;
            }
        })
        .catch((err) => {
            console.error(err); // Permet de voir le détail exact de l'erreur en console F12
            selFil.innerHTML = '<option value="">Erreur de chargement</option>';
        })
        .finally(() => {
            loader.style.display = 'none';
            applyFilters();
        });
});


    // Déclencheur au changement manuel de département
    selectDept.addEventListener('change', function() {
        loadFilieres(this.value);
    });

    // Lancement de la cascade AJAX
    loadDepartements();
});
</script>

@endsection
