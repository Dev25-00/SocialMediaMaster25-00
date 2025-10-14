<?php
require_once '../config.php';
require_once '../functions.php';

$page_title = 'FAQ - Questions Fréquentes | ' . SITE_NAME;
$page_description = 'Trouvez des réponses aux questions les plus fréquentes sur SMM Mastery, nos services, paiements, et support.';

include '../includes/public-header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1><?php echo getIcon('info'); ?> Questions Fréquentes</h1>
        <p>Trouvez rapidement des réponses à vos questions</p>
    </div>
</div>

<div class="page-content">
    
    <!-- Général -->
    <div class="content-section">
        <h2><?php echo getIcon('home'); ?> Général</h2>
        
        <h3>Qu'est-ce que SMM Mastery ?</h3>
        <p>
            SMM Mastery est une plateforme de Social Media Marketing qui vous permet d'acheter des services 
            de croissance pour vos réseaux sociaux : followers, likes, vues, commentaires, et bien plus encore. 
            Nous offrons des services de haute qualité sur toutes les principales plateformes sociales.
        </p>
        
        <h3>Comment ça marche ?</h3>
        <p>C'est très simple :</p>
        <ol>
            <li><strong>Inscrivez-vous</strong> gratuitement et recevez $1 de bonus</li>
            <li><strong>Rechargez votre solde</strong> via PayPal, carte bancaire ou crypto</li>
            <li><strong>Choisissez un service</strong> parmi nos centaines d'options</li>
            <li><strong>Passez commande</strong> en quelques clics</li>
            <li><strong>Suivez votre commande</strong> en temps réel</li>
        </ol>
        
        <h3>Est-ce sûr et légal ?</h3>
        <p>
            Oui, utiliser nos services est légal. Cependant, nous recommandons de respecter les conditions 
            d'utilisation des plateformes sociales. Nous prenons la sécurité très au sérieux : tous les 
            paiements sont sécurisés, et nous ne demandons jamais vos mots de passe.
        </p>
        
        <h3>Quelles plateformes supportez-vous ?</h3>
        <p>Nous supportons toutes les principales plateformes :</p>
        <ul>
            <li>📸 Instagram (Followers, Likes, Views, Comments)</li>
            <li>🎥 YouTube (Subscribers, Views, Likes)</li>
            <li><?php echo getIcon('tiktok'); ?> TikTok (Followers, Likes, Views)</li>
            <li>📘 Facebook (Likes, Followers, Shares)</li>
            <li>🐦 Twitter (Followers, Likes, Retweets)</li>
            <li>💼 LinkedIn, Spotify, Twitch, et plus encore</li>
        </ul>
    </div>

    <!-- Services -->
    <div class="content-section">
        <h2><?php echo getIcon('services'); ?> Services et Commandes</h2>
        
        <h3>Quels sont les différents tiers de qualité ?</h3>
        <p>Nous proposons 4 niveaux de qualité pour répondre à tous les budgets :</p>
        <ul>
            <li><strong><?php echo tierBadge('budget'); ?></strong> : Services économiques pour tester (Drop possible)</li>
            <li><strong><?php echo tierBadge('standard'); ?></strong> : Meilleur rapport qualité/prix (Low Drop, 30j refill)</li>
            <li><strong><?php echo tierBadge('premium'); ?></strong> : Haute qualité avec garanties (No Drop, 90j refill)</li>
            <li><strong><?php echo tierBadge('ultimate'); ?></strong> : Excellence absolue (Garantie à vie)</li>
        </ul>
        
        <h3>Qu'est-ce que le "Drop Rate" ?</h3>
        <p>
            Le Drop Rate indique la probabilité de perte de followers/likes après livraison :
        </p>
        <ul>
            <li><strong>No Drop</strong> : Aucune perte garantie</li>
            <li><strong>Low Drop</strong> : Perte minime (5-10%)</li>
            <li><strong>High Drop</strong> : Perte possible (20-30%)</li>
        </ul>
        
        <h3>Qu'est-ce que le Refill ?</h3>
        <p>
            Le Refill est une garantie de remplacement gratuit en cas de baisse. Si vous constatez une 
            diminution pendant la période de garantie, demandez un refill et nous compenserons gratuitement.
        </p>
        
        <h3>Combien de temps prend une commande ?</h3>
        <p>Le délai varie selon le service :</p>
        <ul>
            <li><strong>Instantané</strong> : Démarrage en quelques minutes</li>
            <li><strong>Rapide</strong> : 0-6 heures</li>
            <li><strong>Standard</strong> : 6-24 heures</li>
            <li><strong>Progressif</strong> : Livraison étalée sur plusieurs jours pour plus de naturel</li>
        </ul>
        
        <h3>Puis-je annuler une commande ?</h3>
        <p>
            Oui, vous pouvez annuler une commande dans les <strong>5 premières minutes</strong> si elle 
            n'a pas encore commencé. Après ce délai, les commandes ne peuvent plus être annulées car 
            elles sont déjà en cours de traitement.
        </p>
        
        <h3>Mon compte doit-il être public ?</h3>
        <p>
            Oui, votre compte doit être <strong>public</strong> pour que nous puissions livrer la commande. 
            Si votre compte est privé, nous ne pourrons pas compléter la livraison.
        </p>
    </div>

    <!-- Paiements -->
    <div class="content-section">
        <h2><?php echo getIcon('card'); ?> Paiements et Solde</h2>
        
        <h3>Quelles méthodes de paiement acceptez-vous ?</h3>
        <p>Nous acceptons :</p>
        <ul>
            <li><?php echo getIcon('card'); ?> <strong>PayPal</strong> (Instantané)</li>
            <li><?php echo getIcon('card'); ?> <strong>Carte bancaire</strong> via Stripe (Instantané)</li>
            <li>₿ <strong>Crypto</strong> : Bitcoin, Ethereum, USDT (1-3 confirmations)</li>
            <li>🏦 <strong>Virement bancaire</strong> (Manuel, 1-3 jours)</li>
        </ul>
        
        <h3>Quel est le montant minimum de dépôt ?</h3>
        <p>
            Le dépôt minimum est de <strong>$5.00</strong>. Il n'y a pas de maximum.
        </p>
        
        <h3>Y a-t-il des bonus sur les dépôts ?</h3>
        <p>Oui ! Nous offrons des bonus automatiques :</p>
        <ul>
            <li>$10-$49 → <strong>+5%</strong></li>
            <li>$50-$99 → <strong>+8%</strong></li>
            <li>$100-$499 → <strong>+10%</strong></li>
            <li>$500+ → <strong>+15%</strong></li>
        </ul>
        
        <h3>Mon paiement est-il sécurisé ?</h3>
        <p>
            Absolument ! Tous les paiements sont traités via des passerelles sécurisées (PayPal, Stripe). 
            Nous ne stockons <strong>jamais</strong> vos informations bancaires. Connexion SSL/TLS 256-bit.
        </p>
        
        <h3>Puis-je être remboursé ?</h3>
        <p>
            Les remboursements sont possibles dans certains cas spécifiques. Consultez notre 
            <a href="refund.php" style="color: #667eea; font-weight: 600;">politique de remboursement</a> 
            pour plus de détails.
        </p>
        
        <h3>Puis-je retirer mon solde ?</h3>
        <p>
            Non, le solde n'est pas retirable. Il peut uniquement être utilisé pour commander des services 
            sur notre plateforme.
        </p>
    </div>

    <!-- Support -->
    <div class="content-section">
        <h2><?php echo getIcon('support'); ?> Support et Assistance</h2>
        
        <h3>Comment contacter le support ?</h3>
        <p>Plusieurs options s'offrent à vous :</p>
        <ul>
            <li><strong>Tickets</strong> : Créez un ticket depuis votre dashboard (réponse sous 6h)</li>
            <li><strong>Email</strong> : support@smmmaster.com</li>
            <li><strong>Chat Live</strong> : Disponible 24/7 (bientôt)</li>
        </ul>
        
        <h3>Quels sont vos horaires de support ?</h3>
        <p>
            Notre support est disponible <strong>24/7</strong>. Nous répondons généralement :
        </p>
        <ul>
            <li>Priorité haute : <strong>2 heures</strong></li>
            <li>Priorité normale : <strong>6 heures</strong></li>
            <li>Priorité basse : <strong>24 heures</strong></li>
        </ul>
        
        <h3>Comment demander un refill ?</h3>
        <p>
            Si vous constatez une baisse et que votre service inclut une garantie refill :
        </p>
        <ol>
            <li>Allez dans "Mes Commandes"</li>
            <li>Trouvez la commande concernée</li>
            <li>Cliquez sur "Demander un refill"</li>
            <li>Le refill sera traité automatiquement</li>
        </ol>
        
        <h3>Ma commande est bloquée, que faire ?</h3>
        <p>
            Si votre commande reste en "Pending" ou "Processing" trop longtemps :
        </p>
        <ol>
            <li>Vérifiez que votre compte est public</li>
            <li>Vérifiez que le lien est correct</li>
            <li>Attendez 24h (certains services prennent du temps)</li>
            <li>Si toujours bloquée, contactez le support avec votre numéro de commande</li>
        </ol>
    </div>

    <!-- Compte et Sécurité -->
    <div class="content-section">
        <h2>🔐 Compte et Sécurité</h2>
        
        <h3>Comment créer un compte ?</h3>
        <p>
            C'est gratuit et rapide :
        </p>
        <ol>
            <li>Cliquez sur "Inscription"</li>
            <li>Remplissez le formulaire (username, email, password)</li>
            <li>Confirmez votre email</li>
            <li>Recevez $1 de bonus de bienvenue !</li>
        </ol>
        
        <h3>Demandez-vous mes mots de passe de réseaux sociaux ?</h3>
        <p>
            <strong>NON, JAMAIS !</strong> Nous ne demandons QUE le lien vers votre profil/post. 
            Si quelqu'un vous demande vos identifiants, c'est une arnaque. Signalez-le immédiatement.
        </p>
        
        <h3>Comment sécuriser mon compte ?</h3>
        <p>Conseils de sécurité :</p>
        <ul>
            <li><?php echo getIcon('success'); ?> Utilisez un mot de passe fort et unique</li>
            <li><?php echo getIcon('success'); ?> Activez l'authentification 2FA (bientôt disponible)</li>
            <li><?php echo getIcon('success'); ?> Ne partagez jamais votre mot de passe</li>
            <li><?php echo getIcon('success'); ?> Vérifiez toujours l'URL : smmmaster.com</li>
            <li><?php echo getIcon('success'); ?> Déconnectez-vous après utilisation sur appareil partagé</li>
        </ul>
        
        <h3>Puis-je avoir plusieurs comptes ?</h3>
        <p>
            Non, les comptes multiples sont interdits. Un seul compte par personne. 
            Les comptes en double seront suspendus.
        </p>
    </div>

    <!-- API et Revendeurs -->
    <div class="content-section">
        <h2>🔌 API et Revendeurs</h2>
        
        <h3>Proposez-vous une API ?</h3>
        <p>
            Oui ! Notre API REST permet aux développeurs et revendeurs d'intégrer nos services 
            dans leurs propres applications. Documentation complète disponible après inscription.
        </p>
        
        <h3>Comment devenir revendeur ?</h3>
        <p>
            C'est simple :
        </p>
        <ol>
            <li>Créez un compte</li>
            <li>Allez dans "Profil" → "Accès API"</li>
            <li>Générez votre clé API</li>
            <li>Consultez la documentation</li>
            <li>Intégrez l'API dans votre site/app</li>
        </ol>
        
        <h3>Y a-t-il des frais pour l'API ?</h3>
        <p>
            Non, l'accès API est <strong>gratuit</strong> pour tous les utilisateurs. Vous payez 
            uniquement les services que vous commandez, aux mêmes prix que sur le site.
        </p>
    </div>

    <!-- Encore des questions ? -->
    <div class="content-section" style="text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h2 style="color: white; border-bottom: none;"><?php echo getIcon('info'); ?> Encore des questions ?</h2>
        <p style="color: white; opacity: 0.9; font-size: 18px; margin-bottom: 30px;">
            Notre équipe support est là pour vous aider 24/7
        </p>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="contact.php" class="btn btn-lg" style="background: white; color: #667eea;">
                <?php echo getIcon('mail'); ?> Nous Contacter
            </a>
            <a href="../support/new-ticket.php" class="btn btn-lg" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid white;">
                <?php echo getIcon('ticket'); ?> Créer un Ticket
            </a>
        </div>
    </div>

</div>

<?php include '../includes/public-footer.php'; ?>
