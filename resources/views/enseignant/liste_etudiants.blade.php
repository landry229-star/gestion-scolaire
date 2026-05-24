@extends('enseignant.app')

@section('content')
<div class="container-fluid text-white">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0">{{ $matiere->nom_matiere }}</h2>
            <p class="text-muted mb-0">Liste nominative des étudiants inscrits</p>
        </div>
        <a href="{{ route('enseignant.index') }}" class="btn btn-outline-light btn-sm">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card bg-dark border-0 shadow">
        {{-- Fix : double card-header supprimé — un seul header avec les deux actions --}}
        <div class="card-header bg-transparent border-secondary d-flex justify-content-between align-items-center">
            <span class="text-primary fw-bold">
                Effectif : {{ $etudiants->count() }} étudiant(s)
            </span>
            <div class="d-flex gap-2">
                {{-- Fix : mr-2 (BS4) → gap-2 sur le flex parent --}}
                <a href="{{ route('enseignant.etudiants.export', $matiere->id_matiere) }}"
                   class="btn btn-sm btn-success">
                    <i class="fas fa-file-excel"></i> Exporter Excel
                </a>
                <button class="btn btn-sm btn-light" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Matricule</th>
                            <th>Nom & Prénoms</th>
                            <th>Sexe</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($etudiants as $ins)
                        <tr>
                            <td>
                                {{-- Fix : fallback photo si null → avatar initiales --}}
                                @if($ins->etudiant && $ins->etudiant->photo)
                                    <img src="{{ asset('storage/' . $ins->etudiant->photo) }}"
                                         class="rounded-circle"
                                         width="35" height="35"
                                         style="object-fit: cover;"
                                         alt="Photo {{ $ins->etudiant->nom }}">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                                         style="width: 35px; height: 35px; font-size: 0.75rem;">
                                        @if($ins->etudiant)
                                            {{ strtoupper(substr($ins->etudiant->nom, 0, 1)) }}{{ strtoupper(substr($ins->etudiant->prenom, 0, 1)) }}
                                        @else
                                            <i class="fas fa-user"></i>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                {{-- Fix : badge-secondary (BS4) → badge bg-secondary (BS5) --}}
                                <span class="badge bg-secondary">
                                    {{ $ins->etudiant->matricule ?? '—' }}
                                </span>
                            </td>
                            <td>
                                @if($ins->etudiant)
                                    {{ strtoupper($ins->etudiant->nom) }} {{ $ins->etudiant->prenom }}
                                @else
                                    <span class="text-danger">Étudiant introuvable</span>
                                @endif
                            </td>
                            <td>{{ $ins->etudiant->sexe ?? '—' }}</td>
                            <td class="text-info">{{ $ins->etudiant->email ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="fas fa-users-slash fa-3x mb-3 d-block"></i>
                                Aucun étudiant inscrit dans cette matière.
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
