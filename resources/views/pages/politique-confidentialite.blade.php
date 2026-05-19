@extends('layouts.public')
@section('title', 'Politique de confidentialité')

@section('content')

<div class="page-hero">
    <div class="conteneur">
        <div class="page-hero-badge"><i class="fas fa-shield-alt"></i> Confidentialité</div>
        <div class="page-hero-title">Politique de confidentialité</div>
        <p class="page-hero-sub">Comment DevHub collecte, utilise et protège vos données personnelles.</p>
    </div>
</div>

<div class="page-content">

    <div class="page-section">
        <div class="page-section-title">1. Responsable du traitement</div>
        <p>
            Le responsable du traitement des données personnelles collectées sur DevHub est l'équipe DevHub,
            joignable à l'adresse : <strong>contact@devhub.bj</strong>
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">2. Données collectées</div>
        <p>DevHub collecte les données suivantes selon les actions réalisées :</p>
        <ul>
            <li><strong>Inscription :</strong> nom, prénom, adresse email, mot de passe (chiffré), rôle (développeur / mentor)</li>
            <li><strong>Profil :</strong> photo de profil, biographie, compétences, liens GitHub / LinkedIn / portfolio</li>
            <li><strong>Publications :</strong> titres, descriptions, médias, technologies, liens</li>
            <li><strong>Messagerie :</strong> contenu des conversations entre utilisateurs</li>
            <li><strong>Newsletter :</strong> adresse email uniquement</li>
            <li><strong>Contact :</strong> nom, email, sujet, message</li>
            <li><strong>Données techniques :</strong> adresse IP (lors de la signature des contrats NDA), logs de connexion</li>
        </ul>
    </div>

    <div class="page-section">
        <div class="page-section-title">3. Finalités du traitement</div>
        <p>Vos données sont utilisées pour :</p>
        <ul>
            <li>Créer et gérer votre compte utilisateur</li>
            <li>Afficher votre profil et vos publications à la communauté</li>
            <li>Faciliter les collaborations et la messagerie entre membres</li>
            <li>Vous mettre en relation avec un mentor ou des collaborateurs</li>
            <li>Vous envoyer la newsletter DevHub (avec votre consentement)</li>
            <li>Assurer la sécurité de la plateforme et prévenir les abus</li>
            <li>Respecter nos obligations légales</li>
        </ul>
    </div>

    <div class="page-section">
        <div class="page-section-title">4. Durée de conservation</div>
        <p>
            Vos données personnelles sont conservées pendant la durée de vie de votre compte.
            Après la suppression de votre compte, vos données sont effacées dans un délai de <strong>30 jours</strong>,
            à l'exception des données requises par la loi (données de facturation, logs légaux).
        </p>
        <p>
            Les données de la newsletter sont conservées jusqu'à désinscription.
            Les messages de contact sont conservés pendant <strong>12 mois</strong>.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">5. Partage des données</div>
        <p>
            DevHub ne vend jamais vos données personnelles à des tiers.
            Vos données peuvent être partagées uniquement dans les cas suivants :
        </p>
        <ul>
            <li>Avec notre hébergeur Railway pour le fonctionnement technique de la plateforme</li>
            <li>Sur réquisition judiciaire ou demande légale des autorités compétentes</li>
        </ul>
        <p>
            Les informations publiques de votre profil (nom, photo, compétences, publications) sont visibles
            par tous les visiteurs du site conformément à votre choix de visibilité.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">6. Vos droits</div>
        <p>Conformément aux lois applicables sur la protection des données, vous disposez des droits suivants :</p>
        <ul>
            <li><strong>Droit d'accès</strong> : consulter les données que nous détenons sur vous</li>
            <li><strong>Droit de rectification</strong> : corriger des données inexactes ou incomplètes</li>
            <li><strong>Droit à l'effacement</strong> : demander la suppression de vos données</li>
            <li><strong>Droit à la portabilité</strong> : recevoir vos données dans un format structuré</li>
            <li><strong>Droit d'opposition</strong> : vous opposer à certains traitements</li>
        </ul>
        <p>
            Pour exercer ces droits, contactez-nous à <strong>contact@devhub.bj</strong> en précisant
            votre demande. Nous nous engageons à y répondre dans un délai de <strong>30 jours</strong>.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">7. Sécurité</div>
        <p>
            DevHub met en œuvre des mesures techniques et organisationnelles appropriées pour protéger
            vos données contre tout accès non autorisé, perte, destruction ou divulgation :
            chiffrement des mots de passe (bcrypt), connexions HTTPS, accès restreints à la base de données,
            journalisation des accès sensibles.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">8. Cookies</div>
        <p>
            DevHub utilise des cookies techniques indispensables au fonctionnement de la plateforme
            (session utilisateur, token CSRF). Aucun cookie publicitaire ou de tracking tiers n'est utilisé.
        </p>
        <p>
            Vous pouvez configurer votre navigateur pour refuser les cookies, mais certaines
            fonctionnalités du site pourraient ne plus fonctionner correctement.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">9. Contact</div>
        <p>
            Pour toute question relative à cette politique ou à l'exercice de vos droits :
            <a href="{{ route('contact') }}" style="color:var(--purple);font-weight:600;">Formulaire de contact</a>
            ou par email à <strong>contact@devhub.bj</strong>
        </p>
        <p style="color:#999;font-size:0.82rem;">Dernière mise à jour : mai 2026</p>
    </div>

</div>

@endsection
