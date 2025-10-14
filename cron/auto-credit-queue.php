<?php
/**
 * CRON JOB: Traitement Queue Auto-Crédit
 * À exécuter toutes les 10 minutes
 * 
 * Commande crontab:
 * */10 * * * * php /path/to/smm/cron/auto-credit-queue.php
 */

// Charger la configuration
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../api/AutoCreditSystem.php';
require_once __DIR__ . '/../api/SMMFollowsAPI.php';

// Vérifier que le système auto-crédit est activé
if (!AUTO_CREDIT_ENABLED) {
    echo "Auto-credit system is disabled.\n";
    exit;
}

echo "================================================\n";
echo "  AUTO-CREDIT QUEUE PROCESSOR\n";
echo "  " . date('Y-m-d H:i:s') . "\n";
echo "================================================\n\n";

try {
    // Initialiser le système
    $smmfollows = new SMMFollowsAPI();
    $autoCreditSystem = new AutoCreditSystem($pdo, $smmfollows);
    
    // Traiter la queue
    echo "Processing queue...\n";
    $autoCreditSystem->processQueue();
    echo "Queue processing completed.\n\n";
    
    // Statistiques
    $stmt = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed
        FROM auto_credit_queue
    ");
    
    $stats = $stmt->fetch();
    
    echo "Queue Statistics:\n";
    echo "- Total items: " . $stats['total'] . "\n";
    echo "- Pending: " . $stats['pending'] . "\n";
    echo "- Completed: " . $stats['completed'] . "\n";
    echo "- Failed: " . $stats['failed'] . "\n\n";
    
    echo "✅ CRON job completed successfully.\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    
    // Logger l'erreur
    logError("Auto-credit queue CRON error: " . $e->getMessage(), 'cron-errors.log');
    
    // Envoyer alerte email
    mail(
        AUTO_CREDIT_ALERT_EMAIL,
        "⚠️ Auto-Credit Queue CRON Error",
        "Error occurred while processing auto-credit queue:\n\n" . $e->getMessage() . "\n\nTime: " . date('Y-m-d H:i:s'),
        "From: " . NO_REPLY_EMAIL
    );
    
    exit(1);
}

echo "================================================\n";
?>
