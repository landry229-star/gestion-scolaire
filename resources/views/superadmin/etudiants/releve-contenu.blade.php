<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relevé de Notes — {{ $etudiant->nom }}</title>
    <style>

        /* =============================================
           RESET & BASE
        ============================================= */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            color: #0f172a;
            font-size: 8.5pt;
            line-height: 1.4;
            background: #ffffff;
            width: 100%;
        }

        /* =============================================
           FILIGRANE  (position:fixed pour DomPDF)
        ============================================= */
        .watermark {
            position: fixed;
            top: 42%;
            left: 50%;
            margin-left: -160px;
            margin-top: -160px;
            opacity: 0.04;
            z-index: -1;
        }
        .watermark img { width: 320px; }

        /* =============================================
           BANDEAU SUPÉRIEUR
        ============================================= */
        .top-banner {
            background: #0f172a;
            color: #ffffff;
            text-align: center;
            padding: 6px 10px;
            font-size: 7pt;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }
        .color-bar {
            height: 3px;
            background: #2563eb;
        }

        /* =============================================
           EN-TÊTE INSTITUTIONNEL
        ============================================= */
        .header-wrap {
            padding: 12px 18px 10px;
            border-bottom: 1.5px solid #e2e8f0;
            margin-bottom: 10px;
        }
        .header-table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: middle; padding: 0; }
        .td-left  { width: 42%; vertical-align: top; }
        .td-center { width: 16%; text-align: center; vertical-align: middle; }
        .td-right { width: 42%; text-align: right; vertical-align: top; }

        .inst-ministry {
            font-size: 6.5pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            line-height: 1.5;
        }
        .inst-univ {
            font-size: 8pt;
            font-weight: 700;
            color: #0f172a;
            margin-top: 4px;
        }
        .inst-detail {
            font-size: 7.5pt;
            color: #334155;
            margin-top: 2px;
            line-height: 1.5;
        }
        .divider-short {
            width: 40px;
            height: 2px;
            background: #2563eb;
            margin: 4px 0;
        }
        .header-logo img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid #e2e8f0;
        }
        .inst-country {
            font-size: 7.5pt;
            font-weight: 700;
            color: #1e293b;
        }
        .inst-motto {
            font-size: 7pt;
            color: #64748b;
            font-style: italic;
            margin-top: 2px;
        }

        /* =============================================
           TITRE DU DOCUMENT
        ============================================= */
        .doc-title-wrap {
            text-align: center;
            margin: 0 18px 10px;
        }
        .doc-title-inner {
            display: inline-block;
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
            padding: 6px 28px;
        }
        .doc-title {
            font-size: 12pt;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .doc-subtitle {
            font-size: 6.5pt;
            color: #64748b;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        /* =============================================
           BLOC INFORMATIONS ÉTUDIANT
        ============================================= */
        .info-section {
            margin: 0 18px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            overflow: hidden;
        }
        .info-section-title {
            background: #1e293b;
            color: #ffffff;
            font-size: 6.5pt;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 4px 10px;
        }
        .info-grid { width: 100%; border-collapse: collapse; }
        .info-grid td {
            padding: 5px 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 8pt;
        }
        .info-grid tr:last-child td { border-bottom: none; }
        .info-label {
            width: 16%;
            background: #f8fafc;
            color: #64748b;
            font-size: 6.8pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            white-space: nowrap;
        }
        .info-value { color: #0f172a; font-weight: 600; }
        .info-value.light { font-weight: 400; color: #334155; }

        /* =============================================
           TABLEAU DES NOTES
        ============================================= */
        .notes-section { margin: 0 18px 10px; }
        .notes-section-title {
            font-size: 6.5pt;
            font-weight: 700;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 4px;
        }
        .table-notes {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
        }
        .table-notes thead tr { background: #1e293b; }
        .table-notes thead th {
            color: #ffffff;
            padding: 7px 8px;
            font-size: 7pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border: none;
        }
        .table-notes tbody tr { border-bottom: 1px solid #e2e8f0; }
        .table-notes tbody tr:nth-child(even) { background: #f8fafc; }
        .table-notes tbody tr:last-child { border-bottom: none; }
        .table-notes tbody td {
            padding: 7px 8px;
            font-size: 8.2pt;
            color: #1e293b;
        }
        .table-notes tfoot tr { background: #1e293b; }
        .table-notes tfoot td {
            padding: 7px 8px;
            font-size: 7.8pt;
            font-weight: 700;
            color: #ffffff;
        }
        .text-center { text-align: center; }

        .code-ue {
            font-family: "Courier New", monospace;
            font-weight: 700;
            font-size: 7.5pt;
            color: #1e3a8a;
            background: #eff6ff;
            padding: 1px 5px;
            border-radius: 3px;
            border: 1px solid #bfdbfe;
        }
        .note-val { font-size: 9pt; font-weight: 700; }
        .note-high { color: #047857; }
        .note-mid  { color: #d97706; }
        .note-low  { color: #b91c1c; }
        .badge-valide  { color: #047857; font-weight: 700; font-size: 7.5pt; }
        .badge-ajourne { color: #b91c1c; font-weight: 700; font-size: 7.5pt; }
        .empty-row td {
            text-align: center;
            padding: 14px;
            color: #94a3b8;
            font-style: italic;
            font-size: 8pt;
        }

        /* =============================================
           DÉCISION DU JURY
        ============================================= */
        .decision-section { margin: 0 18px 10px; }
        .decision-box {
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            overflow: hidden;
        }
        .decision-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 5px 12px;
            font-size: 6.5pt;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #64748b;
            text-align: center;
        }
        .decision-body { padding: 8px 14px; }
        .decision-inner { width: 100%; border-collapse: collapse; }
        .decision-inner td { vertical-align: middle; padding: 0; }
        .decision-stats { width: 58%; vertical-align: middle; }
        .decision-verdict {
            width: 42%;
            text-align: center;
            border-left: 1px solid #e2e8f0;
            padding-left: 14px;
        }

        .stat-table { width: 100%; border-collapse: collapse; }
        .stat-table td { padding: 3px 6px; vertical-align: middle; }
        .stat-label {
            font-size: 6.8pt;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 40%;
            white-space: nowrap;
        }
        .stat-value { font-size: 8.5pt; font-weight: 700; color: #1e293b; }

        .mention-badge {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 3px;
            padding: 1px 8px;
            font-size: 7.5pt;
            font-weight: 700;
        }
        .decision-result {
            font-size: 17pt;
            font-weight: 700;
            letter-spacing: 2px;
        }
        .result-admis   { color: #047857; }
        .result-ajourne { color: #b91c1c; }
        .verdict-sub { font-size: 7pt; margin-top: 3px; }
        .verdict-sub.ok  { color: #047857; }
        .verdict-sub.ko  { color: #b91c1c; }

        /* =============================================
           PIED DE PAGE — SÉCURITÉ + SIGNATURE
        ============================================= */
        .footer-section {
            margin: 0 18px;
            border-top: 1.5px solid #e2e8f0;
            padding-top: 10px;
        }
        .footer-inner { width: 100%; border-collapse: collapse; }
        .footer-inner td { vertical-align: middle; padding: 0; }
        .td-security  { width: 55%; vertical-align: top; }
        .td-signature { width: 45%; text-align: center; vertical-align: top; }

        /* QR + sécurité */
        .security-wrap { width: 100%; border-collapse: collapse; }
        .security-wrap td { vertical-align: middle; padding: 0; }
        .td-qr { width: 75px; }
        .td-qr svg, .td-qr img { width: 70px; height: 70px; }
        .td-sec-info { padding-left: 10px; }
        .sec-title {
            font-size: 7pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .sec-line {
            font-size: 7.5pt;
            color: #334155;
            line-height: 1.7;
        }
        .sec-line b { color: #0f172a; }

        /* Signature */
        .sig-date {
            font-size: 7.5pt;
            color: #475569;
            margin-bottom: 3px;
        }
        .sig-role {
            font-size: 7pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #334155;
            margin-bottom: 5px;
        }
        .sig-tampon { height: 72px; text-align: center; margin: 0 auto 4px; }
        .sig-tampon img { max-height: 72px; opacity: 0.85; }
        .sig-line {
            width: 160px;
            border: none;
            border-top: 1px solid #334155;
            margin: 6px auto 4px;
            display: block;
        }
        .sig-name {
            font-size: 8.5pt;
            font-weight: 700;
            color: #0f172a;
        }

        /* =============================================
           BAS DE PAGE
        ============================================= */
        .page-footer {
            margin: 8px 18px 0;
            padding-top: 5px;
            border-top: 1px solid #f1f5f9;
            text-align: right;
            font-size: 6.5pt;
            color: #94a3b8;
        }

        /* =============================================
           PRINT — supprime toute marge navigateur
        ============================================= */
        @media print {
            @page {
                size: A4 portrait;
                margin: 0mm;
            }
            html, body {
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 0;
            }
            /* couleurs forcées */
            .table-notes thead tr,
            .table-notes tfoot tr,
            .top-banner,
            .info-section-title {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

    </style>
</head>
<body>

    <!-- FILIGRANE -->
    <div class="watermark">
        <img src="{{ asset('images/OIP.jpeg') }}" alt="UAC">
    </div>

    <!-- BANDEAU -->
    <div class="top-banner">
        Université d'Abomey-Calavi &nbsp;•&nbsp; Relevé Officiel &nbsp;•&nbsp; Système LMD
    </div>
    <div class="color-bar"></div>

    <!-- EN-TÊTE -->
    <div class="header-wrap">
        <table class="header-table">
            <tr>
                <td class="td-left">
                    <div class="inst-ministry">
                        Ministère de l'Enseignement Supérieur<br>et de la Recherche Scientifique
                    </div>
                    <div class="divider-short"></div>
                    <div class="inst-univ">Université d'Abomey-Calavi (UAC)</div>
                    <div class="inst-detail">
                        <strong>Établissement :</strong>
                        {{ $etudiant->filiere->departement->etablissement->nom_etablissement ?? 'IFRI' }}<br>
                        <strong>Département :</strong>
                        {{ $etudiant->filiere->departement->nom_departement ?? 'Génie Logiciel' }}
                    </div>
                </td>
                <td class="td-center">
                    <div class="header-logo">

                        <img src="{{ asset('images/OIP.jpeg') }}" alt="Logo UAC">
                    </div>
                </td>
                <td class="td-right">
                    <div class="inst-country">République du Bénin</div>
                    <div class="inst-motto">Fraternité &bull; Justice &bull; Travail</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- TITRE -->
    <div class="doc-title-wrap">
        <div class="doc-title-inner">
            <div class="doc-title">Relevé de Notes Académique</div>
            <div class="doc-subtitle">Document officiel — Confidentiel</div>
        </div>
    </div>

    <!-- INFOS ÉTUDIANT -->
    <div class="info-section">
        <div class="info-section-title">Informations de l'étudiant(e)</div>
        <table class="info-grid">
            <tr>
                <td class="info-label">Nom</td>
                <td class="info-value">{{ strtoupper($etudiant->nom) }}</td>
                <td class="info-label">Matricule</td>
                <td class="info-value">{{ $etudiant->matricule ?? $etudiant->id_etudiant }}</td>
            </tr>
            <tr>
                <td class="info-label">Prénoms</td>
                <td class="info-value light">{{ ucwords(strtolower($etudiant->prenom)) }}</td>
                <td class="info-label">Année acad.</td>
                <td class="info-value light">{{ $anneeAcademique }}</td>
            </tr>
            <tr>
                <td class="info-label">Grade</td>
                <td class="info-value light">Licence (LMD)</td>
                <td class="info-label">Session</td>
                <td class="info-value light">{{ $sessionActive }}</td>
            </tr>
            <tr>
                <td class="info-label">Filière</td>
                <td colspan="3" class="info-value">
                    {{ $etudiant->filiere->nom_filiere ?? 'Génie Logiciel' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- TABLEAU DES NOTES -->
    <div class="notes-section">
        <div class="notes-section-title">Détail des Unités d'Enseignement</div>
        <table class="table-notes">
            <thead>
                <tr>
                    <th style="width:13%;">Code UE</th>
                    <th style="text-align:left;">Matière / Unité d'Enseignement</th>
                    <th style="width:9%; text-align:center;">Crédit</th>
                    <th style="width:12%; text-align:center;">Note /20</th>
                    <th style="width:10%; text-align:center;">Points</th>
                    <th style="width:13%; text-align:center;">Résultat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notes as $note)
                    @php
                        $coeff  = $note->matiere->credit_matiere ?? 1;
                        $points = $note->valeur_note * $coeff;
                        $v      = $note->valeur_note;
                        $cls    = $v >= 14 ? 'note-high' : ($v >= 10 ? 'note-mid' : 'note-low');
                    @endphp
                    <tr>
                        <td class="text-center">
                            <span class="code-ue">{{ $note->matiere->code_matiere ?? 'N/A' }}</span>
                        </td>
                        <td>{{ $note->matiere->nom_matiere }}</td>
                        <td class="text-center">{{ $coeff }}</td>
                        <td class="text-center">
                            <span class="note-val {{ $cls }}">
                                {{ number_format($v, 2, ',', ' ') }}
                            </span>
                        </td>
                        <td class="text-center">{{ number_format($points, 2, ',', ' ') }}</td>
                        <td class="text-center">
                            @if($v >= 10)
                                <span class="badge-valide">&#10003; Validé</span>
                            @else
                                <span class="badge-ajourne">&#10007; Ajourné</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="6">Aucune note enregistrée pour cette session.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Crédits validés : {{ $creditsValides }} / {{ $totalCreditsTableau }}</td>
                    <td class="text-center">{{ $creditsValides }} cr.</td>
                    <td class="text-center">Moy. : {{ number_format($moyenneGenerale, 2, ',', ' ') }}/20</td>
                    <td colspan="2" class="text-center">Mention : {{ $mention }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- DÉCISION DU JURY -->
    <div class="decision-section">
        <div class="decision-box">
            <div class="decision-header">Décision du Jury d'Examen</div>
            <div class="decision-body">
                <table class="decision-inner">
                    <tr>
                        <td class="decision-stats">
                            <table class="stat-table">
                                <tr>
                                    <td class="stat-label">Moyenne générale</td>
                                    <td class="stat-value">
                                        {{ number_format($moyenneGenerale, 2, ',', ' ') }} / 20
                                    </td>
                                </tr>
                                <tr>
                                    <td class="stat-label">Crédits obtenus</td>
                                    <td class="stat-value">
                                        {{ $creditsValides }} / {{ $totalCreditsTableau }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="stat-label">Mention</td>
                                    <td class="stat-value">
                                        <span class="mention-badge">{{ $mention }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="decision-verdict">
                            @if($moyenneGenerale >= 10 && $notes->count() > 0)
                                <div class="decision-result result-admis">ADMIS(E)</div>
                                <div class="verdict-sub ok">&#10003; Toutes conditions remplies</div>
                            @else
                                <div class="decision-result result-ajourne">AJOURNÉ(E)</div>
                                <div class="verdict-sub ko">&#10007; Conditions non satisfaites</div>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- PIED DE PAGE -->
    <div class="footer-section">
        <table class="footer-inner">
            <tr>
                <!-- Sécurité -->
                <td class="td-security">
                    <table class="security-wrap">
                        <tr>
                            <td class="td-qr">
                                {!! $qrCode !!}
                            </td>
                            <td class="td-sec-info">
                                <div class="sec-title">&#128274; Document Authentifié</div>
                                <div class="sec-line">
                                    Code : <b>{{ $codeDocument }}</b><br>
                                    Vérification : <b>uac-hub.bj/verify</b><br>
                                    Généré le : <b>{{ date('d/m/Y') }} à {{ date('H:i') }}</b>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>

                <!-- Signature -->
                <td class="td-signature">
                    <div class="sig-date">Fait à Abomey-Calavi, le {{ date('d/m/Y') }}</div>
                    <div class="sig-role">Le Chef de la Scolarité Centrale</div>
                    <div class="sig-tampon">
                        <img src="{{ asset('images/er.png') }}" alt="Tampon">
                    </div>
                    <hr class="sig-line">
                    <div class="sig-name">{{ $signataire ?? 'Le Directeur' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- BAS DE PAGE -->
    <div class="page-footer">
        Université d'Abomey-Calavi &nbsp;&mdash;&nbsp; Document confidentiel &nbsp;&mdash;&nbsp; Page 1/1
    </div>

</body>
</html>
