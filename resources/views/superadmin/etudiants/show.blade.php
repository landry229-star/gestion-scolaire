@extends('layouts.superadmin')

@section('title', 'Détails Étudiant')

@section('content')

<!-- Google Fonts pour rester raccord avec l'Edit -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Syne:wght@700&display=swap" rel="stylesheet">

<style>
:root {
    --bg-main: #f4f6fa;
    --card-bg: #ffffff;
    --text-heading: #090d16;
    --text-body: #475569;
    --text-muted: #94a3b8;
    --border-color: #f1f5f9;
    --primary: #3b82f6;
    --success: #10b981;
    --danger: #ef4444;
}

body, .container-fluid {
    background-color: var(--bg-main);
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-body);
}

/* ── Barre d'en-tête identique à la page Edit ── */
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

/* Grille principale */
.profile-grid {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 1.75rem;
    align-items: start;
}

@media (max-width: 992px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
}

/* Style des cartes */
.modern-card {
    background: var(--card-bg);
    border-radius: 16px;
    border: 1px solid var(--border-color);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    padding: 2rem;
}

/* Sidebar Gauche */
.profile-sidebar {
    text-align: center;
}

.avatar-circle {
    width: 80px;
    height: 80px;
    background: #eff6ff;
    color: var(--primary);
    font-size: 28px;
    font-weight: 700;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem auto;
    text-transform: uppercase;
    border: 3px solid #dbeafe;
}

.student-name {
    font-family: 'Syne', sans-serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-heading);
    margin-bottom: 0.25rem;
}

.matricule-badge {
    background: #f1f5f9;
    color: #475569;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 1.5rem;
}

/* Zone de Statut */
.status-display-zone {
    margin-bottom: 1.25rem;
    padding: 10px 14px;
    border-radius: 10px;
    background: #fafafa;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 1px solid var(--border-color);
    width: 100%;
}

.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}
.status-dot.actif { background-color: var(--success); box-shadow: 0 0 8px rgba(16, 185, 129, 0.4); }
.status-dot.bloque { background-color: var(--danger); box-shadow: 0 0 8px rgba(239, 68, 68, 0.4); }

.sidebar-actions-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    border-top: 1px solid var(--border-color);
    padding-top: 1.25rem;
    margin-top: 1.25rem;
}

/* Grille d'informations (Droite) */
.card-section-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--text-heading);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-centered-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

@media (max-width: 576px) {
    .info-centered-grid {
        grid-template-columns: 1fr;
    }
}

.info-item {
    padding: 1rem;
    background: #fafafa;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    transition: all 0.2s;
}

.info-item:hover {
    background: #fdfdfd;
    border-color: #e2e8f0;
}

.info-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-muted);
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.info-value {
    font-size: 0.95rem;
    color: var(--text-heading);
    font-weight: 600;
}

/* Boutons */
.btn-modern {
    padding: 10px 18px;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    border: none;
    cursor: pointer;
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

.spinner-hidden { display: none; width: 0.85rem; height: 0.85rem; }
</style>

<div class="container-fluid py-4">

    <!-- ── La Barre d'En-tête Centrée et Alignée ── -->
    <div class="top-header-bar">
        <div class="page-title-group">
            <h2 class="page-title">Fiche Étudiant</h2>
            <p class="page-subtitle">Visualisation complète du dossier universitaire</p>
        </div>
        <a href="{{ route('superadmin.etudiants.index') }}" class="btn-modern btn-modern-secondary text-decoration-none">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="profile-grid">

        <!-- BLOC GAUCHE : Identité & Actions rapides -->
        <div class="modern-card profile-sidebar">
            <div class="avatar-circle">
                {{ substr($etudiant->prenom, 0, 1) }}{{ substr($etudiant->nom, 0, 1) }}
            </div>
            <h3 class="student-name">{{ $etudiant->nom }} {{ $etudiant->prenom }}</h3>
            <span class="matricule-badge">Matricule : {{ $etudiant->matricule ?? $etudiant->id_etudiant }}</span>

            <!-- Statut dynamique JS -->
            <div>
                <div class="status-display-zone">
                    <span id="jsStatusIndicator" class="status-dot {{ $etudiant->statut_compte == 'actif' ? 'actif' : 'bloque' }}"></span>
                    <span id="jsStatusText" class="fw-bold" style="font-size: 0.8rem; color: var(--text-heading);">
                        {{ $etudiant->statut_compte == 'actif' ? 'Compte Actif' : 'Compte Bloqué' }}
                    </span>
                </div>

                <button id="btnToggleStatus"
                        data-id="{{ $etudiant->id_etudiant }}"
                        data-current="{{ $etudiant->statut_compte }}"
                        class="btn {{ $etudiant->statut_compte == 'actif' ? 'btn-outline-danger' : 'btn-outline-success' }} btn-sm w-100 rounded-pill fw-bold py-2" style="font-size: 0.8-rem;">
                    <i class="fas fa-power-off me-1"></i>
                    <span id="btnToggleText">{{ $etudiant->statut_compte == 'actif' ? 'Bloquer l\'accès' : 'Débloquer l\'accès' }}</span>
                    <div class="spinner-border spinner-border-sm text-secondary spinner-hidden ms-1" id="statusSpinner" role="status"></div>
                </button>
            </div>

            <div class="sidebar-actions-group">
                <a href="{{ route('superadmin.etudiants.edit', $etudiant->id_etudiant) }}" class="btn-modern text-white text-decoration-none" style="background-color: var(--primary);">
                    <i class="fas fa-edit"></i> Modifier le profil
                </a>
                <a href="{{ route('superadmin.etudiants.notes', $etudiant->id_etudiant) }}" class="btn-modern text-white text-decoration-none" style="background-color: #0ea5e9;">
                    <i class="fas fa-chart-bar"></i> Notes & Bulletins
                </a>
                <a href="{{ route('superadmin.etudiants.releve', $etudiant->id_etudiant) }}" class="btn-modern text-white text-decoration-none" style="background-color: #1f2937;">
                    <i class="fas fa-file-invoice"></i> Générer un Relevé
                </a>
            </div>
        </div>

        <!-- BLOC DROIT : Dossier d'informations -->
        <div class="modern-card">

            <div class="card-section-title">
                <i class="fas fa-id-card text-primary"></i> Dossier Académique & Civil
            </div>

            <div class="info-centered-grid">

                <div class="info-item">
                    <div class="info-label">Numéro de Matricule</div>
                    <div class="info-value" style="color: var(--primary);">{{ $etudiant->matricule ?? $etudiant->id_etudiant }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Cursus / Filière</div>
                    <div class="info-value">{{ $etudiant->filiere->nom_filiere ?? 'Non assignée' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Adresse Email</div>
                    <div class="info-value">{{ $etudiant->email }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value">{{ $etudiant->telephone ?? '---' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Date de Naissance</div>
                    <div class="info-value">
                        {{ $etudiant->date_naissance ? date('d/m/Y', strtotime($etudiant->date_naissance)) : '---' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Lieu de Naissance</div>
                    <div class="info-value">{{ $etudiant->lieu_naissance ?? '---' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Nationalité</div>
                    <div class="info-value">{{ $etudiant->nationalite ?? '---' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Niveau d'Études</div>
                    <div class="info-value">
                        <span class="badge bg-light text-dark border px-2 py-1" style="border-radius: 6px; font-weight: 600;">
                            {{ $etudiant->niveau }}
                        </span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Logique JS asynchrone d'inversion de statut (100% autonome via URL relative) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnToggle = document.getElementById('btnToggleStatus');
    const indicator = document.getElementById('jsStatusIndicator');
    const statusText = document.getElementById('jsStatusText');
    const btnText = document.getElementById('btnToggleText');
    const spinner = document.getElementById('statusSpinner');

    if (btnToggle) {
        btnToggle.addEventListener('click', function() {
            const currentStatus = this.getAttribute('data-current');
            const newStatus = (currentStatus === 'actif') ? 'bloque' : 'actif';

            spinner.style.display = 'inline-block';
            btnToggle.disabled = true;

            const baseUrl = window.location.href.split('?')[0];
            const targetUrl = baseUrl.endsWith('/') ? `${baseUrl}toggle-status` : `${baseUrl}/toggle-status`;

            fetch(targetUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ statut: newStatus })
            })
            .then(response => {
                if (!response.ok) throw new Error('Erreur réseau');
                return response.json();
            })
            .then(data => {
                if(data.success || data.new_status) {
                    if (newStatus === 'actif') {
                        indicator.className = 'status-dot actif';
                        statusText.innerText = 'Compte Actif';
                        btnText.innerText = 'Bloquer l\'accès';
                        btnToggle.className = 'btn btn-outline-danger btn-sm w-100 rounded-pill fw-bold py-2';
                        btnToggle.setAttribute('data-current', 'actif');
                    } else {
                        indicator.className = 'status-dot bloque';
                        statusText.innerText = 'Compte Bloqué';
                        btnText.innerText = 'Débloquer l\'accès';
                        btnToggle.className = 'btn btn-outline-success btn-sm w-100 rounded-pill fw-bold py-2';
                        btnToggle.setAttribute('data-current', 'bloque');
                    }
                }
            })
            .catch(error => {
                console.error(error);
                alert('Une erreur est survenue lors de la mise à jour du statut.');
            })
            .finally(() => {
                spinner.style.display = 'none';
                btnToggle.disabled = false;
            });
        });
    }
});
</script>

@endsection
