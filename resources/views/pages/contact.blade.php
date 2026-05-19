@extends('layouts.public')
@section('title', 'Contact')

@section('content')

<div class="page-hero">
    <div class="conteneur">
        <div class="page-hero-badge"><i class="fas fa-envelope"></i> Contactez-nous</div>
        <div class="page-hero-title">Une question ? On est là.</div>
        <p class="page-hero-sub">
            Notre équipe vous répond dans un délai de 24 à 48 heures ouvrées.
        </p>
    </div>
</div>

<div class="page-content" style="max-width:980px;">

    @if(session('contact_success'))
        <div style="background:#D1FAE5;border:1px solid #6EE7B7;border-radius:12px;padding:1rem 1.25rem;
                    margin-bottom:2rem;display:flex;align-items:center;gap:0.75rem;font-size:0.9rem;color:#065F46;">
            <i class="fas fa-check-circle" style="font-size:1.2rem;"></i>
            {{ session('contact_success') }}
        </div>
    @endif

    <div class="contact-layout">

        {{-- Infos de contact --}}
        <div>
            <div class="page-section-title" style="margin-bottom:1.5rem;">Nos coordonnées</div>

            <div class="contact-info-item">
                <div class="contact-info-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <div style="font-weight:700;color:#111;margin-bottom:0.2rem;">Email</div>
                    <div style="color:#666;font-size:0.9rem;">229devhub@gmail.com</div>
                    <div style="color:#999;font-size:0.78rem;margin-top:0.1rem;">Réponse sous 24–48 h</div>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <div style="font-weight:700;color:#111;margin-bottom:0.2rem;">Localisation</div>
                    <div style="color:#666;font-size:0.9rem;">Parakou, Bénin</div>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-info-icon"><i class="fas fa-clock"></i></div>
                <div>
                    <div style="font-weight:700;color:#111;margin-bottom:0.2rem;">Disponibilité</div>
                    <div style="color:#666;font-size:0.9rem;">Lun – Ven, 8h – 18h (GMT+1)</div>
                </div>
            </div>

            <div style="margin-top:2rem;padding:1.25rem;background:#F5F3FF;border-radius:12px;border:1px solid #DDD6FE;">
                <div style="font-family:'Urbanist',sans-serif;font-weight:700;color:#7C3AED;margin-bottom:0.5rem;">
                    <i class="fas fa-lightbulb me-2"></i>Vous êtes développeur ?
                </div>
                <p style="font-size:0.85rem;color:#666;margin:0;line-height:1.6;">
                    Rejoignez la communauté DevHub pour partager vos projets, collaborer et bénéficier d'un accompagnement mentor.
                </p>
                <a href="{{ route('register') }}"
                   style="display:inline-block;margin-top:0.75rem;padding:0.45rem 1rem;background:#7C3AED;color:white;
                          border-radius:8px;font-size:0.82rem;font-weight:600;text-decoration:none;">
                    Créer mon compte gratuitement
                </a>
            </div>
        </div>

        {{-- Formulaire --}}
        <div class="contact-form-card">
            <div style="font-family:'Urbanist',sans-serif;font-weight:700;font-size:1.05rem;color:#111;margin-bottom:1.5rem;">
                Envoyer un message
            </div>

            <form action="{{ route('contact.store') }}" method="POST">
                @csrf

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="contact-field">
                        <label>Nom complet <span style="color:#DC2626;">*</span></label>
                        <input type="text" name="nom" value="{{ old('nom') }}"
                               placeholder="Jean Dupont" required>
                        @error('nom')<div style="color:#DC2626;font-size:0.78rem;margin-top:0.25rem;">{{ $message }}</div>@enderror
                    </div>
                    <div class="contact-field">
                        <label>Adresse email <span style="color:#DC2626;">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="vous@email.com" required>
                        @error('email')<div style="color:#DC2626;font-size:0.78rem;margin-top:0.25rem;">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="contact-field">
                    <label>Sujet <span style="color:#DC2626;">*</span></label>
                    <input type="text" name="sujet" value="{{ old('sujet') }}"
                           placeholder="Ex : Question sur mon compte" required>
                    @error('sujet')<div style="color:#DC2626;font-size:0.78rem;margin-top:0.25rem;">{{ $message }}</div>@enderror
                </div>

                <div class="contact-field">
                    <label>Message <span style="color:#DC2626;">*</span></label>
                    <textarea name="message" placeholder="Décrivez votre demande en détail…" required>{{ old('message') }}</textarea>
                    @error('message')<div style="color:#DC2626;font-size:0.78rem;margin-top:0.25rem;">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn-contact-submit">
                    <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
