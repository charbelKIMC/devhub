@extends('layouts.public')
@section('title', 'Mentions légales')

@section('content')

<div class="page-hero">
    <div class="conteneur">
        <div class="page-hero-badge"><i class="fas fa-file-alt"></i> Légal</div>
        <div class="page-hero-title">Mentions légales</div>
        <p class="page-hero-sub">Informations légales relatives à la plateforme DevHub.</p>
    </div>
</div>

<div class="page-content">

    <div class="page-section">
        <div class="page-section-title">1. Éditeur du site</div>
        <p>
            Le site <strong>DevHub</strong> (accessible à l'adresse <em>devhub.bj</em>) est édité par l'équipe DevHub,
            plateforme collaborative dédiée aux développeurs béninois et africains.
        </p>
        <p>
            <strong>Responsable de publication :</strong> Équipe DevHub<br>
            <strong>Email de contact :</strong> 229devhub@gmail.com<br>
            <strong>Siège social :</strong> Parakou, Bénin
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">2. Hébergement</div>
        <p>
            Le site DevHub est hébergé par la société <strong>Railway</strong> (Railway Corp.),
            dont le siège social est situé aux États-Unis. L'hébergement est assuré dans le cloud
            avec une infrastructure distribuée garantissant disponibilité et sécurité des données.
        </p>
        <p>
            Pour toute question relative à l'hébergement : <a href="https://railway.app" target="_blank" style="color:var(--purple);">railway.app</a>
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">3. Propriété intellectuelle</div>
        <p>
            L'ensemble des éléments constituant le site DevHub (textes, graphismes, logotypes, icônes,
            images, sons, vidéos, logiciels, etc.) est la propriété exclusive de l'équipe DevHub
            ou de ses partenaires, et est protégé par les lois en vigueur en matière de propriété intellectuelle.
        </p>
        <p>
            Toute reproduction, représentation, modification, publication ou transmission de tout ou partie
            des éléments du site, quel que soit le moyen ou le procédé utilisé, est interdite sans l'autorisation
            préalable et écrite de l'équipe DevHub.
        </p>
        <p>
            Les contenus publiés par les utilisateurs (projets, articles, code source) restent la propriété
            de leurs auteurs respectifs. En les publiant sur DevHub, l'utilisateur accorde une licence
            non exclusive à DevHub pour les afficher sur la plateforme.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">4. Responsabilité</div>
        <p>
            L'équipe DevHub s'efforce d'assurer l'exactitude et la mise à jour des informations diffusées
            sur le site. Cependant, DevHub ne peut garantir l'exactitude, la précision ou l'exhaustivité
            des informations mises à disposition sur ce site.
        </p>
        <p>
            DevHub décline toute responsabilité pour les dommages directs ou indirects pouvant résulter
            de l'accès au site ou de l'utilisation de son contenu. Les liens hypertextes présents sur le
            site et renvoyant vers d'autres sites internet n'engagent pas la responsabilité de DevHub.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">5. Données personnelles</div>
        <p>
            Les données personnelles collectées sur DevHub font l'objet d'un traitement informatisé.
            Pour en savoir plus sur la collecte et l'utilisation de vos données, veuillez consulter notre
            <a href="{{ route('politique-confidentialite') }}" style="color:var(--purple);font-weight:600;">
                Politique de confidentialité
            </a>.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">6. Droit applicable</div>
        <p>
            Les présentes mentions légales sont soumises au droit béninois. Tout litige relatif à
            l'utilisation du site DevHub sera soumis à la compétence exclusive des tribunaux compétents
            de Parakou, Bénin.
        </p>
        <p style="color:#999;font-size:0.82rem;">Dernière mise à jour : mai 2026</p>
    </div>

</div>

@endsection
