<?php
/**
 * SMM Mastery - Script de nettoyage du dossier includes/
 * Date: 12 Octobre 2025
 * Supprime les fichiers obsolètes après backup
 */

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  🧹 NETTOYAGE DOSSIER INCLUDES - SMM Mastery                    ║\n";
echo "╠════════════════════════════════════════════════════════════════╣\n";
echo "║  Date: " . date('d/m/Y H:i:s') . "                                       ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// Configuration
$includes_dir = __DIR__ . '/../includes';
$backup_dir = __DIR__ . '/../BACKUP_INCLUDES_' . date('Ymd_His');

// Fichiers à supprimer
$files_to_delete = [
    'dashboard-header.php',
    'dashboard-footer.php',
    'dashboard-footer-simple.php',
    'sidebar.php'
];

// Fichiers à garder (vérification)
$files_to_keep = [
    'dashboard-header-simple.php',
    'dashboard-top-bar.php',
    'dashboard-sidebar.php',
    'public-header.php',
    'public-footer.php',
    'icons-config.php',
    'page-header.php',
    'EmailManager.php'
];

echo "📋 ÉTAPE 1: VÉRIFICATION DES FICHIERS\n";
echo str_repeat('-', 64) . "\n";

// Vérifier que les fichiers à garder existent
$missing_critical = [];
foreach ($files_to_keep as $file) {
    $path = $includes_dir . '/' . $file;
    if (!file_exists($path)) {
        $missing_critical[] = $file;
        echo "❌ CRITIQUE: $file manquant!\n";
    } else {
        echo "✅ $file présent\n";
    }
}

if (!empty($missing_critical)) {
    echo "\n⛔ ERREUR: Fichiers critiques manquants!\n";
    echo "Le nettoyage est ANNULÉ pour éviter les problèmes.\n";
    exit(1);
}

echo "\n📦 ÉTAPE 2: CRÉATION DU BACKUP\n";
echo str_repeat('-', 64) . "\n";

// Créer dossier backup
if (!file_exists($backup_dir)) {
    mkdir($backup_dir, 0755, true);
    echo "✅ Dossier backup créé: " . basename($backup_dir) . "\n";
}

// Copier tous les fichiers vers backup
$files_backed_up = 0;
$dir_handle = opendir($includes_dir);
while (($file = readdir($dir_handle)) !== false) {
    if ($file === '.' || $file === '..') continue;
    
    $source = $includes_dir . '/' . $file;
    $destination = $backup_dir . '/' . $file;
    
    if (is_file($source)) {
        copy($source, $destination);
        $files_backed_up++;
    }
}
closedir($dir_handle);

echo "✅ $files_backed_up fichiers sauvegardés dans backup\n";

echo "\n🗑️ ÉTAPE 3: SUPPRESSION DES FICHIERS OBSOLÈTES\n";
echo str_repeat('-', 64) . "\n";

$files_deleted = 0;
$files_not_found = 0;

foreach ($files_to_delete as $file) {
    $path = $includes_dir . '/' . $file;
    
    if (file_exists($path)) {
        if (unlink($path)) {
            echo "✅ Supprimé: $file\n";
            $files_deleted++;
        } else {
            echo "❌ Erreur suppression: $file\n";
        }
    } else {
        echo "ℹ️  Déjà absent: $file\n";
        $files_not_found++;
    }
}

echo "\n📊 ÉTAPE 4: RÉSUMÉ DU NETTOYAGE\n";
echo str_repeat('-', 64) . "\n";

echo "✅ Fichiers sauvegardés: $files_backed_up\n";
echo "🗑️ Fichiers supprimés: $files_deleted\n";
echo "ℹ️  Fichiers déjà absents: $files_not_found\n";

// Liste finale des fichiers
echo "\n📁 ÉTAPE 5: CONTENU FINAL DU DOSSIER INCLUDES\n";
echo str_repeat('-', 64) . "\n";

$final_files = [];
$dir_handle = opendir($includes_dir);
while (($file = readdir($dir_handle)) !== false) {
    if ($file === '.' || $file === '..') continue;
    if (is_file($includes_dir . '/' . $file)) {
        $final_files[] = $file;
    }
}
closedir($dir_handle);

sort($final_files);

echo "Total: " . count($final_files) . " fichiers\n\n";
foreach ($final_files as $index => $file) {
    $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
    echo "$num. $file\n";
}

echo "\n✅ NETTOYAGE TERMINÉ AVEC SUCCÈS!\n";
echo "\n📦 Backup disponible dans: " . basename($backup_dir) . "\n";
echo "\n💡 Pour restaurer en cas de problème:\n";
echo "   Copier le contenu de " . basename($backup_dir) . " vers includes/\n";

echo "\n╔════════════════════════════════════════════════════════════════╗\n";
echo "║  ✨ NETTOYAGE RÉUSSI - INCLUDES OPTIMISÉ                      ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
