<?php
/**
 * SMM Mastery - Fonctions Utilitaires
 * Version: 1.0
 */

// Empêcher l'accès direct
if (!defined('DB_HOST')) {
    die('Accès non autorisé');
}

// Charger la configuration des icônes (Font Awesome)
require_once __DIR__ . '/includes/icons-config.php';

/**
 * Vérifier si l'utilisateur est connecté
 */
/*function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}*/

/**
 * Vérifier si l'utilisateur est admin
 */
/*function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Rediriger vers une page
 *//*
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Obtenir l'utilisateur actuel
 */
function getCurrentUser($pdo) {
    if (!isLoggedIn()) {
        return null;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

/**
 * Nettoyer et sécuriser une chaîne
 */
function clean($string) {
    return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
}

/**
 * Générer un token CSRF
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifier un token CSRF
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Générer un numéro de commande unique
 */
function generateOrderNumber() {
    return 'ORD-' . strtoupper(bin2hex(random_bytes(6)));
}

/**
 * Générer une clé API unique
 */
function generateAPIKey() {
    return bin2hex(random_bytes(32));
}

/**
 * Formater un montant en devise
 */
function formatCurrency($amount, $currency = 'USD') {
    $symbols = [
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£'
    ];
    
    $symbol = $symbols[$currency] ?? '$';
    return $symbol . number_format($amount, 2);
}

/**
 * Envoyer un email
 */
function sendEmail($to, $subject, $message) {
    $headers = "From: " . SITE_EMAIL . "\r\n";
    $headers .= "Reply-To: " . SITE_EMAIL . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $message, $headers);
}

/**
 * Logger une action
 */
function logAction($pdo, $user_id, $action, $details = '') {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO logs (user_id, action, details, ip_address, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([
            $user_id,
            $action,
            $details,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    } catch (PDOException $e) {
        // Table logs n'existe pas encore - ignorer l'erreur
        return false;
    }
}

/**
 * Valider un email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valider un mot de passe
 */
function isValidPassword($password) {
    // Min 8 caractères, 1 majuscule, 1 chiffre
    return strlen($password) >= 8 
        && preg_match('/[A-Z]/', $password) 
        && preg_match('/[0-9]/', $password);
}

/**
 * Valider une URL
 */
function isValidURL($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Obtenir les paramètres du site
 */
function getSiteSettings($pdo) {
    static $settings = null;
    
    if ($settings === null) {
        $stmt = $pdo->query("SELECT key_name, key_value FROM settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['key_name']] = $row['key_value'];
        }
    }
    
    return $settings;
}

/**
 * Obtenir une valeur de paramètre
 */
function getSetting($pdo, $key, $default = '') {
    $settings = getSiteSettings($pdo);
    return $settings[$key] ?? $default;
}

/**
 * Mettre à jour un paramètre
 */
function updateSetting($pdo, $key, $value) {
    $stmt = $pdo->prepare("
        INSERT INTO settings (key_name, key_value, updated_at) 
        VALUES (?, ?, NOW())
        ON DUPLICATE KEY UPDATE key_value = ?, updated_at = NOW()
    ");
    return $stmt->execute([$key, $value, $value]);
}

/**
 * Ajouter une transaction
 */
function addTransaction($pdo, $user_id, $type, $amount, $payment_method = null, $description = '') {
    // Obtenir le solde actuel
    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $balance_before = $stmt->fetchColumn() ?: 0;
    
    // Calculer le nouveau solde
    if (in_array($type, ['deposit', 'refund', 'bonus'])) {
        $balance_after = $balance_before + $amount;
    } else {
        $balance_after = $balance_before - $amount;
    }
    
    // Insérer la transaction
    $stmt = $pdo->prepare("
        INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, payment_method, description, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $user_id,
        $type,
        $amount,
        $balance_before,
        $balance_after,
        $payment_method,
        $description
    ]);
    
    // Mettre à jour le solde de l'utilisateur
    $stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
    $stmt->execute([$balance_after, $user_id]);
    
    return $pdo->lastInsertId();
}

/**
 * Obtenir les statistiques utilisateur
 */
function getUserStats($pdo, $user_id) {
    $stats = [];
    
    // Total dépensé
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(sell_amount), 0) as total_spent
        FROM orders 
        WHERE user_id = ?
    ");
    $stmt->execute([$user_id]);
    $stats['total_spent'] = $stmt->fetchColumn();
    
    // Nombre de commandes
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $stats['total_orders'] = $stmt->fetchColumn();
    
    // Commandes en cours
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM orders 
        WHERE user_id = ? AND status IN ('pending', 'processing')
    ");
    $stmt->execute([$user_id]);
    $stats['pending_orders'] = $stmt->fetchColumn();
    
    // Commandes complétées
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM orders 
        WHERE user_id = ? AND status = 'completed'
    ");
    $stmt->execute([$user_id]);
    $stats['completed_orders'] = $stmt->fetchColumn();
    
    return $stats;
}

/**
 * Formater une date
 */
function formatDate($date, $format = 'd/m/Y H:i') {
    if (empty($date)) return '-';
    return date($format, strtotime($date));
}

/**
 * Obtenir le temps écoulé
 */
function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return "Il y a " . $diff . " seconde" . ($diff > 1 ? 's' : '');
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return "Il y a " . $minutes . " minute" . ($minutes > 1 ? 's' : '');
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return "Il y a " . $hours . " heure" . ($hours > 1 ? 's' : '');
    } elseif ($diff < 2592000) {
        $days = floor($diff / 86400);
        return "Il y a " . $days . " jour" . ($days > 1 ? 's' : '');
    } else {
        return formatDate($datetime);
    }
}

/**
 * Obtenir le badge de statut
 */
function getStatusBadge($status) {
    $badges = [
        'pending' => '<span class="badge badge-warning">En attente</span>',
        'processing' => '<span class="badge badge-info">En cours</span>',
        'completed' => '<span class="badge badge-success">Terminé</span>',
        'partial' => '<span class="badge badge-warning">Partiel</span>',
        'canceled' => '<span class="badge badge-secondary">Annulé</span>',
        'refunded' => '<span class="badge badge-danger">Remboursé</span>',
        'active' => '<span class="badge badge-success">Actif</span>',
        'suspended' => '<span class="badge badge-warning">Suspendu</span>',
        'banned' => '<span class="badge badge-danger">Banni</span>',
    ];
    
    return $badges[$status] ?? '<span class="badge badge-secondary">' . ucfirst($status) . '</span>';
}

/**
 * Obtenir le badge de tier (utilise maintenant Font Awesome)
 */
function getTierBadge($tier) {
    // Charger les icônes si pas déjà fait
    if (!function_exists('tierBadge')) {
        require_once __DIR__ . '/includes/icons-config.php';
    }
    return tierBadge($tier);
}

/**
 * Paginer des résultats
 */
function paginate($pdo, $query, $params, $page, $per_page = 20) {
    $offset = ($page - 1) * $per_page;
    
    // Compter le nombre total
    $count_query = preg_replace('/SELECT .* FROM/i', 'SELECT COUNT(*) FROM', $query);
    $stmt = $pdo->prepare($count_query);
    $stmt->execute($params);
    $total = $stmt->fetchColumn();
    
    // Obtenir les résultats de la page
    $query .= " LIMIT $per_page OFFSET $offset";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll();
    
    return [
        'results' => $results,
        'total' => $total,
        'page' => $page,
        'per_page' => $per_page,
        'total_pages' => ceil($total / $per_page)
    ];
}

/**
 * Afficher les boutons de pagination
 */
function renderPagination($current_page, $total_pages, $url) {
    if ($total_pages <= 1) return '';
    
    $html = '<div class="pagination">';
    
    // Bouton précédent
    if ($current_page > 1) {
        $html .= '<a href="' . $url . '?page=' . ($current_page - 1) . '" class="btn btn-sm">← Précédent</a>';
    }
    
    // Pages
    for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++) {
        $active = $i == $current_page ? 'active' : '';
        $html .= '<a href="' . $url . '?page=' . $i . '" class="btn btn-sm ' . $active . '">' . $i . '</a>';
    }
    
    // Bouton suivant
    if ($current_page < $total_pages) {
        $html .= '<a href="' . $url . '?page=' . ($current_page + 1) . '" class="btn btn-sm">Suivant →</a>';
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Générer un slug à partir d'un texte
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    
    return empty($text) ? 'n-a' : $text;
}

/**
 * Afficher un message flash
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type, // success, error, warning, info
        'message' => $message
    ];
}

/**
 * Obtenir et supprimer le message flash
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

/**
 * Afficher le HTML du message flash
 */
function renderFlashMessage() {
    $flash = getFlashMessage();
    if ($flash) {
        $type = $flash['type'];
        $message = $flash['message'];
        return "
        <div class='alert alert-$type' role='alert'>
            $message
            <button type='button' class='close' onclick='this.parentElement.remove()'>×</button>
        </div>
        ";
    }
    return '';
}
?>
