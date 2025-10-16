<?php
require_once '../config.php';
require_once '../functions.php';

$page_title = 'Tarifs et Pricing | ' . SITE_NAME;
$page_description = 'Découvrez nos tarifs transparents pour tous nos services SMM. 4 niveaux de qualité pour tous les budgets.';

include '../includes/layout/public-header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1><?php echo getIcon('premium', true); ?> Tarifs Transparents</h1>
        <p>Des prix justes pour tous les budgets • Aucun frais caché</p>
    </div>
</div>

<div class="page-content">
    
    <!-- Introduction -->
    <div class="content-section" style="text-align: center;">
        <h2>Choisissez Votre Niveau de Qualité</h2>
        <p style="font-size: 18px; color: #6b7280; max-width: 700px; margin: 0 auto 40px;">
            Nous proposons <strong>4 tiers de qualité</strong> pour répondre à tous les besoins et budgets. 
            Tous nos prix sont affichés <strong>par 1000</strong> (followers, likes, vues, etc.)
        </p>
    </div>

    <!-- Tiers Comparison -->
    <div class="content-section">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 25px;">
            
            <!-- Budget Tier -->
            <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #10b981;">
                <div style="text-align: center; margin-bottom: 25px;">
                    <span style="display: inline-block; padding: 8px 16px; background: #d1fae5; color: #065f46; border-radius: 20px; font-weight: 600; font-size: 14px;">
                        <?php echo getIcon('budget', true); ?> BUDGET
                    </span>
                </div>
                
                <div style="text-align: center; margin-bottom: 25px;">
                    <div style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">À partir de</div>
                    <div style="font-size: 42px; font-weight: 700; color: #10b981;">$1.50</div>
                    <div style="font-size: 14px; color: #6b7280;">par 1000</div>
                </div>
                
                <ul style="list-style: none; padding: 0; margin: 0 0 25px 0;">
                    <li style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><?php echo getIcon('success'); ?> Prix économiques</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><?php echo getIcon('warning'); ?> Drop possible (30-50%)</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><?php echo getIcon('shares'); ?> 7 jours refill</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><?php echo getIcon('bolt'); ?> Livraison standard</li>
                    <li style="padding: 10px 0;"><?php echo getIcon('followers'); ?> Comptes basiques</li>
                </ul>
                
                <a href="../services/index.php?tier=budget" class="btn btn-success btn-block">
                    Voir les Services
                </a>
                
                <p style="text-align: center; font-size: 12px; color: #6b7280; margin-top: 15px;">
                    Parfait pour tester
                </p>
            </div>

            <!-- Standard Tier -->
            <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #3b82f6; position: relative;">
                <div style="position: absolute; top: -12px; right: 20px; background: #3b82f6; color: white; padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                    <?php echo getIcon('star'); ?> POPULAIRE
                </div>
                
                <div style="text-align: center; margin-bottom: 25px;">
                    <span style="display: inline-block; padding: 8px 16px; background: #dbeafe; color: #1e40af; border-radius: 20px; font-weight: 600; font-size: 14px;">
                        <?php echo getIcon('standard', true); ?> STANDARD
                    </span>
                </div>
                
                <div style="text-align: center; margin-bottom: 25px;">
                    <div style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">À partir de</div>
                    <div style="font-size: 42px; font-weight: 700; color: #3b82f6;">$8.00</div>
                    <div style="font-size: 14px; color: #6b7280;">par 1000</div>
                </div>
                
                <ul style="list-style: none; padding: 0; margin: 0 0 25px 0;">
                    <li style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><?php echo getIcon('success'); ?> Meilleur rapport qualité/prix</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><?php echo getIcon('success'); ?> Low Drop (10-20%)</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><?php echo getIcon('shares'); ?> 30 jours refill</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><?php echo getIcon('bolt'); ?> Livraison rapide</li>
                    <li style="padding: 10px 0;"><?php echo getIcon('followers'); ?> Comptes réalistes</li>
                </ul>
                
                <a href="../services/index.php?tier=standard" class="btn btn-primary btn-block">
                    Voir les Services
                </a>
                
                <p style="text-align: center; font-size: 12px; color: #6b7280; margin-top: 15px;">
                    Recommandé pour la croissance
                </p>
            </div>

            <!-- Premium Tier -->
            <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #a855f7;">
                <div style="text-align: center; margin-bottom: 25px;">
                    <span style="display: inline-block; padding: 8px 16px; background: #fae8ff; color: #7e22ce; border-radius: 20px; font-weight: 600; font-size: 14px;">
                        <?php echo getIcon('premium', true); ?> PREMIUM
                    </span>
                </div>
                
                <div style="text-align: center; margin-bottom: 25px;">
                    <div style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">À partir de</div>
                    <div style="font-size: 42px; font-weight: 700; color: #a855f7;">$25.00</div>
                    <div style="font-size: 14px; color: #6b7280;">par 1000</div>
                </div>
                
                <ul style="list-style: none; padding: 0; margin: 0 0 25px 0;">
                    <li style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><?php echo getIcon('success'); ?> Haute qualité garantie</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><?php echo getIcon('success'); ?> No Drop / Very Low Drop</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><?php echo getIcon('shares'); ?> 90 jours refill</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><?php echo getIcon('bolt'); ?> Livraison prioritaire</li>
                    <li style="padding: 10px 0;"><?php echo getIcon('followers'); ?> Comptes premium</li>
                </ul>
                
                <a href="../services/index.php?tier=premium" class="btn btn-block" style="background: #a855f7; color: white;">
                    Voir les Services
                </a>
                
                <p style="text-align: center; font-size: 12px; color: #6b7280; margin-top: 15px;">
                    Pour les professionnels
                </p>
            </div>

            <!-- Ultimate Tier -->
            <div style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border-radius: 12px; padding: 30px; box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3); color: white;">
                <div style="text-align: center; margin-bottom: 25px;">
                    <span style="display: inline-block; padding: 8px 16px; background: rgba(255,255,255,0.3); color: white; border-radius: 20px; font-weight: 600; font-size: 14px;">
                        <?php echo getIcon('ultimate', true); ?> ULTIMATE
                    </span>
                </div>
                
                <div style="text-align: center; margin-bottom: 25px;">
                    <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">À partir de</div>
                    <div style="font-size: 42px; font-weight: 700;">$50.00</div>
                    <div style="font-size: 14px; opacity: 0.9;">par 1000</div>
                </div>
                
                <ul style="list-style: none; padding: 0; margin: 0 0 25px 0;">
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.2);"><?php echo getIcon('success'); ?> Excellence absolue</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.2);"><?php echo getIcon('success'); ?> No Drop garanti</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.2);"><?php echo getIcon('shares'); ?> Lifetime refill</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.2);"><?php echo getIcon('bolt'); ?> Livraison VIP</li>
                    <li style="padding: 10px 0;"><?php echo getIcon('followers'); ?> Comptes réels vérifiés</li>
                </ul>
                
                <a href="../services/index.php?tier=ultimate" class="btn btn-block btn-lg" style="background: white; color: #f59e0b;">
                    Voir les Services
                </a>
                
                <p style="text-align: center; font-size: 12px; opacity: 0.9; margin-top: 15px;">
                    Pour les grandes marques
                </p>
            </div>

        </div>
    </div>

    <!-- Exemples de Prix par Plateforme -->
    <div class="content-section">
        <h2><?php echo getIcon('wallet'); ?> Exemples de Prix par Plateforme</h2>
        <p style="margin-bottom: 30px;">
            Voici quelques exemples de tarifs pour nos services les plus populaires. Les prix peuvent varier selon le tier choisi.
        </p>
        
        <!-- Instagram -->
        <h3 style="margin-top: 40px;">📸 Instagram</h3>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th><?php echo tierBadge('budget'); ?></th>
                        <th><?php echo tierBadge('standard'); ?></th>
                        <th><?php echo tierBadge('premium'); ?></th>
                        <th><?php echo tierBadge('ultimate'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Followers</strong></td>
                        <td>$1.50 - $3.00</td>
                        <td>$8.00 - $12.00</td>
                        <td>$25.00 - $35.00</td>
                        <td>$50.00 - $70.00</td>
                    </tr>
                    <tr style="background: #f9fafb;">
                        <td><strong>Likes</strong></td>
                        <td>$0.50 - $1.00</td>
                        <td>$3.00 - $5.00</td>
                        <td>$10.00 - $15.00</td>
                        <td>$20.00 - $30.00</td>
                    </tr>
                    <tr>
                        <td><strong>Views (Reels)</strong></td>
                        <td>$0.20 - $0.50</td>
                        <td>$1.00 - $2.00</td>
                        <td>$5.00 - $8.00</td>
                        <td>$15.00 - $25.00</td>
                    </tr>
                    <tr style="background: #f9fafb;">
                        <td><strong>Comments</strong></td>
                        <td>$5.00 - $10.00</td>
                        <td>$15.00 - $25.00</td>
                        <td>$40.00 - $60.00</td>
                        <td>$80.00 - $120.00</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- YouTube -->
        <h3 style="margin-top: 40px;">🎥 YouTube</h3>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th><?php echo tierBadge('budget'); ?></th>
                        <th><?php echo tierBadge('standard'); ?></th>
                        <th><?php echo tierBadge('premium'); ?></th>
                        <th><?php echo tierBadge('ultimate'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Subscribers</strong></td>
                        <td>$3.00 - $5.00</td>
                        <td>$12.00 - $18.00</td>
                        <td>$30.00 - $45.00</td>
                        <td>$60.00 - $90.00</td>
                    </tr>
                    <tr style="background: #f9fafb;">
                        <td><strong>Views</strong></td>
                        <td>$0.50 - $1.00</td>
                        <td>$2.00 - $4.00</td>
                        <td>$8.00 - $12.00</td>
                        <td>$20.00 - $35.00</td>
                    </tr>
                    <tr>
                        <td><strong>Likes</strong></td>
                        <td>$1.00 - $2.00</td>
                        <td>$5.00 - $8.00</td>
                        <td>$15.00 - $22.00</td>
                        <td>$35.00 - $50.00</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- TikTok -->
        <h3 style="margin-top: 40px;"><?php echo getIcon('tiktok'); ?> TikTok</h3>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th><?php echo tierBadge('budget'); ?></th>
                        <th><?php echo tierBadge('standard'); ?></th>
                        <th><?php echo tierBadge('premium'); ?></th>
                        <th><?php echo tierBadge('ultimate'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Followers</strong></td>
                        <td>$2.00 - $4.00</td>
                        <td>$10.00 - $15.00</td>
                        <td>$28.00 - $40.00</td>
                        <td>$55.00 - $80.00</td>
                    </tr>
                    <tr style="background: #f9fafb;">
                        <td><strong>Likes</strong></td>
                        <td>$0.30 - $0.80</td>
                        <td>$2.00 - $4.00</td>
                        <td>$8.00 - $12.00</td>
                        <td>$18.00 - $28.00</td>
                    </tr>
                    <tr>
                        <td><strong>Views</strong></td>
                        <td>$0.15 - $0.40</td>
                        <td>$0.80 - $1.50</td>
                        <td>$3.00 - $5.00</td>
                        <td>$10.00 - $18.00</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p style="margin-top: 30px; padding: 20px; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b;">
            <strong><?php echo getIcon('info'); ?> Note :</strong> Ces prix sont des estimations. Les prix exacts peuvent varier selon la disponibilité, 
            la demande et les caractéristiques spécifiques de chaque service. Consultez notre 
            <a href="../services/index.php" style="color: #667eea;">catalogue complet</a> pour les prix actualisés.
        </p>
    </div>

    <!-- Bonus et Promotions -->
    <div class="content-section" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
        <h2 style="color: white; border-bottom: 2px solid rgba(255,255,255,0.3);"><?php echo getIcon('gift'); ?> Bonus sur les Dépôts</h2>
        <p style="opacity: 0.9; margin-bottom: 30px;">
            Plus vous rechargez, plus vous recevez de bonus gratuit !
        </p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <div style="background: rgba(255,255,255,0.2); padding: 25px; border-radius: 12px; text-align: center;">
                <div style="font-size: 36px; font-weight: 700; margin-bottom: 10px;">+5%</div>
                <div style="opacity: 0.9;">$10 - $49</div>
            </div>
            <div style="background: rgba(255,255,255,0.2); padding: 25px; border-radius: 12px; text-align: center;">
                <div style="font-size: 36px; font-weight: 700; margin-bottom: 10px;">+8%</div>
                <div style="opacity: 0.9;">$50 - $99</div>
            </div>
            <div style="background: rgba(255,255,255,0.2); padding: 25px; border-radius: 12px; text-align: center;">
                <div style="font-size: 36px; font-weight: 700; margin-bottom: 10px;">+10%</div>
                <div style="opacity: 0.9;">$100 - $499</div>
            </div>
            <div style="background: rgba(255,255,255,0.3); padding: 25px; border-radius: 12px; text-align: center; border: 2px solid white;">
                <div style="font-size: 36px; font-weight: 700; margin-bottom: 10px;">+15%</div>
                <div style="opacity: 0.9;">$500+</div>
            </div>
        </div>
        
        <p style="text-align: center; margin-top: 30px; opacity: 0.9;">
            Exemple : Déposez $100, recevez $110 de crédit !
        </p>
    </div>

    <!-- Comparaison Concurrence -->
    <div class="content-section">
        <h2><?php echo getIcon('stats'); ?> Pourquoi Nos Prix Sont Plus Bas ?</h2>
        <p style="margin-bottom: 30px;">
            Nous ne sommes pas les moins chers, mais nous offrons le <strong>meilleur rapport qualité/prix</strong> du marché.
        </p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
            <div style="background: #f9fafb; padding: 25px; border-radius: 12px;">
                <div style="font-size: 36px; margin-bottom: 15px;">🤝</div>
                <h3 style="font-size: 18px; margin-bottom: 10px;">Partenariats Directs</h3>
                <p style="color: #6b7280; margin: 0;">
                    Nous travaillons directement avec les fournisseurs, sans intermédiaires.
                </p>
            </div>
            
            <div style="background: #f9fafb; padding: 25px; border-radius: 12px;">
                <div style="font-size: 36px; margin-bottom: 15px;"><?php echo getIcon('wallet'); ?></div>
                <h3 style="font-size: 18px; margin-bottom: 10px;">Marges Raisonnables</h3>
                <p style="color: #6b7280; margin: 0;">
                    Nous privilégions le volume plutôt que des marges excessives.
                </p>
            </div>
            
            <div style="background: #f9fafb; padding: 25px; border-radius: 12px;">
                <div style="font-size: 36px; margin-bottom: 15px;"><?php echo getIcon('search'); ?></div>
                <h3 style="font-size: 18px; margin-bottom: 10px;">Transparence Totale</h3>
                <p style="color: #6b7280; margin: 0;">
                    Pas de frais cachés, pas de surprises. Le prix affiché est le prix final.
                </p>
            </div>
        </div>
    </div>

    <!-- FAQ Pricing -->
    <div class="content-section">
        <h2><?php echo getIcon('info'); ?> Questions Fréquentes sur les Tarifs</h2>
        
        <details style="background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
            <summary style="cursor: pointer; font-weight: 600; color: #111827;">
                Puis-je commander moins de 1000 ?
            </summary>
            <p style="margin-top: 15px; color: #6b7280;">
                Oui ! Nos prix sont affichés par 1000, mais chaque service a un <strong>minimum</strong> 
                (souvent 100 ou 500) et un <strong>maximum</strong>. Le prix est calculé proportionnellement.
                <br>Exemple : 500 followers à $10/1000 = $5.00
            </p>
        </details>
        
        <details style="background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
            <summary style="cursor: pointer; font-weight: 600; color: #111827;">
                Y a-t-il des frais supplémentaires ?
            </summary>
            <p style="margin-top: 15px; color: #6b7280;">
                Non, aucun frais caché. Le prix affiché est le prix final. Seuls les frais de paiement 
                (PayPal, Stripe) peuvent s'appliquer lors de la recharge, mais ils sont clairement indiqués.
            </p>
        </details>
        
        <details style="background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
            <summary style="cursor: pointer; font-weight: 600; color: #111827;">
                Les prix peuvent-ils changer ?
            </summary>
            <p style="margin-top: 15px; color: #6b7280;">
                Oui, les prix peuvent varier selon la demande et la disponibilité. Cependant, une fois 
                votre commande passée, le prix est garanti et ne change pas.
            </p>
        </details>
        
        <details style="background: #f9fafb; padding: 20px; border-radius: 8px;">
            <summary style="cursor: pointer; font-weight: 600; color: #111827;">
                Proposez-vous des réductions pour gros volumes ?
            </summary>
            <p style="margin-top: 15px; color: #6b7280;">
                Oui ! Contactez-nous à <strong>partners@smmmaster.com</strong> pour des devis personnalisés 
                si vous commandez régulièrement de gros volumes.
            </p>
        </details>
    </div>

    <!-- CTA Final -->
    <div class="content-section" style="text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h2 style="color: white; border-bottom: none;"><?php echo getIcon('rocket', true); ?> Prêt à Commencer ?</h2>
        <p style="color: white; opacity: 0.9; font-size: 18px; margin-bottom: 30px;">
            Créez votre compte gratuitement et recevez $1 de bonus de bienvenue !
        </p>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="../auth/register.php" class="btn btn-lg" style="background: white; color: #667eea;">
                <?php echo getIcon('star'); ?> Inscription Gratuite
            </a>
            <a href="../services/index.php" class="btn btn-lg" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid white;">
                <?php echo getIcon('services'); ?> Voir Tous les Services
            </a>
        </div>
    </div>

</div>

<?php include '../includes/layout/public-footer.php'; ?>
