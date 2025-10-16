<?php
/**
 * COMPOSANT PAGE HEADER - Standardisé
 * Header animé et moderne pour toutes les pages dashboard
 * 
 * Variables requises:
 * - $page_header_title (string) - Titre principal
 * - $page_header_icon (string, optional) - Nom de l'icône
 * - $page_header_description (string, optional) - Description sous le titre
 * - $page_header_gradient (bool, optional) - Activer le gradient sur le titre
 */

// Valeurs par défaut
$page_header_icon = $page_header_icon ?? 'dashboard';
$page_header_description = $page_header_description ?? '';
$page_header_gradient = $page_header_gradient ?? false;
?>

<!-- Page Header Section -->
<div class="page-header-modern" style="margin-bottom: 24px; animation: slideDown 0.3s ease-out;">
    <h1 class="page-header-title" style="font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 4px 0; display: flex; align-items: center; gap: 10px;">
        <?php echo getIcon($page_header_icon, false, 'lg'); ?>
        <?php if ($page_header_gradient): ?>
            <span style="background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                <?php echo clean($page_header_title); ?>
            </span>
        <?php else: ?>
            <?php echo clean($page_header_title); ?>
        <?php endif; ?>
    </h1>
    
    <?php if ($page_header_description): ?>
        <p class="page-header-description" style="font-size: 14px; color: #6b7280; margin: 0; display: flex; align-items: center; gap: 6px;">
            <?php echo $page_header_description; ?>
        </p>
    <?php endif; ?>
</div>

<style>
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.page-header-modern {
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 16px;
}

.page-header-title {
    transition: color 0.2s ease;
}

.page-header-description strong {
    color: #2563eb;
    font-weight: 600;
}

@media (max-width: 768px) {
    .page-header-title {
        font-size: 20px !important;
    }
    
    .page-header-description {
        font-size: 13px !important;
    }
}
</style>
