<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 10pt;
        color: #1F2937;
        line-height: 1.6;
    }

    /* En-tête */
    .header {
        background: #7C3AED;
        padding: 18pt 24pt;
        color: white;
        text-align: center;
        margin-bottom: 18pt;
    }
    .header-logo {
        font-size: 20pt;
        font-weight: bold;
        letter-spacing: 1pt;
    }
    .header-sub {
        font-size: 8pt;
        opacity: 0.85;
        margin-top: 3pt;
    }
    .doc-title {
        font-size: 13pt;
        font-weight: bold;
        text-align: center;
        color: #4B0082;
        margin-bottom: 6pt;
        text-transform: uppercase;
        letter-spacing: 1pt;
    }
    .doc-sub {
        font-size: 9pt;
        text-align: center;
        color: #6B7280;
        margin-bottom: 18pt;
    }

    .main-content {
        padding: 0 24pt;
    }

    /* Parties */
    .parties-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 18pt;
    }
    .parties-table td {
        width: 50%;
        vertical-align: top;
        padding: 10pt 12pt;
        border: 1.5pt solid #DDD6FE;
        background: #F5F3FF;
    }
    .party-label {
        font-size: 7pt;
        font-weight: bold;
        color: #7C3AED;
        text-transform: uppercase;
        letter-spacing: 0.5pt;
        margin-bottom: 4pt;
    }
    .party-name {
        font-size: 11pt;
        font-weight: bold;
        color: #111827;
    }
    .party-email {
        font-size: 8pt;
        color: #6B7280;
        margin-top: 2pt;
    }

    /* Articles */
    .article {
        margin-bottom: 14pt;
        page-break-inside: avoid;
    }
    .article-title {
        font-size: 9pt;
        font-weight: bold;
        color: #7C3AED;
        text-transform: uppercase;
        letter-spacing: 0.5pt;
        border-bottom: 1pt solid #DDD6FE;
        padding-bottom: 4pt;
        margin-bottom: 8pt;
    }
    .article p {
        margin-bottom: 5pt;
        text-align: justify;
    }
    .article ul {
        padding-left: 16pt;
        margin: 4pt 0;
    }
    .article ul li {
        margin-bottom: 3pt;
    }
    .highlight-box {
        background: #F5F3FF;
        border-left: 3pt solid #7C3AED;
        padding: 8pt 10pt;
        margin: 8pt 0;
        font-style: italic;
        color: #4B5563;
        font-size: 9pt;
    }
    .divider {
        border: none;
        border-top: 1pt solid #E5E7EB;
        margin: 14pt 0;
    }

    /* Zone signature */
    .signature-section {
        margin-top: 20pt;
        page-break-inside: avoid;
    }
    .signature-title {
        font-size: 10pt;
        font-weight: bold;
        color: #111827;
        text-align: center;
        margin-bottom: 12pt;
        text-transform: uppercase;
    }
    .signature-table {
        width: 100%;
        border-collapse: collapse;
    }
    .signature-table td {
        width: 50%;
        padding: 10pt 12pt;
        border: 1.5pt solid #E5E7EB;
        vertical-align: top;
    }
    .sig-label {
        font-size: 7.5pt;
        font-weight: bold;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.4pt;
        margin-bottom: 4pt;
    }
    .sig-value {
        font-size: 9.5pt;
        color: #111827;
        font-weight: bold;
    }
    .sig-detail {
        font-size: 7.5pt;
        color: #9CA3AF;
        margin-top: 2pt;
    }
    .sig-stamp {
        text-align: center;
        margin-top: 8pt;
        padding: 8pt;
        background: #F0FDF4;
        border: 1pt solid #86EFAC;
        border-radius: 4pt;
    }
    .sig-stamp-text {
        font-size: 8pt;
        color: #059669;
        font-weight: bold;
    }
    .sig-stamp-sub {
        font-size: 7pt;
        color: #6B7280;
        margin-top: 2pt;
    }

    /* Footer */
    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 7.5pt;
        color: #9CA3AF;
        padding: 6pt;
        border-top: 1pt solid #E5E7EB;
        background: white;
    }
    .page-number:after {
        content: counter(page);
    }
</style>
</head>
<body>

<div class="footer">
    DevHub — Document confidentiel · Contrat NDA #{{ $contrat->id }} · Page <span class="page-number"></span>
</div>

<div class="header">
    <div class="header-logo">DevHub</div>
    <div class="header-sub">Plateforme des développeurs juniors au Bénin</div>
</div>

<div class="doc-title">Accord de Non-Divulgation et de Confidentialité</div>
<div class="doc-sub">
    Projet : « {{ $pub->titre }} » · Signé le {{ $dateSignature->format('d/m/Y à H:i') }} · Ref. NDA#{{ $contrat->id }}
</div>

<div class="main-content">
    <table class="parties-table">
        <tr>
            <td>
                <div class="party-label">Responsable du projet</div>
                <div class="party-name">{{ $auteur->prenom }} {{ $auteur->nom }}</div>
                <div class="party-email">{{ $auteur->email }}</div>
            </td>
            <td>
                <div class="party-label">Collaborateur (signataire)</div>
                <div class="party-name">{{ $candidat->prenom }} {{ $candidat->nom }}</div>
                <div class="party-email">{{ $candidat->email }}</div>
            </td>
        </tr>
    </table>

    <div class="article">
        <div class="article-title">Préambule</div>
        <p>Dans le cadre de leur collaboration sur le projet susmentionné (ci-après le « Projet »), les Parties seront amenées à échanger et à partager des informations techniques, commerciales, fonctionnelles ou stratégiques de nature confidentielle. Le présent Accord a pour objet de définir les conditions dans lesquelles ces informations peuvent être utilisées et d'en garantir la protection mutuelle.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 1 — Définitions</div>
        <p><strong>1.1</strong> « Informations Confidentielles » désigne toutes les données, informations, documents, codes sources, algorithmes, bases de données, spécifications techniques, maquettes, études, plans d'affaires, stratégies commerciales, données personnelles, savoir-faire, et toute autre information divulguée par une Partie à l'autre, en lien avec le Projet.</p>
        <p><strong>1.2</strong> « Partie Divulgatrice » désigne la Partie qui transmet des Informations Confidentielles.</p>
        <p><strong>1.3</strong> « Partie Réceptrice » désigne la Partie qui reçoit des Informations Confidentielles.</p>
        <p><strong>1.4</strong> « Tiers » désigne toute personne physique ou morale étrangère au présent Accord.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 2 — Étendue des informations confidentielles</div>
        <p>Sont notamment considérées comme Informations Confidentielles :</p>
        <ul>
            <li>Le code source, les architectures logicielles, les API, les schémas de bases de données ;</li>
            <li>Les données fonctionnelles, cahiers des charges, spécifications, maquettes et prototypes ;</li>
            <li>Les données personnelles des utilisateurs collectées ou traitées dans le cadre du Projet ;</li>
            <li>Les informations financières, budgétaires ou commerciales relatives au Projet ;</li>
            <li>Les stratégies de développement, de déploiement ou de commercialisation ;</li>
            <li>Toute communication échangée entre les Parties via la Plateforme ou tout autre canal ;</li>
            <li>Les identifiants d'accès, mots de passe, clés API et secrets d'environnement.</li>
        </ul>
    </div>

    <div class="article">
        <div class="article-title">Article 3 — Obligations de confidentialité</div>
        <p><strong>3.1</strong> La Partie Réceptrice s'engage à traiter les Informations Confidentielles avec le même niveau de protection qu'elle accorde à ses propres informations et à ne pas les divulguer sans accord écrit préalable. Elle s'engage à n'utiliser ces informations qu'aux fins exclusives du Projet et à signaler toute violation connue.</p>
        <p><strong>3.2</strong> Le Collaborateur s'engage en particulier à ne pas copier les Informations Confidentielles à des fins personnelles, à ne pas les utiliser pour développer un produit concurrent, et à ne pas partager les identifiants techniques avec des Tiers.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 4 — Propriété intellectuelle</div>
        <p><strong>4.1</strong> Toutes les contributions intellectuelles apportées par le Collaborateur dans le cadre du Projet constituent des œuvres de collaboration. Sauf accord contraire, les droits patrimoniaux sont détenus conjointement proportionnellement aux contributions documentées.</p>
        <p><strong>4.2</strong> Le Collaborateur garantit que ses contributions ne portent pas atteinte aux droits de propriété intellectuelle de Tiers.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 5 — Non-concurrence et non-sollicitation</div>
        <p><strong>5.1</strong> Pendant toute la durée de la collaboration et pour une période de <strong>douze (12) mois</strong> après sa fin, le Collaborateur s'engage à ne pas développer un produit ou service substantiellement similaire destiné au même marché.</p>
        <p><strong>5.2</strong> Le Collaborateur s'engage à ne pas solliciter les autres membres de l'équipe à des fins concurrentes pendant <strong>six (6) mois</strong> après la fin de la collaboration.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 6 — Durée</div>
        <p>Le présent Accord entre en vigueur à la date de signature et les obligations de confidentialité (Articles 2, 3, 5) survivent pendant <strong>deux (2) ans</strong> après la fin de la collaboration.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 7 — Restitution et destruction</div>
        <p>À la fin de la collaboration, le Collaborateur s'engage à restituer tous supports contenant des Informations Confidentielles et à en supprimer toute copie locale, sous certification écrite sur demande.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 8 — Exclusions</div>
        <p>Sont exclues des obligations les informations déjà dans le domaine public, connues avant divulgation, reçues licitement d'un Tiers, développées indépendamment, ou divulguées par obligation légale avec préavis.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 9 — Sanctions et responsabilité</div>
        <p>Tout manquement engage la responsabilité civile de la Partie fautive pour l'intégralité des préjudices. La Partie lésée peut demander l'interdiction des actes litigieux, la réparation des préjudices et la condamnation aux dépens devant toute juridiction compétente.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 10 — Dispositions générales</div>
        <p>Le présent Accord constitue l'intégralité de l'entente entre les Parties. Si une clause est nulle, les autres demeurent valables. Toute modification nécessite un accord écrit. Le présent Accord est régi par les lois de la République du Bénin et les textes de l'espace OHADA.</p>
    </div>

    <hr class="divider">

    <div class="signature-section">
        <div class="signature-title">Signature électronique</div>

        <table class="signature-table">
            <tr>
                <td>
                    <div class="sig-label">Signataire</div>
                    <div class="sig-value">{{ $candidat->prenom }} {{ $candidat->nom }}</div>
                    <div class="sig-detail">{{ $candidat->email }}</div>
                </td>
                <td>
                    <div class="sig-label">Date et heure</div>
                    <div class="sig-value">{{ $dateSignature->format('d/m/Y') }}</div>
                    <div class="sig-detail">{{ $dateSignature->format('H:i:s') }} (UTC+1)</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="sig-label">Adresse IP</div>
                    <div class="sig-value">{{ $ipSignature }}</div>
                    <div class="sig-detail">Empreinte de connexion enregistrée</div>
                </td>
                <td>
                    <div class="sig-label">Référence contrat</div>
                    <div class="sig-value">NDA#{{ $contrat->id }}</div>
                    <div class="sig-detail">Plateforme DevHub</div>
                </td>
            </tr>
        </table>

        <div class="sig-stamp">
            <div class="sig-stamp-text">✅ SIGNÉ ÉLECTRONIQUEMENT</div>
            <div class="sig-stamp-sub">
                Document généré automatiquement par DevHub · Valeur juridique conformément aux dispositions OHADA
            </div>
        </div>
    </div>

</div>

</body>
</html>
