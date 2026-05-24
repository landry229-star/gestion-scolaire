@extends('layouts.superadmin ')

@section('title', 'Étudiants')

@section('content')

<style>
.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.page-title{
    font-size:24px;
    font-weight:700;
    color:#1e293b;
}

.grid-filieres{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:20px;
}

.filiere-card{
    background:white;
    border-radius:14px;
    border:1px solid #e2e8f0;
    padding:24px;
    transition:.2s;
}

.filiere-card:hover{
    transform:translateY(-3px);
    box-shadow:0 10px 25px rgba(0,0,0,.05);
}

.filiere-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:18px;
}

.filiere-icon{
    width:55px;
    height:55px;
    border-radius:12px;
    background:#eff6ff;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#3b82f6;
    font-size:22px;
}

.student-count{
    font-size:28px;
    font-weight:700;
    color:#0f172a;
}

.filiere-name{
    font-size:18px;
    font-weight:600;
    margin-bottom:8px;
}

.filiere-code{
    font-size:13px;
    color:#64748b;
    margin-bottom:18px;
}

.actions{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.btn{
    padding:10px 14px;
    border-radius:8px;
    text-decoration:none;
    font-size:13px;
    font-weight:600;
    display:inline-flex;
    align-items:center;
    gap:8px;
}

.btn-view{
    background:#3b82f6;
    color:white;
}

.btn-pdf{
    background:#dc2626;
    color:white;
}

.btn-excel{
    background:#16a34a;
    color:white;
}
</style>

<div class="page-header">
    <h1 class="page-title">
        Gestion des étudiants
    </h1>
</div>

<div class="grid-filieres">

@forelse($filieres as $filiere)

<div class="filiere-card">

    <div class="filiere-top">
        <div class="filiere-icon">
            <i class="fas fa-users"></i>
        </div>

        <div class="student-count">
            {{ $filiere->inscriptions_count }}
        </div>
    </div>

    <div class="filiere-name">
        {{ $filiere->nom_filiere }}
    </div>

    <div class="filiere-code">
        Code : {{ $filiere->code_filiere }}
    </div>

    <div class="actions">

        <a href="{{ route('superadmin.etudiants.filiere', $filiere->id_filiere) }}"
           class="btn btn-view">
            <i class="fas fa-eye"></i>
            Voir
        </a>

        <a href="{{ route('superadmin.etudiants.pdf', $filiere->id_filiere) }}"
           class="btn btn-pdf">
            <i class="fas fa-file-pdf"></i>
            PDF
        </a>

        <a href="{{ route('superadmin.etudiants.excel', $filiere->id_filiere) }}"
           class="btn btn-excel">
            <i class="fas fa-file-excel"></i>
            Excel
        </a>

    </div>

</div>

@empty

<div style="background:white;padding:30px;border-radius:12px;">
    Aucune filière disponible.
</div>

@endforelse

</div>

@endsection
