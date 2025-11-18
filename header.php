<?php
/**
 * The header for Astra Theme.
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?><!DOCTYPE html>
<?php astra_html_before(); ?>
<html <?php language_attributes(); ?>>
<head>
<?php astra_head_top(); ?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php if ( apply_filters( 'astra_header_profile_gmpg_link', true ) ) { ?>
    <link rel="profile" href="https://gmpg.org/xfn/11">
<?php } ?>
<?php wp_head(); ?>

<!-- Sticky Header Responsive - SMM Mastery -->
<style>
    /* ===== RESET BASE ===== */
    #masthead, .site-header, .main-header-bar-wrap, .main-header-bar {
        background: transparent !important;
        position: relative;
    }
    
    /* ===== STICKY HEADER - TOUS BREAKPOINTS ===== */
    .main-header-bar-wrap {
        position: fixed !important;
        top: 0 !important;
        left: 0;
        right: 0;
        width: 100%;
        z-index: 9999;
        transition: transform 0.3s ease, background 0.3s ease;
        transform: translateY(0);
    }

    .main-header-bar-wrap.sticky-hidden {
        transform: translateY(-100%);
    }

    /* ===== BLUR EFFECT - RESPONSIVE ===== */
    .main-header-bar-wrap.sticky-active {
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(12px) saturate(180%);
        -webkit-backdrop-filter: blur(12px) saturate(180%);
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.12);
    }
    
    .main-header-bar-wrap.sticky-active .main-header-bar,
    .main-header-bar-wrap.sticky-active .site-header {
        background: transparent !important;
    }

    /* Logo shrink */
    .main-header-bar-wrap.sticky-active .site-branding img,
    .main-header-bar-wrap.sticky-active .custom-logo-link img {
        max-height: 40px !important;
        transition: all 0.3s ease;
    }
    
    /* ===== FIX LOGO DÉFORMÉ - CONSERVER RATIO ===== */
    .site-branding img,
    .custom-logo-link img,
    .custom-logo {
        height: auto !important;
        width: auto !important;
        max-width: 100% !important;
        object-fit: contain !important;
    }

    .main-header-bar-wrap.sticky-active .main-header-bar {
        padding: 8px 0 !important;
        transition: all 0.3s ease;
    }

    /* Text shadow pour lisibilité */
    .main-header-bar-wrap.sticky-active a {
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    /* ===== MOBILE MENU (< 1025px) ===== */
    @media (max-width: 1024px) {
        /* CACHER BOUTON "COMMENCER MAINTENANT" EN RESPONSIVE */
        .ast-button,
        .button-commencer,
        a[href*="commencer"],
        .ast-header-button-1,
        .header .ast-button,
        .main-header-bar .ast-button {
            display: none !important;
        }
        
        /* Cacher menu Astra par défaut */
        .ast-mobile-menu-buttons,
        .ast-button-wrap .menu-toggle {
            display: none !important;
        }

        /* Bouton hamburger custom - FORCER AFFICHAGE */
        .smm-menu-toggle {
            position: relative !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: none;
            border: none;
            cursor: pointer;
            z-index: 10001 !important;
            padding: 8px;
            margin-left: auto;
        }

        .smm-menu-toggle span {
            display: block !important;
            width: 24px;
            height: 3px;
            background: currentColor;
            margin: 4px 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        /* Animation croix */
        .smm-menu-toggle.active span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }
        .smm-menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }
        .smm-menu-toggle.active span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* Menu dropdown */
        .smm-mobile-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: inherit;
            backdrop-filter: inherit;
            -webkit-backdrop-filter: inherit;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .smm-mobile-menu.active {
            max-height: 100vh;
            overflow-y: auto;
        }

        /* Style liens menu */
        .smm-mobile-menu ul {
            list-style: none;
            margin: 0;
            padding: 15px 0;
        }

        .smm-mobile-menu li {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .smm-mobile-menu a {
            display: block;
            padding: 15px 30px;
            color: inherit;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .smm-mobile-menu a:hover {
            background: rgba(255, 255, 255, 0.1);
            padding-left: 40px;
        }

        /* Cacher menu desktop en mobile */
        .main-header-menu,
        .ast-desktop-menu {
            display: none !important;
        }
    }

    /* Desktop - cacher menu mobile */
    @media (min-width: 1025px) {
        .smm-menu-toggle,
        .smm-mobile-menu {
            display: none !important;
        }
    }

    /* Placeholder compensation */
    .header-placeholder {
        height: var(--header-height, 70px);
        display: block;
    }

    body:not(.admin-bar) #content {
        padding-top: 0;
        margin-top: 0;
    }

    /* Force rendering */
    .main-header-bar-wrap {
        will-change: transform;
        backface-visibility: hidden;
    }
</style>

<!-- Sticky Header Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    let lastScrollTop = 0;
    const scrollThreshold = 50;
    const header = document.querySelector('.main-header-bar-wrap');
    
    if (!header) return;
    
    // Placeholder
    const placeholder = document.createElement('div');
    placeholder.className = 'header-placeholder';
    placeholder.style.height = header.offsetHeight + 'px';
    header.parentNode.insertBefore(placeholder, header.nextSibling);
    
    // Créer menu mobile
    if (window.innerWidth <= 1024) {
        createMobileMenu();
    }
    
    // Scroll handler
    function handleScroll() {
        const scroll = window.pageYOffset;
        
        if (scroll <= scrollThreshold) {
            header.classList.remove('sticky-active', 'sticky-hidden');
        } else if (scroll > lastScrollTop) {
            header.classList.remove('sticky-active');
            header.classList.add('sticky-hidden');
            closeMobileMenu();
        } else {
            header.classList.remove('sticky-hidden');
            header.classList.add('sticky-active');
        }
        
        lastScrollTop = scroll;
    }
    
    // Créer menu mobile
    function createMobileMenu() {
        const mainBar = header.querySelector('.main-header-bar');
        if (!mainBar || document.querySelector('.smm-menu-toggle')) return;
        
        // Bouton toggle
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'smm-menu-toggle';
        toggleBtn.innerHTML = '<span></span><span></span><span></span>';
        toggleBtn.setAttribute('aria-label', 'Menu');
        
        // Menu dropdown
        const mobileMenu = document.createElement('div');
        mobileMenu.className = 'smm-mobile-menu';
        
        // Récupérer liens Astra
        const astraMenu = document.querySelector('.main-header-menu, .ast-desktop-menu');
        if (astraMenu) {
            const links = astraMenu.querySelectorAll('a');
            const ul = document.createElement('ul');
            
            links.forEach(link => {
                const li = document.createElement('li');
                const a = document.createElement('a');
                a.href = link.href;
                a.textContent = link.textContent;
                a.onclick = () => closeMobileMenu();
                li.appendChild(a);
                ul.appendChild(li);
            });
            
            mobileMenu.appendChild(ul);
        }
        
        // Insérer dans header
        const headerRight = mainBar.querySelector('.ast-header-break-point, .site-header-primary-section-right');
        if (headerRight) {
            headerRight.prepend(toggleBtn);
        } else {
            mainBar.appendChild(toggleBtn);
        }
        
        header.appendChild(mobileMenu);
        
        // Event toggle
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });
        
        // Fermer au clic extérieur
        document.addEventListener('click', function(e) {
            if (!header.contains(e.target)) {
                closeMobileMenu();
            }
        });
        
        // Fermer avec ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMobileMenu();
        });
    }
    
    function closeMobileMenu() {
        const btn = document.querySelector('.smm-menu-toggle');
        const menu = document.querySelector('.smm-mobile-menu');
        if (btn) btn.classList.remove('active');
        if (menu) menu.classList.remove('active');
    }
    
    // Scroll listener
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(function() {
                handleScroll();
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
    
    // Resize handler
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth <= 1024 && !document.querySelector('.smm-menu-toggle')) {
                createMobileMenu();
            }
        }, 150);
    });
    
    console.log('✅ Sticky header responsive ready');
});
</script>

<?php astra_head_bottom(); ?>
</head>

<body <?php astra_schema_body(); ?> <?php body_class(); ?>>
<?php astra_body_top(); ?>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#content" title="<?php echo esc_attr( astra_default_strings( 'string-header-skip-link', false ) ); ?>">
    <?php echo esc_html( astra_default_strings( 'string-header-skip-link', false ) ); ?>
</a>

<div <?php echo wp_kses_post( astra_attr( 'site', array( 'id' => 'page', 'class' => 'hfeed site', ) ) ); ?> >
    <?php
    astra_header_before();
    astra_header();
    astra_header_after();
    astra_content_before();
    ?>
    <div id="content" class="site-content">
        <div class="ast-container">
            <?php astra_content_top(); ?>