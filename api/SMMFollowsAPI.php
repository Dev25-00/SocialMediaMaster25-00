<?php
/**
 * SMM Mastery - SMMFollows API Connector
 * Version: 1.0
 * 
 * Ce fichier gère toutes les interactions avec l'API SMMFollows
 */

class SMMFollowsAPI {
    
    private $api_url = 'https://smmfollows.com/api/v2';
    private $api_key;
    
    public function __construct($api_key = null) {
        $this->api_key = $api_key ?: SMMFOLLOWS_API_KEY;
        
        if (empty($this->api_key)) {
            throw new Exception('SMMFollows API key non configurée');
        }
    }
    
    /**
     * Faire une requête à l'API
     */
    private function request($params) {
        $params['key'] = $this->api_key;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($error) {
            throw new Exception("Erreur cURL: $error");
        }
        
        if ($http_code !== 200) {
            throw new Exception("Erreur HTTP: $http_code");
        }
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Erreur JSON: " . json_last_error_msg());
        }
        
        // Vérifier si erreur dans la réponse
        if (isset($data['error'])) {
            throw new Exception("Erreur API: " . $data['error']);
        }
        
        return $data;
    }
    
    /**
     * Obtenir tous les services
     */
    public function getServices() {
        try {
            return $this->request(['action' => 'services']);
        } catch (Exception $e) {
            error_log("SMMFollows getServices error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Créer une nouvelle commande
     * 
     * @param int $service_id ID du service provider
     * @param string $link URL cible
     * @param int $quantity Quantité
     * @param array $options Options additionnelles (runs, interval pour drip-feed)
     * @return array Réponse API
     */
    public function createOrder($service_id, $link, $quantity, $options = []) {
        $params = [
            'action' => 'add',
            'service' => $service_id,
            'link' => $link,
            'quantity' => $quantity
        ];
        
        // Ajouter drip-feed si spécifié
        if (isset($options['runs']) && isset($options['interval'])) {
            $params['runs'] = $options['runs'];
            $params['interval'] = $options['interval'];
        }
        
        return $this->request($params);
    }
    
    /**
     * Obtenir le statut d'une commande
     */
    public function getOrderStatus($order_id) {
        $params = [
            'action' => 'status',
            'order' => $order_id
        ];
        
        return $this->request($params);
    }
    
    /**
     * Obtenir le statut de plusieurs commandes
     */
    public function getMultipleOrderStatus($order_ids) {
        $params = [
            'action' => 'status',
            'orders' => implode(',', $order_ids)
        ];
        
        return $this->request($params);
    }
    
    /**
     * Créer une demande de refill
     */
    public function createRefill($order_id) {
        $params = [
            'action' => 'refill',
            'order' => $order_id
        ];
        
        return $this->request($params);
    }
    
    /**
     * Vérifier le statut d'un refill
     */
    public function getRefillStatus($refill_id) {
        $params = [
            'action' => 'refill_status',
            'refill' => $refill_id
        ];
        
        return $this->request($params);
    }
    
    /**
     * Obtenir le solde du compte SMMFollows
     */
    public function getBalance() {
        try {
            $params = ['action' => 'balance'];
            $result = $this->request($params);
            return $result['balance'] ?? 0;
        } catch (Exception $e) {
            error_log("SMMFollows getBalance error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Mapper les catégories SMMFollows vers nos catégories
     */
    public static function mapCategory($service_name, $service_type = '') {
        $name_lower = strtolower($service_name);
        
        // Instagram
        if (strpos($name_lower, 'instagram') !== false || strpos($name_lower, 'ig ') !== false) {
            if (strpos($name_lower, 'follower') !== false) return 'Instagram Followers';
            if (strpos($name_lower, 'like') !== false) return 'Instagram Likes';
            if (strpos($name_lower, 'view') !== false) return 'Instagram Views';
            if (strpos($name_lower, 'comment') !== false) return 'Instagram Comments';
            if (strpos($name_lower, 'story') !== false) return 'Instagram Story Views';
            if (strpos($name_lower, 'igtv') !== false) return 'Instagram IGTV';
            if (strpos($name_lower, 'reel') !== false) return 'Instagram Reels';
            return 'Instagram Other';
        }
        
        // YouTube
        if (strpos($name_lower, 'youtube') !== false || strpos($name_lower, 'yt ') !== false) {
            if (strpos($name_lower, 'subscriber') !== false) return 'YouTube Subscribers';
            if (strpos($name_lower, 'view') !== false) return 'YouTube Views';
            if (strpos($name_lower, 'like') !== false) return 'YouTube Likes';
            if (strpos($name_lower, 'comment') !== false) return 'YouTube Comments';
            if (strpos($name_lower, 'watch') !== false) return 'YouTube Watch Time';
            return 'YouTube Other';
        }
        
        // TikTok
        if (strpos($name_lower, 'tiktok') !== false || strpos($name_lower, 'tik tok') !== false) {
            if (strpos($name_lower, 'follower') !== false) return 'TikTok Followers';
            if (strpos($name_lower, 'like') !== false) return 'TikTok Likes';
            if (strpos($name_lower, 'view') !== false) return 'TikTok Views';
            if (strpos($name_lower, 'share') !== false) return 'TikTok Shares';
            return 'TikTok Other';
        }
        
        // Facebook
        if (strpos($name_lower, 'facebook') !== false || strpos($name_lower, 'fb ') !== false) {
            if (strpos($name_lower, 'page like') !== false) return 'Facebook Page Likes';
            if (strpos($name_lower, 'follower') !== false) return 'Facebook Followers';
            if (strpos($name_lower, 'post like') !== false) return 'Facebook Post Likes';
            if (strpos($name_lower, 'view') !== false) return 'Facebook Views';
            return 'Facebook Other';
        }
        
        // Twitter/X
        if (strpos($name_lower, 'twitter') !== false || strpos($name_lower, 'x ') !== false) {
            if (strpos($name_lower, 'follower') !== false) return 'Twitter Followers';
            if (strpos($name_lower, 'like') !== false) return 'Twitter Likes';
            if (strpos($name_lower, 'retweet') !== false) return 'Twitter Retweets';
            return 'Twitter Other';
        }
        
        // LinkedIn
        if (strpos($name_lower, 'linkedin') !== false) {
            if (strpos($name_lower, 'follower') !== false) return 'LinkedIn Followers';
            if (strpos($name_lower, 'connection') !== false) return 'LinkedIn Connections';
            return 'LinkedIn Other';
        }
        
        return 'Other';
    }
    
    /**
     * Extraire le nom de la plateforme
     * MISE À JOUR 13/10/2025 - Reconnaissance complète de toutes les plateformes API
     */
    public static function extractPlatform($service_name) {
        $name_lower = strtolower($service_name);
        
        // Mapping exhaustif des plateformes (basé sur analyse API)
        $platforms = [
            // Principales (par ordre de priorité)
            'instagram' => 'Instagram',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
            'tik tok' => 'TikTok',
            'spotify' => 'Spotify',
            'twitter' => 'Twitter',
            'telegram' => 'Telegram',
            'website' => 'Website',
            'twitch' => 'Twitch',
            
            // Émergentes et niches
            'rumble' => 'Rumble',
            'kick' => 'Kick',
            'kwai' => 'Kwai',
            'snapchat' => 'Snapchat',
            'reddit' => 'Reddit',
            'audiomack' => 'Audiomack',
            'quora' => 'Quora',
            'tumblr' => 'Tumblr',
            'truth social' => 'Truth Social',
            'truth' => 'Truth Social',
            'linkedin' => 'LinkedIn',
            'soundcloud' => 'SoundCloud',
            'bluesky' => 'BlueSky',
            'pinterest' => 'Pinterest',
            'medium' => 'Medium',
            'rutube' => 'Rutube',
            'apple music' => 'Apple Music',
            'apple' => 'Apple Music',
            'chzzk' => 'Chzzk',
            'square' => 'Square',
            
            // Anciennes (cas particuliers)
            'facebook' => 'Facebook',
            'fb ' => 'Facebook',
            'ig ' => 'Instagram',
            'yt ' => 'YouTube',
            
            // Génériques
            'mobile' => 'Mobile',
            'worldwide' => 'Worldwide',
        ];
        
        // Recherche par ordre de priorité
        foreach ($platforms as $keyword => $platform_name) {
            if (strpos($name_lower, $keyword) !== false) {
                return $platform_name;
            }
        }
        
        // Extraction du premier mot en majuscule (fallback)
        if (preg_match('/^([A-Z][a-z]+)/', $service_name, $matches)) {
            return $matches[1];
        }
        
        return 'Other';
    }
    
    /**
     * Déterminer le tier basé sur le prix
     */
    public static function determineTier($rate_per_1k) {
        if ($rate_per_1k <= 1.50) {
            return 'budget';
        } elseif ($rate_per_1k <= 8.00) {
            return 'standard';
        } elseif ($rate_per_1k <= 25.00) {
            return 'premium';
        } else {
            return 'ultimate';
        }
    }
    
    /**
     * Calculer le prix de vente selon le tier
     */
    public static function calculateSellPrice($cost_price, $tier) {
        $multipliers = [
            'budget' => 5.0,    // 400% marge
            'standard' => 2.5,  // 150% marge
            'premium' => 2.0,   // 100% marge
            'ultimate' => 1.5   // 50% marge
        ];
        
        $multiplier = $multipliers[$tier] ?? 2.0;
        return round($cost_price * $multiplier, 4);
    }
    
    /**
     * Déterminer le drop rate
     */
    public static function determineDropRate($service_name) {
        $name_lower = strtolower($service_name);
        
        // Patterns pour No Drop
        $no_drop_patterns = ['no drop', 'nodrop', 'non drop', '0% drop', 'lifetime'];
        foreach ($no_drop_patterns as $pattern) {
            if (strpos($name_lower, $pattern) !== false) {
                return 'No Drop';
            }
        }
        
        // Patterns pour Low Drop
        $low_drop_patterns = ['low drop', 'stable', 'quality', 'premium', 'real'];
        foreach ($low_drop_patterns as $pattern) {
            if (strpos($name_lower, $pattern) !== false) {
                return 'Low Drop';
            }
        }
        
        // Patterns pour High Drop
        $high_drop_patterns = ['high drop', 'cheap', 'instant', 'fast'];
        foreach ($high_drop_patterns as $pattern) {
            if (strpos($name_lower, $pattern) !== false) {
                return 'High Drop';
            }
        }
        
        return 'Unknown';
    }
    
    /**
     * Extraire les jours de refill depuis le nom du service
     */
    public static function extractRefillDays($service_name) {
        $name_lower = strtolower($service_name);
        
        // Lifetime refill
        if (strpos($name_lower, 'lifetime') !== false || strpos($name_lower, 'life time') !== false) {
            return 365; // 1 an comme approximation
        }
        
        // Patterns de jours spécifiques
        if (preg_match('/(\d+)\s*day/i', $service_name, $matches)) {
            return (int)$matches[1];
        }
        
        // Refill button/available
        if (strpos($name_lower, 'refill') !== false && strpos($name_lower, 'no refill') === false) {
            return 30; // Par défaut 30 jours
        }
        
        // No refill
        if (strpos($name_lower, 'no refill') !== false) {
            return 0;
        }
        
        return null; // Pas de refill mentionné
    }
    
    /**
     * Parser la description riche de l'API
     * NOUVELLE FONCTION 13/10/2025
     * 
     * Extrait toutes les métadonnées du nom du service:
     * - Quality (High, Real, Premium, etc.)
     * - Location (Global, USA, Worldwide, etc.)
     * - Speed (Fast, Instant, Up To XXK/Day, etc.)
     * - Drop (No Drop, Low Drop, etc.)
     * - Refill Type (Button, Lifetime Guaranteed, etc.)
     * - Average Time (estimation)
     * 
     * @param string $service_name Nom complet du service
     * @return array Données extraites
     */
    public static function parseRichDescription($service_name) {
        $data = [
            'quality' => null,
            'location' => null,
            'speed' => null,
            'drop_info' => null,
            'refill_info' => null,
            'average_time' => null,
            'max_info' => null
        ];
        
        $name_lower = strtolower($service_name);
        
        // 1. QUALITY (✅Quality: XXX)
        if (preg_match('/✅\s*quality:\s*([^|]+)/i', $service_name, $matches)) {
            $data['quality'] = trim($matches[1]);
        } elseif (strpos($name_lower, 'real') !== false) {
            $data['quality'] = 'Real';
        } elseif (strpos($name_lower, 'high quality') !== false || strpos($name_lower, 'hq') !== false) {
            $data['quality'] = 'High';
        } elseif (strpos($name_lower, 'premium') !== false) {
            $data['quality'] = 'Premium';
        }
        
        // 2. LOCATION (🌍Location: XXX)
        if (preg_match('/🌍\s*location:\s*([^|]+)/i', $service_name, $matches)) {
            $data['location'] = trim($matches[1]);
        } elseif (strpos($name_lower, 'global') !== false) {
            $data['location'] = 'Global';
        } elseif (strpos($name_lower, 'worldwide') !== false) {
            $data['location'] = 'Worldwide';
        } elseif (preg_match('/(usa|us |american|europe|eu |asian)/i', $service_name, $matches)) {
            $data['location'] = ucfirst(strtolower($matches[1]));
        }
        
        // 3. SPEED (⚡Speed: XXX)
        if (preg_match('/⚡\s*speed:\s*([^|]+)/i', $service_name, $matches)) {
            $data['speed'] = trim($matches[1]);
        } elseif (preg_match('/up to ([\d,]+k?)\/day/i', $service_name, $matches)) {
            $data['speed'] = 'Up To ' . $matches[1] . '/Day';
        } elseif (strpos($name_lower, 'instant') !== false) {
            $data['speed'] = 'Instant';
        } elseif (strpos($name_lower, 'fast') !== false) {
            $data['speed'] = 'Fast';
        } elseif (strpos($name_lower, 'slow') !== false) {
            $data['speed'] = 'Slow';
        }
        
        // 4. DROP (⏬Drop: XXX)
        if (preg_match('/⏬\s*drop:\s*([^|]+)/i', $service_name, $matches)) {
            $data['drop_info'] = trim($matches[1]);
        }
        
        // 5. REFILL (♻️Refill: XXX)
        if (preg_match('/♻️\s*refill:\s*([^|]+)/i', $service_name, $matches)) {
            $data['refill_info'] = trim($matches[1]);
        }
        
        // 6. MAX (⬆️MAX XXX)
        if (preg_match('/⬆️\s*max\s*([\d,]+k?)/i', $service_name, $matches)) {
            $data['max_info'] = $matches[1];
        }
        
        // 7. AVERAGE TIME (estimation basée sur speed)
        if ($data['speed']) {
            if (stripos($data['speed'], 'instant') !== false) {
                $data['average_time'] = '0-1 hour';
            } elseif (preg_match('/(\d+)k\/day/i', $data['speed'], $matches)) {
                $per_day = (int)$matches[1] * 1000;
                if ($per_day >= 10000) {
                    $data['average_time'] = '1-6 hours';
                } elseif ($per_day >= 1000) {
                    $data['average_time'] = '6-24 hours';
                } else {
                    $data['average_time'] = '1-3 days';
                }
            } elseif (stripos($data['speed'], 'fast') !== false) {
                $data['average_time'] = '1-12 hours';
            } elseif (stripos($data['speed'], 'slow') !== false) {
                $data['average_time'] = '3-7 days';
            }
        }
        
        return $data;
    }
}
?>
