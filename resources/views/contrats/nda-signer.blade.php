@extends('layouts.app')
@section('title', 'Contrat de confidentialité – DevHub')

@section('styles')
<style>
    .nda-wrap {
        max-width: 820px; margin: 0 auto; padding: 0 0 3rem;
    }
    .nda-header {
        display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;
    }
    .nda-back {
        width: 36px; height: 36px; border-radius: 10px;
        background: var(--gray-100); color: var(--gray-500);
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; font-size: 0.9rem; flex-shrink: 0;
        transition: background 0.15s;
    }
    .nda-back:hover { background: var(--gray-200); }
    .nda-title-block h1 {
        font-family: 'Urbanist', sans-serif;
        font-size: 1.1rem; font-weight: 800; color: var(--gray-900);
    }
    .nda-title-block p { font-size: 0.82rem; color: var(--gray-400); margin-top: 0.1rem; }

    /* Info banner */
    .nda-info-banner {
        background: linear-gradient(135deg, #FFFBEB, #FEF3C7);
        border: 1.5px solid #FDE68A; border-radius: 14px;
        padding: 1.1rem 1.35rem; display: flex; gap: 1rem;
        align-items: flex-start; margin-bottom: 1.5rem;
    }
    .nda-info-icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: #F59E0B; color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .nda-info-text h3 {
        font-family: 'Urbanist', sans-serif; font-size: 0.9rem;
        font-weight: 700; color: #92400E; margin-bottom: 0.2rem;
    }
    .nda-info-text p { font-size: 0.8rem; color: #78350F; margin: 0; line-height: 1.5; }

    /* Parties */
    .nda-parties {
        display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
        margin-bottom: 1.5rem;
    }
    @media (max-width: 600px) { .nda-parties { grid-template-columns: 1fr; } }
    .nda-party-card {
        background: white; border: 1.5px solid var(--gray-200);
        border-radius: 12px; padding: 1rem 1.25rem;
    }
    .nda-party-label {
        font-size: 0.7rem; font-weight: 700; color: var(--purple);
        text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;
    }
    .nda-party-name {
        font-family: 'Urbanist', sans-serif; font-size: 0.95rem;
        font-weight: 700; color: var(--gray-900);
    }
    .nda-party-email { font-size: 0.78rem; color: var(--gray-400); margin-top: 0.15rem; }

    /* Contract box */
    .nda-contract-box {
        background: white; border: 1.5px solid var(--gray-200);
        border-radius: 14px; overflow: hidden; margin-bottom: 1.5rem;
    }
    .nda-contract-header {
        background: linear-gradient(135deg, var(--purple), #5B21B6);
        padding: 1.5rem 2rem; text-align: center; color: white;
    }
    .nda-contract-header h2 {
        font-family: 'Urbanist', sans-serif; font-size: 1.15rem;
        font-weight: 800; margin-bottom: 0.25rem;
    }
    .nda-contract-header p { font-size: 0.82rem; opacity: 0.85; margin: 0; }

    .nda-scroll-area {
        height: 480px; overflow-y: auto; padding: 2rem;
        font-size: 0.875rem; line-height: 1.8; color: #374151;
        border-bottom: 1.5px solid var(--gray-200);
        scroll-behavior: smooth;
    }
    .nda-scroll-area::-webkit-scrollbar { width: 6px; }
    .nda-scroll-area::-webkit-scrollbar-track { background: var(--gray-100); }
    .nda-scroll-area::-webkit-scrollbar-thumb { background: var(--purple); border-radius: 3px; }

    .nda-article {
        margin-bottom: 1.75rem;
    }
    .nda-article-title {
        font-family: 'Urbanist', sans-serif; font-size: 0.9rem; font-weight: 800;
        color: var(--purple); text-transform: uppercase; letter-spacing: 0.5px;
        margin-bottom: 0.75rem; padding-bottom: 0.4rem;
        border-bottom: 2px solid var(--purple-light);
    }
    .nda-article p { margin: 0 0 0.6rem; }
    .nda-article ul { padding-left: 1.5rem; margin: 0.4rem 0 0.6rem; }
    .nda-article ul li { margin-bottom: 0.3rem; }
    .nda-highlight {
        background: #F5F3FF; border-left: 3px solid var(--purple);
        padding: 0.75rem 1rem; border-radius: 0 8px 8px 0;
        margin: 0.75rem 0; font-style: italic; color: #4B5563;
    }

    /* Scroll indicator */
    .nda-scroll-hint {
        text-align: center; padding: 0.75rem; background: var(--gray-50);
        font-size: 0.78rem; color: var(--gray-400);
        border-bottom: 1.5px solid var(--gray-200);
    }
    .nda-scroll-hint.done { background: #F0FDF4; color: #059669; }

    /* Sign area */
    .nda-sign-area { padding: 1.5rem 2rem; }
    .nda-checkbox-label {
        display: flex; gap: 0.75rem; cursor: pointer;
        padding: 1rem 1.25rem; background: var(--gray-50);
        border: 1.5px solid var(--gray-200); border-radius: 10px;
        margin-bottom: 1.25rem; transition: border-color 0.2s, background 0.2s;
    }
    .nda-checkbox-label:hover { border-color: var(--purple); }
    .nda-checkbox-label.checked { background: #F5F3FF; border-color: var(--purple); }
    .nda-checkbox-label input[type="checkbox"] {
        width: 18px; height: 18px; flex-shrink: 0; margin-top: 2px;
        accent-color: var(--purple); cursor: pointer;
    }
    .nda-checkbox-text { font-size: 0.85rem; color: var(--gray-700); line-height: 1.6; }
    .nda-checkbox-text strong { color: var(--gray-900); }

    .nda-sign-meta {
        display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    @media (max-width: 600px) { .nda-sign-meta { grid-template-columns: 1fr; } }
    .nda-meta-item {
        background: var(--gray-50); border: 1px solid var(--gray-200);
        border-radius: 8px; padding: 0.65rem 0.9rem;
        font-size: 0.78rem;
    }
    .nda-meta-label { font-weight: 700; color: var(--gray-500); margin-bottom: 0.15rem; }
    .nda-meta-value { color: var(--gray-800); }

    .btn-sign {
        width: 100%; padding: 0.9rem; background: var(--purple); color: white;
        border: none; border-radius: 12px; font-size: 1rem; font-weight: 700;
        cursor: pointer; transition: background 0.2s; font-family: 'DM Sans', sans-serif;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    }
    .btn-sign:hover { background: var(--purple-dark); }
    .btn-sign:disabled { background: var(--gray-200); color: var(--gray-400); cursor: not-allowed; }

    /* Scroll progress bar */
    .nda-progress-bar {
        height: 3px; background: var(--gray-200); position: relative;
    }
    .nda-progress-fill {
        height: 100%; background: var(--purple); width: 0%;
        transition: width 0.2s;
    }
</style>
@endsection

@section('content')
<main class="main-content">
<div class="nda-wrap">

    <div class="nda-header">
        <a href="{{ route('dashboard') }}" class="nda-back">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="nda-title-block">
            <h1>Contrat de Non-Divulgation</h1>
            <p>Projet : <strong>{{ $contrat->publication->titre }}</strong></p>
        </div>
    </div>

    {{-- Avertissement --}}
    <div class="nda-info-banner">
        <div class="nda-info-icon"><i class="fas fa-shield-alt"></i></div>
        <div class="nda-info-text">
            <h3>Lecture obligatoire avant signature</h3>
            <p>Lisez l'intégralité du contrat jusqu'à la fin avant de pouvoir le signer. Ce contrat a valeur juridique et protège toutes les parties du projet.</p>
        </div>
    </div>

    {{-- Parties --}}
    <div class="nda-parties">
        <div class="nda-party-card">
            <div class="nda-party-label"><i class="fas fa-crown me-1"></i> Responsable du projet</div>
            <div class="nda-party-name">{{ $contrat->publication->auteur->prenom }} {{ $contrat->publication->auteur->nom }}</div>
            <div class="nda-party-email">{{ $contrat->publication->auteur->email }}</div>
        </div>
        <div class="nda-party-card" style="border-color:var(--purple);">
            <div class="nda-party-label" style="color:var(--purple);"><i class="fas fa-user me-1"></i> Collaborateur (vous)</div>
            <div class="nda-party-name">{{ $contrat->utilisateur->prenom }} {{ $contrat->utilisateur->nom }}</div>
            <div class="nda-party-email">{{ $contrat->utilisateur->email }}</div>
        </div>
    </div>

    {{-- Contrat --}}
    <div class="nda-contract-box">
        <div class="nda-contract-header">
            <h2><i class="fas fa-file-contract me-2"></i>Accord de Non-Divulgation et de Confidentialité</h2>
            <p>Projet : « {{ $contrat->publication->titre }} » · Plateforme DevHub · {{ now()->format('d/m/Y') }}</p>
        </div>

        <div class="nda-progress-bar">
            <div class="nda-progress-fill" id="progressFill"></div>
        </div>

        <div id="ndaScrollHint" class="nda-scroll-hint">
            <i class="fas fa-arrow-down me-1"></i> Faites défiler jusqu'en bas pour activer la signature
        </div>

        <div class="nda-scroll-area" id="ndaScroll">
            @php $auteur = $contrat->publication->auteur; $candidat = $contrat->utilisateur; @endphp

            <div style="text-align:center;margin-bottom:2rem;padding-bottom:1.5rem;border-bottom:2px solid var(--gray-100);">
                <div style="font-size:0.8rem;color:var(--gray-400);margin-bottom:0.25rem;">Entre :</div>
                <div style="font-weight:700;color:var(--gray-900);">{{ $auteur->prenom }} {{ $auteur->nom }}</div>
                <div style="font-size:0.82rem;color:var(--gray-500);">{{ $auteur->email }} — ci-après « le Responsable »</div>
                <div style="margin:0.75rem 0;color:var(--gray-400);font-weight:700;">et</div>
                <div style="font-weight:700;color:var(--gray-900);">{{ $candidat->prenom }} {{ $candidat->nom }}</div>
                <div style="font-size:0.82rem;color:var(--gray-500);">{{ $candidat->email }} — ci-après « le Collaborateur »</div>
                <div style="margin-top:1rem;font-size:0.85rem;color:var(--gray-600);">
                    dans le cadre du projet : <strong>« {{ $contrat->publication->titre }} »</strong>
                    hébergé sur la plateforme <strong>DevHub</strong>.
                </div>
            </div>

            <div class="nda-article">
                <div class="nda-article-title">Préambule</div>
                <p>Dans le cadre de leur collaboration sur le projet susmentionné (ci-après le « Projet »), les Parties seront amenées à échanger et à partager des informations techniques, commerciales, fonctionnelles ou stratégiques de nature confidentielle. Le présent Accord a pour objet de définir les conditions dans lesquelles ces informations peuvent être utilisées et d'en garantir la protection mutuelle.</p>
            </div>

            <div class="nda-article">
                <div class="nda-article-title">Article 1 — Définitions</div>
                <p><strong>1.1</strong> « Informations Confidentielles » désigne toutes les données, informations, documents, codes sources, algorithmes, bases de données, spécifications techniques, maquettes, études, plans d'affaires, stratégies commerciales, données personnelles, savoir-faire, et toute autre information divulguée par une Partie à l'autre, que ce soit oralement, par écrit, sous forme électronique, graphique ou sous tout autre format, en lien avec le Projet.</p>
                <p><strong>1.2</strong> « Partie Divulgatrice » désigne la Partie qui transmet des Informations Confidentielles.</p>
                <p><strong>1.3</strong> « Partie Réceptrice » désigne la Partie qui reçoit des Informations Confidentielles.</p>
                <p><strong>1.4</strong> « Tiers » désigne toute personne physique ou morale étrangère au présent Accord.</p>
            </div>

            <div class="nda-article">
                <div class="nda-article-title">Article 2 — Étendue des informations confidentielles</div>
                <p>Sont notamment considérées comme Informations Confidentielles au sens du présent Accord :</p>
                <ul>
                    <li>Le code source, les architectures logicielles, les API, les schémas de bases de données et tout élément technique afférent au Projet ;</li>
                    <li>Les données fonctionnelles, cahiers des charges, spécifications, maquettes et prototypes ;</li>
                    <li>Les données personnelles des utilisateurs collectées ou traitées dans le cadre du Projet ;</li>
                    <li>Les informations financières, budgétaires ou commerciales relatives au Projet ;</li>
                    <li>Les stratégies de développement, de déploiement ou de commercialisation ;</li>
                    <li>Toute communication échangée entre les Parties via la messagerie de la Plateforme ou tout autre canal ;</li>
                    <li>Les identifiants d'accès, mots de passe, clés API et secrets d'environnement partagés.</li>
                </ul>
            </div>

            <div class="nda-article">
                <div class="nda-article-title">Article 3 — Obligations de confidentialité</div>
                <p><strong>3.1</strong> La Partie Réceptrice s'engage à :</p>
                <ul>
                    <li>Traiter les Informations Confidentielles avec le même niveau de protection qu'elle accorde à ses propres informations confidentielles, et en tout état de cause, avec un niveau de protection raisonnable ;</li>
                    <li>Ne pas divulguer, transmettre, reproduire, publier ou mettre à la disposition de Tiers les Informations Confidentielles, sans l'accord écrit préalable de la Partie Divulgatrice ;</li>
                    <li>N'utiliser les Informations Confidentielles qu'aux fins exclusives de la réalisation du Projet ;</li>
                    <li>Limiter l'accès aux Informations Confidentielles aux seules personnes qui en ont strictement besoin dans le cadre de leur participation au Projet ;</li>
                    <li>Signaler sans délai à la Partie Divulgatrice toute violation ou tentative de violation de la confidentialité dont elle aurait connaissance.</li>
                </ul>
                <p><strong>3.2</strong> Le Collaborateur s'engage en particulier à ne pas :</p>
                <ul>
                    <li>Copier, dupliquer ou reproduire les Informations Confidentielles à des fins personnelles ou commerciales sans autorisation ;</li>
                    <li>Utiliser les Informations Confidentielles pour développer un produit ou service concurrent ;</li>
                    <li>Partager les identifiants d'accès ou les environnements techniques avec des Tiers.</li>
                </ul>
            </div>

            <div class="nda-article">
                <div class="nda-article-title">Article 4 — Propriété intellectuelle</div>
                <p><strong>4.1</strong> Toutes les contributions intellectuelles apportées par le Collaborateur dans le cadre du Projet — incluant sans limitation les codes, modules, fonctionnalités, designs, algorithmes et documentations — sont réalisées sous la supervision du Responsable et constituent des œuvres de collaboration au sens du droit applicable.</p>
                <p><strong>4.2</strong> Sauf accord écrit contraire entre les Parties, les droits de propriété intellectuelle sur les créations réalisées dans le cadre du Projet sont détenus conjointement par les Parties proportionnellement à leurs contributions respectives, telles que documentées dans l'historique de la Plateforme.</p>
                <p><strong>4.3</strong> Le Collaborateur garantit que ses contributions ne portent pas atteinte aux droits de propriété intellectuelle de Tiers.</p>
                <p><strong>4.4</strong> En cas de fin de collaboration, le Collaborateur conserve son droit moral sur ses contributions identifiables, mais s'engage à ne pas exercer ses droits patrimoniaux de manière à nuire au développement ou à l'exploitation du Projet.</p>
            </div>

            <div class="nda-article">
                <div class="nda-article-title">Article 5 — Non-concurrence et non-sollicitation</div>
                <p><strong>5.1</strong> Pendant toute la durée de sa participation au Projet et pour une période de <strong>douze (12) mois</strong> à compter de la fin de sa collaboration, le Collaborateur s'engage à ne pas développer, seul ou en collaboration avec des Tiers, une application, un service ou un produit présentant un caractère substantiellement similaire au Projet et destiné au même marché cible.</p>
                <p><strong>5.2</strong> Le Collaborateur s'engage à ne pas solliciter, directement ou indirectement, les autres membres de l'équipe du Projet à des fins concurrentes pendant une période de <strong>six (6) mois</strong> à compter de la fin de sa collaboration.</p>
            </div>

            <div class="nda-article">
                <div class="nda-article-title">Article 6 — Durée</div>
                <p><strong>6.1</strong> Le présent Accord entre en vigueur à la date de sa signature électronique par le Collaborateur et demeure applicable pendant toute la durée de la collaboration sur le Projet.</p>
                <p><strong>6.2</strong> Les obligations de confidentialité prévues aux Articles 2, 3 et 5 survivent à la fin du présent Accord et demeurent applicables pendant une période de <strong>deux (2) ans</strong> à compter de la date de fin de la collaboration.</p>
            </div>

            <div class="nda-article">
                <div class="nda-article-title">Article 7 — Restitution et destruction des informations</div>
                <p>À la fin de sa collaboration ou sur demande du Responsable, le Collaborateur s'engage à :</p>
                <ul>
                    <li>Restituer tout support physique ou électronique contenant des Informations Confidentielles ;</li>
                    <li>Supprimer définitivement toute copie locale des Informations Confidentielles ;</li>
                    <li>Certifier par écrit, sur demande, avoir procédé à ladite suppression.</li>
                </ul>
            </div>

            <div class="nda-article">
                <div class="nda-article-title">Article 8 — Exclusions</div>
                <p>Ne sont pas soumises aux obligations de confidentialité du présent Accord les informations qui :</p>
                <ul>
                    <li>Étaient déjà dans le domaine public au moment de leur divulgation ou qui y tombent ultérieurement sans faute de la Partie Réceptrice ;</li>
                    <li>Étaient légitimement connues de la Partie Réceptrice avant leur divulgation, sans obligation de confidentialité ;</li>
                    <li>Sont reçues licitement d'un Tiers sans restriction de divulgation ;</li>
                    <li>Sont développées de manière indépendante par la Partie Réceptrice sans recours aux Informations Confidentielles ;</li>
                    <li>Doivent être divulguées en vertu d'une obligation légale ou réglementaire, sous réserve d'en aviser préalablement la Partie Divulgatrice dans les meilleurs délais.</li>
                </ul>
            </div>

            <div class="nda-article">
                <div class="nda-article-title">Article 9 — Sanctions et responsabilité</div>
                <p><strong>9.1</strong> Tout manquement aux obligations du présent Accord est susceptible d'engager la responsabilité civile de la Partie fautive, laquelle sera tenue de réparer l'intégralité des préjudices en résultant, tant matériels que moraux, directs et indirects.</p>
                <p><strong>9.2</strong> La Partie lésée se réserve le droit de saisir toute juridiction compétente pour obtenir :</p>
                <ul>
                    <li>L'interdiction de poursuivre les actes litigieux par voie d'injonction ;</li>
                    <li>La réparation des préjudices subis ;</li>
                    <li>La condamnation de la Partie fautive aux frais et dépens.</li>
                </ul>
                <p><strong>9.3</strong> La gravité d'une violation de la confidentialité relative aux données personnelles des utilisateurs pourra, le cas échéant, entraîner un signalement auprès des autorités compétentes en matière de protection des données.</p>
            </div>

            <div class="nda-article">
                <div class="nda-article-title">Article 10 — Dispositions générales</div>
                <p><strong>10.1 Intégralité :</strong> Le présent Accord constitue l'intégralité de l'entente entre les Parties concernant son objet et remplace tous accords antérieurs éventuels.</p>
                <p><strong>10.2 Indépendance :</strong> Si une clause du présent Accord est déclarée nulle ou inapplicable, les autres clauses demeurent pleinement en vigueur.</p>
                <p><strong>10.3 Non-renonciation :</strong> Le fait pour une Partie de ne pas exercer un droit conféré par le présent Accord ne constitue pas une renonciation à ce droit pour l'avenir.</p>
                <p><strong>10.4 Modifications :</strong> Toute modification du présent Accord doit faire l'objet d'un accord écrit entre les Parties.</p>
                <p><strong>10.5 Loi applicable :</strong> Le présent Accord est régi par les lois en vigueur en République du Bénin ainsi que par les textes applicables de l'espace OHADA. En cas de litige, les Parties s'engagent à rechercher en priorité une résolution amiable avant tout recours judiciaire.</p>
            </div>

            <div class="nda-highlight">
                En cliquant sur « Signer et rejoindre le projet », le Collaborateur reconnaît avoir lu et compris l'intégralité du présent Accord, et accepte sans réserve les obligations qui lui incombent. La présente signature électronique a pleine valeur contractuelle.
            </div>

            <div id="contractEnd" style="height:1px;"></div>
        </div>

        {{-- Validation formulaire --}}
        <div class="nda-sign-area">
            <form method="POST" action="{{ route('nda.signer', $contrat->token) }}" id="ndaForm">
                @csrf

                <label class="nda-checkbox-label" id="checkLabel">
                    <input type="checkbox" name="accord" id="accordCheck" value="1">
                    <div class="nda-checkbox-text">
                        <strong>Je certifie avoir lu et compris l'intégralité du présent Accord</strong> de Non-Divulgation et de Confidentialité. J'accepte sans réserve toutes les obligations qui m'incombent en tant que Collaborateur et je reconnais que ma signature électronique a valeur contractuelle.
                    </div>
                </label>

                <div class="nda-sign-meta">
                    <div class="nda-meta-item">
                        <div class="nda-meta-label"><i class="fas fa-user me-1"></i> Signataire</div>
                        <div class="nda-meta-value">{{ $contrat->utilisateur->prenom }} {{ $contrat->utilisateur->nom }}</div>
                    </div>
                    <div class="nda-meta-item">
                        <div class="nda-meta-label"><i class="fas fa-calendar me-1"></i> Date de signature</div>
                        <div class="nda-meta-value">{{ now()->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>

                <button type="submit" class="btn-sign" id="btnSign" disabled>
                    <i class="fas fa-file-signature"></i>
                    Signer et rejoindre le projet
                </button>

                <p style="text-align:center;font-size:0.75rem;color:var(--gray-400);margin-top:0.75rem;">
                    <i class="fas fa-shield-alt me-1"></i>
                    Après signature, un PDF du contrat sera envoyé à votre adresse email.
                </p>
            </form>
        </div>
    </div>

</div>
</main>
@endsection

@section('scripts')
<script>
    var scrolled     = false;
    var checked      = false;
    var scrollArea   = document.getElementById('ndaScroll');
    var progressFill = document.getElementById('progressFill');
    var scrollHint   = document.getElementById('ndaScrollHint');
    var accordCheck  = document.getElementById('accordCheck');
    var checkLabel   = document.getElementById('checkLabel');
    var btnSign      = document.getElementById('btnSign');

    function updateBtn() {
        btnSign.disabled = !(scrolled && checked);
    }

    scrollArea.addEventListener('scroll', function () {
        var total   = this.scrollHeight - this.clientHeight;
        var current = this.scrollTop;
        var pct     = total > 0 ? Math.round((current / total) * 100) : 100;
        progressFill.style.width = pct + '%';

        if (pct >= 95) {
            scrolled = true;
            scrollHint.innerHTML = '<i class="fas fa-check me-1"></i> Lecture complète — vous pouvez maintenant signer';
            scrollHint.className = 'nda-scroll-hint done';
            accordCheck.disabled = false;
        }
        updateBtn();
    });

    // Désactiver la checkbox tant que non scrollé
    accordCheck.disabled = true;
    accordCheck.addEventListener('change', function () {
        checked = this.checked;
        checkLabel.classList.toggle('checked', checked);
        updateBtn();
    });

    // Protection double-soumission
    document.getElementById('ndaForm').addEventListener('submit', function () {
        btnSign.disabled = true;
        btnSign.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Signature en cours...';
    });
</script>
@endsection
