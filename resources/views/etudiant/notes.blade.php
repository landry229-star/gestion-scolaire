@extends('etudiant.app')

@section('title', 'Mes notes & Crédits')
@section('page-title', 'Cursus Académique & Notes')

@section('topbar-actions')
    @if(!$noteEnCours && $notes->isNotEmpty())
        <a href="{{ route('etudiant.pdf.releve') }}" class="btn btn-primary btn-sm" target="_blank" style="box-shadow: 0 4px 12px rgba(26,58,92,.15);">
            <i class="ti ti-download"></i> Télécharger le relevé (PDF)
        </a>
    @endif
@endsection

@section('content')

{{-- 1. ALERTE DE PUBLICATION --}}
@if($noteEnCours)
    <div class="alert alert-warning" style="border-left: 4px solid var(--warning); background: var(--warning-lt); margin-bottom: 24px;">
        <i class="ti ti-clock" style="font-size: 20px;"></i>
        <div>
            <strong>Saisie des notes en cours.</strong> Les délibérations sont en cours. Votre relevé final sera téléchargeable dès clôture du semestre.
        </div>
    </div>
@endif

@php
    if (!function_exists('getMentionUac')) {
        function getMentionUac($note) {
            if ($note >= 16) return ['Très Bien', 'mention-tb2'];
            if ($note >= 14) return ['Bien', 'mention-bi'];
            if ($note >= 12) return ['Assez Bien', 'mention-abc'];
            if ($note >= 10) return ['Passable', 'mention-pas'];
            return ['Ajourné', 'mention-tb'];
        }
    }

    // Groupement et nettoyage du nom du semestre pour éviter le bug de l'objet JSON complet
    $notesParSemestre = $notes->groupBy(function($note) {
        $sem = $note->matiere?->semestre;
        if (is_object($sem)) {
            return $sem->code_semestre ?? $sem->description ?? 'Semestre Inconnu';
        }
        if (is_array($sem)) {
            return $sem['code_semestre'] ?? $sem['description'] ?? 'Semestre Inconnu';
        }
        if (is_string($sem) && str_contains($sem, '{')) {
            $decoded = json_decode($sem, true);
            return $decoded['code_semestre'] ?? $decoded['description'] ?? 'Semestre';
        }
        return $sem ?? 'Semestre Unique';
    })->sortKeys();
@endphp

{{-- 2. EN-TÊTE : CARTES DES STATISTIQUES ALIGNÉES ET CENTRÉES --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 32px;">

    {{-- Moyenne Générale --}}
    <div style="background: var(--surface); border-radius: 12px; padding: 20px; border: 1px solid var(--border); border-bottom: 4px solid var(--success); display: flex; align-items: center; justify-content: center; gap: 16px; box-shadow: var(--shadow-sm);">
        <div style="background: rgba(46, 204, 113, 0.1); color: var(--success); width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
            <i class="ti ti-chart-arrows"></i>
        </div>
        <div style="text-align: left;">
            <div style="font-size: 22px; font-weight: 800; color: var(--text); line-height: 1.2;">{{ $moyenne !== null ? number_format($moyenne, 2) : '—' }}<span style="font-size: 12px; color: var(--text-muted); font-weight: 500;">/20</span></div>
            <div style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-top: 2px; letter-spacing: 0.5px;">Moyenne Générale</div>
        </div>
    </div>

    {{-- Unités d'Enseignement --}}
    <div style="background: var(--surface); border-radius: 12px; padding: 20px; border: 1px solid var(--border); border-bottom: 4px solid var(--primary); display: flex; align-items: center; justify-content: center; gap: 16px; box-shadow: var(--shadow-sm);">
        <div style="background: rgba(26, 58, 92, 0.1); color: var(--primary); width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
            <i class="ti ti-book-2"></i>
        </div>
        <div style="text-align: left;">
            <div style="font-size: 22px; font-weight: 800; color: var(--text); line-height: 1.2;">{{ $stats['total'] }}</div>
            <div style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-top: 2px; letter-spacing: 0.5px;">Matières Evaluées</div>
        </div>
    </div>

    {{-- Matières Validées --}}
    <div style="background: var(--surface); border-radius: 12px; padding: 20px; border: 1px solid var(--border); border-bottom: 4px solid #0d47a1; display: flex; align-items: center; justify-content: center; gap: 16px; box-shadow: var(--shadow-sm);">
        <div style="background: #e3f2fd; color: #0d47a1; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
            <i class="ti ti-circle-check"></i>
        </div>
        <div style="text-align: left;">
            <div style="font-size: 22px; font-weight: 800; color: #0d47a1; line-height: 1.2;">{{ $stats['admis'] }}</div>
            <div style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-top: 2px; letter-spacing: 0.5px;">Matières Validées</div>
        </div>
    </div>

    {{-- Matières Renvoyées --}}
    <div style="background: var(--surface); border-radius: 12px; padding: 20px; border: 1px solid var(--border); border-bottom: 4px solid {{ ($stats['total'] - $stats['admis']) > 0 ? 'var(--danger)' : 'var(--success)' }}; display: flex; align-items: center; justify-content: center; gap: 16px; box-shadow: var(--shadow-sm);">
        <div style="background: {{ ($stats['total'] - $stats['admis']) > 0 ? 'rgba(231, 76, 60, 0.1)' : 'rgba(46, 204, 113, 0.1)' }}; color: {{ ($stats['total'] - $stats['admis']) > 0 ? 'var(--danger)' : 'var(--success)' }}; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
            <i class="ti ti-{{ ($stats['total'] - $stats['admis']) > 0 ? 'alert-circle' : 'award' }}"></i>
        </div>
        <div style="text-align: left;">
            <div style="font-size: 22px; font-weight: 800; color: var(--text); line-height: 1.2;">{{ $stats['total'] - $stats['admis'] }}</div>
            <div style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-top: 2px; letter-spacing: 0.5px;">Ajournée(s)</div>
        </div>
    </div>
</div>

{{-- 3. GRAPHIQUE & TABLEAU STYLE UAC --}}
<div style="display: grid; grid-template-columns: 1fr 2.4fr; gap: 24px; align-items: start;">

    {{-- BARRES DE DISTRIBUTION (GAUCHE) --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title" style="font-size:13px;"><i class="ti ti-chart-bar" style="color: var(--primary);"></i> Distribution des notes</div>
        </div>
        <div class="card-body">
            @if($notes->isEmpty())
                <div style="text-align:center; color:var(--text-muted); font-size:13px; padding: 20px 0;">Aucun graphe disponible</div>
            @else
                <div style="display:flex; flex-direction:column; gap:14px;">
                    @foreach($notes->sortByDesc('valeur_note') as $note)
                        @php
                            $n = $note->valeur_note;
                            $pct = ($n / 20) * 100;
                            $barColor = $n >= 14 ? 'var(--success)' : ($n >= 10 ? 'var(--primary)' : 'var(--danger)');
                        @endphp
                        <div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:4px; font-size:12px;">
                                <span style="color:var(--text); font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:70%;">
                                    {{ $note->matiere?->nom_matiere ?? 'N/A' }}
                                </span>
                                <span style="color:{{ $barColor }}; font-weight:700;">{{ number_format($n, 2) }}</span>
                            </div>
                            <div style="background:var(--neutral-lt); border-radius:20px; height:6px; overflow:hidden;">
                                <div style="background:{{ $barColor }}; width:{{ $pct }}%; height:100%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- TABLEAU DETAILLÉ DES SEMESTRES (SANS COEFFICIENT) --}}
    <div style="display: flex; flex-direction: column; gap: 24px;">
        @if($notes->isEmpty())
            <div class="card" style="padding: 48px; text-align:center; color:var(--text-muted);">
                <i class="ti ti-notes-off" style="font-size:44px; display:block; margin-bottom:12px; opacity:.3;"></i>
                Aucun résultat pédagogique publié.
            </div>
        @else
            @foreach($notesParSemestre as $semestreName => $notesDuSemestre)
                <div class="card" style="overflow: hidden; box-shadow: var(--shadow-sm);">
                    <div class="card-header" style="background: #fafbfc; padding: 14px 20px; border-bottom: 1px solid var(--border);">
                        <div class="card-title" style="color: var(--primary); font-size: 14px; font-weight: 700;">
                            <i class="ti ti-calendar-event" style="margin-right: 4px;"></i> Semestre : {{ $semestreName }}
                        </div>
                        <span class="status-badge" style="background: var(--primary-lt); color: var(--primary); font-size: 11px; font-weight: 600;">
                            {{ $notesDuSemestre->count() }} UE engagée(s)
                        </span>
                    </div>

                    <div class="table-wrap">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="width: 20%; text-align: left; padding: 12px 16px;">Code UE</th>
                                    <th style="width: 45%; text-align: left; padding: 12px 16px;">Intitulé de la Matière</th>
                                    <th style="text-align:center; width: 15%; padding: 12px 16px;">Note /20</th>
                                    <th style="text-align:center; width: 10%; padding: 12px 16px;">Crédits</th>
                                    <th style="text-align:center; width: 10%; padding: 12px 16px;">Mention</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $semCreditsPonderes = 0; $totalCreditsMatiere = 0; $totalCreditsCapitalises = 0;
                                @endphp
                                @foreach($notesDuSemestre as $note)
                                    @php
                                        $n = $note->valeur_note;

                                        // Récupération dynamique du crédit
                                        $credit = $note->matiere?->credit_matiere ?? $note->matiere?->credits ?? $note->matiere?->credit ?? 0;

                                        $totalCreditsMatiere += $credit;
                                        $semCreditsPonderes += ($n * $credit);

                                        // Calcul des crédits capitalisés (validés si Note >= 10)
                                        if($n >= 10) {
                                            $totalCreditsCapitalises += $credit;
                                        }

                                        [$mention, $mentionClass] = getMentionUac($n);
                                        $noteColor = $n >= 14 ? 'var(--success)' : ($n >= 10 ? 'var(--primary)' : 'var(--danger)');
                                    @endphp
                                    <tr style="border-bottom: 1px solid var(--border);">
                                        <td style="padding: 14px 16px;">
                                            <code style="font-family: monospace; font-size:12px; font-weight:700; color:var(--text-muted); background: var(--bg); padding: 2px 6px; border-radius: 4px;">
                                                {{ $note->matiere?->code_matiere ?? $note->matiere?->code ?? 'N/A' }}
                                            </code>
                                        </td>
                                        <td style="padding: 14px 16px;">
                                            <div style="font-weight: 600; color: var(--text);">{{ $note->matiere?->nom_matiere ?? 'N/A' }}</div>
                                        </td>
                                        <td style="text-align:center; padding: 14px 16px;">
                                            <span style="font-size:15px; font-weight:700; color:{{ $noteColor }};">
                                                {{ number_format($n, 2) }}
                                            </span>
                                        </td>
                                        <td style="text-align:center; padding: 14px 16px; font-weight: 600; color: {{ $n >= 10 ? 'var(--success)' : 'var(--text-muted)' }};">
                                            {{ $credit }}
                                        </td>
                                        <td style="text-align:center; padding: 14px 16px;">
                                            <span class="mention {{ $mentionClass }}">{{ $mention }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            @php
                                // Calcul de la moyenne semestrielle pondérée par les crédits (Système LMD type UAC)
                                $idMoyenneSemestrielle = $totalCreditsMatiere > 0 ? ($semCreditsPonderes / $totalCreditsMatiere) : 0;
                                [$sMention, $sClass] = getMentionUac($idMoyenneSemestrielle);
                            @endphp
                            <tfoot>
                                <tr style="background: rgba(240, 242, 245, 0.5); border-top: 2px solid var(--border);">
                                    <td colspan="2" style="font-weight:700; font-size:12px; color: var(--primary); padding: 14px 16px; text-transform: uppercase;">
                                        SYNTHÈSE DU SEMESTRE
                                    </td>
                                    <td style="text-align:center; padding: 14px 16px;">
                                        <span style="font-size:15px; font-weight:700; color:{{ $idMoyenneSemestrielle >= 10 ? 'var(--success)' : 'var(--danger)' }};">
                                            {{ number_format($idMoyenneSemestrielle, 2) }}
                                        </span>
                                    </td>
                                    <td style="text-align:center; padding: 14px 16px; font-weight:700; color: var(--success);">
                                        {{ $totalCreditsCapitalises }} / {{ $totalCreditsMatiere }} Crd.
                                    </td>
                                    <td style="text-align:center; padding: 14px 16px;">
                                        <span class="mention {{ $sClass }}">{{ $sMention }}</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</div>

@endsection
