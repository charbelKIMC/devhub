<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#F5F3FF;padding:2rem;margin:0;">
    <div style="max-width:520px;margin:0 auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">

        <div style="background:#7C3AED;padding:1.5rem;text-align:center;">
            <span style="color:white;font-size:1.3rem;font-weight:800;">DevHub</span>
            <div style="color:rgba(255,255,255,0.8);font-size:0.82rem;margin-top:0.2rem;">Notification administrateur</div>
        </div>

        <div style="padding:2rem;">
            <h2 style="color:#111827;font-size:1rem;margin-bottom:1rem;">
                📋 Nouvelle demande de mentor
            </h2>

            <div style="background:#F5F3FF;border:1px solid #DDD6FE;border-radius:12px;padding:1.25rem;margin-bottom:1.25rem;">
                <div style="margin-bottom:0.75rem;">
                    <div style="font-size:0.72rem;font-weight:700;color:#9CA3AF;text-transform:uppercase;margin-bottom:0.2rem;">Développeur</div>
                    <div style="font-weight:700;color:#111827;">{{ $developpeur->prenom }} {{ $developpeur->nom }}</div>
                    <div style="font-size:0.82rem;color:#6B7280;">{{ $developpeur->email }} · {{ $developpeur->specialite ?? 'Développeur' }}</div>
                </div>

                @if($mentorSouhaite)
                    <div>
                        <div style="font-size:0.72rem;font-weight:700;color:#9CA3AF;text-transform:uppercase;margin-bottom:0.2rem;">Mentor souhaité</div>
                        <div style="font-weight:600;color:#7C3AED;">{{ $mentorSouhaite->prenom }} {{ $mentorSouhaite->nom }}</div>
                        <div style="font-size:0.82rem;color:#6B7280;">{{ $mentorSouhaite->specialite }}</div>
                    </div>
                @endif
            </div>

            <p style="color:#6B7280;font-size:0.82rem;margin-bottom:1.5rem;">
                Rendez-vous dans l'espace administrateur pour traiter cette demande.
            </p>

            <div style="text-align:center;">
                <a href="{{ url('/admin/mentors') }}"
                   style="background:#7C3AED;color:white;padding:0.75rem 2rem;border-radius:10px;text-decoration:none;font-weight:600;display:inline-block;">
                    Gérer les mentors
                </a>
            </div>
        </div>

        <div style="background:#F9FAFB;padding:1rem;text-align:center;border-top:1px solid #F3F4F6;">
            <p style="color:#9CA3AF;font-size:0.75rem;margin:0;">
                &copy; {{ date('Y') }} DevHub — Email automatique
            </p>
        </div>
    </div>
</body>
</html>