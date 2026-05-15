@extends('layouts.app')
@section('title', 'Demander un mentor – DevHub')

@section('styles')
    <style>
        .mentor-page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;
        }
        .mentor-page-title {
            font-family: 'Urbanist', sans-serif;
            font-size: 1.1rem; font-weight: 800; color: var(--gray-900);
        }
        .mentor-page-sub { font-size: 0.82rem; color: var(--gray-400); margin-top: 0.15rem; }

        /* Info banner */
        .mentor-info-banner {
            background: linear-gradient(135deg, #EDE9FE, #DDD6FE);
            border: 1px solid #C4B5FD;
            border-radius: 14px; padding: 1.25rem 1.5rem;
            display: flex; align-items: center; gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .mentor-info-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: var(--purple); color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
        }
        .mentor-info-text h3 { font-family: 'Urbanist',sans-serif; font-size: 0.95rem; font-weight: 700; color: var(--purple); margin-bottom: 0.2rem; }
        .mentor-info-text p { font-size: 0.82rem; color: var(--gray-600); margin: 0; line-height: 1.5; }

        /* Mentors grid */
        .mentors-grid {
            display: grid; grid-template-columns: repeat(2, 1fr);
            gap: 0.85rem; margin-bottom: 1.5rem;
        }

        .mentor-card {
            background: white; border: 2px solid var(--gray-200);
            border-radius: 14px; padding: 1.25rem;
            cursor: pointer; transition: all 0.2s;
            display: flex; flex-direction: column; gap: 0.75rem;
            position: relative;
        }
        .mentor-card:hover { border-color: var(--purple); box-shadow: 0 4px 16px rgba(124,58,237,0.1); transform: translateY(-2px); }
        .mentor-card.selected { border-color: var(--purple); background: #F5F3FF; box-shadow: 0 4px 16px rgba(124,58,237,0.15); }

        .mentor-card-check {
            position: absolute; top: 0.75rem; right: 0.75rem;
            width: 22px; height: 22px; border-radius: 50%;
            background: var(--gray-100); color: var(--gray-300);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem; transition: all 0.2s;
        }
        .mentor-card.selected .mentor-card-check { background: var(--purple); color: white; }

        .mentor-card-top { display: flex; align-items: center; gap: 0.75rem; }

        .mentor-ava {
            width: 52px; height: 52px; border-radius: 50%;
            background: var(--purple-light); color: var(--purple);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; font-weight: 700; flex-shrink: 0; overflow: hidden;
        }
        .mentor-ava img { width: 100%; height: 100%; object-fit: cover; }

        .mentor-name { font-family: 'Urbanist',sans-serif; font-size: 0.9rem; font-weight: 700; color: var(--gray-900); }
        .mentor-spec { font-size: 0.75rem; color: var(--purple); font-weight: 600; margin-top: 0.1rem; }

        .mentor-bio { font-size: 0.78rem; color: var(--gray-500); line-height: 1.5; }

        .mentor-stats { display: flex; gap: 0.85rem; }
        .mentor-stat { font-size: 0.72rem; color: var(--gray-400); display: flex; align-items: center; gap: 0.25rem; }

        /* Bouton submit */
        .btn-submit-mentor {
            width: 100%; padding: 0.85rem; background: var(--purple); color: white;
            border: none; border-radius: 12px; font-size: 0.95rem; font-weight: 700;
            cursor: pointer; transition: background 0.2s; font-family: 'DM Sans', sans-serif;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .btn-submit-mentor:hover { background: var(--purple-dark); }
        .btn-submit-mentor:disabled { background: var(--gray-200); color: var(--gray-400); cursor: not-allowed; }

        /* Déjà mentor */
        .already-mentor-card {
            background: #D1FAE5; border: 1px solid #A7F3D0;
            border-radius: 14px; padding: 1.5rem;
            display: flex; align-items: center; gap: 1rem;
        }

        /* Vide */
        .mentors-empty {
            text-align: center; padding: 3rem 2rem;
            background: white; border: 1px solid var(--gray-200); border-radius: 14px;
        }
        .mentors-empty i { font-size: 3rem; color: var(--gray-200); display: block; margin-bottom: 0.75rem; }

        @media (max-width: 768px) { .mentors-grid { grid-template-columns: 1fr; } }
    </style>
@endsection

@section('content')
    <main class="main-content" style="max-width:860px;margin:0 auto;">

        <!-- Header -->
        <div class="mentor-page-header">
            <div>
                <h1 class="mentor-page-title">
                    <i class="fas fa-user-graduate me-2" style="color:var(--purple);"></i>Demander un mentor
                </h1>
                <p class="mentor-page-sub">Choisissez un mentor pour vous accompagner dans votre progression</p>
            </div>
        </div>

        <!-- Suivi de la demande -->
        @php
            $relationActive = \App\Models\RelationMentorat::with('mentor')
                ->where('mentore_id', Auth::id())
                ->where('statut', 'active')
                ->first();
        @endphp

        @if($relationActive)
            <!-- A un mentor actif -->
            <div style="background:#D1FAE5;border:1px solid #6EE7B7;border-radius:14px;padding:1.25rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:1rem;">
                <div style="width:44px;height:44px;border-radius:12px;background:#065F46;color:white;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <div style="font-family:'Urbanist',sans-serif;font-weight:700;font-size:0.95rem;color:#065F46;">
                        Vous avez un mentor actif
                    </div>
                    <div style="font-size:0.82rem;color:#065F46;margin-top:0.1rem;">
                        {{ $relationActive->mentor?->prenom }} {{ $relationActive->mentor?->nom }}
                        · Jusqu'au {{ $relationActive->date_fin ? \Carbon\Carbon::parse($relationActive->date_fin)->format('d/m/Y') : '—' }}
                    </div>
                </div>
                <a href="{{ route('messagerie.index') }}"
                style="margin-left:auto;padding:0.45rem 1rem;background:#065F46;color:white;border-radius:8px;font-size:0.82rem;font-weight:600;text-decoration:none;flex-shrink:0;">
                    <i class="fas fa-comment me-1"></i> Contacter
                </a>
            </div>

        @elseif($dejaRelation)
            <!-- Déjà un mentor -->
            <div class="already-mentor-card">
                <div style="width:48px;height:48px;border-radius:12px;background:#065F46;color:white;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <div style="font-family:'Urbanist',sans-serif;font-weight:700;font-size:0.95rem;color:#065F46;margin-bottom:0.25rem;">
                        Vous avez déjà un mentor actif
                    </div>
                    <p style="font-size:0.82rem;color:#065F46;margin:0;">
                        Rendez-vous dans la messagerie pour communiquer avec votre mentor.
                    </p>
                </div>
                <a href="{{ route('messagerie.index') }}"
                style="margin-left:auto;padding:0.45rem 1rem;background:#065F46;color:white;border-radius:8px;font-size:0.82rem;font-weight:600;text-decoration:none;flex-shrink:0;">
                    <i class="fas fa-comment me-1"></i> Messagerie
                </a>
            </div>

        @else
            <!-- Banner info -->
            <div class="mentor-info-banner">
                <div class="mentor-info-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="mentor-info-text">
                    <h3>Comment ça marche ?</h3>
                    <p>Sélectionnez un mentor ci-dessous. Votre demande sera transmise à l'administrateur qui se chargera de l'attribution officielle. Vous serez notifié par email.</p>
                </div>
            </div>

            <form action="{{ route('mentorat.demander.store') }}" method="POST" id="mentorForm">
                @csrf
                <input type="hidden" name="mentor_id" id="mentorIdInput" value="">

                @if($mentors->isNotEmpty())

                    <div style="font-family:'Urbanist',sans-serif;font-size:0.85rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:0.85rem;">
                        {{ $mentors->count() }} mentor{{ $mentors->count() > 1 ? 's' : '' }} disponible{{ $mentors->count() > 1 ? 's' : '' }}
                    </div>

                    <div class="mentors-grid">
                        @foreach($mentors as $mentor)
                            @php
                                $pubIds  = $mentor->publications()->pluck('id');
                                $note    = \App\Models\Avis::whereIn('publication_id', $pubIds)->avg('note');
                                $nbPubs  = $mentor->publications()->where('statut','publié')->count();
                            @endphp
                            <div class="mentor-card" data-id="{{ $mentor->id }}"
                                onclick="selectMentor(this, {{ $mentor->id }})">

                                <div class="mentor-card-check" id="check-{{ $mentor->id }}">
                                    <i class="fas fa-check"></i>
                                </div>

                                <div class="mentor-card-top">
                                    <div class="mentor-ava">
                                        @if($mentor->photo)
                                            <img src="{{ asset('storage/'.$mentor->photo) }}" alt="">
                                        @else
                                            {{ strtoupper(substr($mentor->prenom, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="mentor-name">{{ $mentor->prenom }} {{ $mentor->nom }}</div>
                                        <div class="mentor-spec">{{ $mentor->specialite ?? 'Mentor' }}</div>
                                    </div>
                                </div>

                                @if($mentor->bio)
                                    <div class="mentor-bio">{{ Str::limit($mentor->bio, 100) }}</div>
                                @endif

                                <div class="mentor-stats">
                                    <span class="mentor-stat">
                                        <i class="fas fa-folder" style="color:var(--purple);"></i>
                                        {{ $nbPubs }} publications
                                    </span>
                                    @if($note)
                                        <span class="mentor-stat">
                                            <i class="fas fa-star" style="color:#F59E0B;"></i>
                                            {{ number_format($note, 1) }}/5
                                        </span>
                                    @endif
                                    @if($mentor->localisation)
                                        <span class="mentor-stat">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $mentor->localisation }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                
                    <!-- Motivation obligatoire -->
                    <div style="margin-bottom:1rem;">
                        <label style="font-family:'Urbanist',sans-serif;font-size:0.82rem;font-weight:700;color:var(--gray-700);display:block;margin-bottom:0.5rem;">
                            Pourquoi souhaitez-vous un mentor ? <span style="color:#DC2626;">*</span>
                        </label>
                        <textarea name="motivation" id="motivationInput" rows="4"
                            placeholder="Décrivez vos objectifs, ce que vous souhaitez apprendre et pourquoi vous avez besoin d'un accompagnement... (minimum 30 caractères)"
                            style="width:100%;padding:0.75rem 1rem;border:1.5px solid var(--gray-200);border-radius:12px;font-size:0.85rem;font-family:'DM Sans',sans-serif;color:var(--gray-900);resize:vertical;outline:none;transition:border-color 0.2s;line-height:1.6;"
                            onfocus="this.style.borderColor='var(--purple)'"
                            onblur="this.style.borderColor='var(--gray-200)'"
                            oninput="updateSubmitBtn()"
                            maxlength="500">{{ old('motivation') }}</textarea>
                        <div style="display:flex;justify-content:space-between;margin-top:0.3rem;">
                            @error('motivation')
                                <span style="font-size:0.75rem;color:#DC2626;">{{ $message }}</span>
                            @else
                                <span style="font-size:0.72rem;color:var(--gray-400);">Minimum 30 caractères</span>
                            @enderror
                            <span id="charCount" style="font-size:0.72rem;color:var(--gray-400);">0/500</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit-mentor" id="btnSubmit" disabled>
                        <i class="fas fa-paper-plane"></i>
                        Soumettre ma demande
                    </button>

                    <p style="text-align:center;font-size:0.75rem;color:var(--gray-400);margin-top:0.75rem;">
                        <i class="fas fa-info-circle me-1"></i>
                        L'attribution finale est effectuée par l'administrateur DevHub.
                    </p>

                @else
                    <div class="mentors-empty">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <div style="font-family:'Urbanist',sans-serif;font-size:1rem;font-weight:700;color:var(--gray-600);margin-bottom:0.4rem;">
                            Aucun mentor disponible
                        </div>
                        <p style="font-size:0.85rem;color:var(--gray-400);">
                            Aucun mentor n'est disponible pour le moment. Revenez plus tard.
                        </p>
                    </div>
                @endif
            </form>
        @endif

    </main>
@endsection

@section('scripts')
    <script>
        function selectMentor(card, id) {
            document.querySelectorAll('.mentor-card').forEach(function(c) {
                c.classList.remove('selected');
            });
            card.classList.add('selected');
            document.getElementById('mentorIdInput').value = id;
            updateSubmitBtn();
        }

        function updateSubmitBtn() {
            var motivation = document.getElementById('motivationInput');
            var btn        = document.getElementById('btnSubmit');
            var counter    = document.getElementById('charCount');
            var mentorId   = document.getElementById('mentorIdInput').value;

            if (motivation && counter) {
                counter.textContent = motivation.value.length + '/500';
            }

            if (btn) {
                btn.disabled = !(mentorId && motivation && motivation.value.length >= 30);
            }
        }
    </script>
@endsection