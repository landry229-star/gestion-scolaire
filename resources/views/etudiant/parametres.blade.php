@extends('etudiant.app')

@section('title', 'Paramètres')
@section('page-title', 'Configuration du compte')

@section('content')

<div style="max-width: 720px; display: flex; flex-direction: column; gap: 24px;">

    {{-- 1. IDENTITÉ & SITUATION ACADÉMIQUE --}}
    <div class="card" style="overflow: hidden; box-shadow: var(--shadow-sm);">
        <div class="card-header" style="background: linear-gradient(to right, #fafbfc, #ffffff); padding: 16px 20px;">
            <div class="card-title" style="font-size: 14px; font-weight: 700; color: var(--primary);">
                <i class="ti ti-user-circle"></i> Fiche d'identité numérique
            </div>
            <span class="status-badge" style="background: var(--neutral-lt); color: var(--text-muted); font-family: monospace; font-size: 11px; font-weight: 700;">
                ID: {{ $etudiant->matricule }}
            </span>
        </div>

        <div class="card-body" style="padding: 24px;">
            <div style="display: flex; align-items: center; gap: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--border);">
                <div style="width: 68px; height: 68px; border-radius: 50%; background: var(--primary-lt); display: flex; align-items: center; justify-content: center; border: 3px solid var(--border); box-shadow: var(--shadow-sm); flex-shrink: 0; overflow: hidden;">
                    @if($etudiant->photo)
                        <img src="{{ asset('storage/' . $etudiant->photo) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="font-size: 24px; font-weight: 700; color: var(--primary); font-family: 'DM Serif Display', serif;">
                            {{ strtoupper(substr($etudiant->prenom, 0, 1)) }}
                        </span>
                    @endif
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--text); margin: 0; line-height: 1.2;">
                        {{ $etudiant->nom }} {{ $etudiant->prenom }}
                    </h3>
                    <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px; display: flex; align-items: center; gap: 6px;">
                        <i class="ti ti-mail" style="font-size: 14px;"></i> {{ auth()->user()->email }}
                    </div>
                </div>
            </div>

            {{-- Grille des détails du Cursus --}}
            <div style="margin-top: 20px;">
                @php
                    $infos = [
                        ['Filière d\'étude',  $etudiant->filiere?->nom_filiere ?? '—', 'ti ti-school'],
                        ['Niveau d\'étude',   $etudiant->niveau ?? '—', 'ti ti-academic-cap'],
                        ['Nationalité',       $etudiant->nationalite ?? '—', 'ti ti-flag'],
                        ['Téléphone GSM',     $etudiant->telephone ?? '—', 'ti ti-phone'],
                        ['Adresse physique',  $etudiant->adresse ?? '—', 'ti ti-map-pin'],
                    ];
                @endphp

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px 24px;">
                    @foreach($infos as [$label, $val, $icon])
                        <div style="display: flex; align-items: flex-start; gap: 10px; padding: 6px 0;">
                            <i class="{{ $icon }}" style="font-size: 16px; color: var(--text-muted); margin-top: 2px;"></i>
                            <div>
                                <div style="font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: var(--text-muted); font-weight: 600;">
                                    {{ $label }}
                                </div>
                                <div style="font-size: 13.5px; font-weight: 600; color: var(--text); margin-top: 2px;">
                                    {{ $val }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- 2. MODIFICATION SECURE DU ACCÈS (MOT DE PASSE) --}}
    <div class="card" style="box-shadow: var(--shadow-sm);">
        <div class="card-header" style="padding: 16px 20px;">
            <div class="card-title" style="font-size: 14px; font-weight: 700;"><i class="ti ti-lock-square" style="color: var(--accent);"></i> Authentification & Sécurité</div>
        </div>
        <div class="card-body" style="padding: 24px;">
            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom: 20px; border-left: 4px solid var(--success);">
                    <i class="ti ti-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('etudiant.parametres.password') }}" id="pwdForm">
                @csrf

                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label" style="font-weight: 600;">Mot de passe actuel *</label>
                    <div style="position: relative;">
                        <input type="password" name="current_password" id="currentPwd"
                               class="form-control @error('current_password') is-invalid @enderror"
                               placeholder="Saisissez votre mot de passe d'origine" autocomplete="current-password" style="padding-right: 40px;">
                        <button type="button" onclick="togglePwd('currentPwd', this)"
                                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted); font-size: 18px; display: flex; align-items: center;">
                            <i class="ti ti-eye"></i>
                        </button>
                    </div>
                    @error('current_password') <div class="invalid-feedback" style="display:block;">{{ $message }}</div> @enderror
                </div>

                <div class="form-grid-2" style="gap: 18px; margin-bottom: 0;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-weight: 600;">Nouveau mot de passe *</label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="newPwd"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Minimum 8 caractères" autocomplete="new-password" oninput="checkStrength(this.value)" style="padding-right: 40px;">
                            <button type="button" onclick="togglePwd('newPwd', this)"
                                    style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted); font-size: 18px; display: flex; align-items: center;">
                                <i class="ti ti-eye"></i>
                            </button>
                        </div>
                        {{-- Indicateur visuel fluide --}}
                        <div id="strength-bar" style="height: 4px; border-radius: 4px; margin-top: 8px; background: var(--neutral-lt); overflow: hidden;">
                            <div id="strength-fill" style="height: 100%; width: 0; border-radius: 4px; transition: width .4s cubic-bezier(0.4, 0, 0.2, 1), background .4s;"></div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px;">
                            <span id="strength-label" style="font-size: 11px; font-weight: 700;"></span>
                        </div>
                        @error('password') <div class="invalid-feedback" style="display:block;">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-weight: 600;">Confirmer le mot de passe *</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="Répétez le nouveau mot de passe" autocomplete="new-password">
                    </div>
                </div>

                <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary" style="font-weight: 600;">
                        <i class="ti ti-shield-check"></i> Sauvegarder les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 3. RAPPEL DES CONSIGNES --}}
    <div style="background: rgba(var(--warning-rgb), 0.04); border: 1px solid var(--warning-lt); border-left: 4px solid var(--warning); border-radius: 8px; padding: 14px 18px;">
        <div style="display: flex; gap: 12px; align-items: flex-start; font-size: 13px; line-height: 1.5; color: #744210;">
            <i class="ti ti-shield-alert" style="font-size: 20px; color: var(--warning); flex-shrink: 0;"></i>
            <div>
                <strong>Avis de sécurité pour votre compte étudiant :</strong>
                Afin de garantir l'intégrité de vos résultats d'examen et données d'inscription, veillez à ne jamais divulguer votre mot de passe à un tiers. La scolarité ne vous demandera jamais vos identifiants par e-mail.
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ti ti-eye-off';
    } else {
        input.type = 'password';
        icon.className = 'ti ti-eye';
    }
}

function checkStrength(val) {
    const fill = document.getElementById('strength-fill');
    const label = document.getElementById('strength-label');
    let score = 0;

    if (!val) {
        fill.style.width = '0%';
        label.textContent = '';
        return;
    }

    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { pct: '15%',  color: 'var(--danger)',   text: 'Sécurité : Critique' },
        { pct: '35%',  color: 'var(--danger)',   text: 'Sécurité : Faible' },
        { pct: '60%',  color: 'var(--accent)',   text: 'Sécurité : Moyenne' },
        { pct: '85%',  color: '#2e7d32',         text: 'Sécurité : Forte' },
        { pct: '100%', color: 'var(--success)',  text: 'Sécurité : Maximale' },
    ];

    fill.style.width = levels[score].pct;
    fill.style.background = levels[score].color;
    label.textContent = levels[score].text;
    label.style.color = levels[score].color;
}
</script>
@endpush

@endsection
