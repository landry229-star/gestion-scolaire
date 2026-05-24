@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Validation des notes en attente</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-left-success shadow animated--grow-in">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des matières à valider</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>Matière</th>
                            <th>Filière</th>
                            <th>Enseignant</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matieres as $matiere)
                        <tr>
                            <td><strong>{{ $matiere->nom_matiere }}</strong></td>
                            <td>{{ $matiere->filiere->nom_filiere }}</td>
                            <td>{{ $matiere->enseignant->nom }} {{ $matiere->enseignant->prenom }}</td>
                            <td class="text-center">
                                <form action="{{ route('superadmin.notes.approve', $matiere->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm px-4 shadow-sm" onclick="return confirm('Voulez-vous vraiment publier ces notes ? Les étudiants y auront accès immédiatement.')">
                                        <i class="fas fa-check-circle mr-1"></i> Approuver & Publier
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <img src="{{ asset('img/no-data.svg') }}" style="width: 150px; opacity: 0.5;" alt=""><br>
                                <p class="mt-3 text-muted">Aucune note en attente de validation pour le moment.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
