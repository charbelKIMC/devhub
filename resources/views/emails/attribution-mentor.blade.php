<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#F5F3FF;padding:2rem;margin:0;">
    <div style="max-width:520px;margin:0 auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <div style="background:#7C3AED;padding:1.5rem;text-align:center;">
            <span style="color:white;font-size:1.3rem;font-weight:800;">DevHub</span>
            <div style="color:rgba(255,255,255,0.8);font-size:0.82rem;margin-top:0.2rem;">Espace Mentor</div>
        </div>
        <div style="padding:2rem;">
            <h2 style="color:#111827;font-size:1rem;margin-bottom:1rem;">
                🎓 Nouveau mentoré attribué
            </h2>
            <p style="color:#6B7280;line-height:1.7;margin-bottom:1.25rem;">
                Bonjour {{ $mentor->prenom }}, un nouveau mentoré vous a été attribué par l'administrateur DevHub.
            </p>
            <div style="background:#F5F3FF;border:1px solid #DDD6FE;border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;">
                <div style="margin-bottom:0.75rem;">
                    <div style="font-size:0.72rem;font-weight:700;color:#9CA3AF;text-transform:uppercase;margin-bottom:0.2rem;">Mentoré</div>
                    <div style="font-weight:700;color:#111827;">{{ $mentore->prenom }} {{ $mentore->nom }}</div>
                </div>
                <div>
                    <div style="font-size:0.72rem;font-weight:700;color:#9CA3AF;text-transform:uppercase;margin-bottom:0.2rem;">Spécialité</div>
                    <div style="color:#7C3AED;font-weight:600;">{{ $mentore->specialite ?? 'Développeur' }}</div>
                </div>
            </div>
            <p style="color:#6B7280;font-size:0.82rem;margin-bottom:1.5rem;">
                Connectez-vous à votre espace mentor pour consulter son profil et le contacter via la messagerie.
            </p>
            <div style="text-align:center;">
                <a href="{{ url('/mentor/espace') }}"
                   style="background:#7C3AED;color:white;padding:0.75rem 2rem;border-radius:10px;text-decoration:none;font-weight:600;display:inline-block;">
                    Accéder à mon espace mentor
                </a>
            </div>
        </div>
        <div style="background:#F9FAFB;padding:1rem;text-align:center;border-top:1px solid #F3F4F6;">
            <p style="color:#9CA3AF;font-size:0.75rem;margin:0;">&copy; {{ date('Y') }} DevHub</p>
        </div>
    </div>
</body>
</html>