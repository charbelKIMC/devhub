<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#F5F3FF;padding:2rem;margin:0;">
<div style="max-width:520px;margin:0 auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div style="background:#7C3AED;padding:2rem;text-align:center;">
        <span style="color:white;font-size:1.4rem;font-weight:800;">DevHub</span>
    </div>
    <div style="padding:2rem;">
        <h2 style="color:#111827;font-size:1.2rem;margin-bottom:0.5rem;">Félicitations {{ $prenom }} !</h2>
        <p style="color:#6B7280;line-height:1.7;margin-bottom:1rem;">
            Votre candidature pour rejoindre le programme de mentorat <strong>DevHub</strong> a été <strong style="color:#059669;">acceptée</strong>.
        </p>
        <p style="color:#6B7280;line-height:1.7;margin-bottom:1.5rem;">
            Pour finaliser la création de votre compte mentor, cliquez sur le bouton ci-dessous et créez votre mot de passe de connexion.
            Ce lien est valable <strong>72 heures</strong>.
        </p>
        <div style="text-align:center;margin:1.5rem 0;">
            <a href="{{ $lien }}" style="background:#7C3AED;color:white;padding:0.85rem 2.5rem;border-radius:10px;text-decoration:none;font-weight:700;font-size:1rem;display:inline-block;">
                Continuer en tant que mentor
            </a>
        </div>
        <p style="color:#9CA3AF;font-size:0.83rem;">
            Si le bouton ne fonctionne pas, copiez ce lien :<br>
            <a href="{{ $lien }}" style="color:#7C3AED;word-break:break-all;">{{ $lien }}</a>
        </p>
    </div>
    <div style="background:#F9FAFB;padding:1rem;text-align:center;border-top:1px solid #F3F4F6;">
        <p style="color:#9CA3AF;font-size:0.75rem;margin:0;">
            &copy; {{ date('Y') }} DevHub — Plateforme des développeurs juniors au Bénin
        </p>
    </div>
</div>
</body>
</html>
