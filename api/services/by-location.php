<?php
/**
 * SMM Mastery - API Countries
 * Get services filtered by country/location
 * 
 * Documentation: DOCS_DEV_TO_PROD/04_DEVELOPMENT_GUIDES/
 * Date: 13 Octobre 2025
 * Version: 1.0
 */

session_start();
require_once __DIR__ . '/../../config.php';

// Headers JSON
header('Content-Type: application/json');

// Vérifier authentification
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Authentication required'
    ]);
    exit;
}

try {
    // Use DB constants
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
    // Get location from query param
    $location = isset($_GET['location']) ? trim($_GET['location']) : '';
    
    if (empty($location)) {
        throw new Exception('Location parameter is required');
    }
    
    // Build query based on location
    if ($location === 'all' || $location === 'All Locations') {
        // Return all active services
        $stmt = $pdo->prepare("
            SELECT * FROM services 
            WHERE is_active = 1 
            ORDER BY platform, name
            LIMIT 1000
        ");
        $stmt->execute();
    } else if (strtolower($location) === 'global' || strtolower($location) === 'worldwide') {
        // Match exact "Global" or "Worldwide" (case-insensitive)
        $stmt = $pdo->prepare("
            SELECT * FROM services 
            WHERE is_active = 1 
            AND (
                LOWER(location) = 'global' 
                OR LOWER(location) = 'worldwide' 
                OR location = ''
            )
            ORDER BY platform, name
        ");
        $stmt->execute();
    } else {
        // Match specific location (case-insensitive, with or without emoji)
        // Use COLLATE for case-insensitive comparison
        $stmt = $pdo->prepare("
            SELECT * FROM services 
            WHERE is_active = 1 
            AND (
                location COLLATE utf8mb4_general_ci = :location
                OR location COLLATE utf8mb4_general_ci LIKE :location_with_space
                OR location COLLATE utf8mb4_general_ci LIKE :location_wildcard
            )
            ORDER BY platform, name
        ");
        
        $stmt->execute([
            ':location' => $location,
            ':location_with_space' => $location . ' %',
            ':location_wildcard' => '%' . $location . '%'
        ]);
    }
    
    $services = $stmt->fetchAll();
    
    // Format response
    echo json_encode([
        'success' => true,
        'location' => $location,
        'total' => count($services),
        'services' => $services
    ], JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
