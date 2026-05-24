 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activation de compte — UAC</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --green: #16a34a; --green-dark: #15803d; --green-soft: #f0fdf4;
            --dark: #1e293b; --dark2: #0f172a; --muted: #64748b;
            --border: #e2e8f0; --bg: #f8fafc; --white: #ffffff;
            --red: #dc2626; --red-soft: #fef2f2;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--dark2) 0%, #1e3a5f 60%, #1a4731 100%);
            min-height: 100vh; display: flex; flex-direction: column;
            align-items: center; justify-content: center; padding: 20px;
        }
        .card { background: var(--white); border-radius: 20px; box-shadow: 0 24px 60px rgba(0,0,0,0.25); width: 100%; max-width: 440px; overflow: hidden; }
        .card-top { background: linear-gradient(135deg, var(--dark2), #1e3a5f); padding: 24px 32px 20px; text-align: center; color: white; }
        .card-top img { height: 52px; border-radius: 8px; margin-bottom: 12px; }
        .card-top h1 { font-size: 19px; font-weight: 700; margin-bottom: 4px; }
        .card-top p  { font-size: 12px; opacity: 0.7; }
        .card-top .icon-wrap { width: 52px; height: 52px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 22px; }
        .card-body { padding: 24px 32px 28px; }

        .alert { display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px; border-radius: 10px; font-size: 13px; margin-bottom: 18px; border-left: 4px solid; }
        .alert-error { background: var(--red-soft); color: #991b1b; border-color: var(--red); }

        .info-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 9px; padding: 10px 14px; font-size: 12px; color: #92400e; margin-bottom: 16px; display: flex; gap: 8px; align-items: flex-start; }

        .form-group { margin-bottom: 14px; }
        .form-label { display: block; font-size: 11px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 13px; }
        input {
            width: 100%; padding: 10px 12px 10px 34px;
            border: 1px solid var(--border); border-radius: 8px;
            font-size: 13.5px; font-family: inherit; color: var(--dark);
            background: var(--white); transition: border .15s, box-shadow .15s;
        }
        input:focus { outline: none; border-color: var(--green); box-shadow: 0 0 0 3px rgba(22,163,74,.1); }

        .btn-submit {
            width: 100%; padding: 13px; background: var(--dark); color: white;
            border: none; border-radius: 10px; font-size: 14px; font-weight: 700;
            cursor: pointer; font-family: inherit; display: flex; align-items: center;
            justify-content: center; gap: 8px; transition: background .2s; margin-top: 6px;
        }
        .btn-submit:hover { background: var(--dark2); }
        .btn-submit .spinner { display: none; width: 16px; height: 16px; border: 2px solid rgba(255,255,255,.4); border-top-color: white; border-radius: 50%; animation: spin .7s linear infinite; }
        .btn-submit.loading .btn-text { display: none; }
        .btn-submit.loading .spinner { display: block; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .footer-link { text-align: center; margin-top: 16px; font-size: 13px; color: var(--muted); }
        .footer-link a { color: var(--green); text-decoration: none; font-weight: 600; }
        .back-home { text-align: center; margin-top: 16px; }
        .back-home a { font-size: 12px; color: rgba(255,255,255,0.6); text-decoration: none; }
        .back-home a:hover { color: white; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-top">
            <div class="icon-wrap"><i class="fas fa-key"></i></div>
            <h1>Activer mon compte</h1>
            <p>Étudiant répertorié — Matricule officiel UAC</p>
        </div>
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle" style="flex-shrink:0;margin-top:1px;"></i>
                    <div>@foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach</div>
                </div>
            @endif

            <div class="info-box">
                <i class="fas fa-exclamation-triangle" style="flex-shrink:0;margin-top:1px;"></i>
                <span>Utilisez le matricule et l'email fournis lors de votre pré-inscription officielle à l'UAC.</span>
            </div>

            <form action="{{ route('account.activate.store') }}" method="POST" id="activationForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">Matricule UAC</label>
                    <div class="input-wrap">
                        <i class="fas fa-id-card"></i>
                        <input type="text" name="matricule" placeholder="Ex: 12345678" value="{{ old('matricule') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email de pré-inscription</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="nom@exemple.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nouveau mot de passe</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="6 caractères minimum" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password_confirmation" placeholder="Répétez le mot de passe" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="activationBtn">
                    <span class="btn-text"><i class="fas fa-unlock-alt"></i> ACTIVER MON ACCÈS</span>
                    <span class="spinner"></span>
                </button>
            </form>

            <div class="footer-link">
                Déjà activé ? <a href="{{ route('login') }}">Se connecter</a>
                &nbsp;·&nbsp; <a href="{{ route('register') }}">Nouveau bachelier</a>
            </div>
        </div>
    </div>

    <div class="back-home">
        <a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
    </div>

    <script>
        document.getElementById('activationForm').addEventListener('submit', function() {
            const btn = document.getElementById('activationBtn');
            btn.classList.add('loading');
            setTimeout(() => btn.classList.remove('loading'), 6000);
        });
        window.addEventListener('pageshow', e => {
            if (e.persisted) document.getElementById('activationBtn').classList.remove('loading');
        });
    </script>
</body>
</html>
