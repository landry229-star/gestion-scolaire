<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer mon compte - UAC</title>
    <style>
        :root {
            --uac-green: #28a745;
            --uac-dark: #2c3e50;
            --uac-light: #f4f7f6;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--uac-light);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .register-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        .header { text-align: center; margin-bottom: 30px; }
        .header img { height: 60px; margin-bottom: 15px; }
        .header h2 { color: var(--uac-dark); margin: 0; font-size: 24px; }
        .header p { color: #666; font-size: 14px; margin-top: 10px; }

        .step-indicator {
            background: #e9ecef;
            color: #495057;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
        }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        @media (max-width: 500px) { .form-grid { grid-template-columns: 1fr; } }

        .form-group { margin-bottom: 18px; text-align: left; }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #444;
            font-size: 13px;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            box-sizing: border-box;
        }
        input:focus {
            border-color: var(--uac-green);
            outline: none;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
            background-color: #fff;
        }

        .btn-submit {
            background-color: var(--uac-green);
            color: white;
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s;
        }
        .btn-submit:hover { background-color: #218838; }

        .alert-error {
            background-color: #fff5f5;
            color: #c53030;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            border: 1px solid #feb2b2;
        }

        .footer-links { text-align: center; margin-top: 25px; font-size: 13px; color: #777; }
        .footer-links a { color: var(--uac-green); text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>

<div class="register-card">
    <div class="header">
        <img src="{{ asset('images/OIP.jpeg') }}" alt="Logo UAC">
        <br>
        <span class="step-indicator">Étape 1 : Informations de connexion</span>
        <h2>Créer mon compte</h2>
        <p>Inscrivez-vous pour commencer votre procédure d'inscription.</p>
    </div>

    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" placeholder="Ex: DOSSOUMOU" required value="{{ old('nom') }}">
            </div>
            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="prenom" placeholder="Ex: Jean" required value="{{ old('prenom') }}">
            </div>
        </div>

        <div class="form-group">
            <label>Adresse Email</label>
            <input type="email" name="email" placeholder="votre@email.com" required value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" placeholder="6 caractères minimum" required>
        </div>

        <div class="form-group">
            <label>Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" placeholder="Répétez le mot de passe" required>
        </div>

        <button type="submit" class="btn-submit">SUIVANT : REMPLIR MON DOSSIER</button>
    </form>

    <div class="footer-links">
        Vous avez déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
    </div>
</div>

</body>
</html>
