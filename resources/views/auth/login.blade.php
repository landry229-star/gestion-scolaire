<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Portail UAC</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --green: #16a34a; --green-dark: #15803d; --green-soft: #f0fdf4;
            --dark: #1e293b; --dark2: #0f172a; --muted: #64748b;
            --border: #e2e8f0; --bg: #f8fafc; --white: #ffffff;
            --red: #dc2626; --red-soft: #fef2f2; --red-border: #fecaca;
        }

        /* 1. L'image est mise ici pour couvrir tout l'arrière-plan de l'écran */
        body {
            font-family: 'Inter', sans-serif;
            background-image: linear-gradient(rgba(45, 42, 40, 0.85), rgba(15, 23, 42, 0.95)), url("{{ asset('images/etu.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 100px;
        }

        /* 2. Le conteneur .main sert maintenant juste à centrer proprement */
        .main {
            width: 100%;
            max-width: 420px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .card {
            background: var(--white); border-radius: 50px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.35);
            width: 40%; overflow: hidden;
        }
        .card-top {
            background: linear-gradient(135deg, var(--dark2), #1e3a5f);
            padding: 30px 32px 24px; text-align: center; color: white;
        }
        .card-top img { height: 60px; border-radius: 10px; margin-bottom: 14px; box-shadow: 0 4px 16px rgba(0,0,0,0.3); }
        .card-top h1 { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
        .card-top p  { font-size: 13px; opacity: 0.7; }
        .card-body { padding: 28px 32px; }

        .alert { display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; border-left: 4px solid; }
        .alert-error   { background: var(--red-soft);   color: #991b1b; border-color: var(--red); }
        .alert-success { background: var(--green-soft); color: #166534; border-color: var(--green); }

        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 11px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 14px; }
        input {
            width: 100%; padding: 11px 12px 11px 36px;
            border: 1px solid var(--border); border-radius: 9px;
            font-size: 14px; font-family: inherit; color: var(--dark);
            background: var(--white); transition: border .15s, box-shadow .15s;
        }
        input:focus { outline: none; border-color: var(--green); box-shadow: 0 0 0 3px rgba(22,163,74,.1); }

        .forgot { text-align: right; margin-bottom: 20px; }
        .forgot a { font-size: 12px; color: var(--muted); text-decoration: none; }
        .forgot a:hover { color: var(--green); }

        .btn-submit {
            width: 100%; padding: 13px; background: var(--green); color: white;
            border: none; border-radius: 10px; font-size: 14px; font-weight: 700;
            cursor: pointer; font-family: inherit; display: flex; align-items: center;
            justify-content: center; gap: 8px; transition: background .2s;
        }
        .btn-submit:hover { background: var(--green-dark); }
        .btn-submit .spinner { display: none; width: 16px; height: 16px; border: 2px solid rgba(255,255,255,.4); border-top-color: white; border-radius: 50%; animation: spin .7s linear infinite; }
        .btn-submit.loading .btn-text { display: none; }
        .btn-submit.loading .spinner { display: block; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .divider { display: flex; align-items: center; gap: 10px; margin: 20px 0; color: var(--muted); font-size: 12px; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }

        .links { display: flex; flex-direction: column; gap: 8px; }
        .link-btn {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px; border: 1px solid var(--border); border-radius: 9px;
            font-size: 13px; font-weight: 500; color: var(--dark); text-decoration: none;
            transition: all .15s; background: var(--white);
        }
        .link-btn:hover { background: var(--bg); border-color: var(--green); color: var(--green); }
        .link-btn i { font-size: 13px; color: var(--green); }

        .back-home { text-align: center; margin-top: 20px; }
        .back-home a { font-size: 13px; color: rgba(255, 255, 255, 0.8); text-decoration: none; font-weight: 500; }
        .back-home a:hover { color: white; text-shadow: 0 2px 4px rgba(0,0,0,0.5); }
    </style>
</head>
<body>

        <div class="card">
            <div class="card-top">
                <img src="{{ asset('images/OIP.jpeg') }}" alt="Logo UAC">
                <h1>Connexion</h1>
                <p>Accédez à votre espace numérique</p>
            </div>
            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle" style="margin-top:1px;flex-shrink:0;"></i>
                        <div>{{ $errors->first() }}</div>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle" style="margin-top:1px;flex-shrink:0;"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST" id="loginForm">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Adresse email</label>
                        <div class="input-wrap">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mot de passe</label>
                        <div class="input-wrap">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>
                    <div class="forgot">
                        <a href="#">Mot de passe oublié ?</a>
                    </div>
                    <button type="submit" class="btn-submit" id="loginBtn">
                        <span class="btn-text"><i class="fas fa-sign-in-alt"></i> SE CONNECTER</span>
                        <span class="spinner"></span>
                    </button>
                </form>

                <div class="divider">Pas encore de compte ?</div>

                <div class="links">
                    <a href="{{ route('register') }}" class="link-btn">
                        <i class="fas fa-user-graduate"></i> Créer mon dossier (Nouveau Bachelier)
                    </a>
                    <a href="{{ route('account.activate') }}" class="link-btn">
                        <i class="fas fa-key"></i> Activer mon accès (Matricule existant)
                    </a>
                </div>
            </div>
        </div>

        <div class="back-home">
            <a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
        </div>


    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            setTimeout(() => btn.classList.remove('loading'), 5000);
        });
        window.addEventListener('pageshow', e => {
            if (e.persisted) document.getElementById('loginBtn').classList.remove('loading');
        });
    </script>
</body>
</html>
