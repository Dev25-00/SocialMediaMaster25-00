<?php
require_once '../config.php';
require_once '../functions.php';

$page_title = 'Contact - ' . SITE_NAME;
$page_description = 'Contactez l\'équipe SMM Mastery. Support, partenariats, presse. Nous sommes là pour vous aider.';

$success = '';
$error = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $subject = clean($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $type = clean($_POST['type'] ?? 'general');
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse email invalide.';
    } elseif (strlen($message) < 20) {
        $error = 'Le message doit contenir au moins 20 caractères.';
    } else {
        // Dans un vrai projet, envoyer un email ou stocker en BDD
        // Pour l'instant, on simule juste
        
        $to = 'contact@smmmaster.com';
        $email_subject = "[$type] $subject - SMM Mastery Contact";
        $email_body = "Nouveau message de contact\n\n";
        $email_body .= "Type: $type\n";
        $email_body .= "Nom: $name\n";
        $email_body .= "Email: $email\n";
        $email_body .= "Sujet: $subject\n\n";
        $email_body .= "Message:\n$message\n";
        
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        
        // Envoyer l'email (décommenter en production)
        // mail($to, $email_subject, $email_body, $headers);
        
        $success = 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les 24 heures.';
        
        // Réinitialiser les champs
        $_POST = array();
    }
}

include '../includes/layout/public-header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1><?php echo getIcon('mail'); ?> Nous Contacter</h1>
        <p>Une question ? Besoin d'aide ? Nous sommes là pour vous !</p>
    </div>
</div>

<div class="page-content">
    
    <!-- Méthodes de Contact -->
    <div class="content-section">
        <h2><?php echo getIcon('support'); ?> Choisissez votre Mode de Contact</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-top: 30px;">
            
            <!-- Support Ticket -->
            <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid #667eea;">
                <div style="font-size: 48px; margin-bottom: 15px;"><?php echo getIcon('ticket'); ?></div>
                <h3 style="margin-bottom: 10px; color: #667eea;">Support Ticket</h3>
                <p style="color: #6b7280; margin-bottom: 20px;">
                    Pour les problèmes techniques ou questions sur vos commandes
                </p>
                <p style="font-size: 14px; color: #10b981; font-weight: 600; margin-bottom: 20px;">
                    <?php echo getIcon('bolt'); ?> Réponse sous 6h
                </p>
                <a href="../support/new-ticket.php" class="btn btn-primary btn-block">
                    Créer un Ticket
                </a>
            </div>
            
            <!-- Email -->
            <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid #10b981;">
                <div style="font-size: 48px; margin-bottom: 15px;"><?php echo getIcon('mail'); ?></div>
                <h3 style="margin-bottom: 10px; color: #10b981;">Email</h3>
                <p style="color: #6b7280; margin-bottom: 20px;">
                    Pour les demandes générales, partenariats ou presse
                </p>
                <p style="font-size: 14px; color: #10b981; font-weight: 600; margin-bottom: 20px;">
                    <?php echo getIcon('bolt'); ?> Réponse sous 24h
                </p>
                <a href="mailto:contact@smmmaster.com" class="btn btn-success btn-block">
                    Envoyer un Email
                </a>
            </div>
            
            <!-- Live Chat (bientôt) -->
            <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid #f59e0b;">
                <div style="font-size: 48px; margin-bottom: 15px;"><?php echo getIcon('support'); ?></div>
                <h3 style="margin-bottom: 10px; color: #f59e0b;">Chat Live</h3>
                <p style="color: #6b7280; margin-bottom: 20px;">
                    Discussion instantanée avec notre équipe
                </p>
                <p style="font-size: 14px; color: #f59e0b; font-weight: 600; margin-bottom: 20px;">
                    🚧 Bientôt Disponible
                </p>
                <button class="btn btn-secondary btn-block" disabled>
                    Ouvrir le Chat
                </button>
            </div>
            
        </div>
    </div>

    <!-- Formulaire de Contact -->
    <div class="content-section">
        <h2><?php echo getIcon('edit'); ?> Formulaire de Contact</h2>
        <p style="margin-bottom: 30px;">
            Remplissez le formulaire ci-dessous et nous vous répondrons dans les plus brefs délais.
        </p>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" style="max-width: 700px;">
            
            <div class="form-group">
                <label for="type">Type de Demande *</label>
                <select id="type" name="type" required>
                    <option value="general">Question Générale</option>
                    <option value="support">Support Technique</option>
                    <option value="billing">Facturation / Paiement</option>
                    <option value="partnership">Partenariat</option>
                    <option value="press">Presse / Média</option>
                    <option value="bug">Signaler un Bug</option>
                    <option value="suggestion">Suggestion / Feedback</option>
                    <option value="other">Autre</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="name">Nom Complet *</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       required 
                       placeholder="John Doe"
                       value="<?php echo $_POST['name'] ?? ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       required 
                       placeholder="votre@email.com"
                       value="<?php echo $_POST['email'] ?? ''; ?>">
                <small>Nous utiliserons cette adresse pour vous répondre</small>
            </div>
            
            <div class="form-group">
                <label for="subject">Sujet *</label>
                <input type="text" 
                       id="subject" 
                       name="subject" 
                       required 
                       placeholder="Ex: Question sur les tarifs"
                       value="<?php echo $_POST['subject'] ?? ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="message">Message *</label>
                <textarea id="message" 
                          name="message" 
                          required 
                          rows="8" 
                          placeholder="Décrivez votre demande en détail..."
                          style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 10px; font-family: inherit; resize: vertical;"><?php echo $_POST['message'] ?? ''; ?></textarea>
                <small>Minimum 20 caractères</small>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg btn-block">
                <?php echo getIcon('mail'); ?> Envoyer le Message
            </button>
            
            <p style="text-align: center; color: #6b7280; font-size: 14px; margin-top: 15px;">
                En envoyant ce formulaire, vous acceptez que nous utilisions vos données pour vous répondre. 
                Consultez notre <a href="privacy.php" style="color: #667eea;">politique de confidentialité</a>.
            </p>
        </form>
    </div>

    <!-- Emails Directs -->
    <div class="content-section">
        <h2>📬 Emails Directs</h2>
        <p>Vous préférez nous écrire directement ? Voici nos adresses par département :</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
            
            <div style="background: #f9fafb; padding: 20px; border-radius: 8px;">
                <h3 style="font-size: 16px; margin-bottom: 10px; color: #667eea;"><?php echo getIcon('mail'); ?> Support Général</h3>
                <a href="mailto:contact@smmmaster.com" style="color: #111827; font-weight: 600;">
                    contact@smmmaster.com
                </a>
                <p style="color: #6b7280; font-size: 14px; margin-top: 5px;">
                    Questions générales, informations
                </p>
            </div>
            
            <div style="background: #f9fafb; padding: 20px; border-radius: 8px;">
                <h3 style="font-size: 16px; margin-bottom: 10px; color: #10b981;">🆘 Support Technique</h3>
                <a href="mailto:support@smmmaster.com" style="color: #111827; font-weight: 600;">
                    support@smmmaster.com
                </a>
                <p style="color: #6b7280; font-size: 14px; margin-top: 5px;">
                    Aide technique, commandes
                </p>
            </div>
            
            <div style="background: #f9fafb; padding: 20px; border-radius: 8px;">
                <h3 style="font-size: 16px; margin-bottom: 10px; color: #f59e0b;">💼 Partenariats</h3>
                <a href="mailto:partners@smmmaster.com" style="color: #111827; font-weight: 600;">
                    partners@smmmaster.com
                </a>
                <p style="color: #6b7280; font-size: 14px; margin-top: 5px;">
                    Collaborations, affiliation, revendeurs
                </p>
            </div>
            
            <div style="background: #f9fafb; padding: 20px; border-radius: 8px;">
                <h3 style="font-size: 16px; margin-bottom: 10px; color: #3b82f6;">📰 Presse</h3>
                <a href="mailto:press@smmmaster.com" style="color: #111827; font-weight: 600;">
                    press@smmmaster.com
                </a>
                <p style="color: #6b7280; font-size: 14px; margin-top: 5px;">
                    Médias, interviews, communiqués
                </p>
            </div>
            
            <div style="background: #f9fafb; padding: 20px; border-radius: 8px;">
                <h3 style="font-size: 16px; margin-bottom: 10px; color: #ef4444;"><?php echo getIcon('wallet'); ?> Facturation</h3>
                <a href="mailto:billing@smmmaster.com" style="color: #111827; font-weight: 600;">
                    billing@smmmaster.com
                </a>
                <p style="color: #6b7280; font-size: 14px; margin-top: 5px;">
                    Paiements, remboursements, factures
                </p>
            </div>
            
            <div style="background: #f9fafb; padding: 20px; border-radius: 8px;">
                <h3 style="font-size: 16px; margin-bottom: 10px; color: #6b7280;">⚖️ Légal</h3>
                <a href="mailto:legal@smmmaster.com" style="color: #111827; font-weight: 600;">
                    legal@smmmaster.com
                </a>
                <p style="color: #6b7280; font-size: 14px; margin-top: 5px;">
                    Questions juridiques, RGPD
                </p>
            </div>
            
        </div>
    </div>

    <!-- Horaires -->
    <div class="content-section">
        <h2>⏰ Nos Horaires de Réponse</h2>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead style="background: #f9fafb;">
                <tr>
                    <th style="padding: 15px; text-align: left; border: 1px solid #e5e7eb;">Type de Contact</th>
                    <th style="padding: 15px; text-align: left; border: 1px solid #e5e7eb;">Disponibilité</th>
                    <th style="padding: 15px; text-align: left; border: 1px solid #e5e7eb;">Temps de Réponse</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 15px; border: 1px solid #e5e7eb;"><?php echo getIcon('ticket'); ?> <strong>Support Ticket</strong></td>
                    <td style="padding: 15px; border: 1px solid #e5e7eb;">24/7</td>
                    <td style="padding: 15px; border: 1px solid #e5e7eb;">
                        <span style="color: #10b981; font-weight: 600;">2-6 heures</span>
                    </td>
                </tr>
                <tr style="background: #f9fafb;">
                    <td style="padding: 15px; border: 1px solid #e5e7eb;"><?php echo getIcon('mail'); ?> <strong>Email</strong></td>
                    <td style="padding: 15px; border: 1px solid #e5e7eb;">Lun-Ven 9h-18h</td>
                    <td style="padding: 15px; border: 1px solid #e5e7eb;">
                        <span style="color: #f59e0b; font-weight: 600;">12-24 heures</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 15px; border: 1px solid #e5e7eb;"><?php echo getIcon('support'); ?> <strong>Chat Live</strong></td>
                    <td style="padding: 15px; border: 1px solid #e5e7eb;">Bientôt</td>
                    <td style="padding: 15px; border: 1px solid #e5e7eb;">
                        <span style="color: #6b7280;">Instantané</span>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <p style="margin-top: 20px; color: #6b7280; font-size: 14px;">
            ⏰ <strong>Note :</strong> Les temps de réponse peuvent être plus longs pendant les weekends et jours fériés. 
            Pour les urgences, privilégiez le système de tickets avec priorité "Haute".
        </p>
    </div>

    <!-- FAQ Rapide -->
    <div class="content-section">
        <h2><?php echo getIcon('info'); ?> Questions Fréquentes avant de Contacter</h2>
        <p>Avant de nous contacter, vérifiez si votre question n'a pas déjà une réponse :</p>
        
        <div style="margin-top: 20px;">
            <details style="background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                <summary style="cursor: pointer; font-weight: 600; color: #111827;">
                    Comment passer une commande ?
                </summary>
                <p style="margin-top: 15px; color: #6b7280;">
                    Inscrivez-vous, rechargez votre solde, choisissez un service, entrez le lien et la quantité, 
                    puis confirmez. Simple et rapide ! <a href="faq.php" style="color: #667eea;">Voir le guide complet →</a>
                </p>
            </details>
            
            <details style="background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                <summary style="cursor: pointer; font-weight: 600; color: #111827;">
                    Ma commande est bloquée, que faire ?
                </summary>
                <p style="margin-top: 15px; color: #6b7280;">
                    Vérifiez que votre compte est public, attendez 24h, puis contactez le support avec votre numéro de commande si le problème persiste.
                </p>
            </details>
            
            <details style="background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                <summary style="cursor: pointer; font-weight: 600; color: #111827;">
                    Comment obtenir un remboursement ?
                </summary>
                <p style="margin-top: 15px; color: #6b7280;">
                    Consultez notre <a href="refund.php" style="color: #667eea;">politique de remboursement</a> pour voir si votre cas est éligible, puis créez un ticket.
                </p>
            </details>
            
            <details style="background: #f9fafb; padding: 20px; border-radius: 8px;">
                <summary style="cursor: pointer; font-weight: 600; color: #111827;">
                    Comment ajouter des fonds ?
                </summary>
                <p style="margin-top: 15px; color: #6b7280;">
                    Allez dans "Mon Solde", choisissez PayPal ou Carte Bancaire, entrez le montant (min $5), et confirmez. Crédit instantané !
                </p>
            </details>
        </div>
        
        <p style="margin-top: 30px; text-align: center;">
            <a href="faq.php" class="btn btn-secondary">
                📚 Voir toute la FAQ
            </a>
        </p>
    </div>

    <!-- Informations Légales -->
    <div class="content-section" style="background: #f9fafb;">
        <h2><?php echo getIcon('info'); ?> Informations Légales</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <h3 style="font-size: 16px; margin-bottom: 10px; color: #667eea;">Société</h3>
                <p style="color: #6b7280; margin: 0;">
                    SMM Mastery SAS<br>
                    Capital Social : [MONTANT]<br>
                    SIRET : [NUMÉRO]
                </p>
            </div>
            <div>
                <h3 style="font-size: 16px; margin-bottom: 10px; color: #667eea;">Adresse</h3>
                <p style="color: #6b7280; margin: 0;">
                    [VOTRE ADRESSE]<br>
                    [CODE POSTAL] [VILLE]<br>
                    [PAYS]
                </p>
            </div>
            <div>
                <h3 style="font-size: 16px; margin-bottom: 10px; color: #667eea;">Hébergement</h3>
                <p style="color: #6b7280; margin: 0;">
                    [NOM HÉBERGEUR]<br>
                    [ADRESSE HÉBERGEUR]
                </p>
            </div>
        </div>
    </div>

    <!-- Réseaux Sociaux -->
    <div class="content-section" style="text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h2 style="color: white; border-bottom: none;">🌐 Suivez-nous sur les Réseaux Sociaux</h2>
        <p style="color: white; opacity: 0.9; margin-bottom: 30px;">
            Restez informé de nos nouveautés, promotions et conseils SMM !
        </p>
        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; font-size: 40px;">
            <a href="#" style="color: white; opacity: 0.8; transition: opacity 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'">📘</a>
            <a href="#" style="color: white; opacity: 0.8; transition: opacity 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'">📸</a>
            <a href="#" style="color: white; opacity: 0.8; transition: opacity 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'">🐦</a>
            <a href="#" style="color: white; opacity: 0.8; transition: opacity 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'">💼</a>
            <a href="#" style="color: white; opacity: 0.8; transition: opacity 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'"><?php echo getIcon('tiktok'); ?></a>
        </div>
    </div>

</div>

<?php include '../includes/layout/public-footer.php'; ?>
