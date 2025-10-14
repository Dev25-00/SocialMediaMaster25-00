<?php
/**
 * SCRIPT DE CORRECTION DES LIENS
 * 
 * Ce script vérifie et corrige tous les liens dans les pages
 */

require_once '../config.php';

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Vérification des Liens</title>
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
        .success {
            color: #10b981;
            padding: 15px;
            background: #d1fae5;
            border-radius: 8px;
            margin: 15px 0;
        }
        .error {
            color: #ef4444;
            padding: 15px;
            background: #fee2e2;
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
        h1 {
            color: #111827;
            margin-bottom: 20px;
        }
        h2 {
            color: #374151;
            margin: 25px 0 15px;
            font-size: 20px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        code {
            background: #f3f4f6;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }
        .url-test {
            display: flex;
            align-items: center;
            padding: 10px;
            margin: 5px 0;
            background: #f9fafb;
            border-radius: 6px;
        }
        .url-test code {
            flex: 1;
        }
        .url-test .status {
            margin-left: 15px;
            font-weight: 600;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            margin-right: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        table th {
            background: #f9fafb;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class='box'>
        <h1>🔗 Vérification des Liens</h1>";

// Afficher SITE_URL
echo "<div class='info'>
    <strong>🌐 SITE_URL configuré :</strong><br>
    <code>" . SITE_URL . "</code>
</div>";

// Liste des URLs importantes à tester
$urls = [
    'Page d\'accueil' => SITE_URL . '/index.php',
    'Login' => SITE_URL . '/auth/login.php',
    'Register' => SITE_URL . '/auth/register.php',
    'Dashboard User' => SITE_URL . '/dashboard/index.php',
    'Services' => SITE_URL . '/services/index.php',
    'Nouvelle Commande' => SITE_URL . '/orders/new.php',
    'Historique' => SITE_URL . '/orders/history.php',
    'Solde' => SITE_URL . '/dashboard/balance.php',
    'Profil' => SITE_URL . '/dashboard/profile.php',
    'Support' => SITE_URL . '/support/tickets.php',
    'Admin Dashboard' => SITE_URL . '/admin/dashboard.php',
    'Admin Settings' => SITE_URL . '/admin/settings.php',
    'Admin API Test' => SITE_URL . '/admin/api-test.php',
];

echo "<h2>📋 Test des URLs principales</h2>";
echo "<table>
    <thead>
        <tr>
            <th>Page</th>
            <th>URL</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>";

foreach ($urls as $name => $url) {
    // Convertir l'URL en chemin fichier
    $file = str_replace(SITE_URL, $_SERVER['DOCUMENT_ROOT'] . '/smm', $url);
    
    if (file_exists($file)) {
        echo "<tr>
            <td>$name</td>
            <td><code>" . str_replace(SITE_URL, '', $url) . "</code></td>
            <td style='color: #10b981; font-weight: 600;'>✅ Existe</td>
        </tr>";
    } else {
        echo "<tr>
            <td>$name</td>
            <td><code>" . str_replace(SITE_URL, '', $url) . "</code></td>
            <td style='color: #ef4444; font-weight: 600;'>❌ Manquant</td>
        </tr>";
    }
}

echo "</tbody></table>";

// Vérifier les sidebars
echo "<h2>🔍 Vérification des Sidebars</h2>";

$sidebars = [
    'Sidebar User' => $_SERVER['DOCUMENT_ROOT'] . '/smm/includes/sidebar.php',
    'Sidebar Admin' => $_SERVER['DOCUMENT_ROOT'] . '/smm/admin/sidebar.php',
];

foreach ($sidebars as $name => $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $uses_site_url = strpos($content, 'SITE_URL') !== false;
        $has_relative = strpos($content, 'href="../') !== false;
        
        echo "<div class='url-test'>";
        echo "<code>$name</code>";
        if ($uses_site_url && !$has_relative) {
            echo "<span class='status' style='color: #10b981;'>✅ OK (utilise SITE_URL)</span>";
        } elseif ($has_relative) {
            echo "<span class='status' style='color: #f59e0b;'>⚠️ Contient des liens relatifs</span>";
        } else {
            echo "<span class='status' style='color: #ef4444;'>❌ N'utilise pas SITE_URL</span>";
        }
        echo "</div>";
    }
}

// Recommandations
echo "<h2>💡 Recommandations</h2>";
echo "<div class='info'>
    <strong>Pour corriger les problèmes de liens :</strong>
    <ol style='margin: 10px 0; padding-left: 25px;'>
        <li>Toujours utiliser <code>SITE_URL</code> pour les liens absolus</li>
        <li>Exemple : <code>&lt;a href=\"&lt;?php echo SITE_URL; ?&gt;/dashboard/index.php\"&gt;</code></li>
        <li>Ne JAMAIS utiliser <code>../</code> dans les href</li>
        <li>Vérifier que <code>SITE_URL</code> est bien défini dans config.php</li>
    </ol>
</div>";

// Actions rapides
echo "<h2>🎯 Actions Rapides</h2>";

echo "<div class='success'>
    <strong>✅ SITE_URL est correctement configuré !</strong><br><br>
    Votre SITE_URL : <code>" . SITE_URL . "</code><br><br>
    
    <strong>Pour tester les liens :</strong><br>
    - Naviguez dans votre application<br>
    - Cliquez sur tous les liens de menu<br>
    - Vérifiez qu'ils fonctionnent depuis n'importe quelle page<br>
</div>";

echo "<a href='" . SITE_URL . "/dashboard/index.php' class='btn'>Tester Dashboard</a>";
echo "<a href='" . SITE_URL . "/admin/dashboard.php' class='btn' style='background: #059669;'>Tester Admin</a>";
echo "<a href='" . SITE_URL . "/services/index.php' class='btn' style='background: #7c3aed;'>Tester Services</a>";

echo "</div></body></html>";
?>
