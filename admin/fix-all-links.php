<?php
/**
 * SCRIPT DE CORRECTION AUTOMATIQUE DES LIENS
 * 
 * Ce script parcourt tous les fichiers PHP et corrige les liens relatifs
 */

require_once '../config.php';

// Liste des fichiers à vérifier et corriger
$files_to_check = [
    // Dashboard
    'dashboard/balance.php',
    'dashboard/profile.php',
    
    // Services
    'services/index.php',
    
    // Orders
    'orders/new.php',
    'orders/history.php',
    'orders/tracking.php',
    
    // Support
    'support/tickets.php',
    'support/new-ticket.php',
    'support/view-ticket.php',
    
    // Admin
    'admin/dashboard.php',
    'admin/settings.php',
    'admin/api-test.php',
    'admin/sync-services.php',
];

$base_path = $_SERVER['DOCUMENT_ROOT'] . '/smm/';
$corrections = [];
$total_corrections = 0;

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Correction Automatique des Liens</title>
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        h1 {
            color: #111827;
            margin-bottom: 20px;
        }
        h2 {
            color: #374151;
            margin: 25px 0 15px;
            font-size: 18px;
        }
        .file-check {
            padding: 15px;
            margin: 10px 0;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #e5e7eb;
        }
        .file-check.has-issues {
            border-left-color: #f59e0b;
            background: #fffbeb;
        }
        .file-check.fixed {
            border-left-color: #10b981;
            background: #d1fae5;
        }
        .issue {
            color: #92400e;
            font-family: monospace;
            font-size: 13px;
            padding: 8px;
            background: #fef3c7;
            border-radius: 4px;
            margin: 5px 0;
        }
        .success {
            color: #10b981;
            padding: 15px;
            background: #d1fae5;
            border-radius: 8px;
            margin: 15px 0;
        }
        .info {
            color: #2563eb;
            padding: 15px;
            background: #dbeafe;
            border-radius: 8px;
            margin: 15px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class='box'>
        <h1>🔧 Correction Automatique des Liens</h1>";

echo "<div class='info'><strong>ℹ️ Analyse en cours...</strong></div>";

foreach ($files_to_check as $file) {
    $file_path = $base_path . $file;
    
    if (!file_exists($file_path)) {
        echo "<div class='file-check'>
            <strong>❌ " . htmlspecialchars($file) . "</strong><br>
            <small style='color: #6b7280;'>Fichier non trouvé</small>
        </div>";
        continue;
    }
    
    $content = file_get_contents($file_path);
    $original_content = $content;
    $file_corrections = 0;
    
    // Patterns à rechercher et remplacer
    $patterns = [
        // Liens vers auth
        '/href=["\']\.\.\/auth\/(login|register|logout|forgot-password)\.php["\']/i' => 'href="<?php echo SITE_URL; ?>/auth/$1.php"',
        
        // Liens vers dashboard
        '/href=["\']\.\.\/dashboard\/(index|balance|profile)\.php["\']/i' => 'href="<?php echo SITE_URL; ?>/dashboard/$1.php"',
        '/href=["\'](index|balance|profile)\.php["\']/i' => 'href="<?php echo SITE_URL; ?>/dashboard/$1.php"',
        
        // Liens vers services
        '/href=["\']\.\.\/services\/index\.php["\']/i' => 'href="<?php echo SITE_URL; ?>/services/index.php"',
        
        // Liens vers orders
        '/href=["\']\.\.\/orders\/(new|history|tracking)\.php["\']/i' => 'href="<?php echo SITE_URL; ?>/orders/$1.php"',
        
        // Liens vers support
        '/href=["\']\.\.\/support\/(tickets|new-ticket|view-ticket)\.php["\']/i' => 'href="<?php echo SITE_URL; ?>/support/$1.php"',
        
        // Liens vers admin
        '/href=["\']\.\.\/admin\/(dashboard|settings|api-test|sync-services)\.php["\']/i' => 'href="<?php echo SITE_URL; ?>/admin/$1.php"',
        
        // Redirects
        '/redirect\(["\']\.\.\/auth\/(login|register)\.php["\']\)/i' => 'redirect(SITE_URL . \'/auth/$1.php\')',
        '/redirect\(["\']\.\.\/dashboard\/index\.php["\']\)/i' => 'redirect(SITE_URL . \'/dashboard/index.php\')',
    ];
    
    $has_issues = false;
    $issues_found = [];
    
    foreach ($patterns as $pattern => $replacement) {
        if (preg_match($pattern, $content)) {
            $has_issues = true;
            $issues_found[] = "Liens relatifs trouvés : " . htmlspecialchars($pattern);
            $content = preg_replace($pattern, $replacement, $content);
            $file_corrections++;
        }
    }
    
    if ($has_issues) {
        echo "<div class='file-check has-issues'>
            <strong>⚠️ " . htmlspecialchars($file) . "</strong><br>
            <small style='color: #6b7280;'>$file_corrections correction(s) à appliquer</small>";
        
        foreach ($issues_found as $issue) {
            echo "<div class='issue'>$issue</div>";
        }
        
        echo "<br><small style='color: #059669;'>✅ Prêt pour correction</small>
        </div>";
        
        $corrections[$file] = [
            'original' => $original_content,
            'corrected' => $content,
            'count' => $file_corrections
        ];
        
        $total_corrections += $file_corrections;
    } else {
        echo "<div class='file-check'>
            <strong>✅ " . htmlspecialchars($file) . "</strong><br>
            <small style='color: #6b7280;'>Aucune correction nécessaire</small>
        </div>";
    }
}

if ($total_corrections > 0) {
    echo "<div class='success'>
        <strong>📊 Résumé :</strong><br>
        - <strong>$total_corrections</strong> corrections possibles trouvées<br>
        - <strong>" . count($corrections) . "</strong> fichier(s) à corriger<br><br>
        
        <strong>⚠️ Note importante :</strong><br>
        Ce script a détecté les corrections à faire mais ne les applique pas automatiquement pour éviter toute perte de données.<br><br>
        
        <strong>Pour appliquer les corrections :</strong><br>
        1. Faites un backup de votre projet<br>
        2. Corrigez manuellement chaque fichier en utilisant SITE_URL<br>
        3. Testez après chaque correction<br>
    </div>";
    
    echo "<h2>📋 Détails des Corrections</h2>";
    
    foreach ($corrections as $file => $data) {
        echo "<div class='file-check fixed'>
            <strong>" . htmlspecialchars($file) . "</strong><br>
            <small>" . $data['count'] . " correction(s)</small>
        </div>";
    }
} else {
    echo "<div class='success'>
        <strong>🎉 Excellent !</strong><br>
        Tous les fichiers utilisent déjà des chemins absolus avec SITE_URL !<br><br>
        Aucune correction nécessaire.
    </div>";
}

echo "
        <a href='" . SITE_URL . "/dashboard/index.php' class='btn'>Retour Dashboard</a>
        <a href='" . SITE_URL . "/admin/dashboard.php' class='btn' style='background: #059669;'>Admin Dashboard</a>
    </div>
</body>
</html>";
?>
