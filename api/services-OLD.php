<?php
/**
 * API Services - Chargement fractionné
 * Retourne les services en JSON par paquets
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Activer l'affichage des erreurs pour debug
error_reporting(E_ALL);
ini_set('display_errors', 0); // Ne pas afficher, mais logger

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

// Vérifier que PDO est disponible
if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database connection not available',
        'debug' => 'PDO object not found'
    ]);
    exit;
}

// Paramètres de pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 50;
$offset = ($page - 1) * $per_page;

// Paramètres de filtrage
$platform = isset($_GET['platform']) ? trim($_GET['platform']) : '';
$tier = isset($_GET['tier']) ? trim($_GET['tier']) : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$action_type = isset($_GET['action_type']) ? trim($_GET['action_type']) : '';
$drop_rate = isset($_GET['drop_rate']) ? trim($_GET['drop_rate']) : '';
$refill_days = isset($_GET['refill_days']) ? trim($_GET['refill_days']) : '';
$price_min = isset($_GET['price_min']) ? floatval($_GET['price_min']) : null;
$price_max = isset($_GET['price_max']) ? floatval($_GET['price_max']) : null;
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'price-asc';

// Charger les marges depuis config
require_once __DIR__ . '/../config.php';
global $PROFIT_MARGINS;

try {
    // Construire la clause WHERE
    $where_conditions = ["is_active = 1"];
    $params = [];
    
    if (!empty($platform)) {
        $where_conditions[] = "platform = :platform";
        $params[':platform'] = $platform;
    }
    
    if (!empty($tier)) {
        $where_conditions[] = "tier = :tier";
        $params[':tier'] = $tier;
    }
    
    if (!empty($search)) {
        $where_conditions[] = "(name LIKE :search OR description LIKE :search OR category LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    
    // Filtre par type d'action (followers, likes, views, etc.)
    if (!empty($action_type)) {
        $where_conditions[] = "(category LIKE :action_type OR name LIKE :action_type_name)";
        $params[':action_type'] = '%' . $action_type . '%';
        $params[':action_type_name'] = '%' . $action_type . '%';
    }
    
    // Filtre par drop rate
    if (!empty($drop_rate)) {
        // Normaliser le drop rate pour matcher la BDD
        $where_conditions[] = "(
            LOWER(drop_rate) LIKE :drop_rate OR 
            REPLACE(LOWER(drop_rate), ' ', '') LIKE :drop_rate_nospace
        )";
        $params[':drop_rate'] = '%' . strtolower($drop_rate) . '%';
        $params[':drop_rate_nospace'] = '%' . str_replace(' ', '', strtolower($drop_rate)) . '%';
    }
    
    // Filtre par refill days
    if (!empty($refill_days)) {
        if ($refill_days === '0') {
            // Sans refill (NULL ou 0)
            $where_conditions[] = "(refill_days IS NULL OR refill_days = 0)";
        } elseif ($refill_days === 'lifetime') {
            // Lifetime (>= 365 jours ou valeur 'lifetime')
            $where_conditions[] = "(refill_days >= 365 OR LOWER(refill_days) = 'lifetime')";
        } elseif ($refill_days === '30') {
            // 1-30 jours
            $where_conditions[] = "(refill_days > 0 AND refill_days <= 30)";
        } elseif ($refill_days === '90') {
            // 30-90 jours
            $where_conditions[] = "(refill_days > 30 AND refill_days <= 90)";
        } elseif ($refill_days === '365') {
            // 90-365 jours
            $where_conditions[] = "(refill_days > 90 AND refill_days < 365)";
        } else {
            // Valeur spécifique >= X jours
            $where_conditions[] = "refill_days >= :refill_days";
            $params[':refill_days'] = intval($refill_days);
        }
    }
    
    // Filtre par prix minimum (APRÈS application de la marge)
    // On filtrera côté PHP après calcul de la marge
    
    $where_clause = implode(' AND ', $where_conditions);
    
    // Déterminer l'ORDER BY selon le tri demandé
    $order_by = "tier ASC, sell_price ASC"; // Par défaut
    switch ($sort) {
        case 'price-asc':
            $order_by = "sell_price ASC, name ASC";
            break;
        case 'price-desc':
            $order_by = "sell_price DESC, name ASC";
            break;
        case 'name-asc':
            $order_by = "name ASC, sell_price ASC";
            break;
        case 'name-desc':
            $order_by = "name DESC, sell_price ASC";
            break;
        default:
            // Par défaut: prix croissant
            $order_by = "sell_price ASC, name ASC";
            break;
    }
    
    // Compter le total avec filtres
    $count_query = "SELECT COUNT(*) as total FROM services WHERE " . $where_clause;
    $count_stmt = $pdo->prepare($count_query);
    foreach ($params as $key => $value) {
        $count_stmt->bindValue($key, $value);
    }
    $count_stmt->execute();
    $total = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Récupérer TOUS les services avec filtres (sans LIMIT) pour calculer le vrai total après filtrage prix
    // On doit faire ça car le filtrage prix se fait après la requête SQL
    $query_all = "SELECT 
                id, 
                platform, 
                tier, 
                category, 
                name, 
                description, 
                sell_price, 
                min_quantity, 
                max_quantity, 
                drop_rate, 
                refill_days
              FROM services 
              WHERE " . $where_clause . "
              ORDER BY " . $order_by;
    
    $stmt_all = $pdo->prepare($query_all);
    foreach ($params as $key => $value) {
        $stmt_all->bindValue($key, $value);
    }
    $stmt_all->execute();
    $all_services = $stmt_all->fetchAll(PDO::FETCH_ASSOC);
    
    // Appliquer les marges et filtrer par prix sur TOUS les services
    $all_services_filtered = [];
    foreach ($all_services as $service) {
        $tier = strtolower($service['tier']);
        $margin = $PROFIT_MARGINS[$tier] ?? 1.0;
        
        // Calculer le prix de vente avec marge
        $original_price = floatval($service['sell_price']);
        $final_price = $original_price * $margin;
        
        // Filtrer par prix min/max
        if ($price_min !== null && $final_price < $price_min) {
            continue;
        }
        if ($price_max !== null && $final_price > $price_max) {
            continue;
        }
        
        // Ajouter le prix final au service
        $service['original_price'] = $original_price;
        $service['sell_price'] = $final_price;
        $service['profit_margin'] = $margin;
        
        $all_services_filtered[] = $service;
    }
    
    // Calculer le VRAI total après filtrage
    $total_after_price_filter = count($all_services_filtered);
    
    // Appliquer la pagination sur les services filtrés
    $services_with_margin = array_slice($all_services_filtered, $offset, $per_page);
    
    // Calculer les vraies pages
    $total_pages = ceil($total_after_price_filter / $per_page);
    $has_more = $page < $total_pages;
    
    // Retourner la réponse JSON
    echo json_encode([
        'success' => true,
        'data' => $services_with_margin,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $per_page,
            'total' => $total_after_price_filter,  // Vrai total après filtrage
            'total_pages' => $total_pages,
            'has_more' => $has_more,
            'returned' => count($services_with_margin)  // Nombre réellement retourné
        ]
    ], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors du chargement des services',
        'message' => $e->getMessage()
    ]);
}
