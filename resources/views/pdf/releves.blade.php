<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <style>

        body{
            font-family: DejaVu Sans;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        th, td{
            border:1px solid black;
            padding:8px;
            text-align:center;
        }

        h1,h2,h3{
            text-align:center;
        }

    </style>
</head>
<body>

<h1>UNIVERSITÉ</h1>

<h2>RELEVÉ DE NOTES</h2>

<p>
    <strong>Étudiant :</strong>

    {{ $etudiant->nom }}
    {{ $etudiant->prenom }}
</p>

<p>
    <strong>Matricule :</strong>

    {{ $etudiant->matricule }}
</p>

<table>

    <thead>
        <tr>

            <th>Matière</th>

            <th>Crédit</th>

            <th>Note</th>

            <th>Résultat</th>

        </tr>
    </thead>

    <tbody>

        @foreach($etudiant->notes as $note)

        <tr>

            <td>
                {{ $note->matiere->nom_matiere }}
            </td>

            <td>
                {{ $note->matiere->credit }}
            </td>

            <td>
                {{ $note->valeur_note }}
            </td>

            <td>

                @if($note->valeur_note >= 10)

                    Validé

                @else

                    Non validé

                @endif

            </td>

        </tr>

        @endforeach

    </tbody>

</table>

<h3>

Moyenne :
{{ $etudiant->deliberation->moyenne_generale ?? 0 }}

</h3>

<h3>

Rang :
{{ $etudiant->deliberation->rang ?? '-' }}

</h3>

<h3>

Décision :
{{ strtoupper($etudiant->deliberation->decision ?? '-') }}

</h3>

</body>
</html>
