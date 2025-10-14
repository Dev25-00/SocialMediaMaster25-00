<?php
require_once '../config.php';
require_once '../functions.php';

$page_title = 'À Propos de ' . SITE_NAME;
$page_description = 'Découvrez SMM Mastery, votre partenaire de confiance pour la croissance sur les réseaux sociaux.';

include '../includes/public-header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1><?php echo getIcon('rocket', true); ?> À Propos de SMM Mastery</h1>
        <p>Votre partenaire pour une croissance sociale authentique</p>
    </div>
</div>

<div class="page-content">
    
    <!-- Notre Mission -->
    <div class="content-section">
        <h2>🎯 Notre Mission</h2>
        <p style="font-size: 18px; line-height: 1.8;">
            Chez <strong>SMM Mastery</strong>, notre mission est de <strong>démocratiser l'accès aux services 
            de Social Media Marketing</strong> en offrant des solutions de croissance abordables, transparentes 
            et de haute qualité pour tous, des créateurs individuels aux grandes entreprises.
        </p>
        <p style="font-size: 18px; line-height: 1.8;">
            Nous croyons que <strong>chacun mérite une chance égale</strong> de réussir sur les réseaux sociaux, 
            quelle que soit la taille de son budget marketing.
        </p>
    </div>

    <!-- Notre Histoire -->
    <div class="content-section">
        <h2>📖 Notre Histoire</h2>
        <p>
            SMM Mastery a été fondé en <strong>2025</strong> par une équipe de passionnés de marketing digital 
            et de technologie. Frustrés par les prix exorbitants et le manque de transparence de l'industrie 
            SMM, nous avons décidé de créer une plateforme différente.
        </p>
        
        <h3>Le Constat</h3>
        <p>
            Nous avons observé que :
        </p>
        <ul>
            <li>🔴 Les services SMM étaient <strong>trop chers</strong> pour la plupart des créateurs</li>
            <li>🔴 La qualité était <strong>imprévisible</strong> et mal documentée</li>
            <li>🔴 Le support client était <strong>inexistant</strong> ou très lent</li>
            <li>🔴 Les plateformes manquaient de <strong>transparence</strong> sur leurs services</li>
        </ul>
        
        <h3>Notre Solution</h3>
        <p>
            Nous avons créé SMM Mastery avec ces principes fondateurs :
        </p>
        <ul>
            <li><?php echo getIcon('success'); ?> <strong>Transparence totale</strong> : Drop rates clairement indiqués</li>
            <li><?php echo getIcon('success'); ?> <strong>Prix justes</strong> : Marges raisonnables, pas de surfacturation</li>
            <li><?php echo getIcon('success'); ?> <strong>Qualité graduelle</strong> : 4 tiers pour tous les budgets</li>
            <li><?php echo getIcon('success'); ?> <strong>Support réactif</strong> : Réponse sous 6h en moyenne</li>
            <li><?php echo getIcon('success'); ?> <strong>Technologie moderne</strong> : Interface intuitive et responsive</li>
        </ul>
    </div>

    <!-- Nos Valeurs -->
    <div class="content-section">
        <h2><?php echo getIcon('premium', true); ?> Nos Valeurs</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-top: 30px;">
            <div style="text-align: center; padding: 30px; background: #f9fafb; border-radius: 12px;">
                <div style="font-size: 48px; margin-bottom: 15px;"><?php echo getIcon('search'); ?></div>
                <h3 style="margin-bottom: 10px;">Transparence</h3>
                <p style="color: #6b7280;">
                    Nous croyons en l'honnêteté totale. Chaque service affiche clairement ses 
                    caractéristiques, avantages et limitations.
                </p>
            </div>
            
            <div style="text-align: center; padding: 30px; background: #f9fafb; border-radius: 12px;">
                <div style="font-size: 48px; margin-bottom: 15px;"><?php echo getIcon('bolt'); ?></div>
                <h3 style="margin-bottom: 10px;">Rapidité</h3>
                <p style="color: #6b7280;">
                    Nous valorisons votre temps. Livraison rapide des services, support réactif, 
                    interface fluide.
                </p>
            </div>
            
            <div style="text-align: center; padding: 30px; background: #f9fafb; border-radius: 12px;">
                <div style="font-size: 48px; margin-bottom: 15px;">🎯</div>
                <h3 style="margin-bottom: 10px;">Qualité</h3>
                <p style="color: #6b7280;">
                    Nous sélectionnons rigoureusement nos fournisseurs pour garantir des services 
                    de haute qualité.
                </p>
            </div>
            
            <div style="text-align: center; padding: 30px; background: #f9fafb; border-radius: 12px;">
                <div style="font-size: 48px; margin-bottom: 15px;"><?php echo getIcon('wallet'); ?></div>
                <h3 style="margin-bottom: 10px;">Prix Justes</h3>
                <p style="color: #6b7280;">
                    Des tarifs compétitifs sans compromis sur la qualité. Pas de frais cachés, 
                    pas de surprises.
                </p>
            </div>
            
            <div style="text-align: center; padding: 30px; background: #f9fafb; border-radius: 12px;">
                <div style="font-size: 48px; margin-bottom: 15px;"><?php echo getIcon('shield'); ?></div>
                <h3 style="margin-bottom: 10px;">Sécurité</h3>
                <p style="color: #6b7280;">
                    Protection maximale de vos données. Paiements sécurisés, confidentialité respectée, 
                    SSL/TLS.
                </p>
            </div>
            
            <div style="text-align: center; padding: 30px; background: #f9fafb; border-radius: 12px;">
                <div style="font-size: 48px; margin-bottom: 15px;">🤝</div>
                <h3 style="margin-bottom: 10px;">Support</h3>
                <p style="color: #6b7280;">
                    Une équipe dédiée pour vous aider 24/7. Votre satisfaction est notre priorité.
                </p>
            </div>
        </div>
    </div>

    <!-- Pourquoi Nous Choisir -->
    <div class="content-section">
        <h2>🌟 Pourquoi Choisir SMM Mastery ?</h2>
        
        <h3><?php echo getIcon('success'); ?> 1. Système Multi-Tier Unique</h3>
        <p>
            Nous sommes l'une des rares plateformes à proposer <strong>4 niveaux de qualité clairement définis</strong> :
        </p>
        <ul>
            <li><strong><?php echo tierBadge('budget'); ?></strong> : Pour tester à moindre coût</li>
            <li><strong><?php echo tierBadge('standard'); ?></strong> : Le meilleur rapport qualité/prix</li>
            <li><strong><?php echo tierBadge('premium'); ?></strong> : Pour ceux qui veulent le meilleur</li>
            <li><strong><?php echo tierBadge('ultimate'); ?></strong> : L'excellence absolue avec garantie à vie</li>
        </ul>
        
        <h3><?php echo getIcon('success'); ?> 2. Transparence Totale</h3>
        <p>
            Chaque service affiche :
        </p>
        <ul>
            <li>Le Drop Rate exact (No Drop, Low Drop, High Drop)</li>
            <li>La période de garantie refill</li>
            <li>La vitesse de livraison</li>
            <li>Les limites min/max</li>
        </ul>
        <p>
            <strong>Pas de surprises, pas de déceptions.</strong>
        </p>
        
        <h3><?php echo getIcon('success'); ?> 3. Prix Compétitifs</h3>
        <p>
            Nos prix sont <strong>30-50% moins chers</strong> que la concurrence grâce à :
        </p>
        <ul>
            <li>Des partenariats directs avec les fournisseurs</li>
            <li>Des marges raisonnables</li>
            <li>Pas de frais cachés</li>
            <li>Des bonus sur les dépôts (jusqu'à 15%)</li>
        </ul>
        
        <h3><?php echo getIcon('success'); ?> 4. Technologie Moderne</h3>
        <ul>
            <li>Interface intuitive et rapide</li>
            <li>Responsive 100% (mobile, tablet, desktop)</li>
            <li>API complète pour les développeurs</li>
            <li>Tracking en temps réel</li>
            <li>Dashboard détaillé avec statistiques</li>
        </ul>
        
        <h3><?php echo getIcon('success'); ?> 5. Support Exceptionnel</h3>
        <ul>
            <li>Réponse sous 6 heures en moyenne</li>
            <li>Support multilingue (FR/EN)</li>
            <li>Système de tickets efficace</li>
            <li>FAQ complète</li>
            <li>Disponible 24/7</li>
        </ul>
        
        <h3><?php echo getIcon('success'); ?> 6. Sécurité Maximale</h3>
        <ul>
            <li>SSL/TLS 256-bit</li>
            <li>Paiements via PayPal, Stripe (certifiés PCI DSS)</li>
            <li>Aucune donnée bancaire stockée</li>
            <li>Conformité RGPD</li>
            <li>Protection anti-DDoS</li>
        </ul>
    </div>

    <!-- Nos Chiffres -->
    <div class="content-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h2 style="color: white; border-bottom: 2px solid rgba(255,255,255,0.3);"><?php echo getIcon('stats'); ?> SMM Mastery en Chiffres</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; margin-top: 30px;">
            <div style="text-align: center;">
                <div style="font-size: 48px; font-weight: 700; margin-bottom: 10px;">500+</div>
                <div style="opacity: 0.9;">Services Disponibles</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 48px; font-weight: 700; margin-bottom: 10px;">10+</div>
                <div style="opacity: 0.9;">Plateformes Supportées</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 48px; font-weight: 700; margin-bottom: 10px;">24/7</div>
                <div style="opacity: 0.9;">Support Disponible</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 48px; font-weight: 700; margin-bottom: 10px;">99.9%</div>
                <div style="opacity: 0.9;">Uptime Garanti</div>
            </div>
        </div>
    </div>

    <!-- Notre Équipe -->
    <div class="content-section">
        <h2><?php echo getIcon('followers'); ?> Notre Équipe</h2>
        <p>
            SMM Mastery est géré par une équipe passionnée de professionnels du marketing digital, 
            développeurs et spécialistes du support client, tous dédiés à votre succès.
        </p>
        
        <h3>Nos Expertises</h3>
        <ul>
            <li><strong>Marketing Digital</strong> : 10+ ans d'expérience en SMM</li>
            <li><strong>Développement Web</strong> : Technologies modernes (PHP, MySQL, JavaScript)</li>
            <li><strong>Support Client</strong> : Formation continue sur les meilleures pratiques</li>
            <li><strong>Sécurité</strong> : Certification en cybersécurité</li>
            <li><strong>Analyse de Données</strong> : Optimisation basée sur les métriques réelles</li>
        </ul>
    </div>

    <!-- Nos Engagements -->
    <div class="content-section">
        <h2>🤝 Nos Engagements</h2>
        
        <div class="highlight-box">
            <h3 style="margin-bottom: 15px;">Nous nous engageons à :</h3>
            <ul style="margin: 0;">
                <li><?php echo getIcon('success'); ?> <strong>Transparence totale</strong> sur nos services et leurs limitations</li>
                <li><?php echo getIcon('success'); ?> <strong>Écouter nos clients</strong> et améliorer continuellement notre plateforme</li>
                <li><?php echo getIcon('success'); ?> <strong>Respecter votre confidentialité</strong> et protéger vos données</li>
                <li><?php echo getIcon('success'); ?> <strong>Fournir un support de qualité</strong> rapide et efficace</li>
                <li><?php echo getIcon('success'); ?> <strong>Maintenir des prix justes</strong> sans compromis sur la qualité</li>
                <li><?php echo getIcon('success'); ?> <strong>Innover constamment</strong> pour rester à la pointe de la technologie</li>
            </ul>
        </div>
    </div>

    <!-- Notre Vision -->
    <div class="content-section">
        <h2>🔮 Notre Vision</h2>
        <p>
            Nous visons à devenir <strong>la plateforme SMM de référence</strong> en combinant :
        </p>
        <ul>
            <li>🎯 <strong>Excellence du service</strong> : Qualité irréprochable</li>
            <li><?php echo getIcon('rocket', true); ?> <strong>Innovation continue</strong> : Nouvelles fonctionnalités régulières</li>
            <li>🌍 <strong>Expansion internationale</strong> : Support multilingue, devises multiples</li>
            <li>🤖 <strong>Automatisation intelligente</strong> : IA pour optimiser les campagnes</li>
            <li><?php echo getIcon('phone'); ?> <strong>Applications mobiles</strong> : iOS et Android (en développement)</li>
        </ul>
    </div>

    <!-- Responsabilité Sociale -->
    <div class="content-section">
        <h2>🌱 Responsabilité Sociale</h2>
        <p>
            Nous croyons en une croissance responsable et éthique. C'est pourquoi :
        </p>
        <ul>
            <li>♻️ Nous utilisons des <strong>serveurs écologiques</strong> alimentés par énergie renouvelable</li>
            <li>🤝 Nous soutenons les <strong>petites entreprises</strong> et créateurs indépendants</li>
            <li>🎓 Nous offrons des <strong>réductions éducatives</strong> pour les étudiants et associations</li>
            <li>💡 Nous partageons notre <strong>connaissance</strong> via des guides gratuits</li>
            <li>🌍 Nous contribuons à des <strong>projets open-source</strong></li>
        </ul>
    </div>

    <!-- Partenaires -->
    <div class="content-section">
        <h2>🤝 Nos Partenaires</h2>
        <p>
            Nous travaillons avec les meilleurs fournisseurs de l'industrie pour vous garantir :
        </p>
        <ul>
            <li><?php echo getIcon('success'); ?> Services de haute qualité</li>
            <li><?php echo getIcon('success'); ?> Livraison rapide et fiable</li>
            <li><?php echo getIcon('success'); ?> Support technique réactif</li>
            <li><?php echo getIcon('success'); ?> Prix compétitifs</li>
        </ul>
        <p>
            Tous nos partenaires sont rigoureusement sélectionnés et évalués régulièrement.
        </p>
    </div>

    <!-- Contact -->
    <div class="content-section">
        <h2><?php echo getIcon('phone'); ?> Nous Contacter</h2>
        <p>
            Une question ? Une suggestion ? Nous sommes là pour vous écouter :
        </p>
        <ul>
            <li><?php echo getIcon('mail'); ?> <strong>Email Général</strong> : contact@smmmaster.com</li>
            <li>💼 <strong>Partenariats</strong> : partners@smmmaster.com</li>
            <li>📰 <strong>Presse</strong> : press@smmmaster.com</li>
            <li><?php echo getIcon('support'); ?> <strong>Support</strong> : <a href="../support/new-ticket.php" style="color: #667eea;">Créer un ticket</a></li>
            <li><?php echo getIcon('info'); ?> <strong>Adresse</strong> : [VOTRE ADRESSE]</li>
        </ul>
    </div>

    <!-- Rejoignez-nous -->
    <div class="content-section" style="text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h2 style="color: white; border-bottom: none;"><?php echo getIcon('rocket', true); ?> Rejoignez l'Aventure !</h2>
        <p style="color: white; opacity: 0.9; font-size: 18px; margin-bottom: 30px;">
            Des milliers de créateurs et entreprises nous font déjà confiance.<br>
            Pourquoi pas vous ?
        </p>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="../auth/register.php" class="btn btn-lg" style="background: white; color: #667eea;">
                <?php echo getIcon('star'); ?> Créer un Compte Gratuit
            </a>
            <a href="../services/index.php" class="btn btn-lg" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid white;">
                <?php echo getIcon('services'); ?> Découvrir les Services
            </a>
        </div>
    </div>

</div>

<?php include '../includes/public-footer.php'; ?>
