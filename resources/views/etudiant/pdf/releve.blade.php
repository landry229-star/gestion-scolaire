@extends('etudiant.app')

@section('title', 'Mon Relevé Provisoire')
@section('page-title', 'Historique des Notes & Crédits')

@section('topbar-actions')
    {{-- Bouton de téléchargement PDF ajouté ici --}}
    <a href="{{ route('etudiant.pdf.releve_pdf') }}" class="btn btn-danger btn-sm" style="box-shadow: 0 4px 12px rgba(220, 53, 69, 0.15); display: inline-flex; align-items: center; gap: 6px;">
        <i class="ti ti-file-type-pdf" style="font-size: 16px;"></i> Télécharger le PDF
    </a>
@endsection

@section('content')

@php
    // Déclaration de la fonction sous forme de Closure pour Blade
    $getMentionUac = function($note) {
        if ($note >= 16) return ['Très Bien', 'mention-tb2'];
        if ($note >= 14) return ['Bien', 'mention-bi'];
        if ($note >= 12) return ['Assez Bien', 'mention-abc'];
        if ($note >= 10) return ['Passable', 'mention-pas'];
        return ['Ajourné', 'mention-tb'];
    };

    // Regroupement par Année Académique, puis par Semestre
    $cursusParAnnee = $notes->groupBy(function($note) {
        return $note->inscription->annee_academique ?? $note->annee_academique ?? 'Année En cours';
    })->sortKeysDesc(); // L'année la plus récente en premier
@endphp

<div style="max-width: 960px; margin: 0 auto; display: flex; flex-direction: column; gap: 32px;">

    {{-- AVERTISSEMENT : RELEVÉ NON OFFICIEL --}}
    <div style="background: #fff8e1; border: 1px solid #ffe082; border-left: 4px solid #ffb300; border-radius: 8px; padding: 16px 20px;">
        <div style="display: flex; gap: 14px; align-items: flex-start; font-size: 13px; color: #b78103; line-height: 1.5;">
            <i class="ti ti-info-circle" style="font-size: 22px; flex-shrink: 0; color: #ffb300;"></i>
            <div>
                <strong>Aperçu personnel du cursus (Non officiel)</strong><br>
                Ce document est un état informatif de vos notes et crédits capitalisés destiné exclusivement à votre consultation personnelle. Le relevé de notes officiel, certifié et signé, doit être obtenu sur demande auprès de la scolarité centrale.
            </div>
        </div>
    </div>

    {{-- FICHE ETUDIANT SYNTHÉTIQUE --}}
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; box-shadow: var(--shadow-sm);">
        <div>
            <span style="font-size: 11px; text-transform: uppercase; color: var(--text-muted); font-weight: 600;">Nom & Prénoms</span>
            <div style="font-size: 14px; font-weight: 700; color: var(--text); margin-top: 2px;">{{ $etudiant->nom_complet ?? $etudiant->nom . ' ' . $etudiant->prenom }}</div>
        </div>
        <div>
            <span style="font-size: 11px; text-transform: uppercase; color: var(--text-muted); font-weight: 600;">Numéro Matricule</span>
            <div style="font-size: 14px; font-weight: 700; color: var(--primary); font-family: monospace; margin-top: 2px;">{{ $etudiant->matricule }}</div>
        </div>
        <div>
            <span style="font-size: 11px; text-transform: uppercase; color: var(--text-muted); font-weight: 600;">Filière Principale</span>
            <div style="font-size: 14px; font-weight: 700; color: var(--text); margin-top: 2px;">{{ $etudiant->filiere?->nom_filiere ?? '—' }}</div>
        </div>
        <div>
            <span style="font-size: 11px; text-transform: uppercase; color: var(--text-muted); font-weight: 600;">Statut Global</span>
            <div style="margin-top: 4px;">
                <span class="mention mention-pas" style="font-size: 10px; font-weight: 700; padding: 3px 8px;">INSCRIPTION CONFIRMÉE</span>
            </div>
        </div>
    </div>

    {{-- BOUCLE PRINCIPALE : PAR ANNÉE ACADÉMIQUE --}}
    @if($notes->isEmpty())
        <div class="card" style="padding: 48px; text-align: center; color: var(--text-muted);">
            <i class="ti ti-notes-off" style="font-size: 48px; opacity: 0.3; display: block; margin-bottom: 12px;"></i>
            Aucun historique de notes enregistré sur votre dossier pour le moment.
        </div>
    @else
        @foreach($cursusParAnnee as $annee => $notesDeLAnnee)
            <div style="display: flex; flex-direction: column; gap: 20px;">

                {{-- Séparateur d'Année --}}
                <div style="display: flex; align-items: center; gap: 12px; margin-top: 8px;">
                    <span style="font-size: 16px; font-weight: 800; color: var(--text); background: var(--neutral-lt); padding: 4px 12px; border-radius: 6px;">
                        <i class="ti ti-archive" style="font-size: 14px; margin-right: 4px;"></i> Année Académique : {{ $annee }}
                    </span>
                    <div style="flex: 1; height: 1px; background: var(--border); border-style: dashed;"></div>
                </div>

                @php
                    // Regroupement par Semestre à l'intérieur de cette année spécifique
                    $semestresDeLAnnee = $notesDeLAnnee->groupBy(function($note) {
                        $sem = $note->matiere?->semestre;
                        if (is_object($sem)) return $sem->code_semestre ?? 'Semestre';
                        if (is_array($sem)) return $sem['code_semestre'] ?? 'Semestre';
                        return $sem ?? 'Semestre Unique';
                    })->sortKeys();
                @endphp

                {{-- AFFICHAGE DES TABLEAUX DE SEMESTRES --}}
                <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                    @foreach($semestresDeLAnnee as $semestreLabel => $notesDuSemestre)
                        <div class="card" style="overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">

                            {{-- En-tête Semestre --}}
                            <div style="background: #fafbfc; padding: 12px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: 700; font-size: 13.5px; color: var(--primary);">
                                    <i class="ti ti-calendar-event"></i> Session : {{ strtoupper($semestreLabel) }}
                                </span>
                                <span style="font-size: 11px; font-weight: 600; color: var(--text-muted); background: var(--bg); padding: 2px 8px; border-radius: 20px;">
                                    {{ $notesDuSemestre->count() }} Unité(s) d'Enseignement
                                </span>
                            </div>

                            {{-- Structure Tableau --}}
                            <div class="table-wrap">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background: rgba(26, 58, 92, 0.02);">
                                            <th style="width: 20%; text-align: left; padding: 10px 16px; font-size: 12px;">Code UE</th>
                                            <th style="width: 50%; text-align: left; padding: 10px 16px; font-size: 12px;">Intitulé de l'Épreuve</th>
                                            <th style="width: 15%; text-align: center; padding: 10px 16px; font-size: 12px;">Note / 20</th>
                                            <th style="width: 15%; text-align: center; padding: 10px 16px; font-size: 12px;">Valeur Crédit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalCrdMatiere = 0; $totalCrdCapitalises = 0; $sommeNotesPonderees = 0;
                                        @endphp
                                        @foreach($notesDuSemestre as $note)
                                            @php
                                                $n = $note->valeur_note;
                                                $cr = $note->matiere?->credit_matiere ?? $note->matiere?->credits ?? $note->matiere?->credit ?? 0;

                                                $totalCrdMatiere += $cr;
                                                $sommeNotesPonderees += ($n * $cr);
                                                if($n >= 10) { $totalCrdCapitalises += $cr; }

                                                $noteColor = $n >= 14 ? 'var(--success)' : ($n >= 10 ? 'var(--primary)' : 'var(--danger)');
                                            @endphp
                                            <tr style="border-bottom: 1px solid var(--border);">
                                                <td style="padding: 12px 16px;">
                                                    <code style="font-family: monospace; font-size: 11.5px; font-weight: 700; color: var(--text-muted); background: var(--neutral-lt); padding: 1px 5px; border-radius: 4px;">
                                                        {{ $note->matiere?->code_matiere ?? $note->matiere?->code ?? 'N/A' }}
                                                    </code>
                                                </td>
                                                <td style="padding: 12px 16px; font-size: 13px; font-weight: 500; color: var(--text);">
                                                    {{ $note->matiere?->nom_matiere ?? 'N/A' }}
                                                </td>
                                                <td style="padding: 12px 16px; text-align: center; font-weight: 700; color: {{ $noteColor }}; font-size: 14px;">
                                                    {{ number_format($n, 2) }}
                                                </td>
                                                <td style="padding: 12px 16px; text-align: center; font-size: 13px; font-weight: 600; color: {{ $n >= 10 ? 'var(--success)' : 'var(--text-muted)' }};">
                                                    {{ $cr }} Crd. <span style="font-size: 10px; font-weight:500; color: var(--text-muted);">{{ $n >= 10 ? '(V)' : '(A)' }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    @php
                                        $moyenneSemestrielle = $totalCrdMatiere > 0 ? ($sommeNotesPonderees / $totalCrdMatiere) : 0;
                                        [$sMention, $sClass] = $getMentionUac($moyenneSemestrielle);
                                    @endphp
                                    <tfoot>
                                        <tr style="background: rgba(240, 242, 245, 0.4); border-top: 1px dashed var(--border);">
                                            <td colspan="2" style="font-weight: 700; font-size: 12px; color: var(--text); padding: 12px 16px;">
                                                RÉSULTATS DE LA SESSION
                                            </td>
                                            <td style="text-align: center; padding: 12px 16px;">
                                                <div style="font-size: 14px; font-weight: 800; color: {{ $moyenneSemestrielle >= 10 ? 'var(--success)' : 'var(--danger)' }};">
                                                    Moy : {{ number_format($moyenneSemestrielle, 2) }}
                                                </div>
                                                <div style="font-size: 10px; font-weight: 700; margin-top: 2px;" class="{{ $sClass }}">{{ $sMention }}</div>
                                            </td>
                                            <td style="text-align: center; padding: 12px 16px;">
                                                <div style="font-size: 13px; font-weight: 700; color: var(--success);">
                                                    {{ $totalCrdCapitalises }} / {{ $totalCrdMatiere }} acq.
                                                </div>
                                                <div style="font-size: 9.5px; color: var(--text-muted); font-weight: 500; margin-top: 2px;">Crédits validés</div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    @endforeach
                </div>

            </div>
        @endforeach
    @endif

</div>

@endsection
