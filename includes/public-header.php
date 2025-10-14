<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $page_description ?? 'SMM Mastery - Votre partenaire pour une croissance sociale authentique'; ?>">
    <meta name="keywords" content="SMM, social media marketing, followers, likes, views, instagram, youtube, tiktok">
    <title><?php echo $page_title ?? 'SMM Mastery'; ?></title>
    
    <!-- Font Awesome -->
    <?php 
    require_once __DIR__ . '/icons-config.php';
    echo ICON_CDN; 
    ?>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/icons.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/fixes.css">
    
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .page-wrapper {
            flex: 1;
            padding-top: 80px;
        }
        .public-header {
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        .public-header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            overflow: visible !important; /* IMPORTANT: Pour le dropdown multi-langue */
        }
        .logo-link {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: transform 0.3s ease;
        }
        .logo-link:hover {
            transform: scale(1.05);
        }
        .logo-icon {
            font-size: 32px;
        }
        .logo-text {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .public-nav {
            display: flex;
            gap: 30px;
            align-items: center;
        }
        .public-nav a {
            color: #6b7280;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .public-nav a:hover {
            color: #2563eb;
        }
        .public-nav a i {
            font-size: 14px;
        }
        .btn {
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        .btn-secondary {
            background: transparent;
            color: #6b7280;
            border: 2px solid #e5e7eb;
        }
        .btn-secondary:hover {
            border-color: #2563eb;
            color: #2563eb;
        }
        .page-hero {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
            padding: 60px 20px;
            text-align: center;
        }
        .page-hero h1 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        .page-hero p {
            font-size: 20px;
            opacity: 0.9;
        }
        .page-content {
            max-width: 900px;
            margin: 60px auto;
            padding: 0 20px;
        }
        .content-section {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .content-section h2 {
            font-size: 28px;
            color: #111827;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2563eb;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .content-section h3 {
            font-size: 22px;
            color: #374151;
            margin-top: 30px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .content-section p {
            line-height: 1.8;
            color: #6b7280;
            margin-bottom: 15px;
        }
        .content-section ul, .content-section ol {
            line-height: 2;
            color: #6b7280;
            margin-left: 20px;
            margin-bottom: 20px;
        }
        .content-section li {
            margin-bottom: 10px;
        }
        .content-section strong {
            color: #111827;
        }
        .highlight-box {
            background: #f0f9ff;
            border-left: 4px solid #2563eb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            display: flex;
            gap: 15px;
        }
        .highlight-box i {
            color: #2563eb;
            font-size: 24px;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .warning-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            display: flex;
            gap: 15px;
        }
        .warning-box i {
            color: #f59e0b;
            font-size: 24px;
            flex-shrink: 0;
            margin-top: 2px;
        }
        
        /* Mobile Menu */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: #6b7280;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .page-hero h1 {
                font-size: 32px;
                flex-direction: column;
                gap: 10px;
            }
            .page-hero p {
                font-size: 16px;
            }
            .content-section {
                padding: 25px 20px;
            }
            .content-section h2 {
                font-size: 22px;
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            .public-nav {
                display: none;
            }
            .mobile-menu-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <header class="public-header">
        <div class="container">
            <a href="<?php echo SITE_URL; ?>/index.php" class="logo-link">
                <?php echo getIcon('rocket', true, 'xl'); ?>
                <span class="logo-text">SMM Mastery</span>
            </a>
            <nav class="public-nav">
                <a href="<?php echo SITE_URL; ?>/index.php">
                    <?php echo getIcon('home', false, 'sm'); ?>
                    Accueil
                </a>
                <a href="<?php echo SITE_URL; ?>/pages/faq.php">
                    <?php echo getIcon('info', false, 'sm'); ?>
                    FAQ
                </a>
                <a href="<?php echo SITE_URL; ?>/pages/about.php">
                    <?php echo getIcon('info', false, 'sm'); ?>
                    À propos
                </a>
                <a href="<?php echo SITE_URL; ?>/pages/contact.php">
                    <?php echo getIcon('mail', false, 'sm'); ?>
                    Contact
                </a>
                
                <!-- Widget Multi-langue -->
                <?php include __DIR__ . '/google-translate-widget.php'; ?>
                
                <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
                    <a href="<?php echo SITE_URL; ?>/dashboard/index.php" class="btn btn-primary">
                        <?php echo getIcon('dashboard', false, 'sm'); ?>
                        Dashboard
                    </a>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>/auth/login.php" class="btn btn-secondary">
                        <?php echo getIcon('user', false, 'sm'); ?>
                        Connexion
                    </a>
                    <a href="<?php echo SITE_URL; ?>/auth/register.php" class="btn btn-primary">
                        <?php echo getIcon('rocket', false, 'sm'); ?>
                        Inscription
                    </a>
                <?php endif; ?>
            </nav>
            <button class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>
    
    <div class="page-wrapper">
