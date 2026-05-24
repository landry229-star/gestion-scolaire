@extends('etudiant.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('topbar-actions')
    @if($inscription && $inscription->statut_dossier !== 'non_soumis')

        {{-- 1. Bouton Pré-inscription : Visible si 'en_cours' OU 'valide' --}}
        @if($inscription->statut_dossier === 'en_cours' || $inscription->statut_dossier === 'valide')
            <a href="{{ route('etudiant.pdf.pre_inscription') }}" class="btn btn-primary btn-sm me-2" target="_blank">
                <i class="ti ti-download"></i> Fiche de Pré-inscription (PDF)
            </a>
        @endif

        {{-- 2. Bouton Inscription Définitive : Uniquement si 'valide' --}}
        @if($inscription->statut_dossier === 'valide')
            <a href="{{ route('etudiant.pdf.inscription_definitive') }}" class="btn btn-success btn-sm" target="_blank" style="box-shadow: 0 4px 12px rgba(46, 204, 113, 0.15);">
                <i class="ti ti-file-check"></i> Fiche d'Inscription Validée (PDF)
            </a>
        @endif

    @endif
@endsection

@section('content')

{{-- 1. BANNIÈRE D'ÉTAT DU DOSSIER --}}
<div style="margin-bottom: 24px;">
    @if($inscription->statut_dossier === 'non_soumis')
        <div class="alert alert-warning" style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="ti ti-alert-triangle" style="font-size: 24px;"></i>
                <div>
                    <strong>Inscription non finalisée !</strong>
                    @if($inscriptionOuverte)
                        Votre dossier n'a pas encore été soumis pour l'année en cours. Veuillez remplir vos informations.
                    @else
                        Les inscriptions sur la plateforme sont actuellement fermées ou suspendues par la scolarité.
                    @endif
                </div>
            </div>
            @if($inscriptionOuverte)
                <a href="{{ route('etudiant.dossier') }}" class="btn btn-primary btn-sm" style="background: var(--warning); color: #000; border: none;">
                    <i class="ti ti-edit"></i> Remplir mon dossier
                </a>
            @endif
        </div>
    @elseif($inscription->statut_dossier === 'en_attente')
        <div class="alert alert-warning" style="border-left: 4px solid var(--accent);">
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <i class="ti ti-clock" style="font-size: 24px; color: var(--accent);"></i>
                <div>
                    <strong>Dossier en cours d'examen.</strong> Votre demande d'inscription pour l'année <strong>{{ $inscription->annee_academique }}</strong> a bien été reçue et est en cours de traitement par les services de la scolarité.
                    <div style="margin-top: 10px;">
                        <a href="{{ route('etudiant.pdf.pre_inscription') }}" target="_blank" class="btn btn-outline btn-sm" style="font-size: 11px; padding: 4px 10px;">
                            <i class="ti ti-file-text"></i> Voir ma fiche de pré-inscription
                        </a>
                        <a href="{{ route('etudiant.dossier') }}" style="margin-left: 12px; font-size: 12px; color: var(--text); text-decoration: underline;">
                            <i class="ti ti-edit"></i> Modifier mes informations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif($inscription->statut_dossier === 'rejete')
        <div class="alert alert-error" style="background: rgba(var(--danger-rgb), 0.1); border-left: 4px solid var(--danger);">
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <i class="ti ti-circle-x" style="font-size: 24px; color: var(--danger);"></i>
                <div>
                    <strong>Dossier rejeté par la scolarité !</strong>
                    <p style="margin: 4px 0 10px 0; color: var(--text-muted);">
                        <strong style="color: var(--text);">Motif :</strong> {{ $inscription->motif_rejet ?? 'Aucun motif spécifié. Veuillez revoir vos pièces jointes.' }}
                    </p>
                    <a href="{{ route('etudiant.dossier') }}" class="btn btn-danger btn-sm">
                        <i class="ti ti-refresh"></i> Corriger et soumettre à nouveau
                    </a>
                </div>
            </div>
        </div>
    @elseif($inscription->statut_dossier === 'valide')
        <div class="alert alert-success" style="background: rgba(var(--success-rgb), 0.1); border-left: 4px solid var(--success);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="ti ti-circle-check" style="font-size: 24px; color: var(--success);"></i>
                <div>
                    <strong>Félicitations !</strong> Votre dossier d'inscription a été officiellement validé pour l'année académique <strong>{{ $inscription->annee_academique }}</strong>. Vos accès aux cours et examens sont entièrement actifs.
                </div>
            </div>
        </div>
    @endif
</div>

{{-- 2. CARTES DES STATISTIQUES RAPIDES (Centrées et alignées sans crash flex) --}}
<div class="stat-cards" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 32px;">

    {{-- Carte 1 : Cursus / Régime --}}
    <div style="background: var(--card-bg); border-radius: 12px; padding: 20px; border: 1px solid var(--border); display: flex; align-items: center; gap: 16px; box-shadow: var(--shadow-sm);">
        <div style="background: var(--primary-lt); color: var(--primary); width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
            <i class="ti ti-school"></i>
        </div>
        <div>
            <div style="font-size: 18px; font-weight: 700; color: var(--text); line-height: 1.2;">
                {{ $inscription->id_filiere ? ($inscription->niveau . ' - ' . ($inscription->filiere->code ?? 'LMD')) : 'Non inscrit' }}
            </div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px; font-weight: 500;">
                {{ $inscription->regime === 'cours_du_jour' ? 'Régime Jour (A)' : ($inscription->regime === 'cours_du_soir' ? 'Régime Soir (B)' : 'Filière & Niveau') }}
            </div>
        </div>
    </div>

    {{-- Carte 2 : Moyenne générale --}}
    <div style="background: var(--card-bg); border-radius: 12px; padding: 20px; border: 1px solid var(--border); display: flex; align-items: center; gap: 16px; box-shadow: var(--shadow-sm);">
        <div style="background: var(--success-lt); color: var(--success); width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
            <i class="ti ti-award"></i>
        </div>
        <div>
            <div style="font-size: 22px; font-weight: 700; color: var(--success); line-height: 1.2;">
                {{ $moyenne !== null ? number_format($moyenne, 2) : '—' }}<span style="font-size: 12px; color: var(--text-muted); font-weight: 400;">/20</span>
            </div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px; font-weight: 500;">
                @if($noteEnCours)
                    <i class="ti ti-hourglass" style="font-size: 11px;"></i> Saisie en cours
                @else
                    Moyenne générale
                @endif
            </div>
        </div>
    </div>

    {{-- Carte 3 : Paiement Banque --}}
    <div style="background: var(--card-bg); border-radius: 12px; padding: 20px; border: 1px solid var(--border); display: flex; align-items: center; gap: 16px; box-shadow: var(--shadow-sm);">
        <div style="background: var(--neutral-lt); color: var(--text); width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
            <i class="ti ti-wallet"></i>
        </div>
        <div>
            <div style="font-size: 15px; font-weight: 700; color: var(--text); text-transform: uppercase; line-height: 1.2;">
                {{ $inscription->reference_paiement ? Str::limit($inscription->reference_paiement, 12) : '—' }}
            </div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px; font-weight: 500;">
                Banque : <span style="font-weight: 600; color: var(--primary);">{{ ucfirst($inscription->statut_financier ?? 'Aucun') }}</span>
            </div>
        </div>
    </div>
</div>

{{-- 3. ZONE PRINCIPALE DE CONTENU --}}
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">

    {{-- COLONNE GAUCHE : RÉSUMÉ DES NOTES NETTOYÉ (STYLE UAC) --}}
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <div class="card" style="background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border); overflow: hidden; box-shadow: var(--shadow-sm);">
            <div class="card-header" style="padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #fafbfc;">
                <div class="card-title" style="font-size: 14px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 8px;">
                    <i class="ti ti-notes" style="color: var(--primary); font-size: 18px;"></i> Dernières évaluations publiées
                </div>
                @if($inscription->statut_dossier === 'valide')
                    <a href="{{ route('etudiant.notes') }}" style="font-size: 12px; color: var(--primary); font-weight: 600; display: flex; align-items: center; gap: 2px;">
                        Relevé complet <i class="ti ti-arrow-right"></i>
                    </a>
                @endif
            </div>

            <div class="card-body" style="padding: 0;">
                @if($inscription->statut_dossier !== 'valide')
                    <div style="text-align: center; padding: 48px 20px; color: var(--text-muted); font-size: 13px;">
                        <i class="ti ti-lock" style="font-size: 36px; display: block; margin-bottom: 10px; color: var(--text-muted); opacity: 0.4;"></i>
                        Les notes seront débloquées dès la validation définitive de votre dossier.
                    </div>
                @elseif($notes->isEmpty())
                    <div style="text-align: center; padding: 48px 20px; color: var(--text-muted); font-size: 13px;">
                        <i class="ti ti-notes-off" style="font-size: 36px; display: block; margin-bottom: 10px; color: var(--text-muted); opacity: 0.4;"></i>
                        Aucun résultat disponible pour le moment.
                    </div>
                @else
                    <div style="display: flex; flex-direction: column;">
                        @foreach($notes->take(4) as $note)
                            @php
                                $nVal = $note->valeur_note;
                                $credit = $note->matiere?->credit_matiere ?? $note->matiere?->credits ?? $note->matiere?->credit ?? 0;
                                $noteColor = $nVal >= 14 ? 'var(--success)' : ($nVal >= 10 ? 'var(--primary)' : 'var(--danger)');
                            @endphp
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--border); transition: background 0.2s ease;">
                                <div style="display: flex; flex-direction: column; gap: 4px; max-width: 75%;">
                                    <div style="font-size: 14px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $note->matiere->nom_matiere ?? 'Matière' }}
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <code style="font-family: monospace; font-size: 11px; font-weight: 700; color: var(--text-muted); background: var(--neutral-lt); padding: 1px 5px; border-radius: 4px;">
                                            {{ $note->matiere?->code_matiere ?? $note->matiere?->code ?? 'N/A' }}
                                        </code>
                                        <span style="font-size: 11px; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 2px;">
                                            <i class="ti ti-database" style="font-size: 12px;"></i> Value : <strong>{{ $credit }} Crd.</strong>
                                        </span>
                                    </div>
                                </div>
                                <div style="text-align: right; flex-shrink: 0;">
                                    <div style="font-size: 16px; font-weight: 800; color: {{ $noteColor }};">
                                        {{ number_format($nVal, 2) }}
                                    </div>
                                    <div style="font-size: 10px; color: var(--text-muted); font-weight: 500; text-transform: uppercase; margin-top: 2px;">
                                        {{ $nVal >= 10 ? 'Validé' : 'Ajourné' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- COLONNE DROITE : AGENDA DES EXAMENS --}}
    <div>
        <div class="card" style="background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border); overflow: hidden; box-shadow: var(--shadow-sm);">
            <div class="card-header" style="padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #fafbfc;">
                <div class="card-title" style="font-size: 14px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 8px;">
                    <i class="ti ti-calendar-event" style="color: var(--danger); font-size: 18px;"></i> Agenda des Examens
                </div>
            </div>

            <div class="card-body" style="padding: 16px 20px;">
                @if($inscription->statut_dossier !== 'valide')
                    <div style="text-align: center; padding: 24px 0; color: var(--text-muted); font-size: 12.5px;">
                        En attente de validation du dossier.
                    </div>
                @elseif(empty($prochainExamens) || count($prochainExamens) === 0)
                    <div style="text-align: center; padding: 24px 0; color: var(--text-muted); font-size: 12.5px;">
                        <i class="ti ti-calendar-off" style="font-size: 26px; display: block; margin-bottom: 8px; color: var(--text-muted); opacity: 0.4;"></i>
                        Aucune épreuve planifiée.
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        @foreach($prochainExamens as $ex)
                            @php $exDate = \Carbon\Carbon::parse($ex->date_examen); @endphp
                            <div style="display: flex; align-items: center; gap: 12px; background: var(--neutral-lt); padding: 10px 12px; border-radius: 8px;">
                                <div style="background: var(--card-bg); border: 1px solid var(--border); border-radius: 6px; padding: 6px; min-width: 44px; text-align: center; line-height: 1.1;">
                                    <div style="font-size: 15px; font-weight: 700; color: var(--text);">{{ $exDate->format('d') }}</div>
                                    <div style="font-size: 9px; text-transform: uppercase; color: var(--text-muted); margin-top: 1px;">{{ $exDate->translatedFormat('M') }}</div>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-size: 12.5px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $ex->nom_examen ?? $ex->matiere->nom_matiere ?? 'Épreuve' }}
                                    </div>
                                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px; display: flex; align-items: center; gap: 8px;">
                                        <span><i class="ti ti-clock"></i> {{ $exDate->format('H:i') }}</span>
                                        @if($ex->salle)
                                            <span><i class="ti ti-door"></i> {{ $ex->salle }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('etudiant.examens') }}" class="btn btn-outline btn-sm" style="width: 100%; text-align: center; margin-top: 14px; font-size: 11.5px; font-weight: 600;">
                        Voir tout le calendrier
                    </a>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
