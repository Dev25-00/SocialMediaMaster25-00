<?php
/**
 * SMM Mastery - Page d'accueil principale
 * Date: 12 Octobre 2025
 * Version: 2.0 - Nouvelle identité visuelle avec icônes Font Awesome
 * Documentation: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\
 */

require_once 'config.php';
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Services SMM Premium | Boost Your Social Media</title>
    <meta name="description" content="Services SMM professionnels pour Instagram, YouTube, TikTok et plus. Prix compétitifs, livraison rapide, qualité garantie. 4 tiers de qualité.">
    <meta name="keywords" content="SMM, Instagram, YouTube, TikTok, Facebook, Twitter, social media marketing, followers, likes, views">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
    
    <style>
        /* Reset & Variables */
        :root {
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --primary-light: #3b82f6;
            --secondary: #f59e0b;
            --success: #10b981;
            --danger: #ef4444;
            --dark: #1f2937;
            --gray: #6b7280;
            --light: #f3f4f6;
            --white: #ffffff;
            --gradient-blue: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #3b82f6 100%);
            --gradient-gold: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--dark);
            background: var(--white);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        /* Header Navigation */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow);
            z-index: 1000;
            transition: all 0.3s ease;
            width: 100%;
            /* Fix pour mobile Safari/Chrome */
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
            will-change: transform;
        }
        
        .header.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-lg);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 24px;
            font-weight: 800;
            background: var(--gradient-blue);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .logo i {
            font-size: 28px;
            background: var(--gradient-blue);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Widget Traduction Header */
        .header-translate-widget {
            display: flex;
            align-items: center;
            order: 1;
        }
        
        .nav {
            display: flex;
            align-items: center;
            gap: 24px;
            order: 2;
        }
        
        .nav a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .nav a:hover {
            color: var(--primary);
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: var(--gradient-blue);
            color: var(--white);
            box-shadow: var(--shadow);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-secondary {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        
        .btn-secondary:hover {
            background: var(--primary);
            color: var(--white);
        }
        
        /* Hero Section */
        .hero {
            margin-top: 70px;
            padding: 100px 0;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(30, 64, 175, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        
        .hero-content {
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        .hero-title {
            font-size: 56px;
            font-weight: 900;
            margin-bottom: 20px;
            background: var(--gradient-blue);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 20px;
            color: var(--gray);
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-bottom: 60px;
            flex-wrap: wrap;
        }
        
        .btn-lg {
            padding: 16px 32px;
            font-size: 18px;
        }
        
        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .stat {
            text-align: center;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: 900;
            background: var(--gradient-blue);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }
        
        .stat-label {
            color: var(--gray);
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <header class="header" id="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fa-solid fa-rocket"></i>
                    <span><?php echo SITE_NAME; ?></span>
                </div>
                
                <!-- Widget Traduction (toujours visible) -->
                <div class="header-translate-widget">
                    <?php include __DIR__ . '/includes/widgets/google-translate-widget-v3-final.php'; ?>
                </div>
                
                <!-- Hamburger Button (Mobile) -->
                <button class="hamburger" id="hamburger" aria-label="Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                
                <!-- Desktop Navigation -->
                <nav class="nav" id="nav">
                    <a href="#services"><i class="fa-solid fa-grid-2"></i> Services</a>
                    <a href="#pricing"><i class="fa-solid fa-tag"></i> Tarifs</a>
                    <a href="#features"><i class="fa-solid fa-star"></i> Avantages</a>
                    <?php if (isLoggedIn()): ?>
                        <a href="dashboard/index.php" class="btn btn-primary">
                            <i class="fa-solid fa-gauge-high"></i> Dashboard
                        </a>
                    <?php else: ?>
                        <a href="auth/login.php" class="btn btn-secondary">
                            <i class="fa-solid fa-right-to-bracket"></i> Connexion
                        </a>
                        <a href="auth/register.php" class="btn btn-primary">
                            <i class="fa-solid fa-user-plus"></i> S'inscrire
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Développez votre présence sociale</h1>
                <p class="hero-subtitle">
                    <i class="fa-solid fa-sparkles"></i>
                    Services SMM professionnels pour Instagram, YouTube, TikTok et plus encore
                </p>
                <div class="hero-buttons">
                    <a href="auth/register.php" class="btn btn-lg btn-primary">
                        <i class="fa-solid fa-rocket"></i>
                        Commencer maintenant
                    </a>
                    <a href="#services" class="btn btn-lg btn-secondary">
                        <i class="fa-solid fa-circle-info"></i>
                        Découvrir les services
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="stat">
                        <div class="stat-number">
                            <i class="fa-solid fa-chart-line"></i> 500K+
                        </div>
                        <div class="stat-label">Commandes traitées</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">
                            <i class="fa-solid fa-users"></i> 10K+
                        </div>
                        <div class="stat-label">Clients satisfaits</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">
                            <i class="fa-solid fa-headset"></i> 24/7
                        </div>
                        <div class="stat-label">Support disponible</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services-section">
        <div class="container">
            <h2 class="section-title">
                <i class="fa-solid fa-grid-2"></i>
                Nos Services Premium
            </h2>
            <p class="section-subtitle">Tous les services dont vous avez besoin pour réussir sur les réseaux sociaux</p>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon" style="background: linear-gradient(135deg, #E4405F 0%, #C13584 100%);">
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                    <h3>Instagram</h3>
                    <p>Followers, Likes, Views, Comments et plus encore</p>
                    <ul class="service-features">
                        <li><i class="fa-solid fa-check"></i> Livraison rapide</li>
                        <li><i class="fa-solid fa-check"></i> Qualité garantie</li>
                        <li><i class="fa-solid fa-check"></i> Refill inclus</li>
                    </ul>
                    <a href="auth/register.php" class="btn btn-service">
                        Découvrir <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon" style="background: linear-gradient(135deg, #FF0000 0%, #CC0000 100%);">
                        <i class="fa-brands fa-youtube"></i>
                    </div>
                    <h3>YouTube</h3>
                    <p>Subscribers, Views, Likes, Watch Time</p>
                    <ul class="service-features">
                        <li><i class="fa-solid fa-check"></i> Croissance organique</li>
                        <li><i class="fa-solid fa-check"></i> Retention élevée</li>
                        <li><i class="fa-solid fa-check"></i> Monétisation safe</li>
                    </ul>
                    <a href="auth/register.php" class="btn btn-service">
                        Découvrir <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon" style="background: linear-gradient(135deg, #000000 0%, #69C9D0 100%);">
                        <i class="fa-brands fa-tiktok"></i>
                    </div>
                    <h3>TikTok</h3>
                    <p>Followers, Likes, Views, Shares</p>
                    <ul class="service-features">
                        <li><i class="fa-solid fa-check"></i> Boost viral</li>
                        <li><i class="fa-solid fa-check"></i> Engagement réel</li>
                        <li><i class="fa-solid fa-check"></i> Algorithme optimisé</li>
                    </ul>
                    <a href="auth/register.php" class="btn btn-service">
                        Découvrir <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon" style="background: linear-gradient(135deg, #1877F2 0%, #0E5FD9 100%);">
                        <i class="fa-brands fa-facebook"></i>
                    </div>
                    <h3>Facebook</h3>
                    <p>Page Likes, Post Likes, Followers</p>
                    <ul class="service-features">
                        <li><i class="fa-solid fa-check"></i> Portée augmentée</li>
                        <li><i class="fa-solid fa-check"></i> Profils actifs</li>
                        <li><i class="fa-solid fa-check"></i> Business ready</li>
                    </ul>
                    <a href="auth/register.php" class="btn btn-service">
                        Découvrir <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon" style="background: linear-gradient(135deg, #1DA1F2 0%, #0C85D0 100%);">
                        <i class="fa-brands fa-twitter"></i>
                    </div>
                    <h3>Twitter/X</h3>
                    <p>Followers, Likes, Retweets, Views</p>
                    <ul class="service-features">
                        <li><i class="fa-solid fa-check"></i> Influence forte</li>
                        <li><i class="fa-solid fa-check"></i> Viralité rapide</li>
                        <li><i class="fa-solid fa-check"></i> Communauté active</li>
                    </ul>
                    <a href="auth/register.php" class="btn btn-service">
                        Découvrir <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon" style="background: linear-gradient(135deg, #0A66C2 0%, #084D91 100%);">
                        <i class="fa-brands fa-linkedin"></i>
                    </div>
                    <h3>LinkedIn</h3>
                    <p>Connections, Likes, Shares, Views</p>
                    <ul class="service-features">
                        <li><i class="fa-solid fa-check"></i> Réseau professionnel</li>
                        <li><i class="fa-solid fa-check"></i> Crédibilité B2B</li>
                        <li><i class="fa-solid fa-check"></i> Lead generation</li>
                    </ul>
                    <a href="auth/register.php" class="btn btn-service">
                        Découvrir <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <style>
            .services-section {
                padding: 80px 0;
                background: var(--white);
            }
            
            .section-title {
                text-align: center;
                font-size: 42px;
                font-weight: 900;
                margin-bottom: 16px;
                color: var(--dark);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
            }
            
            .section-title i {
                background: var(--gradient-blue);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            
            .section-subtitle {
                text-align: center;
                font-size: 18px;
                color: var(--gray);
                margin-bottom: 60px;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            }
            
            .services-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 30px;
                margin-top: 40px;
            }
            
            .service-card {
                background: var(--white);
                border-radius: 16px;
                padding: 32px;
                box-shadow: var(--shadow);
                transition: all 0.3s ease;
                border: 2px solid transparent;
            }
            
            .service-card:hover {
                transform: translateY(-8px);
                box-shadow: var(--shadow-lg);
                border-color: var(--primary-light);
            }
            
            .service-icon {
                width: 70px;
                height: 70px;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 20px;
                color: var(--white);
                font-size: 32px;
                box-shadow: var(--shadow);
            }
            
            .service-card h3 {
                font-size: 24px;
                font-weight: 800;
                margin-bottom: 12px;
                color: var(--dark);
            }
            
            .service-card > p {
                color: var(--gray);
                margin-bottom: 20px;
                line-height: 1.6;
            }
            
            .service-features {
                list-style: none;
                margin-bottom: 24px;
            }
            
            .service-features li {
                padding: 8px 0;
                color: var(--gray);
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .service-features i {
                color: var(--success);
                font-size: 14px;
            }
            
            .btn-service {
                width: 100%;
                justify-content: center;
                background: var(--light);
                color: var(--primary);
                font-weight: 600;
            }
            
            .btn-service:hover {
                background: var(--primary);
                color: var(--white);
            }
        </style>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="pricing-section">
        <div class="container">
            <h2 class="section-title">
                <i class="fa-solid fa-tags"></i>
                Tarifs Compétitifs
            </h2>
            <p class="section-subtitle">4 niveaux de qualité pour tous les budgets</p>
            
            <div class="pricing-grid">
                <div class="pricing-card">
                    <div class="tier-badge budget">
                        <i class="fa-solid fa-leaf"></i> Budget
                    </div>
                    <h3>Tier Budget</h3>
                    <div class="price">
                        <span class="price-label">À partir de</span>
                        <span class="price-amount">1.50$</span>
                        <span class="price-unit">/1000</span>
                    </div>
                    <ul class="features">
                        <li><i class="fa-solid fa-check"></i> Prix économique</li>
                        <li><i class="fa-solid fa-check"></i> Livraison rapide</li>
                        <li><i class="fa-solid fa-check"></i> Parfait pour tester</li>
                        <li><i class="fa-solid fa-triangle-exclamation"></i> Drop possible</li>
                    </ul>
                    <a href="auth/register.php" class="btn btn-block">
                        <i class="fa-solid fa-rocket"></i> Commencer
                    </a>
                </div>
                
                <div class="pricing-card popular">
                    <div class="popular-tag">
                        <i class="fa-solid fa-fire"></i> Populaire
                    </div>
                    <div class="tier-badge standard">
                        <i class="fa-solid fa-star"></i> Standard
                    </div>
                    <h3>Tier Standard</h3>
                    <div class="price">
                        <span class="price-label">À partir de</span>
                        <span class="price-amount">8.00$</span>
                        <span class="price-unit">/1000</span>
                    </div>
                    <ul class="features">
                        <li><i class="fa-solid fa-check"></i> Qualité/Prix optimal</li>
                        <li><i class="fa-solid fa-check"></i> Low Drop (10-20%)</li>
                        <li><i class="fa-solid fa-check"></i> Refill 30 jours</li>
                        <li><i class="fa-solid fa-check"></i> Recommandé</li>
                    </ul>
                    <a href="auth/register.php" class="btn btn-block btn-primary">
                        <i class="fa-solid fa-rocket"></i> Commencer
                    </a>
                </div>
                
                <div class="pricing-card">
                    <div class="tier-badge premium">
                        <i class="fa-solid fa-gem"></i> Premium
                    </div>
                    <h3>Tier Premium</h3>
                    <div class="price">
                        <span class="price-label">À partir de</span>
                        <span class="price-amount">25.00$</span>
                        <span class="price-unit">/1000</span>
                    </div>
                    <ul class="features">
                        <li><i class="fa-solid fa-check"></i> Haute qualité</li>
                        <li><i class="fa-solid fa-check"></i> No/Very Low Drop</li>
                        <li><i class="fa-solid fa-check"></i> Refill 90 jours</li>
                        <li><i class="fa-solid fa-check"></i> Support prioritaire</li>
                    </ul>
                    <a href="auth/register.php" class="btn btn-block">
                        <i class="fa-solid fa-rocket"></i> Commencer
                    </a>
                </div>
                
                <div class="pricing-card">
                    <div class="tier-badge ultimate">
                        <i class="fa-solid fa-crown"></i> Ultimate
                    </div>
                    <h3>Tier Ultimate</h3>
                    <div class="price">
                        <span class="price-label">À partir de</span>
                        <span class="price-amount">50.00$</span>
                        <span class="price-unit">/1000</span>
                    </div>
                    <ul class="features">
                        <li><i class="fa-solid fa-check"></i> Qualité maximale</li>
                        <li><i class="fa-solid fa-check"></i> No Drop garanti</li>
                        <li><i class="fa-solid fa-check"></i> Refill à vie</li>
                        <li><i class="fa-solid fa-check"></i> VIP Support</li>
                    </ul>
                    <a href="auth/register.php" class="btn btn-block">
                        <i class="fa-solid fa-rocket"></i> Commencer
                    </a>
                </div>
            </div>
        </div>
        
        <style>
            .pricing-section {
                padding: 80px 0;
                background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            }
            
            .pricing-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                gap: 30px;
                margin-top: 40px;
            }
            
            .pricing-card {
                background: var(--white);
                border-radius: 20px;
                padding: 36px 28px;
                box-shadow: var(--shadow);
                transition: all 0.3s ease;
                position: relative;
                border: 2px solid transparent;
            }
            
            .pricing-card:hover {
                transform: translateY(-10px);
                box-shadow: var(--shadow-lg);
                border-color: var(--primary-light);
            }
            
            .pricing-card.popular {
                border-color: var(--primary);
                box-shadow: 0 10px 40px rgba(30, 64, 175, 0.2);
            }
            
            .popular-tag {
                position: absolute;
                top: -12px;
                right: 20px;
                background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
                color: var(--white);
                padding: 6px 16px;
                border-radius: 20px;
                font-size: 13px;
                font-weight: 700;
                box-shadow: var(--shadow);
                display: flex;
                align-items: center;
                gap: 6px;
            }
            
            .tier-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 16px;
                border-radius: 12px;
                font-weight: 700;
                font-size: 14px;
                margin-bottom: 16px;
            }
            
            .tier-badge.budget {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: var(--white);
            }
            
            .tier-badge.standard {
                background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
                color: var(--white);
            }
            
            .tier-badge.premium {
                background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
                color: var(--white);
            }
            
            .tier-badge.ultimate {
                background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                color: var(--white);
            }
            
            .pricing-card h3 {
                font-size: 24px;
                font-weight: 800;
                margin-bottom: 20px;
                color: var(--dark);
            }
            
            .price {
                margin-bottom: 30px;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            
            .price-label {
                font-size: 14px;
                color: var(--gray);
                margin-bottom: 8px;
            }
            
            .price-amount {
                font-size: 48px;
                font-weight: 900;
                background: var(--gradient-blue);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                line-height: 1;
            }
            
            .price-unit {
                font-size: 16px;
                color: var(--gray);
                margin-top: 4px;
            }
            
            .features {
                list-style: none;
                margin-bottom: 30px;
            }
            
            .features li {
                padding: 10px 0;
                color: var(--gray);
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 15px;
            }
            
            .features i.fa-check {
                color: var(--success);
                font-size: 16px;
            }
            
            .features i.fa-triangle-exclamation {
                color: var(--secondary);
                font-size: 16px;
            }
            
            .btn-block {
                width: 100%;
                justify-content: center;
                padding: 14px;
                font-size: 16px;
            }
        </style>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <h2 class="section-title">
                <i class="fa-solid fa-star"></i>
                Pourquoi nous choisir ?
            </h2>
            <p class="section-subtitle">Des avantages qui font la différence</p>
            
            <div class="features-grid">
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h3>Livraison rapide</h3>
                    <p>Commandes traitées en quelques minutes grâce à notre système automatisé</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3>100% Sécurisé</h3>
                    <p>Paiements cryptés SSL et protection complète de vos données personnelles</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h3>Support 24/7</h3>
                    <p>Équipe francophone disponible à tout moment via ticket et chat live</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </div>
                    <h3>Garantie Refill</h3>
                    <p>Remplacement automatique gratuit en cas de drop selon votre tier</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <h3>Multi-paiements</h3>
                    <p>PayPal, Stripe, Crypto et autres méthodes sécurisées acceptées</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <h3>Tracking en temps réel</h3>
                    <p>Suivez l'évolution de vos commandes minute par minute sur votre dashboard</p>
                </div>
            </div>
        </div>
        
        <style>
            .features-section {
                padding: 80px 0;
                background: var(--white);
            }
            
            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                gap: 40px;
                margin-top: 60px;
            }
            
            .feature {
                text-align: center;
                padding: 30px;
                transition: all 0.3s ease;
            }
            
            .feature:hover {
                transform: translateY(-5px);
            }
            
            .feature-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 24px;
                background: var(--gradient-blue);
                border-radius: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: var(--shadow);
                transition: all 0.3s ease;
            }
            
            .feature:hover .feature-icon {
                transform: scale(1.1) rotate(5deg);
                box-shadow: var(--shadow-lg);
            }
            
            .feature-icon i {
                font-size: 36px;
                color: var(--white);
            }
            
            .feature h3 {
                font-size: 22px;
                font-weight: 800;
                margin-bottom: 12px;
                color: var(--dark);
            }
            
            .feature p {
                color: var(--gray);
                line-height: 1.7;
                font-size: 15px;
            }
        </style>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>
                    <i class="fa-solid fa-rocket"></i>
                    Prêt à booster votre présence sociale ?
                </h2>
                <p>
                    <i class="fa-solid fa-gift"></i>
                    Inscrivez-vous maintenant et recevez <strong>1$ de bonus</strong> pour commencer
                </p>
                <a href="auth/register.php" class="btn btn-lg btn-cta">
                    <i class="fa-solid fa-user-plus"></i>
                    Créer mon compte gratuitement
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <p class="cta-note">
                    <i class="fa-solid fa-check-circle"></i>
                    Aucune carte bancaire requise • Accès immédiat • Support 24/7
                </p>
            </div>
        </div>
        
        <style>
            .cta-section {
                padding: 100px 0;
                background: var(--gradient-blue);
                position: relative;
                overflow: hidden;
            }
            
            .cta-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
                opacity: 0.3;
            }
            
            .cta-content {
                text-align: center;
                position: relative;
                z-index: 1;
            }
            
            .cta-content h2 {
                font-size: 48px;
                font-weight: 900;
                color: var(--white);
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 16px;
                flex-wrap: wrap;
            }
            
            .cta-content > p {
                font-size: 20px;
                color: rgba(255, 255, 255, 0.9);
                margin-bottom: 40px;
            }
            
            .cta-content strong {
                color: var(--secondary);
                font-weight: 800;
            }
            
            .btn-cta {
                background: var(--white);
                color: var(--primary);
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
                padding: 18px 40px;
                font-size: 18px;
                gap: 12px;
            }
            
            .btn-cta:hover {
                transform: translateY(-4px);
                box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
            }
            
            .cta-note {
                margin-top: 24px;
                font-size: 14px;
                color: rgba(255, 255, 255, 0.8);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                flex-wrap: wrap;
            }
            
            .cta-note i {
                color: var(--success);
            }
        </style>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fa-solid fa-rocket"></i>
                        <span><?php echo SITE_NAME; ?></span>
                    </div>
                    <p>Votre partenaire de confiance pour une croissance sociale authentique et rapide</p>
                    <div class="social-links">
                        <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-telegram"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4><i class="fa-solid fa-grid-2"></i> Services</h4>
                    <ul>
                        <li><a href="services/index.php"><i class="fa-brands fa-instagram"></i> Instagram</a></li>
                        <li><a href="services/index.php"><i class="fa-brands fa-youtube"></i> YouTube</a></li>
                        <li><a href="services/index.php"><i class="fa-brands fa-tiktok"></i> TikTok</a></li>
                        <li><a href="services/index.php"><i class="fa-brands fa-facebook"></i> Facebook</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4><i class="fa-solid fa-headset"></i> Support</h4>
                    <ul>
                        <li><a href="pages/faq.php"><i class="fa-solid fa-circle-question"></i> FAQ</a></li>
                        <li><a href="pages/contact.php"><i class="fa-solid fa-envelope"></i> Contact</a></li>
                        <li><a href="pages/api-docs.php"><i class="fa-solid fa-code"></i> API Docs</a></li>
                        <li><a href="#"><i class="fa-solid fa-signal"></i> Status</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4><i class="fa-solid fa-scale-balanced"></i> Légal</h4>
                    <ul>
                        <li><a href="pages/terms.php"><i class="fa-solid fa-file-contract"></i> CGU</a></li>
                        <li><a href="pages/privacy.php"><i class="fa-solid fa-lock"></i> Confidentialité</a></li>
                        <li><a href="pages/refund.php"><i class="fa-solid fa-rotate-left"></i> Remboursement</a></li>
                        <li><a href="pages/disclaimer.php"><i class="fa-solid fa-circle-info"></i> Disclaimer</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>
                    <i class="fa-solid fa-copyright"></i>
                    2025 <strong><?php echo SITE_NAME; ?></strong>. Tous droits réservés.
                </p>
                <div class="payment-methods">
                    <i class="fa-brands fa-cc-paypal"></i>
                    <i class="fa-brands fa-cc-stripe"></i>
                    <i class="fa-brands fa-bitcoin"></i>
                    <i class="fa-solid fa-credit-card"></i>
                </div>
            </div>
        </div>
        
        <style>
            .footer {
                background: var(--dark);
                color: rgba(255, 255, 255, 0.8);
                padding: 60px 0 30px;
            }
            
            .footer-content {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 40px;
                margin-bottom: 40px;
            }
            
            .footer-section h4 {
                color: var(--white);
                font-size: 18px;
                font-weight: 700;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .footer-logo {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 24px;
                font-weight: 800;
                color: var(--white);
                margin-bottom: 16px;
            }
            
            .footer-logo i {
                font-size: 28px;
                background: var(--gradient-gold);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            
            .footer-section p {
                line-height: 1.7;
                margin-bottom: 20px;
            }
            
            .social-links {
                display: flex;
                gap: 12px;
            }
            
            .social-links a {
                width: 40px;
                height: 40px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--white);
                text-decoration: none;
                transition: all 0.3s ease;
            }
            
            .social-links a:hover {
                background: var(--primary);
                transform: translateY(-3px);
            }
            
            .footer-section ul {
                list-style: none;
            }
            
            .footer-section ul li {
                margin-bottom: 12px;
            }
            
            .footer-section ul li a {
                color: rgba(255, 255, 255, 0.7);
                text-decoration: none;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .footer-section ul li a:hover {
                color: var(--white);
                padding-left: 5px;
            }
            
            .footer-bottom {
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                padding-top: 30px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 20px;
            }
            
            .footer-bottom p {
                margin: 0;
                display: flex;
                align-items: center;
                gap: 6px;
            }
            
            .footer-bottom strong {
                color: var(--white);
            }
            
            .payment-methods {
                display: flex;
                gap: 16px;
                font-size: 24px;
            }
            
            .payment-methods i {
                color: rgba(255, 255, 255, 0.5);
                transition: all 0.3s ease;
            }
            
            .payment-methods i:hover {
                color: var(--white);
                transform: scale(1.2);
            }
            
            /* Hamburger Menu */
            .hamburger {
                display: none;
                flex-direction: column;
                justify-content: space-between;
                width: 30px;
                height: 24px;
                background: transparent;
                border: none;
                cursor: pointer;
                padding: 0;
                z-index: 1001;
            }
            
            .hamburger span {
                width: 100%;
                height: 3px;
                background: var(--primary);
                border-radius: 3px;
                transition: all 0.3s ease;
            }
            
            .hamburger.active span:nth-child(1) {
                transform: translateY(10.5px) rotate(45deg);
            }
            
            .hamburger.active span:nth-child(2) {
                opacity: 0;
            }
            
            .hamburger.active span:nth-child(3) {
                transform: translateY(-10.5px) rotate(-45deg);
            }
            
            /* Responsive */
            @media (max-width: 992px) {
                .header-content {
                    display: grid;
                    grid-template-columns: 1fr auto auto;
                    grid-template-areas: "logo translate hamburger";
                    align-items: center;
                    gap: 16px;
                }
                
                .logo {
                    grid-area: logo;
                }
                
                .header-translate-widget {
                    grid-area: translate;
                    order: unset;
                }
                
                .hamburger {
                    display: flex;
                    grid-area: hamburger;
                }
                
                .nav {
                    position: fixed;
                    top: 64px;
                    left: 0;
                    right: 0;
                    background: var(--white);
                    flex-direction: column;
                    padding: 16px 20px;  /* Réduit de 20px à 16px */
                    box-shadow: var(--shadow-lg);
                    transform: translateY(-100%);
                    opacity: 0;
                    visibility: hidden;
                    transition: all 0.3s ease;
                    z-index: 1000;
                    order: unset;
                    max-height: 60vh;  /* Limite hauteur mobile */
                    overflow-y: auto;
                }
                
                .nav.active {
                    transform: translateY(0);
                    opacity: 1;
                    visibility: visible;
                }
                
                .nav a {
                    padding: 10px 16px;  /* Réduit de 12px à 10px */
                    border-radius: 8px;
                    transition: all 0.3s ease;
                    font-size: 15px;    /* Légèrement plus petit */
                }
                
                .nav a:hover {
                    background: var(--light);
                }
                
                .nav .btn {
                    width: 100%;
                    justify-content: center;
                    margin-top: 6px;    /* Réduit de 8px à 6px */
                    padding: 10px 16px; /* Plus compact */
                    font-size: 14px;
                }
            }
            
            /* Fix sticky mobile spécifique */
            @media (max-width: 992px) {
                .header {
                    /* Force position fixed sur mobile */
                    position: fixed !important;
                    top: 0 !important;
                    background: rgba(255, 255, 255, 0.98);
                    backdrop-filter: blur(15px);
                    -webkit-backdrop-filter: blur(15px);
                    /* Améliore performance mobile */
                    -webkit-transform: translateZ(0);
                    transform: translateZ(0);
                    backface-visibility: hidden;
                    perspective: 1000;
                }
                
                /* Évite les problèmes de viewport mobile */
                .header-content {
                    position: relative;
                    z-index: 1001;
                }
            }
            
            /* Mobile très petits écrans */
            @media (max-width: 480px) {
                .header-content {
                    padding: 12px 0;  /* Header plus compact */
                }
                
                .logo {
                    font-size: 20px;  /* Logo plus petit */
                }
                
                .logo span {
                    display: none;     /* Cache texte, garde juste icône */
                }
                
                .header-translate-widget {
                    /* Le widget reste mais plus compact */
                }
                
                .nav {
                    padding: 12px 16px;  /* Encore plus compact */
                    max-height: 50vh;    /* Plus petit sur très petits écrans */
                }
                
                .nav a {
                    padding: 8px 12px;   /* Ultra compact */
                    font-size: 14px;
                }
                
                .nav .btn {
                    padding: 8px 12px;
                    font-size: 13px;
                }
            }
                
                .nav .btn-secondary {
                    margin-top: 8px;
                }
                
                .nav .btn-primary {
                    margin-top: 8px;
                }
                
                .hero-title {
                    font-size: 42px;
                }
                
                .hero-stats {
                    grid-template-columns: 1fr;
                    gap: 20px;
                }
                
                .services-grid {
                    grid-template-columns: 1fr;
                }
                
                .pricing-grid {
                    grid-template-columns: 1fr;
                }
                
                .features-grid {
                    grid-template-columns: 1fr;
                }
                
                .cta-content h2 {
                    font-size: 32px;
                }
                
                .footer-content {
                    grid-template-columns: 1fr;
                }
                
                .footer-bottom {
                    flex-direction: column;
                    text-align: center;
                }
            }
            
            @media (max-width: 768px) {
                .hero {
                    padding: 60px 0;
                }
                
                .hero-title {
                    font-size: 32px;
                }
                
                .hero-subtitle {
                    font-size: 16px;
                }
                
                .btn-lg {
                    padding: 12px 24px;
                    font-size: 16px;
                }
                
                .section-title {
                    font-size: 32px;
                }
                
                .cta-content h2 {
                    font-size: 24px;
                }
            }
        </style>
    </footer>

    <!-- JavaScript -->
    <script>
        // Header scroll effect
        const header = document.getElementById('header');
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Mobile Menu Toggle
        const hamburger = document.getElementById('hamburger');
        const nav = document.getElementById('nav');
        
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            nav.classList.toggle('active');
            
            // Prevent body scroll when menu is open
            if (nav.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
        
        // Close menu when clicking on a link
        nav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                nav.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!nav.contains(e.target) && !hamburger.contains(e.target)) {
                hamburger.classList.remove('active');
                nav.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
        
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        // Observe elements
        document.querySelectorAll('.service-card, .pricing-card, .feature').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>
