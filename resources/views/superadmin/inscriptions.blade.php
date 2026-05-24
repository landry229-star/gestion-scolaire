{{-- resources/views/superadmin/inscriptions.blade.php --}}

@extends('layouts.superadmin')

@section('title', 'Gestion des inscriptions')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@600;700&display=swap" rel="stylesheet">

<div class="ins-root">

    {{-- ── HEADER ─────────────────────────────────────────────────────────── --}}
    <div class="ins-topbar">
        <div class="ins-topbar-left">
            <span class="ins-logo-chip">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
            </span>
            <div>
                <h1 class="ins-title">Inscriptions</h1>
                <p class="ins-sub">Validation & gestion des dossiers étudiants</p>
            </div>
        </div>
        <div class="ins-stats-row">
            <div class="ins-stat" data-filter="all">
                <span class="ins-stat-num" id="stat-total">0</span>
                <span class="ins-stat-label">Total</span>
            </div>
            <div class="ins-stat" data-filter="en_cours">
                <span class="ins-stat-num amber" id="stat-pending">0</span>
                <span class="ins-stat-label">En attente</span>
            </div>
            <div class="ins-stat" data-filter="valide">
                <span class="ins-stat-num green" id="stat-validated">0</span>
                <span class="ins-stat-label">Validés</span>
            </div>
            <div class="ins-stat" data-filter="rejete">
                <span class="ins-stat-num red" id="stat-rejected">0</span>
                <span class="ins-stat-label">Rejetés</span>
            </div>
        </div>
    </div>

    {{-- ── ALERTS ──────────────────────────────────────────────────────────── --}}
    @if(session('success'))
    <div class="ins-alert ins-alert-ok">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="ins-alert ins-alert-err">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- ── TOOLBAR ─────────────────────────────────────────────────────────── --}}
    <div class="ins-toolbar">
        <div class="ins-search-wrap">
            <svg class="ins-search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" id="search-input" class="ins-search" placeholder="Rechercher un étudiant…">
        </div>

        <div class="ins-filters">
            <button class="ins-filter-btn active" data-status="all">Tous</button>
            <button class="ins-filter-btn" data-status="en_cours">
                <span class="dot amber"></span>En attente
            </button>
            <button class="ins-filter-btn" data-status="valide">
                <span class="dot green"></span>Validés
            </button>
            <button class="ins-filter-btn" data-status="rejete">
                <span class="dot red"></span>Rejetés
            </button>
        </div>

        {{-- Filtre Département --}}
        <div class="ins-select-group">
            <svg class="ins-select-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <select id="dept-select" class="ins-select">
                <option value="">Tous les départements</option>
                @foreach($departements as $dept)
                    <option value="{{ $dept->id_departement }}">{{ $dept->nom_departement }}</option>
                @endforeach
            </select>
        </div>

        {{-- Filtre Filière — alimenté par AJAX --}}
        <div class="ins-select-group" id="filiere-group">
            <svg class="ins-select-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            <select id="filiere-select" class="ins-select" disabled>
                <option value="">Toutes les filières</option>
            </select>
            <span class="ins-select-loader" id="filiere-loader" style="display:none">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="spin"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
            </span>
        </div>
    </div>

    {{-- ── TABLE ───────────────────────────────────────────────────────────── --}}
    <div class="ins-card">
        <div class="ins-table-wrap">
            <table class="ins-table" id="main-table">
                <thead>
                    <tr>
                        <th class="sortable" data-col="0">#<span class="sort-icon">↕</span></th>
                        <th class="sortable" data-col="1">Étudiant<span class="sort-icon">↕</span></th>
                        <th>Email</th>
                        <th class="sortable" data-col="3">Filière<span class="sort-icon">↕</span></th>
                        <th class="sortable" data-col="4">Date<span class="sort-icon">↕</span></th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="table-body">

                @forelse($inscriptions as $inscription)
                @php
                    $statut = $inscription->statut_dossier ?? 'en_cours';

                    // ✅ Données passées à la modal — ajouter un champ ici si besoin
                    $modalData = [
                        'id'               => $inscription->id_inscription,
                        'statut'           => $statut,
                        'valider_url'      => route('superadmin.inscriptions.valider', $inscription->id_inscription),
                        'rejeter_url'      => route('superadmin.inscriptions.rejeter', $inscription->id_inscription),


                        // Champs personnels
                        'nom'              => $inscription->etudiant->nom ?? '---',
                        'prenom'           => $inscription->etudiant->prenom ?? '',
                        'fullname'         => trim(($inscription->etudiant->nom ?? '').' '.($inscription->etudiant->prenom ?? '')),
                        'email'            => $inscription->etudiant->email ?? '---',
                        'telephone'        => $inscription->etudiant->telephone ?? 'Non renseigné',
                        'naissance'        => $inscription->etudiant->date_naissance
                                                ? \Carbon\Carbon::parse($inscription->etudiant->date_naissance)->format('d/m/Y')
                                                : 'Non renseignée',
                        'lieu_naissance'   => $inscription->etudiant->lieu_naissance ?? 'Non renseigné',
                        'sexe'             => $inscription->etudiant->sexe ?? 'Non renseigné',
                        'adresse'          => $inscription->etudiant->adresse ?? 'Non renseignée',
                        'nationalite'      => $inscription->etudiant->nationalite ?? 'Non renseignée',
                        'groupe_sanguin'   => $inscription->etudiant->groupe_sanguin ?? 'Non renseigné',

                        // Champs académiques
                        'filiere'          => $inscription->filiere->nom_filiere ?? '---',
                        'niveau'           => $inscription->niveau ?? 'Non renseigné',
                        'entite'           => $inscription->entite ?? 'Non renseigné',
                        'annee_etude'      => $inscription->annee_etude ?? 'Non renseigné',
                        'regime'           => $inscription->regime ?? 'Non renseigné',
                        'statut_boursier'  => $inscription->statut_boursier ?? 'Non renseigné',
                        'num_table_bac'    => $inscription->num_table_bac ?? 'Non renseigné',
                        'serie_bac'        => $inscription->serie_bac ?? 'Non renseigné',
                        'annee_bac'        => $inscription->annee_bac ?? 'Non renseigné',
                        'date'             => $inscription->created_at->format('d/m/Y à H:i'),
                        'motif'        => $inscription->motif_rejet ?? '',
                        // Fichiers — chemins stockés relatifs au disk public
                        'photo'        => $inscription->etudiant->photo
                                            ? asset('storage/' . ltrim($inscription->etudiant->photo, '/'))
                                            : null,
                        'document_cip' => $inscription->etudiant->document_cip
                                            ? asset('storage/' . ltrim($inscription->etudiant->document_cip, '/'))
                                            : null,
                        'diplome_bac'  => $inscription->etudiant->diplome_bac
                                            ? asset('storage/' . ltrim($inscription->etudiant->diplome_bac, '/'))
                                            : null,
                        // ✅ Nouveau champ ? Ajoute-le ici avec sa clé
                    ];
                @endphp
                <tr
                    data-status="{{ $statut }}"
                    data-filiere="{{ $inscription->filiere->nom_filiere ?? '' }}"
                    data-name="{{ strtolower(($inscription->etudiant->nom ?? '').' '.($inscription->etudiant->prenom ?? '')) }}"
                    data-date="{{ $inscription->created_at->format('Y-m-d') }}"
                >
                    <td class="td-num">{{ $loop->iteration }}</td>

                    <td>
                        <div class="stu-cell">
                            <div class="stu-av" style="--hue:{{ abs(crc32($inscription->etudiant->nom ?? 'E')) % 360 }}deg">
                                {{ strtoupper(substr($inscription->etudiant->nom ?? 'E', 0, 1)) }}
                            </div>
                            <div>
                                <p class="stu-name">{{ $inscription->etudiant->nom ?? '---' }} {{ $inscription->etudiant->prenom ?? '' }}</p>
                                <p class="stu-meta">{{ $inscription->etudiant->telephone ?? 'Aucun téléphone' }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="td-email">{{ $inscription->etudiant->email ?? '---' }}</td>

                    <td>
                        <span class="chip chip-blue">{{ $inscription->filiere->nom_filiere ?? '---' }}</span>
                    </td>

                    <td class="td-date">{{ $inscription->created_at->format('d/m/Y') }}</td>

                    <td>
                        @if($statut === 'valide')
                            <span class="chip chip-green">Validé</span>
                        @elseif($statut === 'rejete')
                            <span class="chip chip-red">Rejeté</span>
                        @else
                            <span class="chip chip-amber">En attente</span>
                        @endif
                    </td>

                    <td>
                        <button
                            type="button"
                            class="btn-view"
                            onclick="openModal({{ json_encode($modalData) }})"
                        >
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Voir le dossier
                        </button>
                    </td>
                </tr>
                @empty
                <tr id="empty-row">
                    <td colspan="7">
                        <div class="ins-empty">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p>Aucun dossier trouvé</p>
                        </div>
                    </td>
                </tr>
                @endforelse

                </tbody>
            </table>
        </div>

        <div class="ins-table-footer">
            <span id="result-count" class="ins-count-label"></span>
        </div>
    </div>

</div>{{-- /ins-root --}}


{{-- ═══════════════════════════════ MODAL ═════════════════════════════════ --}}
<div id="modal-wrap" class="modal-wrap" role="dialog" aria-modal="true" aria-labelledby="modal-student-name">
    <div class="modal-panel" id="modal-panel">

        {{-- Header --}}
        <div class="modal-head">
            <div class="modal-head-inner">
                <div class="modal-av" id="modal-av">E</div>
                <div>
                    <p class="modal-student-nm" id="modal-student-name">---</p>
                    <span class="modal-status-chip" id="modal-status-chip">En attente</span>
                </div>
            </div>
            <button class="modal-x" id="modal-x" aria-label="Fermer">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="modal-body">

            {{-- ✅ Section personnelle — générée dynamiquement via data-key --}}
            <p class="modal-section-title">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Informations personnelles
            </p>
            <div class="modal-grid" id="modal-grid-personnel">
                {{--
                    ✅ Pour ajouter un champ perso dans la modal :
                    1. Ajouter la clé dans $modalData du @forelse ci-dessus
                    2. Ajouter une ligne ici avec le même data-key
                --}}
                <div class="modal-field"><span class="mf-label">Nom complet</span><span class="mf-val" data-key="fullname">---</span></div>
                <div class="modal-field"><span class="mf-label">Email</span><span class="mf-val" data-key="email">---</span></div>
                <div class="modal-field"><span class="mf-label">Téléphone</span><span class="mf-val" data-key="telephone">---</span></div>
                <div class="modal-field"><span class="mf-label">Date de naissance</span><span class="mf-val" data-key="naissance">---</span></div>
                <div class="modal-field"><span class="mf-label">Lieu de naissance</span><span class="mf-val" data-key="lieu_naissance">---</span></div>
                <div class="modal-field"><span class="mf-label">Sexe</span><span class="mf-val" data-key="sexe">---</span></div>
                <div class="modal-field"><span class="mf-label">Adresse</span><span class="mf-val" data-key="adresse">---</span></div>
                <div class="modal-field"><span class="mf-label">Nationalité</span><span class="mf-val" data-key="nationalite">---</span></div>
                <div class="modal-field"><span class="mf-label">Groupe sanguin</span><span class="mf-val" data-key="groupe_sanguin">---</span></div>
            </div>

            {{-- ✅ Section académique — générée dynamiquement via data-key --}}
            <p class="modal-section-title" style="margin-top:20px">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                Informations académiques
            </p>
            <div class="modal-grid" id="modal-grid-academique">
                {{--
                    ✅ Pour ajouter un champ académique dans la modal :
                    1. Ajouter la clé dans $modalData du @forelse ci-dessus
                    2. Ajouter une ligne ici avec le même data-key
                --}}
                <div class="modal-field"><span class="mf-label">Filière</span><span class="mf-val" data-key="filiere">---</span></div>
                <div class="modal-field"><span class="mf-label">Niveau</span><span class="mf-val" data-key="niveau">---</span></div>
                <div class="modal-field"><span class="mf-label">Entité</span><span class="mf-val" data-key="entite">---</span></div>
                <div class="modal-field"><span class="mf-label">Année d'étude</span><span class="mf-val" data-key="annee_etude">---</span></div>
                <div class="modal-field"><span class="mf-label">Régime</span><span class="mf-val" data-key="regime">---</span></div>
                <div class="modal-field"><span class="mf-label">Prise en charge</span><span class="mf-val" data-key="statut_boursier">---</span></div>
                 <div class="modal-field"><span class="mf-label">Groupe sanguin</span><span class="mf-val" data-key="groupe_sanguin">---</span></div>
                <div class="modal-field"><span class="mf-label">N° Table BAC</span><span class="mf-val" data-key="num_table_bac">---</span></div>
                <div class="modal-field"><span class="mf-label">Série BAC</span><span class="mf-val" data-key="serie_bac">---</span></div>
                <div class="modal-field"><span class="mf-label">Année BAC</span><span class="mf-val" data-key="annee_bac">---</span></div>
                <div class="modal-field"><span class="mf-label">Soumis le</span><span class="mf-val" data-key="date">---</span></div>
            </div>

            {{-- Section Documents joints --}}
            <p class="modal-section-title" style="margin-top:20px">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Documents joints
            </p>
            <div class="modal-grid" id="modal-grid-documents">
                <div class="modal-field">
                    <span class="mf-label">Photo</span>
                    <div id="doc-photo" style="margin-top:6px;"></div>
                </div>
                <div class="modal-field">
                    <span class="mf-label">Acte CIP / CNIE</span>
                    <div id="doc-cip" style="margin-top:6px;"></div>
                </div>
                <div class="modal-field">
                    <span class="mf-label">Attestation BAC</span>
                    <div id="doc-bac" style="margin-top:6px;"></div>
                </div>
            </div>

            {{-- Motif rejet (lecture seule) --}}
            <div id="motif-display" class="motif-box" style="display:none">
                <p class="motif-title">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Motif du rejet
                </p>
                <p id="motif-text" class="motif-text"></p>
            </div>

            {{-- Zone saisie motif rejet --}}
            <div id="reject-zone" style="display:none; margin-top:16px">
                <p class="modal-section-title" style="color:#b91c1c">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    Motif du rejet
                </p>
                <textarea id="motif-input" rows="3" placeholder="Expliquez le motif du rejet…" style="width:100%;border:1px solid #fca5a5;border-radius:8px;padding:10px;font-size:13px;resize:none;font-family:inherit;background:#fff5f5;box-sizing:border-box;"></textarea>
            </div>

        </div>

        {{-- Footer --}}
        <div class="modal-foot">
            <button class="mf-btn mf-neutral" id="btn-close">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Fermer
            </button>
            <button class="mf-btn mf-danger" id="btn-show-rej" style="display:none" onclick="showRejectZone()">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                Rejeter
            </button>
            <button class="mf-btn mf-danger" id="btn-confirm-rej" style="display:none" onclick="submitRejet()">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Confirmer le rejet
            </button>
            <button class="mf-btn mf-success" id="btn-valider" style="display:none" onclick="submitValidation()">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                Valider l'inscription
            </button>

        </div>

        {{-- Forms cachés --}}
        <form id="form-val"  method="POST" style="display:none">@csrf</form>
        <form id="form-rej"  method="POST" style="display:none">@csrf <input type="hidden" name="motif_rejet" id="hidden-motif"></form>
        <form id="form-rein" method="POST" style="display:none">@csrf</form>

    </div>
</div>
{{-- ══════════════════════════════════════════════════════════════════════ --}}


<style>
/* ── Reset & base ─────────────────────────────────────────────── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
.ins-root{font-family:'DM Sans',sans-serif;color:#0f172a;padding:0 0 40px}

/* ── Top bar ──────────────────────────────────────────────────── */
.ins-topbar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px}
.ins-topbar-left{display:flex;align-items:center;gap:14px}
.ins-logo-chip{width:42px;height:42px;border-radius:12px;background:#1d4ed8;color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ins-title{font-family:'Syne',sans-serif;font-size:24px;font-weight:700;color:#0f172a;line-height:1.2}
.ins-sub{font-size:13px;color:#64748b;margin-top:2px}

/* stats row */
.ins-stats-row{display:flex;gap:6px;flex-wrap:wrap}
.ins-stat{display:flex;flex-direction:column;align-items:center;background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:10px 18px;cursor:pointer;transition:.15s}
.ins-stat:hover{border-color:#93c5fd}
.ins-stat.active{border-color:#3b82f6;background:#eff6ff}
.ins-stat-num{font-family:'Syne',sans-serif;font-size:22px;font-weight:700;color:#0f172a}
.ins-stat-num.amber{color:#d97706}
.ins-stat-num.green{color:#16a34a}
.ins-stat-num.red{color:#dc2626}
.ins-stat-label{font-size:11px;color:#94a3b8;margin-top:2px;text-transform:uppercase;letter-spacing:.04em}

/* ── Alerts ───────────────────────────────────────────────────── */
.ins-alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:16px}
.ins-alert-ok{background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0}
.ins-alert-err{background:#fef2f2;color:#dc2626;border:1px solid #fecaca}

/* ── Toolbar ─────────────────────────────────────────────────── */
.ins-toolbar{display:flex;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:16px}
.ins-search-wrap{position:relative;flex:1;min-width:200px}
.ins-search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;pointer-events:none}
.ins-search{width:100%;padding:9px 12px 9px 34px;border:1px solid #e2e8f0;border-radius:9px;font-size:13px;font-family:'DM Sans',sans-serif;outline:none;transition:.15s}
.ins-search:focus{border-color:#3b82f6;box-shadow:0 0 0 3px #dbeafe}
.ins-filters{display:flex;gap:4px;flex-wrap:wrap}
.ins-filter-btn{padding:8px 14px;border:1px solid #e2e8f0;border-radius:8px;background:#fff;font-size:13px;font-family:'DM Sans',sans-serif;cursor:pointer;display:flex;align-items:center;gap:6px;transition:.15s;color:#475569}
.ins-filter-btn:hover{background:#f8fafc;border-color:#cbd5e1}
.ins-filter-btn.active{background:#1d4ed8;color:#fff;border-color:#1d4ed8}
.dot{width:7px;height:7px;border-radius:50%;flex-shrink:0}
.dot.amber{background:#f59e0b}
.dot.green{background:#22c55e}
.dot.red{background:#ef4444}

/* Select group */
.ins-select-group{position:relative;display:flex;align-items:center}
.ins-select-icon{position:absolute;left:11px;color:#94a3b8;pointer-events:none;z-index:1}
.ins-select{padding:9px 12px 9px 30px;border:1px solid #e2e8f0;border-radius:9px;font-size:13px;font-family:'DM Sans',sans-serif;outline:none;background:#fff;cursor:pointer;color:#475569;transition:.15s;appearance:none;min-width:170px}
.ins-select:focus{border-color:#3b82f6;box-shadow:0 0 0 3px #dbeafe}
.ins-select:disabled{opacity:.55;cursor:not-allowed;background:#f8fafc}
.ins-select-loader{position:absolute;right:10px;top:50%;transform:translateY(-50%);color:#3b82f6}
@keyframes spin{to{transform:rotate(360deg)}}
.spin{animation:spin .7s linear infinite}

/* ── Card / table ─────────────────────────────────────────────── */
.ins-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden}
.ins-table-wrap{overflow-x:auto}
.ins-table{width:100%;border-collapse:collapse;font-size:13.5px}
.ins-table thead{background:#f8fafc}
.ins-table th{padding:13px 16px;text-align:left;font-size:11.5px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;user-select:none}
.ins-table th.sortable{cursor:pointer}
.ins-table th.sortable:hover{color:#1d4ed8}
.sort-icon{margin-left:4px;opacity:.4;font-style:normal}
.th-sorted .sort-icon{opacity:1;color:#1d4ed8}
.ins-table td{padding:14px 16px;border-top:1px solid #f1f5f9;vertical-align:middle}
.ins-table tbody tr{transition:background .12s}
.ins-table tbody tr:hover{background:#f8fafc}
.ins-table tbody tr.hidden-row{display:none}

/* student cell */
.stu-cell{display:flex;align-items:center;gap:10px}
.stu-av{width:36px;height:36px;border-radius:50%;background:oklch(60% 0.18 var(--hue,220deg));color:#fff;font-weight:600;font-size:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.stu-name{font-weight:500;font-size:13.5px;line-height:1.3}
.stu-meta{font-size:11.5px;color:#94a3b8;margin-top:1px}
.td-email{color:#475569;font-size:13px}
.td-date{color:#64748b;white-space:nowrap}
.td-num{color:#94a3b8;font-size:12px;font-weight:600}

/* chips */
.chip{display:inline-flex;align-items:center;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;white-space:nowrap}
.chip-blue{background:#dbeafe;color:#1e40af}
.chip-amber{background:#fef3c7;color:#92400e}
.chip-green{background:#dcfce7;color:#166534}
.chip-red{background:#fee2e2;color:#991b1b}

/* view button */
.btn-view{display:inline-flex;align-items:center;gap:6px;padding:7px 13px;border:1px solid #e2e8f0;border-radius:8px;background:#fff;font-size:12.5px;font-weight:500;cursor:pointer;color:#334155;transition:.15s;font-family:'DM Sans',sans-serif}
.btn-view:hover{background:#1d4ed8;color:#fff;border-color:#1d4ed8}

/* table footer */
.ins-table-footer{padding:12px 16px;border-top:1px solid #f1f5f9;background:#fafafa}
.ins-count-label{font-size:12px;color:#94a3b8}

/* empty */
.ins-empty{padding:60px 20px;text-align:center;color:#94a3b8}
.ins-empty svg{display:block;margin:0 auto 12px;opacity:.4}
.ins-empty p{font-size:14px}

/* ── Modal ────────────────────────────────────────────────────── */
.modal-wrap{display:none;position:fixed;inset:0;background:rgba(15,23,42,.5);backdrop-filter:blur(3px);z-index:9999;align-items:center;justify-content:center;padding:20px}
.modal-wrap.open{display:flex}
.modal-panel{background:#fff;border-radius:18px;width:100%;max-width:680px;max-height:90vh;overflow-y:auto;box-shadow:0 32px 64px rgba(0,0,0,.18);display:flex;flex-direction:column;animation:panelUp .22s cubic-bezier(.16,1,.3,1)}
@keyframes panelUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:none}}

.modal-head{padding:20px 22px;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(135deg,#0f2557 0%,#1d4ed8 100%);border-radius:18px 18px 0 0;position:relative;overflow:hidden}
.modal-head::after{content:'';position:absolute;top:-40px;right:-40px;width:140px;height:140px;border-radius:50%;background:rgba(255,255,255,.05)}
.modal-head-inner{display:flex;align-items:center;gap:14px;position:relative}
.modal-av{width:52px;height:52px;border-radius:50%;background:rgba(255,255,255,.2);color:#fff;font-family:'Syne',sans-serif;font-size:20px;font-weight:700;display:flex;align-items:center;justify-content:center;border:2px solid rgba(255,255,255,.3);flex-shrink:0}
.modal-student-nm{font-family:'Syne',sans-serif;font-size:18px;font-weight:700;color:#fff;line-height:1.2}
.modal-status-chip{display:inline-block;margin-top:5px;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:600;background:rgba(255,255,255,.18);color:#fff;border:1px solid rgba(255,255,255,.25)}
.modal-status-chip.chip-green{background:rgba(134,239,172,.25);border-color:rgba(134,239,172,.4)}
.modal-status-chip.chip-red{background:rgba(252,165,165,.25);border-color:rgba(252,165,165,.4)}
.modal-x{background:rgba(255,255,255,.15);border:none;width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#fff;position:relative;transition:.15s}
.modal-x:hover{background:rgba(255,255,255,.28)}

.modal-body{padding:22px;flex:1}
.modal-section-title{display:flex;align-items:center;gap:7px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#64748b;margin-bottom:12px}
.modal-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(185px,1fr));gap:10px}
.modal-field{background:#f8fafc;border:1px solid #e2e8f0;border-radius:9px;padding:11px 13px}
.mf-label{display:block;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8;margin-bottom:3px}
.mf-val{display:block;font-size:13.5px;font-weight:500;color:#0f172a;word-break:break-all}

.motif-box{margin-top:16px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:14px}
.motif-title{display:flex;align-items:center;gap:7px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#dc2626;margin-bottom:8px}
.motif-text{font-size:13.5px;color:#7f1d1d;line-height:1.6}

.modal-foot{padding:14px 22px;border-top:1px solid #f1f5f9;display:flex;justify-content:flex-end;gap:8px;flex-wrap:wrap}
.mf-btn{display:inline-flex;align-items:center;gap:7px;padding:10px 16px;border-radius:9px;border:none;font-size:13px;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:.15s}
.mf-neutral{background:#f1f5f9;color:#475569}
.mf-neutral:hover{background:#e2e8f0}
.mf-success{background:#16a34a;color:#fff}
.mf-success:hover{background:#15803d}
.mf-danger{background:#dc2626;color:#fff}
.mf-danger:hover{background:#b91c1c}
.mf-reinscr{background:#7c3aed;color:#fff}
.mf-reinscr:hover{background:#6d28d9}
</style>


<script>
/* ════════════════════════════════════════════════════════════════════
   GLOBALS
════════════════════════════════════════════════════════════════════ */
let _valUrl = '', _rejUrl = '', _currentStatut = '';
let _sortCol = -1, _sortDir = 1;
let _activeStatus = 'all';

const rows = () => [...document.querySelectorAll('#table-body tr[data-status]')];
const $    = id  => document.getElementById(id);

/* ════════════════════════════════════════════════════════════════════
   REINDEX — ✅ Fonction centralisée appelée partout
════════════════════════════════════════════════════════════════════ */
function reindexRows() {
    let n = 1;
    rows().forEach(r => {
        if (!r.classList.contains('hidden-row')) {
            r.cells[0].textContent = n++;
        }
    });
}

/* ════════════════════════════════════════════════════════════════════
   STATS — ✅ Corrigé avec 'en_cours'
════════════════════════════════════════════════════════════════════ */
function computeStats() {
    const all = rows();
    $('stat-total').textContent     = all.length;
    $('stat-pending').textContent   = all.filter(r => r.dataset.status === 'en_cours').length;
    $('stat-validated').textContent = all.filter(r => r.dataset.status === 'valide').length;
    $('stat-rejected').textContent  = all.filter(r => r.dataset.status === 'rejete').length;
}

/* ════════════════════════════════════════════════════════════════════
   FILTRAGE
════════════════════════════════════════════════════════════════════ */
function applyFilters() {
    const q   = $('search-input').value.toLowerCase().trim();
    const fil = $('filiere-select').value;
    let vis   = 0;

    rows().forEach(r => {
        const matchStatus = _activeStatus === 'all' || r.dataset.status === _activeStatus;
        const matchFil    = !fil || r.dataset.filiere === fil;
        const matchSearch = !q  || r.dataset.name.includes(q);
        const show        = matchStatus && matchFil && matchSearch;
        r.classList.toggle('hidden-row', !show);
        if (show) vis++;
    });

    const emptyEl = $('empty-row');
    if (emptyEl) emptyEl.style.display = vis === 0 ? '' : 'none';

    reindexRows(); // ✅ Renumérotation après chaque filtre

    $('result-count').textContent = vis + ' dossier' + (vis > 1 ? 's' : '') + ' affiché' + (vis > 1 ? 's' : '');
}

/* Boutons filtre statut */
document.querySelectorAll('.ins-filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.ins-filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        _activeStatus = btn.dataset.status;
        applyFilters();
    });
});

/* Chips stats cliquables */
document.querySelectorAll('.ins-stat').forEach(chip => {
    chip.addEventListener('click', () => {
        document.querySelectorAll('.ins-stat').forEach(c => c.classList.remove('active'));
        chip.classList.add('active');
        _activeStatus = chip.dataset.filter;
        document.querySelectorAll('.ins-filter-btn').forEach(b => {
            b.classList.toggle('active', b.dataset.status === _activeStatus);
        });
        applyFilters();
    });
});

$('search-input').addEventListener('input', applyFilters);
$('filiere-select').addEventListener('change', applyFilters);

/* ════════════════════════════════════════════════════════════════════
   AJAX : Département → Filières
════════════════════════════════════════════════════════════════════ */
$('dept-select').addEventListener('change', function () {
    const idDept = this.value;
    const selFil = $('filiere-select');
    const loader = $('filiere-loader');

    selFil.innerHTML = '<option value="">Toutes les filières</option>';
    selFil.disabled  = true;
    applyFilters();

    if (!idDept) return;

    loader.style.display = 'inline-flex';
    selFil.innerHTML     = '<option value="">Chargement…</option>';

    const baseUrl   = "{{ route('superadmin.getFilieres', '__ID__') }}";
    const targetUrl = baseUrl.replace('__ID__', idDept);

    fetch(targetUrl)
        .then(r => { if (!r.ok) throw new Error('Réponse invalide'); return r.json(); })
        .then(data => {
            selFil.innerHTML = '<option value="">Toutes les filières</option>';
            if (data.length === 0) {
                selFil.innerHTML = '<option value="">Aucune filière trouvée</option>';
            } else {
                data.forEach(f => {
                    const opt       = document.createElement('option');
                    opt.value       = f.nom_filiere;
                    opt.textContent = f.nom_filiere;
                    selFil.appendChild(opt);
                });
                selFil.disabled = false;
            }
        })
        .catch(err => {
            console.error(err);
            selFil.innerHTML = '<option value="">Erreur de chargement</option>';
        })
        .finally(() => {
            loader.style.display = 'none';
            applyFilters();
        });
});

/* ════════════════════════════════════════════════════════════════════
   SORTING — ✅ Utilise reindexRows()
════════════════════════════════════════════════════════════════════ */
document.querySelectorAll('.ins-table th.sortable').forEach(th => {
    th.addEventListener('click', () => {
        const col = +th.dataset.col;
        if (_sortCol === col) _sortDir *= -1; else { _sortCol = col; _sortDir = 1; }

        document.querySelectorAll('.ins-table th').forEach(h => h.classList.remove('th-sorted'));
        th.classList.add('th-sorted');

        const tbody   = document.getElementById('table-body');
        const allRows = [...tbody.querySelectorAll('tr[data-status]')];

        allRows.sort((a, b) => {
            const av = a.cells[col]?.textContent.trim() || '';
            const bv = b.cells[col]?.textContent.trim() || '';
            if (col === 4) {
                const da = a.dataset.date, db = b.dataset.date;
                return _sortDir * (da < db ? -1 : da > db ? 1 : 0);
            }
            return _sortDir * av.localeCompare(bv, 'fr');
        });

        allRows.forEach(r => tbody.appendChild(r));
        reindexRows(); // ✅ Renumérotation après tri
    });
});

/* ════════════════════════════════════════════════════════════════════
   RENDER DOC — ✅ Définie en dehors de openModal
════════════════════════════════════════════════════════════════════ */
function renderDoc(containerId, url, isPhoto) {
    const el = $(containerId);
    if (!el) return;
    if (!url) {
        el.innerHTML = '<span style="font-size:12px;color:#94a3b8;">Non fourni</span>';
        return;
    }
    if (isPhoto) {
        el.innerHTML = `<img src="${url}" style="width:70px;height:70px;border-radius:8px;object-fit:cover;border:1px solid #e2e8f0;">`;
    } else {
        const ext = url.split('.').pop().toLowerCase();
        if (ext === 'pdf') {
            el.innerHTML = `<a href="${url}" target="_blank" style="display:inline-flex;align-items:center;gap:6px;padding:6px 10px;background:#eff6ff;color:#1d4ed8;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg>
                Voir le PDF
            </a>`;
        } else {
            el.innerHTML = `<a href="${url}" target="_blank"><img src="${url}" style="width:100%;max-height:80px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;"></a>`;
        }
    }
}

/* ════════════════════════════════════════════════════════════════════
   MODAL OPEN — ✅ Remplissage dynamique via data-key
════════════════════════════════════════════════════════════════════ */
function openModal(d) {
    _valUrl        = d.valider_url;
    _rejUrl        = d.rejeter_url;
    _currentStatut = d.statut;

    // Header
    $('modal-av').textContent           = (d.nom.charAt(0) || 'E').toUpperCase();
    $('modal-student-name').textContent = d.fullname;

    // Chip statut
    const chip = $('modal-status-chip');
    chip.className = 'modal-status-chip';
    if (d.statut === 'valide')      { chip.textContent = '✓ Validé';      chip.classList.add('chip-green'); }
    else if (d.statut === 'rejete') { chip.textContent = '✕ Rejeté';      chip.classList.add('chip-red'); }
    else                             { chip.textContent = '⏳ En attente'; }

    // ✅ Remplissage automatique de TOUS les champs texte via data-key
    document.querySelectorAll('.mf-val[data-key]').forEach(el => {
        const key = el.dataset.key;
        el.textContent = (d[key] !== undefined && d[key] !== null && d[key] !== '')
            ? d[key]
            : '---';
    });

    // Motif rejet (lecture)
    const motifBox = $('motif-display');
    if (d.statut === 'rejete' && d.motif) {
        $('motif-text').textContent = d.motif;
        motifBox.style.display = 'block';
    } else {
        motifBox.style.display = 'none';
    }

    // ✅ Documents joints
    renderDoc('doc-photo', d.photo,        true);
    renderDoc('doc-cip',   d.document_cip, false);
    renderDoc('doc-bac',   d.diplome_bac,  false);

    resetRejectZone();

    // Boutons selon statut
    $('btn-show-rej').style.display    = d.statut === 'en_cours' ? '' : 'none';
    $('btn-valider').style.display     = d.statut === 'en_cours' ? '' : 'none';
    $('btn-confirm-rej').style.display = 'none';

    $('modal-wrap').classList.add('open');
    document.body.style.overflow = 'hidden';
}

/* ════════════════════════════════════════════════════════════════════
   MODAL CLOSE
════════════════════════════════════════════════════════════════════ */
function closeModal() {
    $('modal-wrap').classList.remove('open');
    document.body.style.overflow = '';
    resetRejectZone();
}

$('modal-x').addEventListener('click', closeModal);
$('btn-close').addEventListener('click', closeModal);
$('modal-wrap').addEventListener('click', e => { if (e.target === $('modal-wrap')) closeModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

/* ════════════════════════════════════════════════════════════════════
   REJECT ZONE
════════════════════════════════════════════════════════════════════ */
function showRejectZone() {
    $('reject-zone').style.display     = 'block';
    $('btn-show-rej').style.display    = 'none';
    $('btn-confirm-rej').style.display = '';
    $('btn-valider').style.display     = 'none';
    $('motif-input').focus();
}

function resetRejectZone() {
    $('reject-zone').style.display     = 'none';
    $('btn-confirm-rej').style.display = 'none';
    if (_currentStatut === 'en_cours') {
        $('btn-show-rej').style.display = '';
        $('btn-valider').style.display  = '';
    }
    if ($('motif-input')) $('motif-input').value = '';
}

/* ════════════════════════════════════════════════════════════════════
   SUBMIT ACTIONS
════════════════════════════════════════════════════════════════════ */
function submitValidation() {
    if (!confirm('Confirmer la validation de ce dossier ?')) return;
    const f = $('form-val');
    f.action = _valUrl;
    f.submit();
}

function submitRejet() {
    const motif = $('motif-input').value.trim();
    if (!motif) { alert('Veuillez saisir un motif de rejet.'); $('motif-input').focus(); return; }
    if (!confirm('Confirmer le rejet de ce dossier ?')) return;
    $('hidden-motif').value = motif;
    const f = $('form-rej');
    f.action = _rejUrl;
    f.submit();
}

function submitReinscription() {
    if (!confirm('Créer une nouvelle inscription pour cet étudiant ?')) return;
    const f = $('form-rein');
    f.action = _reinUrl;
    f.submit();
}

/* ════════════════════════════════════════════════════════════════════
   INIT
════════════════════════════════════════════════════════════════════ */
computeStats();
applyFilters();
</script>

@endsection
