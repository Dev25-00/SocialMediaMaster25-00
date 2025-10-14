    </div> <!-- Fin page-wrapper -->
    
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <!-- Column 1 -->
                <div class="footer-section">
                    <h3>
                        <?php echo getIcon('rocket', true, 'lg'); ?>
                        SMM Mastery
                    </h3>
                    <p style="color: rgba(255,255,255,0.7); margin-bottom: 20px;">
                        Votre partenaire de confiance pour une croissance sociale authentique et professionnelle.
                    </p>
                    <div style="display: flex; gap: 15px; font-size: 24px;">
                        <a href="#" style="color: rgba(255,255,255,0.7); transition: color 0.3s;" class="social-icon">
                            <?php echo getIcon('facebook'); ?>
                        </a>
                        <a href="#" style="color: rgba(255,255,255,0.7); transition: color 0.3s;" class="social-icon">
                            <?php echo getIcon('twitter'); ?>
                        </a>
                        <a href="#" style="color: rgba(255,255,255,0.7); transition: color 0.3s;" class="social-icon">
                            <?php echo getIcon('instagram'); ?>
                        </a>
                        <a href="#" style="color: rgba(255,255,255,0.7); transition: color 0.3s;" class="social-icon">
                            <?php echo getIcon('linkedin'); ?>
                        </a>
                    </div>
                </div>
                
                <!-- Column 2 -->
                <div class="footer-section">
                    <h4>
                        <?php echo getIcon('services', false, 'md'); ?>
                        Produits
                    </h4>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>/services/index.php">
                            <?php echo getIcon('services', false, 'sm'); ?> Services
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/pricing.php">
                            <?php echo getIcon('money', false, 'sm'); ?> Tarifs
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/api.php">
                            <?php echo getIcon('settings', false, 'sm'); ?> API
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/reseller.php">
                            <?php echo getIcon('users', false, 'sm'); ?> Revendeurs
                        </a></li>
                    </ul>
                </div>
                
                <!-- Column 3 -->
                <div class="footer-section">
                    <h4>
                        <?php echo getIcon('info', false, 'md'); ?>
                        Entreprise
                    </h4>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>/pages/about.php">
                            <?php echo getIcon('info', false, 'sm'); ?> À propos
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/contact.php">
                            <?php echo getIcon('mail', false, 'sm'); ?> Contact
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/faq.php">
                            <?php echo getIcon('info', false, 'sm'); ?> FAQ
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/support/tickets.php">
                            <?php echo getIcon('support', false, 'sm'); ?> Support
                        </a></li>
                    </ul>
                </div>
                
                <!-- Column 4 -->
                <div class="footer-section">
                    <h4>
                        <?php echo getIcon('shield', false, 'md'); ?>
                        Légal
                    </h4>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>/pages/terms.php">
                            <?php echo getIcon('shield', false, 'sm'); ?> CGU
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/privacy.php">
                            <?php echo getIcon('lock', false, 'sm'); ?> Confidentialité
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/refund.php">
                            <?php echo getIcon('money', false, 'sm'); ?> Remboursements
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/disclaimer.php">
                            <?php echo getIcon('warning', false, 'sm'); ?> Disclaimer
                        </a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>
                    <?php echo getIcon('shield', false, 'sm'); ?>
                    &copy; <?php echo date('Y'); ?> <strong>SMM Mastery</strong>. Tous droits réservés.
                </p>
                <p style="margin-top: 10px; font-size: 14px;">
                    <?php echo getIcon('lock', false, 'sm'); ?> Paiements sécurisés • 
                    <?php echo getIcon('rocket', true, 'sm'); ?> Livraison rapide • 
                    <?php echo getIcon('support', false, 'sm'); ?> Support 24/7
                </p>
            </div>
        </div>
    </footer>
    
    <style>
        .footer {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: white;
            padding: 60px 20px 30px;
            margin-top: 60px;
        }
        .footer .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        .footer-section h3 {
            font-size: 24px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .footer-section h4 {
            font-size: 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .footer-section ul {
            list-style: none;
            padding: 0;
        }
        .footer-section li {
            margin-bottom: 12px;
        }
        .footer-section a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .footer-section a:hover {
            color: white;
            transform: translateX(5px);
        }
        .social-icon:hover {
            color: white !important;
            transform: scale(1.2) !important;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 30px;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
        }
        .footer-bottom strong {
            color: white;
        }
        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }
    </style>

    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
