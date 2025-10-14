<?php
/**
 * PROFIL UTILISATEUR - Version 2.0
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

// Vérifier si connecté
if (!isLoggedIn()) {
    redirect(SITE_URL . '/auth/login.php');
}

$user = getCurrentUser($pdo);
$error = '';
$success = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_profile') {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $csrf_token = $_POST['csrf_token'] ?? '';
        
        if (!verifyCSRFToken($csrf_token)) {
            $error = 'Token de sécurité invalide';
        } elseif (empty($username) || empty($email)) {
            $error = 'Tous les champs sont requis';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Email invalide';
        } else {
            // Vérifier si username/email déjà utilisés par un autre
            $stmt = $pdo->prepare("
                SELECT id FROM users 
                WHERE (username = ? OR email = ?) AND id != ?
            ");
            $stmt->execute([$username, $email, $user['id']]);
            
            if ($stmt->fetch()) {
                $error = 'Username ou email déjà utilisé';
            } else {
                // Mettre à jour
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET username = ?, email = ?, updated_at = NOW()
                    WHERE id = ?
                ");
                
                if ($stmt->execute([$username, $email, $user['id']])) {
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    $success = 'Profil mis à jour avec succès !';
                    $user = getCurrentUser($pdo);
                } else {
                    $error = 'Erreur lors de la mise à jour';
                }
            }
        }
    }
    
    elseif ($action === 'generate_api_key') {
        $csrf_token = $_POST['csrf_token'] ?? '';
        
        if (!verifyCSRFToken($csrf_token)) {
            $error = 'Token de sécurité invalide';
        } else {
            // Générer une nouvelle API key
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
    }
    
    elseif ($action === 'delete_api_key') {
        $csrf_token = $_POST['csrf_token'] ?? '';
        
        if (!verifyCSRFToken($csrf_token)) {
            $error = 'Token de sécurité invalide';
        } else {
            $stmt = $pdo->prepare("
                UPDATE users 
                SET api_key = NULL, updated_at = NOW()
                WHERE id = ?
            ");
            
            if ($stmt->execute([$user['id']])) {
                $success = 'Clé API supprimée avec succès !';
                $user = getCurrentUser($pdo);
            } else {
                $error = 'Erreur lors de la suppression de la clé API';
            }
        }
    }
    
    elseif ($action === 'change_password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';
        
        if (!verifyCSRFToken($csrf_token)) {
            $error = 'Token de sécurité invalide';
        } elseif (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = 'Tous les champs sont requis';
        } elseif ($new_password !== $confirm_password) {
            $error = 'Les mots de passe ne correspondent pas';
        } elseif (strlen($new_password) < PASSWORD_MIN_LENGTH) {
            $error = 'Le mot de passe doit contenir au moins ' . PASSWORD_MIN_LENGTH . ' caractères';
        } else {
            // Vérifier mot de passe actuel
            if (!password_verify($current_password, $user['password'])) {
                $error = 'Mot de passe actuel incorrect';
            } else {
                // Mettre à jour
                $new_hash = password_hash($new_password, HASH_ALGO);
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET password = ?, updated_at = NOW()
                    WHERE id = ?
                ");
                
                if ($stmt->execute([$new_hash, $user['id']])) {
                    $success = 'Mot de passe modifié avec succès !';
                } else {
                    $error = 'Erreur lors de la modification';
                }
            }
        }
    }
}

// Configuration page
$page_title = "Mon Profil";
$page_title_bar = "Mon Profil";

// Inclure header simple
require_once __DIR__ . '/../includes/dashboard-header-simple.php';
?>

<!-- Container sans padding top (collé au top-bar) -->
<div class="container-fluid" style="padding: 0;">
    
    <!-- Content wrapper avec padding latéral seulement -->
    <div style="padding: 24px;">
        
        <?php 
        // Header configuration
        $page_header_title = "Mon Profil";
        $page_header_icon = "user";
        $page_header_description = getIcon('info', false, 'sm') . " Gérez vos informations personnelles et vos paramètres de sécurité";
        $page_header_gradient = false;
        require_once __DIR__ . '/../includes/page-header.php';
        ?>
        
        <!-- Messages -->
        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo getIcon('error'); ?>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo getIcon('success'); ?>
                <span><?php echo $success; ?></span>
            </div>
        <?php endif; ?>

        <div class="profile-layout">
            
            <!-- Sidebar Info -->
            <div class="profile-sidebar">
                <div class="profile-card">
                    <div class="profile-avatar">
                        <?php echo getIcon('user', true, 'xl'); ?>
                    </div>
                    <h3><?php echo clean($user['username']); ?></h3>
                    <p class="profile-email"><?php echo clean($user['email']); ?></p>
                    
                    <?php if ($user['role'] === 'admin'): ?>
                        <span class="role-badge role-admin">
                            <?php echo getIcon('shield', false, 'sm'); ?>
                            Administrateur
                        </span>
                    <?php elseif ($user['role'] === 'reseller'): ?>
                        <span class="role-badge role-reseller">
                            <?php echo getIcon('star', false, 'sm'); ?>
                            Revendeur
                        </span>
                    <?php else: ?>
                        <span class="role-badge role-user">
                            <?php echo getIcon('user', false, 'sm'); ?>
                            Utilisateur
                        </span>
                    <?php endif; ?>
                    
                    <div class="profile-stats">
                        <div class="profile-stat">
                            <div class="stat-value"><?php echo formatCurrency($user['balance']); ?></div>
                            <div class="stat-label">Solde</div>
                        </div>
                        <div class="profile-stat">
                            <div class="stat-value"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></div>
                            <div class="stat-label">Membre depuis</div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="profile-links">
                    <a href="<?php echo SITE_URL; ?>/dashboard/balance.php" class="profile-link">
                        <?php echo getIcon('wallet'); ?>
                        Mon solde
                    </a>
                    <a href="<?php echo SITE_URL; ?>/orders/history.php" class="profile-link">
                        <?php echo getIcon('orders'); ?>
                        Mes commandes
                    </a>
                    <a href="<?php echo SITE_URL; ?>/support/tickets.php" class="profile-link">
                        <?php echo getIcon('support'); ?>
                        Support
                    </a>
                    <?php if ($user['role'] === 'admin'): ?>
                    <a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="profile-link">
                        <?php echo getIcon('settings'); ?>
                        Administration
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="profile-main">
                
                <!-- Informations personnelles -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <?php echo getIcon('edit'); ?>
                            Informations personnelles
                        </h2>
                    </div>
                    <form method="POST" class="card-body">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="form-group">
                            <label for="username">
                                <?php echo getIcon('user', false, 'sm'); ?>
                                Nom d'utilisateur
                            </label>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   value="<?php echo clean($user['username']); ?>" 
                                   required
                                   class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">
                                <?php echo getIcon('mail', false, 'sm'); ?>
                                Adresse email
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo clean($user['email']); ?>" 
                                   required
                                   class="form-control">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <?php echo getIcon('success', false, 'sm'); ?>
                            Enregistrer les modifications
                        </button>
                    </form>
                </div>
                
                <!-- Changer mot de passe -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <?php echo getIcon('lock'); ?>
                            Sécurité et mot de passe
                        </h2>
                    </div>
                    <form method="POST" class="card-body">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="change_password">
                        
                        <div class="form-group">
                            <label for="current_password">
                                <?php echo getIcon('lock', false, 'sm'); ?>
                                Mot de passe actuel
                            </label>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   required
                                   class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">
                                <?php echo getIcon('lock', false, 'sm'); ?>
                                Nouveau mot de passe
                            </label>
                            <input type="password" 
                                   id="new_password" 
                                   name="new_password" 
                                   required
                                   minlength="<?php echo PASSWORD_MIN_LENGTH; ?>"
                                   class="form-control">
                            <small class="form-hint">
                                Minimum <?php echo PASSWORD_MIN_LENGTH; ?> caractères
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">
                                <?php echo getIcon('lock', false, 'sm'); ?>
                                Confirmer le mot de passe
                            </label>
                            <input type="password" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   required
                                   class="form-control">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <?php echo getIcon('success', false, 'sm'); ?>
                            Changer le mot de passe
                        </button>
                    </form>
                </div>
                
                <!-- API Key -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <?php echo getIcon('shield'); ?>
                            Clé API pour développeurs
                        </h2>
                        <p style="font-size: 13px; color: #6b7280; font-weight: 400; margin-top: 8px;">
                            Utilisez cette clé pour intégrer nos services dans vos applications
                        </p>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($user['api_key'])): ?>
                            <div class="api-key-container">
                                <label>
                                    <?php echo getIcon('lock', false, 'sm'); ?>
                                    Votre clé API
                                </label>
                                <div class="api-key-display">
                                    <code id="api-key-value"><?php echo $user['api_key']; ?></code>
                                    <button type="button" class="btn-copy" onclick="copyApiKey()" title="Copier">
                                        <?php echo getIcon('upload', false, 'sm'); ?>
                                    </button>
                                </div>
                                <small class="form-hint" style="color: #ef4444;">
                                    <?php echo getIcon('warning', false, 'sm'); ?>
                                    Ne partagez jamais votre clé API. Elle donne un accès complet à votre compte.
                                </small>
                            </div>
                            
                            <div style="margin-top: 20px; display: flex; gap: 10px;">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="action" value="generate_api_key">
                                    <button type="submit" class="btn btn-secondary" onclick="return confirm('Êtes-vous sûr ? L\'ancienne clé ne fonctionnera plus.')">
                                        <?php echo getIcon('edit', false, 'sm'); ?>
                                        Régénérer
                                    </button>
                                </form>
                                
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="action" value="delete_api_key">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre clé API ?')">
                                        <?php echo getIcon('delete', false, 'sm'); ?>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info" style="margin-bottom: 20px;">
                                <?php echo getIcon('info'); ?>
                                <span>Vous n'avez pas encore de clé API. Générez-en une pour commencer à utiliser notre API.</span>
                            </div>
                            
                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="action" value="generate_api_key">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo getIcon('add', false, 'sm'); ?>
                                    Générer une clé API
                                </button>
                            </form>
                        <?php endif; ?>
                        
                        <div class="api-documentation" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                            <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;">
                                <?php echo getIcon('info', false, 'sm'); ?>
                                Documentation API
                            </h4>
                            <p style="font-size: 13px; color: #6b7280; margin-bottom: 10px;">
                                Notre API RESTful vous permet de :
                            </p>
                            <ul style="font-size: 13px; color: #6b7280; padding-left: 20px;">
                                <li>Récupérer la liste des services</li>
                                <li>Créer des commandes automatiquement</li>
                                <li>Suivre l'état de vos commandes</li>
                                <li>Consulter votre solde</li>
                            </ul>
                            <a href="<?php echo SITE_URL; ?>/pages/api-docs.php" class="btn btn-link" style="margin-top: 10px;">
                                <?php echo getIcon('view', false, 'sm'); ?>
                                Voir la documentation complète
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Informations compte -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <?php echo getIcon('info'); ?>
                            Informations du compte
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    <?php echo getIcon('calendar', false, 'sm'); ?>
                                    Date de création
                                </div>
                                <div class="info-value">
                                    <?php echo date('d F Y à H:i', strtotime($user['created_at'])); ?>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">
                                    <?php echo getIcon('calendar', false, 'sm'); ?>
                                    Dernière connexion
                                </div>
                                <div class="info-value">
                                    <?php echo $user['last_login'] ? date('d F Y à H:i', strtotime($user['last_login'])) : 'Jamais'; ?>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">
                                    <?php echo getIcon('shield', false, 'sm'); ?>
                                    Statut du compte
                                </div>
                                <div class="info-value">
                                    <?php echo statusBadge($user['status']); ?>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">
                                    <?php echo getIcon('user', false, 'sm'); ?>
                                    Type de compte
                                </div>
                                <div class="info-value">
                                    <?php 
                                    $roles = [
                                        'admin' => 'Administrateur',
                                        'reseller' => 'Revendeur',
                                        'user' => 'Utilisateur'
                                    ];
                                    echo $roles[$user['role']] ?? 'Utilisateur';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>

    </div>
</div>

<style>
.profile-layout {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 30px;
    align-items: start;
}

.profile-sidebar {
    position: sticky;
    top: 20px;
}

.profile-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    margin: 0 auto 20px;
}

.profile-card h3 {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.profile-email {
    color: #6b7280;
    font-size: 14px;
    margin-bottom: 16px;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 20px;
}

.role-admin {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
}

.role-reseller {
    background: linear-gradient(135deg, #ddd6fe, #c4b5fd);
    color: #5b21b6;
}

.role-user {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
}

.profile-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

.profile-stat {
    text-align: center;
}

.profile-stat .stat-value {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.profile-stat .stat-label {
    font-size: 12px;
    color: #6b7280;
}

.profile-links {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.profile-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    color: #374151;
    text-decoration: none;
    border-bottom: 1px solid #f3f4f6;
    transition: all 0.3s ease;
}

.profile-link:last-child {
    border-bottom: none;
}

.profile-link:hover {
    background: #f9fafb;
    color: #2563eb;
}

.profile-main {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.info-item {
    padding: 16px;
    background: #f9fafb;
    border-radius: 8px;
}

.info-label {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.info-value {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
}

/* API Key Styles */
.api-key-container {
    margin-bottom: 20px;
}

.api-key-container label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 10px;
}

.api-key-display {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 12px 16px;
}

.api-key-display code {
    flex: 1;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    color: #111827;
    background: transparent;
    border: none;
    word-break: break-all;
}

.btn-copy {
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 8px 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn-copy:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
}

.btn-copy:active {
    transform: translateY(0);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

.btn-link {
    background: transparent;
    color: #2563eb;
    border: none;
    padding: 8px 0;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

@media (max-width: 768px) {
    .profile-layout {
        grid-template-columns: 1fr;
    }
    
    .profile-sidebar {
        position: static;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function copyApiKey() {
    const apiKeyElement = document.getElementById('api-key-value');
    const apiKey = apiKeyElement.textContent;
    
    // Copier dans le presse-papier
    navigator.clipboard.writeText(apiKey).then(function() {
        // Afficher un message de succès
        const btn = event.target.closest('.btn-copy');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<?php echo getIcon("success", false, "sm"); ?> Copié !';
        btn.style.background = '#10b981';
        
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.style.background = '';
        }, 2000);
    }).catch(function(err) {
        alert('Erreur lors de la copie : ' + err);
    });
}
</script>

    </div> <!-- Fin padding wrapper -->

</div> <!-- Fin container-fluid -->
</div>

<?php require_once __DIR__ . '/../includes/dashboard-footer-simple.php'; ?>
