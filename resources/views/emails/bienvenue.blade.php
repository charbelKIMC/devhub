<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#F5F3FF;padding:2rem;margin:0;">
    <div style="max-width:520px;margin:0 auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">

        <!-- Header -->
        <div style="background:#7C3AED;padding:2rem;text-align:center;">
            <span style="color:white;font-size:1.4rem;font-weight:800;">DevHub</span>
        </div>

        <!-- Corps -->
        <div style="padding:2rem;">
            <h2 style="color:#111827;font-size:1rem;margin-bottom:0.5rem;">
                Bienvenue {{ $utilisateur->prenom }} ! 👋
            </h2>
            <p style="color:#6B7280;line-height:1.7;margin-bottom:1rem;font-size:0.85rem;">
                Votre profil DevHub est maintenant complet. Vous faites officiellement partie de la communauté des développeurs de l'Afrique francophone.
            </p>
            <p style="color:#6B7280;line-height:1.7;margin-bottom:1.5rem;font-size:0.85rem;">
                Vous pouvez dès maintenant publier vos projets, rejoindre des collaborations et trouver un mentor.
            </p>

            <div style="text-align:center;margin:1.5rem 0;">
                <a href="{{ url('/dashboard') }}"
                   style="background:#7C3AED;color:white;padding:0.75rem 2rem;
                          border-radius:10px;text-decoration:none;font-weight:600;
                          display:inline-block; font-size:0.95rem;">
                    Accéder à mon tableau de bord
                </a>
            </div>

            <hr style="border:none;border-top:1px solid #F3F4F6;margin:1.5rem 0;">

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;text-align:center;">
                <div>
                    <div style="font-size:1.15rem;">📝</div>
                    <div style="font-size:0.78rem;color:#6B7280;margin-top:0.3rem;">Publiez vos projets</div>
                </div>
                <div>
                    <div style="font-size:1.15rem;">🤝</div>
                    <div style="font-size:0.78rem;color:#6B7280;margin-top:0.3rem;">Collaborez</div>
                </div>
                <div>
                    <div style="font-size:1.15rem;">🎓</div>
                    <div style="font-size:0.78rem;color:#6B7280;margin-top:0.3rem;">Trouvez un mentor</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div style="background:#F9FAFB;padding:1rem;text-align:center;border-top:1px solid #F3F4F6;">
            <p style="color:#9CA3AF;font-size:0.65rem;margin:0;">
                Cet email a été envoyé automatiquement par DevHub.<br>
                &copy; {{ date('Y') }} DevHub — Plateforme des développeurs
            </p>
        </div>
    </div>
</body>
</html>