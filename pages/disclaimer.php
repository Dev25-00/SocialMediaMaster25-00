<?php
require_once '../config.php';
require_once '../functions.php';

$page_title = 'Disclaimer - Avertissement Légal | ' . SITE_NAME;
$page_description = 'Avertissement légal et limitations concernant l\'utilisation des services SMM Mastery.';

include '../includes/layout/public-header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1><?php echo getIcon('warning'); ?> Disclaimer - Avertissement Légal</h1>
        <p>Informations importantes à lire avant utilisation</p>
    </div>
</div>

<div class="page-content">
    
    <div class="warning-box">
        <strong><?php echo getIcon('warning'); ?> Important :</strong> Veuillez lire attentivement ce disclaimer avant d'utiliser nos services. 
        L'utilisation de SMM Mastery implique votre acceptation de ces conditions et limitations.
    </div>

    <!-- 1. Nature des Services -->
    <div class="content-section">
        <h2>1. Nature de nos Services</h2>
        
        <h3>1.1 Services Fournis</h3>
        <p>
            SMM Mastery est une plateforme de <strong>Social Media Marketing</strong> qui propose des services 
            de croissance sur les réseaux sociaux. Nous agissons en tant qu'<strong>intermédiaire</strong> 
            entre vous et nos fournisseurs tiers.
        </p>
        
        <h3>1.2 Aucune Affiliation</h3>
        <p>
            SMM Mastery n'est <strong>PAS affilié, sponsorisé, ou approuvé</strong> par :
        </p>
        <ul>
            <li><?php echo getIcon('error'); ?> Meta (Facebook, Instagram)</li>
            <li><?php echo getIcon('error'); ?> Google (YouTube)</li>
            <li><?php echo getIcon('error'); ?> ByteDance (TikTok)</li>
            <li><?php echo getIcon('error'); ?> Twitter (X)</li>
            <li><?php echo getIcon('error'); ?> LinkedIn</li>
            <li><?php echo getIcon('error'); ?> Ou toute autre plateforme de médias sociaux</li>
        </ul>
        <p>
            Tous les noms de marques, logos et marques de commerce mentionnés sur ce site appartiennent 
            à leurs propriétaires respectifs et sont utilisés uniquement à des fins d'identification.
        </p>
    </div>

    <!-- 2. Conformité avec les TOS -->
    <div class="content-section">
        <h2>2. Conditions d'Utilisation des Plateformes</h2>
        
        <div class="warning-box">
            <strong><?php echo getIcon('warning'); ?> Avertissement Important :</strong> L'utilisation de services SMM peut violer les 
            conditions d'utilisation de certaines plateformes de médias sociaux.
        </div>
        
        <h3>2.1 Responsabilité de l'Utilisateur</h3>
        <p>
            Vous reconnaissez et acceptez que :
        </p>
        <ul>
            <li><?php echo getIcon('success'); ?> Vous êtes <strong>seul responsable</strong> du respect des TOS de chaque plateforme</li>
            <li><?php echo getIcon('success'); ?> Vous devez lire et comprendre les règles de chaque plateforme avant d'utiliser nos services</li>
            <li><?php echo getIcon('success'); ?> Vous utilisez nos services <strong>à vos propres risques</strong></li>
            <li><?php echo getIcon('success'); ?> Nous ne sommes <strong>pas responsables</strong> des conséquences de votre utilisation</li>
        </ul>
        
        <h3>2.2 Risques Potentiels</h3>
        <p>
            L'utilisation de nos services peut entraîner :
        </p>
        <ul>
            <li><?php echo getIcon('warning'); ?> Avertissements de la plateforme</li>
            <li><?php echo getIcon('warning'); ?> Limitations de votre compte</li>
            <li><?php echo getIcon('warning'); ?> Suspension temporaire</li>
            <li><?php echo getIcon('warning'); ?> Bannissement définitif</li>
            <li><?php echo getIcon('warning'); ?> Suppression de contenu</li>
        </ul>
        <p>
            <strong>Nous ne sommes pas responsables</strong> de ces conséquences. Utilisez nos services 
            en connaissance de cause.
        </p>
    </div>

    <!-- 3. Aucune Garantie -->
    <div class="content-section">
        <h2>3. Absence de Garanties</h2>
        
        <h3>3.1 Services "Tel Quel"</h3>
        <p>
            Nos services sont fournis <strong>"TEL QUEL"</strong> et <strong>"TEL QUE DISPONIBLES"</strong>, 
            sans aucune garantie d'aucune sorte, expresse ou implicite.
        </p>
        
        <h3>3.2 Aucune Garantie de Résultats</h3>
        <p>
            Nous ne garantissons PAS :
        </p>
        <ul>
            <li><?php echo getIcon('error'); ?> Des résultats spécifiques (croissance, engagement, ventes, etc.)</li>
            <li><?php echo getIcon('error'); ?> La qualité exacte des followers/likes/vues (origine, activité, etc.)</li>
            <li><?php echo getIcon('error'); ?> La permanence des services fournis</li>
            <li><?php echo getIcon('error'); ?> L'absence de drop (baisse) - même sur services "No Drop"</li>
            <li><?php echo getIcon('error'); ?> La compatibilité avec les algorithmes des plateformes</li>
            <li><?php echo getIcon('error'); ?> L'impact positif sur votre activité</li>
        </ul>
        
        <h3>3.3 Variabilité des Services</h3>
        <p>
            La qualité et l'efficacité de nos services peuvent varier en fonction de :
        </p>
        <ul>
            <li>Le fournisseur tiers</li>
            <li>Les changements d'algorithmes des plateformes</li>
            <li>La demande et la disponibilité</li>
            <li>Des facteurs hors de notre contrôle</li>
        </ul>
    </div>

    <!-- 4. Limitation de Responsabilité -->
    <div class="content-section">
        <h2>4. Limitation de Responsabilité</h2>
        
        <h3>4.1 Dommages Non Couverts</h3>
        <p>
            <strong>SMM Mastery, ses propriétaires, employés et affiliés ne seront EN AUCUN CAS responsables de :</strong>
        </p>
        <ul>
            <li><?php echo getIcon('error'); ?> Perte de followers, likes, vues ou tout autre métrique</li>
            <li><?php echo getIcon('error'); ?> Suspension, limitation ou bannissement de vos comptes</li>
            <li><?php echo getIcon('error'); ?> Perte de revenus, profits ou opportunités commerciales</li>
            <li><?php echo getIcon('error'); ?> Dommages à votre réputation ou image de marque</li>
            <li><?php echo getIcon('error'); ?> Perte de données ou de contenu</li>
            <li><?php echo getIcon('error'); ?> Dommages indirects, consécutifs ou punitifs</li>
            <li><?php echo getIcon('error'); ?> Tout autre dommage résultant de l'utilisation ou de l'impossibilité d'utiliser nos services</li>
        </ul>
        
        <h3>4.2 Responsabilité Maximale</h3>
        <p>
            En toutes circonstances, notre responsabilité totale ne dépassera <strong>jamais</strong> 
            le montant que vous avez payé pour le service spécifique concerné au cours des 30 derniers jours.
        </p>
    </div>

    <!-- 5. Utilisation à Vos Risques -->
    <div class="content-section">
        <h2>5. Utilisation à Vos Propres Risques</h2>
        
        <h3>5.1 Décision Éclairée</h3>
        <p>
            En utilisant SMM Mastery, vous reconnaissez que :
        </p>
        <ul>
            <li><?php echo getIcon('success'); ?> Vous avez été informé des risques</li>
            <li><?php echo getIcon('success'); ?> Vous comprenez que les services peuvent violer certaines TOS</li>
            <li><?php echo getIcon('success'); ?> Vous acceptez tous les risques associés</li>
            <li><?php echo getIcon('success'); ?> Vous prenez une décision éclairée et volontaire</li>
        </ul>
        
        <h3>5.2 Alternatives Recommandées</h3>
        <p>
            Si vous souhaitez respecter strictement les TOS des plateformes, nous vous recommandons d'utiliser :
        </p>
        <ul>
            <li><?php echo getIcon('success'); ?> Les outils publicitaires officiels (Facebook Ads, Instagram Ads, etc.)</li>
            <li><?php echo getIcon('success'); ?> Les stratégies de marketing organique</li>
            <li><?php echo getIcon('success'); ?> Les collaborations avec influenceurs</li>
            <li><?php echo getIcon('success'); ?> Le contenu de qualité et l'engagement authentique</li>
        </ul>
    </div>

    <!-- 6. Pas de Conseils Professionnels -->
    <div class="content-section">
        <h2>6. Absence de Conseils Professionnels</h2>
        
        <h3>6.1 Pas de Conseil Marketing</h3>
        <p>
            Les informations fournies sur SMM Mastery sont à <strong>titre informatif uniquement</strong> et 
            ne constituent pas :
        </p>
        <ul>
            <li><?php echo getIcon('error'); ?> Des conseils en marketing professionnel</li>
            <li><?php echo getIcon('error'); ?> Des conseils juridiques</li>
            <li><?php echo getIcon('error'); ?> Des conseils financiers</li>
            <li><?php echo getIcon('error'); ?> Des recommandations d'investissement</li>
        </ul>
        
        <h3>6.2 Consultez des Professionnels</h3>
        <p>
            Pour des décisions importantes concernant votre entreprise ou votre marque, consultez toujours 
            des professionnels qualifiés (avocats, consultants en marketing, etc.).
        </p>
    </div>

    <!-- 7. Modifications du Service -->
    <div class="content-section">
        <h2>7. Modifications et Interruptions</h2>
        
        <h3>7.1 Droit de Modification</h3>
        <p>
            Nous nous réservons le droit, à tout moment et sans préavis, de :
        </p>
        <ul>
            <li>Modifier, suspendre ou interrompre tout ou partie de nos services</li>
            <li>Changer les prix et les conditions</li>
            <li>Ajouter ou retirer des fonctionnalités</li>
            <li>Modifier ce disclaimer</li>
        </ul>
        
        <h3>7.2 Aucune Obligation de Continuité</h3>
        <p>
            Nous ne garantissons pas la disponibilité continue de nos services et ne sommes pas responsables 
            des interruptions, qu'elles soient planifiées ou non.
        </p>
    </div>

    <!-- 8. Contenu Utilisateur -->
    <div class="content-section">
        <h2>8. Responsabilité du Contenu</h2>
        
        <h3>8.1 Votre Contenu</h3>
        <p>
            Vous êtes entièrement responsable :
        </p>
        <ul>
            <li><?php echo getIcon('success'); ?> Du contenu que vous publiez sur vos comptes sociaux</li>
            <li><?php echo getIcon('success'); ?> De la légalité de votre contenu</li>
            <li><?php echo getIcon('success'); ?> Du respect des droits de propriété intellectuelle</li>
            <li><?php echo getIcon('success'); ?> De l'obtention des permissions nécessaires</li>
        </ul>
        
        <h3>8.2 Contenu Interdit</h3>
        <p>
            Nous nous réservons le droit de refuser nos services pour des comptes qui :
        </p>
        <ul>
            <li><?php echo getIcon('error'); ?> Publient du contenu illégal</li>
            <li><?php echo getIcon('error'); ?> Violent les droits de propriété intellectuelle</li>
            <li><?php echo getIcon('error'); ?> Promeuvent la haine, la violence ou la discrimination</li>
            <li><?php echo getIcon('error'); ?> Contiennent du contenu pour adultes (sans vérification d'âge appropriée)</li>
            <li><?php echo getIcon('error'); ?> Font la promotion d'activités frauduleuses</li>
        </ul>
    </div>

    <!-- 9. Liens Externes -->
    <div class="content-section">
        <h2>9. Liens Externes et Tiers</h2>
        
        <h3>9.1 Sites Tiers</h3>
        <p>
            Notre site peut contenir des liens vers des sites web tiers. Nous ne sommes pas responsables :
        </p>
        <ul>
            <li><?php echo getIcon('error'); ?> Du contenu de ces sites</li>
            <li><?php echo getIcon('error'); ?> De leur disponibilité</li>
            <li><?php echo getIcon('error'); ?> De leurs pratiques de confidentialité</li>
            <li><?php echo getIcon('error'); ?> De tout dommage résultant de leur utilisation</li>
        </ul>
        
        <h3>9.2 Fournisseurs Tiers</h3>
        <p>
            Nous travaillons avec des fournisseurs tiers pour livrer nos services. Nous ne contrôlons pas 
            leurs méthodes et ne sommes pas responsables de leurs pratiques.
        </p>
    </div>

    <!-- 10. Juridiction -->
    <div class="content-section">
        <h2>10. Juridiction et Loi Applicable</h2>
        
        <h3>10.1 Loi Applicable</h3>
        <p>
            Ce disclaimer est régi par les lois de <strong>[VOTRE JURIDICTION]</strong>, sans égard aux 
            principes de conflits de lois.
        </p>
        
        <h3>10.2 Résolution de Litiges</h3>
        <p>
            Tout litige découlant de ou lié à ce disclaimer sera soumis à la juridiction exclusive des 
            tribunaux de <strong>[VOTRE VILLE/PAYS]</strong>.
        </p>
    </div>

    <!-- 11. Intégralité -->
    <div class="content-section">
        <h2>11. Dispositions Finales</h2>
        
        <h3>11.1 Intégralité de l'Accord</h3>
        <p>
            Ce disclaimer, conjointement avec nos Conditions Générales d'Utilisation et notre Politique 
            de Confidentialité, constitue l'intégralité de l'accord entre vous et SMM Mastery.
        </p>
        
        <h3>11.2 Divisibilité</h3>
        <p>
            Si une disposition de ce disclaimer est jugée invalide ou inapplicable, les autres dispositions 
            resteront pleinement en vigueur.
        </p>
        
        <h3>11.3 Traduction</h3>
        <p>
            En cas de divergence entre les versions linguistiques de ce disclaimer, la version 
            <strong>anglaise</strong> prévaudra.
        </p>
    </div>

    <!-- 12. Contact -->
    <div class="content-section">
        <h2>12. Questions sur ce Disclaimer</h2>
        <p>
            Pour toute question concernant ce disclaimer, contactez-nous :
        </p>
        <ul>
            <li><?php echo getIcon('mail'); ?> <strong>Email Légal</strong> : legal@smmmaster.com</li>
            <li><?php echo getIcon('support'); ?> <strong>Support</strong> : <a href="../support/new-ticket.php" style="color: #667eea;">Créer un ticket</a></li>
            <li><?php echo getIcon('document'); ?> <strong>Adresse</strong> : [VOTRE ADRESSE LÉGALE]</li>
        </ul>
    </div>

    <!-- Important Notice Box -->
    <div class="content-section" style="background: #fee2e2; border-left: 4px solid #ef4444;">
        <h3 style="color: #991b1b; margin-bottom: 15px;"><?php echo getIcon('warning'); ?> AVERTISSEMENT FINAL IMPORTANT</h3>
        <p style="color: #7f1d1d; margin: 0; line-height: 1.8;">
            <strong>EN UTILISANT SMM Mastery, VOUS RECONNAISSEZ AVOIR LU, COMPRIS ET ACCEPTÉ CE DISCLAIMER 
            DANS SON INTÉGRALITÉ.</strong> Vous reconnaissez que vous utilisez nos services à vos propres 
            risques et que nous déclinons toute responsabilité pour les conséquences de votre utilisation, 
            incluant mais sans s'y limiter : suspension de comptes, perte de données, dommages financiers 
            ou à la réputation.
        </p>
    </div>

    <!-- Footer de page -->
    <div class="content-section" style="background: #f9fafb; border-left: 4px solid #6b7280;">
        <p style="margin: 0; color: #6b7280;">
            <strong>Dernière mise à jour :</strong> <?php echo date('d F Y'); ?><br>
            <strong>Version :</strong> 1.0<br>
            <strong>Langue de référence :</strong> Anglais
        </p>
    </div>

    <!-- CTA Alternatif -->
    <div class="content-section" style="text-align: center; background: #f9fafb;">
        <h2>📚 Documents Légaux Connexes</h2>
        <p style="color: #6b7280; margin-bottom: 30px;">
            Consultez également nos autres documents légaux :
        </p>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <a href="terms.php" class="btn btn-secondary">
                <?php echo getIcon('document'); ?> CGU
            </a>
            <a href="privacy.php" class="btn btn-secondary">
                <?php echo getIcon('lock'); ?> Confidentialité
            </a>
            <a href="refund.php" class="btn btn-secondary">
                <?php echo getIcon('wallet'); ?> Remboursements
            </a>
            <a href="faq.php" class="btn btn-secondary">
                <?php echo getIcon('info'); ?> FAQ
            </a>
        </div>
    </div>

</div>

<?php include '../includes/layout/public-footer.php'; ?>
