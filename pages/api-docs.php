<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation API - SMM Mastery</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px;
        }
        
        .section {
            margin-bottom: 40px;
        }
        
        .section h2 {
            color: #1f2937;
            font-size: 24px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .endpoint {
            background: #f9fafb;
            border-left: 4px solid #2563eb;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .endpoint h3 {
            color: #2563eb;
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .method {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            margin-right: 10px;
        }
        
        .method.get {
            background: #d1fae5;
            color: #065f46;
        }
        
        .method.post {
            background: #dbeafe;
            color: #1e40af;
        }
        
        pre {
            background: #1f2937;
            color: #10b981;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 15px 0;
            font-size: 14px;
            line-height: 1.6;
        }
        
        code {
            font-family: 'Courier New', monospace;
        }
        
        .warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .warning strong {
            color: #92400e;
        }
        
        .info {
            background: #dbeafe;
            border-left: 4px solid #2563eb;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .params {
            margin: 15px 0;
        }
        
        .params table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .params th {
            background: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
        }
        
        .params td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .params tr:last-child td {
            border-bottom: none;
        }
        
        .required {
            color: #ef4444;
            font-weight: 600;
        }
        
        .optional {
            color: #6b7280;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Documentation API</h1>
            <p>Intégrez facilement nos services SMM dans vos applications</p>
        </div>
        
        <div class="content">
            
            <!-- Introduction -->
            <div class="section">
                <h2>📖 Introduction</h2>
                <p>Bienvenue dans la documentation de l'API SMM Mastery. Notre API RESTful vous permet d'automatiser la gestion de vos commandes de services SMM (Social Media Marketing).</p>
                
                <div class="info">
                    <strong>Base URL:</strong> <code>https://votre-domaine.com/api/</code>
                </div>
                
                <div class="warning">
                    <strong>⚠️ Sécurité:</strong> Ne partagez JAMAIS votre clé API. Elle donne un accès complet à votre compte.
                </div>
            </div>
            
            <!-- Authentification -->
            <div class="section">
                <h2>🔑 Authentification</h2>
                <p>Toutes les requêtes API doivent inclure votre clé API dans les headers.</p>
                
                <pre>
Headers:
  Authorization: Bearer VOTRE_CLE_API_64_CARACTERES
  Content-Type: application/json
                </pre>
                
                <p style="margin-top: 15px;"><strong>Comment obtenir votre clé API ?</strong></p>
                <ol style="margin-left: 20px; margin-top: 10px; line-height: 1.8;">
                    <li>Connectez-vous à votre dashboard</li>
                    <li>Allez dans "Mon Profil"</li>
                    <li>Section "Clé API pour développeurs"</li>
                    <li>Cliquez sur "Générer une clé API"</li>
                    <li>Copiez votre clé (64 caractères hexadécimaux)</li>
                </ol>
            </div>
            
            <!-- Endpoints -->
            <div class="section">
                <h2>🔌 Endpoints Disponibles</h2>
                
                <!-- Balance -->
                <div class="endpoint">
                    <h3><span class="method get">GET</span> Consulter votre solde</h3>
                    <p><code>/api/balance</code></p>
                    
                    <p style="margin-top: 15px;"><strong>Exemple de requête:</strong></p>
                    <pre>
curl -X GET "https://votre-domaine.com/api/balance" \
  -H "Authorization: Bearer VOTRE_CLE_API" \
  -H "Content-Type: application/json"
                    </pre>
                    
                    <p style="margin-top: 15px;"><strong>Réponse:</strong></p>
                    <pre>
{
  "success": true,
  "data": {
    "balance": 125.50,
    "currency": "USD"
  }
}
                    </pre>
                </div>
                
                <!-- Services -->
                <div class="endpoint">
                    <h3><span class="method get">GET</span> Liste des services</h3>
                    <p><code>/api/services</code></p>
                    
                    <p style="margin-top: 15px;"><strong>Paramètres optionnels:</strong></p>
                    <div class="params">
                        <table>
                            <tr>
                                <th>Paramètre</th>
                                <th>Type</th>
                                <th>Description</th>
                            </tr>
                            <tr>
                                <td>platform</td>
                                <td>string</td>
                                <td class="optional">Filtrer par plateforme (instagram, youtube, tiktok, etc.)</td>
                            </tr>
                            <tr>
                                <td>tier</td>
                                <td>string</td>
                                <td class="optional">Filtrer par tier (budget, standard, premium, ultimate)</td>
                            </tr>
                        </table>
                    </div>
                    
                    <p style="margin-top: 15px;"><strong>Exemple de requête:</strong></p>
                    <pre>
curl -X GET "https://votre-domaine.com/api/services?platform=instagram" \
  -H "Authorization: Bearer VOTRE_CLE_API" \
  -H "Content-Type: application/json"
                    </pre>
                    
                    <p style="margin-top: 15px;"><strong>Réponse:</strong></p>
                    <pre>
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Instagram Followers - Standard",
      "platform": "instagram",
      "tier": "standard",
      "price": "2.50",
      "min_quantity": 100,
      "max_quantity": 10000,
      "description": "Followers de qualité standard"
    },
    ...
  ]
}
                    </pre>
                </div>
                
                <!-- Créer une commande -->
                <div class="endpoint">
                    <h3><span class="method post">POST</span> Créer une commande</h3>
                    <p><code>/api/orders</code></p>
                    
                    <p style="margin-top: 15px;"><strong>Paramètres requis:</strong></p>
                    <div class="params">
                        <table>
                            <tr>
                                <th>Paramètre</th>
                                <th>Type</th>
                                <th>Description</th>
                            </tr>
                            <tr>
                                <td>service_id</td>
                                <td>integer</td>
                                <td class="required">ID du service (requis)</td>
                            </tr>
                            <tr>
                                <td>link</td>
                                <td>string</td>
                                <td class="required">URL de la page cible (requis)</td>
                            </tr>
                            <tr>
                                <td>quantity</td>
                                <td>integer</td>
                                <td class="required">Quantité désirée (requis)</td>
                            </tr>
                        </table>
                    </div>
                    
                    <p style="margin-top: 15px;"><strong>Exemple de requête:</strong></p>
                    <pre>
curl -X POST "https://votre-domaine.com/api/orders" \
  -H "Authorization: Bearer VOTRE_CLE_API" \
  -H "Content-Type: application/json" \
  -d '{
    "service_id": 1,
    "link": "https://instagram.com/username",
    "quantity": 1000
  }'
                    </pre>
                    
                    <p style="margin-top: 15px;"><strong>Réponse:</strong></p>
                    <pre>
{
  "success": true,
  "data": {
    "order_id": 12345,
    "order_number": "ORD-20251012-12345",
    "status": "pending",
    "service_name": "Instagram Followers - Standard",
    "quantity": 1000,
    "link": "https://instagram.com/username",
    "total_cost": 25.00,
    "created_at": "2025-10-12 14:30:00"
  }
}
                    </pre>
                </div>
                
                <!-- Statut commande -->
                <div class="endpoint">
                    <h3><span class="method get">GET</span> Statut d'une commande</h3>
                    <p><code>/api/orders/{order_id}</code></p>
                    
                    <p style="margin-top: 15px;"><strong>Exemple de requête:</strong></p>
                    <pre>
curl -X GET "https://votre-domaine.com/api/orders/12345" \
  -H "Authorization: Bearer VOTRE_CLE_API" \
  -H "Content-Type: application/json"
                    </pre>
                    
                    <p style="margin-top: 15px;"><strong>Réponse:</strong></p>
                    <pre>
{
  "success": true,
  "data": {
    "order_id": 12345,
    "order_number": "ORD-20251012-12345",
    "status": "processing",
    "start_count": 5230,
    "remains": 350,
    "delivered": 650,
    "progress": 65,
    "created_at": "2025-10-12 14:30:00",
    "updated_at": "2025-10-12 15:45:00"
  }
}
                    </pre>
                </div>
                
                <!-- Historique -->
                <div class="endpoint">
                    <h3><span class="method get">GET</span> Historique des commandes</h3>
                    <p><code>/api/orders</code></p>
                    
                    <p style="margin-top: 15px;"><strong>Paramètres optionnels:</strong></p>
                    <div class="params">
                        <table>
                            <tr>
                                <th>Paramètre</th>
                                <th>Type</th>
                                <th>Description</th>
                            </tr>
                            <tr>
                                <td>status</td>
                                <td>string</td>
                                <td class="optional">Filtrer par statut (pending, processing, completed, etc.)</td>
                            </tr>
                            <tr>
                                <td>limit</td>
                                <td>integer</td>
                                <td class="optional">Nombre de résultats (défaut: 50, max: 100)</td>
                            </tr>
                            <tr>
                                <td>offset</td>
                                <td>integer</td>
                                <td class="optional">Décalage pour pagination (défaut: 0)</td>
                            </tr>
                        </table>
                    </div>
                    
                    <p style="margin-top: 15px;"><strong>Exemple de requête:</strong></p>
                    <pre>
curl -X GET "https://votre-domaine.com/api/orders?status=completed&limit=10" \
  -H "Authorization: Bearer VOTRE_CLE_API" \
  -H "Content-Type: application/json"
                    </pre>
                </div>
            </div>
            
            <!-- Codes de statut -->
            <div class="section">
                <h2>📊 Codes de Statut des Commandes</h2>
                
                <div class="params">
                    <table>
                        <tr>
                            <th>Statut</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td><code>pending</code></td>
                            <td>Commande en attente de traitement</td>
                        </tr>
                        <tr>
                            <td><code>processing</code></td>
                            <td>Commande en cours de livraison</td>
                        </tr>
                        <tr>
                            <td><code>completed</code></td>
                            <td>Commande terminée avec succès</td>
                        </tr>
                        <tr>
                            <td><code>partial</code></td>
                            <td>Commande partiellement livrée</td>
                        </tr>
                        <tr>
                            <td><code>canceled</code></td>
                            <td>Commande annulée</td>
                        </tr>
                        <tr>
                            <td><code>refunded</code></td>
                            <td>Commande remboursée</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Codes HTTP -->
            <div class="section">
                <h2>🌐 Codes de Réponse HTTP</h2>
                
                <div class="params">
                    <table>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td><code>200</code></td>
                            <td>Succès</td>
                        </tr>
                        <tr>
                            <td><code>201</code></td>
                            <td>Créé (nouvelle commande)</td>
                        </tr>
                        <tr>
                            <td><code>400</code></td>
                            <td>Requête invalide (paramètres manquants ou incorrects)</td>
                        </tr>
                        <tr>
                            <td><code>401</code></td>
                            <td>Non autorisé (clé API invalide ou manquante)</td>
                        </tr>
                        <tr>
                            <td><code>403</code></td>
                            <td>Interdit (solde insuffisant)</td>
                        </tr>
                        <tr>
                            <td><code>404</code></td>
                            <td>Non trouvé (service ou commande introuvable)</td>
                        </tr>
                        <tr>
                            <td><code>429</code></td>
                            <td>Trop de requêtes (rate limit dépassé)</td>
                        </tr>
                        <tr>
                            <td><code>500</code></td>
                            <td>Erreur serveur</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Exemples de code -->
            <div class="section">
                <h2>💻 Exemples de Code</h2>
                
                <h3 style="color: #2563eb; margin-bottom: 15px;">PHP</h3>
                <pre>
&lt;?php
$api_key = 'VOTRE_CLE_API_64_CARACTERES';
$api_url = 'https://votre-domaine.com/api';

// Configuration cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $api_key,
    'Content-Type: application/json'
]);

// Consulter le solde
curl_setopt($ch, CURLOPT_URL, $api_url . '/balance');
$response = curl_exec($ch);
$data = json_decode($response, true);
echo "Solde: $" . $data['data']['balance'];

// Créer une commande
$order_data = [
    'service_id' => 1,
    'link' => 'https://instagram.com/username',
    'quantity' => 1000
];

curl_setopt($ch, CURLOPT_URL, $api_url . '/orders');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_data));
$response = curl_exec($ch);
$result = json_decode($response, true);

if ($result['success']) {
    echo "Commande créée: " . $result['data']['order_number'];
}

curl_close($ch);
?&gt;
                </pre>
                
                <h3 style="color: #2563eb; margin: 30px 0 15px;">Python</h3>
                <pre>
import requests

API_KEY = 'VOTRE_CLE_API_64_CARACTERES'
API_URL = 'https://votre-domaine.com/api'

headers = {
    'Authorization': f'Bearer {API_KEY}',
    'Content-Type': 'application/json'
}

# Consulter le solde
response = requests.get(f'{API_URL}/balance', headers=headers)
data = response.json()
print(f"Solde: ${data['data']['balance']}")

# Créer une commande
order_data = {
    'service_id': 1,
    'link': 'https://instagram.com/username',
    'quantity': 1000
}

response = requests.post(f'{API_URL}/orders', 
                        json=order_data, 
                        headers=headers)
result = response.json()

if result['success']:
    print(f"Commande créée: {result['data']['order_number']}")
                </pre>
                
                <h3 style="color: #2563eb; margin: 30px 0 15px;">JavaScript (Node.js)</h3>
                <pre>
const axios = require('axios');

const API_KEY = 'VOTRE_CLE_API_64_CARACTERES';
const API_URL = 'https://votre-domaine.com/api';

const headers = {
    'Authorization': `Bearer ${API_KEY}`,
    'Content-Type': 'application/json'
};

// Consulter le solde
axios.get(`${API_URL}/balance`, { headers })
    .then(response => {
        console.log(`Solde: $${response.data.data.balance}`);
    });

// Créer une commande
const orderData = {
    service_id: 1,
    link: 'https://instagram.com/username',
    quantity: 1000
};

axios.post(`${API_URL}/orders`, orderData, { headers })
    .then(response => {
        if (response.data.success) {
            console.log(`Commande créée: ${response.data.data.order_number}`);
        }
    })
    .catch(error => {
        console.error('Erreur:', error.response.data);
    });
                </pre>
            </div>
            
            <!-- Rate Limiting -->
            <div class="section">
                <h2>⚡ Rate Limiting</h2>
                
                <div class="info">
                    <p><strong>Limite actuelle:</strong> 60 requêtes par minute</p>
                    <p style="margin-top: 10px;">Si vous dépassez cette limite, vous recevrez une erreur <code>429 Too Many Requests</code></p>
                </div>
                
                <p style="margin-top: 20px;"><strong>Headers de rate limit dans chaque réponse:</strong></p>
                <pre>
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 45
X-RateLimit-Reset: 1697123456
                </pre>
            </div>
            
            <!-- Support -->
            <div class="section">
                <h2>💬 Support</h2>
                <p>Besoin d'aide ? Contactez notre équipe :</p>
                <ul style="margin-left: 20px; margin-top: 15px; line-height: 1.8;">
                    <li>📧 Email: support@votre-domaine.com</li>
                    <li>💬 Chat: Disponible dans votre dashboard</li>
                    <li>📝 Tickets: <a href="../support/new-ticket.php" style="color: #2563eb;">Créer un ticket</a></li>
                </ul>
            </div>
            
        </div>
    </div>
    
    <div style="text-align: center; padding: 40px 0; color: white;">
        <p style="font-size: 14px;">📚 Documentation API - SMM Mastery</p>
        <p style="font-size: 12px; margin-top: 5px; opacity: 0.8;">Version 1.0 - Mise à jour: 12 octobre 2025</p>
    </div>
</body>
</html>
