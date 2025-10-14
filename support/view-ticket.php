<?php
require_once '../config.php';
require_once '../functions.php';
require_once '../includes/icons-config.php';

// Vérifier si connecté
if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);
$ticket_id = $_GET['id'] ?? 0;
$error = '';
$success = '';

// Récupérer le ticket
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ? AND user_id = ?");
$stmt->execute([$ticket_id, $user['id']]);
$ticket = $stmt->fetch();

if (!$ticket) {
    setFlashMessage('error', 'Ticket introuvable');
    redirect('tickets.php');
}

// Traitement ajout message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_message'])) {
    $message = trim($_POST['message'] ?? '');
    
    if (empty($message)) {
        $error = 'Le message ne peut pas être vide';
    } elseif (strlen($message) < 10) {
        $error = 'Le message doit contenir au moins 10 caractères';
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO ticket_messages (ticket_id, user_id, message, is_admin, created_at)
            VALUES (?, ?, ?, 0, NOW())
        ");
        
        if ($stmt->execute([$ticket_id, $user['id'], $message])) {
            // Mettre à jour le ticket
            $pdo->prepare("UPDATE tickets SET status = 'open', updated_at = NOW() WHERE id = ?")->execute([$ticket_id]);
            
            $success = 'Message envoyé avec succès !';
            // Recharger pour voir le nouveau message
            header("Location: view-ticket.php?id=$ticket_id");
            exit;
        } else {
            $error = 'Erreur lors de l\'envoi du message';
        }
    }
}

// Fermer le ticket
if (isset($_POST['close_ticket'])) {
    $pdo->prepare("UPDATE tickets SET status = 'closed', updated_at = NOW() WHERE id = ?")->execute([$ticket_id]);
    $ticket['status'] = 'closed';
    $success = 'Ticket fermé avec succès !';
}

// Récupérer les messages
$stmt = $pdo->prepare("
    SELECT tm.*, u.username 
    FROM ticket_messages tm
    JOIN users u ON tm.user_id = u.id
    WHERE tm.ticket_id = ?
    ORDER BY tm.created_at ASC
");
$stmt->execute([$ticket_id]);
$messages = $stmt->fetchAll();

// Configuration page
$page_title = "Ticket #" . $ticket['id'];
$page_title_bar = "Ticket #" . $ticket['id'];

// Inclure header simple
require_once __DIR__ . '/../includes/dashboard-header-simple.php';
?>

<!-- Container sans padding top (collé au top-bar) -->
<div class="container-fluid" style="padding: 0;">
    
    <!-- Content wrapper avec padding latéral seulement -->
    <div style="padding: 24px;">
    
    <!-- Action Bar -->
    <div style="margin-bottom: 20px;">
        <a href="tickets.php" class="btn btn-secondary">← Retour aux tickets</a>
    </div>

<style>
    .message {
        margin-bottom: 20px;
        display: flex;
        gap: 15px;
    }
    .message.admin {
        flex-direction: row-reverse;
    }
    .message-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        flex-shrink: 0;
    }
    .message.admin .message-avatar {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    .message-content {
        flex: 1;
        background: #f9fafb;
        padding: 15px 20px;
        border-radius: 12px;
        position: relative;
    }
    .message.admin .message-content {
        background: #e0f2fe;
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .message-author {
            font-weight: 600;
            color: #111827;
        }
        .message.admin .message-author {
            color: #0284c7;
        }
        .message-time {
            font-size: 12px;
            color: #6b7280;
        }
        .message-text {
            color: #374151;
            line-height: 1.6;
            white-space: pre-wrap;
        }
    </style>
</head>
<body class="dashboard-page logged-in">
    
    <!-- Sidebar -->
    <?php include '../includes/dashboard-sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <h1>Ticket #<?php echo $ticket['id']; ?></h1>
            </div>
            <div class="top-bar-right">
                <a href="tickets.php" class="btn btn-secondary">← Retour</a>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div style="max-width: 900px;">
            
            <!-- Ticket Header -->
            <div class="card">
                <div style="padding: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px;">
                        <div>
                            <h2 style="font-size: 24px; margin-bottom: 10px;">
                                <?php echo clean($ticket['subject']); ?>
                            </h2>
                            <div style="display: flex; gap: 15px; font-size: 14px; color: #6b7280;">
                                <span>Créé le <?php echo formatDate($ticket['created_at'], 'd/m/Y à H:i'); ?></span>
                                <span>•</span>
                                <span>Mis à jour <?php echo timeAgo($ticket['updated_at']); ?></span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <?php
                            $priority_badges = [
                                'low' => '<span class="badge badge-secondary">Basse</span>',
                                'medium' => '<span class="badge badge-info">Normale</span>',
                                'high' => '<span class="badge badge-danger">Haute</span>'
                            ];
                            echo $priority_badges[$ticket['priority']] ?? '';
                            
                            $status_badges = [
                                'open' => '<span class="badge badge-warning">Ouvert</span>',
                                'answered' => '<span class="badge badge-info">Répondu</span>',
                                'closed' => '<span class="badge badge-secondary">Fermé</span>'
                            ];
                            echo $status_badges[$ticket['status']] ?? '';
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="card">
                <div class="card-header">
                    <h2>Conversation</h2>
                </div>
                <div style="padding: 30px;">
                    <?php foreach ($messages as $msg): ?>
                        <div class="message <?php echo $msg['is_admin'] ? 'admin' : ''; ?>">
                            <div class="message-avatar">
                                <?php echo strtoupper(substr($msg['username'], 0, 1)); ?>
                            </div>
                            <div class="message-content">
                                <div class="message-header">
                                    <span class="message-author">
                                        <?php echo clean($msg['username']); ?>
                                        <?php if ($msg['is_admin']): ?>
                                            <span style="font-size: 12px; font-weight: normal; color: #059669;">• Support</span>
                                        <?php endif; ?>
                                    </span>
                                    <span class="message-time"><?php echo timeAgo($msg['created_at']); ?></span>
                                </div>
                                <div class="message-text"><?php echo nl2br(clean($msg['message'])); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Reply Form -->
            <?php if ($ticket['status'] !== 'closed'): ?>
            <div class="card">
                <div class="card-header">
                    <h2>Répondre</h2>
                </div>
                <form method="POST" style="padding: 30px;">
                    <input type="hidden" name="add_message" value="1">
                    
                    <div class="form-group">
                        <textarea name="message" 
                                  rows="6" 
                                  placeholder="Votre message..."
                                  required
                                  style="width: 100%; padding: 15px; border: 2px solid #e5e7eb; border-radius: 10px; font-family: inherit;"></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary">
                            <?php echo getIcon('mail'); ?> Envoyer la réponse
                        </button>
                        <button type="submit" name="close_ticket" value="1" class="btn btn-secondary"
                                onclick="return confirm('Êtes-vous sûr de vouloir fermer ce ticket ?')">
                            <?php echo getIcon('success'); ?> Fermer le ticket
                        </button>
                    </div>
                </form>
            </div>
            <?php else: ?>
            <div class="card">
                <div style="padding: 30px; text-align: center; background: #f9fafb;">
                    <div style="font-size: 48px; margin-bottom: 15px;"><?php echo getIcon('success'); ?></div>
                    <h3>Ticket fermé</h3>
                    <p style="color: #6b7280; margin: 15px 0;">
                        Ce ticket a été marqué comme résolu. Si vous avez besoin d'aide supplémentaire, créez un nouveau ticket.
                    </p>
                    <a href="new-ticket.php" class="btn btn-primary">Créer un nouveau ticket</a>
                </div>
            </div>
            <?php endif; ?>

        </div>

    </div> <!-- Fin padding wrapper -->

</div> <!-- Fin container-fluid -->
</div>

<script>
    // Scroll vers le bas au chargement
    window.addEventListener('load', function() {
        const messages = document.querySelector('.card:nth-of-type(2)');
        if (messages) {
            messages.scrollIntoView({ behavior: 'smooth', block: 'end' });
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/dashboard-footer-simple.php'; ?>
