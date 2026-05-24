{{-- resources/views/superadmin/etudiants_filiere.blade.php --}}

@extends('layouts.superadmin')

@section('title', 'Étudiants - ' . $filiere->nom_filiere)

@section('content')
<style>
.page-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:25px;
    }

    .page-header h2{
        font-size:28px;
        color:#1e293b;
        margin:0;
    }

    .page-header p{
        color:#64748b;
        margin-top:5px;
        font-size:14px;
    }

    .btn-add{
        background:#2563eb;
        color:white;
        border:none;
        padding:12px 18px;
        border-radius:10px;
        cursor:pointer;
        font-weight:600;
        transition:0.3s;
    }

    .btn-add:hover{
        background:#1d4ed8;
    }

    .grid{
        display:grid;
        grid-template-columns:380px 1fr;
        gap:25px;
    }

    .card{
        background:white;
        border-radius:16px;
        padding:25px;
        box-shadow:0 4px 18px rgba(0,0,0,0.06);
    }

    .card h3{
        margin-bottom:20px;
        color:#0f172a;
        font-size:20px;
    }

    .form-group{
        margin-bottom:18px;
    }

    .form-group label{
        display:block;
        margin-bottom:8px;
        font-weight:600;
        color:#334155;
    }

    .form-control{
        width:100%;
        padding:12px;
        border:1px solid #cbd5e1;
        border-radius:10px;
        font-size:14px;
        transition:0.3s;
    }

    .form-control:focus{
        border-color:#2563eb;
        outline:none;
        box-shadow:0 0 0 3px rgba(37,99,235,0.15);
    }

    .btn-submit{
        width:100%;
        background:#16a34a;
        color:white;
        border:none;
        padding:14px;
        border-radius:10px;
        font-weight:bold;
        cursor:pointer;
        transition:0.3s;
    }

    .btn-submit:hover{
        background:#15803d;
    }

    .alert-success{
        background:#dcfce7;
        color:#166534;
        padding:14px;
        border-radius:10px;
        margin-bottom:20px;
    }

    .alert-error{
        background:#fee2e2;
        color:#991b1b;
        padding:14px;
        border-radius:10px;
        margin-bottom:20px;
    }

    table{
        width:100%;
        border-collapse:collapse;
    }

    table thead{
        background:#f1f5f9;
    }

    table th{
        text-align:left;
        padding:14px;
        color:#334155;
        font-size:14px;
    }

    table td{
        padding:14px;
        border-bottom:1px solid #e2e8f0;
        font-size:14px;
        color:#334155;
    }

    .badge{
        display:inline-block;
        padding:6px 10px;
        border-radius:999px;
        font-size:12px;
        font-weight:bold;
    }

    .badge-ma{
        background:#dbeafe;
        color:#1d4ed8;
    }

    .badge-mc{
        background:#ede9fe;
        color:#7c3aed;
    }

    .badge-pt{
        background:#dcfce7;
        color:#15803d;
    }

    .btn-delete{
        background:#ef4444;
        color:white;
        border:none;
        padding:8px 12px;
        border-radius:8px;
        cursor:pointer;
    }

    .btn-delete:hover{
        background:#dc2626;
    }

    .enseignant-info{
        display:flex;
        align-items:center;
        gap:12px;
    }

    .avatar{
        width:42px;
        height:42px;
        border-radius:50%;
        background:#2563eb;
        color:white;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:bold;
    }

    @media(max-width:950px){
        .grid{
            grid-template-columns:1fr;
        }
    }

</style>

<div class="page-header">

    <h1 class="page-title">
        Étudiants de la filière : {{ $filiere->nom_filiere }}
    </h1>

    <p class="page-subtitle">
        Code : {{ $filiere->code_filiere ?? '---' }}
    </p>

</div>

{{-- ACTIONS --}}
<div class="action-bar">

    <a href="{{ route('superadmin.etudiants.excel', $filiere->id_filiere) }}"
       class="btn btn-success">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>

    <a href="{{ route('superadmin.etudiants.pdf', $filiere->id_filiere) }}"
       class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>

</div>

{{-- TABLE --}}
<div class="card">

    <div class="card-header">
        <h3>
            <i class="fas fa-users"></i>
            Liste des étudiants
        </h3>

        <span class="badge-info">
            {{ $etudiants->count() }} étudiant(s)
        </span>
    </div>

    <div class="card-body">

        @if($etudiants->count() > 0)

            <div class="table-responsive">

                <table class="table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Étudiant</th>
                            <th>Matricule</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Moyenne</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($etudiants as $etudiant)

                            @php
                                $notes = $etudiant->notes;

                                $total = 0;
                                $coef = 0;

                                foreach($notes as $n){
                                    $c = $n->matiere->credit_matiere ?? 1;
                                    $total += $n->note_obtenue * $c;
                                    $coef += $c;
                                }

                                $moyenne = $coef > 0 ? round($total / $coef, 2) : 0;
                            @endphp

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <div class="student">
                                        <div class="avatar">
                                            {{ strtoupper(substr($etudiant->nom,0,1)) }}
                                        </div>
                                        <div>
                                            <strong>
                                                {{ $etudiant->nom }} {{ $etudiant->prenom }}
                                            </strong>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ $etudiant->matricule }}</td>

                                <td>{{ $etudiant->email }}</td>

                                <td>{{ $etudiant->telephone ?? '---' }}</td>

                                <td>
                                    <span class="badge {{ $moyenne >= 10 ? 'ok' : 'bad' }}">
                                        {{ $moyenne }}/20
                                    </span>
                                </td>

                                <td>

                                    <a href="{{ route('superadmin.releve', $etudiant->id_etudiant) }}"
                                       class="btn btn-primary">
                                        <i class="fas fa-file-alt"></i>
                                        Relevé
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty">
                <i class="fas fa-user-slash"></i>
                <p>Aucun étudiant dans cette filière</p>
            </div>

        @endif

    </div>

</div>

{{-- STYLE --}}
<style>

.page-header{
    margin-bottom:20px;
}

.page-title{
    font-size:24px;
    font-weight:700;
}

.page-subtitle{
    color:#64748b;
    margin-top:5px;
}

.action-bar{
    display:flex;
    gap:10px;
    margin-bottom:15px;
}

.btn{
    padding:10px 14px;
    border-radius:8px;
    color:white;
    text-decoration:none;
    font-size:13px;
    font-weight:600;
}

.btn-success{ background:#16a34a; }
.btn-danger{ background:#dc2626; }
.btn-primary{ background:#3b82f6; }

.card{
    background:white;
    border:1px solid #e2e8f0;
    border-radius:12px;
}

.card-header{
    padding:15px;
    display:flex;
    justify-content:space-between;
    border-bottom:1px solid #e2e8f0;
}

.card-body{
    padding:15px;
}

.table{
    width:100%;
    border-collapse:collapse;
}

.table th, .table td{
    padding:12px;
    border-bottom:1px solid #f1f5f9;
    text-align:left;
    font-size:14px;
}

.student{
    display:flex;
    align-items:center;
    gap:10px;
}

.avatar{
    width:35px;
    height:35px;
    border-radius:50%;
    background:#3b82f6;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
}

.badge{
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.ok{
    background:#dcfce7;
    color:#166534;
}

.bad{
    background:#fee2e2;
    color:#991b1b;
}

.empty{
    text-align:center;
    padding:40px;
    color:#64748b;
}

</style>

@endsection
