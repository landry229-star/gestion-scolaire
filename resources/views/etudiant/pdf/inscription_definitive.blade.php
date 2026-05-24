<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Attestation d'Inscription — {{ $etudiant->matricule }}</title>
    <style>

        /* ============================================================
           BASE — Compatible DomPDF (pas de flex, float, grid, nth-child)
        ============================================================ */
        @page { margin: 28px 42px; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12.5px;
            color: #000;
            line-height: 1.5;
            background: #fff;
        }

        /* ============================================================
           EN-TÊTE
        ============================================================ */
        .header-outer {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .header-tbl { width: 100%; border-collapse: collapse; }

        .h-left {
            width: 50%;
            vertical-align: top;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.5;
            text-align: left;
        }

        .h-right {
            width: 50%;
            vertical-align: top;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.5;
            text-align: right;
        }

        .h-institution { font-size: 11.5px; }
        .h-sep { font-weight: normal; font-size: 9px; display: block; }
        .h-devise { font-weight: normal; font-style: italic; text-transform: none; font-size: 9.5px; }

        /* ============================================================
           TITRE
        ============================================================ */
        .title-block { text-align: center; margin: 14px 0 10px 0; }

        .doc-title {
            font-size: 17px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-decoration: underline;
        }

        .doc-numero {
            font-family: Arial, sans-serif;
            font-size: 11px;
            font-weight: bold;
            margin-top: 4px;
        }

        .title-hr {
            width: 55%;
            margin: 8px auto 0 auto;
            border: none;
            border-top: 1px solid #777;
        }

        /* ============================================================
           TEXTE DE CERTIFICATION
        ============================================================ */
        .certif {
            text-align: justify;
            text-indent: 40px;
            margin: 12px 0;
            font-size: 12.5px;
            line-height: 1.6;
        }

        .annee-encadree {
            font-weight: bold;
            border: 1.5px solid #000;
            padding: 1px 6px;
            font-size: 12.5px;
        }

        /* ============================================================
           TABLEAU PRINCIPAL (DONNÉES + PHOTO)
        ============================================================ */
        .main-tbl { width: 100%; border-collapse: collapse; margin: 12px 0; }

        .cell-data { vertical-align: top; padding-right: 10px; }
        .cell-photo { vertical-align: top; width: 122px; }

        /* ============================================================
           TABLEAU DES INFORMATIONS
        ============================================================ */
        .info-tbl {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #444;
        }

        /* En-tête de section */
        .sec-head { background-color: #1a1a2e; }
        .sec-head td {
            color: #fff;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 5px 9px;
        }

        /* Ligne paire */
        .row-even td { background-color: #f0f0f0; }
        /* Ligne impaire */
        .row-odd td { background-color: #ffffff; }

        .info-tbl td {
            padding: 5px 9px;
            border-bottom: 1px solid #ccc;
            vertical-align: top;
        }

        .td-lbl { width: 35%; font-size: 11.5px; color: #222; }
        .td-sep { width: 4%;  text-align: center; font-size: 11.5px; }
        .td-val { width: 61%; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .td-val.mixed { text-transform: none; }
        .td-val.mono {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            letter-spacing: 2px;
            color: #1a1a2e;
        }

        /* ============================================================
           PHOTO
        ============================================================ */
        .photo-box {
            width: 112px;
            height: 138px;
            border: 1.5px solid #555;
            overflow: hidden;
            background: #f5f5f5;
        }

        .photo-placeholder {
            width: 112px;
            height: 138px;
            text-align: center;
            vertical-align: middle;
            font-size: 9px;
            font-family: Arial, sans-serif;
            color: #888;
            line-height: 1.4;
        }

        .photo-caption {
            font-size: 8px;
            font-family: Arial, sans-serif;
            color: #555;
            text-align: center;
            margin-top: 3px;
        }

        /* ============================================================
           CONCLUSION
        ============================================================ */
        .conclusion {
            text-align: justify;
            text-indent: 40px;
            margin: 14px 0 16px 0;
            font-size: 12.5px;
            line-height: 1.6;
        }

        /* ============================================================
           SIGNATURE — table pour éviter float (non supporté DomPDF)
        ============================================================ */
        .sig-tbl { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .sig-empty { width: 48%; vertical-align: top; }
        .sig-block {
            width: 52%;
            vertical-align: top;
            text-align: center;
            font-size: 12.5px;
        }

        .sig-date { font-style: italic; margin-bottom: 6px; }
        .sig-title { font-weight: bold; text-transform: uppercase; font-size: 11.5px; line-height: 1.4; }
        .sig-spacer { height: 68px; }
        .sig-name {
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            font-size: 12.5px;
        }

        /* ============================================================
           NOTE DE SÉCURITÉ
        ============================================================ */
        .security {
            margin-top: 30px;
            padding-top: 7px;
            border-top: 1.5px solid #000;
            font-family: Arial, sans-serif;
            font-size: 8px;
            color: #333;
            text-align: justify;
            line-height: 1.3;
        }

    </style>
</head>
<body>

    {{-- ============================================================
         EN-TÊTE OFFICIEL
    ============================================================ --}}
    <div class="header-outer">
        <table class="header-tbl">
            <tr>
                <td class="h-left">
                    <span class="h-institution">Université d'Abomey-Calavi</span><br>
                    <span class="h-sep">————————————————</span>
                    Secrétariat Général<br>
                    <span class="h-sep">————————————————</span>
                    Direction de la Scolarité Universitaire
                </td>
                <td class="h-right">
                    <span class="h-institution">République du Bénin</span><br>
                    <span class="h-devise">Fraternité – Justice – Travail</span><br>
                    <span class="h-sep">————————————————</span>
                    Ministère de l'Enseignement Supérieur<br>
                    et de la Recherche Scientifique
                </td>
            </tr>
        </table>
    </div>


    {{-- ============================================================
         TITRE
    ============================================================ --}}
    <div class="title-block">
        <div class="doc-title">Attestation d'Inscription</div>
        <div class="doc-numero">
            N°&nbsp;{{ str_pad($inscription->numero_attestation ?? '0658', 4, '0', STR_PAD_LEFT) }}-{{ \Carbon\Carbon::parse($inscription->date_inscription)->format('Y') }}/UAC/SG/DSU
        </div>
        <hr class="title-hr">
    </div>


    {{-- ============================================================
         TEXTE DE CERTIFICATION
    ============================================================ --}}
    <p class="certif">
        Le Vice-Recteur chargé de la scolarité et de l'orientation universitaire de l'Université
        d'Abomey-Calavi certifie que l'étudiant(e) dont les caractéristiques suivent est
        régulièrement inscrit(e) pour l'année académique
        <span class="annee-encadree">{{ $inscription->annee_academique ?? '2023-2024' }}</span> :
    </p>


    {{-- ============================================================
         DONNÉES + PHOTO
    ============================================================ --}}
    <table class="main-tbl">
        <tr>

            {{-- DONNÉES --}}
            <td class="cell-data">
                <table class="info-tbl">

                    {{-- Section identité --}}
                    <tr class="sec-head">
                        <td colspan="3">&#9658;&nbsp;&nbsp;Identité de l'Étudiant(e)</td>
                    </tr>
                    <tr class="row-odd">
                        <td class="td-lbl">Numéro Matricule</td>
                        <td class="td-sep">:</td>
                        <td class="td-val mono">{{ $etudiant->matricule ?? '—' }}</td>
                    </tr>
                    <tr class="row-even">
                        <td class="td-lbl">Nom</td>
                        <td class="td-sep">:</td>
                        <td class="td-val">{{ strtoupper($etudiant->nom) }}</td>
                    </tr>
                    <tr class="row-odd">
                        <td class="td-lbl">Prénom(s)</td>
                        <td class="td-sep">:</td>
                        <td class="td-val mixed">{{ $etudiant->prenom }}</td>
                    </tr>
                    <tr class="row-even">
                        <td class="td-lbl">Date de naissance</td>
                        <td class="td-sep">:</td>
                        <td class="td-val mixed">
                            {{ $etudiant->date_naissance
                                ? \Carbon\Carbon::parse($etudiant->date_naissance)->translatedFormat('d F Y')
                                : '—' }}
                        </td>
                    </tr>
                    <tr class="row-odd">
                        <td class="td-lbl">Lieu de naissance</td>
                        <td class="td-sep">:</td>
                        <td class="td-val mixed">{{ $etudiant->lieu_naissance ?? '—' }}</td>
                    </tr>
                    <tr class="row-even">
                        <td class="td-lbl">Nationalité</td>
                        <td class="td-sep">:</td>
                        <td class="td-val mixed">{{ $etudiant->nationalite ?? 'Béninoise' }}</td>
                    </tr>

                    {{-- Section inscription --}}
                    <tr class="sec-head">
                        <td colspan="3">&#9658;&nbsp;&nbsp;Inscription Académique</td>
                    </tr>
                    <tr class="row-odd">
                        <td class="td-lbl">Établissement / Entité</td>
                        <td class="td-sep">:</td>
                        <td class="td-val">
                            {{ $inscription->filiere->entite_uac ?? 'Faculté des Sciences et Techniques (FAST)' }}
                        </td>
                    </tr>
                    <tr class="row-even">
                        <td class="td-lbl">Option / Filière</td>
                        <td class="td-sep">:</td>
                        <td class="td-val">
                            {{ $inscription->filiere->nom_filiere ?? 'Mathématiques, Informatique et Applications (MIA)' }}
                        </td>
                    </tr>
                    <tr class="row-odd">
                        <td class="td-lbl">Niveau d'étude</td>
                        <td class="td-sep">:</td>
                        <td class="td-val mixed">{{ $inscription->niveau ?? 'Licence 1 (Semestres 1 & 2)' }}</td>
                    </tr>
                    <tr class="row-even">
                        <td class="td-lbl">Année académique</td>
                        <td class="td-sep">:</td>
                        <td class="td-val">{{ $inscription->annee_academique ?? '2023-2024' }}</td>
                    </tr>

                </table>
            </td>

            {{-- PHOTO --}}
            <td class="cell-photo">
                @if($etudiant->photo && Storage::disk('public')->exists($etudiant->photo))
                    <div class="photo-box">
                        <img src="{{ public_path('storage/' . $etudiant->photo) }}"
                             width="112" height="138"
                             style="display:block;" alt="Photo">
                    </div>
                @else
                    <table style="width:112px;height:138px;border:1.5px solid #555;
                                  border-collapse:collapse;background:#f5f5f5;">
                        <tr>
                            <td class="photo-placeholder">
                                Photo<br>d'identité<br>réglementaire
                            </td>
                        </tr>
                    </table>
                @endif
                <div class="photo-caption">Photo de l'étudiant(e)</div>
            </td>

        </tr>
    </table>


    {{-- ============================================================
         CONCLUSION
    ============================================================ --}}
    <p class="conclusion">
        En foi de quoi, la présente attestation lui est délivrée pour servir et valoir ce que de droit.
    </p>


    {{-- ============================================================
         SIGNATURE (table — pas de float)
    ============================================================ --}}
    <table class="sig-tbl">
        <tr>
            <td class="sig-empty"></td>
            <td class="sig-block">
                <div class="sig-date">
                    Fait à Abomey-Calavi, le
                    {{ \Carbon\Carbon::parse($inscription->date_validation ?? now())->translatedFormat('d F Y') }}
                </div>
                <div class="sig-title">
                    Pour le Vice-Recteur et par délégation,<br>
                    Le Directeur de la Scolarité Universitaire
                </div>
                <div class="sig-spacer"></div>
                <div class="sig-name">{{ $signature_directeur ?? 'Prof. AZOGAN Landry' }}</div>
            </td>
        </tr>
    </table>


    {{-- ============================================================
         NOTE DE SÉCURITÉ
    ============================================================ --}}
    <div class="security">
        <strong>Important :</strong> La présente attestation d'inscription est une pièce officielle délivrée par la Direction de la
        Scolarité Universitaire (DSU) de l'Université d'Abomey-Calavi. Elle ne doit comporter aucune surcharge ni rature sous peine
        de nullité. Sa validité peut être vérifiée à tout moment auprès des services académiques centraux du Rectorat.
        — Document généré électroniquement, valable sans signature manuscrite conformément aux textes en vigueur.
    </div>

</body>
</html>
