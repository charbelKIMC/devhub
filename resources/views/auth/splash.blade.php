<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevHub</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Urbanist', sans-serif;
            background: #F5F3FF;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2.5rem;
            animation: fadeIn 0.5s ease;
        }

        .logo-icon {
            width: 52px; height: 52px;
            background: white;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; color: white;
        }

        .logo-text {
            font-size: 1.75rem;
            font-weight: 800;
            color: #7C3AED;
        }

        /* Barre de chargement */
        .progress-bar {
            width: 200px;
            height: 4px;
            background: #DDD6FE;
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #7C3AED;
            border-radius: 2px;
            animation: progress 2.5s ease forwards;
        }

        @keyframes progress {
            0%   { width: 0%; }
            30%  { width: 40%; }
            60%  { width: 70%; }
            90%  { width: 90%; }
            100% { width: 100%; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="logo">
        <div><img src="{{ asset('img/logo-devhub.png') }}" alt="Logo de DevHub" class="logo-icon"></div>
        <span class="logo-text">DevHub</span>
    </div>

    <div class="progress-bar">
        <div class="progress-fill" id="progressFill"></div>
    </div>

    <script>
        setTimeout(function() {
            @if(Auth::user()->isAdmin())
                window.location.href = "{{ route('admin.dashboard') }}";
            @elseif(Auth::user()->isMentor())
                window.location.href = "{{ route('mentor.espace') }}";
            @else
                window.location.href = "{{ route('dashboard') }}";
            @endif
        }, 2500);
    </script>
</body>
</html>