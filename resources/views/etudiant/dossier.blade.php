@extends('etudiant.app')

@section('title', 'Mon dossier')
@section('page-title', 'Mon dossier d\'inscription')

@section('content')

@php
    $locked = $inscription && $inscription->statut_dossier === 'valide';
@endphp

<style>
/* ══════════════════════════════════════════
   🎯 REFACTORING CSS POUR LE RESPONSIVE MOBILE
   ══════════════════════════════════════════ */
:root {
    --primary-rgb: 59, 130, 246;
    --success-rgb: 16, 185, 129;
    --warning-rgb: 245, 158, 11;
    --danger-rgb: 239, 68, 68;
}

/* Grilles Fluides adaptatives */
.responsive-grid-3 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.responsive-grid-2 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

/* Layout asymétrique de l'Étape 2 (Formulaire + Pièces jointes) */
.step-2-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    align-items: start;
}

/* Wrapper global du stepper */
.stepper-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    background: var(--surface, #fff);
    padding: 12px;
    border-radius: 12px;
    box-shadow: var(--shadow-sm, 0 2px 4px rgba(0,0,0,0.05));
    overflow-x: auto; /* Évite de casser si l'écran est minuscule */
}

.step-item {
    text-align: center;
    flex: 1;
    position: relative;
    opacity: 0.4;
    transition: all 0.3s ease;
    min-width: 80px;
}

.step-item.active { opacity: 1; font-weight: 700; color: var(--primary); }
.step-item.completed { opacity: 0.9; color: var(--success); }

.step-index {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #cbd5e1;
    color: #fff;
    line-height: 28px;
    margin: 0 auto 4px;
    font-weight: bold;
    font-size: 13px;
}
.step-item.active .step-index { background: var(--primary); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2); }
.step-item.completed .step-index { background: var(--success); }
.step-text { font-size: 12px; white-space: nowrap; }

/* Éléments de formulaire */
.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 12px;
}
.form-label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
    color: #334155;
}
.form-control {
    width: 100% !important; /* Force l'alignement sans déborder */
    box-sizing: border-box;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    font-size: 14px;
}

.file-zone {
    border: 2px dashed #cbd5e1;
    padding: 15px 10px;
    cursor: pointer;
    text-align: center;
    border-radius: 8px;
    background: #f8fafc;
    transition: all 0.2s;
}
.file-zone:hover { border-color: var(--primary); background: rgba(59, 130, 246, 0.02); }

/* Zone boutons de navigation basse */
.actions-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 25px;
    border-top: 1px solid #e2e8f0;
    padding-top: 15px;
    gap: 10px;
}

/* Zone récapitulative Table */
.recap-wrapper {
    overflow-x: auto; /* Protection table sur mobile */
}
.recap-table td {
    padding: 10px 6px;
    font-size: 13.5px;
}

/* 📱 MEDIA QUERIES SÉCURITÉ MOBILE (Écrans inférieurs à 768px) */
@media (max-width: 768px) {
    .step-2-layout {
        grid-template-columns: 1fr; /* Passe le bloc pièces jointes sous le formulaire principal */
    }
    .recap-table tr {
        display: flex;
        flex-direction: column;
        padding: 8px 0;
    }
    .recap-table td {
        padding: 2px 0;
        width: 100% !important;
    }
    .actions-footer {
        flex-direction: column-reverse; /* Met le bouton "Suivant" en haut sur mobile */
    }
    .actions-footer button, .actions-footer div {
        width: 100%;
    }
    .actions-footer div {
        flex-direction: column;
    }
}
</style>

{{-- 1. BANNIÈRES D'ÉTAT DYNAMIQUES --}}
<div id="statusBannerContainer" style="margin-bottom: 20px;">
    @if($locked)
        <div class="alert alert-success" style="border-left: 4px solid var(--success); background: rgba(16, 185, 129, 0.05); display: flex; align-items: start; gap: 12px; padding: 12px; border-radius: 8px;">
            <i class="ti ti-lock" style="font-size: 20px; color: var(--success); margin-top: 2px;"></i>
            <div style="font-size: 13.5px;">
                <strong>Dossier validé définitivement !</strong><br>
                Votre dossier a été accepté par la scolarité. Les informations ne sont plus modifiables.
            </div>
        </div>
    @elseif($inscription && $inscription->statut_dossier === 'en_cours')
        <div class="alert alert-warning" style="border-left: 4px solid var(--warning); background: rgba(245, 158, 11, 0.05); display: flex; align-items: start; gap: 12px; padding: 12px; border-radius: 8px;">
            <i class="ti ti-clock" style="font-size: 20px; color: var(--warning); margin-top: 2px;"></i>
            <div style="font-size: 13.5px;">
                <strong>Dossier en cours d'examen.</strong><br> Demande en attente de traitement par la scolarité.
            </div>
        </div>
    @elseif($inscription && $inscription->statut_dossier === 'rejete')
        <div class="alert alert-error" style="border-left: 4px solid var(--danger); background: rgba(239, 68, 68, 0.05); display: flex; align-items: start; gap: 12px; padding: 12px; border-radius: 8px;">
            <i class="ti ti-alert-circle" style="font-size: 20px; color: var(--danger); margin-top: 2px;"></i>
            <div style="font-size: 13.5px;">
                <strong>Dossier renvoyé pour correction.</strong>
                @if($inscription->motif_rejet) <p style="margin: 4px 0 0 0;"><strong>Motif :</strong> {{ $inscription->motif_rejet }}</p> @endif
            </div>
        </div>
    @endif
</div>

@if(!$locked)
    {{-- BARRE DE PROGRESSION DU STEPPER --}}
    <div class="stepper-container">
        <div class="step-item active" id="step-dot-1">
            <div class="step-index">1</div>
            <div class="step-text">État Civil</div>
        </div>
        <div class="step-item" id="step-dot-2">
            <div class="step-index">2</div>
            <div class="step-text">Choix & Diplôme</div>
        </div>
        <div class="step-item" id="step-dot-3">
            <div class="step-index">3</div>
            <div class="step-text">Vérification</div>
        </div>
    </div>
@endif

{{-- 2. FORMULAIRE PRINCIPAL --}}
<form method="POST" action="{{ route('etudiant.dossier.store') }}" enctype="multipart/form-data" id="dossierFormAjax" autocomplete="off">
    @csrf

    {{-- ========================================================
         ÉTAPE 1 : RENSEIGNEMENTS PERSONNELS & ÉTAT CIVIL
         ======================================================== --}}
    <div class="step-section" id="step-section-1">
        <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div class="card-header" style="padding: 15px; border-bottom: 1px solid #e2e8f0; font-weight: 700;">
                <div class="card-title" style="margin:0; font-size:15px;"><i class="ti ti-id-badge" style="color: var(--primary);"></i> Étape 1 : Renseignements Personnels</div>
            </div>
            <div class="card-body" style="padding: 15px;">
                <div class="responsive-grid-2">
                    <div class="form-group">
                        <label class="form-label">Nom de famille</label>
                        <input class="form-control" type="text" value="{{ $etudiant->nom }}" disabled style="background: #f1f5f9; text-transform: uppercase; font-weight:600;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prénoms</label>
                        <input class="form-control" type="text" value="{{ $etudiant->prenom }}" disabled style="background: #f1f5f9; font-weight:600;">
                    </div>
                </div>

                <div class="responsive-grid-3">
                    <div class="form-group">
                        <label class="form-label">Sexe *</label>
                        <select name="sexe" id="input-sexe" class="form-control" {{ $locked ? 'disabled' : '' }}>
                            <option value="">— Sélectionner —</option>
                            <option value="M" {{ old('sexe', $etudiant->sexe) === 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('sexe', $etudiant->sexe) === 'F' ? 'selected' : '' }}>Féminin</option>
                        </select>
                        <div class="invalid-feedback" id="err-sexe" style="color:var(--danger); font-size:11px; margin-top:2px;"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Date de naissance *</label>
                        <input type="date" name="date_naissance" id="input-date_naissance" class="form-control"
                               value="{{ old('date_naissance', $etudiant->date_naissance ? \Carbon\Carbon::parse($etudiant->date_naissance)->format('Y-m-d') : '') }}" {{ $locked ? 'disabled' : '' }}>
                        <div class="invalid-feedback" id="err-date_naissance" style="color:var(--danger); font-size:11px; margin-top:2px;"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lieu de naissance *</label>
                        <input type="text" name="lieu_naissance" id="input-lieu_naissance" class="form-control"
                               value="{{ old('lieu_naissance', $etudiant->lieu_naissance) }}" placeholder="Ex: Cotonou" {{ $locked ? 'disabled' : '' }}>
                        <div class="invalid-feedback" id="err-lieu_naissance" style="color:var(--danger); font-size:11px; margin-top:2px;"></div>
                    </div>
                </div>

                <div class="responsive-grid-3">
                    <div class="form-group">
                        <label class="form-label">Numéro Téléphone GSM *</label>
                        <input type="tel" name="telephone" id="input-telephone" class="form-control"
                               value="{{ old('telephone', $etudiant->telephone ?? $inscription->telephone ?? '') }}" placeholder="Ex: 97000000" {{ $locked ? 'disabled' : '' }}>
                        <div class="invalid-feedback" id="err-telephone" style="color:var(--danger); font-size:11px; margin-top:2px;"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nationalité *</label>
                        <input type="text" name="nationalite" id="input-nationalite" class="form-control"
                               value="{{ old('nationalite', $etudiant->nationalite ?? 'Béninoise') }}" {{ $locked ? 'disabled' : '' }}>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Niveau d'étude actuel *</label>
                        <select name="niveau" id="input-niveau" class="form-control" {{ $locked ? 'disabled' : '' }}>
                            <option value="">— Sélectionner —</option>
                            <option value="Licence 1" {{ old('niveau', $etudiant->niveau) === 'Licence 1' ? 'selected' : '' }}>Licence 1</option>
                            <option value="Licence 2" {{ old('niveau', $etudiant->niveau) === 'Licence 2' ? 'selected' : '' }}>Licence 2</option>
                            <option value="Licence 3" {{ old('niveau', $etudiant->niveau) === 'Licence 3' ? 'selected' : '' }}>Licence 3</option>
                            <option value="Master 1" {{ old('niveau', $etudiant->niveau) === 'Master 1' ? 'selected' : '' }}>Master 1</option>
                            <option value="Master 2" {{ old('niveau', $etudiant->niveau) === 'Master 2' ? 'selected' : '' }}>Master 2</option>
                        </select>
                        <div class="invalid-feedback" id="err-niveau" style="color:var(--danger); font-size:11px; margin-top:2px;"></div>
                    </div>
                </div>

                <div class="responsive-grid-2">
                    <div class="form-group">
                        <label class="form-label">Adresse complète de résidence *</label>
                        <input type="text" name="adresse" id="input-adresse" class="form-control"
                               value="{{ old('adresse', $etudiant->adresse ?? $inscription->adresse ?? '') }}" placeholder="Ex: Calavi, Zogbadjè" {{ $locked ? 'disabled' : '' }}>
                        <div class="invalid-feedback" id="err-adresse" style="color:var(--danger); font-size:11px; margin-top:2px;"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Groupe Sanguin</label>
                        <select name="groupe_sanguin" id="input-sanguin" class="form-control" {{ $locked ? 'disabled' : '' }}>
                            <option value="">— Non spécifié —</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $grp)
                                <option value="{{ $grp }}" {{ old('groupe_sanguin', $etudiant->groupe_sanguin ?? '') === $grp ? 'selected' : '' }}>{{ $grp }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================
         ÉTAPE 2 : CHOIX ACADÉMIQUE & INFORMATIONS DU BAC + PIÈCES
         ======================================================== --}}
    <div class="step-section" id="step-section-2" style="display: none;">
        <div class="step-2-layout">

            {{-- Colonne Gauche : Formulaires --}}
            <div style="display: flex; flex-direction: column; gap: 16px; width:100%;">
                <div class="card" style="border-radius:12px; border:1px solid #e2e8f0; background:#fff;">
                    <div class="card-header" style="padding:15px; border-bottom:1px solid #e2e8f0; font-weight:700; font-size:15px;">
                        <i class="ti ti-school" style="color: var(--primary);"></i> Étape 2.1 : Inscription Pédagogique
                    </div>
                    <div class="card-body" style="padding: 15px;">
                        <div class="responsive-grid-2">
                            <div class="form-group">
                                <label class="form-label">Entité / École / Faculté *</label>
                                <select name="entite" id="input-entite" class="form-control" {{ $locked ? 'disabled' : '' }}>
                                    <option value="">— Choisir l'entité —</option>
                                    <option value="FLASH" {{ old('entite', $inscription->entite ?? '') === 'FLASH' ? 'selected' : '' }}>FLASH</option>
                                    <option value="FASEG" {{ old('entite', $inscription->entite ?? '') === 'FASEG' ? 'selected' : '' }}>FASEG</option>
                                    <option value="FADESP" {{ old('entite', $inscription->entite ?? '') === 'FADESP' ? 'selected' : '' }}>FADESP</option>
                                    <option value="FAST" {{ old('entite', $inscription->entite ?? '') === 'FAST' ? 'selected' : '' }}>FAST</option>
                                    <option value="IUT" {{ old('entite', $inscription->entite ?? '') === 'IUT' ? 'selected' : '' }}>IUT</option>
                                </select>
                                <div class="invalid-feedback" id="err-entite" style="color:var(--danger); font-size:11px; margin-top:2px;"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Date de l'inscription *</label>
                                <input type="date" name="date_inscription" id="input-date_inscription" class="form-control"
                                       value="{{ old('date_inscription', $inscription->date_inscription ? \Carbon\Carbon::parse($inscription->date_inscription)->format('Y-m-d') : date('Y-m-d')) }}" {{ $locked ? 'disabled' : '' }}>
                            </div>
                        </div>

                        <div class="responsive-grid-2">
                            <div class="form-group">
                                <label class="form-label">Filière d'étude sollicitée *</label>
                                <select name="id_filiere" id="input-filiere" class="form-control" {{ $locked ? 'disabled' : '' }}>
                                    <option value="">— Choisir votre filière —</option>
                                    @foreach($filieres as $filiere)
                                        <option value="{{ $filiere->id_filiere }}" {{ old('id_filiere', $inscription->id_filiere ?? $etudiant->id_filiere) == $filiere->id_filiere ? 'selected' : '' }}>{{ $filiere->nom_filiere }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="err-id_filiere" style="color:var(--danger); font-size:11px; margin-top:2px;"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Année d'étude *</label>
                                <select name="annee_etude" id="input-annee_etude" class="form-control" {{ $locked ? 'disabled' : '' }}>
                                    <option value="">— Choisir l'année d'étude —</option>
                                    <option value="1" {{ old('annee_etude', $inscription->annee_etude ?? '') == '1' ? 'selected' : '' }}>1ère Année</option>
                                    <option value="2" {{ old('annee_etude', $inscription->annee_etude ?? '') == '2' ? 'selected' : '' }}>2ème Année</option>
                                    <option value="3" {{ old('annee_etude', $inscription->annee_etude ?? '') == '3' ? 'selected' : '' }}>3ème Année</option>
                                </select>
                                <div class="invalid-feedback" id="err-annee_etude" style="color:var(--danger); font-size:11px; margin-top:2px;"></div>
                            </div>
                        </div>

                        <div class="responsive-grid-2">
                            <div class="form-group">
                                <label class="form-label">Régime d'étude *</label>
                                <select name="regime" id="input-regime" class="form-control" {{ $locked ? 'disabled' : '' }}>
                                    <option value="cours_du_jour" {{ old('regime', $inscription->regime ?? '') === 'cours_du_jour' ? 'selected' : '' }}>Cours du Jour</option>
                                    <option value="cours_du_soir" {{ old('regime', $inscription->regime ?? '') === 'cours_du_soir' ? 'selected' : '' }}>Cours du Soir</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Prise en charge *</label>
                                <select name="statut_boursier" id="input-statut_boursier" class="form-control" {{ $locked ? 'disabled' : '' }}>
                                    <option value="Non Boursier" {{ old('statut_boursier', $inscription->statut_boursier ?? '') === 'Non Boursier' ? 'selected' : '' }}>Non Boursier</option>
                                    <option value="Boursier" {{ old('statut_boursier', $inscription->statut_boursier ?? '') === 'Boursier' ? 'selected' : '' }}>Boursier de l'État</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" style="border-radius:12px; border:1px solid #e2e8f0; background:#fff;">
                    <div class="card-header" style="padding:15px; border-bottom:1px solid #e2e8f0; font-weight:700; font-size:15px;">
                        <i class="ti ti-certificate" style="color: var(--success);"></i> Étape 2.2 : Références du BAC
                    </div>
                    <div class="card-body" style="padding: 15px;">
                        <div class="responsive-grid-3">
                            <div class="form-group">
                                <label class="form-label">N° Table (BAC) *</label>
                                <input type="text" name="num_table_bac" id="input-table-bac" class="form-control" value="{{ old('num_table_bac', $etudiant->num_table_bac ?? '') }}" placeholder="Ex: 102-CC24" {{ $locked ? 'disabled' : '' }}>
                                <div class="invalid-feedback" id="err-num_table_bac" style="color:var(--danger); font-size:11px; margin-top:2px;"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Série du BAC *</label>
                                <select name="serie_bac" id="input-serie-bac" class="form-control" {{ $locked ? 'disabled' : '' }}>
                                    <option value="">— Série —</option>
                                    @foreach(['C', 'D', 'E', 'G1', 'G2', 'A1', 'A2'] as $serie)
                                        <option value="{{ $serie }}" {{ old('serie_bac', $etudiant->serie_bac ?? '') === $serie ? 'selected' : '' }}>Série {{ $serie }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Année obtention *</label>
                                <input type="number" name="annee_bac" id="input-annee-bac" class="form-control" value="{{ old('annee_bac', $etudiant->annee_bac ?? 2025) }}" {{ $locked ? 'disabled' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Colonne Droite : Pièces jointes (S'adapte sous le formulaire en version Mobile) --}}
            <div style="display: flex; flex-direction: column; gap: 16px; width:100%;">
                <div class="card" style="text-align: center; padding: 15px; border-radius:12px; border:1px solid #e2e8f0; background:#fff;">
                    <div style="margin-bottom: 10px;">
                        <img id="avatarPreview" src="{{ $etudiant->photo ? asset('storage/' . $etudiant->photo) : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=100&h=100&q=80' }}" style="width:90px; height:90px; border-radius:50%; object-fit:cover; border:2px solid #e2e8f0;">
                    </div>
                    <div class="file-zone" @if(!$locked) onclick="document.getElementById('photo-input').click()" @endif>
                        <input type="file" name="photo" accept="image/jpeg,image/png" id="photo-input" style="display:none;">
                        <div style="font-size:11.5px; font-weight:600;" id="photo-label">Photo Fond Blanc *</div>
                    </div>
                </div>

                <div class="card" style="padding: 15px; gap:12px; display:flex; flex-direction:column; border-radius:12px; border:1px solid #e2e8f0; background:#fff;">
                    <label class="form-label" style="margin-bottom:0;">Documents obligatoires :</label>
                    <div class="file-zone" @if(!$locked) onclick="document.getElementById('cip-input').click()" @endif>
                        <input type="file" name="document_cip" accept="image/jpeg,image/png,application/pdf" id="cip-input" style="display:none;">
                        <div id="cip-label" style="font-size:11.5px;">Charger l'acte CIP/CNIE *</div>
                    </div>
                    <div class="file-zone" @if(!$locked) onclick="document.getElementById('bac-input').click()" @endif>
                        <input type="file" name="diplome_bac" accept="image/jpeg,image/png,application/pdf" id="bac-input" style="display:none;">
                        <div id="bac-label" style="font-size:11.5px;">Charger Attestation BAC *</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ========================================================
         ÉTAPE 3 : VÉRIFICATION GLOBALE (RÉCAPITULATIF INTÉGRAL)
         ======================================================== --}}
    <div class="step-section" id="step-section-3" style="display: none;">
        <div class="card" style="border-radius:12px; border:1px solid #e2e8f0; background:#fff;">
            <div class="card-header" style="padding:15px; border-bottom:1px solid #e2e8f0; font-weight:700; font-size:15px;">
                <i class="ti ti-eye" style="color: var(--success);"></i> Étape 3 : Récapitulatif Fiche Inscription
            </div>
            <div class="card-body" style="padding: 15px;">
                <p style="color:#64748b; margin-bottom:15px; font-size:13px;">Contrôlez vos déclarations avant la validation définitive.</p>

                <div class="recap-wrapper">
                    <table class="recap-table" style="width: 100%; border-collapse: collapse;">
                        <tr style="border-bottom: 1px solid #f1f5f9;"><td style="font-weight:700; width:35%;">Étudiant :</td><td style="text-transform:uppercase; font-weight:600;" id="recap-nom">{{ $etudiant->nom }} {{ $etudiant->prenom }}</td></tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;"><td style="font-weight:700;">Sexe & Niveau :</td><td id="recap-sexe">—</td></tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;"><td style="font-weight:700;">Naissance :</td><td id="recap-naissance">—</td></tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;"><td style="font-weight:700;">Contact :</td><td id="recap-contact">—</td></tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;"><td style="font-weight:700;">Résidence :</td><td id="recap-adresse">—</td></tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;"><td style="font-weight:700; color:var(--primary);">Affectation :</td><td style="font-weight:600; color:var(--primary);" id="recap-filiere">—</td></tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;"><td style="font-weight:700;">Modalités :</td><td id="recap-regime">—</td></tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;"><td style="font-weight:700; color:var(--success);">Fichier BAC :</td><td id="recap-bac">—</td></tr>
                    </table>
                </div>

                <div style="margin-top:15px; padding:10px; background:#fff9db; border-left:4px solid #fcc419; border-radius:6px; font-size:12px; color:#664d03;">
                    ⚠️ <strong>Avertissement :</strong> Toute fausse déclaration sur vos diplômes ou pièces d'état civil annulera votre cursus.
                </div>
            </div>
        </div>
    </div>

    {{-- ZONE DES BOUTONS DE NAVIGATION --}}
    @if(!$locked)
        <div class="actions-footer">
            <button type="button" class="btn form-control" id="prevBtn" style="display: none; background:#cbd5e1; font-weight:600;" onclick="changeStep(-1)">
                <i class="ti ti-arrow-left"></i> Précédent
            </button>
            <div style="margin-left: auto; display: flex; gap: 10px; width: 100%;">
                <button type="button" class="btn id-next-btn form-control" id="nextBtn" style="background:var(--primary); color:white; font-weight:600;" onclick="changeStep(1)">
                    Suivant <i class="ti ti-arrow-right"></i>
                </button>
                <button type="submit" class="btn form-control" id="saveDossierBtn" style="display:none; background:var(--success); color:white; font-weight:700;">
                    <i class="ti ti-send"></i> Soumettre mon dossier
                </button>
            </div>
        </div>
    @endif
</form>

{{-- MODAL DE RÉUSSITE MOBILE-RESPONSIVE --}}
<div id="successDownloadModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(15,36,58,0.7); z-index:9999; align-items:center; justify-content:center; padding:15px;">
    <div class="card" style="width:100%; max-width:420px; padding:20px; text-align:center; background:#fff; border-radius:12px;">
        <i class="ti ti-circle-check" style="font-size:45px; color:var(--success);"></i>
        <h3 style="font-size:16px; font-weight:700; margin-top:10px;">Dossier Transmis avec Succès !</h3>
        <p style="color:#64748b; font-size:12.5px; margin: 10px 0 20px;">Votre demande d'inscription est enregistrée et verrouillée.</p>
        <div style="display:flex; flex-direction:column; gap:8px;">
            <a href="{{ route('pdf.pre_inscription') }}" target="_blank" class="btn" style="background:var(--success); color:white; padding:10px; border-radius:6px; font-weight:600; text-decoration:none; font-size:13px;"><i class="ti ti-download"></i> Imprimer ma Fiche d'Inscription</a>
            <button type="button" onclick="window.location.reload();" class="btn" style="border:1px solid #cbd5e1; padding:10px; border-radius:6px; font-size:13px;">Fermer</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentStep = 1;

function showStep(step) {
    document.querySelectorAll('.step-section').forEach((section, index) => {
        section.style.display = (index + 1 === step) ? 'block' : 'none';
    });

    document.querySelectorAll('.step-item').forEach((dot, index) => {
        if (index + 1 === step) dot.className = "step-item active";
        else if (index + 1 < step) dot.className = "step-item completed";
        else dot.className = "step-item";
    });

    document.getElementById('prevBtn').style.display = (step === 1) ? 'none' : 'block';
    if (step === 3) {
        document.getElementById('nextBtn').style.display = 'none';
        document.getElementById('saveDossierBtn').style.display = 'block';
        remplirRecapitulatif();
    } else {
        document.getElementById('nextBtn').style.display = 'block';
        document.getElementById('saveDossierBtn').style.display = 'none';
    }
    window.scrollTo({top: 0, behavior: 'smooth'}); // Remonte en haut de page à chaque changement sur mobile
}

function changeStep(n) {
    if (n === 1 && currentStep === 1) {
        if(!document.getElementById('input-sexe').value || !document.getElementById('input-date_naissance').value || !document.getElementById('input-adresse').value || !document.getElementById('input-telephone').value || !document.getElementById('input-niveau').value) {
            alert("Erreur : Renseignez tous les champs obligatoires (*) de l'Étape 1.");
            return;
        }
    }
    if (n === 1 && currentStep === 2) {
        if(!document.getElementById('input-filiere').value || !document.getElementById('input-table-bac').value || !document.getElementById('input-entite').value || !document.getElementById('input-annee_etude').value) {
            alert("Erreur : Complétez vos choix d'école, filière, année d'étude et votre numéro de table BAC.");
            return;
        }
    }
    currentStep += n;
    showStep(currentStep);
}

function remplirRecapitulatif() {
    const sx = document.getElementById('input-sexe').value === 'M' ? 'Masculin' : 'Féminin';
    const nv = document.getElementById('input-niveau').value;
    const sg = document.getElementById('input-sanguin').value || 'Non spécifié';

    document.getElementById('recap-sexe').textContent = `${sx} — [${nv}] — (Sanguin : ${sg})`;
    document.getElementById('recap-naissance').textContent = `${document.getElementById('input-date_naissance').value} à ${document.getElementById('input-lieu_naissance').value}`;
    document.getElementById('recap-contact').textContent = `${document.getElementById('input-telephone').value} (Nationalité : ${document.getElementById('input-nationalite').value})`;
    document.getElementById('recap-adresse').textContent = document.getElementById('input-adresse').value;

    const fil = document.getElementById('input-filiere');
    const filNom = fil.options[fil.selectedIndex] ? fil.options[fil.selectedIndex].text : '—';
    document.getElementById('recap-filiere').textContent = `[${document.getElementById('input-entite').value}] ${filNom} — Année d'étude : ${document.getElementById('input-annee_etude').value}`;

    const reg = document.getElementById('input-regime').value === 'cours_du_jour' ? 'Jour' : 'Soir';
    document.getElementById('recap-regime').textContent = `${reg} (${document.getElementById('input-statut_boursier').value}) — Fait le ${document.getElementById('input-date_inscription').value}`;
    document.getElementById('recap-bac').textContent = `Table : ${document.getElementById('input-table-bac').value} — Série : ${document.getElementById('input-serie-bac').value} — Année : ${document.getElementById('input-annee-bac').value}`;
}

document.addEventListener('DOMContentLoaded', function () {
    showStep(currentStep);

    const matchFile = (inpId, lblId) => {
        document.getElementById(inpId)?.addEventListener('change', function() {
            if(this.files[0]) document.getElementById(lblId).innerHTML = `✅ ${this.files[0].name.substring(0,14)}...`;
        });
    };
    matchFile('cip-input', 'cip-label');
    matchFile('bac-input', 'bac-label');
    matchFile('photo-input', 'photo-label');

    document.getElementById('photo-input')?.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => { document.getElementById('avatarPreview').src = e.target.result; }
            reader.readAsDataURL(this.files[0]);
        }
    });

    const form = document.getElementById('dossierFormAjax');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('saveDossierBtn');
        btn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(async response => {
            const data = await response.json();
            if(response.ok && data.success) {
                document.getElementById('successDownloadModal').style.display = 'flex';
            } else {
                alert(data.message || "Erreur lors de la soumission. Vérifiez vos pièces jointes.");
                btn.disabled = false;
            }
        }).catch(() => {
            alert("Erreur de connexion serveur.");
            btn.disabled = false;
        });
    });
});
</script>
@endpush

@endsection
