<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #F5F3FF; margin: 0; padding: 30px 0; }
        .card { max-width: 480px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: #7C3AED; padding: 32px; text-align: center; }
        .header img { height: 36px; }
        .header h1 { color: white; font-size: 1.2rem; margin: 12px 0 0; font-weight: 700; }
        .body { padding: 36px 32px; }
        .otp-box { display: flex; justify-content: center; gap: 12px; margin: 28px 0; }
        .digit { width: 56px; height: 64px; background: #F5F3FF; border: 2px solid #7C3AED; border-radius: 12px; font-size: 2rem; font-weight: 800; color: #7C3AED; display: flex; align-items: center; justify-content: center; }
        .info { font-size: 0.82rem; color: #6B7280; text-align: center; line-height: 1.6; }
        .footer { background: #F9FAFB; padding: 20px 32px; text-align: center; font-size: 0.75rem; color: #9CA3AF; border-top: 1px solid #F3F4F6; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>DevHub — Réinitialisation du mot de passe</h1>
        </div>
        <div class="body">
            <p style="color:#374151;margin-bottom:8px;">Bonjour <strong>{{ $prenom }}</strong>,</p>
            <p style="color:#6B7280;font-size:0.9rem;line-height:1.6;">
                Voici votre code de vérification à 4 chiffres. Il expire dans <strong>2 minutes</strong>.
            </p>
            <div class="otp-box">
                @foreach(str_split($otp) as $digit)
                    <div class="digit">{{ $digit }}</div>
                @endforeach
            </div>
            <p class="info">
                Si vous n'avez pas demandé de réinitialisation, ignorez cet email.<br>
                Votre mot de passe reste inchangé.
            </p>
        </div>
        <div class="footer">
            © {{ date('Y') }} DevHub · Cet email a été envoyé automatiquement, ne pas répondre.
        </div>
    </div>
</body>
</html>
