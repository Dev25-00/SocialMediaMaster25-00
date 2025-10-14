<?php
require_once '../config.php';
require_once '../functions.php';
require_once '../includes/icons-config.php';

// Vérifier si connecté
if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);

// Filtres
$status = $_GET['status'] ?? '';

$where = ["user_id = ?"];
$params = [$user['id']];

if ($status) {
    $where[] = "status = ?";
    $params[] = $status;
}

$where_clause = implode(' AND ', $where);

// Récupérer les tickets
$stmt = $pdo->prepare("
    SELECT * FROM tickets 
    WHERE $where_clause 
    ORDER BY 
        CASE 
            WHEN status = 'open' THEN 1
            WHEN status = 'answered' THEN 2
            ELSE 3
        END,
        priority DESC,
        updated_at DESC
");
$stmt->execute($params);
$tickets = $stmt->fetchAll();

// Configuration page
$page_title = "Support Tickets";
$page_title_bar = "Mes Tickets";

// Inclure header simple
require_once __DIR__ . '/../includes/dashboard-header-simple.php';
?>

<!-- Container sans padding top (collé au top-bar) -->
<div class="container-fluid" style="padding: 0;">
    
    <!-- Content wrapper avec padding latéral seulement -->
    <div style="padding: 24px;">
    
    <?php 
    // Header configuration
    $page_header_title = "Support Tickets";
    $page_header_icon = "support";
    $page_header_description = getIcon('info', false, 'sm') . " Besoin d'aide ? Créez un ticket ou consultez vos demandes";
    $page_header_gradient = false;
    require_once __DIR__ . '/../includes/page-header.php';
    ?>
    
    <!-- Action Bar -->
    <div style="margin-bottom: 20px; display: flex; justify-content: flex-end;">
        <a href="new-ticket.php" class="btn btn-primary">
            <?php echo getIcon('add', false, 'sm'); ?>
            Nouveau ticket
        </a>
    </div>

    <?php echo renderFlashMessage(); ?>

        <!-- Filters -->
        <div class="filters">
            <form method="GET">
                <div class="filter-group">
                    <label><?php echo getIcon('stats'); ?> Statut</label>
                    <select name="status" onchange="this.form.submit()">
                        <option value="">Tous</option>
                        <option value="open" <?php echo $status === 'open' ? 'selected' : ''; ?>>Ouvert</option>
                        <option value="answered" <?php echo $status === 'answered' ? 'selected' : ''; ?>>Répondu</option>
                        <option value="closed" <?php echo $status === 'closed' ? 'selected' : ''; ?>>Fermé</option>
                    </select>
                </div>
                <div class="filter-group">
                    <a href="tickets.php" class="btn btn-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>

        <!-- Tickets List -->
        <div class="card">
            <div class="card-header">
                <h2><?php echo count($tickets); ?> ticket<?php echo count($tickets) > 1 ? 's' : ''; ?></h2>
            </div>
            
            <?php if (empty($tickets)): ?>
                <div class="empty-state">
                    <div class="empty-icon"><?php echo getIcon('support'); ?></div>
                    <h3>Aucun ticket</h3>
                    <p>Vous n'avez pas encore créé de ticket de support</p>
                    <a href="new-ticket.php" class="btn btn-primary">Créer un ticket</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sujet</th>
                                <th>Priorité</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                                <th>Dernière mise à jour</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tickets as $ticket): ?>
                            <tr>
                                <td><strong>#<?php echo $ticket['id']; ?></strong></td>
                                <td>
                                    <strong><?php echo clean($ticket['subject']); ?></strong>
                                </td>
                                <td>
                                    <?php
                                    $priority_badges = [
                                        'low' => '<span class="badge badge-secondary">Basse</span>',
                                        'medium' => '<span class="badge badge-info">Normale</span>',
                                        'high' => '<span class="badge badge-danger">Haute</span>'
                                    ];
                                    echo $priority_badges[$ticket['priority']] ?? $ticket['priority'];
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $status_badges = [
                                        'open' => '<span class="badge badge-warning">Ouvert</span>',
                                        'answered' => '<span class="badge badge-info">Répondu</span>',
                                        'closed' => '<span class="badge badge-secondary">Fermé</span>'
                                    ];
                                    echo $status_badges[$ticket['status']] ?? $ticket['status'];
                                    ?>
                                </td>
                                <td><?php echo formatDate($ticket['created_at'], 'd/m/Y H:i'); ?></td>
                                <td><?php echo timeAgo($ticket['updated_at']); ?></td>
                                <td>
                                    <a href="view-ticket.php?id=<?php echo $ticket['id']; ?>" class="btn btn-sm">
                                        <?php echo getIcon('view'); ?> Voir
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- FAQ Quick Links -->
        <div class="card">
            <div class="card-header">
                <h2>Besoin d'aide rapide ?</h2>
            </div>
            <div style="padding: 30px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <a href="#" style="padding: 15px; background: #f9fafb; border-radius: 8px; text-align: center; transition: all 0.3s;">
                        <div style="font-size: 32px; margin-bottom: 10px;">📖</div>
                        <strong>FAQ</strong>
                        <p style="font-size: 12px; color: #6b7280; margin-top: 5px;">Questions fréquentes</p>
                    </a>
                    <a href="#" style="padding: 15px; background: #f9fafb; border-radius: 8px; text-align: center; transition: all 0.3s;">
                        <div style="font-size: 32px; margin-bottom: 10px;">📚</div>
                        <strong>Guides</strong>
                        <p style="font-size: 12px; color: #6b7280; margin-top: 5px;">Tutoriels détaillés</p>
                    </a>
                    <a href="#" style="padding: 15px; background: #f9fafb; border-radius: 8px; text-align: center; transition: all 0.3s;">
                        <div style="font-size: 32px; margin-bottom: 10px;"><?php echo getIcon('support'); ?></div>
                        <strong>Chat Live</strong>
                        <p style="font-size: 12px; color: #6b7280; margin-top: 5px;">Support instantané</p>
                    </a>
                    <a href="#" style="padding: 15px; background: #f9fafb; border-radius: 8px; text-align: center; transition: all 0.3s;">
                        <div style="font-size: 32px; margin-bottom: 10px;"><?php echo getIcon('mail'); ?></div>
                        <strong>Email</strong>
                        <p style="font-size: 12px; color: #6b7280; margin-top: 5px;">support@smmmaster.com</p>
                    </a>
                </div>
            </div>
        </div>

    </div> <!-- Fin padding wrapper -->

</div> <!-- Fin container-fluid -->
</div>

<?php require_once __DIR__ . '/../includes/dashboard-footer-simple.php'; ?>
