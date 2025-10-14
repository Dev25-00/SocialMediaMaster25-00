<?php
require_once '../config.php';
require_once '../functions.php';

$page_title = 'Politique de Remboursement | ' . SITE_NAME;
$page_description = 'Découvrez notre politique de remboursement et nos garanties. Conditions de refund et de refill.';

include '../includes/public-header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1><?php echo getIcon('wallet'); ?> Politique de Remboursement</h1>
        <p>Transparence totale sur nos conditions de remboursement</p>
    </div>
</div>

<div class="page-content">
    
    <div class="highlight-box">
        <strong><?php echo getIcon('info'); ?> Principe général :</strong> Les services SMM sont numériques et fournis par des tiers. 
        Une fois qu'une commande est lancée, elle ne peut généralement pas être remboursée. Cependant, 
        nous offrons des garanties refill et des remboursements dans certains cas spécifiques.
    </div>

    <!-- 1. Politique Générale -->
    <div class="content-section">
        <h2>1. Politique Générale de Remboursement</h2>
        
        <h3>1.1 Nature des Services</h3>
        <p>
            SMM Mastery propose des services numériques instantanés. Une fois qu'une commande est passée 
            et commence à être traitée, elle est immédiatement transmise à nos fournisseurs et le processus 
            de livraison démarre.
        </p>
        <p>
            Pour cette raison, <strong>les remboursements ne sont généralement pas disponibles</strong>, 
            sauf dans les cas spécifiques mentionnés ci-dessous.
        </p>
        
        <h3>1.2 Remboursements vs Refill</h3>
        <p>
            Au lieu de remboursements, nous offrons des <strong>garanties refill</strong> sur de nombreux services :
        </p>
        <ul>
            <li><strong>Refill</strong> : Remplacement gratuit en cas de baisse pendant la période de garantie</li>
            <li><strong>Remboursement</strong> : Retour de l'argent (uniquement dans les cas spécifiés)</li>
        </ul>
    </div>

    <!-- 2. Cas de Remboursement -->
    <div class="content-section">
        <h2>2. Cas où un Remboursement est Possible</h2>
        
        <h3>2.1 Service Non Démarré (<?php echo getIcon('success'); ?> Remboursement Total)</h3>
        <p>
            Vous pouvez obtenir un remboursement complet si :
        </p>
        <ul>
            <li><?php echo getIcon('success'); ?> Votre commande est restée en statut "Pending" pendant plus de <strong>72 heures</strong></li>
            <li><?php echo getIcon('success'); ?> Le service n'a pas du tout commencé</li>
            <li><?php echo getIcon('success'); ?> Vous avez contacté le support et confirmé le non-démarrage</li>
        </ul>
        <p>
            <strong>Comment demander :</strong> Créez un ticket avec votre numéro de commande.
        </p>
        
        <h3>2.2 Service Incorrect (<?php echo getIcon('success'); ?> Remboursement Partiel/Total)</h3>
        <p>
            Remboursement possible si :
        </p>
        <ul>
            <li><?php echo getIcon('success'); ?> Le service livré ne correspond pas à ce que vous avez commandé</li>
            <li><?php echo getIcon('success'); ?> Exemple : Vous commandez des followers Instagram et recevez des likes YouTube</li>
            <li><?php echo getIcon('success'); ?> Vous devez fournir des preuves (captures d'écran)</li>
        </ul>
        
        <h3>2.3 Erreur de Notre Part (<?php echo getIcon('success'); ?> Remboursement Total)</h3>
        <p>
            Remboursement automatique si :
        </p>
        <ul>
            <li><?php echo getIcon('success'); ?> Bug système qui a débité 2 fois votre compte</li>
            <li><?php echo getIcon('success'); ?> Erreur de prix affichée sur le site</li>
            <li><?php echo getIcon('success'); ?> Problème technique avéré de notre côté</li>
        </ul>
        
        <h3>2.4 Livraison Partielle (<50%) (<?php echo getIcon('success'); ?> Remboursement Partiel)</h3>
        <p>
            Si votre commande est marquée "Partial" avec moins de 50% livré :
        </p>
        <ul>
            <li><?php echo getIcon('success'); ?> Remboursement pour la quantité non livrée</li>
            <li><?php echo getIcon('success'); ?> Exemple : Commande de 1000, livré 300 → Remboursement pour 700</li>
        </ul>
    </div>

    <!-- 3. Cas SANS Remboursement -->
    <div class="content-section">
        <h2>3. Cas où Aucun Remboursement n'est Accordé</h2>
        
        <div class="warning-box">
            <strong><?php echo getIcon('warning'); ?> Important :</strong> Les situations suivantes ne donnent PAS droit à remboursement.
        </div>
        
        <h3>3.1 Drop Naturel (<?php echo getIcon('error'); ?> Pas de Remboursement, mais Refill possible)</h3>
        <p>
            Si vous constatez une baisse après livraison :
        </p>
        <ul>
            <li><?php echo getIcon('error'); ?> Pas de remboursement</li>
            <li><?php echo getIcon('success'); ?> Mais <strong>Refill gratuit</strong> si votre service inclut cette garantie</li>
            <li>💡 Vérifiez le "Drop Rate" et la période de garantie de votre service</li>
        </ul>
        
        <h3>3.2 Changement d'Avis (<?php echo getIcon('error'); ?> Pas de Remboursement)</h3>
        <p>
            Pas de remboursement si :
        </p>
        <ul>
            <li><?php echo getIcon('error'); ?> Vous changez d'avis après avoir passé commande</li>
            <li><?php echo getIcon('error'); ?> Vous trouvez moins cher ailleurs</li>
            <li><?php echo getIcon('error'); ?> Vous n'êtes plus satisfait du résultat (mais service livré)</li>
        </ul>
        
        <h3>3.3 Problèmes de Votre Côté (<?php echo getIcon('error'); ?> Pas de Remboursement)</h3>
        <p>
            Pas de remboursement si le problème vient de vous :
        </p>
        <ul>
            <li><?php echo getIcon('error'); ?> Compte privé (on ne peut pas livrer)</li>
            <li><?php echo getIcon('error'); ?> Lien incorrect fourni</li>
            <li><?php echo getIcon('error'); ?> Compte supprimé ou banni</li>
            <li><?php echo getIcon('error'); ?> Changement de nom d'utilisateur après commande</li>
        </ul>
        
        <h3>3.4 Services "No Refund" (<?php echo getIcon('error'); ?> Pas de Remboursement)</h3>
        <p>
            Certains services sont clairement marqués "No Refund" :
        </p>
        <ul>
            <li><?php echo getIcon('error'); ?> Ces services ne comportent aucune garantie</li>
            <li><?php echo getIcon('error'); ?> Prix plus bas en échange</li>
            <li>💡 Lisez bien la description avant de commander</li>
        </ul>
        
        <h3>3.5 Délai de Livraison (<?php echo getIcon('error'); ?> Pas de Remboursement)</h3>
        <p>
            Les délais sont <strong>estimatifs</strong> :
        </p>
        <ul>
            <li><?php echo getIcon('error'); ?> Un retard de livraison ne donne pas droit à remboursement</li>
            <li><?php echo getIcon('success'); ?> Sauf si le délai dépasse 72h et que le service n'a pas démarré (voir 2.1)</li>
        </ul>
    </div>

    <!-- 4. Système de Refill -->
    <div class="content-section">
        <h2>4. Garantie Refill (Remplacement Gratuit)</h2>
        
        <h3>4.1 Qu'est-ce que le Refill ?</h3>
        <p>
            Le <strong>Refill</strong> est une garantie de remplacement gratuit si vous constatez une baisse 
            de vos followers, likes, ou autres pendant la période de garantie.
        </p>
        
        <h3>4.2 Périodes de Garantie</h3>
        <ul>
            <li><?php echo getIcon('shares'); ?> <strong>7 jours</strong> : Services Budget</li>
            <li><?php echo getIcon('shares'); ?> <strong>30 jours</strong> : Services Standard</li>
            <li><?php echo getIcon('shares'); ?> <strong>90 jours</strong> : Services Premium</li>
            <li><?php echo getIcon('shares'); ?> <strong>365 jours (Lifetime)</strong> : Services Ultimate</li>
            <li><?php echo getIcon('error'); ?> <strong>No Refill</strong> : Certains services sans garantie</li>
        </ul>
        
        <h3>4.3 Comment Demander un Refill ?</h3>
        <ol>
            <li>Connectez-vous à votre compte</li>
            <li>Allez dans "Mes Commandes"</li>
            <li>Trouvez la commande concernée</li>
            <li>Cliquez sur "Demander un Refill"</li>
            <li>Le refill sera traité automatiquement</li>
        </ol>
        
        <h3>4.4 Conditions du Refill</h3>
        <p>
            Pour bénéficier du refill :
        </p>
        <ul>
            <li><?php echo getIcon('success'); ?> La demande doit être faite <strong>pendant la période de garantie</strong></li>
            <li><?php echo getIcon('success'); ?> Le compte doit toujours être public</li>
            <li><?php echo getIcon('success'); ?> Le compte ne doit pas avoir été supprimé ou banni</li>
            <li><?php echo getIcon('success'); ?> Un seul refill par commande</li>
        </ul>
    </div>

    <!-- 5. Procédure de Demande -->
    <div class="content-section">
        <h2>5. Comment Demander un Remboursement</h2>
        
        <h3>5.1 Étapes à Suivre</h3>
        <ol>
            <li><strong>Vérifiez l'éligibilité</strong> : Votre cas correspond-il à un cas de remboursement (Section 2) ?</li>
            <li><strong>Rassemblez les preuves</strong> :
                <ul>
                    <li>Numéro de commande</li>
                    <li>Captures d'écran du problème</li>
                    <li>Description détaillée</li>
                </ul>
            </li>
            <li><strong>Contactez le Support</strong> :
                <ul>
                    <li>Créez un ticket depuis votre dashboard</li>
                    <li>Ou envoyez un email à : refund@smmmaster.com</li>
                </ul>
            </li>
            <li><strong>Attendez la réponse</strong> : Nous répondons sous <strong>24-48 heures</strong></li>
        </ol>
        
        <h3>5.2 Informations à Fournir</h3>
        <p>
            Pour traiter rapidement votre demande, incluez :
        </p>
        <ul>
            <li><?php echo getIcon('success'); ?> Numéro de commande (ORD-XXXXX)</li>
            <li><?php echo getIcon('success'); ?> Email de votre compte</li>
            <li><?php echo getIcon('success'); ?> Description claire du problème</li>
            <li><?php echo getIcon('success'); ?> Captures d'écran (avant/après si applicable)</li>
            <li><?php echo getIcon('success'); ?> Date de la commande</li>
        </ul>
        
        <h3>5.3 Délai de Traitement</h3>
        <ul>
            <li><?php echo getIcon('mail'); ?> <strong>Réponse initiale</strong> : 24-48 heures</li>
            <li><?php echo getIcon('search'); ?> <strong>Investigation</strong> : 2-5 jours ouvrables</li>
            <li><?php echo getIcon('wallet'); ?> <strong>Remboursement</strong> (si approuvé) : Immédiat sur votre solde</li>
            <li>↩️ <strong>Retour méthode de paiement originale</strong> (si demandé) : 5-10 jours ouvrables</li>
        </ul>
    </div>

    <!-- 6. Modalités de Remboursement -->
    <div class="content-section">
        <h2>6. Modalités de Remboursement</h2>
        
        <h3>6.1 Où le Remboursement est Crédité ?</h3>
        <p>
            Par défaut, les remboursements sont crédités sur <strong>votre solde SMM Mastery</strong> :
        </p>
        <ul>
            <li><?php echo getIcon('success'); ?> Instantané</li>
            <li><?php echo getIcon('success'); ?> Réutilisable immédiatement pour d'autres commandes</li>
        </ul>
        
        <h3>6.2 Remboursement sur Méthode de Paiement Originale</h3>
        <p>
            Si vous préférez un remboursement sur votre carte/PayPal :
        </p>
        <ul>
            <li><?php echo getIcon('edit'); ?> Vous devez en faire la demande explicite</li>
            <li><?php echo getIcon('pending'); ?> Délai : 5-10 jours ouvrables</li>
            <li><?php echo getIcon('card'); ?> Des frais de traitement peuvent s'appliquer (frais PayPal, etc.)</li>
            <li><?php echo getIcon('warning'); ?> Disponible uniquement pour les montants >$20</li>
        </ul>
        
        <h3>6.3 Frais</h3>
        <ul>
            <li><?php echo getIcon('success'); ?> <strong>Remboursement sur solde</strong> : GRATUIT, aucun frais</li>
            <li><?php echo getIcon('card'); ?> <strong>Remboursement sur carte/PayPal</strong> : Frais de transaction appliqués (environ 3-5%)</li>
        </ul>
    </div>

    <!-- 7. Cas Spéciaux -->
    <div class="content-section">
        <h2>7. Cas Spéciaux et Exceptions</h2>
        
        <h3>7.1 Problème avec un Fournisseur</h3>
        <p>
            Si notre fournisseur a un problème technique majeur :
        </p>
        <ul>
            <li><?php echo getIcon('success'); ?> Remboursement automatique de toutes les commandes affectées</li>
            <li><?php echo getIcon('success'); ?> Notification envoyée à tous les utilisateurs concernés</li>
        </ul>
        
        <h3>7.2 Suspension de Compte Social</h3>
        <p>
            Si votre compte social est suspendu/banni :
        </p>
        <ul>
            <li><?php echo getIcon('error'); ?> Pas de remboursement (le service a été livré)</li>
            <li>💡 Nous ne sommes pas responsables des actions des plateformes sociales</li>
            <li>💡 Lisez les TOS de chaque plateforme avant d'utiliser nos services</li>
        </ul>
        
        <h3>7.3 Force Majeure</h3>
        <p>
            En cas de force majeure (panne majeure, catastrophe naturelle, etc.), nous ne sommes 
            pas responsables des retards ou non-livraison, mais nous ferons notre maximum pour résoudre 
            la situation.
        </p>
    </div>

    <!-- 8. Prévention des Abus -->
    <div class="content-section">
        <h2>8. Prévention des Abus</h2>
        
        <div class="warning-box">
            <strong><?php echo getIcon('warning'); ?> Attention :</strong> Les abus de demandes de remboursement peuvent entraîner 
            la suspension de votre compte.
        </div>
        
        <h3>8.1 Utilisation Abusive</h3>
        <p>
            Nous nous réservons le droit de refuser des remboursements si :
        </p>
        <ul>
            <li><?php echo getIcon('error'); ?> Vous faites des demandes répétées injustifiées</li>
            <li><?php echo getIcon('error'); ?> Vous essayez de frauder le système</li>
            <li><?php echo getIcon('error'); ?> Vous fournissez de fausses preuves</li>
            <li><?php echo getIcon('error'); ?> Votre compte montre des signes d'activité frauduleuse</li>
        </ul>
        
        <h3>8.2 Enquête</h3>
        <p>
            Nous nous réservons le droit d'enquêter sur toute demande de remboursement et de demander 
            des preuves supplémentaires si nécessaire.
        </p>
    </div>

    <!-- 9. Contact -->
    <div class="content-section">
        <h2>9. Questions sur les Remboursements ?</h2>
        <p>
            Pour toute question concernant notre politique de remboursement :
        </p>
        <ul>
            <li><?php echo getIcon('mail'); ?> <strong>Email</strong> : refund@smmmaster.com</li>
            <li><?php echo getIcon('support'); ?> <strong>Support Ticket</strong> : <a href="../support/new-ticket.php" style="color: #667eea;">Créer un ticket</a></li>
            <li>📖 <strong>FAQ</strong> : <a href="faq.php" style="color: #667eea;">Consultez notre FAQ</a></li>
        </ul>
    </div>

    <!-- Tableau récapitulatif -->
    <div class="content-section">
        <h2><?php echo getIcon('list'); ?> Tableau Récapitulatif</h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead style="background: #f9fafb;">
                    <tr>
                        <th style="padding: 12px; text-align: left; border: 1px solid #e5e7eb;">Situation</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #e5e7eb;">Remboursement</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #e5e7eb;">Refill</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">Service non démarré (72h+)</td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #e5e7eb; color: #10b981; font-weight: bold;"><?php echo getIcon('success'); ?> OUI</td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #e5e7eb;">-</td>
                    </tr>
                    <tr style="background: #f9fafb;">
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">Service incorrect</td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #e5e7eb; color: #10b981; font-weight: bold;"><?php echo getIcon('success'); ?> OUI</td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #e5e7eb;">-</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">Drop après livraison</td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #e5e7eb; color: #ef4444; font-weight: bold;"><?php echo getIcon('error'); ?> NON</td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #e5e7eb; color: #10b981; font-weight: bold;"><?php echo getIcon('success'); ?> OUI*</td>
                    </tr>
                    <tr style="background: #f9fafb;">
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">Changement d'avis</td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #e5e7eb; color: #ef4444; font-weight: bold;"><?php echo getIcon('error'); ?> NON</td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #e5e7eb;">-</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">Compte privé/supprimé</td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #e5e7eb; color: #ef4444; font-weight: bold;"><?php echo getIcon('error'); ?> NON</td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #e5e7eb; color: #ef4444; font-weight: bold;"><?php echo getIcon('error'); ?> NON</td>
                    </tr>
                    <tr style="background: #f9fafb;">
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">Livraison partielle (<50%)</td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #e5e7eb; color: #10b981; font-weight: bold;"><?php echo getIcon('success'); ?> PARTIEL</td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #e5e7eb;">-</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p style="margin-top: 15px; font-size: 14px; color: #6b7280;">
            * Refill disponible uniquement si le service inclut cette garantie et pendant la période indiquée
        </p>
    </div>

    <!-- CTA -->
    <div class="content-section" style="text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h2 style="color: white; border-bottom: none;">💯 Satisfaction Garantie</h2>
        <p style="color: white; opacity: 0.9; font-size: 18px; margin-bottom: 30px;">
            Nous faisons tout notre possible pour assurer votre satisfaction !
        </p>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="../services/index.php" class="btn btn-lg" style="background: white; color: #667eea;">
                <?php echo getIcon('services'); ?> Parcourir les Services
            </a>
            <a href="faq.php" class="btn btn-lg" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid white;">
                <?php echo getIcon('info'); ?> FAQ
            </a>
        </div>
    </div>

</div>

<?php include '../includes/public-footer.php'; ?>
