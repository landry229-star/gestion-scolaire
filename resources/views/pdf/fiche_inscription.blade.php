<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche d'Inscription - {{ $etudiant->matricule }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; margin: 0; padding: 0; }
        .container { padding: 30px; border: 5px solid #eee; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #28a745; padding-bottom: 10px; }
        .logo { height: 90px; margin-bottom: 10px; }
        .univ-name { font-size: 20px; font-weight: bold; color: #1b1b18; text-transform: uppercase; }

        .fiche-title { text-align: center; background: #f8f9fa; padding: 10px; margin: 20px 0; border: 1px solid #ddd; }

        .content-table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .content-table td { padding: 10px; vertical-align: top; }

        .photo-box { width: 120px; height: 150px; border: 1px solid #ccc; text-align: center; }
        .student-photo { width: 100%; height: 100%; object-fit: cover; }

        .info-label { font-weight: bold; color: #555; width: 200px; }
        .info-value { color: #000; font-size: 14px; }

        .footer { margin-top: 40px; }
        .signature-section { width: 100%; margin-top: 30px; }
        .signature-box { text-align: center; width: 50%; float: right; }

        .barcode { margin-top: 20px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="{{ public_path('images/logo_uac.png') }}" class="logo">
        <div class="univ-name">Université d'Abomey-Calavi</div>
        <div style="font-size: 12px;">République du Bénin</div>
        <div style="font-size: 11px; margin-top: 5px;">Ministère de l'Enseignement Supérieur et de la Recherche Scientifique</div>
    </div>

    <div class="fiche-title">
        <h2 style="margin: 0; color: #28a745;">FICHE D'INSCRIPTION OFFICIELLE</h2>
        <div style="font-size: 14px;">Année Académique : 2025-2026</div>
    </div>

    <table class="content-table">
        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td class="info-label">MATRICULE :</td>
                        <td class="info-value" style="font-size: 18px; font-weight: bold; color: #28a745;">
                            {{ $etudiant->matricule }}
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">NOM :</td>
                        <td class="info-value">{{ strtoupper($etudiant->nom) }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">PRÉNOM :</td>
                        <td class="info-value">{{ $etudiant->prenom }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">FILIÈRE :</td>
                        <td class="info-value"><strong>{{ optional($inscription->filiere)->nom_filiere }} }}</strong></td>
                    </tr>
                    <tr>
                        <td class="info-label">DATE DE NAISSANCE :</td>
                        <td class="info-value">{{ date('d/m/Y', strtotime($etudiant->date_naissance)) }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">LIEU DE NAISSANCE :</td>
                        <td class="info-value">{{ $etudiant->lieu_naissance }}</td>
                    </tr>
                </table>
            </td>

            <td style="width: 150px;">
                <div class="photo-box">
                    @if($etudiant->photo)
                        <img src="{{ public_path('storage/' . $etudiant->photo) }}" class="student-photo">
                    @else
                        <div style="padding-top: 50px; font-size: 10px; color: #999;">PHOTO</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div style="margin-top: 30px; padding: 15px; border: 1px dashed #28a745; background: #f0fff4;">
        <small><strong>Note :</strong> Cette fiche est générée numériquement. Elle est valable pour toutes les formalités administratives au sein de l'université pour l'année en cours.</small>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p>Fait à Abomey-Calavi, le {{ date('d/m/Y') }}</p>
            <p><strong>Le Chef Scolarité</strong></p>
            <div style="margin-top: 40px; border-bottom: 1px solid #000; width: 150px; margin-left: auto; margin-right: auto;"></div>
            <p style="font-size: 10px; font-style: italic;">(Cachet et Signature)</p>
        </div>
    </div>

    <div style="clear: both;"></div>

    <div class="barcode">
        ID-INSCRIPTION: {{ $inscription->id_inscription }} | VERIFICATION-CODE: {{ md5($etudiant->matricule) }}
    </div>
</div>

</body>
</html>
