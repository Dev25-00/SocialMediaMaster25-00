<?php
/**
 * Page de diagnostic - Vérifier accès admin
 */
require_once '../config.php';
require_once '../functions.php';

echo "<h1>🔍 Diagnostic Admin Panel</h1>";
echo "<hr>";

// 1. Vérifier la session
echo "<h2>1️⃣ Session</h2>";
echo "<pre>";
echo "Session démarrée : " . (session_status() === PHP_SESSION_ACTIVE ? '✅ OUI' : '❌ NON') . "\n";
echo "User ID : " . ($_SESSION['user_id'] ?? '❌ Non défini') . "\n";
echo "Username : " . ($_SESSION['username'] ?? '❌ Non défini') . "\n";
echo "Role : " . ($_SESSION['role'] ?? '❌ Non défini') . "\n";
echo "</pre>";

// 2. Vérifier les fonctions
echo "<h2>2️⃣ Fonctions</h2>";
echo "<pre>";
echo "isLoggedIn() : " . (function_exists('isLoggedIn') ? '✅ Existe' : '❌ N\'existe pas') . "\n";
if (function_exists('isLoggedIn')) {
    echo "  → Résultat : " . (isLoggedIn() ? '✅ Connecté' : '❌ Non connecté') . "\n";
}
echo "isAdmin() : " . (function_exists('isAdmin') ? '✅ Existe' : '❌ N\'existe pas') . "\n";
if (function_exists('isAdmin')) {
    echo "  → Résultat : " . (isAdmin() ? '✅ Admin' : '❌ Pas admin') . "\n";
}
echo "getIcon() : " . (function_exists('getIcon') ? '✅ Existe' : '❌ N\'existe pas') . "\n";
echo "</pre>";

// 3. Vérifier l'utilisateur en base
echo "<h2>3️⃣ Utilisateur en base</h2>";
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<pre>";
        echo "ID : {$user['id']}\n";
        echo "Username : {$user['username']}\n";
        echo "Email : {$user['email']}\n";
        echo "Role : {$user['role']}\n";
        echo "Status : {$user['status']}\n";
        echo "</pre>";
        
        if ($user['role'] !== 'admin') {
            echo "<p style='color: red; font-weight: bold;'>⚠️ ATTENTION : Vous n'êtes pas admin ! Role actuel : {$user['role']}</p>";
            echo "<p><a href='../auth/logout.php'>Se déconnecter</a> puis se reconnecter avec un compte admin.</p>";
        } else {
            echo "<p style='color: green; font-weight: bold;'>✅ Vous êtes bien admin !</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Utilisateur non trouvé en base</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Pas d'ID utilisateur en session</p>";
}

// 4. Tester l'accès aux pages admin
echo "<h2>4️⃣ Accès aux pages</h2>";
$admin_pages = [
    'dashboard.php' => 'Dashboard',
    'services.php' => 'Services',
    'sync-services.php' => 'Synchronisation',
    'check-prices.php' => 'Vérifier Prix',
    'users.php' => 'Utilisateurs',
    'orders.php' => 'Commandes',
    'settings.php' => 'Paramètres',
];

echo "<ul>";
foreach ($admin_pages as $page => $name) {
    $file_path = __DIR__ . '/' . $page;
    $exists = file_exists($file_path);
    $readable = $exists && is_readable($file_path);
    
    echo "<li>";
    echo "<strong>$name</strong> ($page) : ";
    echo $exists ? '✅ Existe' : '❌ N\'existe pas';
    echo " | ";
    echo $readable ? '✅ Lisible' : '❌ Non lisible';
    echo " | ";
    echo "<a href='$page' target='_blank'>Tester l'accès →</a>";
    echo "</li>";
}
echo "</ul>";

// 5. Vérifier API
echo "<h2>5️⃣ Configuration API</h2>";
$api_key = getSetting($pdo, 'smmfollows_api_key', '');
echo "<pre>";
echo "API Key configurée : " . ($api_key ? '✅ OUI (' . substr($api_key, 0, 10) . '...)' : '❌ NON') . "\n";
echo "</pre>";

if (!$api_key) {
    echo "<p style='color: orange;'>⚠️ Vous devez configurer l'API Key dans les paramètres pour synchroniser les services.</p>";
    echo "<p><a href='settings.php' style='padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;'>Configurer maintenant</a></p>";
}

echo "<hr>";
echo "<h2>🔗 Liens rapides</h2>";
echo "<p><a href='dashboard.php' style='padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>📊 Dashboard</a></p>";
echo "<p><a href='sync-services.php' style='padding: 10px 20px; background: #10b981; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>🔄 Synchroniser</a></p>";
echo "<p><a href='check-prices.php' style='padding: 10px 20px; background: #f59e0b; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>💰 Vérifier Prix</a></p>";
?>
