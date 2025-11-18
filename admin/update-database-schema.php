<?php
/**
 * SMM Mastery - Mise à jour du schéma BDD
 * Date: 13 Octobre 2025
 * Version: 1.0
 * 
 * AJOUT DES COLONNES MANQUANTES POUR SYNCHRONISATION API COMPLÈTE
 * Documentation: DOCS_DEV_TO_PROD\05_FIXES_PATCHES\database\
 */

session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

// Vérification admin
if (!isset($_SESSION['user_id']) || !isAdmin()) {
    die("Accès refusé");
}

$success = [];
$errors = [];

// 1. DRIPFEED (Boolean) - Livraison progressive disponible
try {
    $pdo->exec("ALTER TABLE services ADD COLUMN dripfeed BOOLEAN DEFAULT 0 AFTER is_active");
    $success[] = "✅ Colonne 'dripfeed' ajoutée";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column") !== false || strpos($e->getMessage(), "déjà utilisé") !== false) {
        $success[] = "ℹ️ Colonne 'dripfeed' existe déjà";
    } else {
        $errors[] = "❌ Erreur dripfeed: " . $e->getMessage();
    }
}

// 2. CANCEL (Boolean) - Annulation possible
try {
    $pdo->exec("ALTER TABLE services ADD COLUMN cancel BOOLEAN DEFAULT 0 AFTER dripfeed");
    $success[] = "✅ Colonne 'cancel' ajoutée";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column") !== false || strpos($e->getMessage(), "déjà utilisé") !== false) {
        $success[] = "ℹ️ Colonne 'cancel' existe déjà";
    } else {
        $errors[] = "❌ Erreur cancel: " . $e->getMessage();
    }
}

// 3. API_CATEGORY (VARCHAR) - Catégorie originale API
try {
    $pdo->exec("ALTER TABLE services ADD COLUMN api_category VARCHAR(100) DEFAULT NULL AFTER category");
    $success[] = "✅ Colonne 'api_category' ajoutée";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column") !== false || strpos($e->getMessage(), "déjà utilisé") !== false) {
        $success[] = "ℹ️ Colonne 'api_category' existe déjà";
    } else {
        $errors[] = "❌ Erreur api_category: " . $e->getMessage();
    }
}

// 4. REFILL_TYPE (VARCHAR) - Type refill (button, lifetime, guaranteed)
try {
    $pdo->exec("ALTER TABLE services ADD COLUMN refill_type VARCHAR(50) DEFAULT NULL AFTER refill_days");
    $success[] = "✅ Colonne 'refill_type' ajoutée";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column") !== false || strpos($e->getMessage(), "déjà utilisé") !== false) {
        $success[] = "ℹ️ Colonne 'refill_type' existe déjà";
    } else {
        $errors[] = "❌ Erreur refill_type: " . $e->getMessage();
    }
}

// 5. AVERAGE_TIME (VARCHAR) - Temps moyen d'exécution
try {
    $pdo->exec("ALTER TABLE services ADD COLUMN average_time VARCHAR(100) DEFAULT NULL AFTER speed");
    $success[] = "✅ Colonne 'average_time' ajoutée";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column") !== false || strpos($e->getMessage(), "déjà utilisé") !== false) {
        $success[] = "ℹ️ Colonne 'average_time' existe déjà";
    } else {
        $errors[] = "❌ Erreur average_time: " . $e->getMessage();
    }
}

// 6. QUALITY (VARCHAR) - Qualité extraite (High, Real, etc.)
try {
    $pdo->exec("ALTER TABLE services ADD COLUMN quality VARCHAR(50) DEFAULT NULL AFTER tier");
    $success[] = "✅ Colonne 'quality' ajoutée";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column") !== false || strpos($e->getMessage(), "déjà utilisé") !== false) {
        $success[] = "ℹ️ Colonne 'quality' existe déjà";
    } else {
        $errors[] = "❌ Erreur quality: " . $e->getMessage();
    }
}

// 7. LOCATION (VARCHAR) - Localisation (Global, USA, etc.)
try {
    $pdo->exec("ALTER TABLE services ADD COLUMN location VARCHAR(100) DEFAULT NULL AFTER quality");
    $success[] = "✅ Colonne 'location' ajoutée";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column") !== false || strpos($e->getMessage(), "déjà utilisé") !== false) {
        $success[] = "ℹ️ Colonne 'location' existe déjà";
    } else {
        $errors[] = "❌ Erreur location: " . $e->getMessage();
    }
}

// Vérification finale
$stmt = $pdo->query("DESCRIBE services");
$columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour BDD - SMM Mastery</title>
    <link rel="stylesheet" href="../assets/css/global/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard/dashboard.css">
    <link rel="stylesheet" href="../assets/css/global/fixes.css">
    <link rel="stylesheet" href="/smm/assets/css/admin/admin-dashboard.css">
    <style>
        .update-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        }
        .section {
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        .section.success { border-color: #10b981; }
        .section.error { border-color: #ef4444; }
        .section.info { border-color: #3b82f6; }
        .message {
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
        }
        .message.success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }
        .message.error {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        .message.info {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }
        .columns-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }
        .column-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 0.9em;
        }
        .column-badge.new {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .btn-next {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
            margin-top: 20px;
            transition: transform 0.3s;
        }
        .btn-next:hover {
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/sidebar.php'; ?>
    <div class="main-content">
    <div class="update-container">
        <h1 style="text-align: center; color: #fff; margin-bottom: 30px;">
            🗄️ Mise à jour du Schéma BDD
        </h1>

        <?php if (!empty($success)): ?>
        <div class="section success">
            <h2 style="color: #10b981; margin-top: 0;">✅ Succès (<?= count($success) ?>)</h2>
            <?php foreach ($success as $msg): ?>
                <div class="message success"><?= $msg ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
        <div class="section error">
            <h2 style="color: #ef4444; margin-top: 0;">❌ Erreurs (<?= count($errors) ?>)</h2>
            <?php foreach ($errors as $msg): ?>
                <div class="message error"><?= $msg ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="section info">
            <h2 style="color: #3b82f6; margin-top: 0;">📊 Structure finale (<?= count($columns) ?> colonnes)</h2>
            <div class="columns-grid">
                <?php 
                $new_columns = ['dripfeed', 'cancel', 'api_category', 'refill_type', 'average_time', 'quality', 'location'];
                foreach ($columns as $col): 
                    $is_new = in_array($col, $new_columns);
                ?>
                    <div class="column-badge <?= $is_new ? 'new' : '' ?>">
                        <?= $is_new ? '🆕 ' : '' ?><?= htmlspecialchars($col) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (empty($errors)): ?>
        <div style="text-align: center;">
            <a href="sync-services.php" class="btn-next">
                ⏭️ PHASE 2 : Synchroniser les services
            </a>
        </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 20px;">
            <a href="dashboard.php" style="color: #94a3b8;">← Retour au tableau de bord</a>
        </div>
    </div>
    </div>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>
