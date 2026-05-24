@extends('layouts.superadmin')

@section('title', 'Confirmer la suppression')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght=400;500;600&display=swap');
    .delete-container {
        font-family: 'DM Sans', sans-serif;
        max-width: 500px;
        margin: 4rem auto;
        background: #fff;
        border: 1px solid #e8e4da;
        border-radius: 14px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .warning-icon { font-size: 3rem; margin-bottom: 1rem; color: #ef4444; }
    .title { font-size: 1.5rem; font-weight: 600; color: #0f172a; margin-bottom: 0.5rem; }
    .text { color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem; line-height: 1.5; }
    .item-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem;
        font-weight: 500;
        color: #1e293b;
        margin-bottom: 2rem;
        font-family: monospace;
    }
    .actions { display: flex; gap: 0.75rem; justify-content: center; }
    .btn {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 0 1.5rem; height: 42px; border-radius: 8px; font-size: 0.875rem;
        font-weight: 500; cursor: pointer; border: none; text-decoration: none; transition: 0.15s;
    }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .btn-danger { background: #ef4444; color: white; }
    .btn-danger:hover { background: #dc2626; }
</style>

<div class="delete-container">
    <div class="warning-icon">⚠️</div>
    <h2 class="title">Supprimer la matière ?</h2>
    <p class="text">
        Attention, cette action est irréversible. Êtes-vous sûr de vouloir retirer cette matière définitivement du système ?
    </p>

    <div class="item-box">
        [{{ $matiere->code_matiere }}] {{ $matiere->nom_matiere }}
    </div>

    <div class="actions">
        <a href="{{ route('superadmin.matieres') }}" class="btn btn-secondary">Annuler</a>

        <form action="{{ route('superadmin.matieres.destroy', $matiere->id_matiere) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
        </form>
    </div>
</div>
@endsection
