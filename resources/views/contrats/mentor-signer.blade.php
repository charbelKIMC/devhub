@extends('layouts.mentor')
@section('title', 'Contrat d\'engagement mentor – DevHub')

@section('styles')
<style>
    .cm-wrap {
        max-width: 820px; margin: 0 auto; padding: 0 0 3rem;
    }
    .cm-header {
        display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;
    }
    .cm-title-block h1 {
        font-family: 'Urbanist', sans-serif;
        font-size: 1.1rem; font-weight: 800; color: var(--gray-900);
    }
    .cm-title-block p { font-size: 0.82rem; color: var(--gray-400); margin-top: 0.1rem; }

    .cm-info-banner {
        background: linear-gradient(135deg, #F5F3FF, #EDE9FE);
        border: 1.5px solid #DDD6FE; border-radius: 14px;
        padding: 1.1rem 1.35rem; display: flex; gap: 1rem;
        align-items: flex-start; margin-bottom: 1.5rem;
    }
    .cm-info-icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: var(--purple); color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .cm-info-text h3 {
        font-family: 'Urbanist', sans-serif; font-size: 0.9rem;
        font-weight: 700; color: #4C1D95; margin-bottom: 0.2rem;
    }
    .cm-info-text p { font-size: 0.8rem; color: #5B21B6; margin: 0; line-height: 1.5; }

    .cm-mentor-card {
        background: white; border: 1.5px solid var(--gray-200);
        border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: 1rem;
    }
    .cm-mentor-avatar {
        width: 48px; height: 48px; border-radius: 50%;
        background: linear-gradient(135deg, var(--purple), #5B21B6);
        color: white; display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.1rem; flex-shrink: 0;
        overflow: hidden;
    }
    .cm-mentor-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .cm-mentor-name {
        font-family: 'Urbanist', sans-serif; font-size: 0.95rem;
        font-weight: 700; color: var(--gray-900);
    }
    .cm-mentor-info { font-size: 0.78rem; color: var(--gray-400); margin-top: 0.15rem; }
    .cm-mentor-badge {
        margin-left: auto; background: #F5F3FF; color: var(--purple);
        font-size: 0.72rem; font-weight: 700; padding: 0.3rem 0.75rem;
        border-radius: 20px; border: 1.5px solid #DDD6FE; flex-shrink: 0;
    }

    .cm-contract-box {
        background: white; border: 1.5px solid var(--gray-200);
        border-radius: 14px; overflow: hidden; margin-bottom: 1.5rem;
    }
    .cm-contract-header {
        background: linear-gradient(135deg, var(--purple), #5B21B6);
        padding: 1.5rem 2rem; text-align: center; color: white;
    }
    .cm-contract-header h2 {
        font-family: 'Urbanist', sans-serif; font-size: 1.15rem;
        font-weight: 800; margin-bottom: 0.25rem;
    }
    .cm-contract-header p { font-size: 0.82rem; opacity: 0.85; margin: 0; }

    .cm-scroll-area {
        height: 480px; overflow-y: auto; padding: 2rem;
        font-size: 0.875rem; line-height: 1.8; color: #374151;
        border-bottom: 1.5px solid var(--gray-200);
        scroll-behavior: smooth;
    }
    .cm-scroll-area::-webkit-scrollbar { width: 6px; }
    .cm-scroll-area::-webkit-scrollbar-track { background: var(--gray-100); }
    .cm-scroll-area::-webkit-scrollbar-thumb { background: var(--purple); border-radius: 3px; }

    .cm-article { margin-bottom: 1.75rem; }
    .cm-article-title {
        font-family: 'Urbanist', sans-serif; font-size: 0.9rem; font-weight: 800;
        color: var(--purple); text-transform: uppercase; letter-spacing: 0.5px;
        margin-bottom: 0.75rem; padding-bottom: 0.4rem;
        border-bottom: 2px solid var(--purple-light);
    }
    .cm-article p  { margin: 0 0 0.6rem; }
    .cm-article ul { padding-left: 1.5rem; margin: 0.4rem 0 0.6rem; }
    .cm-article ul li { margin-bottom: 0.3rem; }
    .cm-highlight {
        background: #F5F3FF; border-left: 3px solid var(--purple);
        padding: 0.75rem 1rem; border-radius: 0 8px 8px 0;
        margin: 0.75rem 0; font-style: italic; color: #4B5563;
    }

    .cm-scroll-hint {
        text-align: center; padding: 0.75rem; background: var(--gray-50);
        font-size: 0.78rem; color: var(--gray-400);
        border-bottom: 1.5px solid var(--gray-200);
    }
    .cm-scroll-hint.done { background: #F0FDF4; color: #059669; }

    .cm-sign-area { padding: 1.5rem 2rem; }
    .cm-checkbox-label {
        display: flex; gap: 0.75rem; cursor: pointer;
        padding: 1rem 1.25rem; background: var(--gray-50);
        border: 1.5px solid var(--gray-200); border-radius: 10px;
        margin-bottom: 1.25rem; transition: border-color 0.2s, background 0.2s;
    }
    .cm-checkbox-label:hover { border-color: var(--purple); }
    .cm-checkbox-label.checked { background: #F5F3FF; border-color: var(--purple); }
    .cm-checkbox-label input[type="checkbox"] {
        width: 18px; height: 18px; flex-shrink: 0; margin-top: 2px;
        accent-color: var(--purple); cursor: pointer;
    }
    .cm-checkbox-text { font-size: 0.85rem; color: var(--gray-700); line-height: 1.6; }
    .cm-checkbox-text strong { color: var(--gray-900); }

    .cm-sign-meta {
        display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    @media (max-width: 600px) { .cm-sign-meta { grid-template-columns: 1fr; } }
    .cm-meta-item {
        background: var(--gray-50); border: 1px solid var(--gray-200);
        border-radius: 8px; padding: 0.65rem 0.9rem; font-size: 0.78rem;
    }
    .cm-meta-label { font-weight: 700; color: var(--gray-500); margin-bottom: 0.15rem; }
    .cm-meta-value { color: var(--gray-800); }

    .btn-sign {
        width: 100%; padding: 0.9rem; background: var(--purple); color: white;
        border: none; border-radius: 12px; font-size: 1rem; font-weight: 700;
        cursor: pointer; transition: background 0.2s; font-family: 'DM Sans', sans-serif;
    }
    .btn-sign:hover { background: #5B21B6; }
    .btn-sign:disabled { background: var(--gray-300); cursor: not-allowed; }
</style>
@endsection

@section('content')
<div class="cm-wrap">

    <div class="cm-header">
        <div class="cm-title-block">
            <h1><i class="fas fa-file-signature me-2"></i>Contrat d'engagement mentor</h1>
            <p>Veuillez lire et signer ce contrat pour accéder à votre espace mentor.</p>
        </div>
    </div>

    {{-- Bannière info --}}
    <div class="cm-info-banner">
        <div class="cm-info-icon"><i class="fas fa-shield-alt"></i></div>
        <div class="cm-info-text">
            <h3>Signature unique et permanente</h3>
            <p>Ce contrat ne doit être signé qu'une seule fois. Il couvre l'ensemble de vos activités de mentorat sur DevHub. Une copie signée vous sera envoyée par email ainsi qu'à l'administration.</p>
        </div>
    </div>

    {{-- Carte mentor --}}
    <div class="cm-mentor-card">
        <div class="cm-mentor-avatar">
            @if($mentor->photo)
                <img src="{{ asset('storage/' . $mentor->photo) }}" alt="">
            @else
                {{ strtoupper(substr($mentor->prenom, 0, 1)) }}
            @endif
        </div>
        <div>
            <div class="cm-mentor-name">{{ $mentor->prenom }} {{ $mentor->nom }}</div>
            <div class="cm-mentor-info">{{ $mentor->email }}{{ $mentor->specialite ? ' · ' . $mentor->specialite : '' }}</div>
        </div>
        <div class="cm-mentor-badge"><i class="fas fa-certificate me-1"></i>Mentor DevHub</div>
    </div>

    {{-- Contrat --}}
    <div class="cm-contract-box">
        <div class="cm-contract-header">
            <h2><i class="fas fa-file-contract me-2"></i>Contrat d'Engagement et de Confidentialité Mentor</h2>
            <p>Réf. CM#{{ $contrat->id }} · DevHub — Plateforme des développeurs juniors au Bénin</p>
        </div>

        <div class="cm-scroll-hint" id="scrollHint">
            <i class="fas fa-arrow-down me-1"></i> Faites défiler pour lire le contrat en entier
        </div>

        <div class="cm-scroll-area" id="scrollArea">

            <div class="cm-article">
                <div class="cm-article-title">Préambule</div>
                <p>En acceptant ce contrat, le Mentor reconnaît avoir pris connaissance de l'ensemble des règles, responsabilités et engagements liés à l'exercice de la fonction de mentor au sein de la plateforme DevHub. Ce contrat entre en vigueur dès sa signature électronique et couvre l'intégralité des activités de mentorat du signataire sur la Plateforme.</p>
            </div>

            <div class="cm-article">
                <div class="cm-article-title">Article 1 — Définitions</div>
                <p><strong>1.1</strong> « Mentor » désigne le signataire du présent contrat, titulaire d'un compte mentor actif sur la Plateforme.</p>
                <p><strong>1.2</strong> « Mentoré » désigne tout développeur junior accompagné par le Mentor dans le cadre d'une relation de mentorat individuelle ou au sein d'un projet collaboratif.</p>
                <p><strong>1.3</strong> « Informations Confidentielles » désigne toute donnée, code source, stratégie, information personnelle ou professionnelle partagée par un Mentoré ou une équipe de projet dans le cadre des activités sur la Plateforme.</p>
                <p><strong>1.4</strong> « Projet » désigne tout projet personnel ou collaboratif suivi par le Mentor sur la Plateforme.</p>
            </div>

            <div class="cm-article">
                <div class="cm-article-title">Article 2 — Engagement de confidentialité</div>
                <p><strong>2.1</strong> Le Mentor s'engage à traiter comme strictement confidentielles toutes les Informations Confidentielles portées à sa connaissance dans le cadre de ses activités de mentorat, notamment :</p>
                <ul>
                    <li>Les codes sources, architectures et données techniques des Projets accompagnés ;</li>
                    <li>Les informations personnelles, professionnelles et financières des Mentorés ;</li>
                    <li>Les échanges, conversations et contenus partagés via la messagerie de la Plateforme ;</li>
                    <li>Les stratégies, idées et plans d'affaires communiqués par les Mentorés ;</li>
                    <li>Les identifiants, clés API ou secrets techniques éventuellement partagés.</li>
                </ul>
                <p><strong>2.2</strong> Le Mentor s'engage à ne divulguer ces informations à aucun Tiers sans accord écrit préalable du Mentoré concerné.</p>
                <p><strong>2.3</strong> Ces obligations de confidentialité survivent pendant <strong>trois (3) ans</strong> après la fin de la relation de mentorat.</p>
            </div>

            <div class="cm-article">
                <div class="cm-article-title">Article 3 — Engagement de disponibilité et de suivi</div>
                <p><strong>3.1</strong> Le Mentor s'engage à assurer un suivi régulier de ses Mentorés et à répondre à leurs messages dans un délai raisonnable.</p>
                <p><strong>3.2</strong> En cas d'indisponibilité prolongée, le Mentor s'engage à en informer la Plateforme afin qu'un remplaçant puisse être désigné sans préjudice pour les Mentorés.</p>
                <p><strong>3.3</strong> Le Mentor accepte la limite de <strong>trois (3) projets collaboratifs</strong> simultanés imposée par la Plateforme afin de garantir la qualité de l'accompagnement.</p>
            </div>

            <div class="cm-article">
                <div class="cm-article-title">Article 4 — Intégrité, déontologie et neutralité</div>
                <p><strong>4.1</strong> Le Mentor s'engage à exercer sa mission avec impartialité, sans discrimination fondée sur l'origine, le genre, l'âge ou tout autre critère personnel.</p>
                <p><strong>4.2</strong> Le Mentor s'interdit d'utiliser sa position pour influencer les Mentorés à des fins commerciales personnelles, de recrutement ou de promotion d'intérêts concurrents.</p>
                <p><strong>4.3</strong> Le Mentor ne peut en aucun cas se substituer au développeur dans la réalisation d'un Projet. Son rôle est exclusivement celui d'un guide et d'un conseiller.</p>
                <p><strong>4.4</strong> La propriété intellectuelle des Projets accompagnés appartient intégralement aux développeurs Mentorés. Le Mentor ne revendique aucun droit sur ces œuvres.</p>
            </div>

            <div class="cm-article">
                <div class="cm-article-title">Article 5 — Non-sollicitation</div>
                <p><strong>5.1</strong> Pendant toute la durée de la relation de mentorat et pour une période de <strong>douze (12) mois</strong> après sa fin, le Mentor s'engage à ne pas recruter ou solliciter commercialement ses Mentorés à des fins extérieures à la Plateforme.</p>
                <p><strong>5.2</strong> Le Mentor s'engage à ne pas utiliser les contacts établis via la Plateforme pour détourner des Mentorés vers des services concurrents.</p>
            </div>

            <div class="cm-article">
                <div class="cm-article-title">Article 6 — Responsabilités</div>
                <p><strong>6.1</strong> Le Mentor est responsable de la qualité de ses conseils et de leur conformité aux bonnes pratiques du secteur du développement logiciel.</p>
                <p><strong>6.2</strong> Le Mentor est responsable de tout manquement aux obligations du présent contrat et s'expose, en cas de violation, à la suspension ou à la suppression de son compte, ainsi qu'à des poursuites civiles.</p>
                <p><strong>6.3</strong> La Plateforme DevHub se réserve le droit de résilier l'accès du Mentor en cas de manquement grave ou répété aux présentes obligations.</p>
            </div>

            <div class="cm-article">
                <div class="cm-article-title">Article 7 — Durée</div>
                <p>Le présent contrat entre en vigueur à la date de signature et reste valable pendant toute la durée d'activité du Mentor sur la Plateforme. Les obligations de confidentialité et de non-sollicitation survivent à la résiliation.</p>
            </div>

            <div class="cm-article">
                <div class="cm-article-title">Article 8 — Dispositions générales</div>
                <p>Le présent contrat constitue l'intégralité des engagements du Mentor envers DevHub. Si une clause est déclarée nulle, les autres restent applicables. Tout litige est soumis aux juridictions compétentes de la République du Bénin et aux textes de l'espace OHADA.</p>
            </div>

            <div class="cm-highlight">
                En signant ce contrat, le Mentor reconnaît avoir lu, compris et accepté l'intégralité des présentes dispositions et s'engage à les respecter de bonne foi pendant toute la durée de sa mission sur DevHub.
            </div>

        </div>

        {{-- Zone signature --}}
        <div class="cm-sign-area">
            <form action="{{ route('mentor.contrat.signer') }}" method="POST" id="signForm">
                @csrf

                <label class="cm-checkbox-label" id="checkboxLabel">
                    <input type="checkbox" name="accord" id="accordCheck" value="1">
                    <div class="cm-checkbox-text">
                        <strong>Je certifie avoir lu l'intégralité de ce contrat</strong> et j'accepte sans réserve les engagements de confidentialité, de disponibilité, de déontologie et de non-sollicitation qui y sont stipulés.
                    </div>
                </label>

                <div class="cm-sign-meta">
                    <div class="cm-meta-item">
                        <div class="cm-meta-label">Signataire</div>
                        <div class="cm-meta-value">{{ $mentor->prenom }} {{ $mentor->nom }}</div>
                    </div>
                    <div class="cm-meta-item">
                        <div class="cm-meta-label">Date</div>
                        <div class="cm-meta-value">{{ now()->format('d/m/Y') }}</div>
                    </div>
                    <div class="cm-meta-item">
                        <div class="cm-meta-label">Email</div>
                        <div class="cm-meta-value">{{ $mentor->email }}</div>
                    </div>
                    <div class="cm-meta-item">
                        <div class="cm-meta-label">Référence</div>
                        <div class="cm-meta-value">CM#{{ $contrat->id }}</div>
                    </div>
                </div>

                <button type="submit" class="btn-sign" id="btnSign" disabled>
                    <i class="fas fa-signature me-2"></i>Signer le contrat et accéder à mon espace
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
(function () {
    var scrollArea   = document.getElementById('scrollArea');
    var scrollHint   = document.getElementById('scrollHint');
    var accordCheck  = document.getElementById('accordCheck');
    var checkLabel   = document.getElementById('checkboxLabel');
    var btnSign      = document.getElementById('btnSign');
    var scrolled     = false;

    function checkReady() {
        btnSign.disabled = !(scrolled && accordCheck.checked);
    }

    scrollArea.addEventListener('scroll', function () {
        var atBottom = this.scrollTop + this.clientHeight >= this.scrollHeight - 40;
        if (atBottom && !scrolled) {
            scrolled = true;
            scrollHint.classList.add('done');
            scrollHint.innerHTML = '<i class="fas fa-check me-1"></i> Contrat lu';
        }
        checkReady();
    });

    accordCheck.addEventListener('change', function () {
        checkLabel.classList.toggle('checked', this.checked);
        checkReady();
    });
})();
</script>
@endsection
