<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail Numérique — UAC</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sidebar-w: 260px;
            --green:      #16a34a;
            --green-dark: #15803d;
            --green-soft: #f0fdf4;
            --dark:       #1e293b;
            --dark2:      #0f172a;
            --blue:       #3b82f6;
            --gold:       #f59e0b;
            --muted:      #64748b;
            --border:     #e2e8f0;
            --bg:         #f8fafc;
            --white:      #ffffff;
        }
        /* ======================================================
   MAIN AREA
====================================================== */
.main {
    margin-left: var(--sidebar-w);
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;

    /* On combine le dégradé et l'image proprement en CSS */
    background-image: linear-gradient(rgba(204, 97, 26, 0.144), rgba(102, 94, 96, 0.85)), url('/images/etu.jpg');

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed; /* L'image reste fixe pendant le défilement */
}
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--dark); min-height: 100vh; display: flex; flex-direction: column; }

        /* ── HERO ── */
        .hero {
            background-image: linear-gradient(rgba(65, 62, 60, 0.918), rgba(236, 229, 232, 0.007)), url('/images/etu.jpg');
            color: white;
            padding: 60px 20px 100px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-content { position: relative; z-index: 1; }
        .hero-logo { height: 90px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 8px 32px rgba(0,0,0,0.3); }
        .hero h1 { font-size: 30px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 10px; }
        .hero p  { font-size: 17px; opacity: 0.85; font-weight: 300; }
        .hero-divider { width: 60px; height: 4px; background: var(--green); border-radius: 2px; margin: 18px auto; }
        .hero-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 20px; font-size: 12px; margin-top: 8px; }

        /* ── MAIN ── */
        .main { max-width: 10000px; margin: -50px auto 0; padding: 0 20px 40px; width: 100%; flex: 1; }

        /* ── CARDS D'ACTION ── */
        .cards-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        @media(max-width:640px){ .cards-grid { grid-template-columns: 1fr; } }

        .action-card {
            background: var(--white);
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            padding: 32px 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: transform .25s, box-shadow .25s;
            position: relative;
            overflow: hidden;
        }
        .action-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
        }
        .card-green::before { background: var(--green); }
        .card-dark::before  { background: var(--dark); }

        .action-card:hover { transform: translateY(-6px); box-shadow: 0 12px 36px rgba(0,0,0,0.12); }

        .card-icon-wrap {
            width: 70px; height: 70px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px;
            margin-bottom: 16px;
        }
        .icon-green { background: var(--green-soft); color: var(--green); }
        .icon-dark  { background: #f1f5f9; color: var(--dark); }

        .action-card h2 { font-size: 19px; font-weight: 700; margin-bottom: 10px; }
        .action-card p  { font-size: 13.5px; color: var(--muted); line-height: 1.6; margin-bottom: 22px; flex: 1; }

        /* ── BOUTONS ── */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 13px 24px; border-radius: 10px;
            font-size: 14px; font-weight: 700; text-decoration: none;
            width: 100%; border: none; cursor: pointer; font-family: inherit;
            transition: all .2s; position: relative; overflow: hidden;
        }
        .btn-green { background: var(--green); color: white; }
        .btn-green:hover { background: var(--green-dark); }
        .btn-dark  { background: var(--dark); color: white; }
        .btn-dark:hover  { background: var(--dark2); }

        /* Spinner sur bouton */
        .btn .spinner { display: none; width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.4); border-top-color: white; border-radius: 50%; animation: spin .7s linear infinite; }
        .btn.loading .btn-text { display: none; }
        .btn.loading .spinner { display: block; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── LOGIN LINK ── */
        .login-link-wrap { text-align: center; margin: 10px 0 30px; }
        .login-link {
            display: inline-flex; align-items: center; gap: 8px;
            color: var(--dark); text-decoration: none; font-size: 14px; font-weight: 600;
            padding: 10px 20px; background: var(--white); border: 1px solid var(--border);
            border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all .15s;
        }
        .login-link:hover { background: var(--dark); color: white; border-color: var(--dark); }

        /* ── TABS ── */
        .tabs-section { background: var(--white); border-radius: 16px; border: 1px solid var(--border); box-shadow: 0 4px 16px rgba(0,0,0,0.05); overflow: hidden; }
        .tab-nav { display: flex; border-bottom: 1px solid var(--border); }
        .tab-btn {
            flex: 1; padding: 16px; background: none; border: none;
            font-family: inherit; font-size: 13.5px; font-weight: 600;
            color: var(--muted); cursor: pointer; transition: all .15s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            border-bottom: 3px solid transparent; margin-bottom: -1px;
        }
        .tab-btn:hover { color: var(--dark); background: var(--bg); }
        .tab-btn.active { color: var(--green); border-bottom-color: var(--green); background: var(--white); }
        .tab-pane { display: none; padding: 28px; }
        .tab-pane.active { display: block; }

        /* ── STEPS ── */
        .steps-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
        @media(max-width:640px){ .steps-grid { grid-template-columns: 1fr 1fr; } }
        .step-item { text-align: center; padding: 16px 10px; border-radius: 12px; background: var(--bg); border: 1px solid var(--border); position: relative; }
        .step-num {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--green); color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 700; margin: 0 auto 12px;
        }
        .step-icon { font-size: 22px; margin-bottom: 8px; }
        .step-item h4 { font-size: 13px; font-weight: 600; margin-bottom: 6px; color: var(--dark); }
        .step-item p  { font-size: 12px; color: var(--muted); line-height: 1.4; }

        /* Flèche entre steps */
        .step-item:not(:last-child)::after {
            content: '→';
            position: absolute;
            right: -12px; top: 50%;
            transform: translateY(-50%);
            color: var(--green);
            font-size: 18px;
            font-weight: 700;
        }
        @media(max-width:640px){ .step-item::after { display: none; } }

        /* ── FAQ ── */
        .faq-list { display: flex; flex-direction: column; gap: 10px; }
        .faq-item { border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
        .faq-question {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 18px; cursor: pointer;
            background: var(--white); user-select: none;
            font-size: 14px; font-weight: 600; color: var(--dark);
            transition: background .15s;
        }
        .faq-question:hover { background: var(--bg); }
        .faq-question-left { display: flex; align-items: center; gap: 10px; }
        .faq-question-left i { color: var(--green); font-size: 14px; }
        .faq-chevron { color: var(--muted); font-size: 12px; transition: transform .2s; }
        .faq-item.open .faq-chevron { transform: rotate(180deg); }
        .faq-answer { display: none; padding: 0 18px 14px; font-size: 13.5px; color: var(--muted); line-height: 1.6; background: var(--bg); border-top: 1px solid var(--border); padding-top: 12px; }
        .faq-item.open .faq-answer { display: block; }

        /* ── FOOTER ── */
        footer { background: var(--dark); color: rgba(255,255,255,0.6); text-align: center; padding: 24px 20px; font-size: 13px; margin-top: auto; }
        footer strong { color: white; }
    </style>
</head>
<body>

{{-- ══ HERO ══ --}}
<header class="hero">
    <div class="hero-content">
        <img src="{{ asset('images/OIP.jpeg') }}" alt="Logo UAC" class="hero-logo">
        <h1>Université d'Abomey-Calavi</h1>
        <div class="hero-divider"></div>
        <p>Portail Numérique de la Scolarité</p>
        <div class="hero-badge">
            <i class="fas fa-circle" style="color:#22c55e;font-size:8px;"></i>
            Système en ligne · Année Académique 2025–2026
        </div>
    </div>
</header>

{{-- ══ MAIN ══ --}}
<div class="main">

    {{-- ── CARDS D'ACTION ── --}}
    <div class="cards-grid">

        {{-- Nouveau bachelier --}}
        <div class="action-card card-green">
            <div class="card-icon-wrap icon-green">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h2>Nouveau Bachelier</h2>
            <p>Vous venez d'obtenir votre Bac et n'avez pas encore de dossier numérique à l'UAC. Créez votre espace en quelques minutes.</p>
            <a href="{{ route('register') }}" class="btn btn-green" id="btn-register" onclick="handleClick(this)">
                <span class="btn-text"><i class="fas fa-plus-circle"></i> CRÉER MON COMPTE</span>
                <span class="spinner"></span>
            </a>
        </div>

        {{-- Étudiant répertorié --}}
        <div class="action-card card-dark">
            <div class="card-icon-wrap icon-dark">
                <i class="fas fa-key"></i>
            </div>
            <h2>Étudiant Répertorié</h2>
            <p>Vous possédez déjà un matricule officiel ou vous êtes un ancien étudiant. Activez votre accès numérique.</p>
            <a href="{{ route('account.activate') }}" class="btn btn-dark" id="btn-activate" onclick="handleClick(this)">
                <span class="btn-text"><i class="fas fa-unlock-alt"></i> ACTIVER MON ACCÈS</span>
                <span class="spinner"></span>
            </a>
        </div>

    </div>

    {{-- ── LIEN CONNEXION ── --}}
    <div class="login-link-wrap">
        <a href="{{ route('login') }}" class="login-link">
            <i class="fas fa-sign-in-alt"></i>
            Déjà inscrit ? Se connecter à mon espace
            <i class="fas fa-arrow-right" style="font-size:11px;"></i>
        </a>
    </div>

    {{-- ── ONGLETS : Comment ça marche + FAQ ── --}}
    <div class="tabs-section">
        <div class="tab-nav">
            <button class="tab-btn active" onclick="switchTab('steps', this)">
                <i class="fas fa-list-ol"></i> Comment ça marche ?
            </button>
            <button class="tab-btn" onclick="switchTab('faq', this)">
                <i class="fas fa-question-circle"></i> Questions fréquentes (FAQ)
            </button>
        </div>

        {{-- Onglet 1 : Étapes --}}
        <div id="tab-steps" class="tab-pane active">
            <div class="steps-grid">
                <div class="step-item">
                    <div class="step-num">1</div>
                    <div class="step-icon">🔐</div>
                    <h4>Créer un compte</h4>
                    <p>Inscription en ligne ou activation de votre matricule existant.</p>
                </div>
                <div class="step-item">
                    <div class="step-num">2</div>
                    <div class="step-icon">📋</div>
                    <h4>Remplir le dossier</h4>
                    <p>Informations personnelles et pièces justificatives (Acte, BAC, Photo).</p>
                </div>
                <div class="step-item">
                    <div class="step-num">3</div>
                    <div class="step-icon">✅</div>
                    <h4>Validation scolarité</h4>
                    <p>La scolarité examine votre dossier sous 48h à 72h ouvrables.</p>
                </div>
                <div class="step-item">
                    <div class="step-num">4</div>
                    <div class="step-icon">🎓</div>
                    <h4>Accès complet</h4>
                    <p>Téléchargez votre fiche d'inscription et consultez vos résultats.</p>
                </div>
            </div>
        </div>

        {{-- Onglet 2 : FAQ --}}
        <div id="tab-faq" class="tab-pane">
            <div class="faq-list">

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <div class="faq-question-left">
                            <i class="fas fa-exclamation-circle"></i>
                            Mon matricule ne fonctionne pas
                        </div>
                        <i class="fas fa-chevron-down faq-chevron"></i>
                    </div>
                    <div class="faq-answer">
                        Assurez-vous d'utiliser le matricule à 8 chiffres figurant sur votre récépissé ou carte étudiant. Si le problème persiste, contactez directement le service scolarité de votre faculté muni d'une pièce d'identité.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <div class="faq-question-left">
                            <i class="fas fa-file-upload"></i>
                            Quels fichiers puis-je envoyer ?
                        </div>
                        <i class="fas fa-chevron-down faq-chevron"></i>
                    </div>
                    <div class="faq-answer">
                        Nous acceptons les scans ou photos lisibles aux formats <strong>PDF, JPG et PNG</strong>. Chaque fichier ne doit pas dépasser <strong>2 Mo</strong>. Assurez-vous que les documents sont clairs et lisibles avant de les envoyer.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <div class="faq-question-left">
                            <i class="fas fa-user-edit"></i>
                            J'ai fait une erreur dans mon nom
                        </div>
                        <i class="fas fa-chevron-down faq-chevron"></i>
                    </div>
                    <div class="faq-answer">
                        Une fois le dossier soumis, les informations d'état civil (nom, prénom, date de naissance) ne peuvent plus être modifiées par vous-même. Seul un agent de la scolarité peut effectuer ces corrections après vérification physique de vos documents officiels.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <div class="faq-question-left">
                            <i class="fas fa-clock"></i>
                            Combien de temps pour valider mon dossier ?
                        </div>
                        <i class="fas fa-chevron-down faq-chevron"></i>
                    </div>
                    <div class="faq-answer">
                        Le délai de traitement est généralement de <strong>48h à 72h</strong> ouvrables après soumission. Vous serez notifié via votre espace étudiant dès que votre dossier aura été examiné. En période de forte affluence (rentrée académique), ce délai peut être légèrement allongé.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <div class="faq-question-left">
                            <i class="fas fa-lock"></i>
                            Pourquoi ma fiche est-elle indisponible ?
                        </div>
                        <i class="fas fa-chevron-down faq-chevron"></i>
                    </div>
                    <div class="faq-answer">
                        La fiche d'inscription est temporairement bloquée pendant la période de <strong>saisie des notes</strong> par les enseignants. Elle redeviendra accessible dès que les notes auront été publiées officiellement par la scolarité.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <div class="faq-question-left">
                            <i class="fas fa-redo"></i>
                            Mon dossier a été rejeté, que faire ?
                        </div>
                        <i class="fas fa-chevron-down faq-chevron"></i>
                    </div>
                    <div class="faq-answer">
                        Connectez-vous à votre espace étudiant pour voir le motif du rejet. Corrigez les éléments signalés (document illisible, photo non conforme, etc.) puis soumettez à nouveau votre dossier. Votre dossier sera réexaminé dans les meilleurs délais.
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

{{-- ══ FOOTER ══ --}}
<footer>
    <p><strong>République du Bénin</strong> — Ministère de l'Enseignement Supérieur et de la Recherche Scientifique</p>
    <p style="margin-top:6px;">&copy; {{ date('Y') }} <strong>Université d'Abomey-Calavi</strong> — Tous droits réservés.</p>
</footer>

<script>
    // ── ONGLETS ──
    function switchTab(name, btn) {
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + name).classList.add('active');
        btn.classList.add('active');
    }

    // ── FAQ ACCORDION ──
    function toggleFaq(el) {
        const item = el.closest('.faq-item');
        const isOpen = item.classList.contains('open');
        // Fermer tous
        document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
        // Ouvrir celui cliqué si fermé
        if (!isOpen) item.classList.add('open');
    }

    // ── BOUTONS : spinner + prévenir double-clic ──
    function handleClick(btn) {
    // 1. Vérifier si on est déjà en train de charger
    if (btn.classList.contains('loading')) {
        return false;
    }

    // 2. Ajouter la classe de chargement
    btn.classList.add('loading');

    // 3. Optionnel : Désactiver le clic pour éviter les doublons
    btn.style.pointerEvents = 'none';

    // Le lien sera suivi naturellement par le navigateur
    return true;
}

    // ── Remettre les boutons à zéro si on revient (bouton retour) ──
    window.addEventListener('pageshow', function(e) {
        if (e.persisted) {
            document.querySelectorAll('.btn').forEach(b => b.classList.remove('loading'));
        }
    });
</script>
</body>
</html>
