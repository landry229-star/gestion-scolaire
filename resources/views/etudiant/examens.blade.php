@extends('etudiant.app')

@section('title', 'Mes examens')
@section('page-title', 'Calendrier des examens')

@section('content')

@if($examens->isEmpty())
    <div class="card" style="text-align:center; padding: 60px 40px;">
        <i class="ti ti-calendar-off" style="font-size:52px; color:var(--border); display:block; margin-bottom:14px;"></i>
        <div style="font-size:16px; font-weight:500; color:var(--text-muted);">Aucun examen programmé</div>
        <div style="font-size:13px; color:var(--text-muted); margin-top:6px;">
            Les examens de votre filière apparaîtront ici dès leur planification.
        </div>
    </div>
@else
    @foreach(collect($examens) as $moisKey => $examensParMois)
        @php
            $moisLabel = \Carbon\Carbon::createFromFormat('Y-m', $moisKey)->translatedFormat('F Y');
            $moisLabel = ucfirst($moisLabel);
        @endphp

        <div style="margin-bottom: 28px;">
            <div style="font-size:13px; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:.08em; margin-bottom:12px; padding-bottom:8px; border-bottom:2px solid var(--border);">
                <i class="ti ti-calendar" style="font-size:14px;"></i>
                {{ $moisLabel }}
            </div>

            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($examensParMois as $examen)
                    @php
                        $date       = \Carbon\Carbon::parse($examen->date_examen);
                        $isPast     = $date->isPast();
                        $isToday    = $date->isToday();
                        $isSoon     = $date->isFuture() && $date->diffInDays(now()) <= 7;
                        $resultat   = $resultats[$examen->id_examen] ?? null;
                    @endphp

                    <div class="card" style="border-left: 4px solid {{ $isToday ? 'var(--danger)' : ($isSoon ? 'var(--accent)' : ($isPast ? 'var(--border)' : 'var(--primary)')) }}; opacity: {{ $isPast && !$resultat ? '.7' : '1' }};">
                        <div class="card-body" style="padding: 14px 18px; display:flex; align-items:center; gap:18px;">

                            {{-- Date --}}
                            <div style="background: {{ $isToday ? 'var(--danger-lt)' : ($isPast ? 'var(--neutral-lt)' : 'var(--primary-lt)') }}; border-radius:10px; padding:10px 14px; text-align:center; min-width:56px; flex-shrink:0;">
                                <div style="font-size:22px; font-weight:700; color: {{ $isToday ? 'var(--danger)' : ($isPast ? 'var(--text-muted)' : 'var(--primary)') }}; line-height:1;">
                                    {{ $date->format('d') }}
                                </div>
                                <div style="font-size:11px; text-transform:uppercase; letter-spacing:.06em; color: {{ $isPast ? 'var(--text-muted)' : 'var(--primary)' }}; margin-top:2px;">
                                    {{ $date->translatedFormat('M') }}
                                </div>
                            </div>

                            {{-- Infos examen --}}
                            <div style="flex:1; min-width:0;">
                                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:4px;">
                                    <span style="font-size:15px; font-weight:600; color:var(--text);">
                                        {{ $examen->nom_examen ?? $examen->matiere?->nom_matiere ?? 'Examen' }}
                                    </span>
                                    @if($isToday)
                                        <span class="status-badge en_attente" style="font-size:11px; padding: 2px 8px;">
                                            <i class="ti ti-circle-filled" style="font-size:7px;"></i> Aujourd'hui
                                        </span>
                                    @elseif($isSoon)
                                        <span style="background:var(--accent-lt); color:var(--accent); font-size:11px; padding:2px 8px; border-radius:20px; font-weight:500;">
                                            Dans {{ $date->diffInDays(now()) }}j
                                        </span>
                                    @elseif($isPast)
                                        <span class="status-badge non_soumis" style="font-size:11px; padding:2px 8px;">Passé</span>
                                    @endif
                                </div>

                                <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap; font-size:12.5px; color:var(--text-muted);">
                                    <span><i class="ti ti-clock" style="font-size:13px;"></i> {{ $date->format('H:i') }}</span>
                                    @if($examen->salle)
                                        <span><i class="ti ti-door" style="font-size:13px;"></i> Salle {{ $examen->salle }}</span>
                                    @endif
                                    @if($examen->duree)
                                        <span><i class="ti ti-hourglass" style="font-size:13px;"></i> {{ $examen->duree }} min</span>
                                    @endif
                                    @if($examen->matiere?->coefficient)
                                        <span><i class="ti ti-multiplier-2x" style="font-size:13px;"></i> Coeff. {{ $examen->matiere->coefficient }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Résultat si disponible --}}
                            @if($resultat)
                                @php
                                    $note = $resultat->note ?? null;
                                    $noteColor = $note >= 10 ? 'var(--success)' : 'var(--danger)';
                                @endphp
                                <div style="text-align:right; flex-shrink:0; border-left:1px solid var(--border); padding-left:18px;">
                                    @if($note !== null)
                                        <div style="font-size:24px; font-weight:700; color:{{ $noteColor }}; line-height:1.1;">
                                            {{ number_format($note, 2) }}
                                        </div>
                                        <div style="font-size:11px; color:var(--text-muted);">/20</div>
                                    @else
                                        <div style="font-size:12px; color:var(--text-muted);">En attente</div>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    {{-- Légende --}}
    <div style="display:flex; align-items:center; gap:20px; flex-wrap:wrap; margin-top:8px; padding-top:16px; border-top:1px solid var(--border); font-size:12px; color:var(--text-muted);">
        <span style="display:flex; align-items:center; gap:6px;">
            <span style="width:12px; height:12px; border-radius:3px; background:var(--primary);"></span> À venir
        </span>
        <span style="display:flex; align-items:center; gap:6px;">
            <span style="width:12px; height:12px; border-radius:3px; background:var(--accent);"></span> Dans 7 jours
        </span>
        <span style="display:flex; align-items:center; gap:6px;">
            <span style="width:12px; height:12px; border-radius:3px; background:var(--danger);"></span> Aujourd'hui
        </span>
        <span style="display:flex; align-items:center; gap:6px;">
            <span style="width:12px; height:12px; border-radius:3px; background:var(--border);"></span> Passé
        </span>
    </div>
@endif

@endsection
