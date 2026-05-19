<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#F5F3FF;padding:2rem;margin:0;">
<div style="max-width:520px;margin:0 auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div style="background:#7C3AED;padding:2rem;text-align:center;">
        <span style="color:white;font-size:1.4rem;font-weight:800;">DevHub</span>
    </div>
    <div style="padding:2rem;">
        <h2 style="color:#111827;font-size:1.2rem;margin-bottom:0.5rem;">Contrat signé, {{ $mentor->prenom }} !</h2>
        <p style="color:#6B7280;line-height:1.7;margin-bottom:1rem;">
            Votre contrat d'engagement mentor DevHub a bien été enregistré.
            Vous trouverez une copie signée en pièce jointe de cet email.
        </p>
        <div style="background:#F5F3FF;border-radius:10px;padding:1rem;margin-bottom:1.5rem;">
            <p style="color:#374151;margin:0 0 0.35rem;font-size:0.88rem;"><strong>Nom :</strong> {{ $mentor->prenom }} {{ $mentor->nom }}</p>
            <p style="color:#374151;margin:0 0 0.35rem;font-size:0.88rem;"><strong>Email :</strong> {{ $mentor->email }}</p>
            <p style="color:#374151;margin:0;font-size:0.88rem;"><strong>Signé le :</strong> {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
        <p style="color:#6B7280;font-size:0.85rem;line-height:1.6;margin-bottom:1.5rem;">
            Ce contrat couvre l'ensemble de vos activités de mentorat sur la plateforme DevHub.
            Conservez ce document précieusement.
        </p>
        <div style="text-align:center;margin:1.5rem 0;">
            <a href="{{ url('/mentor/espace') }}" style="background:#7C3AED;color:white;padding:0.85rem 2.5rem;border-radius:10px;text-decoration:none;font-weight:700;font-size:1rem;display:inline-block;">
                Accéder à mon espace mentor
            </a>
        </div>
    </div>
    <div style="background:#F9FAFB;padding:1rem;text-align:center;border-top:1px solid #F3F4F6;">
        <p style="color:#9CA3AF;font-size:0.75rem;margin:0;">
            &copy; {{ date('Y') }} DevHub — Plateforme des développeurs juniors au Bénin
        </p>
    </div>
</div>
</body>
</html>
