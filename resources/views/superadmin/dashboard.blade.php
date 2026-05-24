@extends('layouts.superadmin')

@section('title', 'Tableau de bord')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Cabinet+Grotesk:wght@700;800;900&display=swap" rel="stylesheet">

<style>
:root {
    --bg-main: #080c14;
    --bg-surface: #0d1421;
    --bg-card: #111827;
    --bg-card-hover: #151e2e;
    --border: rgba(255,255,255,0.06);
    --border-hover: rgba(255,255,255,0.12);

    --text-heading: #f0f4ff;
    --text-body: #8b9ab5;
    --text-muted: #4e5f7a;

    --blue: #3b82f6;
    --blue-glow: rgba(59,130,246,0.15);
    --purple: #a78bfa;
    --purple-glow: rgba(167,139,250,0.15);
    --emerald: #34d399;
    --emerald-glow: rgba(52,211,153,0.15);
    --amber: #fbbf24;
    --amber-glow: rgba(251,191,36,0.15);
    --slate: #64748b;
    --slate-glow: rgba(100,116,139,0.12);

    --radius-xl: 20px;
    --radius-lg: 16px;
    --radius-md: 12px;
    --radius-sm: 8px;

    --font-display: 'Cabinet Grotesk', sans-serif;
    --font-body: 'DM Sans', sans-serif;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body, .container-fluid {
    background-color: var(--bg-main);
    font-family: var(--font-body);
    color: var(--text-body);
    min-height: 100vh;
}

/* ─── Alert ─── */
.alert-custom {
    padding: 1rem 1.5rem;
    background: rgba(52,211,153,0.08);
    color: var(--emerald);
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid rgba(52,211,153,0.2);
    animation: slideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

/* ─── Header ─── */
.top-header-bar {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
    position: relative;
    overflow: hidden;
}

.top-header-bar::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 220px; height: 220px;
    background: radial-gradient(circle, rgba(59,130,246,0.07) 0%, transparent 70%);
    pointer-events: none;
}

.page-label {
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--blue);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.page-label::before {
    content: '';
    display: inline-block;
    width: 6px; height: 6px;
    background: var(--blue);
    border-radius: 50%;
    box-shadow: 0 0 8px var(--blue);
}

.page-title {
    font-family: var(--font-display);
    font-weight: 900;
    color: var(--text-heading);
    font-size: 1.75rem;
    letter-spacing: -0.03em;
    line-height: 1;
}

.page-subtitle {
    color: var(--text-muted);
    font-size: 0.83rem;
    margin-top: 6px;
    font-weight: 400;
}

.btn-refresh {
    background: rgba(255,255,255,0.04);
    color: var(--text-body);
    border: 1px solid var(--border);
    padding: 0.65rem 1.2rem;
    border-radius: var(--radius-md);
    font-family: var(--font-body);
    font-weight: 600;
    font-size: 0.82rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    letter-spacing: 0.01em;
}

.btn-refresh:hover {
    background: rgba(255,255,255,0.07);
    border-color: var(--border-hover);
    color: var(--text-heading);
}

/* ─── Stats Grid ─── */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.25rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--bg-card);
    padding: 1.75rem 1.5rem;
    border-radius: var(--radius-xl);
    border: 1px solid var(--border);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
    cursor: default;
}

.stat-card::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: inherit;
    background: radial-gradient(300px circle at var(--mx, 50%) var(--my, 50%), var(--glow-color, rgba(59,130,246,0.04)), transparent 70%);
    opacity: 0;
    transition: opacity 0.3s;
    pointer-events: none;
}

.stat-card:hover {
    border-color: var(--border-hover);
    background: var(--bg-card-hover);
    transform: translateY(-4px);
}
.stat-card:hover::after { opacity: 1; }

.card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.card-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
}

.card-icon {
    width: 38px; height: 38px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    transition: transform 0.3s ease;
    flex-shrink: 0;
}
.stat-card:hover .card-icon { transform: scale(1.08) rotate(5deg); }

.stat-number {
    font-family: var(--font-display);
    font-size: 2.75rem;
    font-weight: 900;
    color: var(--text-heading);
    line-height: 1;
    letter-spacing: -0.05em;
}

.stat-trend {
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 5px;
    color: var(--text-muted);
}

/* Card Themes */
.card-blue  { --glow-color: var(--blue-glow); }
.card-blue  .card-icon { background: var(--blue-glow); color: var(--blue); }
.card-blue  .stat-number { color: var(--blue); }

.card-purple { --glow-color: var(--purple-glow); }
.card-purple .card-icon { background: var(--purple-glow); color: var(--purple); }
.card-purple .stat-number { color: var(--purple); }

.card-emerald { --glow-color: var(--emerald-glow); }
.card-emerald .card-icon { background: var(--emerald-glow); color: var(--emerald); }
.card-emerald .stat-number { color: var(--emerald); }

.card-amber { --glow-color: var(--amber-glow); }
.card-amber .card-icon { background: var(--amber-glow); color: var(--amber); }
.card-amber .stat-number { color: var(--amber); }

.card-slate { --glow-color: var(--slate-glow); }
.card-slate .card-icon { background: var(--slate-glow); color: var(--slate); }
.card-slate .stat-number { color: var(--text-heading); }

/* ─── Panel Section ─── */
.panel-section {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.panel-section::before {
    content: '';
    position: absolute;
    bottom: -80px; left: -80px;
    width: 280px; height: 280px;
    background: radial-gradient(circle, rgba(52,211,153,0.04) 0%, transparent 70%);
    pointer-events: none;
}

.panel-header-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.panel-eyebrow {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--emerald);
    margin-bottom: 8px;
    display: flex; align-items: center; gap: 6px;
}
.panel-eyebrow::before {
    content: '';
    width: 5px; height: 5px;
    background: var(--emerald);
    border-radius: 50%;
    box-shadow: 0 0 6px var(--emerald);
}

.panel-info h3 {
    font-family: var(--font-display);
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--text-heading);
    letter-spacing: -0.02em;
    margin-bottom: 6px;
}

.panel-info p {
    font-size: 0.85rem;
    color: var(--text-muted);
    line-height: 1.6;
    max-width: 520px;
}

/* Control Pill */
.control-wrapper {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    background: rgba(255,255,255,0.03);
    padding: 0.65rem 1.25rem;
    border-radius: 100px;
    border: 1px solid var(--border);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 5px 12px;
    border-radius: 100px;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.01em;
}
.badge-open  { background: rgba(52,211,153,0.1); color: var(--emerald); border: 1px solid rgba(52,211,153,0.2); }
.badge-closed{ background: rgba(239,68,68,0.08); color: #f87171; border: 1px solid rgba(239,68,68,0.15); }

/* Toggle Switch */
.switch-container {
    position: relative;
    display: inline-block;
    width: 46px; height: 24px;
    flex-shrink: 0;
}
.switch-container input { opacity: 0; width: 0; height: 0; }
.slider-toggle {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background: rgba(255,255,255,0.1);
    border-radius: 34px;
    transition: 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.slider-toggle::before {
    content: '';
    position: absolute;
    height: 18px; width: 18px;
    left: 3px; bottom: 3px;
    background: white;
    border-radius: 50%;
    transition: 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 1px 4px #c7baba09;
}
input:checked + .slider-toggle { background: var(--emerald); }
input:checked + .slider-toggle::before { transform: translateX(22px); }

/* ─── Form ─── */
.panel-form-box {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border);
    animation: fadeUp 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

.form-grid-dates {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}

.form-field-group label {
    display: block;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
    margin-bottom: 8px;
}

.input-date-custom {
    width: 100%;
    padding: 0.8rem 1rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--border);
    background: rgba(255,255,255,0.03);
    font-family: var(--font-body);
    color: var(--text-heading);
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    color-scheme: dark;
}
.input-date-custom:focus {
    outline: none;
    border-color: rgba(59,130,246,0.5);
    background: rgba(59,130,246,0.04);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.08);
}

.info-badge-campagne {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--emerald);
    margin-bottom: 1.5rem;
    background: rgba(52,211,153,0.07);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 14px;
    border-radius: var(--radius-sm);
    border: 1px solid rgba(52,211,153,0.15);
}

.btn-save-period {
    background: var(--blue);
    color: #fff;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: var(--radius-md);
    font-family: var(--font-body);
    font-weight: 700;
    font-size: 0.875rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}
.btn-save-period::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
}
.btn-save-period:hover {
    background: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(59,130,246,0.3);
}

/* ─── Animations ─── */
@keyframes slideDown { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeUp    { from { opacity: 0; transform: translateY(8px);  } to { opacity: 1; transform: translateY(0); } }
@keyframes spin      { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

.fa-spin { animation: spin 0.7s linear infinite; }
.loading-pulse { opacity: 0.4; transition: opacity 0.3s; }

/* ─── Responsive ─── */
@media (max-width: 640px) {
    .dashboard-grid { grid-template-columns: repeat(2, 1fr); }
    .stat-number { font-size: 2rem; }
    .top-header-bar { padding: 1.25rem; }
    .panel-section { padding: 1.5rem; }
}
</style>

<div class="container-fluid py-4 px-3 px-md-4">

    @if(session('success'))
        <div class="alert-custom">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Header -->
    <div class="top-header-bar">
        <div>
            <div class="page-label">Administration</div>
            <h2 class="page-title">Tableau de Bord</h2>
            <p class="page-subtitle">Indicateurs temps réel de l'établissement</p>
        </div>
        <button id="refreshBtn" class="btn-refresh">
            <i class="fas fa-sync-alt" id="refresh-icon"></i>
            <span>Actualiser</span>
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="dashboard-grid">

        <div class="stat-card card-blue">
            <div class="card-top">
                <span class="card-label">Étudiants</span>
                <div class="card-icon"><i class="fas fa-graduation-cap"></i></div>
            </div>
            <div class="stat-number" id="count-etudiants">{{ $stats['etudiants'] }}</div>
            <div class="stat-trend"><i class="fas fa-circle" style="font-size:6px"></i> Total inscrits</div>
        </div>

        <div class="stat-card card-purple">
            <div class="card-top">
                <span class="card-label">Enseignants</span>
                <div class="card-icon"><i class="fas fa-chalkboard-teacher"></i></div>
            </div>
            <div class="stat-number" id="count-enseignants">{{ $stats['enseignants'] }}</div>
            <div class="stat-trend"><i class="fas fa-circle" style="font-size:6px"></i> Corps enseignant</div>
        </div>

        <div class="stat-card card-emerald">
            <div class="card-top">
                <span class="card-label">Filières</span>
                <div class="card-icon"><i class="fas fa-book-open"></i></div>
            </div>
            <div class="stat-number" id="count-filieres">{{ $stats['filieres'] }}</div>
            <div class="stat-trend"><i class="fas fa-circle" style="font-size:6px"></i> Programmes actifs</div>
        </div>

        {{-- FIX : statut_dossier en_cours --}}
        <div class="stat-card card-amber">
            <div class="card-top">
                <span class="card-label">En Cours</span>
                <div class="card-icon"><i class="fas fa-hourglass-half"></i></div>
            </div>
            <div class="stat-number" id="count-attente">
                {{ $stats['statut_dossier']['en_cours'] ?? 0 }}
            </div>
            <div class="stat-trend"><i class="fas fa-circle" style="font-size:6px"></i> Dossiers en traitement</div>
        </div>

        <div class="stat-card card-slate">
            <div class="card-top">
                <span class="card-label">Utilisateurs</span>
                <div class="card-icon"><i class="fas fa-users-cog"></i></div>
            </div>
            <div class="stat-number" id="count-users">{{ $stats['total_users'] }}</div>
            <div class="stat-trend"><i class="fas fa-circle" style="font-size:6px"></i> Comptes actifs</div>
        </div>

    </div>

    <!-- Période d'inscription -->
    <div class="panel-section">
        <div class="panel-header-wrapper">
            <div class="panel-info">
                <div class="panel-eyebrow">Gestion des accès</div>
                <h3>Période d'inscriptions universitaires</h3>
                <p>Ouvrez ou clôturez la fenêtre de dépôt de dossiers pour les nouveaux étudiants.</p>
            </div>
            <div class="control-wrapper">
                <div id="statusBadge" class="status-badge {{ $inscriptionOuverte ? 'badge-open' : 'badge-closed' }}">
                    <i class="fas {{ $inscriptionOuverte ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                    <span>{{ $inscriptionOuverte ? 'Ouvertes' : 'Fermées' }}</span>
                </div>
                <label class="switch-container">
                    <input type="checkbox" id="inscriptionToggle" {{ $inscriptionOuverte ? 'checked' : '' }}>
                    <span class="slider-toggle"></span>
                </label>
            </div>
        </div>

        @php
            $activePeriod = \App\Models\PeriodeInscription::where('active', true)->first();
        @endphp

        <div id="periodFormContainer" class="panel-form-box" style="display: {{ $inscriptionOuverte ? 'block' : 'none' }};">
            <form method="POST" action="{{ route('superadmin.dashboard') }}">
                @csrf
                <div class="form-grid-dates">
                    <div class="form-field-group">
                        <label>Date d'ouverture *</label>
                        <input type="date" name="date_debut" class="input-date-custom" required
                               value="{{ $activePeriod ? \Carbon\Carbon::parse($activePeriod->date_debut)->format('Y-m-d') : date('Y-m-d') }}">
                    </div>
                    <div class="form-field-group">
                        <label>Date de clôture *</label>
                        <input type="date" name="date_fin" class="input-date-custom" required
                               value="{{ $activePeriod ? \Carbon\Carbon::parse($activePeriod->date_fin)->format('Y-m-d') : '' }}">
                    </div>
                </div>

                @if($activePeriod)
                    <div class="info-badge-campagne">
                        <i class="fas fa-info-circle"></i>
                        Année universitaire active : <strong>{{ $activePeriod->annee }}</strong>
                    </div>
                @endif

                <button type="submit" class="btn-save-period">
                    <i class="fas fa-calendar-check"></i> Enregistrer la période
                </button>
            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── Glow effect on cards ──
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('mousemove', e => {
            const r = card.getBoundingClientRect();
            card.style.setProperty('--mx', `${e.clientX - r.left}px`);
            card.style.setProperty('--my', `${e.clientY - r.top}px`);
        });
    });

    // ── Refresh stats ──
    const refreshBtn  = document.getElementById('refreshBtn');
    const refreshIcon = document.getElementById('refresh-icon');
    const statsUrl    = "{{ route('superadmin.stats') }}";

    function fetchStats() {
        refreshIcon.classList.add('fa-spin');
        ['etudiants','enseignants','filieres','attente','users'].forEach(id => {
            document.getElementById(`count-${id}`)?.classList.add('loading-pulse');
        });

        fetch(statsUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(data => {
            const set = (id, val) => {
                const el = document.getElementById(id);
                if (el && val !== undefined) el.textContent = val;
            };
            set('count-etudiants',  data.etudiants);
            set('count-enseignants',data.enseignants);
            set('count-filieres',   data.filieres);
            // FIX : statut_dossier.en_cours
            set('count-attente',    data.statut_dossier?.en_cours ?? data.inscriptions_attente ?? 0);
            set('count-users',      data.total_users);
        })
        .catch(err => console.error('Stats refresh error:', err))
        .finally(() => {
            refreshIcon.classList.remove('fa-spin');
            ['etudiants','enseignants','filieres','attente','users'].forEach(id => {
                document.getElementById(`count-${id}`)?.classList.remove('loading-pulse');
            });
        });
    }

    refreshBtn.addEventListener('click', fetchStats);
    setInterval(fetchStats, 60000);

    // ── Toggle inscription ──
    const toggle    = document.getElementById('inscriptionToggle');
    const badge     = document.getElementById('statusBadge');
    const formBox   = document.getElementById('periodFormContainer');
    const closeUrl  = "{{ route('superadmin.inscriptions.fermer') }}";

    toggle.addEventListener('change', function () {
        if (this.checked) {
            badge.className = 'status-badge badge-open';
            badge.innerHTML = '<i class="fas fa-check-circle"></i><span>Ouvertes</span>';
            formBox.style.display = 'block';
        } else {
            if (!confirm("Confirmer la clôture immédiate des inscriptions ?")) {
                this.checked = true; return;
            }
            this.disabled = true;

            fetch(closeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(r => { if (!r.ok) throw new Error(); return r.json(); })
            .then(() => {
                badge.className = 'status-badge badge-closed';
                badge.innerHTML = '<i class="fas fa-times-circle"></i><span>Fermées</span>';
                formBox.style.display = 'none';
            })
            .catch(() => {
                this.checked = true;
                alert("Impossible de clore la période automatiquement.");
            })
            .finally(() => { this.disabled = false; });
        }
    });
});
</script>

@endsection
