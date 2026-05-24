<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #1e293b; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { font-size: 16px; margin: 5px 0; color: #1e293b; }
        .header p  { font-size: 10px; color: #666; margin: 2px 0; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        thead th { background: #1e293b; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        tbody tr:nth-child(even) td { background: #f8fafc; }
        .badge { display: inline-block; padding: 2px 7px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-m { background: #dbeafe; color: #1e40af; }
        .badge-f { background: #fce7f3; color: #9d174d; }
        .footer { margin-top: 20px; font-size: 9px; color: #aaa; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 8px; }
        .total { background: #f0fdf4; padding: 8px 12px; border-radius: 6px; margin-bottom: 14px; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>UNIVERSITÉ D'ABOMEY-CALAVI</h1>
        <p>Liste officielle des étudiants inscrits</p>
        <p>Filière : <strong>{{ $filiere->nom_filiere }}</strong> — Année académique 2025-2026</p>
    </div>

    <div class="total">
        <strong>Total étudiants :</strong> {{ $etudiants->count() }}
        &nbsp;|&nbsp; <strong>Masculin :</strong> {{ $etudiants->where('sexe', 'M')->count() }}
        &nbsp;|&nbsp; <strong>Féminin :</strong> {{ $etudiants->where('sexe', 'F')->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Matricule</th>
                <th>Nom & Prénom</th>
                <th>Sexe</th>
                <th>Email</th>
                <th>Téléphone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($etudiants as $i => $e)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $e->matricule ?? '—' }}</strong></td>
                <td>{{ $e->nom }} {{ $e->prenom }}</td>
                <td>
                    <span class="badge {{ $e->sexe == 'M' ? 'badge-m' : 'badge-f' }}">
                        {{ $e->sexe == 'M' ? 'M' : 'F' }}
                    </span>
                </td>
                <td>{{ $e->email }}</td>
                <td>{{ $e->telephone ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Document généré le {{ date('d/m/Y à H:i') }} — UAC © {{ date('Y') }}
    </div>
</body>
</html>
