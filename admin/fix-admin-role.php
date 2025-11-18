<?php
/**
 * SCRIPT DE MISE À JOUR - RÔLE ADMIN
 * 
 * Ce script met à jour le compte admin pour lui donner le rôle 'admin'
 * Exécutez ce fichier une seule fois puis supprimez-le
 */

require_once '../config.php';

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Mise à jour Rôle Admin</title>
    <link rel='stylesheet' href='../assets/css/global/main.css'>
    <link rel='stylesheet' href='../assets/css/dashboard/dashboard.css'>
    <link rel='stylesheet' href='../assets/css/global/fixes.css'>
    <link rel='stylesheet' href='/smm/assets/css/admin/admin-dashboard.css'>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
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
<body>";

require_once __DIR__ . '/sidebar.php';
echo "<div class='main-content'><div class='box'>
        <h1>🔧 Mise à jour Rôle Admin</h1>";

try {
    // Vérifier si la colonne role existe
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'");
    $role_column_exists = $stmt->fetch();
    
    if (!$role_column_exists) {
        echo "<div class='info'><strong>ℹ️ Information :</strong> La colonne 'role' n'existe pas. Création en cours...</div>";
        
        // Ajouter la colonne role si elle n'existe pas
        $pdo->exec("ALTER TABLE users ADD COLUMN role ENUM('user', 'reseller', 'admin') DEFAULT 'user' AFTER status");
        
        echo "<div class='success'><strong>✅ Succès :</strong> Colonne 'role' créée avec succès !</div>";
    }
    
    // Mettre à jour le compte admin
    $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE username = 'admin'");
    $stmt->execute();
    $updated = $stmt->rowCount();
    
    if ($updated > 0) {
        echo "<div class='success'><strong>✅ Succès :</strong> Le compte 'admin' a été mis à jour avec le rôle 'admin' !</div>";
    } else {
        echo "<div class='info'><strong>ℹ️ Information :</strong> Aucun compte 'admin' trouvé ou déjà à jour.</div>";
    }
    
    // Afficher tous les comptes admin
    $stmt = $pdo->query("SELECT id, username, email, role, status FROM users WHERE role = 'admin'");
    $admins = $stmt->fetchAll();
    
    if (!empty($admins)) {
        echo "<div class='info'><strong>👥 Comptes administrateurs :</strong><br><br>";
        foreach ($admins as $admin) {
            echo "- <strong>" . htmlspecialchars($admin['username']) . "</strong> (" . htmlspecialchars($admin['email']) . ") - Statut: " . $admin['status'] . "<br>";
        }
        echo "</div>";
    }
    
    echo "<div class='success'>
        <strong>🎉 Terminé !</strong><br><br>
        Vous pouvez maintenant :
        <ol style='margin: 10px 0; padding-left: 20px;'>
            <li><strong>Vous déconnecter</strong> de votre compte actuel</li>
            <li><strong>Vous reconnecter</strong> avec le compte admin</li>
            <li><strong>Accéder au panel admin</strong> : <code>http://localhost/smm/admin/dashboard.php</code></li>
        </ol>
    </div>";
    
    echo "<div class='error'>
        <strong>⚠️ Important :</strong><br>
        Pour des raisons de sécurité, <strong>supprimez ce fichier</strong> après utilisation :<br>
        <code>D:\\wamp64\\www\\smm\\admin\\fix-admin-role.php</code>
    </div>";
    
    echo "<a href='../auth/logout.php' class='btn'>Se déconnecter</a>";
    echo " <a href='../auth/login.php' class='btn' style='background: #10b981;'>Se connecter</a>";
    
} catch (PDOException $e) {
    echo "<div class='error'><strong>❌ Erreur :</strong> " . $e->getMessage() . "</div>";
}

echo "</div></div><script src='../assets/js/mobile-menu.js'></script></body></html>";
?>
