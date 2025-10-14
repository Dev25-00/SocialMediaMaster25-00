<?php
/**
 * SMM Mastery - Migration: Create User Favorites Table
 * Date: 13 Octobre 2025
 * Version: 1.0
 */

// Configuration DB directe
define('DB_HOST', 'localhost');
define('DB_NAME', 'smm_master');
define('DB_USER', 'root');
define('DB_PASS', 'root');

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
$username = DB_USER;
$password = DB_PASS;
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

echo "🔧 SMM Mastery - Migration User Favorites\n";
echo "=========================================\n\n";

try {
    // Connexion à la base de données
    $pdo = new PDO($dsn, $username, $password, $options);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données établie\n\n";
    
    // Lire le fichier SQL
    $sql = file_get_contents(__DIR__ . '/create_user_favorites.sql');
    
    // Exécuter la requête
    $pdo->exec($sql);
    
    echo "✅ Table 'user_favorites' créée avec succès!\n\n";
    
    // Vérifier la structure
    $stmt = $pdo->query("DESCRIBE user_favorites");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📋 Structure de la table:\n";
    echo "-------------------------\n";
    foreach ($columns as $column) {
        echo sprintf(
            "  - %-15s %-20s %s\n",
            $column['Field'],
            $column['Type'],
            $column['Key'] ? "({$column['Key']})" : ''
        );
    }
    
    echo "\n✅ Migration terminée avec succès!\n";
    echo "\n📊 Statistiques:\n";
    echo "  - Table: user_favorites\n";
    echo "  - Colonnes: " . count($columns) . "\n";
    echo "  - Indexes: 2 (user_favorites, user_order)\n";
    echo "  - Contrainte unique: user_id + service_id\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
