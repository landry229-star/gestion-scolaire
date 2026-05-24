<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { margin:0; font-family:Inter,sans-serif; background:#f8fafc; }

        .sidebar {
            position:fixed; left:0; top:0; width:250px; height:100vh;
            background:#1a2332; color:white; padding:20px;
        }

        .main {
            margin-left:250px;
            padding:20px;
        }

        .nav-link {
            display:block;
            color:#cbd5e1;
            padding:10px;
            text-decoration:none;
            border-radius:6px;
        }

        .nav-link:hover {
            background:#334155;
            color:white;
        }

        .active { background:#3b82f6; color:white; }
    </style>
</head>

<body>

{{-- SIDEBAR --}}
<div class="sidebar">
    <h3>UAC SUPER ADMIN</h3>

    <a class="nav-link" href="{{ route('superadmin.dashboard') }}">Dashboard</a>
    <a class="nav-link" href="{{ route('superadmin.inscriptions') }}">Inscriptions</a>
    <a class="nav-link" href="{{ route('superadmin.etudiants') }}">Étudiants</a>
    <a class="nav-link" href="{{ route('superadmin.enseignants') }}">Enseignants</a>
    <a class="nav-link" href="{{ route('superadmin.notes') }}">Notes</a>
    <a class="nav-link" href="{{ route('superadmin.matieres') }}">Matières</a>
    <a class="nav-link" href="{{ route('superadmin.academics') }}">Académique</a>
</div>

{{-- CONTENU --}}
<div class="main">
    @yield('content')
</div>

</body>
</html>
