<?php
/**
 * SYSTÈME DE TRADUCTION PHP - SANS GOOGLE
 * 
 * Système de traduction basé sur des fichiers PHP
 * - Pas de dépendance externe
 * - Traduction instantanée
 * - 100% fiable
 * 
 * @version 1.0
 * @date 14/10/2025
 */

// Détecter la langue actuelle
session_start();
$current_lang = $_SESSION['lang'] ?? $_GET['lang'] ?? 'fr';
$_SESSION['lang'] = $current_lang;

// Fichier de traductions de base
$translations = [
    'fr' => [
        // Navigation
        'dashboard' => 'Tableau de bord',
        'services' => 'Services',
        'orders' => 'Commandes',
        'new_order' => 'Nouvelle commande',
        'order_history' => 'Historique',
        'balance' => 'Mon solde',
        'profile' => 'Mon profil',
        'support' => 'Support',
        'logout' => 'Déconnexion',
        
        // Page commandes
        'my_orders' => 'Mes Commandes',
        'order_number' => 'N° Commande',
        'service' => 'Service',
        'link' => 'Lien',
        'quantity' => 'Quantité',
        'amount' => 'Montant',
        'status' => 'Statut',
        'date' => 'Date',
        'actions' => 'Actions',
        'view' => 'Voir',
        'filter' => 'Filtrer',
        'reset' => 'Réinitialiser',
        'search' => 'Rechercher',
        'no_orders' => 'Aucune commande',
        
        // Statuts
        'pending' => 'En attente',
        'processing' => 'En cours',
        'completed' => 'Terminé',
        'partial' => 'Partiel',
        'canceled' => 'Annulé',
        'refunded' => 'Remboursé',
    ],
    
    'en' => [
        // Navigation
        'dashboard' => 'Dashboard',
        'services' => 'Services',
        'orders' => 'Orders',
        'new_order' => 'New Order',
        'order_history' => 'History',
        'balance' => 'My Balance',
        'profile' => 'My Profile',
        'support' => 'Support',
        'logout' => 'Logout',
        
        // Orders page
        'my_orders' => 'My Orders',
        'order_number' => 'Order #',
        'service' => 'Service',
        'link' => 'Link',
        'quantity' => 'Quantity',
        'amount' => 'Amount',
        'status' => 'Status',
        'date' => 'Date',
        'actions' => 'Actions',
        'view' => 'View',
        'filter' => 'Filter',
        'reset' => 'Reset',
        'search' => 'Search',
        'no_orders' => 'No orders',
        
        // Status
        'pending' => 'Pending',
        'processing' => 'Processing',
        'completed' => 'Completed',
        'partial' => 'Partial',
        'canceled' => 'Canceled',
        'refunded' => 'Refunded',
    ],
    
    'es' => [
        // Navigation
        'dashboard' => 'Panel',
        'services' => 'Servicios',
        'orders' => 'Pedidos',
        'new_order' => 'Nuevo Pedido',
        'order_history' => 'Historial',
        'balance' => 'Mi Saldo',
        'profile' => 'Mi Perfil',
        'support' => 'Soporte',
        'logout' => 'Salir',
        
        // Orders page
        'my_orders' => 'Mis Pedidos',
        'order_number' => 'Pedido #',
        'service' => 'Servicio',
        'link' => 'Enlace',
        'quantity' => 'Cantidad',
        'amount' => 'Monto',
        'status' => 'Estado',
        'date' => 'Fecha',
        'actions' => 'Acciones',
        'view' => 'Ver',
        'filter' => 'Filtrar',
        'reset' => 'Reiniciar',
        'search' => 'Buscar',
        'no_orders' => 'Sin pedidos',
        
        // Status
        'pending' => 'Pendiente',
        'processing' => 'Procesando',
        'completed' => 'Completado',
        'partial' => 'Parcial',
        'canceled' => 'Cancelado',
        'refunded' => 'Reembolsado',
    ],
    
    'de' => [
        // Navigation
        'dashboard' => 'Dashboard',
        'services' => 'Dienste',
        'orders' => 'Bestellungen',
        'new_order' => 'Neue Bestellung',
        'order_history' => 'Verlauf',
        'balance' => 'Mein Guthaben',
        'profile' => 'Mein Profil',
        'support' => 'Support',
        'logout' => 'Abmelden',
        
        // Orders page
        'my_orders' => 'Meine Bestellungen',
        'order_number' => 'Bestellung #',
        'service' => 'Dienst',
        'link' => 'Link',
        'quantity' => 'Menge',
        'amount' => 'Betrag',
        'status' => 'Status',
        'date' => 'Datum',
        'actions' => 'Aktionen',
        'view' => 'Ansehen',
        'filter' => 'Filtern',
        'reset' => 'Zurücksetzen',
        'search' => 'Suchen',
        'no_orders' => 'Keine Bestellungen',
        
        // Status
        'pending' => 'Ausstehend',
        'processing' => 'In Bearbeitung',
        'completed' => 'Abgeschlossen',
        'partial' => 'Teilweise',
        'canceled' => 'Abgebrochen',
        'refunded' => 'Erstattet',
    ],
];

// Fonction de traduction
function t($key, $lang = null) {
    global $translations, $current_lang;
    $lang = $lang ?? $current_lang;
    
    // Vérifier si la langue existe
    if (!isset($translations[$lang])) {
        $lang = 'fr'; // Fallback français
    }
    
    // Retourner la traduction ou la clé si non trouvée
    return $translations[$lang][$key] ?? $key;
}

// Widget de sélection de langue
?>

<!-- CSS Widget PHP Translation -->
<style>
.php-translate-widget {
    position: relative;
    display: inline-block;
    z-index: 999;
}

.php-translate-select {
    background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
}

.php-translate-select:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
}

.php-translate-select option {
    background: white;
    color: #333;
}
</style>

<!-- HTML Widget PHP -->
<div class="php-translate-widget">
    <select class="php-translate-select" onchange="changeLanguagePHP(this.value)">
        <option value="fr" <?php echo $current_lang === 'fr' ? 'selected' : ''; ?>>🇫🇷 FR</option>
        <option value="en" <?php echo $current_lang === 'en' ? 'selected' : ''; ?>>🇬🇧 EN</option>
        <option value="es" <?php echo $current_lang === 'es' ? 'selected' : ''; ?>>🇪🇸 ES</option>
        <option value="de" <?php echo $current_lang === 'de' ? 'selected' : ''; ?>>🇩🇪 DE</option>
    </select>
</div>

<!-- JavaScript pour changement de langue -->
<script>
function changeLanguagePHP(lang) {
    // Construire l'URL avec le paramètre lang
    const url = new URL(window.location);
    url.searchParams.set('lang', lang);
    
    // Recharger avec la nouvelle langue
    window.location.href = url.toString();
}
</script>
