{{--
    Partial : superadmin/etudiants/partials/table-rows.blade.php
    Variables attendues :
      - $etudiants  : collection paginée (LengthAwarePaginator)
--}}

@forelse($etudiants as $etudiant)

    @php
        $offset = method_exists($etudiants, 'currentPage')
                    ? ($etudiants->currentPage() - 1) * $etudiants->perPage()
                    : 0;
        $num = $offset + $loop->iteration;
    @endphp

    <tr>

        {{-- N° --}}
        <td>
            <div class="row-num">{{ $num }}</div>
        </td>

        {{-- Matricule --}}
        <td>
            <span class="matricule-tag">{{ $etudiant->matricule ?? '—' }}</span>
        </td>

        {{-- Nom --}}
        <td>
            <span class="cell-name">{{ $etudiant->nom }}</span>
        </td>

        {{-- Prénom --}}
        <td>
            <span class="cell-prenom">{{ $etudiant->prenom }}</span>
        </td>

        {{-- Email --}}
        <td>
            <span class="cell-email">
                <i class="fas fa-envelope"></i>
                {{ $etudiant->email ?? '—' }}
            </span>
        </td>

        {{-- Filière --}}
        <td>
            <span class="filiere-tag" title="{{ $etudiant->filiere->nom_filiere ?? '—' }}">
                {{ $etudiant->filiere->nom_filiere ?? '—' }}
            </span>
        </td>

        {{-- Niveau --}}
        <td>
            <span class="level-badge">{{ $etudiant->niveau ?? '—' }}</span>
        </td>

        {{-- Statut --}}
        <td>
            @if($etudiant->statut === 'actif')
                <span class="status-pill actif">
                    <span class="status-dot"></span> Actif
                </span>
            @elseif($etudiant->statut === 'bloque')
                <span class="status-pill bloque">
                    <span class="status-dot"></span> Bloqué
                </span>
            @else
                <span style="color:var(--text-muted);font-size:0.82rem">—</span>
            @endif
        </td>

        {{-- Actions --}}
        @php $eid = $etudiant->getKey(); @endphp
        <td>
            <div class="action-group">
                <a href="{{ route('superadmin.etudiants.show', $eid) }}" class="btn-action" title="Voir">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('superadmin.etudiants.edit', $eid) }}" class="btn-action" title="Modifier">
                    <i class="fas fa-pen"></i>
                </a>
                <form action="{{ route('superadmin.etudiants.destroy', $eid) }}"
                      method="POST"
                      onsubmit="return confirm('Supprimer cet étudiant ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action danger" title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </td>

    </tr>

@empty

    <tr>
        <td colspan="9">
            <div class="empty-state">
                <i class="fas fa-user-graduate"></i>
                <p>Aucun étudiant ne correspond à vos critères de recherche.</p>
            </div>
        </td>
    </tr>

@endforelse
