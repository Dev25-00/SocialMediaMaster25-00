<?php
require_once '../config.php';
require_once '../functions.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../auth/login.php');
}

// Traitement POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'reply_ticket') {
        $ticket_id = (int)$_POST['ticket_id'];
        $message = clean($_POST['message']);
        
        if (!empty($message)) {
            $stmt = $pdo->prepare("SELECT user_id FROM tickets WHERE id = ?");
            $stmt->execute([$ticket_id]);
            $ticket = $stmt->fetch();
            
            if ($ticket) {
                $stmt = $pdo->prepare("
                    INSERT INTO ticket_messages (ticket_id, user_id, message, is_admin)
                    VALUES (?, ?, ?, 1)
                ");
                $stmt->execute([$ticket_id, getCurrentUser($pdo)['id'], $message]);
                
                $stmt = $pdo->prepare("UPDATE tickets SET status = 'answered', updated_at = NOW() WHERE id = ?");
                $stmt->execute([$ticket_id]);
                
                setFlashMessage('Réponse envoyée !', 'success');
            }
        }
        redirect('tickets.php?id=' . $ticket_id);
    }
}

// Stats
$stats = [];
$stats['total'] = $pdo->query("SELECT COUNT(*) FROM tickets")->fetchColumn();
$stats['open'] = $pdo->query("SELECT COUNT(*) FROM tickets WHERE status = 'open'")->fetchColumn();
$stats['answered'] = $pdo->query("SELECT COUNT(*) FROM tickets WHERE status = 'answered'")->fetchColumn();
$stats['closed'] = $pdo->query("SELECT COUNT(*) FROM tickets WHERE status = 'closed'")->fetchColumn();

// Liste tickets
$stmt = $pdo->query("
    SELECT t.*, u.username, u.email,
           (SELECT COUNT(*) FROM ticket_messages WHERE ticket_id = t.id) as message_count
    FROM tickets t
    JOIN users u ON t.user_id = u.id
    ORDER BY t.updated_at DESC
    LIMIT 50
");
$tickets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Tickets</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard/dashboard.css">
    <link rel="stylesheet" href="../assets/css/global/fixes.css">
<head>
    ...existing code...
    <link rel="stylesheet" href="/smm/assets/css/admin/admin-dashboard.css">
</head>
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <div class="main-content">
        <div class="top-bar">
            <div class="top-bar-left">
                <h1><?php echo getIcon('ticket'); ?> Gestion des Tickets</h1>
            </div>
        </div>

        <?php echo renderFlashMessage(); ?>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"><?php echo getIcon('ticket'); ?></div>
                <div class="stat-info">
                    <div class="stat-label">Total</div>
                    <div class="stat-value"><?php echo $stats['total']; ?></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">📬</div>
                <div class="stat-info">
                    <div class="stat-label">Ouverts</div>
                    <div class="stat-value"><?php echo $stats['open']; ?></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"><?php echo getIcon('support'); ?></div>
                <div class="stat-info">
                    <div class="stat-label">Répondus</div>
                    <div class="stat-value"><?php echo $stats['answered']; ?></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);"><?php echo getIcon('success'); ?></div>
                <div class="stat-info">
                    <div class="stat-label">Fermés</div>
                    <div class="stat-value"><?php echo $stats['closed']; ?></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Liste des tickets</h2>
            </div>
            
            <?php if (empty($tickets)): ?>
                <div class="empty-state">
                    <div class="empty-icon"><?php echo getIcon('ticket'); ?></div>
                    <h3>Aucun ticket</h3>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sujet</th>
                                <th>Utilisateur</th>
                                <th>Statut</th>
                                <th>Messages</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tickets as $t): ?>
                            <tr>
                                <td><strong>#<?php echo $t['id']; ?></strong></td>
                                <td><?php echo clean($t['subject']); ?></td>
                                <td><?php echo clean($t['username']); ?></td>
                                <td><?php echo getStatusBadge($t['status']); ?></td>
                                <td><?php echo $t['message_count']; ?></td>
                                <td><?php echo timeAgo($t['created_at']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>
