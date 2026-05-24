<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relevé Provisoire de Notes - {{ $etudiant->matricule }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .entete-uac {
            font-size: 11px;
            text-align: center;
            font-weight: bold;
            line-height: 1.3;
        }
        .doc-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .info-box {
            width: 100%;
            border: 1px solid #ddd;
            margin-bottom: 25px;
            padding: 12px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            background-color: #f5f5f5;
            padding: 6px 10px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-left: 3px solid #1a3a5c;
        }
        .semestre-title {
            font-size: 11px;
            font-weight: bold;
            color: #1a3a5c;
            margin: 10px 0 5px 0;
        }
        .table-notes {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table-notes th {
            background-color: #1a3a5c;
            color: white;
            font-size: 11px;
            padding: 6px;
            text-align: left;
            border: 1px solid #1a3a5c;
        }
        .table-notes td {
            padding: 6px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }

        /* Couleurs pour les mentions basées sur ton code d'origine */
        .moyenne-box {
            background-color: #fafbfc;
            font-weight: bold;
        }
        .footer-note {
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            text-align: center;
            border-top: 1px dashed #ddd;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    @php
        $getMentionUac = function($note) {
            if ($note >= 16) return 'Très Bien';
            if ($note >= 14) return 'Bien';
            if ($note >= 12) return 'Assez Bien';
            if ($note >= 10) return 'Passable';
            return 'Ajourné';
        };

        $cursusParAnnee = $notes->groupBy(function($note) {
            return $note->inscription->annee_academique ?? $note->annee_academique ?? 'Année En cours';
        })->sortKeysDesc();
    @endphp

    {{-- EN-TÊTE OFFICIEL STYLE UNIVERSITÉ --}}
    <table class="header-table">
        <tr>
            <td class="entete-uac" style="width: 45%;">
                UNIVERSITÉ D'ABOMEY-CALAVI (UAC)<br>
                ***********************<br>
                RECONNAISSANCE DU PARCOURS SCOLARISÉ
            </td>
            <td style="width: 10%;"></td>
            <td class="entete-uac" style="width: 45%;">
                RÉPUBLIQUE DU BÉNIN<br>
                ***********************<br>
                MINISTÈRE DE L'ENSEIGNEMENT SUPÉRIEUR<br>
                ET DE LA RECHERCHE SCIENTIFIQUE
            </td>
        </tr>
    </table>

    <div class="doc-title">Relevé de Notes et Crédits Provisoire</div>

    {{-- INFOS ÉTUDIANT --}}
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td class="bold" style="width: 15%;">Matricule :</td>
                <td style="width: 35%;">{{ $etudiant->matricule }}</td>
                <td class="bold" style="width: 15%;">Filière :</td>
                <td style="width: 35%;">{{ $etudiant->filiere?->nom_filiere ?? '—' }}</td>
            </tr>
            <tr>
                <td class="bold">Nom & Prénoms :</td>
                <td>{{ $etudiant->nom_complet ?? $etudiant->nom . ' ' . $etudiant->prenom }}</td>
                <td class="bold">Date :</td>
                <td>{{ date('d/m/Y') }}</td>
            </tr>
        </table>
    </div>

    {{-- CORPS DU RELEVÉ --}}
    @foreach($cursusParAnnee as $annee => $notesDeLAnnee)
        <div class="section-title">Année Académique : {{ $annee }}</div>

        @php
            $semestresDeLAnnee = $notesDeLAnnee->groupBy(function($note) {
                $sem = $note->matiere?->semestre;
                if (is_object($sem)) return $sem->code_semestre ?? 'Semestre';
                if (is_array($sem)) return $sem['code_semestre'] ?? 'Semestre';
                return $sem ?? 'Semestre Unique';
            })->sortKeys();
        @endphp

        @foreach($semestresDeLAnnee as $semestreLabel => $notesDuSemestre)
            <div class="semestre-title">Session : {{ strtoupper($semestreLabel) }}</div>

            <table class="table-notes">
                <thead>
                    <tr>
                        <th style="width: 15%;">Code UE</th>
                        <th style="width: 55%;">Intitulé de l'Épreuve</th>
                        <th style="width: 15%; text-align: center;">Note / 20</th>
                        <th style="width: 15%; text-align: center;">Crédit</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalCrdMatiere = 0; $totalCrdCapitalises = 0; $sommeNotesPonderees = 0;
                    @endphp
                    @foreach($notesDuSemestre as $note)
                        @php
                            $n = $note->valeur_note;
                            $cr = $note->matiere?->credit_matiere ?? $note->matiere?->credits ?? $note->matiere?->credit ?? 0;

                            $totalCrdMatiere += $cr;
                            $sommeNotesPonderees += ($n * $cr);
                            if($n >= 10) { $totalCrdCapitalises += $cr; }
                        @endphp
                        <tr>
                            <td>{{ $note->matiere?->code_matiere ?? $note->matiere?->code ?? 'N/A' }}</td>
                            <td>{{ $note->matiere?->nom_matiere ?? 'N/A' }}</td>
                            <td class="text-center bold">{{ number_format($n, 2) }}</td>
                            <td class="text-center">{{ $cr }} {{ $n >= 10 ? '(V)' : '(A)' }}</td>
                        </tr>
                    @endforeach

                    @php
                        $moyenneSemestrielle = $totalCrdMatiere > 0 ? ($sommeNotesPonderees / $totalCrdMatiere) : 0;
                        $sMention = $getMentionUac($moyenneSemestrielle);
                    @endphp
                    {{-- LIGNE DE SYNTHÈSE DU SEMESTRE --}}
                    <tr class="moyenne-box">
                        <td colspan="2" class="bold">RÉSULTATS DE LA SESSION</td>
                        <td class="text-center">
                            Moy: {{ number_format($moyenneSemestrielle, 2) }}<br>
                            <span style="font-size: 9px; font-weight: normal;">{{ $sMention }}</span>
                        </td>
                        <td class="text-center">
                            {{ $totalCrdCapitalises }} / {{ $totalCrdMatiere }}<br>
                            <span style="font-size: 9px; font-weight: normal;">Crédits val.</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @endforeach

    <div class="footer-note">
        Document généré électroniquement. Ce relevé provisoire ne remplace pas le relevé officiel signé par les autorités de la scolarité centrale.
    </div>

</body>
</html>
