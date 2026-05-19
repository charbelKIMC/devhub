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
    .header {
        background: #7C3AED;
        padding: 18pt 24pt;
        color: white;
        text-align: center;
        margin-bottom: 18pt;
    }
    .header-logo { font-size: 20pt; font-weight: bold; letter-spacing: 1pt; }
    .header-sub  { font-size: 8pt; opacity: 0.85; margin-top: 3pt; }
    .doc-title {
        font-size: 13pt; font-weight: bold; text-align: center;
        color: #4B0082; margin-bottom: 6pt;
        text-transform: uppercase; letter-spacing: 1pt;
    }
    .doc-sub { font-size: 9pt; text-align: center; color: #6B7280; margin-bottom: 18pt; }
    .main-content { padding: 0 24pt; }

    .party-block {
        border: 1.5pt solid #DDD6FE; background: #F5F3FF;
        padding: 10pt 14pt; border-radius: 4pt; margin-bottom: 18pt;
    }
    .party-label { font-size: 7pt; font-weight: bold; color: #7C3AED; text-transform: uppercase; letter-spacing: 0.5pt; margin-bottom: 4pt; }
    .party-name  { font-size: 11pt; font-weight: bold; color: #111827; }
    .party-email { font-size: 8pt; color: #6B7280; margin-top: 2pt; }
    .party-spec  { font-size: 8pt; color: #6B7280; margin-top: 1pt; }

    .article { margin-bottom: 14pt; page-break-inside: avoid; }
    .article-title {
        font-size: 9pt; font-weight: bold; color: #7C3AED;
        text-transform: uppercase; letter-spacing: 0.5pt;
        border-bottom: 1pt solid #DDD6FE; padding-bottom: 4pt; margin-bottom: 8pt;
    }
    .article p  { margin-bottom: 5pt; text-align: justify; }
    .article ul { padding-left: 16pt; margin: 4pt 0; }
    .article ul li { margin-bottom: 3pt; }
    .highlight-box {
        background: #F5F3FF; border-left: 3pt solid #7C3AED;
        padding: 8pt 10pt; margin: 8pt 0;
        font-style: italic; color: #4B5563; font-size: 9pt;
    }
    .divider { border: none; border-top: 1pt solid #E5E7EB; margin: 14pt 0; }

    .signature-section { margin-top: 20pt; page-break-inside: avoid; }
    .signature-title { font-size: 10pt; font-weight: bold; color: #111827; text-align: center; margin-bottom: 12pt; text-transform: uppercase; }
    .signature-table { width: 100%; border-collapse: collapse; }
    .signature-table td { width: 50%; padding: 10pt 12pt; border: 1.5pt solid #E5E7EB; vertical-align: top; }
    .sig-label  { font-size: 7.5pt; font-weight: bold; color: #6B7280; text-transform: uppercase; letter-spacing: 0.4pt; margin-bottom: 4pt; }
    .sig-value  { font-size: 9.5pt; color: #111827; font-weight: bold; }
    .sig-detail { font-size: 7.5pt; color: #9CA3AF; margin-top: 2pt; }
    .sig-stamp  { text-align: center; margin-top: 8pt; padding: 8pt; background: #F0FDF4; border: 1pt solid #86EFAC; border-radius: 4pt; }
    .sig-stamp-text { font-size: 8pt; color: #059669; font-weight: bold; }
    .sig-stamp-sub  { font-size: 7pt; color: #6B7280; margin-top: 2pt; }

    .footer {
        position: fixed; bottom: 0; left: 0; right: 0;
        text-align: center; font-size: 7.5pt; color: #9CA3AF;
        padding: 6pt; border-top: 1pt solid #E5E7EB; background: white;
    }
    .page-number:after { content: counter(page); }
</style>
</head>
<body>

<div class="footer">
    DevHub — Document confidentiel · Contrat Mentor #{{ $contrat->id }} · Page <span class="page-number"></span>
</div>

<div class="header">
    <div class="header-logo">DevHub</div>
    <div class="header-sub">Plateforme des développeurs juniors au Bénin</div>
</div>

<div class="doc-title">Contrat d'Engagement et de Confidentialité Mentor</div>
<div class="doc-sub">
    Signé le {{ $dateSignature->format('d/m/Y à H:i') }} · Ref. CM#{{ $contrat->id }}
</div>

<div class="main-content">
    <div class="party-block">
        <div class="party-label">Signataire — Mentor</div>
        <div class="party-name">{{ $mentor->prenom }} {{ $mentor->nom }}</div>
        <div class="party-email">{{ $mentor->email }}</div>
        @if($mentor->specialite)
        <div class="party-spec">Spécialité : {{ $mentor->specialite }}</div>
        @endif
    </div>

    <div class="article">
        <div class="article-title">Préambule</div>
        <p>En acceptant ce contrat, le Mentor reconnaît avoir pris connaissance de l'ensemble des règles, responsabilités et engagements liés à l'exercice de la fonction de mentor au sein de la plateforme DevHub (ci-après « la Plateforme »). Ce contrat entre en vigueur dès sa signature électronique et couvre l'intégralité des activités de mentorat du signataire sur la Plateforme.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 1 — Définitions</div>
        <p><strong>1.1</strong> « Mentor » désigne le signataire du présent contrat, titulaire d'un compte mentor actif sur la Plateforme.</p>
        <p><strong>1.2</strong> « Mentoré » désigne tout développeur junior accompagné par le Mentor dans le cadre d'une relation de mentorat individuelle ou au sein d'un projet collaboratif.</p>
        <p><strong>1.3</strong> « Informations Confidentielles » désigne toute donnée, code source, stratégie, information personnelle ou professionnelle partagée par un Mentoré ou une équipe de projet dans le cadre des activités sur la Plateforme.</p>
        <p><strong>1.4</strong> « Projet » désigne tout projet personnel ou collaboratif suivi par le Mentor sur la Plateforme.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 2 — Engagement de confidentialité</div>
        <p><strong>2.1</strong> Le Mentor s'engage à traiter comme strictement confidentielles toutes les Informations Confidentielles portées à sa connaissance dans le cadre de ses activités de mentorat, notamment :</p>
        <ul>
            <li>Les codes sources, architectures et données techniques des Projets accompagnés ;</li>
            <li>Les informations personnelles, professionnelles et financières des Mentorés ;</li>
            <li>Les échanges, conversations et contenus partagés via la messagerie de la Plateforme ;</li>
            <li>Les stratégies, idées et plans d'affaires communiqués par les Mentorés ;</li>
            <li>Les identifiants, clés API ou secrets techniques éventuellement partagés.</li>
        </ul>
        <p><strong>2.2</strong> Le Mentor s'engage à ne divulguer ces informations à aucun Tiers, y compris à d'autres Mentors, sans accord écrit préalable du Mentoré concerné.</p>
        <p><strong>2.3</strong> Ces obligations de confidentialité survivent pendant <strong>trois (3) ans</strong> après la fin de la relation de mentorat.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 3 — Engagement de disponibilité et de suivi</div>
        <p><strong>3.1</strong> Le Mentor s'engage à assurer un suivi régulier de ses Mentorés et à répondre à leurs messages dans un délai raisonnable.</p>
        <p><strong>3.2</strong> En cas d'indisponibilité prolongée, le Mentor s'engage à en informer la Plateforme afin qu'un remplaçant puisse être désigné sans préjudice pour les Mentorés.</p>
        <p><strong>3.3</strong> Le Mentor accepte la limite de <strong>trois (3) projets collaboratifs</strong> simultanés imposée par la Plateforme afin de garantir la qualité de l'accompagnement.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 4 — Intégrité, déontologie et neutralité</div>
        <p><strong>4.1</strong> Le Mentor s'engage à exercer sa mission avec impartialité, sans discrimination fondée sur l'origine, le genre, l'âge ou tout autre critère personnel.</p>
        <p><strong>4.2</strong> Le Mentor s'interdit d'utiliser sa position pour influencer les Mentorés à des fins commerciales personnelles, de recrutement ou de promotion d'intérêts concurrents.</p>
        <p><strong>4.3</strong> Le Mentor ne peut en aucun cas se substituer au développeur dans la réalisation d'un Projet. Son rôle est exclusivement celui d'un guide et d'un conseiller.</p>
        <p><strong>4.4</strong> La propriété intellectuelle des Projets accompagnés appartient intégralement aux développeurs Mentorés. Le Mentor ne revendique aucun droit sur ces œuvres.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 5 — Non-sollicitation</div>
        <p><strong>5.1</strong> Pendant toute la durée de la relation de mentorat et pour une période de <strong>douze (12) mois</strong> après sa fin, le Mentor s'engage à ne pas recruter ou solliciter commercialement ses Mentorés à des fins extérieures à la Plateforme, sans leur consentement explicite.</p>
        <p><strong>5.2</strong> Le Mentor s'engage à ne pas utiliser les contacts ou relations établis via la Plateforme pour détourner des Mentorés vers des services concurrents.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 6 — Responsabilités</div>
        <p><strong>6.1</strong> Le Mentor est responsable de la qualité de ses conseils et de leur conformité aux bonnes pratiques du secteur du développement logiciel.</p>
        <p><strong>6.2</strong> Le Mentor est responsable de tout manquement aux obligations du présent contrat et s'expose, en cas de violation, à la suspension ou à la suppression de son compte, ainsi qu'à des poursuites civiles.</p>
        <p><strong>6.3</strong> La Plateforme DevHub se réserve le droit de résilier l'accès du Mentor en cas de manquement grave ou répété aux présentes obligations.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 7 — Durée</div>
        <p>Le présent contrat entre en vigueur à la date de signature et reste valable pendant toute la durée d'activité du Mentor sur la Plateforme. Il peut être résilié à tout moment par l'une ou l'autre des parties, sous réserve du respect des obligations de confidentialité et de non-sollicitation qui survivent à la résiliation.</p>
    </div>

    <div class="article">
        <div class="article-title">Article 8 — Dispositions générales</div>
        <p>Le présent contrat constitue l'intégralité des engagements du Mentor envers la Plateforme DevHub. Si une clause est déclarée nulle, les autres restent applicables. Tout litige est soumis aux juridictions compétentes de la République du Bénin et aux textes de l'espace OHADA.</p>
    </div>

    <div class="highlight-box">
        En signant ce contrat, le Mentor reconnaît avoir lu, compris et accepté l'intégralité des présentes dispositions et s'engage à les respecter de bonne foi pendant toute la durée de sa mission sur DevHub.
    </div>

    <hr class="divider">

    <div class="signature-section">
        <div class="signature-title">Signature électronique</div>

        <table class="signature-table">
            <tr>
                <td>
                    <div class="sig-label">Signataire — Mentor</div>
                    <div class="sig-value">{{ $mentor->prenom }} {{ $mentor->nom }}</div>
                    <div class="sig-detail">{{ $mentor->email }}</div>
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
                    <div class="sig-value">CM#{{ $contrat->id }}</div>
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
