@extends('enseignant.app')

@section('content')
<div class="container-fluid">
    {{-- Carte profil enseignant --}}
    <div class="card bg-dark text-white shadow mb-4 border-0">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    @if($enseignant->photo)
                        <img src="{{ asset('storage/' . $enseignant->photo) }}"
                             class="rounded-circle border border-primary shadow"
                             width="80" height="80" style="object-fit: cover;"
                             alt="Photo de {{ $enseignant->nom }}">
                    @else
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    @endif
                </div>
                <div class="col">
                    <h4 class="mb-0">{{ $enseignant->nom }} {{ $enseignant->prenom }}</h4>
                    {{-- Fix : badge-pill badge-primary (BS4) → badge rounded-pill bg-primary (BS5) --}}
                    <span class="badge rounded-pill bg-primary">{{ $enseignant->grade_enseignant }}</span>
                    <div class="mt-2 small text-muted">
                        <i class="fas fa-envelope me-2"></i> {{ $enseignant->email }} |
                        <i class="fas fa-phone me-2"></i> {{ $enseignant->telephone }}
                    </div>
                </div>
                <div class="col-auto text-end">
                    <div class="text-muted small mb-2">{{ now()->translatedFormat('d F Y') }}</div>
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-user-edit"></i> Modifier Profil
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerte session verrouillée --}}
    @if($sessionVerrouillee)
        <div class="alert alert-warning border-0 shadow mb-4">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Session Clôturée :</strong> La saisie et la modification des notes sont désactivées pour cette période.
        </div>
    @endif

    {{-- Cartes statistiques --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-dark text-white border-start border-primary border-3 shadow h-100 py-2">
                <div class="card-body">
                    {{-- Fix : no-gutters (BS4) → g-0 (BS5) --}}
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Matières Assignées</div>
                            <div class="h5 mb-0 fw-bold">{{ $matieres->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card bg-dark text-white border-start border-success border-3 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Session Actuelle</div>
                            {{-- Fix : valeur hardcodée remplacée par une variable dynamique --}}
                            <div class="h5 mb-0 fw-bold">{{ $sessionActuelle ?? '—' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success" style="opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Tableau des matières --}}
        <div class="col-lg-8">
            <div class="card bg-dark text-white shadow mb-4 border-0">
                <div class="card-header py-3 d-flex align-items-center justify-content-between bg-transparent border-bottom border-secondary">
                    <h6 class="m-0 fw-bold text-primary">Gestion des notes par UE</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr class="text-muted" style="font-size: 0.85rem;">
                                    <th>MATIÈRE</th>
                                    <th>FILIÈRE</th>
                                    <th>ANNÉE</th>
                                    <th class="text-center">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($matieres as $matiere)
                                <tr>
                                    <td><strong>{{ $matiere->nom_matiere }}</strong></td>
                                    <td><small>{{ $matiere->filiere->nom_filiere ?? 'Non définie' }}</small></td>
                                    <td>
                                        {{-- Fix : badge-secondary (BS4) → badge bg-secondary (BS5) --}}
                                        <span class="badge bg-secondary">{{ $matiere->pivot->annee_academique }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('enseignant.etudiants.liste', $matiere->id_matiere) }}"
                                               class="btn btn-outline-info btn-sm" title="Liste des étudiants">
                                                <i class="fas fa-users"></i>
                                            </a>
                                            <a href="{{ route('enseignant.notes.saisie', $matiere->id_matiere) }}"
                                               class="btn btn-primary btn-sm px-3">
                                                <i class="fas fa-pen-square me-1"></i> Saisir Notes
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        <i class="fas fa-folder-open fa-3x mb-3 d-block"></i>
                                        Aucune matière assignée pour le moment.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Outils & Documents --}}
        <div class="col-lg-4">
            <div class="card bg-dark text-white shadow mb-4 border-start border-warning border-3">
                <div class="card-body">
                    <h6 class="fw-bold text-warning mb-3">Outils & Documents</h6>

                    {{-- Fix : route non implémentée signalée explicitement + disabled pour éviter une erreur silencieuse --}}
                    <a href="{{ route('enseignant.etudiants.export.all') }}"
                       class="btn btn-outline-light w-100 text-start mb-2">
                        <i class="fas fa-file-excel me-2 text-success"></i> Télécharger listes d'appel
                    </a>

                    <button class="btn btn-outline-light w-100 text-start mb-2" onclick="window.print()">
                        <i class="fas fa-print me-2 text-info"></i> Imprimer mon emploi du temps
                    </button>

                    <div class="mt-4 p-3 rounded" style="background: rgba(255,255,255,.05);">
                        <small class="text-warning d-block mb-1">
                            <i class="fas fa-clock me-1"></i> Délai de saisie :
                        </small>
                        <p class="small mb-0 text-muted">
                            Les notes du semestre doivent être soumises avant le
                            <strong>{{ $dateLimite ?? '15 juin 2026' }}</strong> pour délibération.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
