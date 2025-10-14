<?php
/**
 * Analyse des données de l'API SMMFollows
 * Compare ce que l'API retourne vs ce qu'on stocke
 */
require_once '../config.php';
require_once '../functions.php';
require_once '../api/SMMFollowsAPI.php';

// Vérifier si admin
if (!isLoggedIn() || !isAdmin()) {
    die('Accès non autorisé');
}

echo "<h1>🔍 Analyse des Données API SMMFollows</h1>";
echo "<style>
    body { font-family: 'Inter', sans-serif; padding: 20px; background: #f5f5f5; }
    .section { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #667eea; color: white; }
    .warning { background: #fef3c7; padding: 15px; border-left: 4px solid #f59e0b; margin: 20px 0; }
    .success { background: #d1fae5; padding: 15px; border-left: 4px solid #10b981; margin: 20px 0; }
    .error { background: #fee2e2; padding: 15px; border-left: 4px solid #ef4444; margin: 20px 0; }
    pre { background: #1f2937; color: #fff; padding: 15px; border-radius: 5px; overflow-x: auto; }
    .missing { color: #ef4444; font-weight: bold; }
    .present { color: #10b981; font-weight: bold; }
</style>";

// 1. Récupérer un échantillon de l'API
echo "<div class='section'>";
echo "<h2>📡 1. Données brutes de l'API (échantillon)</h2>";

try {
    $api_key = getSetting($pdo, 'smmfollows_api_key', '');
    
    if (empty($api_key)) {
        echo "<div class='error'>❌ API Key non configurée. <a href='settings.php'>Configurer maintenant</a></div>";
        exit;
    }
    
    $api = new SMMFollowsAPI($api_key);
    $services = $api->getServices();
    
    if (empty($services)) {
        echo "<div class='error'>❌ Aucun service retourné par l'API</div>";
        exit;
    }
    
    echo "<div class='success'>✅ " . count($services) . " services récupérés de l'API</div>";
    
    // Afficher un échantillon de 3 services
    echo "<h3>Échantillon de 3 services :</h3>";
    $sample = array_slice($services, 0, 3);
    
    foreach ($sample as $index => $service) {
        echo "<h4>Service #" . ($index + 1) . "</h4>";
        echo "<pre>" . json_encode($service, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
    }
    
    // 2. Analyser les champs disponibles
    echo "<h2>📊 2. Champs disponibles dans l'API</h2>";
    
    $all_keys = [];
    foreach ($services as $service) {
        $all_keys = array_merge($all_keys, array_keys($service));
    }
    $all_keys = array_unique($all_keys);
    sort($all_keys);
    
    echo "<p><strong>Champs trouvés dans l'API :</strong></p>";
    echo "<ul>";
    foreach ($all_keys as $key) {
        echo "<li><code>$key</code></li>";
    }
    echo "</ul>";
    
    // 3. Vérifier la structure de la table services
    echo "<h2>🗄️ 3. Structure de la table `services` en BDD</h2>";
    
    $stmt = $pdo->query("DESCRIBE services");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table>";
    echo "<tr><th>Colonne</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td><strong>{$col['Field']}</strong></td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    $db_columns = array_column($columns, 'Field');
    
    // 4. Comparaison API vs BDD
    echo "<h2>⚖️ 4. Comparaison des champs</h2>";
    
    // Mapping API -> BDD actuel
    $current_mapping = [
        'service' => 'provider_id',
        'name' => 'name',
        'type' => 'category (via mapCategory)',
        'rate' => 'cost_price + sell_price (via calculateSellPrice)',
        'min' => 'min_quantity',
        'max' => 'max_quantity',
    ];
    
    echo "<h3>🟢 Champs actuellement mappés :</h3>";
    echo "<table>";
    echo "<tr><th>Champ API</th><th>Champ BDD</th><th>Note</th></tr>";
    foreach ($current_mapping as $api_field => $db_info) {
        echo "<tr>";
        echo "<td><code>$api_field</code></td>";
        echo "<td>$db_info</td>";
        echo "<td class='present'>✅ Mappé</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Champs API non utilisés
    $mapped_api_fields = array_keys($current_mapping);
    $unused_api_fields = array_diff($all_keys, $mapped_api_fields);
    
    if (!empty($unused_api_fields)) {
        echo "<div class='warning'>";
        echo "<h3>⚠️ Champs API NON utilisés actuellement :</h3>";
        echo "<ul>";
        foreach ($unused_api_fields as $field) {
            // Vérifier quelques exemples de valeurs
            $examples = [];
            foreach (array_slice($services, 0, 3) as $s) {
                if (isset($s[$field])) {
                    $examples[] = is_array($s[$field]) ? json_encode($s[$field]) : $s[$field];
                }
            }
            $example_text = !empty($examples) ? " (ex: " . implode(', ', array_unique($examples)) . ")" : "";
            echo "<li class='missing'><code>$field</code>$example_text</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
    
    // 5. Recommandations
    echo "<h2>💡 5. Recommandations d'amélioration</h2>";
    
    echo "<div class='section'>";
    echo "<h3>Champs à ajouter dans la BDD :</h3>";
    
    $recommendations = [
        ['field' => 'dripfeed', 'type' => 'BOOLEAN', 'reason' => 'Gestion du dripfeed (livraison progressive)'],
        ['field' => 'cancel', 'type' => 'BOOLEAN', 'reason' => 'Possibilité d\'annuler une commande'],
        ['field' => 'category', 'type' => 'VARCHAR(100)', 'reason' => 'Catégorie exacte de l\'API'],
        ['field' => 'refill_type', 'type' => 'VARCHAR(50)', 'reason' => 'Type de refill (button, lifetime, etc.)'],
        ['field' => 'average_time', 'type' => 'VARCHAR(100)', 'reason' => 'Temps moyen d\'exécution'],
    ];
    
    echo "<table>";
    echo "<tr><th>Champ suggéré</th><th>Type SQL</th><th>Raison</th></tr>";
    foreach ($recommendations as $rec) {
        $exists = in_array($rec['field'], $db_columns) ? '✅ Existe déjà' : '❌ À ajouter';
        echo "<tr>";
        echo "<td><code>{$rec['field']}</code> $exists</td>";
        echo "<td>{$rec['type']}</td>";
        echo "<td>{$rec['reason']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    
    // 6. Recherche des services manquants (LoyalFans, Kick, etc.)
    echo "<h2>🔍 6. Plateformes disponibles dans l'API</h2>";
    
    $platforms = [];
    foreach ($services as $service) {
        $name = $service['name'] ?? '';
        // Extraire la plateforme (premier mot souvent)
        if (preg_match('/^([A-Za-z]+)/', $name, $matches)) {
            $platform = $matches[1];
            if (!isset($platforms[$platform])) {
                $platforms[$platform] = 0;
            }
            $platforms[$platform]++;
        }
    }
    
    arsort($platforms);
    
    echo "<table>";
    echo "<tr><th>Plateforme</th><th>Nombre de services</th></tr>";
    foreach (array_slice($platforms, 0, 30) as $platform => $count) {
        echo "<tr>";
        echo "<td><strong>$platform</strong></td>";
        echo "<td>$count</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<div class='warning'>";
    echo "<p><strong>Note :</strong> Si des plateformes comme <strong>LoyalFans</strong>, <strong>Kick</strong>, etc. apparaissent ici mais ne sont pas visibles sur le site, c'est parce que la fonction <code>extractPlatform()</code> ne les reconnaît pas correctement.</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur : " . $e->getMessage() . "</div>";
}

echo "</div>";

echo "<hr>";
echo "<p><a href='sync-services.php' style='padding: 10px 20px; background: #10b981; color: white; text-decoration: none; border-radius: 5px;'>🔄 Synchroniser maintenant</a></p>";
echo "<p><a href='check-prices.php' style='padding: 10px 20px; background: #f59e0b; color: white; text-decoration: none; border-radius: 5px;'>💰 Vérifier les prix</a></p>";
?>
