<?php
/**
 * API Services V2 - Chargement avec TOUTES les métadonnées
 * Date: 13 Octobre 2025
 * Version: 2.0
 * 
 * NOUVEAUTÉS V2:
 * - Inclut quality, location, speed, average_time
 * - Inclut dripfeed, cancel, api_category, refill_type
 * - Filtres avancés par quality et location
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database connection not available'
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
$search_id = isset($_GET['search_id']) ? trim($_GET['search_id']) : ''; // 🔍 Recherche exacte par ID
$action_type = isset($_GET['action_type']) ? trim($_GET['action_type']) : '';
$drop_rate = isset($_GET['drop_rate']) ? trim($_GET['drop_rate']) : '';
$refill_days = isset($_GET['refill_days']) ? trim($_GET['refill_days']) : '';
$price_min = isset($_GET['price_min']) ? floatval($_GET['price_min']) : null;
$price_max = isset($_GET['price_max']) ? floatval($_GET['price_max']) : null;
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'price-asc';

// ✅ NOUVEAUX FILTRES V2
$quality = isset($_GET['quality']) ? trim($_GET['quality']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$dripfeed = isset($_GET['dripfeed']) ? trim($_GET['dripfeed']) : '';
$cancel = isset($_GET['cancel']) ? trim($_GET['cancel']) : '';

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
    
    // 🔍 Recherche EXACTE par ID de service (provider_id)
    if (!empty($search_id)) {
        $where_conditions[] = "provider_id = :search_id";
        $params[':search_id'] = $search_id;
    }
    
    if (!empty($action_type)) {
        $where_conditions[] = "(category LIKE :action_type OR name LIKE :action_type_name)";
        $params[':action_type'] = '%' . $action_type . '%';
        $params[':action_type_name'] = '%' . $action_type . '%';
    }
    
    if (!empty($drop_rate)) {
        $where_conditions[] = "(
            LOWER(drop_rate) LIKE :drop_rate OR 
            REPLACE(LOWER(drop_rate), ' ', '') LIKE :drop_rate_nospace
        )";
        $params[':drop_rate'] = '%' . strtolower($drop_rate) . '%';
        $params[':drop_rate_nospace'] = '%' . str_replace(' ', '', strtolower($drop_rate)) . '%';
    }
    
    if (!empty($refill_days)) {
        if ($refill_days === '0') {
            $where_conditions[] = "(refill_days IS NULL OR refill_days = 0)";
        } elseif ($refill_days === 'lifetime') {
            $where_conditions[] = "(refill_days >= 365 OR LOWER(refill_type) LIKE '%lifetime%')";
        } elseif ($refill_days === '30') {
            $where_conditions[] = "(refill_days > 0 AND refill_days <= 30)";
        } elseif ($refill_days === '90') {
            $where_conditions[] = "(refill_days > 30 AND refill_days <= 90)";
        } elseif ($refill_days === '365') {
            $where_conditions[] = "(refill_days > 90 AND refill_days < 365)";
        }
    }
    
    // ✅ NOUVEAU: Filtre par Quality
    if (!empty($quality)) {
        $where_conditions[] = "LOWER(quality) = :quality";
        $params[':quality'] = strtolower($quality);
    }
    
    // ✅ NOUVEAU: Filtre par Location
    if (!empty($location)) {
        $where_conditions[] = "LOWER(location) LIKE :location";
        $params[':location'] = '%' . strtolower($location) . '%';
    }
    
    // ✅ NOUVEAU: Filtre Dripfeed uniquement
    if ($dripfeed === '1' || $dripfeed === 'true') {
        $where_conditions[] = "dripfeed = 1";
    }
    
    // ✅ NOUVEAU: Filtre Cancel disponible
    if ($cancel === '1' || $cancel === 'true') {
        $where_conditions[] = "cancel = 1";
    }
    
    if ($price_min !== null) {
        $where_conditions[] = "sell_price >= :price_min";
        $params[':price_min'] = $price_min;
    }
    
    if ($price_max !== null) {
        $where_conditions[] = "sell_price <= :price_max";
        $params[':price_max'] = $price_max;
    }
    
    $where_clause = implode(' AND ', $where_conditions);
    
    // Déterminer l'ordre
    $order_clause = match($sort) {
        'price-asc' => 'sell_price ASC',
        'price-desc' => 'sell_price DESC',
        'name-asc' => 'name ASC',
        'name-desc' => 'name DESC',
        'popular' => 'platform ASC, tier ASC',
        default => 'sell_price ASC'
    };
    
    // ✅ SELECT avec TOUTES les nouvelles colonnes
    $query = "
        SELECT 
            id,
            provider_id,
            platform,
            name,
            description,
            category,
            api_category,
            tier,
            quality,
            location,
            min_quantity,
            max_quantity,
            cost_price,
            sell_price,
            drop_rate,
            refill_days,
            refill_type,
            speed,
            average_time,
            dripfeed,
            cancel
        FROM services
        WHERE {$where_clause}
        ORDER BY {$order_clause}
        LIMIT :limit OFFSET :offset
    ";
    
    $stmt = $pdo->prepare($query);
    
    // Bind pagination params
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    // Bind filter params
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Compter le total pour pagination
    $count_query = "SELECT COUNT(*) FROM services WHERE {$where_clause}";
    $count_stmt = $pdo->prepare($count_query);
    foreach ($params as $key => $value) {
        $count_stmt->bindValue($key, $value);
    }
    $count_stmt->execute();
    $total = $count_stmt->fetchColumn();
    
    // Formater les services pour le frontend
    $formatted_services = array_map(function($service) {
        return [
            'id' => (int)$service['id'],
            'provider_id' => (int)$service['provider_id'],
            'platform' => $service['platform'],
            'name' => $service['name'],
            'description' => $service['description'],
            'category' => $service['category'],
            'api_category' => $service['api_category'], // ✅ NOUVEAU
            'tier' => $service['tier'],
            'quality' => $service['quality'], // ✅ NOUVEAU
            'location' => $service['location'], // ✅ NOUVEAU
            'min_quantity' => (int)$service['min_quantity'],
            'max_quantity' => (int)$service['max_quantity'],
            'price' => floatval($service['sell_price']),
            'drop_rate' => $service['drop_rate'],
            'refill_days' => $service['refill_days'] ? (int)$service['refill_days'] : null,
            'refill_type' => $service['refill_type'], // ✅ NOUVEAU
            'speed' => $service['speed'], // ✅ NOUVEAU
            'average_time' => $service['average_time'], // ✅ NOUVEAU
            'dripfeed' => (bool)$service['dripfeed'], // ✅ NOUVEAU
            'cancel' => (bool)$service['cancel'], // ✅ NOUVEAU
        ];
    }, $services);
    
    $total_pages = ceil($total / $per_page);
    
    echo json_encode([
        'success' => true,
        'services' => $formatted_services,
        'pagination' => [
            'page' => $page,
            'per_page' => $per_page,
            'total' => (int)$total,
            'total_pages' => $total_pages,
            'has_more' => ($page < $total_pages) // ✅ AJOUT has_more
        ],
        'filters_applied' => [
            'platform' => $platform ?: null,
            'tier' => $tier ?: null,
            'quality' => $quality ?: null, // ✅ NOUVEAU
            'location' => $location ?: null, // ✅ NOUVEAU
            'dripfeed' => $dripfeed ? true : null, // ✅ NOUVEAU
            'cancel' => $cancel ? true : null, // ✅ NOUVEAU
            'search' => $search ?: null,
            'sort' => $sort
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}
?>
