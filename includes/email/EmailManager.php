<?php
/**
 * GESTIONNAIRE D'EMAILS
 * Gère l'envoi d'emails avec templates HTML
 * 
 * @author SMM Mastery Team
 * @version 2.0
 */

class EmailManager {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Envoyer un email en utilisant un template
     * 
     * @param string $template_key Clé du template
     * @param string $recipient_email Email du destinataire
     * @param array $variables Variables à remplacer dans le template
     * @param int $user_id ID de l'utilisateur (optionnel)
     * @return bool Success
     */
    public function sendTemplateEmail($template_key, $recipient_email, $variables = [], $user_id = null) {
        try {
            // Récupérer le template
            $template = $this->getTemplate($template_key);
            
            if (!$template) {
                throw new Exception("Template not found: $template_key");
            }
            
            if (!$template['is_active']) {
                throw new Exception("Template is inactive: $template_key");
            }
            
            // Remplacer les variables
            $subject = $this->replaceVariables($template['subject'], $variables);
            $body_html = $this->replaceVariables($template['body_html'], $variables);
            $body_text = $this->replaceVariables($template['body_text'], $variables);
            
            // Wrapper HTML
            $html_email = $this->wrapHTMLEmail($body_html, $subject);
            
            // Envoyer l'email
            $result = $this->sendEmail($recipient_email, $subject, $html_email, $body_text);
            
            // Logger
            $this->logEmail($user_id, $template_key, $recipient_email, $subject, $result ? 'sent' : 'failed');
            
            return $result;
            
        } catch (Exception $e) {
            $this->logEmail($user_id, $template_key, $recipient_email, '', 'failed', $e->getMessage());
            return false;
        }
    }
    
    /**
     * Envoyer un email simple
     */
    private function sendEmail($to, $subject, $body_html, $body_text = '') {
        // Headers
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . SMTP_FROM_NAME . ' <' . SITE_EMAIL . '>',
            'Reply-To: ' . SUPPORT_EMAIL,
            'X-Mailer: PHP/' . phpversion()
        ];
        
        // Utiliser SMTP si configuré, sinon mail() par défaut
        if (defined('SMTP_HOST') && SMTP_HOST) {
            return $this->sendSMTP($to, $subject, $body_html, $body_text);
        } else {
            return mail($to, $subject, $body_html, implode("\r\n", $headers));
        }
    }
    
    /**
     * Envoyer via SMTP (PHPMailer recommandé en production)
     */
    private function sendSMTP($to, $subject, $body_html, $body_text) {
        // TODO: Intégrer PHPMailer pour SMTP
        // Pour l'instant, utiliser mail() natif
        
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . SMTP_FROM_NAME . ' <' . SITE_EMAIL . '>',
            'Reply-To: ' . SUPPORT_EMAIL
        ];
        
        return mail($to, $subject, $body_html, implode("\r\n", $headers));
    }
    
    /**
     * Récupérer un template depuis la BDD
     */
    private function getTemplate($template_key) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM email_templates 
            WHERE template_key = ?
        ");
        $stmt->execute([$template_key]);
        return $stmt->fetch();
    }
    
    /**
     * Remplacer les variables dans le texte
     */
    private function replaceVariables($text, $variables) {
        foreach ($variables as $key => $value) {
            $text = str_replace('{{' . $key . '}}', $value, $text);
        }
        return $text;
    }
    
    /**
     * Wrapper HTML pour email professionnel
     */
    private function wrapHTMLEmail($content, $title) {
        $logo_url = SITE_URL . '/assets/images/logo.png'; // À créer
        
        return '
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . htmlspecialchars($title) . '</title>
            <style>
                body {
                    font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                    line-height: 1.6;
                    color: #374151;
                    background-color: #f3f4f6;
                    margin: 0;
                    padding: 0;
                }
                .email-container {
                    max-width: 600px;
                    margin: 40px auto;
                    background: white;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }
                .email-header {
                    background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
                    padding: 30px;
                    text-align: center;
                }
                .email-logo {
                    font-size: 32px;
                    color: white;
                    font-weight: 800;
                    margin: 0;
                }
                .email-body {
                    padding: 40px 30px;
                }
                .email-body h1 {
                    color: #111827;
                    font-size: 24px;
                    margin-top: 0;
                }
                .email-body h2 {
                    color: #1f2937;
                    font-size: 20px;
                    margin-top: 30px;
                }
                .email-body p {
                    margin: 15px 0;
                }
                .email-body a {
                    color: #2563eb;
                    text-decoration: none;
                }
                .email-button {
                    display: inline-block;
                    padding: 12px 30px;
                    background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
                    color: white !important;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 600;
                    margin: 20px 0;
                }
                .email-footer {
                    background: #f9fafb;
                    padding: 30px;
                    text-align: center;
                    font-size: 14px;
                    color: #6b7280;
                    border-top: 1px solid #e5e7eb;
                }
                .email-footer a {
                    color: #2563eb;
                    text-decoration: none;
                }
                ul {
                    list-style: none;
                    padding: 0;
                }
                li {
                    padding: 8px 0;
                    border-bottom: 1px solid #f3f4f6;
                }
                li:last-child {
                    border-bottom: none;
                }
                strong {
                    color: #111827;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="email-header">
                    <h1 class="email-logo">🚀 SMM Mastery</h1>
                </div>
                <div class="email-body">
                    ' . $content . '
                </div>
                <div class="email-footer">
                    <p>
                        <strong>SMM Mastery</strong><br>
                        Votre partenaire pour une croissance sociale authentique
                    </p>
                    <p style="margin-top: 20px;">
                        <a href="' . SITE_URL . '">Visiter le site</a> • 
                        <a href="' . SITE_URL . '/support/tickets.php">Support</a> • 
                        <a href="' . SITE_URL . '/pages/contact.php">Contact</a>
                    </p>
                    <p style="margin-top: 20px; font-size: 12px; color: #9ca3af;">
                        Cet email a été envoyé par SMM Mastery. Si vous ne souhaitez plus recevoir nos emails, 
                        <a href="' . SITE_URL . '/dashboard/profile.php">gérez vos préférences</a>.
                    </p>
                </div>
            </div>
        </body>
        </html>
        ';
    }
    
    /**
     * Logger un email envoyé
     */
    private function logEmail($user_id, $template_key, $recipient_email, $subject, $status, $error = null) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO email_logs 
                (user_id, template_key, recipient_email, subject, status, error_message, sent_at, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $user_id,
                $template_key,
                $recipient_email,
                $subject,
                $status,
                $error,
                $status === 'sent' ? date('Y-m-d H:i:s') : null
            ]);
        } catch (PDOException $e) {
            // Silencieux pour ne pas bloquer l'envoi
        }
    }
    
    /**
     * HELPERS: Emails prédéfinis
     */
    
    public function sendWelcomeEmail($user) {
        return $this->sendTemplateEmail('welcome', $user['email'], [
            'username' => $user['username'],
            'email' => $user['email'],
            'dashboard_url' => SITE_URL . '/dashboard/index.php'
        ], $user['id']);
    }
    
    public function sendOrderConfirmation($order, $service, $user) {
        return $this->sendTemplateEmail('order_confirmation', $user['email'], [
            'order_number' => $order['order_number'],
            'service_name' => $service['name'],
            'quantity' => number_format($order['quantity']),
            'amount' => number_format($order['sell_amount'], 2),
            'link' => $order['link'],
            'tracking_url' => SITE_URL . '/orders/tracking.php?id=' . $order['id']
        ], $user['id']);
    }
    
    public function sendOrderProcessing($order, $user) {
        return $this->sendTemplateEmail('order_processing', $user['email'], [
            'order_number' => $order['order_number'],
            'estimated_time' => $order['estimated_time'] ?? '24-72 heures',
            'tracking_url' => SITE_URL . '/orders/tracking.php?id=' . $order['id']
        ], $user['id']);
    }
    
    public function sendOrderCompleted($order, $user) {
        return $this->sendTemplateEmail('order_completed', $user['email'], [
            'order_number' => $order['order_number'],
            'delivered_count' => $order['quantity'] - $order['remains'],
            'quantity' => $order['quantity']
        ], $user['id']);
    }
    
    public function sendDepositConfirmation($amount, $bonus, $new_balance, $user) {
        return $this->sendTemplateEmail('deposit_confirmation', $user['email'], [
            'amount' => number_format($amount, 2),
            'bonus' => number_format($bonus, 2),
            'new_balance' => number_format($new_balance, 2)
        ], $user['id']);
    }
}
?>
