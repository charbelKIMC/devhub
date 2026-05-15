<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#F5F3FF;padding:2rem;margin:0;">
    <div style="max-width:520px;margin:0 auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">

        <!-- Header -->
        <div style="background:#7C3AED;padding:2rem;text-align:center;">
            <div style="display:inline-flex;align-items:center;gap:0.5rem;">
                <div style="width:40px;height:40px;background:rgba(255,255,255,0.2);border-radius:10px;display:inline-flex;align-items:center;justify-content:center;">
                    <span style="color:white;font-size:1.1rem;">&#60;/&#62;</span>
                </div>
                <span style="color:white;font-size:1.4rem;font-weight:800;">DevHub</span>
            </div>
        </div>

        <!-- Corps -->
        <div style="padding:2rem;">
            <h2 style="color:#111827;font-size:1.25rem;margin-bottom:0.5rem;">
                Bonjour {{ $prenom }} !
            </h2>
            <p style="color:#6B7280;line-height:1.7;margin-bottom:1rem;">
                Merci de vous être inscrit sur <strong>DevHub</strong>. Il ne reste plus qu'une étape : confirmez votre adresse email pour accéder à la configuration de votre profil.
            </p>
            <p style="color:#6B7280;line-height:1.7;margin-bottom:1.5rem;">
                Ce lien est valable pendant <strong>24 heures</strong>. Passé ce délai, vous devrez recommencer l'inscription.
            </p>

            <div style="text-align:center;margin:1.5rem 0;">
                <a href="{{ $lien }}"
                   style="background:#7C3AED;color:white;padding:0.85rem 2.5rem;
                          border-radius:10px;text-decoration:none;font-weight:700;
                          font-size:1rem;display:inline-block;">
                    Continuer l'inscription
                </a>
            </div>

            <p style="color:#9CA3AF;font-size:0.85rem;line-height:1.6;">
                Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>
                <a href="{{ $lien }}" style="color:#7C3AED;word-break:break-all;">{{ $lien }}</a>
            </p>

            <hr style="border:none;border-top:1px solid #F3F4F6;margin:1.5rem 0;">

            <p style="color:#9CA3AF;font-size:0.8rem;">
                Si vous n'avez pas créé de compte sur DevHub, ignorez simplement cet email.
            </p>
        </div>

        <!-- Footer -->
        <div style="background:#F9FAFB;padding:1rem;text-align:center;border-top:1px solid #F3F4F6;">
            <p style="color:#9CA3AF;font-size:0.75rem;margin:0;">
                Cet email a été envoyé automatiquement par DevHub.<br>
                &copy; {{ date('Y') }} DevHub — Plateforme des développeurs Junior au Bénin
            </p>
        </div>
    </div>
</body>
</html>
