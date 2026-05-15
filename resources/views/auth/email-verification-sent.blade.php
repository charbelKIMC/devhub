<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérifiez votre email – DevHub</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/s-register.css') }}">
    <style>
        .verify-card {
            max-width: 480px;
            margin: 0 auto;
            background: rgba(255,255,255,0.97);
            border-radius: 20px;
            padding: 3rem 2.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            text-align: center;
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #7C3AED, #6D28D9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .icon-circle i { color: white; font-size: 2rem; }
        .verify-title { font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 0.75rem; }
        .verify-text  { color: #6B7280; line-height: 1.7; margin-bottom: 0.5rem; }
        .email-badge  {
            display: inline-block;
            background: #F3F4F6;
            color: #7C3AED;
            font-weight: 600;
            padding: 0.4rem 1rem;
            border-radius: 8px;
            margin: 0.75rem 0 1.5rem;
            font-size: 0.95rem;
        }
        .steps {
            background: #F9FAFB;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            text-align: left;
            margin-bottom: 1.5rem;
        }
        .steps p { color: #374151; font-size: 0.88rem; margin-bottom: 0.5rem; }
        .steps p:last-child { margin-bottom: 0; }
        .steps i { color: #7C3AED; width: 18px; }
        .btn-back-register {
            display: inline-block;
            color: #7C3AED;
            text-decoration: none;
            font-size: 0.88rem;
            margin-top: 1rem;
        }
        .btn-back-register:hover { text-decoration: underline; color: #6D28D9; }
    </style>
</head>
<body>
    <div class="background-image"></div>
    <div class="gradient-overlay"></div>

    <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;position:relative;z-index:10;">
        <div class="verify-card">

            <div class="icon-circle">
                <i class="fas fa-envelope-open-text"></i>
            </div>

            <div class="verify-title">Vérifiez votre email</div>

            <p class="verify-text">Un email de confirmation a été envoyé à :</p>

            <div class="email-badge">
                {{ session('email', 'votre adresse email') }}
            </div>

            <div class="steps">
                <p><i class="fas fa-inbox me-2"></i> Ouvrez votre boîte mail</p>
                <p><i class="fas fa-envelope me-2"></i> Cherchez un email de <strong>DevHub</strong></p>
                <p><i class="fas fa-mouse-pointer me-2"></i> Cliquez sur <strong>"Confirmer mon adresse email"</strong></p>
                <p><i class="fas fa-check-circle me-2"></i> Votre compte sera créé automatiquement</p>
            </div>

            <p class="verify-text" style="font-size:0.85rem;">
                Le lien expire dans <strong>24 heures</strong>.<br>
                Vérifiez aussi vos <strong>spams</strong> si vous ne voyez pas l'email.
            </p>

            <a href="{{ route('register') }}" class="btn-back-register">
                <i class="fas fa-arrow-left me-1"></i> Revenir à l'inscription
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
