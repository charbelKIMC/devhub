<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#F5F3FF;padding:2rem;margin:0;">
<div style="max-width:540px;margin:0 auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div style="background:linear-gradient(135deg,#7C3AED,#5B21B6);padding:1.75rem 2rem;text-align:center;">
        <span style="color:white;font-size:1.4rem;font-weight:800;">DevHub</span>
        <p style="color:#EDE9FE;font-size:0.85rem;margin:0.25rem 0 0;">Plateforme des développeurs juniors au Bénin</p>
    </div>
    <div style="padding:2rem;">
        <div style="text-align:center;margin-bottom:1.5rem;">
            <div style="width:60px;height:60px;background:#D1FAE5;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:1.5rem;">✅</div>
        </div>

        <h2 style="color:#111827;font-size:1.15rem;text-align:center;margin-bottom:0.75rem;">
            Félicitations, {{ $candidat->prenom }} !
        </h2>
        <p style="color:#6B7280;line-height:1.7;text-align:center;margin-bottom:1.5rem;">
            Votre candidature au projet <strong style="color:#7C3AED;">« {{ $pub->titre }} »</strong>
            a été <strong style="color:#059669;">acceptée</strong> par le responsable du projet.
        </p>

        <div style="background:#FFFBEB;border:1.5px solid #FDE68A;border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;">
            <p style="font-size:0.85rem;font-weight:700;color:#92400E;margin:0 0 0.5rem;">⚠️ Étape obligatoire</p>
            <p style="font-size:0.85rem;color:#78350F;line-height:1.6;margin:0;">
                Avant de rejoindre l'équipe, vous devez <strong>lire et signer le contrat de non-divulgation et de confidentialité</strong>. Ce contrat protège le projet et tous ses collaborateurs.
            </p>
        </div>

        <div style="text-align:center;margin-bottom:1.75rem;">
            <a href="{{ $lienNda }}"
               style="background:#7C3AED;color:white;padding:0.85rem 2.25rem;border-radius:10px;text-decoration:none;font-weight:700;font-size:0.95rem;display:inline-block;">
                📄 Lire et signer le contrat
            </a>
        </div>

        <p style="color:#9CA3AF;font-size:0.78rem;text-align:center;line-height:1.6;margin-bottom:0.5rem;">
            Ce lien est personnel et sécurisé. Ne le partagez pas.
        </p>
        <p style="color:#9CA3AF;font-size:0.78rem;text-align:center;line-height:1.6;">
            Après signature, vous recevrez une copie PDF du contrat et serez automatiquement ajouté à l'équipe et à la conversation de groupe.
        </p>
    </div>
    <div style="background:#F9FAFB;padding:1rem;text-align:center;border-top:1px solid #F3F4F6;">
        <p style="color:#9CA3AF;font-size:0.75rem;margin:0;">&copy; {{ date('Y') }} DevHub — Tous droits réservés</p>
    </div>
</div>
</body>
</html>
