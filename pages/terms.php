<?php
require_once '../config.php';
require_once '../functions.php';

$page_title = 'CGU - Conditions Générales d\'Utilisation | ' . SITE_NAME;
$page_description = 'Conditions générales d\'utilisation de SMM Mastery. Consultez nos termes et conditions avant d\'utiliser nos services.';

include '../includes/public-header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1><?php echo getIcon('document'); ?> Conditions Générales d'Utilisation</h1>
        <p>Dernière mise à jour : <?php echo date('d F Y'); ?></p>
    </div>
</div>

<div class="page-content">
    
    <div class="highlight-box">
        <strong><?php echo getIcon('info'); ?> Important :</strong> En utilisant SMM Mastery, vous acceptez ces conditions générales d'utilisation. 
        Veuillez les lire attentivement. Si vous n'acceptez pas ces conditions, n'utilisez pas nos services.
    </div>

    <!-- 1. Acceptation -->
    <div class="content-section">
        <h2>1. Acceptation des Conditions</h2>
        <p>
            En accédant et en utilisant SMM Mastery (ci-après "le Service", "nous", "notre"), vous acceptez 
            d'être lié par ces Conditions Générales d'Utilisation. Ces conditions s'appliquent à tous les 
            visiteurs, utilisateurs et autres personnes qui accèdent ou utilisent le Service.
        </p>
        <p>
            Nous nous réservons le droit de modifier ces conditions à tout moment. Votre utilisation continue 
            du Service après de tels changements constitue votre acceptation des nouvelles conditions.
        </p>
    </div>

    <!-- 2. Description du Service -->
    <div class="content-section">
        <h2>2. Description du Service</h2>
        <p>
            SMM Mastery est une plateforme de Social Media Marketing qui propose des services de croissance 
            sur les réseaux sociaux, incluant mais sans s'y limiter :
        </p>
        <ul>
            <li>Followers / Abonnés</li>
            <li>Likes / J'aime</li>
            <li>Vues / Views</li>
            <li>Commentaires</li>
            <li>Partages / Shares</li>
            <li>Et autres services SMM</li>
        </ul>
        <p>
            Nous agissons en tant qu'intermédiaire entre vous et nos fournisseurs de services tiers. 
            Nous ne garantissons pas l'origine exacte des services fournis.
        </p>
    </div>

    <!-- 3. Inscription et Compte -->
    <div class="content-section">
        <h2>3. Inscription et Compte Utilisateur</h2>
        
        <h3>3.1 Éligibilité</h3>
        <p>
            Pour utiliser nos services, vous devez :
        </p>
        <ul>
            <li>Avoir au moins 18 ans ou l'âge de la majorité dans votre juridiction</li>
            <li>Fournir des informations exactes et à jour lors de l'inscription</li>
            <li>Maintenir la sécurité de votre compte et mot de passe</li>
            <li>Ne pas utiliser le Service si vous avez été précédemment banni</li>
        </ul>
        
        <h3>3.2 Responsabilité du Compte</h3>
        <p>
            Vous êtes responsable de toutes les activités qui se produisent sous votre compte. 
            Vous acceptez de :
        </p>
        <ul>
            <li>Garder votre mot de passe confidentiel</li>
            <li>Ne pas partager votre compte avec d'autres</li>
            <li>Nous notifier immédiatement en cas d'utilisation non autorisée</li>
            <li>N'avoir qu'un seul compte (les comptes multiples sont interdits)</li>
        </ul>
        
        <h3>3.3 Résiliation de Compte</h3>
        <p>
            Nous nous réservons le droit de suspendre ou résilier votre compte à tout moment si :
        </p>
        <ul>
            <li>Vous violez ces conditions d'utilisation</li>
            <li>Vous utilisez le Service de manière frauduleuse</li>
            <li>Vous créez plusieurs comptes</li>
            <li>Vous tentez de nuire au Service ou à d'autres utilisateurs</li>
        </ul>
    </div>

    <!-- 4. Utilisation du Service -->
    <div class="content-section">
        <h2>4. Utilisation Acceptable du Service</h2>
        
        <h3>4.1 Utilisations Autorisées</h3>
        <p>
            Vous pouvez utiliser nos services uniquement pour :
        </p>
        <ul>
            <li>Vos propres comptes de réseaux sociaux</li>
            <li>Des comptes pour lesquels vous avez l'autorisation explicite</li>
            <li>Des objectifs légitimes de marketing et de croissance</li>
        </ul>
        
        <h3>4.2 Utilisations Interdites</h3>
        <p>
            Vous vous engagez à NE PAS :
        </p>
        <ul>
            <li><?php echo getIcon('error'); ?> Utiliser le Service pour des activités illégales</li>
            <li><?php echo getIcon('error'); ?> Commander des services pour des comptes que vous ne possédez pas sans autorisation</li>
            <li><?php echo getIcon('error'); ?> Harceler, menacer ou diffamer autrui</li>
            <li><?php echo getIcon('error'); ?> Publier du contenu offensant, violent, pornographique ou illégal</li>
            <li><?php echo getIcon('error'); ?> Tenter de pirater, perturber ou surcharger nos systèmes</li>
            <li><?php echo getIcon('error'); ?> Utiliser des bots ou scripts automatisés contre le Service</li>
            <li><?php echo getIcon('error'); ?> Revendre nos services sans autorisation (sauf via API)</li>
            <li><?php echo getIcon('error'); ?> Créer de faux comptes ou fournir de fausses informations</li>
        </ul>
    </div>

    <!-- 5. Commandes et Paiements -->
    <div class="content-section">
        <h2>5. Commandes et Paiements</h2>
        
        <h3>5.1 Passation de Commandes</h3>
        <p>
            Lorsque vous passez une commande :
        </p>
        <ul>
            <li>Vous acceptez de payer le prix indiqué</li>
            <li>Le montant est immédiatement débité de votre solde</li>
            <li>Les commandes ne peuvent généralement pas être annulées une fois commencées</li>
            <li>Vous devez fournir un lien valide et accessible publiquement</li>
        </ul>
        
        <h3>5.2 Paiements</h3>
        <p>
            Concernant les paiements :
        </p>
        <ul>
            <li>Le dépôt minimum est de $5.00</li>
            <li>Les paiements sont non remboursables sauf exceptions (voir Politique de Remboursement)</li>
            <li>Le solde ne peut pas être retiré, uniquement utilisé pour des services</li>
            <li>Nous acceptons PayPal, cartes bancaires, et crypto-monnaies</li>
            <li>Tous les prix sont en USD</li>
        </ul>
        
        <h3>5.3 Garanties et Refill</h3>
        <p>
            Certains services incluent une garantie "Refill" :
        </p>
        <ul>
            <li>Le refill compense les baisses pendant la période garantie</li>
            <li>Vous devez demander le refill pendant la période de garantie</li>
            <li>Le refill n'est accordé qu'une seule fois par commande</li>
            <li>Les services "No Refill" n'incluent aucune garantie</li>
        </ul>
    </div>

    <!-- 6. Livraison et Délais -->
    <div class="content-section">
        <h2>6. Livraison et Temps de Traitement</h2>
        <p>
            Nous faisons de notre mieux pour livrer rapidement, cependant :
        </p>
        <ul>
            <li>Les délais de livraison sont <strong>estimatifs</strong> et non garantis</li>
            <li>Les délais peuvent varier selon le service et la demande</li>
            <li>Certains services prennent plusieurs jours (livraison progressive)</li>
            <li>Nous ne sommes pas responsables des retards causés par des fournisseurs tiers</li>
            <li>Les commandes peuvent être livrées partiellement si le service n'est plus disponible</li>
        </ul>
    </div>

    <!-- 7. Propriété Intellectuelle -->
    <div class="content-section">
        <h2>7. Propriété Intellectuelle</h2>
        <p>
            Tout le contenu de SMM Mastery, incluant mais sans s'y limiter :
        </p>
        <ul>
            <li>Le logo, le nom, et l'image de marque</li>
            <li>Le design et l'interface du site</li>
            <li>Les textes, images, et graphiques</li>
            <li>Le code source et la structure du site</li>
        </ul>
        <p>
            ...sont la propriété exclusive de SMM Mastery ou de ses concédants de licence et sont protégés 
            par les lois sur le droit d'auteur et la propriété intellectuelle.
        </p>
        <p>
            Vous n'êtes <strong>pas autorisé</strong> à copier, modifier, distribuer ou revendre 
            notre contenu sans autorisation écrite explicite.
        </p>
    </div>

    <!-- 8. Limitation de Responsabilité -->
    <div class="content-section">
        <h2>8. Limitation de Responsabilité</h2>
        
        <div class="warning-box">
            <strong><?php echo getIcon('warning'); ?> Important :</strong> Veuillez lire attentivement cette section.
        </div>
        
        <h3>8.1 Service "Tel Quel"</h3>
        <p>
            Le Service est fourni "TEL QUEL" et "TEL QUE DISPONIBLE", sans garantie d'aucune sorte, 
            expresse ou implicite. Nous ne garantissons pas :
        </p>
        <ul>
            <li>La disponibilité ininterrompue du Service</li>
            <li>L'absence d'erreurs ou de bugs</li>
            <li>La qualité exacte des services fournis</li>
            <li>Les résultats spécifiques de l'utilisation des services</li>
        </ul>
        
        <h3>8.2 Limitation des Dommages</h3>
        <p>
            EN AUCUN CAS, SMM Mastery, SES DIRECTEURS, EMPLOYÉS OU AGENTS NE SERONT RESPONSABLES DE :
        </p>
        <ul>
            <li>Dommages indirects, accessoires, spéciaux ou consécutifs</li>
            <li>Perte de profits, revenus, données ou utilisation</li>
            <li>Dommages résultant de l'utilisation ou de l'impossibilité d'utiliser le Service</li>
            <li>Suspension ou bannissement de vos comptes sociaux</li>
            <li>Actions de plateformes tierces contre vous</li>
        </ul>
        <p>
            Notre responsabilité totale ne dépassera pas le montant que vous avez payé au cours 
            des 3 derniers mois.
        </p>
        
        <h3>8.3 Responsabilité de l'Utilisateur</h3>
        <p>
            Vous reconnaissez et acceptez que :
        </p>
        <ul>
            <li>Vous utilisez le Service à vos propres risques</li>
            <li>Vous êtes seul responsable de toute violation des TOS des plateformes sociales</li>
            <li>Nous ne sommes pas responsables si vos comptes sont suspendus ou bannis</li>
            <li>Vous devez respecter les règles de chaque plateforme sociale</li>
        </ul>
    </div>

    <!-- 9. Confidentialité -->
    <div class="content-section">
        <h2>9. Confidentialité et Données Personnelles</h2>
        <p>
            Votre vie privée est importante pour nous. L'utilisation de vos données personnelles est 
            régie par notre <a href="privacy.php" style="color: #667eea; font-weight: 600;">Politique de Confidentialité</a>.
        </p>
        <p>
            Points clés :
        </p>
        <ul>
            <li>Nous collectons uniquement les données nécessaires</li>
            <li>Nous ne vendons jamais vos données à des tiers</li>
            <li>Vos données sont stockées de manière sécurisée</li>
            <li>Vous pouvez demander la suppression de vos données</li>
        </ul>
    </div>

    <!-- 10. Modifications du Service -->
    <div class="content-section">
        <h2>10. Modifications du Service</h2>
        <p>
            Nous nous réservons le droit de :
        </p>
        <ul>
            <li>Modifier, suspendre ou arrêter tout ou partie du Service à tout moment</li>
            <li>Changer les prix de nos services</li>
            <li>Ajouter ou retirer des fonctionnalités</li>
            <li>Modifier ces conditions d'utilisation</li>
        </ul>
        <p>
            Nous tenterons de vous notifier des changements majeurs, mais nous ne sommes pas obligés de le faire.
        </p>
    </div>

    <!-- 11. Résolution des Litiges -->
    <div class="content-section">
        <h2>11. Résolution des Litiges</h2>
        
        <h3>11.1 Support Client</h3>
        <p>
            En cas de problème, contactez d'abord notre support client. La plupart des problèmes peuvent 
            être résolus rapidement et à l'amiable.
        </p>
        
        <h3>11.2 Loi Applicable</h3>
        <p>
            Ces conditions sont régies par les lois de [VOTRE JURIDICTION]. Tout litige sera soumis 
            à la juridiction exclusive des tribunaux de [VOTRE VILLE/PAYS].
        </p>
    </div>

    <!-- 12. Divers -->
    <div class="content-section">
        <h2>12. Dispositions Diverses</h2>
        
        <h3>12.1 Intégralité de l'Accord</h3>
        <p>
            Ces conditions constituent l'intégralité de l'accord entre vous et SMM Mastery concernant 
            l'utilisation du Service.
        </p>
        
        <h3>12.2 Divisibilité</h3>
        <p>
            Si une disposition de ces conditions est jugée invalide, les autres dispositions resteront 
            pleinement applicables.
        </p>
        
        <h3>12.3 Renonciation</h3>
        <p>
            L'absence d'exercice d'un droit ne constitue pas une renonciation à ce droit.
        </p>
        
        <h3>12.4 Contact</h3>
        <p>
            Pour toute question concernant ces conditions, contactez-nous :
        </p>
        <ul>
            <li><?php echo getIcon('mail'); ?> Email : legal@smmmaster.com</li>
            <li><?php echo getIcon('support'); ?> Support : <a href="../support/new-ticket.php" style="color: #667eea;">Créer un ticket</a></li>
            <li><?php echo getIcon('document'); ?> Adresse : [VOTRE ADRESSE LÉGALE]</li>
        </ul>
    </div>

    <!-- Footer de page -->
    <div class="content-section" style="background: #f9fafb; border-left: 4px solid #667eea;">
        <p style="margin: 0; color: #6b7280;">
            <strong>Dernière mise à jour :</strong> <?php echo date('d F Y'); ?><br>
            <strong>Version :</strong> 1.0<br>
            <strong>Effectif depuis :</strong> <?php echo date('d F Y'); ?>
        </p>
    </div>

    <!-- CTA -->
    <div class="content-section" style="text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h2 style="color: white; border-bottom: none;"><?php echo getIcon('success'); ?> Vous acceptez ces conditions ?</h2>
        <p style="color: white; opacity: 0.9; font-size: 18px; margin-bottom: 30px;">
            Créez votre compte gratuitement et commencez à faire grandir votre présence sociale !
        </p>
        <a href="../auth/register.php" class="btn btn-lg" style="background: white; color: #667eea;">
            <?php echo getIcon('rocket', true); ?> Créer un Compte Gratuit
        </a>
    </div>

</div>

<?php include '../includes/public-footer.php'; ?>
