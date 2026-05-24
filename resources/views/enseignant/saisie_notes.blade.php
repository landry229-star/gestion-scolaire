@extends('enseignant.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 text-white">
            Saisie des notes : <span class="text-primary">{{ $matiere->nom_matiere }}</span>
        </h2>
        <a href="{{ route('enseignant.index') }}" class="btn btn-sm btn-outline-light shadow-sm">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    {{-- Alerte session verrouillée --}}
    @if($sessionVerrouillee)
        <div class="alert alert-{{ auth()->user()->is_superadmin ? 'danger' : 'warning' }} shadow border-0 mb-4">
            @if(auth()->user()->is_superadmin)
                <i class="fas fa-exclamation-triangle"></i>
                <strong>MODE SUPER-ADMIN :</strong> La session est verrouillée, mais vous avez le privilège de modification permanente.
            @else
                <i class="fas fa-lock"></i>
                <strong>SESSION CLÔTURÉE :</strong> La période de saisie est terminée. Les notes sont en lecture seule.
            @endif
        </div>
    @endif

    {{-- Formulaire principal (brouillon) --}}
    <form action="{{ route('enseignant.notes.store', $matiere->id_matiere) }}" method="POST">
        @csrf
        <div class="card bg-dark text-white shadow border-0">
            <div class="card-header bg-transparent border-secondary">
                <h6 class="m-0 fw-bold text-primary">
                    Liste des étudiants — {{ $matiere->filiere->nom_filiere ?? 'N/A' }}
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead>
                            <tr class="text-muted">
                                <th>Étudiant</th>
                                <th class="text-center">Note CC (40%)</th>
                                <th class="text-center">Note Examen (60%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($etudiants as $ins)
                            @php
                                {{-- Fix : null-check sur la relation etudiant avant d'appeler getNote() --}}
                                $noteExistante = $ins->etudiant ? $ins->etudiant->getNote($matiere->id_matiere) : null;
                                $isReadOnly    = ($sessionVerrouillee && !auth()->user()->is_superadmin);
                            @endphp
                            <tr>
                                <td class="align-middle">
                                    @if($ins->etudiant)
                                        <span class="text-uppercase fw-bold">{{ $ins->etudiant->nom }}</span>
                                        {{ $ins->etudiant->prenom }}
                                        <br><small class="text-muted">{{ $ins->etudiant->matricule }}</small>
                                    @else
                                        <span class="text-danger"><i class="fas fa-exclamation-circle"></i> Étudiant introuvable</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <input type="number" step="0.01"
                                           name="notes[{{ $ins->id_etudiant }}][cc]"
                                           class="form-control form-control-sm bg-secondary text-white border-0 mx-auto"
                                           style="width: 100px;"
                                           min="0" max="20"
                                           value="{{ $noteExistante->note_cc ?? '' }}"
                                           {{ $isReadOnly ? 'readonly' : '' }}>
                                </td>
                                <td class="text-center">
                                    <input type="number" step="0.01"
                                           name="notes[{{ $ins->id_etudiant }}][exam]"
                                           class="form-control form-control-sm bg-secondary text-white border-0 mx-auto"
                                           style="width: 100px;"
                                           min="0" max="20"
                                           value="{{ $noteExistante->note_examen ?? '' }}"
                                           {{ $isReadOnly ? 'readonly' : '' }}>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Fix : card-footer ET bouton "Enregistrer" sont DANS le <form> principal --}}
            <div class="card-footer bg-transparent border-secondary d-flex justify-content-end gap-2">
                @if(!$sessionVerrouillee || auth()->user()->is_superadmin)
                    {{-- Fix : mr-2 (BS4) → me-2 (BS5), ici remplacé par gap-2 sur le flex parent --}}
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer le brouillon
                    </button>
                @endif
            </div>
        </div>
    </form>{{-- Fin du formulaire principal --}}

    {{-- Formulaire de soumission finale (séparé intentionnellement) --}}
    @if(!$sessionVerrouillee || auth()->user()->is_superadmin)
        <div class="d-flex justify-content-end mt-2">
            <form action="{{ route('enseignant.notes.soumettre', $matiere->id_matiere) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success"
                        onclick="return confirm('Une fois soumises, les notes seront envoyées pour validation. Confirmer ?')">
                    <i class="fas fa-paper-plane"></i> Soumettre à l'Admin
                </button>
            </form>
        </div>
    @endif
</div>

<script>
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const inputs = Array.from(document.querySelectorAll('input[type="number"]:not([readonly])'));
        const index = inputs.indexOf(e.target);
        if (index > -1 && index + 2 < inputs.length) {
            inputs[index + 2].focus();
        }
    }
});
</script>
@endsection
