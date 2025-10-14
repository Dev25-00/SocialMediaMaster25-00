<?php
/**
 * HEADER DASHBOARD SIMPLE - Compatible avec dashboard.css
 */

// Sécurité: Vérifier la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . '/auth/login.php');
    exit;
}

// Charger config icônes
require_once __DIR__ . '/icons-config.php';

// Informations utilisateur
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';
$email = $_SESSION['email'] ?? '';
$role = $_SESSION['role'] ?? 'user';

// Récupérer le solde actuel
try {
    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_balance = $stmt->fetchColumn() ?: 0;
} catch (PDOException $e) {
    $user_balance = 0;
}

// Déterminer la page active pour highlighting navigation
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Dashboard'; ?> - SMM Mastery</title>
    
    <!-- Font Awesome -->
    <?php echo ICON_CDN; ?>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/icons.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/dashboard-responsive.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js (si nécessaire) -->
    <?php if (isset($include_charts) && $include_charts): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php endif; ?>
</head>
<body class="dashboard-page">
    
    <!-- Overlay pour mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <?php include __DIR__ . '/dashboard-sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="main-content">
        
        <!-- Top Bar Minimaliste avec Hamburger intégré -->
        <?php include __DIR__ . '/dashboard-top-bar.php'; ?>
