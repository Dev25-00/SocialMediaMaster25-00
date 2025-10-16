<?php
/**
 * SMM Mastery - Paramètres du Compte
 * Date: 14 Octobre 2025
 * Version: 1.0
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../functions.php';

// Vérifier si connecté
if (!isLoggedIn()) {
    redirect(SITE_URL . '/auth/login.php');
}

$user = getCurrentUser($pdo);
$error = '';
$success = '';

// Variables pour la page
$page_title_bar = 'Paramètres';
$current_page = 'settings';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verifyCSRFToken($csrf_token)) {
        $error = 'Token de sécurité invalide';
    }
    
    // Mise à jour du mot de passe
    elseif ($action === 'update_password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = 'Tous les champs sont requis';
        } elseif ($new_password !== $confirm_password) {
            $error = 'Les mots de passe ne correspondent pas';
        } elseif (strlen($new_password) < 8) {
            $error = 'Le mot de passe doit contenir au moins 8 caractères';
        } else {
            // Vérifier le mot de passe actuel
            if (password_verify($current_password, $user['password'])) {
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET password = ?, updated_at = NOW()
                    WHERE id = ?
                ");
                
                if ($stmt->execute([$new_password_hash, $user['id']])) {
                    $success = 'Mot de passe mis à jour avec succès !';
                } else {
                    $error = 'Erreur lors de la mise à jour';
                }
            } else {
                $error = 'Mot de passe actuel incorrect';
            }
        }
    }
    
    // Mise à jour préférences notifications
    elseif ($action === 'update_notifications') {
        $email_notifications = isset($_POST['email_notifications']) ? 1 : 0;
        $order_notifications = isset($_POST['order_notifications']) ? 1 : 0;
        $promo_notifications = isset($_POST['promo_notifications']) ? 1 : 0;
        
        $stmt = $pdo->prepare("
            UPDATE users 
            SET 
                email_notifications = ?,
                order_notifications = ?,
                promo_notifications = ?,
                updated_at = NOW()
            WHERE id = ?
        ");
        
        if ($stmt->execute([$email_notifications, $order_notifications, $promo_notifications, $user['id']])) {
            $success = 'Préférences de notifications mises à jour !';
            $user = getCurrentUser($pdo);
        } else {
            $error = 'Erreur lors de la mise à jour';
        }
    }
    
    // Régénérer clé API
    elseif ($action === 'generate_api_key') {
        $new_api_key = bin2hex(random_bytes(32));
        
        $stmt = $pdo->prepare("
            UPDATE users 
            SET api_key = ?, updated_at = NOW()
            WHERE id = ?
        ");
        
        if ($stmt->execute([$new_api_key, $user['id']])) {
            $success = 'Nouvelle clé API générée avec succès !';
            $user = getCurrentUser($pdo);
        } else {
            $error = 'Erreur lors de la génération de la clé API';
        }
    }
    
    // Supprimer clé API
    elseif ($action === 'delete_api_key') {
        $stmt = $pdo->prepare("
            UPDATE users 
            SET api_key = NULL, updated_at = NOW()
            WHERE id = ?
        ");
        
        if ($stmt->execute([$user['id']])) {
            $success = 'Clé API supprimée avec succès !';
            $user = getCurrentUser($pdo);
        } else {
            $error = 'Erreur lors de la suppression';
        }
    }
}

// Header
require_once __DIR__ . '/../../includes/layout/dashboard-header-simple.php';
?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require_once __DIR__ . '/../../includes/layout/dashboard-sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Messages -->
        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo getIcon('times-circle', true); ?>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo getIcon('check-circle', true); ?>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <!-- Page Title -->
        <div class="page-header">
            <h1><?php echo getIcon('cog', true, 'xl'); ?> Paramètres du Compte</h1>
            <p class="subtitle">Gérez vos paramètres de sécurité et préférences</p>
        </div>
        
        <div class="settings-grid">
            <!-- Sécurité du compte -->
            <div class="card">
                <div class="card-header">
                    <h2><?php echo getIcon('lock', true); ?> Sécurité</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="update_password">
                        
                        <div class="form-group">
                            <label>Mot de passe actuel</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Nouveau mot de passe</label>
                            <input type="password" name="new_password" class="form-control" required minlength="8">
                            <small>Minimum 8 caractères</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Confirmer le nouveau mot de passe</label>
                            <input type="password" name="confirm_password" class="form-control" required minlength="8">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <?php echo getIcon('save', true); ?> Mettre à jour le mot de passe
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Préférences notifications -->
            <div class="card">
                <div class="card-header">
                    <h2><?php echo getIcon('bell', true); ?> Notifications</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="update_notifications">
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="email_notifications" 
                                    <?php echo isset($user['email_notifications']) && $user['email_notifications'] ? 'checked' : ''; ?>>
                                <span>Recevoir des notifications par email</span>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="order_notifications" 
                                    <?php echo isset($user['order_notifications']) && $user['order_notifications'] ? 'checked' : ''; ?>>
                                <span>Notifications de commandes</span>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="promo_notifications" 
                                    <?php echo isset($user['promo_notifications']) && $user['promo_notifications'] ? 'checked' : ''; ?>>
                                <span>Recevoir les offres promotionnelles</span>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <?php echo getIcon('save', true); ?> Enregistrer les préférences
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- API Key -->
            <div class="card">
                <div class="card-header">
                    <h2><?php echo getIcon('key', true); ?> Clé API</h2>
                </div>
                <div class="card-body">
                    <?php if (isset($user['api_key']) && !empty($user['api_key'])): ?>
                        <div class="api-key-display">
                            <label>Votre clé API</label>
                            <div class="api-key-box">
                                <code id="api-key"><?php echo htmlspecialchars($user['api_key']); ?></code>
                                <button type="button" class="btn-copy" onclick="copyApiKey()">
                                    <?php echo getIcon('copy', true); ?> Copier
                                </button>
                            </div>
                        </div>
                        
                        <div class="api-actions">
                            <form method="POST" action="" style="display: inline;" 
                                onsubmit="return confirm('Voulez-vous vraiment régénérer votre clé API ? L\'ancienne clé ne fonctionnera plus.')">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="action" value="generate_api_key">
                                <button type="submit" class="btn btn-warning">
                                    <?php echo getIcon('sync', true); ?> Régénérer
                                </button>
                            </form>
                            
                            <form method="POST" action="" style="display: inline;" 
                                onsubmit="return confirm('Voulez-vous vraiment supprimer votre clé API ?')">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="action" value="delete_api_key">
                                <button type="submit" class="btn btn-danger">
                                    <?php echo getIcon('trash', true); ?> Supprimer
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <p>Vous n'avez pas encore de clé API.</p>
                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="action" value="generate_api_key">
                            <button type="submit" class="btn btn-primary">
                                <?php echo getIcon('plus', true); ?> Générer une clé API
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <div class="api-info">
                        <p><strong>Utilisez votre clé API pour :</strong></p>
                        <ul>
                            <li>Intégrations tierces</li>
                            <li>Automatisation des commandes</li>
                            <li>Applications externes</li>
                        </ul>
                        <p class="text-warning">
                            <?php echo getIcon('exclamation-triangle', true); ?>
                            <strong>Important :</strong> Ne partagez jamais votre clé API !
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Informations compte -->
            <div class="card">
                <div class="card-header">
                    <h2><?php echo getIcon('info-circle', true); ?> Informations</h2>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="label">Compte créé le :</span>
                        <span class="value"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Dernière mise à jour :</span>
                        <span class="value"><?php echo date('d/m/Y H:i', strtotime($user['updated_at'])); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Statut du compte :</span>
                        <span class="value">
                            <span class="badge badge-success">
                                <?php echo getIcon('check-circle', true); ?> Actif
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions dangereuses -->
        <div class="danger-zone">
            <h3><?php echo getIcon('exclamation-triangle', true); ?> Zone Dangereuse</h3>
            <p>Actions irréversibles nécessitant une confirmation</p>
            <button type="button" class="btn btn-danger" onclick="alert('Fonctionnalité à venir : Suppression de compte')">
                <?php echo getIcon('user-times', true); ?> Supprimer mon compte
            </button>
        </div>
    </main>
</div>

<script>
function copyApiKey() {
    const apiKey = document.getElementById('api-key').textContent;
    navigator.clipboard.writeText(apiKey).then(() => {
        alert('Clé API copiée dans le presse-papiers !');
    }).catch(err => {
        console.error('Erreur copie:', err);
    });
}
</script>

<style>
.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.api-key-display {
    margin-bottom: 20px;
}

.api-key-box {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-top: 10px;
}

.api-key-box code {
    flex: 1;
    background: #f3f4f6;
    padding: 12px;
    border-radius: 8px;
    font-family: 'Courier New', monospace;
    font-size: 14px;
    word-break: break-all;
}

.btn-copy {
    background: #2563eb;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-copy:hover {
    background: #1d4ed8;
}

.api-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.api-info {
    margin-top: 20px;
    padding: 15px;
    background: #f9fafb;
    border-radius: 8px;
}

.api-info ul {
    margin: 10px 0;
    padding-left: 20px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #e5e7eb;
}

.info-row:last-child {
    border-bottom: none;
}

.info-row .label {
    font-weight: 600;
    color: #6b7280;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.danger-zone {
    margin-top: 40px;
    padding: 20px;
    background: #fef2f2;
    border: 2px solid #fecaca;
    border-radius: 12px;
}

.danger-zone h3 {
    color: #dc2626;
    margin-bottom: 10px;
}

.danger-zone p {
    color: #7f1d1d;
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
    
    .api-key-box {
        flex-direction: column;
    }
    
    .api-actions {
        flex-direction: column;
    }
}
</style>

<?php require_once __DIR__ . '/../../includes/layout/dashboard-footer-simple.php'; ?>
