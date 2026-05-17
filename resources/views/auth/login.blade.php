<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Campus Manager</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script>
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) { window.location.reload(); }
        });
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; height: 100vh; display: flex; overflow: hidden; }

        /* ── Left branding panel ── */
        .auth-left {
            width: 44%;
            flex-shrink: 0;
            background: linear-gradient(145deg, #16A34A 0%, #15803D 60%, #14532D 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px;
            position: relative;
            overflow: hidden;
        }
        .auth-left::before {
            content: '';
            position: absolute;
            top: -120px; right: -120px;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
        }
        .auth-left::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: rgba(255,255,255,.04);
        }

        /* ── Right form panel ── */
        .auth-right {
            flex: 1;
            background: #F8FAFC;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
            overflow-y: auto;
        }
        .auth-card {
            width: 100%;
            max-width: 440px;
            background: #FFFFFF;
            border-radius: 24px;
            padding: 40px;
            border: 1px solid #F1F5F9;
            box-shadow: 0 4px 24px rgba(15,23,42,.07);
        }

        /* ── Form elements ── */
        .f-group { margin-bottom: 18px; }
        .f-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 7px; }
        .f-input {
            width: 100%; height: 48px;
            border: 1.5px solid #E2E8F0;
            border-radius: 12px;
            padding: 0 14px;
            font-size: 14px; font-family: 'Inter', sans-serif;
            color: #0F172A; background: #FFFFFF;
            outline: none;
            transition: border-color .18s, box-shadow .18s;
        }
        .f-input:focus { border-color: #22C55E; box-shadow: 0 0 0 3px rgba(34,197,94,.10); }
        .f-input.error { border-color: #EF4444; box-shadow: 0 0 0 3px rgba(239,68,68,.08); }
        .f-error { font-size: 12px; color: #EF4444; margin-top: 5px; display: none; }
        .f-error.visible { display: block; }

        /* ── Buttons ── */
        .btn-submit {
            width: 100%; height: 52px;
            background: #16A34A; color: #fff;
            border: none; border-radius: 14px;
            font-size: 15px; font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            transition: transform .18s, box-shadow .18s, background .18s;
            box-shadow: 0 4px 16px rgba(22,163,74,.22);
        }
        .btn-submit:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(22,163,74,.28); }
        .btn-submit:disabled { background: #86EFAC; cursor: not-allowed; transform: none; box-shadow: none; }

        /* ── Spinner ── */
        .spinner {
            width: 18px; height: 18px;
            border: 2.5px solid rgba(255,255,255,.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            display: none;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Alert global ── */
        .alert-error {
            background: #FEF2F2; border: 1px solid #FECACA;
            border-radius: 12px; padding: 12px 16px;
            font-size: 13px; color: #DC2626;
            display: none; align-items: center; gap: 10px;
            margin-bottom: 20px;
        }
        .alert-error.visible { display: flex; }

        /* ── Feature list (left panel) ── */
        .feature-item {
            display: flex; align-items: center; gap: 12px;
            color: rgba(255,255,255,.88);
            font-size: 14px; font-weight: 500;
            margin-bottom: 16px;
        }
        .feature-dot {
            width: 28px; height: 28px; border-radius: 8px;
            background: rgba(255,255,255,.15);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        /* ── Responsive mobile ── */
        @media (max-width: 768px) {
            body { flex-direction: column; height: auto; overflow: auto; }
            .auth-left { width: 100%; padding: 32px 24px; min-height: auto; }
            .auth-left .features-section { display: none; }
            .auth-right { padding: 24px 16px; }
            .auth-card { padding: 28px 24px; }
        }
    </style>
</head>
<body>

<!-- ══ LEFT BRANDING ══ -->
<div class="auth-left">
    <div style="position:relative; z-index:1;">
        <!-- Logo -->
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:48px;">
            <div style="width:48px; height:48px; border-radius:14px; background:rgba(255,255,255,.2); display:flex; align-items:center; justify-content:center; backdrop-filter:blur(8px);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z" fill="white"/>
                </svg>
            </div>
            <div>
                <div style="font-size:20px; font-weight:700; color:#fff; line-height:1.2;">Campus Manager</div>
                <div style="font-size:12px; color:rgba(255,255,255,.65); margin-top:1px;">University Management</div>
            </div>
        </div>

        <!-- Tagline -->
        <h2 style="font-size:32px; font-weight:800; color:#fff; line-height:1.25; margin-bottom:16px; letter-spacing:-.5px;">
            Gérez votre université<br>avec confiance
        </h2>
        <p style="font-size:15px; color:rgba(255,255,255,.75); line-height:1.7; margin-bottom:40px; max-width:340px;">
            Plateforme complète de gestion des étudiants, filières, inscriptions et statistiques académiques.
        </p>

        <!-- Features -->
        <div class="features-section">
            @foreach([
                ['Gestion complète des étudiants et dossiers'],
                ['Statistiques et analytics en temps réel'],
                ['Sécurité et contrôle des accès avancé'],
                ['Interface rapide et intuitive'],
            ] as [$feat])
            <div class="feature-item">
                <div class="feature-dot">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                {{ $feat }}
            </div>
            @endforeach
        </div>
    </div>

    <!-- Bottom -->
    <div style="position:relative; z-index:1; border-top:1px solid rgba(255,255,255,.15); padding-top:24px;">
        <div style="display:flex; gap:24px;">
            @foreach(['1 248 Étudiants', '6 Filières', '15 Admins'] as $stat)
            <div>
                <div style="font-size:18px; font-weight:700; color:#fff;">{{ explode(' ', $stat)[0] }}</div>
                <div style="font-size:12px; color:rgba(255,255,255,.6);">{{ implode(' ', array_slice(explode(' ', $stat), 1)) }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- ══ RIGHT FORM ══ -->
<div class="auth-right">
    <div class="auth-card">

        <!-- Header -->
        <div style="margin-bottom:28px;">
            <h1 style="font-size:24px; font-weight:700; color:#0F172A; margin-bottom:6px;">Connexion</h1>
            <p style="font-size:14px; color:#64748B;">Accédez à votre espace administrateur</p>
        </div>

        <!-- Global error alert -->
        <div class="alert-error" id="alert-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span id="alert-error-msg">Email ou mot de passe incorrect.</span>
        </div>

        @if ($errors->has('email'))
        <div class="alert-error visible" style="display:flex;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span>{{ $errors->first('email') }}</span>
        </div>
        @endif

        <form id="login-form" method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <!-- Email -->
            <div class="f-group">
                <label class="f-label" for="email">Email institutionnel</label>
                <input type="email" id="email" name="email" class="f-input {{ $errors->has('email') ? 'error' : '' }}"
                    value="{{ old('email') }}" placeholder="admin@campus.ma" autocomplete="email">
                <div class="f-error" id="err-email">Veuillez saisir un email valide.</div>
            </div>

            <!-- Password -->
            <div class="f-group">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:7px;">
                    <label class="f-label" for="password" style="margin:0;">Mot de passe</label>
                    <a href="#" style="font-size:12px; font-weight:500; color:#16A34A;">Mot de passe oublié ?</a>
                </div>
                <div style="position:relative;">
                    <input type="password" id="password" name="password" class="f-input"
                        placeholder="••••••••" autocomplete="current-password" style="padding-right:46px;">
                    <button type="button" id="toggle-password" style="position:absolute; right:14px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#94A3B8; padding:0; display:flex; align-items:center;">
                        <svg id="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                <div class="f-error" id="err-password">Le mot de passe est requis.</div>
            </div>

            <!-- Remember + submit -->
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13px; color:#475569; font-weight:500; user-select:none;">
                    <input type="checkbox" name="remember" id="remember" style="width:16px; height:16px; accent-color:#16A34A; cursor:pointer;">
                    Se souvenir de moi
                </label>
            </div>

            <button type="submit" class="btn-submit" id="btn-submit">
                <div class="spinner" id="spinner"></div>
                <span id="btn-text">Se connecter</span>
            </button>
        </form>

        <!-- Register link -->
        <p style="text-align:center; margin-top:24px; font-size:13px; color:#64748B;">
            Pas encore de compte ?
            <a href="{{ route('register') }}" style="font-weight:600; color:#16A34A; margin-left:4px;">Créer un compte</a>
        </p>
    </div>
</div>

<script>
(function () {
    const form      = document.getElementById('login-form');
    const btnSubmit = document.getElementById('btn-submit');
    const btnText   = document.getElementById('btn-text');
    const spinner   = document.getElementById('spinner');
    const emailEl   = document.getElementById('email');
    const passEl    = document.getElementById('password');
    const errEmail  = document.getElementById('err-email');
    const errPass   = document.getElementById('err-password');
    const toggleBtn = document.getElementById('toggle-password');

    // ── Toggle password visibility
    toggleBtn.addEventListener('click', () => {
        const isPass = passEl.type === 'password';
        passEl.type = isPass ? 'text' : 'password';
        document.getElementById('eye-icon').innerHTML = isPass
            ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
            : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    });

    // ── Inline validation on blur
    function validateEmail() {
        const val = emailEl.value.trim();
        const ok  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
        emailEl.classList.toggle('error', !ok && val !== '');
        errEmail.classList.toggle('visible', !ok && val !== '');
        return ok;
    }
    function validatePassword() {
        const ok = passEl.value.length > 0;
        passEl.classList.toggle('error', !ok);
        errPass.classList.toggle('visible', !ok);
        return ok;
    }

    emailEl.addEventListener('blur', validateEmail);
    emailEl.addEventListener('input', () => {
        if (emailEl.classList.contains('error')) validateEmail();
    });
    passEl.addEventListener('blur', validatePassword);
    passEl.addEventListener('input', () => {
        if (passEl.classList.contains('error')) validatePassword();
    });

    // ── Form submit: loading state
    form.addEventListener('submit', (e) => {
        const emailOk = validateEmail();
        const passOk  = validatePassword();
        if (!emailOk || !passOk) { e.preventDefault(); return; }

        btnSubmit.disabled = true;
        spinner.style.display = 'block';
        btnText.textContent = 'Connexion en cours…';
    });
})();
</script>
</body>
</html>
