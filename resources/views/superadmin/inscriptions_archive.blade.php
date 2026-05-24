@extends('layouts.superadmin')

@section('title', 'Archive des inscriptions')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Archive des inscriptions</h1>
        <p class="page-subtitle">
            Historique des dossiers validés et rejetés
        </p>
    </div>
</div>

{{-- FILTRES --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">

        <form method="GET" action="">
            <div style="display:flex; gap:15px; flex-wrap:wrap;">

                <select name="statut" class="form-control">
                    <option value="">-- Statut --</option>
                    <option value="valide">Validées</option>
                    <option value="rejete">Rejetées</option>
                </select>

                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Nom étudiant...">

                <button type="submit" class="btn btn-primary">
                    Filtrer
                </button>

            </div>
        </form>

    </div>
</div>

{{-- TABLEAU --}}
<div class="card">

    <div class="card-header">
        <h3>
            <i class="fas fa-history"></i>
            Historique des inscriptions
        </h3>

        <span class="badge-count">
            {{ $inscriptions->count() }} dossier(s)
        </span>
    </div>

    <div class="card-body">

        @if($inscriptions->count() > 0)

            <div class="table-responsive">

                <table class="table-custom">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Étudiant</th>
                            <th>Email</th>
                            <th>Filière</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Motif (si rejet)</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($inscriptions as $inscription)

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <div class="student-info">

                                        <div class="student-avatar">
                                            {{ strtoupper(substr($inscription->etudiant->nom ?? 'E',0,1)) }}
                                        </div>

                                        <div>
                                            <div class="student-name">
                                                {{ $inscription->etudiant->nom ?? '---' }}
                                                {{ $inscription->etudiant->prenom ?? '' }}
                                            </div>

                                            <div class="student-meta">
                                                {{ $inscription->etudiant->telephone ?? '' }}
                                            </div>
                                        </div>

                                    </div>
                                </td>

                                <td>
                                    {{ $inscription->etudiant->email ?? '---' }}
                                </td>

                                <td>
                                    <span class="badge badge-primary">
                                        {{ $inscription->filiere->nom_filiere ?? '---' }}
                                    </span>
                                </td>

                                <td>
                                    @if($inscription->statut_dossier == 'valide')
                                        <span class="badge badge-success">Validé</span>
                                    @else
                                        <span class="badge badge-danger">Rejeté</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $inscription->updated_at->format('d/m/Y') }}
                                </td>

                                <td>
                                    {{ $inscription->motif_rejet ?? '---' }}
                                </td>

                            </tr>
<td>
    @if($inscription->validated_by)
        Validé par #{{ $inscription->validated_by }}
    @elseif($inscription->rejected_by)
        Rejeté par #{{ $inscription->rejected_by }}
    @else
        ---
    @endif
</td>
                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-state">

                <i class="fas fa-folder-open"></i>

                <h3>Aucune donnée disponible</h3>

                <p>Aucune inscription archivée pour le moment.</p>

            </div>

        @endif

    </div>

</div>

{{-- STYLE --}}
<style>

.page-header{
    margin-bottom:25px;
}

.page-title{
    font-size:28px;
    font-weight:700;
}

.page-subtitle{
    color:#64748b;
    margin-top:5px;
}

.card{
    background:white;
    border:1px solid #e2e8f0;
    border-radius:14px;
    overflow:hidden;
}

.card-header{
    padding:18px;
    border-bottom:1px solid #e2e8f0;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.card-body{
    padding:20px;
}

.table-custom{
    width:100%;
    border-collapse:collapse;
}

.table-custom th{
    background:#f8fafc;
    padding:12px;
    text-align:left;
    font-size:13px;
}

.table-custom td{
    padding:12px;
    border-top:1px solid #f1f5f9;
    font-size:14px;
}

.student-info{
    display:flex;
    gap:10px;
    align-items:center;
}

.student-avatar{
    width:38px;
    height:38px;
    border-radius:50%;
    background:#3b82f6;
    color:white;
    display:flex;
    justify-content:center;
    align-items:center;
    font-weight:700;
}

.student-name{
    font-weight:600;
}

.student-meta{
    font-size:12px;
    color:#64748b;
}

.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}

.badge-primary{ background:#dbeafe; color:#1d4ed8; }
.badge-success{ background:#dcfce7; color:#166534; }
.badge-danger{ background:#fee2e2; color:#991b1b; }

.badge-count{
    background:#eff6ff;
    color:#2563eb;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
}

.empty-state{
    text-align:center;
    padding:60px 20px;
}

.empty-state i{
    font-size:50px;
    color:#94a3b8;
    margin-bottom:10px;
}

</style>

@endsection
