<?php
require_once '../config.php';
require_once '../functions.php';
require_once '../includes/config/icons-config.php';

// Vérifier si connecté
if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);
$error = '';
$success = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = clean($_POST['subject'] ?? '');
    $category = clean($_POST['category'] ?? '');
    $priority = clean($_POST['priority'] ?? 'medium');
    $message = trim($_POST['message'] ?? '');
    $order_id = intval($_POST['order_id'] ?? 0);
    
    if (empty($subject) || empty($message)) {
        $error = 'Le sujet et le message sont obligatoires';
    } elseif (strlen($message) < 20) {
        $error = 'Le message doit contenir au moins 20 caractères';
    } else {
        try {
            // Créer le ticket
            $stmt = $pdo->prepare("
                INSERT INTO tickets (user_id, subject, status, priority, created_at, updated_at)
                VALUES (?, ?, 'open', ?, NOW(), NOW())
            ");
            $stmt->execute([$user['id'], $subject, $priority]);
            $ticket_id = $pdo->lastInsertId();
            
            // Ajouter le premier message
            $stmt = $pdo->prepare("
                INSERT INTO ticket_messages (ticket_id, user_id, message, is_admin, created_at)
                VALUES (?, ?, ?, 0, NOW())
            ");
            $stmt->execute([$ticket_id, $user['id'], $message]);
            
            setFlashMessage('success', "Ticket #$ticket_id créé avec succès ! Notre équipe vous répondra dans les plus brefs délais.");
            redirect("view-ticket.php?id=$ticket_id");
            
        } catch (Exception $e) {
            $error = 'Erreur lors de la création du ticket';
        }
    }
}

// Récupérer les commandes récentes pour le dropdown
$stmt = $pdo->prepare("
    SELECT id, order_number 
    FROM orders 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 10
");
$stmt->execute([$user['id']]);
$recent_orders = $stmt->fetchAll();

// Configuration page
$page_title = "Nouveau Ticket";
$page_title_bar = "Nouveau Ticket";

// Inclure header simple
require_once __DIR__ . '/../includes/layout/dashboard-header-simple.php';
?>

<!-- Container sans padding top (collé au top-bar) -->
<div class="container-fluid" style="padding: 0;">
    
    <!-- Content wrapper avec padding latéral seulement -->
    <div style="padding: 24px;">
    
    <!-- Action Bar -->
    <div style="margin-bottom: 20px;">
        <a href="tickets.php" class="btn btn-secondary">← Retour aux tickets</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <h1>Nouveau Ticket de Support <?php echo getIcon('support'); ?></h1>
            </div>
            <div class="top-bar-right">
                <a href="tickets.php" class="btn btn-secondary">← Mes tickets</a>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div style="max-width: 800px;">
            
            <div class="card">
                <div class="card-header">
                    <h2>Créer un ticket</h2>
                </div>
                <form method="POST" style="padding: 30px;">
                    
                    <div class="form-group">
                        <label for="subject">Sujet *</label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               required 
                               placeholder="Ex: Problème avec ma commande #ORD-ABC123"
                               value="<?php echo $_POST['subject'] ?? ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Catégorie</label>
                        <select id="category" name="category">
                            <option value="order">Commande</option>
                            <option value="payment">Paiement</option>
                            <option value="technical">Technique</option>
                            <option value="refill">Refill</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="priority">Priorité</label>
                        <select id="priority" name="priority">
                            <option value="low">Basse</option>
                            <option value="medium" selected>Normale</option>
                            <option value="high">Haute</option>
                        </select>
                        <small>Choisissez "Haute" uniquement pour les problèmes urgents</small>
                    </div>
                    
                    <?php if (!empty($recent_orders)): ?>
                    <div class="form-group">
                        <label for="order_id">Commande concernée (optionnel)</label>
                        <select id="order_id" name="order_id">
                            <option value="">Aucune commande spécifique</option>
                            <?php foreach ($recent_orders as $order): ?>
                                <option value="<?php echo $order['id']; ?>">
                                    <?php echo $order['order_number']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" 
                                  name="message" 
                                  required 
                                  rows="8" 
                                  placeholder="Décrivez votre problème en détail..."
                                  style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 10px; font-family: inherit;"><?php echo $_POST['message'] ?? ''; ?></textarea>
                        <small>Minimum 20 caractères. Soyez le plus précis possible pour un traitement plus rapide.</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <?php echo getIcon('mail'); ?> Envoyer le ticket
                    </button>
                </form>
            </div>

            <!-- Info Box -->
            <div class="card">
                <div style="padding: 30px; background: #f9fafb;">
                    <h3 style="margin-bottom: 15px;"><?php echo getIcon('info'); ?> Conseils pour un traitement rapide</h3>
                    <ul style="color: #6b7280; line-height: 1.8;">
                        <li><?php echo getIcon('success'); ?> Donnez le numéro de commande si applicable</li>
                        <li><?php echo getIcon('success'); ?> Décrivez précisément le problème</li>
                        <li><?php echo getIcon('success'); ?> Ajoutez des captures d'écran si nécessaire</li>
                        <li><?php echo getIcon('success'); ?> Vérifiez votre email pour les réponses</li>
                    </ul>
                    
                    <hr style="margin: 20px 0; border: none; border-top: 1px solid #e5e7eb;">
                    
                    <h4 style="margin-bottom: 10px;"><?php echo getIcon('pending'); ?> Temps de réponse moyen</h4>
                    <div style="color: #6b7280;">
                        <p>• Priorité haute : <strong>2 heures</strong></p>
                        <p>• Priorité normale : <strong>6 heures</strong></p>
                        <p>• Priorité basse : <strong>24 heures</strong></p>
                    </div>
                </div>
            </div>

        </div>

    </div> <!-- Fin padding wrapper -->

</div> <!-- Fin container-fluid -->
</div>

<?php require_once __DIR__ . '/../includes/layout/dashboard-footer-simple.php'; ?>
