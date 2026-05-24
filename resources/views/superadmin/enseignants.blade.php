@extends('layouts.superadmin')

@section('title', 'Gestion des enseignants')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Cabinet+Grotesk:wght@700;800;900&display=swap" rel="stylesheet">

<style>
:root {
    --bg-main:       #080c14;
    --bg-card:       #111827;
    --bg-card-hover: #151e2e;
    --bg-input:      rgba(255,255,255,0.03);
    --border:        rgba(255,255,255,0.06);
    --border-hover:  rgba(255,255,255,0.12);
    --border-focus:  rgba(59,130,246,0.5);

    --text-heading:  #f0f4ff;
    --text-body:     #8b9ab5;
    --text-muted:    #4e5f7a;

    --blue:    #3b82f6;
    --blue-g:  rgba(59,130,246,0.12);
    --purple:  #a78bfa;
    --purple-g:rgba(167,139,250,0.12);
    --emerald: #34d399;
    --emerald-g:rgba(52,211,153,0.12);
    --amber:   #fbbf24;
    --amber-g: rgba(251,191,36,0.12);
    --red:     #f87171;
    --red-g:   rgba(248,113,113,0.1);

    --radius-xl: 20px;
    --radius-lg: 16px;
    --radius-md: 12px;
    --radius-sm: 8px;

    --font-display: 'Cabinet Grotesk', sans-serif;
    --font-body:    'DM Sans', sans-serif;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body, .container-fluid {
    background: var(--bg-main);
    font-family: var(--font-body);
    color: var(--text-body);
}

/* ─── Alerts ─── */
.alert-success, .alert-danger {
    padding: 0.9rem 1.25rem;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    animation: slideDown 0.35s cubic-bezier(0.16,1,0.3,1);
}
.alert-success { background: rgba(52,211,153,0.08); color: var(--emerald); border: 1px solid rgba(52,211,153,0.2); }
.alert-danger  { background: var(--red-g); color: var(--red); border: 1px solid rgba(248,113,113,0.2); }
.alert-danger ul { padding-left: 1rem; }

/* ─── Page Header ─── */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.page-eyebrow {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--purple);
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 6px;
}
.page-eyebrow::before {
    content: '';
    width: 6px; height: 6px;
    background: var(--purple);
    border-radius: 50%;
    box-shadow: 0 0 8px var(--purple);
}
.page-title h2 {
    font-family: var(--font-display);
    font-size: 1.75rem;
    font-weight: 900;
    color: var(--text-heading);
    letter-spacing: -0.03em;
    line-height: 1;
}
.page-title p {
    color: var(--text-muted);
    font-size: 0.83rem;
    margin-top: 6px;
}

/* ─── Stats Grid ─── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.25rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
    position: relative;
    overflow: hidden;
}
.stat-card:hover {
    border-color: var(--border-hover);
    background: var(--bg-card-hover);
    transform: translateY(-3px);
}
.stat-card::after {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 120px; height: 120px;
    border-radius: 50%;
    background: var(--glow, rgba(59,130,246,0.05));
    pointer-events: none;
}

.stat-icon {
    width: 48px; height: 48px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.icon-blue   { background: var(--blue-g);   color: var(--blue);   --glow: var(--blue-g); }
.icon-green  { background: var(--emerald-g);color: var(--emerald);--glow: var(--emerald-g); }
.icon-purple { background: var(--purple-g); color: var(--purple); --glow: var(--purple-g); }
.icon-amber  { background: var(--amber-g);  color: var(--amber);  --glow: var(--amber-g); }

.stat-info h3 {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 900;
    color: var(--text-heading);
    letter-spacing: -0.04em;
    line-height: 1;
}
.stat-info p {
    font-size: 0.78rem;
    color: var(--text-muted);
    margin-top: 4px;
    font-weight: 500;
}

/* ─── Main Grid ─── */
.main-grid {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 1.5rem;
}

@media (max-width: 1024px) {
    .main-grid { grid-template-columns: 1fr; }
}

/* ─── Card ─── */
.card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    padding: 1.75rem;
    position: relative;
    overflow: hidden;
}

.card-eyebrow {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--blue);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.card-eyebrow::before {
    content: '';
    width: 5px; height: 5px;
    background: var(--blue);
    border-radius: 50%;
    box-shadow: 0 0 6px var(--blue);
}

.card-title {
    font-family: var(--font-display);
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--text-heading);
    letter-spacing: -0.02em;
    margin-bottom: 1.75rem;
}

/* ─── Form ─── */
.form-group { margin-bottom: 1.1rem; }

.form-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
    margin-bottom: 7px;
}

.form-control {
    width: 100%;
    padding: 0.8rem 1rem;
    background: var(--bg-input);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    font-family: var(--font-body);
    font-size: 0.9rem;
    color: var(--text-heading);
    transition: all 0.2s ease;
    color-scheme: dark;
}
.form-control::placeholder { color: var(--text-muted); }
.form-control:focus {
    outline: none;
    border-color: var(--border-focus);
    background: rgba(59,130,246,0.04);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.08);
}

.btn-submit {
    width: 100%;
    background: var(--blue);
    color: #fff;
    border: none;
    padding: 0.85rem;
    border-radius: var(--radius-md);
    font-family: var(--font-body);
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 0.5rem;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}
.btn-submit::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.08), transparent);
}
.btn-submit:hover {
    background: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(59,130,246,0.3);
}

/* ─── Search ─── */
.search-box {
    position: relative;
    margin-bottom: 1.25rem;
}
.search-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 0.85rem;
    pointer-events: none;
}
.search-box input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    background: var(--bg-input);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    font-family: var(--font-body);
    font-size: 0.875rem;
    color: var(--text-heading);
    transition: all 0.2s ease;
    color-scheme: dark;
}
.search-box input::placeholder { color: var(--text-muted); }
.search-box input:focus {
    outline: none;
    border-color: var(--border-focus);
    background: rgba(59,130,246,0.03);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.07);
}

/* ─── Table ─── */
.table-wrapper {
    overflow-x: auto;
    margin: 0 -0.25rem;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table thead tr {
    border-bottom: 1px solid var(--border);
}

table th {
    padding: 0.75rem 1rem;
    text-align: left;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
    white-space: nowrap;
}

table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background 0.15s ease;
}
table tbody tr:last-child { border-bottom: none; }
table tbody tr:hover { background: rgba(255,255,255,0.02); }

table td {
    padding: 1rem;
    vertical-align: middle;
}

/* Teacher cell */
.teacher-info { display: flex; align-items: center; gap: 12px; }

.teacher-avatar {
    width: 42px; height: 42px;
    border-radius: var(--radius-sm);
    background: linear-gradient(135deg, var(--purple), var(--blue));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1rem;
    flex-shrink: 0;
    font-family: var(--font-display);
}

.teacher-name {
    font-weight: 700;
    color: var(--text-heading);
    font-size: 0.9rem;
}
.teacher-email {
    color: var(--text-muted);
    font-size: 0.78rem;
    margin-top: 2px;
}

/* Badges */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.03em;
}
.badge-ma { background: var(--blue-g);   color: var(--blue);   border: 1px solid rgba(59,130,246,0.2); }
.badge-mc { background: var(--purple-g); color: var(--purple); border: 1px solid rgba(167,139,250,0.2); }
.badge-pt { background: var(--emerald-g);color: var(--emerald);border: 1px solid rgba(52,211,153,0.2); }

/* Dept pill */
.dept-pill {
    font-size: 0.8rem;
    color: var(--text-body);
    background: rgba(255,255,255,0.04);
    border: 1px solid var(--border);
    padding: 4px 10px;
    border-radius: var(--radius-sm);
    display: inline-block;
}

/* Delete btn */
.btn-delete {
    border: 1px solid rgba(248,113,113,0.2);
    background: var(--red-g);
    color: var(--red);
    padding: 8px 10px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.85rem;
}
.btn-delete:hover {
    background: rgba(248,113,113,0.18);
    border-color: rgba(248,113,113,0.35);
    transform: scale(1.05);
}

.empty {
    text-align: center;
    padding: 3rem;
    color: var(--text-muted);
    font-size: 0.9rem;
}
.empty i { font-size: 2rem; margin-bottom: 0.75rem; display: block; opacity: 0.4; }

/* ─── Animations ─── */
@keyframes slideDown { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="container-fluid py-4 px-3 px-md-4">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-danger">
            <i class="fas fa-exclamation-circle" style="margin-top:2px;flex-shrink:0"></i>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- Header --}}
    <div class="page-header">
        <div class="page-title">
            <div class="page-eyebrow">Personnel académique</div>
            <h2>Gestion des enseignants</h2>
            <p>Administration du corps enseignant de l'établissement</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-icon icon-purple">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $enseignants->count() }}</h3>
                <p>Total enseignants</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-green">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $enseignants->where('grade_enseignant', 'PT')->count() }}</h3>
                <p>Professeurs titulaires</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-blue">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $enseignants->where('grade_enseignant', 'MC')->count() }}</h3>
                <p>Maîtres de Conférences</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-amber">
                <i class="fas fa-user"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $enseignants->where('grade_enseignant', 'MA')->count() }}</h3>
                <p>Maîtres Assistants</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-purple">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $departements->count() }}</h3>
                <p>Départements</p>
            </div>
        </div>

    </div>

    {{-- Main Grid --}}
    <div class="main-grid">

        {{-- Formulaire --}}
        <div class="card">
            <div class="card-eyebrow">Nouveau</div>
            <div class="card-title">Ajouter un enseignant</div>

            <form action="{{ route('superadmin.enseignants.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control"
                           value="{{ old('nom') }}" placeholder="Ex: Dupont" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" class="form-control"
                           value="{{ old('prenom') }}" placeholder="Ex: Jean" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Adresse email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}" placeholder="enseignant@universite.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Grade</label>
                    <select name="grade_enseignant" class="form-control">
                        <option value="">-- Sélectionner --</option>
                        <option value="MA" {{ old('grade_enseignant') == 'MA' ? 'selected' : '' }}>
                            Maître Assistant (MA)
                        </option>
                        <option value="MC" {{ old('grade_enseignant') == 'MC' ? 'selected' : '' }}>
                            Maître de Conférences (MC)
                        </option>
                        <option value="PT" {{ old('grade_enseignant') == 'PT' ? 'selected' : '' }}>
                            Professeur Titulaire (PT)
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Département</label>
                    <select name="id_departement" class="form-control">
                        <option value="">-- Sélectionner un département --</option>
                        @foreach($departements as $dep)
                            <option value="{{ $dep->id_departement }}">
                                {{ $dep->nom_departement }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-plus"></i> Ajouter l'enseignant
                </button>
            </form>
        </div>

        {{-- Tableau --}}
        <div class="card">
            <div class="card-eyebrow">Répertoire</div>
            <div class="card-title">Liste des enseignants</div>

            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Rechercher par nom, email, département...">
            </div>

            @if($enseignants->isEmpty())
                <div class="empty">
                    <i class="fas fa-chalkboard-teacher"></i>
                    Aucun enseignant enregistré pour le moment.
                </div>
            @else
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Enseignant</th>
                                <th>Grade</th>
                                <th>Département</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="teacherTable">
                            @foreach($enseignants as $ens)
                                <tr>
                                    <td>
                                        <div class="teacher-info">
                                            <div class="teacher-avatar">
                                                {{ strtoupper(substr($ens->nom, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="teacher-name">{{ $ens->nom }} {{ $ens->prenom }}</div>
                                                <div class="teacher-email">{{ $ens->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($ens->grade_enseignant == 'MA')
                                            <span class="badge badge-ma">MA</span>
                                        @elseif($ens->grade_enseignant == 'MC')
                                            <span class="badge badge-mc">MC</span>
                                        @elseif($ens->grade_enseignant == 'PT')
                                            <span class="badge badge-pt">PT</span>
                                        @else
                                            <span style="color:var(--text-muted)">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="dept-pill">
                                            {{ $ens->departement->nom_departement ?? '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('superadmin.enseignants.delete', $ens->id_enseignant) }}"
                                              method="POST"
                                              onsubmit="return confirm('Supprimer cet enseignant ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
    const val = this.value.toLowerCase();
    document.querySelectorAll('#teacherTable tr').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
    });
});
</script>

@endsection
