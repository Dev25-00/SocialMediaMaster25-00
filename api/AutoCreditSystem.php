<?php
/**
 * SYSTÈME INTELLIGENT D'AUTO-CRÉDIT
 * Gère le crédit automatique du compte SMMFollows lors des commandes
 * 
 * Principe:
 * 1. Client paie → Solde crédité sur SMM Mastery
 * 2. Client passe commande → Système calcule coût fournisseur
 * 3. Système crédite automatiquement SMMFollows (via PayPal/Stripe/Crypto)
 * 4. Commande passée chez fournisseur
 * 5. Si échec crédit → Queue + Retry + Alerte admin
 * 
 * @author SMM Mastery Team
 * @version 2.0
 * @date 12 Octobre 2025
 */

class AutoCreditSystem {
    
    private $pdo;
    private $smmfollows;
    private $log_file = 'auto-credit.log';
    
    public function __construct($pdo, $smmfollows = null) {
        $this->pdo = $pdo;
        $this->smmfollows = $smmfollows ?? new SMMFollowsAPI();
    }
    
    /**
     * Traiter une commande avec auto-crédit
     * 
     * @param int $order_id ID de la commande
     * @param array $orderOptions Options supplémentaires (ex: ['runs' => int, 'interval' => int] pour dripfeed)
     * @return array ['success' => bool, 'message' => string, 'order_data' => array]
     */
    public function processOrder($order_id, array $orderOptions = []) {
        $this->log("Processing order #$order_id with auto-credit system");
        
        try {
            // 1. Récupérer les détails de la commande
            $order = $this->getOrderDetails($order_id);
            if (!$order) {
                throw new Exception("Order not found");
            }
            
            // 2. Vérifier le solde actuel de SMMFollows
            $provider_balance = $this->smmfollows->getBalance();
            $required_amount = $order['cost_amount'];
            
            $this->log("Provider balance: $$provider_balance, Required: $$required_amount");
            
            // 3. Si solde insuffisant → selon mode auto-crédit
            if ($provider_balance < $required_amount) {
                $mode = $this->getAutoCreditMode();

                // Mode queue_only: ne tente pas de crédit, met en file d'attente et alerte
                if ($mode === 'queue_only') {
                    $this->addToQueue($order_id, $required_amount);
                    $this->sendAlertEmail('queued_due_to_low_balance', $order_id, 'Insufficient provider balance');
                    return [
                        'success' => false,
                        'message' => 'Provider balance too low. Order queued for later processing.',
                        'queued' => true
                    ];
                }

                // Créditer un montant équivalent au coût de la commande (minimum $10 requis par le fournisseur)
                $credit_needed = max(10.0, (float)$required_amount);
                // Arrondir proprement à 2 décimales
                $credit_needed = round($credit_needed + 0.00001, 2);
                $this->log("Insufficient balance. Crediting $" . number_format($credit_needed, 2));
                
                $credit_result = $this->creditProviderAccount($credit_needed, $order_id);
                
                if (!$credit_result['success']) {
                    // Échec du crédit → Mettre en queue
                    $this->addToQueue($order_id, $credit_needed);
                    $this->sendAlertEmail('credit_failed', $order_id, $credit_result['error']);
                    
                    return [
                        'success' => false,
                        'message' => 'Unable to credit provider account. Order queued for retry.',
                        'queued' => true
                    ];
                }
                
                $this->log("Successfully credited $$credit_needed to provider");
            }
            
            // 4. Passer la commande chez le fournisseur (prend en compte dripfeed si présent)
            $provider_order = $this->placeProviderOrder($order, $orderOptions);
            
            if (!$provider_order['success']) {
                throw new Exception("Failed to place order with provider: " . $provider_order['error']);
            }
            
            // 5. Mettre à jour la commande
            $this->updateOrderStatus($order_id, 'processing', $provider_order['order_id']);
            
            // 6. Envoyer email de confirmation au client
            $this->sendOrderConfirmationEmail($order);
            
            $this->log("Order #$order_id successfully processed");
            
            return [
                'success' => true,
                'message' => 'Order placed successfully',
                'provider_order_id' => $provider_order['order_id'],
                'order_data' => $order
            ];
            
        } catch (Exception $e) {
            $this->log("ERROR processing order #$order_id: " . $e->getMessage());
            $this->sendAlertEmail('order_error', $order_id, $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Créditer le compte fournisseur automatiquement
     * 
     * @param float $amount Montant à créditer
     * @param int $order_id ID de la commande liée
     * @return array ['success' => bool, 'error' => string]
     */
    private function creditProviderAccount($amount, $order_id) {
        $this->log("Attempting to credit provider account: $$amount");
        
        try {
            // Récupérer la méthode de paiement préférée depuis settings
            $payment_method = $this->getPreferredPaymentMethod();
            
            switch ($payment_method) {
                case 'paypal':
                    $result = $this->creditViaPayPal($amount, $order_id);
                    break;
                    
                case 'stripe':
                    $result = $this->creditViaStripe($amount, $order_id);
                    break;
                    
                case 'crypto':
                    $result = $this->creditViaCrypto($amount, $order_id);
                    break;
                    
                default:
                    throw new Exception("Invalid payment method: $payment_method");
            }
            
            if ($result['success']) {
                // Logger la transaction
                $this->logCreditTransaction($amount, $payment_method, $order_id, $result['transaction_id']);
            }
            
            return $result;
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Crédit via PayPal Payouts API
     */
    private function creditViaPayPal($amount, $order_id) {
        $this->log("Crediting via PayPal: $$amount");
        
        try {
            // Configuration PayPal
            $mode = PAYPAL_MODE; // 'sandbox' ou 'live'
            $client_id = PAYPAL_CLIENT_ID;
            $secret = PAYPAL_SECRET;
            
            // Récupérer email PayPal du fournisseur depuis settings
            $provider_paypal = $this->getProviderPayPalEmail();
            
            if (!$provider_paypal) {
                throw new Exception("Provider PayPal email not configured");
            }
            
            // Obtenir access token
            $auth_url = $mode === 'live' 
                ? 'https://api-m.paypal.com/v1/oauth2/token'
                : 'https://api-m.sandbox.paypal.com/v1/oauth2/token';
            
            $ch = curl_init($auth_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "$client_id:$secret");
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
            
            $auth_response = json_decode(curl_exec($ch), true);
            curl_close($ch);
            
            if (!isset($auth_response['access_token'])) {
                throw new Exception("Failed to get PayPal access token");
            }
            
            $access_token = $auth_response['access_token'];
            
            // Créer payout
            $payout_url = $mode === 'live'
                ? 'https://api-m.paypal.com/v1/payments/payouts'
                : 'https://api-m.sandbox.paypal.com/v1/payments/payouts';
            
            $payout_data = [
                'sender_batch_header' => [
                    'sender_batch_id' => 'SMM_' . $order_id . '_' . time(),
                    'email_subject' => 'SMM Mastery - Credit for order #' . $order_id,
                    'email_message' => 'You have received a payment from SMM Mastery'
                ],
                'items' => [
                    [
                        'recipient_type' => 'EMAIL',
                        'amount' => [
                            'value' => number_format($amount, 2, '.', ''),
                            'currency' => 'USD'
                        ],
                        'receiver' => $provider_paypal,
                        'note' => 'SMM Mastery auto-credit for order #' . $order_id,
                        'sender_item_id' => 'order_' . $order_id
                    ]
                ]
            ];
            
            $ch = curl_init($payout_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payout_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $access_token
            ]);
            
            $payout_response = json_decode(curl_exec($ch), true);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($http_code !== 201) {
                throw new Exception("PayPal payout failed: " . json_encode($payout_response));
            }
            
            $this->log("PayPal credit successful. Batch ID: " . $payout_response['batch_header']['payout_batch_id']);
            
            return [
                'success' => true,
                'transaction_id' => $payout_response['batch_header']['payout_batch_id'],
                'method' => 'paypal'
            ];
            
        } catch (Exception $e) {
            $this->log("PayPal credit error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Crédit via Stripe Transfers/Payouts
     */
    private function creditViaStripe($amount, $order_id) {
        $this->log("Crediting via Stripe: $$amount");
        
        try {
            require_once __DIR__ . '/../vendor/stripe/stripe-php/init.php';
            
            \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
            
            // Récupérer Stripe account ID du fournisseur
            $provider_stripe_account = $this->getProviderStripeAccount();
            
            if (!$provider_stripe_account) {
                throw new Exception("Provider Stripe account not configured");
            }
            
            // Créer un transfer
            $transfer = \Stripe\Transfer::create([
                'amount' => round($amount * 100), // Stripe utilise centimes
                'currency' => 'usd',
                'destination' => $provider_stripe_account,
                'description' => 'SMM Mastery auto-credit for order #' . $order_id,
                'metadata' => [
                    'order_id' => $order_id,
                    'type' => 'auto_credit'
                ]
            ]);
            
            $this->log("Stripe credit successful. Transfer ID: " . $transfer->id);
            
            return [
                'success' => true,
                'transaction_id' => $transfer->id,
                'method' => 'stripe'
            ];
            
        } catch (Exception $e) {
            $this->log("Stripe credit error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Crédit via Crypto (Bitcoin/USDT)
     */
    private function creditViaCrypto($amount, $order_id) {
        $this->log("Crediting via Crypto: $$amount");
        
        // TODO: Implémenter selon le wallet crypto utilisé
        // Exemple: CoinPayments, BTCPay, etc.
        
        return [
            'success' => false,
            'error' => 'Crypto payments not yet implemented'
        ];
    }
    
    /**
     * Passer la commande chez le fournisseur
     */
    private function placeProviderOrder($order, array $orderOptions = []) {
        try {
            // Déterminer options dripfeed: priorité aux options passées, sinon à celles stockées sur la commande
            $options = [];
            if (!empty($orderOptions)) {
                if (isset($orderOptions['runs']) && isset($orderOptions['interval'])) {
                    $options['runs'] = (int)$orderOptions['runs'];
                    $options['interval'] = (int)$orderOptions['interval'];
                }
            } else {
                if (!empty($order['dripfeed']) && !empty($order['dripfeed_runs']) && !empty($order['dripfeed_interval'])) {
                    $options['runs'] = (int)$order['dripfeed_runs'];
                    $options['interval'] = (int)$order['dripfeed_interval'];
                }
            }

            $result = $this->smmfollows->createOrder(
                (int)$order['provider_service_id'],
                (string)$order['link'],
                (int)$order['quantity'],
                $options
            );
            
            // SMMFollows renvoie { order: <id> } en cas de succès
            if (isset($result['order'])) {
                return [
                    'success' => true,
                    'order_id' => $result['order']
                ];
            }
            
            // Si pas d'ID renvoyé, construire message d'erreur
            $err = isset($result['error']) ? $result['error'] : 'Unknown provider response';
            throw new Exception($err);
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Ajouter une commande à la queue de retry
     */
    private function addToQueue($order_id, $amount_needed) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO auto_credit_queue 
                (order_id, amount_needed, attempts, status, created_at, next_retry)
                VALUES (?, ?, 0, 'pending', NOW(), DATE_ADD(NOW(), INTERVAL ? SECOND))
            ");
            
            $stmt->execute([
                $order_id,
                $amount_needed,
                AUTO_CREDIT_RETRY_DELAY
            ]);
            
            $this->log("Order #$order_id added to retry queue");
            
        } catch (PDOException $e) {
            $this->log("Error adding to queue: " . $e->getMessage());
        }
    }
    
    /**
     * Traiter la queue de retry (à appeler via CRON)
     */
    public function processQueue() {
        $this->log("Processing auto-credit queue");
        
        try {
            // Récupérer les commandes en attente
            $stmt = $this->pdo->query("
                SELECT * FROM auto_credit_queue
                WHERE status = 'pending' 
                AND attempts < " . AUTO_CREDIT_MAX_RETRIES . "
                AND next_retry <= NOW()
                ORDER BY created_at ASC
                LIMIT 10
            ");
            
            $queue_items = $stmt->fetchAll();
            
            foreach ($queue_items as $item) {
                $this->log("Retrying order #" . $item['order_id']);
                
                // Incrémenter le nombre de tentatives
                $this->pdo->prepare("
                    UPDATE auto_credit_queue 
                    SET attempts = attempts + 1,
                        last_attempt = NOW()
                    WHERE id = ?
                ")->execute([$item['id']]);
                
                $mode = $this->getAutoCreditMode();
                if ($mode === 'queue_only') {
                    // En mode queue_only, ne pas tenter de crédit. Vérifier si le solde suffit maintenant.
                    $provider_balance = $this->smmfollows->getBalance();
                    $order = $this->getOrderDetails($item['order_id']);
                    $required_amount = $order ? (float)$order['cost_amount'] : (float)$item['amount_needed'];
                    if ($provider_balance >= $required_amount) {
                        // Passer la commande
                        $place = $this->placeProviderOrder($order);
                        if ($place['success']) {
                            $this->pdo->prepare("
                                UPDATE auto_credit_queue 
                                SET status = 'completed', completed_at = NOW()
                                WHERE id = ?
                            ")->execute([$item['id']]);
                            $this->updateOrderStatus($item['order_id'], 'processing', $place['order_id']);
                            $this->log("Queue-only: order #" . $item['order_id'] . " placed successfully after manual top-up");
                            continue;
                        } else {
                            // Si échec de placement, replanifier
                            $this->pdo->prepare("
                                UPDATE auto_credit_queue 
                                SET next_retry = DATE_ADD(NOW(), INTERVAL ? SECOND)
                                WHERE id = ?
                            ")->execute([AUTO_CREDIT_RETRY_DELAY, $item['id']]);
                            $this->log("Queue-only: provider placement failed: " . $place['error']);
                            continue;
                        }
                    } else {
                        // Solde toujours insuffisant → replanifier
                        $this->pdo->prepare("
                            UPDATE auto_credit_queue 
                            SET next_retry = DATE_ADD(NOW(), INTERVAL ? SECOND)
                            WHERE id = ?
                        ")->execute([AUTO_CREDIT_RETRY_DELAY, $item['id']]);
                        continue;
                    }
                }
                
                // Réessayer le crédit (mode normal)
                $result = $this->creditProviderAccount($item['amount_needed'], $item['order_id']);
                
                if ($result['success']) {
                    // Succès → Marquer comme complété et traiter la commande
                    $this->pdo->prepare("
                        UPDATE auto_credit_queue 
                        SET status = 'completed', completed_at = NOW()
                        WHERE id = ?
                    ")->execute([$item['id']]);
                    
                    // Traiter la commande
                    $this->processOrder($item['order_id']);
                    
                } else {
                    // Échec → Planifier prochain retry
                    if ($item['attempts'] + 1 >= AUTO_CREDIT_MAX_RETRIES) {
                        // Max retries atteint → Marquer comme failed
                        $this->pdo->prepare("
                            UPDATE auto_credit_queue 
                            SET status = 'failed'
                            WHERE id = ?
                        ")->execute([$item['id']]);
                        
                        $this->sendAlertEmail('max_retries_reached', $item['order_id'], $result['error']);
                    } else {
                        // Planifier prochain retry
                        $this->pdo->prepare("
                            UPDATE auto_credit_queue 
                            SET next_retry = DATE_ADD(NOW(), INTERVAL ? SECOND)
                            WHERE id = ?
                        ")->execute([AUTO_CREDIT_RETRY_DELAY, $item['id']]);
                    }
                }
            }
            
            $this->log("Queue processing completed");
            
        } catch (Exception $e) {
            $this->log("Error processing queue: " . $e->getMessage());
        }
    }
    
    /**
     * Envoyer email d'alerte admin
     */
    private function sendAlertEmail($type, $order_id, $error) {
        $to = AUTO_CREDIT_ALERT_EMAIL;
        $subject = "⚠️ SMM Mastery - Auto-Credit Alert";
        
        $messages = [
            'credit_failed' => "Failed to credit provider account for order #$order_id.\nError: $error",
            'order_error' => "Error processing order #$order_id.\nError: $error",
            'max_retries_reached' => "Max retries reached for order #$order_id.\nLast error: $error",
            'queued_due_to_low_balance' => "Order #$order_id queued due to low provider balance.\nAction required: Add funds to SMMFollows, the system will retry automatically."
        ];
        
        $body = $messages[$type] ?? "Unknown error type: $type";
        $body .= "\n\nPlease check the auto-credit logs for more details.";
        $body .= "\nTime: " . date('Y-m-d H:i:s');
        
        mail($to, $subject, $body, "From: " . NO_REPLY_EMAIL);
        
        $this->log("Alert email sent to $to");
    }
    
    /**
     * Envoyer email de confirmation au client
     */
    private function sendOrderConfirmationEmail($order) {
        if (!SEND_ORDER_CONFIRMATION) return;
        
        // TODO: Implémenter avec template HTML
        $this->log("Sending order confirmation email for order #" . $order['id']);
    }
    
    /**
     * Récupérer les détails d'une commande
     */
    private function getOrderDetails($order_id) {
        $stmt = $this->pdo->prepare("
            SELECT o.*, s.provider_id as provider_service_id, u.email as user_email
            FROM orders o
            JOIN services s ON o.service_id = s.id
            JOIN users u ON o.user_id = u.id
            WHERE o.id = ?
        ");
        $stmt->execute([$order_id]);
        return $stmt->fetch();
    }
    
    /**
     * Mettre à jour le statut d'une commande
     */
    private function updateOrderStatus($order_id, $status, $provider_order_id = null) {
        $sql = "UPDATE orders SET status = ?";
        $params = [$status];
        
        if ($provider_order_id) {
            $sql .= ", provider_order_id = ?";
            $params[] = $provider_order_id;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $order_id;
        
        $this->pdo->prepare($sql)->execute($params);
    }
    
    /**
     * Logger une transaction de crédit
     */
    private function logCreditTransaction($amount, $method, $order_id, $transaction_id) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO provider_credit_log 
                (amount, payment_method, order_id, transaction_id, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$amount, $method, $order_id, $transaction_id]);
        } catch (PDOException $e) {
            $this->log("Error logging credit transaction: " . $e->getMessage());
        }
    }

    /**
     * Lire le mode auto-crédit: 'normal' (par défaut) ou 'queue_only'
     */
    private function getAutoCreditMode() {
        try {
            $stmt = $this->pdo->prepare("SELECT key_value FROM settings WHERE key_name = 'auto_credit_mode'");
            $stmt->execute();
            $mode = $stmt->fetchColumn();
            return in_array($mode, ['queue_only', 'normal']) ? $mode : 'normal';
        } catch (PDOException $e) {
            return 'normal';
        }
    }
    
    /**
     * Récupérer méthode de paiement préférée
     */
    private function getPreferredPaymentMethod() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT key_value FROM settings 
                WHERE key_name = 'auto_credit_payment_method'
            ");
            $stmt->execute();
            return $stmt->fetchColumn() ?: 'paypal';
        } catch (PDOException $e) {
            return 'paypal';
        }
    }
    
    /**
     * Récupérer email PayPal du fournisseur
     */
    private function getProviderPayPalEmail() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT key_value FROM settings 
                WHERE key_name = 'provider_paypal_email'
            ");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Récupérer compte Stripe du fournisseur
     */
    private function getProviderStripeAccount() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT key_value FROM settings 
                WHERE key_name = 'provider_stripe_account'
            ");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Logger un message
     */
    private function log($message) {
        if (LOG_AUTO_CREDIT) {
            $log = "[" . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL;
            error_log($log, 3, LOGS_PATH . '/' . $this->log_file);
        }
    }
}
