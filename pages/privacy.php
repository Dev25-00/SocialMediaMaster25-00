<?php
require_once '../config.php';
require_once '../functions.php';

$page_title = 'Politique de Confidentialité | ' . SITE_NAME;
$page_description = 'Découvrez comment SMM Mastery collecte, utilise et protège vos données personnelles. Conformité RGPD.';

include '../includes/public-header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1><?php echo getIcon('lock'); ?> Politique de Confidentialité</h1>
        <p>Dernière mise à jour : <?php echo date('d F Y'); ?></p>
    </div>
</div>

<div class="page-content">
    
    <div class="highlight-box">
        <strong><?php echo getIcon('shield'); ?> Votre vie privée est importante</strong> : Chez SMM Mastery, nous prenons très au sérieux 
        la protection de vos données personnelles. Cette politique explique comment nous collectons, utilisons 
        et protégeons vos informations.
    </div>

    <!-- 1. Introduction -->
    <div class="content-section">
        <h2>1. Introduction</h2>
        <p>
            SMM Mastery ("nous", "notre", "nos") s'engage à protéger votre vie privée. Cette Politique de 
            Confidentialité explique comment nous collectons, utilisons, partageons et protégeons vos 
            informations personnelles lorsque vous utilisez notre site web et nos services.
        </p>
        <p>
            En utilisant SMM Mastery, vous acceptez les pratiques décrites dans cette politique. Si vous n'acceptez 
            pas cette politique, veuillez ne pas utiliser nos services.
        </p>
    </div>

    <!-- 2. Informations Collectées -->
    <div class="content-section">
        <h2>2. Informations que Nous Collectons</h2>
        
        <h3>2.1 Informations que Vous Nous Fournissez</h3>
        <p>
            Nous collectons les informations que vous nous fournissez directement :
        </p>
        <ul>
            <li><strong>Lors de l'inscription :</strong>
                <ul>
                    <li>Nom d'utilisateur</li>
                    <li>Adresse email</li>
                    <li>Mot de passe (stocké crypté avec bcrypt)</li>
                </ul>
            </li>
            <li><strong>Lors de l'utilisation du service :</strong>
                <ul>
                    <li>Liens de réseaux sociaux (pour livrer les commandes)</li>
                    <li>Historique des commandes</li>
                    <li>Messages de support</li>
                    <li>Préférences de notification</li>
                </ul>
            </li>
            <li><strong>Informations de paiement :</strong>
                <ul>
                    <li>Montant des transactions</li>
                    <li>Méthode de paiement utilisée</li>
                    <li><?php echo getIcon('warning'); ?> <strong>IMPORTANT</strong> : Nous ne stockons JAMAIS vos données bancaires complètes</li>
                </ul>
            </li>
        </ul>
        
        <h3>2.2 Informations Collectées Automatiquement</h3>
        <p>
            Lorsque vous utilisez notre service, nous collectons automatiquement :
        </p>
        <ul>
            <li><strong>Données de connexion :</strong>
                <ul>
                    <li>Adresse IP</li>
                    <li>Type de navigateur et version</li>
                    <li>Système d'exploitation</li>
                    <li>Pages visitées et temps passé</li>
                    <li>Date et heure des visites</li>
                </ul>
            </li>
            <li><strong>Cookies et technologies similaires :</strong>
                <ul>
                    <li>Cookies de session (pour vous garder connecté)</li>
                    <li>Cookies de préférence (pour mémoriser vos choix)</li>
                    <li>Cookies analytiques (pour améliorer notre service)</li>
                </ul>
            </li>
        </ul>
        
        <h3>2.3 Informations que Nous NE Collectons PAS</h3>
        <p>
            Pour votre sécurité, nous ne collectons <strong>JAMAIS</strong> :
        </p>
        <ul>
            <li><?php echo getIcon('error'); ?> Vos mots de passe de réseaux sociaux</li>
            <li><?php echo getIcon('error'); ?> Vos numéros de carte bancaire complets</li>
            <li><?php echo getIcon('error'); ?> Vos informations bancaires sensibles</li>
            <li><?php echo getIcon('error'); ?> Données biométriques</li>
        </ul>
    </div>

    <!-- 3. Utilisation des Données -->
    <div class="content-section">
        <h2>3. Comment Nous Utilisons Vos Données</h2>
        <p>
            Nous utilisons vos informations pour :
        </p>
        
        <h3>3.1 Fournir le Service</h3>
        <ul>
            <li><?php echo getIcon('success'); ?> Créer et gérer votre compte</li>
            <li><?php echo getIcon('success'); ?> Traiter vos commandes</li>
            <li><?php echo getIcon('success'); ?> Gérer votre solde et transactions</li>
            <li><?php echo getIcon('success'); ?> Fournir un support client</li>
            <li><?php echo getIcon('success'); ?> Communiquer avec vous sur vos commandes</li>
        </ul>
        
        <h3>3.2 Améliorer le Service</h3>
        <ul>
            <li><?php echo getIcon('stats'); ?> Analyser l'utilisation du site</li>
            <li><?php echo getIcon('stats'); ?> Identifier et corriger les bugs</li>
            <li><?php echo getIcon('stats'); ?> Développer de nouvelles fonctionnalités</li>
            <li><?php echo getIcon('stats'); ?> Optimiser l'expérience utilisateur</li>
        </ul>
        
        <h3>3.3 Sécurité et Conformité</h3>
        <ul>
            <li><?php echo getIcon('lock'); ?> Détecter et prévenir la fraude</li>
            <li><?php echo getIcon('lock'); ?> Protéger contre les accès non autorisés</li>
            <li><?php echo getIcon('lock'); ?> Respecter les obligations légales</li>
            <li><?php echo getIcon('lock'); ?> Résoudre les litiges</li>
        </ul>
        
        <h3>3.4 Marketing (avec votre consentement)</h3>
        <ul>
            <li><?php echo getIcon('mail'); ?> Envoyer des newsletters (vous pouvez vous désabonner)</li>
            <li><?php echo getIcon('mail'); ?> Informer sur les promotions et nouveautés</li>
            <li><?php echo getIcon('mail'); ?> Envoyer des offres personnalisées</li>
        </ul>
    </div>

    <!-- 4. Partage des Données -->
    <div class="content-section">
        <h2>4. Partage de Vos Informations</h2>
        
        <div class="warning-box">
            <strong>🔐 Promesse importante :</strong> Nous ne vendons JAMAIS vos données personnelles à des tiers.
        </div>
        
        <h3>4.1 Fournisseurs de Services</h3>
        <p>
            Nous partageons certaines données avec des partenaires de confiance qui nous aident à opérer :
        </p>
        <ul>
            <li><strong>Fournisseurs SMM</strong> : Pour livrer vos commandes (seulement les liens nécessaires)</li>
            <li><strong>Processeurs de paiement</strong> : PayPal, Stripe (pour traiter les paiements sécurisés)</li>
            <li><strong>Hébergement</strong> : Serveurs web sécurisés</li>
            <li><strong>Email</strong> : Service d'envoi d'emails (notifications, support)</li>
        </ul>
        <p>
            Ces partenaires sont contractuellement tenus de protéger vos données et ne peuvent les utiliser 
            qu'aux fins spécifiées.
        </p>
        
        <h3>4.2 Obligations Légales</h3>
        <p>
            Nous pouvons divulguer vos informations si requis par la loi ou dans le cadre de :
        </p>
        <ul>
            <li>Ordonnances judiciaires ou assignations</li>
            <li>Enquêtes gouvernementales</li>
            <li>Protection de nos droits légaux</li>
            <li>Prévention de fraude ou d'activités illégales</li>
        </ul>
        
        <h3>4.3 Transfert d'Entreprise</h3>
        <p>
            En cas de fusion, acquisition ou vente de notre entreprise, vos données pourraient être 
            transférées. Vous seriez notifié de tout changement.
        </p>
    </div>

    <!-- 5. Sécurité des Données -->
    <div class="content-section">
        <h2>5. Sécurité de Vos Données</h2>
        <p>
            Nous prenons la sécurité très au sérieux et mettons en place des mesures robustes :
        </p>
        
        <h3>5.1 Mesures Techniques</h3>
        <ul>
            <li>🔐 <strong>Cryptage SSL/TLS</strong> : Toutes les communications sont cryptées</li>
            <li>🔐 <strong>Hashing des mots de passe</strong> : bcrypt avec coût élevé</li>
            <li>🔐 <strong>Pare-feu et protection DDoS</strong></li>
            <li>🔐 <strong>Surveillance 24/7</strong> : Détection d'intrusions</li>
            <li>🔐 <strong>Backups réguliers</strong> : Vos données sont sauvegardées</li>
            <li>🔐 <strong>Accès limité</strong> : Seul le personnel autorisé accède aux données</li>
        </ul>
        
        <h3>5.2 Mesures Organisationnelles</h3>
        <ul>
            <li>Formation du personnel sur la sécurité</li>
            <li>Audits de sécurité réguliers</li>
            <li>Politiques strictes d'accès aux données</li>
            <li>Tests de pénétration périodiques</li>
        </ul>
        
        <div class="warning-box">
            <strong><?php echo getIcon('warning'); ?> Important :</strong> Aucun système n'est 100% sûr. Bien que nous fassions tout notre possible, 
            nous ne pouvons garantir une sécurité absolue. Protégez votre mot de passe et signalez toute 
            activité suspecte immédiatement.
        </div>
    </div>

    <!-- 6. Vos Droits (RGPD) -->
    <div class="content-section">
        <h2>6. Vos Droits sur Vos Données (RGPD)</h2>
        <p>
            Conformément au RGPD (Règlement Général sur la Protection des Données), vous avez les droits suivants :
        </p>
        
        <h3>6.1 Droit d'Accès</h3>
        <p>
            <?php echo getIcon('success'); ?> Vous pouvez demander une copie de toutes les données que nous détenons sur vous.
        </p>
        
        <h3>6.2 Droit de Rectification</h3>
        <p>
            <?php echo getIcon('success'); ?> Vous pouvez corriger des informations inexactes ou incomplètes depuis votre profil ou en nous contactant.
        </p>
        
        <h3>6.3 Droit à l'Effacement ("Droit à l'oubli")</h3>
        <p>
            <?php echo getIcon('success'); ?> Vous pouvez demander la suppression de vos données. Nous les supprimerons sauf si nous sommes 
            légalement tenus de les conserver (ex: pour la comptabilité).
        </p>
        
        <h3>6.4 Droit à la Portabilité</h3>
        <p>
            <?php echo getIcon('success'); ?> Vous pouvez demander un export de vos données dans un format structuré (CSV, JSON).
        </p>
        
        <h3>6.5 Droit d'Opposition</h3>
        <p>
            <?php echo getIcon('success'); ?> Vous pouvez vous opposer au traitement de vos données à des fins de marketing direct.
        </p>
        
        <h3>6.6 Droit de Limitation</h3>
        <p>
            <?php echo getIcon('success'); ?> Vous pouvez demander la limitation du traitement de vos données dans certains cas.
        </p>
        
        <h3>6.7 Comment Exercer Vos Droits ?</h3>
        <p>
            Pour exercer l'un de ces droits, contactez-nous à :
        </p>
        <ul>
            <li><?php echo getIcon('mail'); ?> <strong>Email</strong> : privacy@smmmaster.com</li>
            <li><?php echo getIcon('support'); ?> <strong>Ticket Support</strong> : <a href="../support/new-ticket.php" style="color: #667eea;">Créer un ticket</a></li>
        </ul>
        <p>
            Nous répondrons dans un délai de <strong>30 jours</strong> maximum.
        </p>
    </div>

    <!-- 7. Cookies -->
    <div class="content-section">
        <h2>7. Cookies et Technologies Similaires</h2>
        
        <h3>7.1 Qu'est-ce qu'un Cookie ?</h3>
        <p>
            Un cookie est un petit fichier texte stocké sur votre appareil qui nous permet de vous 
            reconnaître et d'améliorer votre expérience.
        </p>
        
        <h3>7.2 Types de Cookies que Nous Utilisons</h3>
        <ul>
            <li><strong>Cookies Essentiels</strong> (obligatoires) :
                <ul>
                    <li>Session de connexion</li>
                    <li>Sécurité (CSRF protection)</li>
                    <li>Préférences de langue</li>
                </ul>
            </li>
            <li><strong>Cookies Fonctionnels</strong> :
                <ul>
                    <li>Mémorisation de vos choix</li>
                    <li>"Remember Me"</li>
                </ul>
            </li>
            <li><strong>Cookies Analytiques</strong> (avec consentement) :
                <ul>
                    <li>Google Analytics (anonymisé)</li>
                    <li>Statistiques d'utilisation</li>
                </ul>
            </li>
        </ul>
        
        <h3>7.3 Gestion des Cookies</h3>
        <p>
            Vous pouvez gérer ou supprimer les cookies via les paramètres de votre navigateur. 
            Notez que désactiver les cookies essentiels peut affecter le fonctionnement du site.
        </p>
    </div>

    <!-- 8. Conservation des Données -->
    <div class="content-section">
        <h2>8. Durée de Conservation des Données</h2>
        <p>
            Nous conservons vos données aussi longtemps que nécessaire :
        </p>
        <ul>
            <li><strong>Compte actif</strong> : Tant que vous utilisez le service</li>
            <li><strong>Données transactionnelles</strong> : 10 ans (obligation légale comptable)</li>
            <li><strong>Logs de sécurité</strong> : 90 jours</li>
            <li><strong>Compte inactif</strong> : Suppression après 3 ans d'inactivité (avec notification préalable)</li>
            <li><strong>Suppression demandée</strong> : 30 jours maximum</li>
        </ul>
    </div>

    <!-- 9. Transferts Internationaux -->
    <div class="content-section">
        <h2>9. Transferts Internationaux de Données</h2>
        <p>
            Vos données peuvent être stockées et traitées dans différents pays. Nous nous assurons que 
            tous les transferts de données respectent les normes RGPD et autres réglementations applicables.
        </p>
        <p>
            Nos serveurs principaux sont situés en : <strong>[LOCALISATION DE VOS SERVEURS]</strong>
        </p>
    </div>

    <!-- 10. Mineurs -->
    <div class="content-section">
        <h2>10. Protection des Mineurs</h2>
        <p>
            Notre service n'est <strong>pas destiné aux personnes de moins de 18 ans</strong>. 
            Nous ne collectons pas sciemment de données de mineurs.
        </p>
        <p>
            Si vous êtes parent et que vous découvrez que votre enfant nous a fourni des informations, 
            contactez-nous immédiatement pour que nous puissions supprimer ces données.
        </p>
    </div>

    <!-- 11. Modifications -->
    <div class="content-section">
        <h2>11. Modifications de cette Politique</h2>
        <p>
            Nous pouvons mettre à jour cette politique de temps en temps. En cas de changements importants, 
            nous vous notifierons par :
        </p>
        <ul>
            <li><?php echo getIcon('mail'); ?> Email à votre adresse enregistrée</li>
            <li><?php echo getIcon('bell'); ?> Notification sur le site</li>
            <li><?php echo getIcon('phone'); ?> Message dans votre dashboard</li>
        </ul>
        <p>
            La date de "Dernière mise à jour" en haut de cette page indique quand la politique a été 
            modifiée pour la dernière fois.
        </p>
    </div>

    <!-- 12. Contact -->
    <div class="content-section">
        <h2>12. Nous Contacter</h2>
        <p>
            Pour toute question concernant cette politique de confidentialité ou vos données personnelles :
        </p>
        <ul>
            <li><?php echo getIcon('mail'); ?> <strong>Email Confidentialité</strong> : privacy@smmmaster.com</li>
            <li><?php echo getIcon('mail'); ?> <strong>DPO (Délégué à la Protection des Données)</strong> : dpo@smmmaster.com</li>
            <li><?php echo getIcon('support'); ?> <strong>Support</strong> : <a href="../support/new-ticket.php" style="color: #667eea;">Créer un ticket</a></li>
            <li><?php echo getIcon('document'); ?> <strong>Adresse postale</strong> : [VOTRE ADRESSE LÉGALE]</li>
        </ul>
    </div>

    <!-- Footer de page -->
    <div class="content-section" style="background: #f9fafb; border-left: 4px solid #10b981;">
        <p style="margin: 0; color: #6b7280;">
            <strong><?php echo getIcon('success'); ?> Conformité RGPD</strong><br>
            SMM Mastery est conforme au Règlement Général sur la Protection des Données (RGPD) et s'engage 
            à protéger votre vie privée conformément aux meilleures pratiques internationales.
        </p>
    </div>

    <!-- CTA -->
    <div class="content-section" style="text-align: center; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
        <h2 style="color: white; border-bottom: none;"><?php echo getIcon('lock'); ?> Vos données sont en sécurité</h2>
        <p style="color: white; opacity: 0.9; font-size: 18px; margin-bottom: 30px;">
            Rejoignez des milliers d'utilisateurs qui nous font confiance
        </p>
        <a href="../auth/register.php" class="btn btn-lg" style="background: white; color: #10b981;">
            <?php echo getIcon('rocket', true); ?> Créer un Compte Sécurisé
        </a>
    </div>

</div>

<?php include '../includes/public-footer.php'; ?>
