<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#F5F3FF;padding:2rem;margin:0;">
<div style="max-width:540px;margin:0 auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div style="background:#7C3AED;padding:1.75rem 2rem;text-align:center;">
        <span style="color:white;font-size:1.4rem;font-weight:800;">DevHub</span>
        <p style="color:#EDE9FE;font-size:0.85rem;margin:0.25rem 0 0;">Plateforme des développeurs juniors au Bénin</p>
    </div>
    <div style="padding:2rem;">
        <h2 style="color:#111827;font-size:1.1rem;margin-bottom:0.75rem;">Bonjour {{ $auteur->prenom }},</h2>
        <p style="color:#6B7280;line-height:1.7;margin-bottom:1.25rem;">
            <strong style="color:#111827;">{{ $candidat->prenom }} {{ $candidat->nom }}</strong>
            (@{{ $candidat->pseudo }}) vient de postuler pour rejoindre votre projet collaboratif.
        </p>

        <div style="background:#F5F3FF;border:1.5px solid #DDD6FE;border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;">
            <p style="font-size:0.78rem;font-weight:700;color:#7C3AED;text-transform:uppercase;letter-spacing:0.5px;margin:0 0 0.6rem;">Projet</p>
            <p style="font-size:1rem;font-weight:700;color:#111827;margin:0 0 0.5rem;">{{ $pub->titre }}</p>
            @if($candidat->specialite)
            <p style="font-size:0.85rem;color:#6B7280;margin:0;">Spécialité du candidat : {{ $candidat->specialite }}</p>
            @endif
        </div>

        <p style="color:#374151;font-size:0.9rem;line-height:1.6;margin-bottom:1.5rem;">
            Connectez-vous à DevHub pour consulter sa motivation, son profil et décider d'accepter ou refuser sa candidature.
        </p>

        <div style="text-align:center;margin-bottom:1.5rem;">
            <a href="{{ route('dashboard.collaborations') }}"
               style="background:#7C3AED;color:white;padding:0.75rem 2rem;border-radius:10px;text-decoration:none;font-weight:700;font-size:0.9rem;display:inline-block;">
                Voir les candidatures
            </a>
        </div>

        <p style="color:#9CA3AF;font-size:0.8rem;line-height:1.6;">
            Si vous n'attendiez pas cette notification, vous pouvez ignorer cet email.
        </p>
    </div>
    <div style="background:#F9FAFB;padding:1rem;text-align:center;border-top:1px solid #F3F4F6;">
        <p style="color:#9CA3AF;font-size:0.75rem;margin:0;">&copy; {{ date('Y') }} DevHub — Tous droits réservés</p>
    </div>
</div>
</body>
</html>
