<?php
/**
 * SMM Mastery - API: Remove Favorite
 * Date: 13 Octobre 2025
 * Version: 1.0
 * Endpoint: DELETE /api/favorites/remove.php
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
    
    // Récupérer données (support POST et DELETE methods)
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['service_id'])) {
        throw new Exception('Service ID is required');
    }
    
    $user_id = $_SESSION['user_id'];
    $service_id = (int)$data['service_id'];
    
    // Supprimer des favoris
    $stmt = $pdo->prepare("
        DELETE FROM user_favorites
        WHERE user_id = ? AND service_id = ?
    ");
    $stmt->execute([$user_id, $service_id]);
    
    if ($stmt->rowCount() > 0) {
        // Compter total favoris restants
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_favorites WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $total = $stmt->fetchColumn();
        
        echo json_encode([
            'success' => true,
            'message' => 'Service removed from favorites',
            'total_favorites' => $total
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Service was not in favorites'
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
