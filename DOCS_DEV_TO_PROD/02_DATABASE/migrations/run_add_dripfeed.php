<?php
/**
 * SMM Mastery - Migration: Add Drip-feed support
 * Date: 13 Octobre 2025
 */

require_once __DIR__ . '/../../../config.php';

echo "=== MIGRATION: Add Drip-feed to Orders ===\n\n";

try {
    // Check if columns already exist
    $check = $pdo->query("SHOW COLUMNS FROM orders LIKE 'dripfeed'")->fetch();
    
    if ($check) {
        echo "✓ Drip-feed columns already exist.\n";
        exit(0);
    }
    
    echo "Adding dripfeed column...\n";
    $pdo->exec("ALTER TABLE orders ADD COLUMN dripfeed TINYINT(1) DEFAULT 0 COMMENT 'Enable drip-feed delivery' AFTER notes");
    echo "✓ Done\n";
    
    echo "Adding dripfeed_runs column...\n";
    $pdo->exec("ALTER TABLE orders ADD COLUMN dripfeed_runs INT DEFAULT NULL COMMENT 'Number of batches for drip-feed' AFTER dripfeed");
    echo "✓ Done\n";
    
    echo "Adding dripfeed_interval column...\n";
    $pdo->exec("ALTER TABLE orders ADD COLUMN dripfeed_interval INT DEFAULT NULL COMMENT 'Minutes between batches' AFTER dripfeed_runs");
    echo "✓ Done\n";
    
    echo "Creating index...\n";
    $pdo->exec("CREATE INDEX idx_orders_dripfeed ON orders (dripfeed, status)");
    echo "✓ Done\n";
    
    echo "\n✅ Migration completed successfully!\n";
    
} catch (PDOException $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
