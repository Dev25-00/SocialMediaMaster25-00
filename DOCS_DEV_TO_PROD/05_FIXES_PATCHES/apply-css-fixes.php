<?php
/**
 * SMM Mastery - Script d'application automatique des corrections CSS/JS
 * Applique fixes.css et mobile-menu.js aux fichiers qui en ont besoin
 */

// Fichiers à corriger (qui ont leur propre structure HTML)
$files_to_fix = [
    'admin/api-test.php',
    'auth/login.php', 
    'auth/register.php',
    'payment/paypal.php',
    'payment/paypal-success.php',
    'payment/stripe.php',
    'payment/test-payment.php'
];

$fixes_applied = 0;
$errors = [];

foreach ($files_to_fix as $file) {
    $full_path = __DIR__ . '/' . $file;
    
    if (!file_exists($full_path)) {
        $errors[] = "Fichier non trouvé: $file";
        continue;
    }
    
    $content = file_get_contents($full_path);
    
    // Vérifier si les corrections sont déjà appliquées
    if (strpos($content, 'fixes.css') !== false && strpos($content, 'mobile-menu.js') !== false) {
        echo "✅ $file - Déjà corrigé\n";
        continue;
    }
    
    $modified = false;
    
    // Ajouter fixes.css si manquant
    if (strpos($content, 'fixes.css') === false) {
        // Chercher la ligne dashboard.css ou main.css pour insérer après
        if (preg_match('/(\s*<link rel="stylesheet" href="[^"]*(?:dashboard|main)\.css">\s*)/m', $content, $matches)) {
            $css_line = $matches[1];
            $fixes_css = str_replace('main.css', 'fixes.css', $css_line);
            $fixes_css = str_replace('dashboard.css', 'fixes.css', $fixes_css);
            $content = str_replace($css_line, $css_line . $fixes_css, $content);
            $modified = true;
        }
    }
    
    // Ajouter mobile-menu.js si manquant  
    if (strpos($content, 'mobile-menu.js') === false) {
        // Chercher main.js pour insérer après
        if (preg_match('/(\s*<script src="[^"]*main\.js"><\/script>\s*)/m', $content, $matches)) {
            $js_line = $matches[1];
            $mobile_js = str_replace('main.js', 'mobile-menu.js', $js_line);
            $content = str_replace($js_line, $js_line . $mobile_js, $content);
            $modified = true;
        }
    }
    
    if ($modified) {
        if (file_put_contents($full_path, $content)) {
            echo "✅ $file - Corrections appliquées\n";
            $fixes_applied++;
        } else {
            $errors[] = "Erreur écriture: $file";
        }
    } else {
        echo "⚠️ $file - Impossible d'appliquer les corrections automatiquement\n";
    }
}

echo "\n📊 RÉSUMÉ:\n";
echo "- Corrections appliquées: $fixes_applied fichiers\n";
echo "- Erreurs: " . count($errors) . "\n";

if (!empty($errors)) {
    echo "\n❌ ERREURS:\n";
    foreach ($errors as $error) {
        echo "- $error\n";
    }
}

echo "\n✅ Script terminé !\n";
?>
