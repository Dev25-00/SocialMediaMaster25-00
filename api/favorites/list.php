<?php
/**
 * SMM Mastery - API: List Favorites
 * Date: 13 Octobre 2025
 * Version: 1.0
 * Endpoint: GET /api/favorites/list.php
 */

// Désactiver les warnings pour éviter de polluer le JSON
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '0');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

// Vérifier authentification
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized - Please login'
    ]);
    exit;
}

require_once __DIR__ . '/../../config.php';

try {
    // Connexion DB
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
    $user_id = $_SESSION['user_id'];
    
    // Récupérer les favoris avec infos services complètes
    $stmt = $pdo->prepare("
        SELECT 
            uf.id as favorite_id,
            uf.added_at,
            uf.notes,
            s.*
        FROM user_favorites uf
        INNER JOIN services s ON s.id = uf.service_id
        WHERE uf.user_id = ?
        ORDER BY uf.added_at DESC
    ");
    $stmt->execute([$user_id]);
    $favorites = $stmt->fetchAll();
    
    // Formater les favoris
    $formatted = array_map(function($fav) {
        return [
            'favorite_id' => $fav['favorite_id'],
            'added_at' => $fav['added_at'],
            'notes' => $fav['notes'],
            'service' => [
                'id' => $fav['id'],
                'provider_id' => $fav['provider_id'],
                'platform' => $fav['platform'],
                'name' => $fav['name'],
                'description' => $fav['description'],
                'location' => $fav['location'],
                'price' => (float)$fav['sell_price'],
                'min_quantity' => (int)$fav['min_quantity'],
                'max_quantity' => (int)$fav['max_quantity'],
                'tier' => $fav['tier'],
                'quality' => $fav['quality'],
                'refill_days' => $fav['refill_days'],
                'refill_type' => $fav['refill_type'],
                'drop_rate' => $fav['drop_rate'],
                'speed' => $fav['speed'],
                'average_time' => $fav['average_time'],
                'dripfeed' => $fav['dripfeed'] == 1,
                'cancel' => $fav['cancel'] == 1
            ]
        ];
    }, $favorites);
    
    echo json_encode([
        'success' => true,
        'total' => count($formatted),
        'favorites' => $formatted
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
