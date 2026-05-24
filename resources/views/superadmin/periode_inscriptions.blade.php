@extends('layouts.superadmin')

@section('content')

<h2>Période d'inscription</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('superadmin.periode.gerer') }}">
    @csrf

    <label>Date début</label>
    <input type="date" name="date_debut" required>

    <label>Date fin</label>
    <input type="date" name="date_fin" required>

    <button type="submit">Définir période</button>
</form>

<hr>

<form method="POST" action="{{ route('superadmin.periode.fermer') }}">
    @csrf
    <button type="submit" style="background:red;color:white;">
        Fermer immédiatement les inscriptions
    </button>
</form>

@endsection
