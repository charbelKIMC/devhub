@extends('layouts.public')
@section('title', "Conditions d'utilisation")

@section('content')

<div class="page-hero">
    <div class="conteneur">
        <div class="page-hero-badge"><i class="fas fa-handshake"></i> CGU</div>
        <div class="page-hero-title">Conditions d'utilisation</div>
        <p class="page-hero-sub">Les règles qui encadrent l'utilisation de la plateforme DevHub.</p>
    </div>
</div>

<div class="page-content">

    <div class="page-section">
        <div class="page-section-title">1. Objet</div>
        <p>
            Les présentes conditions générales d'utilisation (CGU) régissent l'accès et l'utilisation
            de la plateforme DevHub, accessible à l'adresse <em>devhub.bj</em>.
            En accédant à DevHub ou en créant un compte, vous acceptez sans réserve les présentes CGU.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">2. Accès au service</div>
        <p>
            DevHub est accessible gratuitement à tout utilisateur disposant d'un accès à Internet.
            Certaines fonctionnalités (publication, messagerie, collaboration) nécessitent la création
            d'un compte et la vérification de l'adresse email.
        </p>
        <p>
            DevHub se réserve le droit de restreindre ou de suspendre l'accès au service pour maintenance,
            mise à jour ou en cas de violation des présentes CGU, sans préavis.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">3. Création de compte</div>
        <p>
            Pour accéder aux fonctionnalités complètes, l'utilisateur doit créer un compte en fournissant
            des informations exactes et à jour. Chaque utilisateur est responsable de la confidentialité
            de ses identifiants de connexion.
        </p>
        <p>
            La création de plusieurs comptes par un même utilisateur à des fins abusives est interdite.
            DevHub se réserve le droit de supprimer tout compte créé avec de fausses informations.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">4. Comportements interdits</div>
        <p>Il est formellement interdit sur DevHub de :</p>
        <ul>
            <li>Publier des contenus illégaux, diffamatoires, haineux, obscènes ou portant atteinte à autrui</li>
            <li>Usurper l'identité d'une autre personne ou organisation</li>
            <li>Diffuser des logiciels malveillants, virus ou tout code nuisible</li>
            <li>Utiliser la plateforme à des fins de spam, phishing ou démarchage non sollicité</li>
            <li>Copier, revendre ou exploiter commercialement le contenu d'autres utilisateurs sans autorisation</li>
            <li>Tenter de compromettre la sécurité de la plateforme (injection SQL, XSS, etc.)</li>
            <li>Contourner les mécanismes de modération ou de sécurité</li>
            <li>Harceler, menacer ou intimider d'autres membres de la communauté</li>
        </ul>
        <p>
            Tout comportement abusif pourra entraîner la suspension immédiate du compte concerné,
            sans préjudice des poursuites judiciaires éventuelles.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">5. Contenu utilisateur</div>
        <p>
            L'utilisateur est seul responsable des contenus qu'il publie sur DevHub (projets, articles,
            commentaires, messages). Il garantit disposer de tous les droits nécessaires sur ces contenus.
        </p>
        <p>
            En publiant sur DevHub, l'utilisateur accorde à la plateforme une licence mondiale, non exclusive
            et gratuite pour afficher, reproduire et distribuer ce contenu dans le cadre du service.
        </p>
        <p>
            DevHub se réserve le droit de supprimer tout contenu qui violerait les présentes CGU ou
            les lois en vigueur, sans notification préalable.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">6. Programme mentor</div>
        <p>
            Le programme mentor est soumis à une validation préalable par l'équipe DevHub.
            Les mentors s'engagent à respecter une éthique professionnelle et à maintenir la confidentialité
            des échanges avec les développeurs accompagnés.
        </p>
        <p>
            La signature d'un contrat de confidentialité (NDA) est requise pour accéder aux projets
            collaboratifs marqués confidentiels. Toute violation de ce contrat engage la responsabilité
            juridique du signataire.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">7. Propriété intellectuelle</div>
        <p>
            La plateforme DevHub, son code source, son design et ses fonctionnalités sont protégés
            par le droit de la propriété intellectuelle. Toute reproduction ou exploitation non autorisée
            est strictement interdite.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">8. Limitation de responsabilité</div>
        <p>
            DevHub ne peut être tenu responsable des dommages directs ou indirects résultant de l'utilisation
            ou de l'impossibilité d'utiliser le service. La plateforme est fournie "en l'état", sans garantie
            de continuité ou d'absence d'erreur.
        </p>
        <p>
            DevHub n'est pas responsable des contenus publiés par les utilisateurs ni des interactions
            entre membres de la communauté.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">9. Modification des CGU</div>
        <p>
            DevHub se réserve le droit de modifier les présentes CGU à tout moment. Les utilisateurs
            seront informés des changements significatifs par notification sur la plateforme.
            La poursuite de l'utilisation du service après modification vaut acceptation des nouvelles CGU.
        </p>
    </div>

    <div class="page-section">
        <div class="page-section-title">10. Droit applicable et juridiction</div>
        <p>
            Les présentes CGU sont soumises au droit de la République du Bénin.
            Tout litige découlant de l'utilisation de DevHub sera soumis à la compétence exclusive
            des tribunaux compétents de Cotonou, Bénin.
        </p>
        <p style="color:#999;font-size:0.82rem;">Dernière mise à jour : mai 2026</p>
    </div>

</div>

@endsection
