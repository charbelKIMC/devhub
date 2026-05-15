<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mot de passe oublié – DevHub</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif; min-height:100vh; background:#fff; display:flex; flex-direction:column; }

        .btn-back { position:fixed; top:28px; left:32px; gap:8px; background:none; border:1px solid #7C3AED; border-radius:8px; padding:5px 10px; font-size:14px; font-weight:600; color:#7C3AED; cursor:pointer; text-decoration:none; transition:all .25s; z-index:100; display:inline-flex; align-items:center; gap:6px; }
        .btn-back:hover { background:#7C3AED; color:#fff; }

        .logo-center { position:fixed; top:0; left:0; width:100%; height:100%; background-image:url("{{ asset('img/logo-devhub.png') }}"); background-size:cover; background-position:center; z-index:1; opacity:0.04; }

        .page-wrapper { flex:1; display:flex; align-items:center; justify-content:center; padding:80px 20px 40px; z-index:3; position:relative; }
        .form-card { width:100%; max-width:480px; text-align:center; }

        .steps-bar { display:flex; align-items:center; justify-content:center; margin-bottom:35px; }
        .step-dot { width:36px; height:36px; border-radius:50%; border:1px solid #ddd; background:#fff; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:600; color:#bbb; transition:all .4s; }
        .step-dot.active { border-color:#7C3AED; background:#7C3AED; color:#fff; }
        .step-dot.done   { border-color:#7C3AED; background:#7C3AED; color:#fff; }
        .step-line { width:70px; height:1px; background:#ddd; transition:background .4s; }
        .step-line.done { background:#7C3AED; }

        .step-icon-wrapper { width:70px; height:70px; background:#7C3AED; border-radius:14px; display:flex; align-items:center; justify-content:center; margin:0 auto 15px; animation:iconPop .5s cubic-bezier(.175,.885,.32,1.275) both; }
        .step-icon-wrapper i { font-size:28px; color:#fff; }
        @keyframes iconPop { 0%{transform:scale(.5);opacity:0} 100%{transform:scale(1);opacity:1} }

        .step-title { font-size:1.5rem; font-weight:700; color:#111827; letter-spacing:-.5px; animation:fadeUp .4s ease both; }
        .step-subtitle { font-size:1rem; color:#666; margin-bottom:20px; line-height:1.6; animation:fadeUp .4s .1s ease both; }
        .step-subtitle strong { color:#7C3AED; }
        @keyframes fadeUp { 0%{transform:translateY(16px);opacity:0} 100%{transform:translateY(0);opacity:1} }

        .form-group { margin-bottom:10px; text-align:left; animation:fadeUp .4s .15s ease both; }
        label { display:block; color:#111827; margin-bottom:5px; font-weight:600; font-size:.9rem; }
        .input-group { position:relative; }
        .input-group i { position:absolute; left:16px; top:50%; transform:translateY(-35%); color:#888; pointer-events:none; }
        input[type="email"],input[type="text"],input[type="password"] { width:100%; padding:12px 16px 12px 45px; border:1px solid #e0e0e0; border-radius:8px; font-size:15px; transition:all .3s; }
        input:focus { outline:none; border-color:#7C3AED; box-shadow:0 0 0 3px rgba(124,58,237,.1); }

        .otp-container { display:flex; gap:12px; justify-content:center; margin-bottom:10px; animation:fadeUp .4s .15s ease both; }
        .otp-input { width:52px !important; height:58px; text-align:center; font-size:2rem; font-weight:700; border:1px solid #e0e0e0; border-radius:12px; padding:0 !important; transition:all .25s; }
        .otp-input:focus { border-color:#7C3AED; box-shadow:0 0 0 3px rgba(124,58,237,.1); outline:none; }
        .otp-input.filled { border-color:#7C3AED; background:#7C3AED; color:#fff; }

        .otp-timer { font-size:13px; color:#888; margin-bottom:15px; animation:fadeUp .4s .2s ease both; }
        .otp-timer span { font-weight:600; color:#7C3AED; }
        .resend-link { color:#7C3AED; font-weight:600; text-decoration:underline; cursor:pointer; display:none; }
        .resend-link.visible { display:inline; }

        .strength-bar { height:5px; background:#eee; border-radius:3px; margin-top:10px; overflow:hidden; }
        .strength-fill { height:100%; width:0%; background:#7C3AED; border-radius:3px; transition:width .4s,background .4s; }
        .strength-label { font-size:12px; color:#888; margin-top:6px; font-weight:600; text-align:right; }

        .btn-main { width:100%; padding:16px; background:#7C3AED; color:#fff; border:none; border-radius:8px; font-size:16px; font-weight:700; cursor:pointer; transition:all .3s; text-transform:uppercase; letter-spacing:1px; }
        .btn-main:hover { background:#6D28D9; }
        .btn-main:disabled { background:#DDD6FE; cursor:not-allowed; }

        .alert-msg { border-radius:8px; padding:.65rem 1rem; font-size:.85rem; margin-top:.75rem; display:none; }
        .alert-msg.error   { background:#FEE2E2; color:#DC2626; }
        .alert-msg.success { background:#D1FAE5; color:#065F46; }

        .success-wrapper { display:none; flex-direction:column; align-items:center; animation:fadeUp .5s ease both; }
        .success-wrapper.show { display:flex; }
        .success-icon { width:90px; height:90px; background:#7C3AED; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 24px; animation:iconPop .6s cubic-bezier(.175,.885,.32,1.275) both; }
        .success-icon i { font-size:40px; color:#fff; }
        .btn-login-redirect { display:inline-block; margin-top:24px; padding:16px 32px; background:#7C3AED; color:#fff; border-radius:10px; font-size:15px; font-weight:700; text-decoration:none; }
        .btn-login-redirect:hover { background:#6D28D9; color:#fff; }

        @media (max-width:540px) {
            .otp-input { width:44px !important; height:52px; font-size:1.5rem; }
            .step-line { width:40px; }
        }
    </style>
</head>
<body>
    <div class="logo-center"></div>

    <a href="{{ route('login') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Retour
    </a>

    <div class="page-wrapper">
        <div class="form-card">

            <div class="steps-bar">
                <div class="step-dot active" id="dot-1">1</div>
                <div class="step-line" id="line-1"></div>
                <div class="step-dot" id="dot-2">2</div>
                <div class="step-line" id="line-2"></div>
                <div class="step-dot" id="dot-3">3</div>
            </div>

            {{-- ÉTAPE 1 : Email --}}
            <div id="step-1">
                <div class="step-icon-wrapper"><i class="fas fa-envelope-open-text"></i></div>
                <div class="step-title">Mot de passe oublié ?</div>
                <p class="step-subtitle">Entrez votre adresse email. Nous vous enverrons un code de vérification.</p>
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" placeholder="votre@email.com">
                    </div>
                </div>
                <div class="alert-msg" id="msg-1"></div>
                <button class="btn-main mt-2" id="btn-1" onclick="sendOtp()">
                    <i class="fas fa-paper-plane me-2"></i> Envoyer le code
                </button>
            </div>

            {{-- ÉTAPE 2 : OTP --}}
            <div id="step-2" style="display:none;">
                <div class="step-icon-wrapper"><i class="fas fa-shield-halved"></i></div>
                <div class="step-title">Vérification</div>
                <p class="step-subtitle">Un code à 4 chiffres a été envoyé à <strong id="email-display"></strong></p>
                <div class="otp-container">
                    <input type="text" class="otp-input" maxlength="1" id="otp-1" inputmode="numeric">
                    <input type="text" class="otp-input" maxlength="1" id="otp-2" inputmode="numeric">
                    <input type="text" class="otp-input" maxlength="1" id="otp-3" inputmode="numeric">
                    <input type="text" class="otp-input" maxlength="1" id="otp-4" inputmode="numeric">
                </div>
                <div class="otp-timer" id="otp-timer">
                    Code expire dans <span id="timer-count">02:00</span>
                    <span class="resend-link" id="resend-link" onclick="renvoyerOtp()">Renvoyer le code</span>
                </div>
                <div class="alert-msg" id="msg-2"></div>
                <button class="btn-main" id="btn-2" onclick="verifierOtp()">
                    <i class="fas fa-check me-2"></i> Vérifier le code
                </button>
            </div>

            {{-- ÉTAPE 3 : Nouveau mot de passe --}}
            <div id="step-3" style="display:none;">
                <div class="step-icon-wrapper"><i class="fas fa-key"></i></div>
                <div class="step-title">Nouveau mot de passe</div>
                <p class="step-subtitle">Choisissez un mot de passe fort et sécurisé.</p>
                <div class="form-group">
                    <label>Nouveau mot de passe</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="new-password" placeholder="••••••••" oninput="checkStrength(this.value)">
                    </div>
                    <div class="strength-bar"><div class="strength-fill" id="strength-fill"></div></div>
                    <div class="strength-label" id="strength-label">Au moins 8 caractères</div>
                </div>
                <div class="form-group">
                    <label>Confirmer le mot de passe</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm-password" placeholder="••••••••">
                    </div>
                </div>
                <div class="alert-msg" id="msg-3"></div>
                <button class="btn-main mt-2" id="btn-3" onclick="reinitialiser()">
                    <i class="fas fa-check-circle me-2"></i> Réinitialiser
                </button>
            </div>

            {{-- SUCCÈS --}}
            <div id="step-success" class="success-wrapper">
                <div class="success-icon"><i class="fas fa-check"></i></div>
                <div class="step-title">Mot de passe réinitialisé !</div>
                <p class="step-subtitle">Votre mot de passe a été mis à jour avec succès.</p>
                <a href="{{ route('login') }}" class="btn-login-redirect">
                    <i class="fas fa-arrow-right me-2"></i> Se connecter
                </a>
            </div>

        </div>
    </div>

    <script>
        const CSRF   = document.querySelector('meta[name="csrf-token"]').content;
        const BASE   = '{{ rtrim(url('/'), '/') }}';
        let emailSaisi = '';
        let timerInterval;

        // ─── Helpers ───
        function showAlert(id, msg, type) {
            const el = document.getElementById(id);
            el.textContent = msg;
            el.className = 'alert-msg ' + type;
            el.style.display = 'block';
        }
        function hideAlert(id) { document.getElementById(id).style.display = 'none'; }

        function setLoading(btnId, loading) {
            const btn = document.getElementById(btnId);
            btn.disabled = loading;
            if (loading) btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Patientez...';
        }

        function updateStepBar(step) {
            for (let i = 1; i <= 3; i++) {
                const dot = document.getElementById('dot-' + i);
                dot.classList.remove('active', 'done');
                if (i < step)  dot.classList.add('done');
                if (i === step) dot.classList.add('active');
            }
            for (let i = 1; i <= 2; i++) {
                document.getElementById('line-' + i).classList.toggle('done', i < step);
            }
        }

        function showStep(n) {
            ['step-1','step-2','step-3','step-success'].forEach(id => {
                document.getElementById(id).style.display = 'none';
            });
            const target = n === 'success' ? 'step-success' : 'step-' + n;
            const el = document.getElementById(target);
            el.style.display = n === 'success' ? 'flex' : 'block';
            const icon = el.querySelector('.step-icon-wrapper,.success-icon');
            if (icon) { icon.style.animation='none'; icon.offsetHeight; icon.style.animation=''; }
        }

        // ─── Étape 1 : envoyer OTP ───
        async function sendOtp() {
            const email = document.getElementById('email').value.trim();
            if (!email) { showAlert('msg-1', 'Veuillez entrer votre adresse email.', 'error'); return; }
            hideAlert('msg-1');
            setLoading('btn-1', true);

            const res  = await fetch(BASE + '/mot-de-passe-oublie/envoyer-otp', {
                method: 'POST',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
                body: JSON.stringify({ email })
            });
            const data = await res.json();
            setLoading('btn-1', false);
            document.getElementById('btn-1').innerHTML = '<i class="fas fa-paper-plane me-2"></i> Envoyer le code';

            if (!data.success) { showAlert('msg-1', data.message || 'Erreur.', 'error'); return; }

            emailSaisi = email;
            document.getElementById('email-display').textContent = email;
            showStep(2);
            updateStepBar(2);
            startTimer();
            setTimeout(() => document.getElementById('otp-1').focus(), 100);
        }

        // ─── Étape 2 : vérifier OTP ───
        async function verifierOtp() {
            const otp = ['otp-1','otp-2','otp-3','otp-4'].map(id => document.getElementById(id).value).join('');
            if (otp.length < 4) { showAlert('msg-2', 'Entrez les 4 chiffres du code.', 'error'); return; }
            hideAlert('msg-2');
            setLoading('btn-2', true);

            const res  = await fetch(BASE + '/mot-de-passe-oublie/verifier-otp', {
                method: 'POST',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
                body: JSON.stringify({ email: emailSaisi, otp })
            });
            const data = await res.json();
            setLoading('btn-2', false);
            document.getElementById('btn-2').innerHTML = '<i class="fas fa-check me-2"></i> Vérifier le code';

            if (!data.success) { showAlert('msg-2', data.message || 'Code incorrect.', 'error'); return; }

            clearInterval(timerInterval);
            showStep(3);
            updateStepBar(3);
            setTimeout(() => document.getElementById('new-password').focus(), 100);
        }

        // ─── Étape 3 : nouveau mot de passe ───
        async function reinitialiser() {
            const p1 = document.getElementById('new-password').value;
            const p2 = document.getElementById('confirm-password').value;
            if (p1.length < 8) { showAlert('msg-3', 'Le mot de passe doit contenir au moins 8 caractères.', 'error'); return; }
            if (p1 !== p2)      { showAlert('msg-3', 'Les mots de passe ne correspondent pas.', 'error'); return; }
            hideAlert('msg-3');
            setLoading('btn-3', true);

            const res  = await fetch(BASE + '/mot-de-passe-oublie/reinitialiser', {
                method: 'POST',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
                body: JSON.stringify({ email: emailSaisi, mot_de_passe: p1, confirmation: p2 })
            });
            const data = await res.json();
            setLoading('btn-3', false);
            document.getElementById('btn-3').innerHTML = '<i class="fas fa-check-circle me-2"></i> Réinitialiser';

            if (!data.success) { showAlert('msg-3', data.message || 'Erreur.', 'error'); return; }

            showStep('success');
            document.querySelector('.steps-bar').style.opacity = '0.3';
        }

        // ─── Timer OTP ───
        function startTimer() {
            let timeLeft = 120;
            document.getElementById('resend-link').classList.remove('visible');
            document.getElementById('timer-count').textContent = '02:00';
            clearInterval(timerInterval);
            timerInterval = setInterval(() => {
                timeLeft--;
                const m = String(Math.floor(timeLeft/60)).padStart(2,'0');
                const s = String(timeLeft%60).padStart(2,'0');
                document.getElementById('timer-count').textContent = m+':'+s;
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    document.getElementById('timer-count').textContent = '00:00';
                    document.getElementById('resend-link').classList.add('visible');
                }
            }, 1000);
        }

        async function renvoyerOtp() {
            document.querySelectorAll('.otp-input').forEach(el => { el.value=''; el.classList.remove('filled'); });
            hideAlert('msg-2');
            await sendOtpSilent();
            startTimer();
            document.getElementById('otp-1').focus();
        }

        async function sendOtpSilent() {
            await fetch(BASE + '/mot-de-passe-oublie/envoyer-otp', {
                method: 'POST',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
                body: JSON.stringify({ email: emailSaisi })
            });
        }

        // ─── Champs OTP ───
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.otp-input');
            inputs.forEach((input, i) => {
                input.addEventListener('input', e => {
                    const val = e.target.value.replace(/\D/g,'');
                    e.target.value = val;
                    if (val) { e.target.classList.add('filled'); if (i < inputs.length-1) inputs[i+1].focus(); }
                    else       e.target.classList.remove('filled');
                });
                input.addEventListener('keydown', e => {
                    if (e.key==='Backspace' && !input.value && i>0) {
                        inputs[i-1].focus(); inputs[i-1].value=''; inputs[i-1].classList.remove('filled');
                    }
                });
            });

            // Enter sur email
            document.getElementById('email').addEventListener('keydown', e => { if (e.key==='Enter') sendOtp(); });
        });

        // ─── Force mot de passe ───
        function checkStrength(value) {
            let score = 0;
            if (value.length >= 8)           score++;
            if (/[A-Z]/.test(value))         score++;
            if (/[0-9]/.test(value))         score++;
            if (/[^a-zA-Z0-9]/.test(value))  score++;
            const levels = [
                {w:'0%',  bg:'#eee',    txt:'Au moins 8 caractères'},
                {w:'25%', bg:'#EF4444', txt:'Faible'},
                {w:'50%', bg:'#F59E0B', txt:'Moyen'},
                {w:'75%', bg:'#10B981', txt:'Bon'},
                {w:'100%',bg:'#7C3AED', txt:'Excellent'},
            ];
            const lvl = value.length === 0 ? levels[0] : levels[score];
            document.getElementById('strength-fill').style.width      = lvl.w;
            document.getElementById('strength-fill').style.background = lvl.bg;
            document.getElementById('strength-label').textContent     = lvl.txt;
        }
    </script>
</body>
</html>
