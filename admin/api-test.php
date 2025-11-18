<?php
require_once '../config.php';
require_once '../functions.php';
require_once '../api/SMMFollowsAPI.php';

// Vérifier si admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);
$result = '';
$error = '';

// Récupérer l'API key
$api_key = getSetting($pdo, 'smmfollows_api_key', '');

if (empty($api_key)) {
    $error = 'API Key non configurée. <a href="settings.php">Configurez-la ici</a>';
}

// Traitement des tests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($api_key)) {
    $test_type = $_POST['test_type'] ?? '';
    
    try {
        $api = new SMMFollowsAPI($api_key);
        
        switch ($test_type) {
            case 'services':
                $services = $api->getServices();
                $result = [
                    'success' => true,
                    'message' => 'Services récupérés avec succès',
                    'count' => count($services),
                    'data' => array_slice($services, 0, 5) // Premiers 5 services
                ];
                break;
                
            case 'balance':
                $balance = $api->getBalance();
                $result = [
                    'success' => true,
                    'message' => 'Solde récupéré avec succès',
                    'balance' => $balance
                ];
                break;
                
            case 'order_status':
                $order_id = intval($_POST['order_id'] ?? 0);
                if ($order_id) {
                    $status = $api->getOrderStatus($order_id);
                    $result = [
                        'success' => true,
                        'message' => 'Statut récupéré',
                        'data' => $status
                    ];
                } else {
                    $result = ['success' => false, 'message' => 'ID commande requis'];
                }
                break;
                
            case 'create_order':
                $service_id = intval($_POST['service_id'] ?? 0);
                $link = trim($_POST['link'] ?? '');
                $quantity = intval($_POST['quantity'] ?? 0);
                
                if ($service_id && $link && $quantity) {
                    $order = $api->createOrder($service_id, $link, $quantity);
                    $result = [
                        'success' => true,
                        'message' => 'Commande créée !',
                        'data' => $order
                    ];
                } else {
                    $result = ['success' => false, 'message' => 'Tous les champs requis'];
                }
                break;
        }
        
    } catch (Exception $e) {
        $result = [
            'success' => false,
            'message' => 'Erreur API',
            'error' => $e->getMessage()
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test API SMMFollows - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard/dashboard.css">
    <link rel="stylesheet" href="../assets/css/global/fixes.css">
    <style>
        .test-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .test-card h3 {
            margin-bottom: 15px;
            color: #111827;
        }
        .result-box {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
        }
        .result-success {
            background: #d1fae5;
            border-left: 4px solid #10b981;
        }
        .result-error {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
        }
    </style>
</head>
<body class="dashboard-page logged-in">
    
    <!-- Sidebar -->
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <h1>🧪 Test API SMMFollows</h1>
            </div>
            <div class="top-bar-right">
                <a href="settings.php" class="btn btn-secondary">← Paramètres</a>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result): ?>
            <div class="result-box <?php echo $result['success'] ? 'result-success' : 'result-error'; ?>">
                <strong><?php echo $result['success'] ? '✅ SUCCÈS' : '❌ ERREUR'; ?></strong>
                <pre><?php echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
            </div>
        <?php endif; ?>

        <div style="max-width: 1200px;">
            
            <!-- API Info -->
            <div class="card" style="margin-bottom: 20px;">
                <div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px;">
                    <h2 style="margin-bottom: 15px;">Informations API</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                        <div>
                            <div style="opacity: 0.9; font-size: 14px;">URL API</div>
                            <div style="font-weight: 600;">https://smmfollows.com/api/v2</div>
                        </div>
                        <div>
                            <div style="opacity: 0.9; font-size: 14px;">API Key</div>
                            <div style="font-weight: 600; font-family: monospace; font-size: 12px;">
                                <?php echo $api_key ? substr($api_key, 0, 20) . '...' : 'Non configurée'; ?>
                            </div>
                        </div>
                        <div>
                            <div style="opacity: 0.9; font-size: 14px;">Statut</div>
                            <div style="font-weight: 600;">
                                <?php echo $api_key ? '🟢 Configurée' : '🔴 Non configurée'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
                
                <!-- Test 1: Get Services -->
                <div class="test-card">
                    <h3>📋 Test 1: Récupérer les Services</h3>
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 15px;">
                        Récupère tous les services disponibles sur SMMFollows
                    </p>
                    <form method="POST">
                        <input type="hidden" name="test_type" value="services">
                        <button type="submit" class="btn btn-primary btn-block" <?php echo empty($api_key) ? 'disabled' : ''; ?>>
                            Tester GetServices()
                        </button>
                    </form>
                </div>

                <!-- Test 2: Get Balance -->
                <div class="test-card">
                    <h3>💰 Test 2: Vérifier le Solde</h3>
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 15px;">
                        Récupère votre solde actuel sur SMMFollows
                    </p>
                    <form method="POST">
                        <input type="hidden" name="test_type" value="balance">
                        <button type="submit" class="btn btn-primary btn-block" <?php echo empty($api_key) ? 'disabled' : ''; ?>>
                            Tester GetBalance()
                        </button>
                    </form>
                </div>

                <!-- Test 3: Order Status -->
                <div class="test-card">
                    <h3>📊 Test 3: Statut d'une Commande</h3>
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 15px;">
                        Vérifie le statut d'une commande SMMFollows
                    </p>
                    <form method="POST">
                        <input type="hidden" name="test_type" value="order_status">
                        <div class="form-group">
                            <input type="number" 
                                   name="order_id" 
                                   placeholder="ID Commande SMMFollows"
                                   style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 10px;">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" <?php echo empty($api_key) ? 'disabled' : ''; ?>>
                            Tester GetOrderStatus()
                        </button>
                    </form>
                </div>

                <!-- Test 4: Create Order -->
                <div class="test-card">
                    <h3>🛍️ Test 4: Créer une Commande</h3>
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 15px;">
                        ⚠️ Ceci va créer une VRAIE commande !
                    </p>
                    <form method="POST" onsubmit="return confirm('Créer une vraie commande ?')">
                        <input type="hidden" name="test_type" value="create_order">
                        <div class="form-group">
                            <input type="number" 
                                   name="service_id" 
                                   placeholder="ID Service"
                                   style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 10px;">
                            <input type="url" 
                                   name="link" 
                                   placeholder="https://instagram.com/username"
                                   style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 10px;">
                            <input type="number" 
                                   name="quantity" 
                                   placeholder="Quantité"
                                   style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 10px;">
                        </div>
                        <button type="submit" class="btn btn-danger btn-block" <?php echo empty($api_key) ? 'disabled' : ''; ?>>
                            Tester CreateOrder()
                        </button>
                    </form>
                </div>

            </div>

            <!-- Documentation -->
            <div class="card">
                <div class="card-header">
                    <h2>📖 Documentation API</h2>
                </div>
                <div style="padding: 30px;">
                    <h3 style="margin-bottom: 15px;">Endpoints disponibles :</h3>
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Paramètres</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>services</code></td>
                                <td>-</td>
                                <td>Liste tous les services</td>
                            </tr>
                            <tr>
                                <td><code>add</code></td>
                                <td>service, link, quantity</td>
                                <td>Créer une commande</td>
                            </tr>
                            <tr>
                                <td><code>status</code></td>
                                <td>order</td>
                                <td>Statut d'une commande</td>
                            </tr>
                            <tr>
                                <td><code>refill</code></td>
                                <td>order</td>
                                <td>Demander un refill</td>
                            </tr>
                            <tr>
                                <td><code>balance</code></td>
                                <td>-</td>
                                <td>Solde du compte</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div style="margin-top: 20px;">
                        <a href="https://smmfollows.com/api" target="_blank" class="btn btn-secondary">
                            📖 Documentation complète
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>
