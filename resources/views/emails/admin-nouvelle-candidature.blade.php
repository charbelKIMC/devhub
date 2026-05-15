<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#F5F3FF;padding:2rem;margin:0;">
<div style="max-width:520px;margin:0 auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div style="background:#7C3AED;padding:2rem;text-align:center;">
        <span style="color:white;font-size:1.4rem;font-weight:800;">DevHub — Admin</span>
    </div>
    <div style="padding:2rem;">
        <h2 style="color:#111827;font-size:1.1rem;margin-bottom:0.75rem;">Nouvelle candidature mentor reçue</h2>
        <table style="width:100%;border-collapse:collapse;margin-bottom:1.5rem;">
            <tr><td style="padding:6px 0;color:#6B7280;font-size:0.88rem;width:40%;">Nom complet</td><td style="padding:6px 0;color:#111827;font-weight:600;">{{ $candidature->prenom }} {{ $candidature->nom }}</td></tr>
            <tr><td style="padding:6px 0;color:#6B7280;font-size:0.88rem;">Email</td><td style="padding:6px 0;color:#111827;">{{ $candidature->email }}</td></tr>
            <tr><td style="padding:6px 0;color:#6B7280;font-size:0.88rem;">Téléphone</td><td style="padding:6px 0;color:#111827;">{{ $candidature->telephone }}</td></tr>
            <tr><td style="padding:6px 0;color:#6B7280;font-size:0.88rem;">Spécialité</td><td style="padding:6px 0;color:#111827;">{{ $candidature->specialite }}</td></tr>
            <tr><td style="padding:6px 0;color:#6B7280;font-size:0.88rem;">Expérience</td><td style="padding:6px 0;color:#111827;">{{ $candidature->annees_experience }}</td></tr>
        </table>
        <div style="text-align:center;margin:1.5rem 0;">
            <a href="{{ $lien }}" style="background:#7C3AED;color:white;padding:0.75rem 2rem;border-radius:10px;text-decoration:none;font-weight:700;display:inline-block;">
                Voir le dossier complet
            </a>
        </div>
    </div>
    <div style="background:#F9FAFB;padding:1rem;text-align:center;border-top:1px solid #F3F4F6;">
        <p style="color:#9CA3AF;font-size:0.75rem;margin:0;">&copy; {{ date('Y') }} DevHub</p>
    </div>
</div>
</body>
</html>
