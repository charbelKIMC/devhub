<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer votre mot de passe – DevHub</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/s-register.css') }}">
    <style>
        .mentor-card {
            max-width: 460px;
            margin: 0 auto;
            background: rgba(255,255,255,0.97);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        .mentor-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #F5F3FF;
            color: #7C3AED;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            margin-bottom: 1.25rem;
        }
        .mentor-title { font-size: 1.4rem; font-weight: 700; color: #111827; margin-bottom: 0.4rem; }
        .mentor-sub   { color: #6B7280; font-size: 0.88rem; line-height: 1.6; margin-bottom: 1.5rem; }
        .form-label   { font-weight: 600; font-size: 0.88rem; color: #374151; margin-bottom: 0.35rem; display: block; }
        .form-ctrl {
            width: 100%; padding: 0.7rem 1rem; border: 1.5px solid #E5E7EB;
            border-radius: 10px; font-size: 0.92rem; color: #111827;
            outline: none; transition: border-color 0.2s;
        }
        .form-ctrl:focus { border-color: #7C3AED; box-shadow: 0 0 0 3px rgba(124,58,237,0.1); }
        .strength-bar  { height: 4px; background: #E5E7EB; border-radius: 2px; margin: 0.4rem 0 0.2rem; overflow:hidden; }
        .strength-fill { height: 100%; border-radius: 2px; transition: width 0.3s, background 0.3s; width: 0%; }
        .strength-text { font-size: 0.75rem; color: #9CA3AF; margin-bottom: 1rem; }
        .btn-activate {
            width: 100%; padding: 0.85rem; background: #7C3AED; color: white;
            border: none; border-radius: 10px; font-size: 1rem; font-weight: 700;
            cursor: pointer; transition: background 0.2s;
        }
        .btn-activate:hover { background: #6D28D9; }
        .alert-error {
            background: #FEF2F2; border: 1px solid #FECACA; color: #B91C1C;
            padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.88rem; margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="background-image"></div>
    <div class="gradient-overlay"></div>

    <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;position:relative;z-index:10;">
        <div class="mentor-card">

            <div class="mentor-badge">
                <i class="fas fa-chalkboard-teacher"></i> Programme Mentorat DevHub
            </div>

            <div class="mentor-title">Créez votre mot de passe</div>
            <p class="mentor-sub">
                Bonjour <strong>{{ $prenom }}</strong> ! Votre candidature a été acceptée.<br>
                Définissez un mot de passe pour activer votre compte mentor.
            </p>

            @if($errors->any())
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('mentor.activation.store', ['token' => $token]) }}">
                @csrf

                <div style="margin-bottom:1rem;">
                    <label class="form-label">Adresse email</label>
                    <input type="email" class="form-ctrl" value="{{ $email }}" disabled>
                </div>

                <div style="margin-bottom:0.5rem;">
                    <label class="form-label" for="mot_de_passe">Mot de passe <span style="color:#EF4444;">*</span></label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-ctrl" placeholder="Minimum 8 caractères" required>
                    <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                    <div class="strength-text" id="strengthText">Au moins 8 caractères</div>
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label class="form-label" for="mot_de_passe_confirmation">Confirmer le mot de passe <span style="color:#EF4444;">*</span></label>
                    <input type="password" id="mot_de_passe_confirmation" name="mot_de_passe_confirmation" class="form-ctrl" placeholder="Répétez le mot de passe" required>
                </div>

                <button type="submit" class="btn-activate">
                    <i class="fas fa-check me-2"></i> Activer mon compte mentor
                </button>
            </form>
        </div>
    </div>

    <script>
        const pwd = document.getElementById('mot_de_passe');
        const fill = document.getElementById('strengthFill');
        const text = document.getElementById('strengthText');

        pwd.addEventListener('input', function () {
            const v = this.value;
            let s = 0;
            if (v.length >= 8) s += 25;
            if (v.match(/[a-z]/) && v.match(/[A-Z]/)) s += 25;
            if (v.match(/[0-9]/)) s += 25;
            if (v.match(/[^a-zA-Z0-9]/)) s += 25;
            fill.style.width = s + '%';
            if (s === 0)       { text.textContent = 'Au moins 8 caractères'; fill.style.background = '#E5E7EB'; }
            else if (s <= 25)  { text.textContent = 'Faible';    fill.style.background = '#EF4444'; }
            else if (s <= 50)  { text.textContent = 'Moyen';     fill.style.background = '#F59E0B'; }
            else if (s <= 75)  { text.textContent = 'Bon';       fill.style.background = '#3B82F6'; }
            else               { text.textContent = 'Excellent'; fill.style.background = '#7C3AED'; }
        });
    </script>
</body>
</html>
