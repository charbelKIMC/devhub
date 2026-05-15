<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#F5F3FF;padding:2rem;margin:0;">
<div style="max-width:540px;margin:0 auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div style="background:linear-gradient(135deg,#7C3AED,#5B21B6);padding:1.75rem 2rem;text-align:center;">
        <span style="color:white;font-size:1.4rem;font-weight:800;">DevHub</span>
        <p style="color:#EDE9FE;font-size:0.85rem;margin:0.25rem 0 0;">Attribution de mentorat — Projet collaboratif</p>
    </div>
    <div style="padding:2rem;">
        <h2 style="color:#111827;font-size:1.1rem;margin-bottom:0.75rem;">Bonjour {{ $mentor->prenom }},</h2>
        <p style="color:#6B7280;line-height:1.7;margin-bottom:1.25rem;">
            Vous avez été attribué comme <strong>mentor</strong> du projet collaboratif
            <strong style="color:#7C3AED;">« {{ $pub->titre }} »</strong> sur DevHub.
            L'équipe vient d'être constituée et a besoin de votre accompagnement.
        </p>

        <div style="background:#F5F3FF;border:1.5px solid #DDD6FE;border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;">
            <p style="font-size:0.85rem;font-weight:700;color:#7C3AED;margin:0 0 0.75rem;">Détails du projet</p>
            <p style="font-size:1rem;font-weight:700;color:#111827;margin:0 0 0.4rem;">{{ $pub->titre }}</p>
            @if($pub->projetCollaboratif?->technologies)
            <p style="font-size:0.8rem;color:#6B7280;margin:0;">
                Technologies : {{ implode(', ', $pub->projetCollaboratif->technologies) }}
            </p>
            @endif
        </div>

        <p style="color:#374151;font-size:0.88rem;line-height:1.6;margin-bottom:1.5rem;">
            Vous avez été ajouté à la conversation de groupe du projet. Présentez-vous à l'équipe et proposez votre premier point de contact pour bien démarrer la collaboration.
        </p>

        <div style="text-align:center;margin-bottom:1.5rem;">
            <a href="{{ route('messagerie.show', $conversation->id) }}"
               style="background:#7C3AED;color:white;padding:0.75rem 2rem;border-radius:10px;text-decoration:none;font-weight:700;font-size:0.9rem;display:inline-block;">
                Rejoindre la conversation de groupe
            </a>
        </div>
    </div>
    <div style="background:#F9FAFB;padding:1rem;text-align:center;border-top:1px solid #F3F4F6;">
        <p style="color:#9CA3AF;font-size:0.75rem;margin:0;">&copy; {{ date('Y') }} DevHub — Tous droits réservés</p>
    </div>
</div>
</body>
</html>
