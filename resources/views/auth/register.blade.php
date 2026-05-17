<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte — Campus Manager</title>
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

        .auth-left {
            width: 40%;
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
            top: -100px; right: -100px;
            width: 360px; height: 360px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
        }
        .auth-left::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: rgba(255,255,255,.04);
        }
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
            max-width: 480px;
            background: #FFFFFF;
            border-radius: 24px;
            padding: 40px;
            border: 1px solid #F1F5F9;
            box-shadow: 0 4px 24px rgba(15,23,42,.07);
        }
        .f-group { margin-bottom: 16px; }
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

        /* Strength indicator */
        .strength-bar { height: 4px; border-radius: 2px; margin-top: 8px; background: #F1F5F9; overflow: hidden; }
        .strength-fill { height: 100%; border-radius: 2px; width: 0%; transition: width .3s, background .3s; }

        .btn-submit {
            width: 100%; height: 52px;
            background: #16A34A; color: #fff;
            border: none; border-radius: 14px;
            font-size: 15px; font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            transition: transform .18s, box-shadow .18s;
            box-shadow: 0 4px 16px rgba(22,163,74,.22);
        }
        .btn-submit:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(22,163,74,.28); }
        .btn-submit:disabled { background: #86EFAC; cursor: not-allowed; transform: none; box-shadow: none; }

        .spinner { width: 18px; height: 18px; border: 2.5px solid rgba(255,255,255,.35); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .step-indicator { display: flex; align-items: center; gap: 8px; margin-bottom: 28px; }
        .step-dot { width: 8px; height: 8px; border-radius: 50%; background: #DCFCE7; }
        .step-dot.active { background: #16A34A; width: 24px; border-radius: 4px; }

        @media (max-width: 768px) {
            body { flex-direction: column; height: auto; overflow: auto; }
            .auth-left { width: 100%; padding: 28px 24px; }
            .auth-right { padding: 20px 16px; }
            .auth-card { padding: 28px 20px; }
        }
    </style>
</head>
<body>

<!-- ══ LEFT BRANDING ══ -->
<div class="auth-left">
    <div style="position:relative; z-index:1;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:44px;">
            <div style="width:48px; height:48px; border-radius:14px; background:rgba(255,255,255,.2); display:flex; align-items:center; justify-content:center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z" fill="white"/>
                </svg>
            </div>
            <div>
                <div style="font-size:20px; font-weight:700; color:#fff;">Campus Manager</div>
                <div style="font-size:12px; color:rgba(255,255,255,.6);">University Management</div>
            </div>
        </div>

        <h2 style="font-size:30px; font-weight:800; color:#fff; line-height:1.3; margin-bottom:14px; letter-spacing:-.5px;">
            Bienvenue sur<br>Campus Manager
        </h2>
        <p style="font-size:14px; color:rgba(255,255,255,.75); line-height:1.7; margin-bottom:36px;">
            Créez votre compte administrateur pour commencer à gérer votre établissement.
        </p>

        <!-- Steps visual -->
        <div style="display:flex; flex-direction:column; gap:16px;">
            @foreach([
                ['1','Créez votre compte admin'],
                ['2','Configurez votre université'],
                ['3','Importez vos étudiants'],
            ] as [$num, $label])
            <div style="display:flex; align-items:center; gap:14px;">
                <div style="width:32px; height:32px; border-radius:50%; background:rgba(255,255,255,.18); display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; color:#fff; flex-shrink:0;">{{ $num }}</div>
                <span style="font-size:14px; color:rgba(255,255,255,.85); font-weight:500;">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div style="position:relative; z-index:1; border-top:1px solid rgba(255,255,255,.15); padding-top:20px;">
        <p style="font-size:13px; color:rgba(255,255,255,.6);">
            Déjà un compte ?
            <a href="{{ route('login') }}" style="color:#fff; font-weight:600; margin-left:4px;">Se connecter →</a>
        </p>
    </div>
</div>

<!-- ══ RIGHT FORM ══ -->
<div class="auth-right">
    <div class="auth-card">

        <div class="step-indicator">
            <div class="step-dot active"></div>
            <div class="step-dot"></div>
            <div class="step-dot"></div>
        </div>

        <div style="margin-bottom:28px;">
            <h1 style="font-size:22px; font-weight:700; color:#0F172A; margin-bottom:5px;">Créer un compte administrateur</h1>
            <p style="font-size:13px; color:#64748B;">Remplissez les informations ci-dessous pour commencer</p>
        </div>

        @if ($errors->any())
        <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:12px; padding:12px 16px; font-size:13px; color:#DC2626; display:flex; align-items:flex-start; gap:10px; margin-bottom:20px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0; margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div>{{ $errors->first() }}</div>
        </div>
        @endif

        <form id="register-form" method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            <!-- Name -->
            <div class="f-group">
                <label class="f-label" for="name">Nom complet</label>
                <input type="text" id="name" name="name" class="f-input {{ $errors->has('name') ? 'error' : '' }}"
                    value="{{ old('name') }}" placeholder="Ex : Ahmed Benali" autocomplete="name">
                <div class="f-error" id="err-name">Le nom est requis.</div>
            </div>

            <!-- Email -->
            <div class="f-group">
                <label class="f-label" for="email">Email institutionnel</label>
                <input type="email" id="email" name="email" class="f-input {{ $errors->has('email') ? 'error' : '' }}"
                    value="{{ old('email') }}" placeholder="admin@campus.ma" autocomplete="email">
                <div class="f-error" id="err-email">Veuillez saisir un email valide.</div>
            </div>

            <!-- Passwords side by side -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div class="f-group">
                    <label class="f-label" for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="f-input"
                        placeholder="Min. 8 caractères" autocomplete="new-password">
                    <div class="strength-bar"><div class="strength-fill" id="strength-fill"></div></div>
                    <div class="f-error" id="err-password">Min. 8 caractères requis.</div>
                </div>
                <div class="f-group">
                    <label class="f-label" for="password_confirmation">Confirmer</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="f-input"
                        placeholder="Répétez le mot de passe" autocomplete="new-password">
                    <div class="f-error" id="err-confirm">Les mots de passe ne correspondent pas.</div>
                </div>
            </div>

            <!-- Terms notice -->
            <div style="background:#F0FDF4; border-radius:10px; padding:12px 14px; margin-bottom:22px; display:flex; align-items:flex-start; gap:10px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2" style="flex-shrink:0; margin-top:1px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <p style="font-size:12px; color:#15803D; line-height:1.5;">
                    Ce compte aura accès complet à l'administration. Assurez-vous d'utiliser un mot de passe fort et sécurisé.
                </p>
            </div>

            <button type="submit" class="btn-submit" id="btn-submit">
                <div class="spinner" id="spinner"></div>
                <span id="btn-text">Créer mon compte</span>
            </button>
        </form>

        <p style="text-align:center; margin-top:20px; font-size:13px; color:#64748B;">
            Déjà un compte ?
            <a href="{{ route('login') }}" style="font-weight:600; color:#16A34A; margin-left:4px;">Se connecter</a>
        </p>
    </div>
</div>

<script>
(function () {
    const form    = document.getElementById('register-form');
    const nameEl  = document.getElementById('name');
    const emailEl = document.getElementById('email');
    const passEl  = document.getElementById('password');
    const confEl  = document.getElementById('password_confirmation');
    const fill    = document.getElementById('strength-fill');
    const btnSubmit = document.getElementById('btn-submit');
    const spinner   = document.getElementById('spinner');
    const btnText   = document.getElementById('btn-text');

    // ── Password strength
    passEl.addEventListener('input', () => {
        const v = passEl.value;
        let score = 0;
        if (v.length >= 8)  score++;
        if (/[A-Z]/.test(v)) score++;
        if (/[0-9]/.test(v)) score++;
        if (/[^A-Za-z0-9]/.test(v)) score++;
        const pct = score * 25;
        const colors = ['#EF4444','#F97316','#EAB308','#22C55E'];
        fill.style.width = pct + '%';
        fill.style.background = colors[score - 1] || '#F1F5F9';
    });

    // ── Validators
    function validateName()  { const ok = nameEl.value.trim().length > 0; nameEl.classList.toggle('error', !ok); document.getElementById('err-name').classList.toggle('visible', !ok); return ok; }
    function validateEmail() { const ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailEl.value.trim()); emailEl.classList.toggle('error', !ok && emailEl.value !== ''); document.getElementById('err-email').classList.toggle('visible', !ok && emailEl.value !== ''); return ok; }
    function validatePassword() { const ok = passEl.value.length >= 8; passEl.classList.toggle('error', !ok && passEl.value !== ''); document.getElementById('err-password').classList.toggle('visible', !ok && passEl.value !== ''); return ok; }
    function validateConfirm() { const ok = confEl.value === passEl.value && confEl.value.length > 0; confEl.classList.toggle('error', !ok && confEl.value !== ''); document.getElementById('err-confirm').classList.toggle('visible', !ok && confEl.value !== ''); return ok; }

    nameEl.addEventListener('blur', validateName);
    emailEl.addEventListener('blur', validateEmail);
    passEl.addEventListener('blur', validatePassword);
    confEl.addEventListener('blur', validateConfirm);

    [nameEl, emailEl, passEl, confEl].forEach(el => {
        el.addEventListener('input', () => { if (el.classList.contains('error')) el.dispatchEvent(new Event('blur')); });
    });

    form.addEventListener('submit', (e) => {
        const ok = [validateName(), validateEmail(), validatePassword(), validateConfirm()].every(Boolean);
        if (!ok) { e.preventDefault(); return; }
        btnSubmit.disabled = true;
        spinner.style.display = 'block';
        btnText.textContent = 'Création en cours…';
    });
})();
</script>
</body>
</html>
