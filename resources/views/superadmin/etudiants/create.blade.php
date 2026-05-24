@extends('layouts.superadmin')

@section('title', 'Ajouter un Étudiant')

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
    --primary: #3b82f6;
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

.page-title-group { display: flex; flex-direction: column; gap: 2px; }
.page-title { font-family: 'Syne', sans-serif; font-weight: 700; color: var(--text-heading); font-size: 1.4rem; margin: 0; }
.page-subtitle { color: var(--text-muted); font-size: 0.8rem; margin: 0; font-weight: 500; }

.modern-card {
    background: var(--card-bg);
    border-radius: 16px;
    border: 1px solid var(--border-color);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    padding: 2.5rem;
    max-width: 900px;
    margin: 0 auto;
}

.form-section-title {
    font-size: 0.95rem; font-weight: 700; color: var(--text-heading);
    text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1.75rem;
    display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;
}

.form-grid {
    display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; margin-bottom: 2rem;
}

@media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }

.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-label { font-size: 0.8rem; font-weight: 600; color: var(--text-heading); }
.form-control-modern {
    padding: 10px 14px; font-size: 0.9rem; font-weight: 500; color: var(--text-heading);
    background-color: #fafafa; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; transition: all 0.2s;
}
.form-control-modern:focus {
    background-color: #ffffff; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.08);
}

.btn-modern { padding: 10px 20px; font-size: 0.85rem; font-weight: 600; border-radius: 8px; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; cursor: pointer; border: none; }
.btn-modern-primary { background-color: var(--primary); color: #ffffff; }
.btn-modern-primary:hover { background-color: #2563eb; }
.btn-modern-secondary { background-color: #ffffff; border: 1px solid #e2e8f0; color: var(--text-body); }
.btn-modern-secondary:hover { background-color: #f8fafc; }
</style>

<div class="container-fluid py-4">

    <div class="top-header-bar" style="max-width: 900px; margin: 0 auto 2rem auto;">
        <div class="page-title-group">
            <h2 class="page-title">Nouvel Étudiant</h2>
            <p class="page-subtitle">Immatriculation et ouverture de dossier d'accès</p>
        </div>
        <a href="{{ route('superadmin.etudiants.index') }}" class="btn-modern btn-modern-secondary">
            <i class="fas fa-arrow-left"></i> Annuler
        </a>
    </div>

    <div class="modern-card">
        <!-- Messages flash d'erreurs Laravel -->
        @if ($errors->any())
            <div class="alert alert-danger rounded-3 mb-4 py-2 px-3" style="font-size: 0.85rem;">
                <ul class="m-0">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('superadmin.etudiants.store') }}" method="POST">
            @csrf

            <!-- Section 1 : État Civil -->
            <div class="form-section-title"><i class="fas fa-user text-primary"></i> Informations Personnelles</div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="nom" class="form-control-modern" value="{{ old('nom') }}" required placeholder="Ex: DOSSA">
                </div>
                <div class="form-group">
                    <label class="form-label">Prénom <span class="text-danger">*</span></label>
                    <input type="text" name="prenom" class="form-control-modern" value="{{ old('prenom') }}" required placeholder="Ex: Jean">
                </div>
                <div class="form-group">
                    <label class="form-label">Date de Naissance</label>
                    <input type="date" name="date_naissance" class="form-control-modern" value="{{ old('date_naissance') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Lieu de Naissance</label>
                    <input type="text" name="lieu_naissance" class="form-control-modern" value="{{ old('lieu_naissance') }}" placeholder="Ex: Cotonou">
                </div>
                <div class="form-group">
                    <label class="form-label">Nationalité</label>
                    <input type="text" name="nationalite" class="form-control-modern" value="{{ old('nationalite') }}" placeholder="Ex: Béninoise">
                </div>
                <div class="form-group">
                    <label class="form-label">Téléphone</label>
                    <input type="tel" name="telephone" class="form-control-modern" value="{{ old('telephone') }}" placeholder="Ex: +229 00000000">
                </div>
            </div>

            <!-- Section 2 : Identifiants & Cursus -->
            <div class="form-section-title"><i class="fas fa-graduation-cap text-primary"></i> Cadre Académique & Sécurité</div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Adresse Email Académique <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control-modern" value="{{ old('email') }}" required placeholder="etudiant@universite.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Numéro Matricule (Optionnel)</label>
                    <input type="text" name="matricule" class="form-control-modern" value="{{ old('matricule') }}" placeholder="Laisser vide pour auto-générer">
                </div>
                <div class="form-group">
                    <label class="form-label">Filière d'Étude <span class="text-danger">*</span></label>
                    <select name="id_filiere" class="form-control-modern" style="padding: 9px 14px;" required>
                        <option value="">Sélectionner une filière</option>
                        @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id_filiere }}" {{ old('id_filiere') == $filiere->id_filiere ? 'selected' : '' }}>
                                {{ $filiere->nom_filiere }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Niveau Actuel <span class="text-danger">*</span></label>
                    <select name="niveau" class="form-control-modern" style="padding: 9px 14px;" required>
                        <option value="Licence 1" {{ old('niveau') == 'Licence 1' ? 'selected' : '' }}>Licence 1</option>
                        <option value="Licence 2" {{ old('niveau') == 'Licence 2' ? 'selected' : '' }}>Licence 2</option>
                        <option value="Licence 3" {{ old('niveau') == 'Licence 3' ? 'selected' : '' }}>Licence 3</option>
                        <option value="Master 1" {{ old('niveau') == 'Master 1' ? 'selected' : '' }}>Master 1</option>
                        <option value="Master 2" {{ old('niveau') == 'Master 2' ? 'selected' : '' }}>Master 2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de Passe d'accès Initial <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control-modern" required placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label class="form-label">Statut du compte à l'ouverture</label>
                    <select name="statut_compte" class="form-control-modern" style="padding: 9px 14px;">
                        <option value="actif" selected>Actif (Accès autorisé immédiat)</option>
                        <option value="bloque">Bloqué (En attente d'activation)</option>
                    </select>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="d-flex justify-content-end gap-2 border-top pt-4">
                <button type="submit" class="btn-modern btn-modern-primary">
                    <i class="fas fa-check"></i> Enregistrer l'étudiant
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
