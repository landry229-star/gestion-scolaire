<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche de Préinscription — {{ $etudiant->matricule ?? 'Provisoire' }}</title>
    <style>
        /* ============================================================
           BASE — 100% Compatible DomPDF
        ============================================================ */
        @page { margin: 22px 38px; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111;
            font-size: 11px;
            line-height: 1.4;
            background: #fff;
        }

        /* ============================================================
           EN-TÊTE
        ============================================================ */
        .header-tbl { width: 100%; border-collapse: collapse; margin-bottom: 10px; }

        .h-left {
            width: 50%;
            vertical-align: top;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.5;
            text-align: left;
        }

        .h-right {
            width: 50%;
            vertical-align: top;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.5;
            text-align: right;
        }

        .h-inst   { font-size: 11px; }
        .h-sep    { font-weight: normal; font-size: 9px; display: block; }
        .h-devise { font-weight: normal; font-style: italic; text-transform: none; font-size: 9px; }

        .header-border {
            border-bottom: 2.5px double #1a5c36;
            margin-bottom: 8px;
            padding-bottom: 8px;
        }

        /* ============================================================
           TITRE CENTRAL
        ============================================================ */
        .doc-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #1a5c36;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }

        .bank-info {
            text-align: center;
            font-size: 9.5px;
            font-weight: bold;
            color: #333;
            margin-bottom: 4px;
        }

        .tech-code {
            text-align: center;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            font-weight: bold;
            color: #cc0000;
            letter-spacing: 1.5px;
            margin-bottom: 4px;
        }

        .academic-year {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .title-hr {
            width: 70%;
            border: none;
            border-top: 1px solid #ccc;
            margin: 6px auto 12px auto;
        }

        /* ============================================================
           SECTION TITRES
        ============================================================ */
        .section-title {
            font-size: 11.5px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            color: #000;
            letter-spacing: 0.5px;
            margin: 12px 0 5px 0;
        }

        /* ============================================================
           BLOC IDENTITÉ (table)
        ============================================================ */
        .identity-tbl {
            width: 100%;
            border-collapse: collapse;
            background-color: #dce8fb;
            margin-bottom: 12px;
            border: 1px solid #b0c8f0;
        }

        .identity-tbl td { padding: 0; vertical-align: top; }

        /* Données à gauche */
        .id-data-cell { vertical-align: top; padding: 0; }

        /* QR à droite */
        .qr-cell {
            width: 115px;
            text-align: center;
            vertical-align: middle;
            padding: 10px;
            background-color: #dce8fb;
            border-left: 1px solid #b0c8f0;
        }

        .qr-inner {
            width: 90px;
            height: 90px;
            background: #fff;
            border: 1px solid #ccc;
            margin: 0 auto;
            padding: 2px;
        }

        /* DomPDF applique parfaitement la taille fixe sur l'image PNG */
        .qr-inner img {
            width: 90px;
            height: 90px;
            display: block;
        }

        .qr-label {
            font-size: 8px;
            color: #555;
            text-align: center;
            margin-top: 4px;
            font-weight: bold;
        }

        /* ============================================================
           TABLEAUX DE DONNÉES
        ============================================================ */
        .data-tbl { width: 100%; border-collapse: collapse; }

        .data-tbl td {
            padding: 5px 10px;
            border-bottom: 1px solid #c5d8f8;
            vertical-align: middle;
        }

        .data-tbl tr:last-child td { border-bottom: none; }

        .lbl { font-weight: normal; color: #333; width: 32%; font-size: 10.5px; }
        .val { color: #000; font-weight: bold; font-size: 11px; }
        .val-red { color: #cc0000; font-weight: bold; font-size: 11px; }
        .val-mono {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            letter-spacing: 1.5px;
            color: #1a1a2e;
            font-weight: bold;
        }

        /* ============================================================
           BLOC INSCRIPTION (fond gris)
        ============================================================ */
        .insc-block {
            background-color: #f0f0f0;
            border: 1px solid #d0d0d0;
            margin-bottom: 12px;
        }

        .insc-block .data-tbl td { border-bottom: 1px solid #ddd; }

        /* ============================================================
           ALERTE PAIEMENT
        ============================================================ */
        .alert-box {
            border-left: 3px solid #cc0000;
            background: #fff5f5;
            padding: 6px 10px;
            margin: 10px 0;
            font-size: 10px;
            line-height: 1.4;
            text-align: justify;
            color: #333;
        }

        .alert-box strong { color: #cc0000; text-transform: uppercase; }

        /* ============================================================
           LAYOUT BAS DE PAGE (2 colonnes)
        ============================================================ */
        .bottom-tbl { width: 100%; border-collapse: collapse; margin-top: 8px; }

        .bottom-left  { width: 52%; vertical-align: top; padding-right: 12px; }
        .bottom-right { width: 48%; vertical-align: top; padding-left: 12px; border-left: 1px dashed #bbb; }

        .charte-title { font-weight: bold; font-size: 10.5px; margin-bottom: 4px; }

        .charte-item {
            font-size: 10px;
            font-weight: bold;
            padding: 2px 0 2px 10px;
            border-left: 3px solid #1a5c36;
            margin-bottom: 4px;
            color: #111;
        }

        .engagement-text {
            font-size: 9.5px;
            text-align: justify;
            margin-top: 6px;
            color: #444;
        }

        .biblio-title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            color: #1a5c36;
            margin: 10px 0 2px 0;
        }

        .biblio-text {
            font-size: 9.5px;
            text-align: justify;
            line-height: 1.35;
            color: #444;
        }

        /* ============================================================
           INFO CARD + CACHET
        ============================================================ */
        .info-card {
            border: 1px dashed #777;
            background: #fafafa;
            padding: 8px 10px;
            font-size: 10px;
            line-height: 1.4;
            margin-bottom: 12px;
        }

        .info-card-title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            margin-bottom: 3px;
            color: #000;
        }

        /* Cachet circulaire simulé */
        .stamp-tbl {
            width: 100px;
            height: 100px;
            border-collapse: collapse;
            border: 3px dotted #888;
            margin: 0 auto;
        }

        .stamp-tbl td {
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            color: #777;
            letter-spacing: 1px;
            padding: 0;
        }

        /* ============================================================
           NB + CONFORMITÉ + DATE
        ============================================================ */
        .nb-text {
            color: #cc0000;
            font-weight: bold;
            font-size: 10.5px;
            margin-top: 14px;
            text-align: justify;
            border-top: 1px solid #f0c0c0;
            padding-top: 8px;
        }

        .conformity-box {
            border: 1px solid #999;
            padding: 8px 12px;
            margin-top: 10px;
            font-size: 9.5px;
            background: #fff;
            line-height: 1.5;
        }

        .conformity-title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            margin-bottom: 3px;
        }

        .retrieval-date {
            margin-top: 10px;
            font-size: 9.5px;
            text-align: right;
            color: #444;
            font-weight: bold;
            font-style: italic;
        }
    </style>
</head>
<body>

    {{-- ============================================================
         EN-TÊTE OFFICIEL
    ============================================================ --}}
    <div class="header-border">
        <table class="header-tbl">
            <tr>
                <td class="h-left">
                    <span class="h-inst">Université d'Abomey-Calavi</span><br>
                    <span class="h-sep">————————————————</span>
                    Secrétariat Général<br>
                    <span class="h-sep">————————————————</span>
                    Direction de la Scolarité Universitaire
                </td>
                <td class="h-right">
                    <span class="h-inst">République du Bénin</span><br>
                    <span class="h-devise">Fraternité – Justice – Travail</span><br>
                    <span class="h-sep">————————————————</span>
                    Ministère de l'Enseignement Supérieur<br>
                    et de la Recherche Scientifique
                </td>
            </tr>
        </table>
    </div>

    {{-- ============================================================
         TITRE + INFOS TECHNIQUES
    ============================================================ --}}
    <div class="doc-title">Fiche de Préinscription</div>
    <hr class="title-hr">

    <div class="bank-info">
        Compte Ecobank Inscription N°321106970703 &nbsp;|&nbsp; Compte Inscription UBA N°506100001485
    </div>

    <div class="tech-code">
        1#{{ $etudiant->id ?? '10035024' }}#{{ date('y') }}#{{ $inscription->filiere->code ?? 'LRs1s2-MIA' }}
    </div>

    <div class="academic-year">
        Année académique : <strong>{{ $inscription->annee_academique ?? '2023 - 2024' }}</strong>
    </div>

    {{-- ============================================================
         SECTION IDENTITÉ
    ============================================================ --}}
    <div class="section-title">— Identité —</div>

    <table class="identity-tbl">
        <tr>
            {{-- DONNÉES --}}
            <td class="id-data-cell">
                <table class="data-tbl">
                    <tr>
                        <td class="lbl">Identifiant</td>
                        <td class="val val-mono">{{ strtolower($etudiant->prenom ?? 'prenom') }}.{{ $etudiant->id ?? '_' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Matricule</td>
                        <td class="val val-mono">{{ $etudiant->matricule ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Nom</td>
                        <td class="val" style="text-transform:uppercase;">{{ $etudiant->nom ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Prénom(s)</td>
                        <td class="val">{{ $etudiant->prenom ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Né(e) le</td>
                        <td class="val">
                            {{ $etudiant->date_naissance
                                ? \Carbon\Carbon::parse($etudiant->date_naissance)->format('d/m/Y')
                                : '—' }}
                            @if($etudiant->lieu_naissance)
                                &nbsp;à {{ $etudiant->lieu_naissance }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="lbl">Nationalité</td>
                        <td class="val">{{ $etudiant->nationalite ?? 'Béninoise' }}</td>
                    </tr>
                </table>
            </td>

            {{-- QR CODE CORRIGÉ POUR DOMPDF --}}
            <td class="qr-cell">
    <div class="qr-inner">
        <!-- Cette balise va maintenant lire le SVG encodé en base64 sans aucune erreur -->
        <img src="{{ $qrCode }}" alt="QR Code">
    </div>
    <div class="qr-label">Scanner pour<br>vérifier</div>
</td>
        </tr>
    </table>

    {{-- ============================================================
         SECTION INSCRIPTION
    ============================================================ --}}
    <div class="section-title">— Inscription —</div>

    <div class="insc-block">
        <table class="data-tbl">
            <tr>
                <td class="lbl">Entité</td>
                <td class="val" colspan="3">
                    {{ $inscription->filiere->entite_uac ?? 'Faculté des Sciences et Techniques (FAST)' }}
                </td>
            </tr>
            <tr>
                <td class="lbl">Formation</td>
                <td class="val" colspan="3" style="font-weight:normal;color:#333;">
                    {{ $inscription->filiere->nom_filiere ?? 'Licence en Mathématiques, Informatique et Applications – Sem. 1 & 2' }}
                </td>
            </tr>
            <tr>
                <td class="lbl">Code étape</td>
                <td class="val" style="width:28%;">{{ $inscription->filiere->code ?? 'LRs1s2-MIA' }}</td>
                <td class="lbl" style="width:22%;">Statut</td>
                <td class="val">{{ $inscription->statut_boursier ?? 'Boursier' }}</td>
            </tr>
            <tr>
                <td class="lbl">Montant dû</td>
                <td class="val">
                    {{ $inscription->montant_frais ? number_format($inscription->montant_frais, 1, '.', ' ') : '15 000.0' }} FCFA
                </td>
                <td class="lbl" style="color:#cc0000;">Restant dû</td>
                <td class="val-red">
                    {{ $inscription->restant_du ? number_format($inscription->restant_du, 1, '.', ' ') : '15 000.0' }} FCFA
                </td>
            </tr>
        </table>
    </div>

    {{-- ============================================================
         ALERTE PAIEMENT
    ============================================================ --}}
    <div class="alert-box">
        <strong>Attention :</strong> Veuillez vérifier l'exactitude des données présentes sur cette fiche avant tout paiement.
        Aucun remboursement ni report ne sera possible après paiement.
    </div>

    {{-- ============================================================
         BAS DE PAGE : CHARTES (gauche) | INFO + CACHET (droite)
    ============================================================ --}}
    <table class="bottom-tbl">
        <tr>
            {{-- COLONNE GAUCHE --}}
            <td class="bottom-left">
                <div class="charte-title">Je certifie avoir pris connaissance avec :</div>

                <div class="charte-item">La charte des examens à l'UAC</div>
                <div class="charte-item">La charte procédure disciplinaire en vigueur</div>
                <div class="charte-item">La charte anti-plagiat de l'UAC</div>
                <div class="charte-item">Le règlement d'usage des ressources informatiques de l'UAC</div>

                <div class="engagement-text">
                    Et je m'engage à respecter la réglementation et les consignes données.
                    Le plagiat est un vol de la production intellectuelle d'autrui.
                </div>

                <div class="biblio-title">Bibliothèque Universitaire</div>
                <div class="biblio-text">
                    La bibliothèque de l'UAC invite tous les étudiants inscrits à découvrir son fond
                    documentaire riche et diversifié. Visitez
                    <strong style="text-decoration:underline;">www.koha.uac.bj</strong> pour en savoir plus.
                </div>
            </td>

            {{-- COLONNE DROITE --}}
            <td class="bottom-right">
                <div class="info-card">
                    <div class="info-card-title">Information</div>
                    Tout nouvel étudiant doit prendre rendez-vous dans son espace étudiant pour la prise
                    de photo et l'impression de sa carte d'étudiant.
                </div>

                {{-- Cachet "En cours" simulé --}}
                <table class="stamp-tbl">
                    <tr>
                        <td>En cours</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ============================================================
         NB OBLIGATOIRE
    ============================================================ --}}
    <div class="nb-text">
        NB : Le processus de validation de votre inscription dans votre espace étudiant est
        <span style="text-decoration:underline;">obligatoire</span>, sans quoi votre préinscription est nulle.
    </div>

    {{-- ============================================================
         CONFORMITÉ + DATE
    ============================================================ --}}
    <div class="conformity-box">
        <div class="conformity-title">Vérifiez la conformité de ce document</div>
        1. Scannez le QR Code pour vérifier la conformité du document.<br>
        2. Assurez-vous que le document est identique à celui en ligne.
    </div>

    <div class="retrieval-date">
        Retirée le {{ \Carbon\Carbon::parse($inscription->updated_at ?? now())->translatedFormat('d F Y à H:i:s') }}
    </div>

</body>
</html>
