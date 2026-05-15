<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#F5F3FF;padding:2rem;margin:0;">
<div style="max-width:540px;margin:0 auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div style="background:linear-gradient(135deg,#059669,#047857);padding:1.75rem 2rem;text-align:center;">
        <span style="color:white;font-size:1.4rem;font-weight:800;">DevHub</span>
        <p style="color:#A7F3D0;font-size:0.85rem;margin:0.25rem 0 0;">Contrat de confidentialité signé</p>
    </div>
    <div style="padding:2rem;">
        <h2 style="color:#111827;font-size:1.15rem;margin-bottom:0.75rem;">
            Bienvenue dans l'équipe, {{ $candidat->prenom }} ! 🎉
        </h2>
        <p style="color:#6B7280;line-height:1.7;margin-bottom:1.25rem;">
            Votre contrat de non-divulgation et de confidentialité pour le projet
            <strong style="color:#7C3AED;">« {{ $pub->titre }} »</strong> a bien été signé et enregistré.
        </p>

        <div style="background:#F0FDF4;border:1.5px solid #86EFAC;border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;">
            <p style="font-size:0.85rem;font-weight:700;color:#166534;margin:0 0 0.5rem;">✅ Ce qui vient de se passer</p>
            <ul style="font-size:0.85rem;color:#15803D;line-height:2;margin:0;padding-left:1.25rem;">
                <li>Vous avez été ajouté à l'équipe du projet</li>
                <li>La conversation de groupe a été ouverte pour vous</li>
                <li>Le responsable du projet a été notifié</li>
                <li>Une copie PDF du contrat est jointe à cet email</li>
            </ul>
        </div>

        <p style="color:#374151;font-size:0.9rem;line-height:1.6;margin-bottom:1.5rem;">
            Conservez précieusement le PDF joint à cet email. Il constitue votre exemplaire du contrat signé électroniquement.
        </p>

        <div style="text-align:center;margin-bottom:1.5rem;">
            <a href="{{ route('messagerie.index') }}"
               style="background:#7C3AED;color:white;padding:0.75rem 2rem;border-radius:10px;text-decoration:none;font-weight:700;font-size:0.9rem;display:inline-block;">
                Accéder à la messagerie
            </a>
        </div>
    </div>
    <div style="background:#F9FAFB;padding:1rem;text-align:center;border-top:1px solid #F3F4F6;">
        <p style="color:#9CA3AF;font-size:0.75rem;margin:0;">&copy; {{ date('Y') }} DevHub — Tous droits réservés</p>
    </div>
</div>
</body>
</html>
