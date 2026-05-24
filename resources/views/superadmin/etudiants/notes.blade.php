@extends('layouts.superadmin')

@section('title', 'Notes de l\'étudiant')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;500;600;700&family=Syne:wght=700&display=swap" rel="stylesheet">

<style>
:root {
    --bg-main: #f4f6fa;
    --bg-card: #ffffff;
    --text-heading: #090d16;
    --text-body: #475569;
    --text-muted: #94a3b8;
    --border-color: #f1f5f9;
    --primary: #3b82f6;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
}

body, .container-fluid {
    background-color: var(--bg-main);
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-body);
}

.notes-container { max-width: 1000px; margin: 0 auto; }

.notes-card {
    background: var(--bg-card); border-radius: 16px; border: 1px solid var(--border-color);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); padding: 24px; margin-top: 20px;
}

.notes-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 20px; margin-bottom: 24px; gap: 16px; }
.notes-title { font-family: 'Syne', sans-serif; font-weight: 700; color: var(--text-heading); font-size: 1.3rem; margin: 0; }

.moyenne-badge {
    background: #eff6ff; border: 1px solid #dbeafe; color: var(--primary);
    padding: 10px 20px; border-radius: 12px; font-size: 15px; font-weight: 700;
}
.moyenne-badge.recale { background: #fef2f2; border: 1px solid #fee2e2; color: var(--danger); }

.semester-divider {
    background: #f8fafc; color: var(--text-heading); font-size: 0.8rem;
    font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; padding: 10px 16px !important;
}

.custom-table { width: 100%; border-collapse: collapse; }
.custom-table th { background-color: #fafafa; color: var(--text-body); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; padding: 14px 16px; border-bottom: 1px solid var(--border-color); }
.custom-table td { padding: 14px 16px; vertical-align: middle; font-size: 0.875rem; border-bottom: 1px solid var(--border-color); }

.code-text {
    color: #475569;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.02em;
}

.note-value { font-size: 15px; font-weight: 700; }
.note-value.valide { color: var(--success); }
.note-value.echec { color: var(--danger); }

.status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; border-radius: 20px; }
.status-badge.published { background: rgba(16, 185, 129, 0.08); color: var(--success); }
.status-badge.draft { background: rgba(245, 158, 11, 0.08); color: var(--warning); }
.status-dot { width: 6px; height: 6px; border-radius: 50%; background-color: currentColor; }

.btn-back { background: #ffffff; color: var(--text-body); border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; margin-top: 20px; }
</style>

<div class="container-fluid py-4">
    <div class="notes-container">

        @php
            $total = 0; $credit_matiere = 0;
            foreach($notes as $note) {
                $coef = $note->matiere->credit_matiere ?? 1;
                $total += $note->valeur_note * $coef;
                $credit_matiere += $coef;
            }
            $moyenne = $credit_matiere > 0 ? round($total / $credit_matiere, 2) : 0;

            // Regrouper par semestre
            $notesGroupees = $notes->groupBy(function($item) {
                $sem = $item->matiere->semestre;
                if (is_object($sem) || is_array($sem)) {
                    return $sem['description'] ?? $sem['code_semestre'] ?? 'Semestre Indéfini';
                }
                return $sem ?? 'Semestre Unique';
            });
        @endphp

        <div class="notes-card">
            <div class="notes-header">
                <div>
                    <h5 class="notes-title">Carnet de Notes de {{ $etudiant->nom }} {{ $etudiant->prenom }}</h5>
                    <small class="text-muted">Consulter le relevé de notes officiel de l'étudiant</small>
                </div>
                <div class="moyenne-badge {{ $moyenne < 10 ? 'recale' : '' }}">
                    Moyenne Générale : {{ number_format($moyenne, 2) }} / 20
                </div>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Code</th>
                            <th style="width: 45%;">Matière</th>
                            <th class="text-center" style="width: 15%;">Note / 20</th>
                            <th class="text-center" style="width: 10%;">Crédits</th>
                            <th style="width: 15%;">Session</th>
                            <th style="width: 10%;">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notesGroupees as $semestreLabel => $notesDuSemestre)
                            <tr>
                                <td colspan="6" class="semester-divider">
                                    <i class="fas fa-bookmark me-2 text-primary"></i>
                                    {{ str_contains($semestreLabel, '{') ? 'Semestre Concerné' : $semestreLabel }}
                                </td>
                            </tr>

                            @foreach($notesDuSemestre as $note)
                                @php $coef = $note->matiere->credit_matiere ?? 1; @endphp
                                <tr>
                                    <td class="code-text">
                                        {{ $note->matiere->code_matiere ?? '---' }}
                                    </td>
                                    <td class="fw-semibold text-dark">
                                        {{ $note->matiere->nom_matiere ?? '---' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="note-value {{ $note->valeur_note >= 10 ? 'valide' : 'echec' }}">
                                            {{ number_format($note->valeur_note, 2) }}
                                        </span>
                                    </td>
                                    <td class="text-center text-secondary fw-medium">{{ $coef }}</td>
                                    <td class="text-muted" style="font-size: 0.8rem; text-transform: capitalize;">{{ $note->session ?? 'Normale' }}</td>
                                    <td>
                                        <span class="status-badge {{ $note->is_published ? 'published' : 'draft' }}">
                                            <span class="status-dot"></span>
                                            {{ $note->is_published ? 'Publié' : 'Brouillon' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    Aucune note enregistrée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <a href="{{ url()->previous() }}" class="btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
    </div>
</div>

@endsection
