<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#F5F3FF;padding:2rem;margin:0;">
<div style="max-width:520px;margin:0 auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div style="background:#7C3AED;padding:2rem;text-align:center;">
        <span style="color:white;font-size:1.4rem;font-weight:800;">DevHub</span>
    </div>
    <div style="padding:2rem;">
        <h2 style="color:#111827;font-size:1.2rem;margin-bottom:0.75rem;">Bonjour {{ $prenom }},</h2>
        <p style="color:#6B7280;line-height:1.7;margin-bottom:1rem;">
            Nous avons bien reçu votre candidature pour rejoindre le programme de mentorat <strong>DevHub</strong>.
        </p>
        <div style="background:#F5F3FF;border-left:4px solid #7C3AED;padding:1rem 1.25rem;border-radius:0 8px 8px 0;margin-bottom:1.5rem;">
            <p style="color:#374151;margin:0;font-size:0.9rem;line-height:1.6;">
                Notre équipe va examiner votre dossier avec attention. Vous recevrez une réponse dans un délai de <strong>5 à 10 jours ouvrables</strong>.
            </p>
        </div>
        <p style="color:#6B7280;font-size:0.88rem;line-height:1.6;">
            En attendant, si vous avez des questions, n'hésitez pas à nous contacter à <a href="mailto:229devhub@gmail.com" style="color:#7C3AED;">229devhub@gmail.com</a>.
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
