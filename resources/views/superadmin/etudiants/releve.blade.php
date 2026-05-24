@extends('layouts.superadmin')

@section('title', 'Relevé de Notes Officiel')

@section('content')

<div class="container-fluid py-4 main-page">

    <!-- CONFIG PANEL -->
    <div class="config-panel no-print">

        <div class="config-header">
            <div class="header-left-side">
                <div class="config-icon">
                    <i class="fas fa-file-signature"></i>
                </div>
                <div>
                    <h2 class="config-title">Relevé de Notes Officiel</h2>
                    <p class="config-subtitle">Configuration du document académique</p>
                </div>
            </div>

            <!-- BOUTON RETOUR RAJOUTÉ -->
            <a href="{{ route('superadmin.etudiants.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                <span>Retour à la liste</span>
            </a>
        </div>

        <div class="row g-4 mt-1">

            <div class="col-lg-3 col-md-6">
                <label class="config-label">Semestre</label>
                <div class="input-icon-group">
                    <span class="input-icon"><i class="fas fa-layer-group"></i></span>
                    <select id="selectSemestre" class="form-select custom-input custom-select">
                        <option value="">Tous les semestres</option>
                        <option value="S1">Semestre 1</option>
                        <option value="S2">Semestre 2</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <label class="config-label">Année académique</label>
                <div class="input-icon-group">
                    <span class="input-icon"><i class="fas fa-calendar-alt"></i></span>
                    <input type="text" id="inputAnnee" class="form-control custom-input custom-text" value="2025-2026">
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <label class="config-label">Session</label>
                <div class="input-icon-group">
                    <span class="input-icon"><i class="fas fa-check-circle"></i></span>
                    <select id="selectSession" class="form-select custom-input custom-select">
                        <option value="Normale">Session Normale</option>
                        <option value="Rattrapage">Session de Rattrapage</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <label class="config-label">Signataire officiel</label>
                <div class="input-icon-group">
                    <span class="input-icon"><i class="fas fa-user-tie"></i></span>
                    <input type="text" id="inputSignataire" class="form-control custom-input custom-text" value="Dr. Landry AZOGAN">
                </div>
            </div>

        </div>

        <!-- BOUTONS ACTIONS -->
        <div class="config-actions">

            {{-- ✅ TÉLÉCHARGER EN PDF PROPRE (DomPDF) --}}
            <a id="btnDownloadPdf"
               href="{{ route('superadmin.etudiants.telecharger-releve', $etudiant->id_etudiant) }}"
               class="btn-pdf-dl"
               target="_blank">
                <i class="fas fa-file-pdf"></i>
                <span>Télécharger le PDF</span>
            </a>

            {{-- Aperçu impression navigateur --}}
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i>
                <span>Aperçu impression</span>
            </button>

        </div>

    </div>

    <!-- FEUILLE A4 — Aperçu -->
    <div id="relevePaperContainer" class="releve-paper">
        @include('superadmin.etudiants.releve-contenu')
    </div>

</div>

<style>
body { background:#eef2f7; font-family:"Inter","Segoe UI",sans-serif; }
.main-page { min-height:100vh; }

.config-panel {
    background:#fff; border-radius:24px; padding:30px;
    border:1px solid #e2e8f0; margin-bottom:28px;
    box-shadow:0 4px 20px rgba(15,23,42,0.04);
}
.config-header {
    display:flex;
    align-items:center;
    justify-content:space-between; /* Aligne le titre à gauche et le retour à droite */
    gap:18px;
    margin-bottom:28px;
}
.header-left-side { display:flex; align-items:center; gap:18px; }

.config-icon {
    width:58px; height:58px; border-radius:16px;
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff; display:flex; align-items:center; justify-content:center;
    font-size:20px; box-shadow:0 10px 25px rgba(37,99,235,0.22);
}
.config-title { margin:0; font-size:22px; font-weight:700; color:#0f172a; }
.config-subtitle { margin-top:4px; color:#64748b; font-size:13px; }

/* Nouveau Style du Bouton Retour */
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f1f5f9;
    color: #475569;
    padding: 10px 18px;
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid #e2e8f0;
}
.btn-back:hover {
    background: #e2e8f0;
    color: #0f172a;
    transform: translateX(-2px);
}

.config-label { display:block; margin-bottom:8px; font-size:12.5px; font-weight:600; color:#334155; }
.input-icon-group { position:relative; }
.input-icon {
    position:absolute; top:50%; left:14px;
    transform:translateY(-50%); color:#64748b; font-size:13px; z-index:2;
}
.custom-input {
    height:50px; border-radius:13px !important;
    border:1px solid #dbe3ee !important; background:#f8fafc !important;
    font-size:13.5px !important; transition:all .2s;
}
.custom-input:focus {
    background:#fff !important; border-color:#2563eb !important;
    box-shadow:0 0 0 4px rgba(37,99,235,0.12) !important;
}
.custom-select { padding-left:44px !important; appearance:none; cursor:pointer; }
.custom-text   { padding-left:44px !important; }

.config-actions {
    margin-top:28px; padding-top:20px; border-top:1px solid #e2e8f0;
    display:flex; justify-content:flex-end; gap:12px;
}

.btn-pdf-dl {
    border:none;
    background:linear-gradient(135deg,#b91c1c,#991b1b);
    color:#fff; padding:13px 22px; border-radius:13px;
    font-size:13.5px; font-weight:600;
    display:inline-flex; align-items:center; gap:9px;
    text-decoration:none; cursor:pointer;
    transition:all .22s;
    box-shadow:0 6px 18px rgba(185,28,28,0.20);
}
.btn-pdf-dl:hover {
    transform:translateY(-2px);
    box-shadow:0 10px 24px rgba(185,28,28,0.26);
    color:#fff;
}

.btn-print {
    border:none;
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff; padding:13px 22px; border-radius:13px;
    font-size:13.5px; font-weight:600;
    display:inline-flex; align-items:center; gap:9px;
    cursor:pointer; transition:all .22s;
    box-shadow:0 6px 18px rgba(37,99,235,0.18);
}
.btn-print:hover { transform:translateY(-2px); }

.releve-paper {
    background:#fff; max-width:850px; margin:0 auto;
    padding:40px 50px; border-radius:18px;
    border:1px solid #dbe3ee;
    box-shadow:0 10px 30px rgba(15,23,42,0.06);
}

.loading-blur { opacity:0.4; filter:blur(1px); pointer-events:none; }

@media print {
    .no-print { display:none !important; }
    body { background:#fff !important; padding:0 !important; }
    .main-page { padding:0 !important; }
    .releve-paper {
        box-shadow:none !important; border:none !important;
        border-radius:0 !important; margin:0 !important;
        padding:0 !important; max-width:100% !important;
    }
    @page { size:A4; margin:0; }
}

@media(max-width:768px) {
    .config-panel { padding:18px; }
    .config-header { flex-direction: column-reverse; align-items: flex-start; gap: 14px; }
    .header-left-side { width: 100%; }
    .btn-back { width: 100%; justify-content: center; }
    .btn-print, .btn-pdf-dl { width:100%; justify-content:center; }
    .releve-paper { padding:20px; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const sSemestre   = document.getElementById('selectSemestre');
    const iAnnee      = document.getElementById('inputAnnee');
    const sSession    = document.getElementById('selectSession');
    const iSignataire = document.getElementById('inputSignataire');
    const container   = document.getElementById('relevePaperContainer');
    const btnPdf      = document.getElementById('btnDownloadPdf');

    function updatePdfLink() {
        const base   = btnPdf.href.split('?')[0];
        const params = new URLSearchParams({
            semestre:   sSemestre.value,
            annee:      iAnnee.value,
            session:    sSession.value,
            signataire: iSignataire.value,
            download:   1
        });
        btnPdf.href = base + '?' + params.toString();
    }

    function updateReleve() {
        container.classList.add('loading-blur');
        updatePdfLink();

        const params = new URLSearchParams({
            semestre:   sSemestre.value,
            annee:      iAnnee.value,
            session:    sSession.value,
            signataire: iSignataire.value
        });

        fetch(window.location.pathname + '?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            container.innerHTML = data.html;
            container.classList.remove('loading-blur');
        })
        .catch(() => container.classList.remove('loading-blur'));
    }

    sSemestre.addEventListener('change', updateReleve);
    sSession.addEventListener('change',  updateReleve);

    let timer;
    [iAnnee, iSignataire].forEach(input => {
        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(updateReleve, 400);
        });
    });

    updatePdfLink();
});
</script>

@endsection
