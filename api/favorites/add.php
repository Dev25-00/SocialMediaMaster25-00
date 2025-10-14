<?php
/**
 * SMM Mastery - API: Add Favorite
 * Date: 13 Octobre 2025
 * Version: 1.0
 * Endpoint: POST /api/favorites/add.php
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
    
    // Récupérer données POST
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['service_id'])) {
        throw new Exception('Service ID is required');
    }
    
    $user_id = $_SESSION['user_id'];
    $service_id = (int)$data['service_id'];
    
    // Vérifier que le service existe
    $stmt = $pdo->prepare("SELECT id, name FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch();
    
    if (!$service) {
        throw new Exception('Service not found');
    }
    
    // Ajouter aux favoris (ignore si existe déjà grâce à UNIQUE KEY)
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO user_favorites (user_id, service_id)
        VALUES (?, ?)
    ");
    $stmt->execute([$user_id, $service_id]);
    
    // Vérifier si ajouté ou déjà existant
    if ($pdo->lastInsertId() > 0) {
        // Compter total favoris
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_favorites WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $total = $stmt->fetchColumn();
        
        echo json_encode([
            'success' => true,
            'message' => 'Service added to favorites',
            'service' => [
                'id' => $service['id'],
                'name' => $service['name']
            ],
            'total_favorites' => $total
        ]);
    } else {
        // Déjà en favoris
        echo json_encode([
            'success' => true,
            'message' => 'Service already in favorites',
            'already_exists' => true
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
