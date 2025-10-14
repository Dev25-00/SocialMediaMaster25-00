/**
 * SMM Mastery - Main JavaScript
 * Version: 1.0
 */

// Small guard: prefer debugLog() over console.log in production.
// To enable verbose logging for development set: window.SMM_DEBUG = true
(function () {
    if (typeof window === 'undefined') return;
    window.SMM_DEBUG = window.SMM_DEBUG || false;
    // If debugLog exists (we added assets/js/debug-log.js), prefer it.
    if (typeof window.debugLog === 'function') {
        // Replace console.log calls that are direct references in this file by using debugLog in future edits.
        // This guard prevents accidental logs when SMM_DEBUG is false.
        try {
            // noop - kept for clarity. Use debugLog() calls when adding new logs.
        } catch (e) { }
    }
})();

// Smooth scroll pour les liens d'ancrage
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

// Auto-hide alerts après 5 secondes
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        if (!alert.querySelector('.close')) {
            alert.style.transition = 'opacity 0.3s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }
    });
}, 5000);

// Fermer les alerts manuellement
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('close')) {
        e.target.parentElement.remove();
    }
});

// Confirmation avant suppression
document.querySelectorAll('[data-confirm]').forEach(element => {
    element.addEventListener('click', function (e) {
        if (!confirm(this.dataset.confirm || 'Êtes-vous sûr ?')) {
            e.preventDefault();
        }
    });
});

// Copier dans le presse-papiers
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Copié !', 'success');
    }).catch(err => {
        console.error('Erreur de copie:', err);
    });
}

// Toast notifications
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Formater les nombres
function formatNumber(num) {
    return new Intl.NumberFormat().format(num);
}

// Formater la devise
function formatCurrency(amount, currency = 'USD') {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency
    }).format(amount);
}

// Calculateur de prix (pour les pages de services)
function calculatePrice(quantity, pricePerUnit) {
    const total = (quantity * pricePerUnit) / 1000;
    return total.toFixed(2);
}

// Validation de formulaire
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    let isValid = true;
    const inputs = form.querySelectorAll('[required]');

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('error');
            isValid = false;
        } else {
            input.classList.remove('error');
        }
    });

    return isValid;
}

// Animations au scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.querySelectorAll('.service-card, .pricing-card, .feature').forEach(el => {
    observer.observe(el);
});

// Mobile menu toggle (si implémenté)
const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
const mobileMenu = document.querySelector('.mobile-menu');

if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('active');
    });
}

// Loading state pour les boutons
function setButtonLoading(button, loading = true) {
    if (loading) {
        button.dataset.originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="loading"></span> Chargement...';
    } else {
        button.disabled = false;
        button.innerHTML = button.dataset.originalText || button.innerHTML;
    }
}

// AJAX helper
async function fetchAPI(url, options = {}) {
    try {
        const response = await fetch(url, {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error('API Error:', error);
        showToast('Erreur de connexion', 'error');
        throw error;
    }
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Search filter
const searchInputs = document.querySelectorAll('[data-search]');
searchInputs.forEach(input => {
    input.addEventListener('input', debounce((e) => {
        const searchTerm = e.target.value.toLowerCase();
        const targetSelector = e.target.dataset.search;
        const items = document.querySelectorAll(targetSelector);

        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    }, 300));
});

// Auto-logout sur inactivité (30 minutes)
let inactivityTimer;
function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(() => {
        if (confirm('Votre session va expirer. Voulez-vous rester connecté ?')) {
            resetInactivityTimer();
        } else {
            window.location.href = '/auth/logout.php';
        }
    }, 30 * 60 * 1000); // 30 minutes
}

if (document.body.classList.contains('logged-in')) {
    ['mousedown', 'keypress', 'scroll', 'touchstart'].forEach(event => {
        document.addEventListener(event, resetInactivityTimer, true);
    });
    resetInactivityTimer();
}

// Console log pour debug (silencieux par défaut, activez window.SMM_DEBUG = true pour voir)
if (typeof debugLog === 'function') {
    debugLog('SMM Mastery JS loaded ✓');
} else if (window.SMM_DEBUG) {
    console.log('SMM Mastery JS loaded ✓');
}

// ========== Scroll to Top Button ==========
document.addEventListener('DOMContentLoaded', function () {
    // Créer le bouton scroll-to-top s'il n'existe pas déjà
    if (!document.querySelector('.scroll-to-top')) {
        const scrollBtn = document.createElement('button');
        scrollBtn.className = 'scroll-to-top';
        scrollBtn.innerHTML = '↑';
        scrollBtn.setAttribute('aria-label', 'Retour en haut');
        scrollBtn.setAttribute('title', 'Retour en haut');
        document.body.appendChild(scrollBtn);

        // Afficher/masquer le bouton selon la position de scroll
        window.addEventListener('scroll', function () {
            if (window.pageYOffset > 300) {
                scrollBtn.classList.add('show');
            } else {
                scrollBtn.classList.remove('show');
            }
        });

        // Action au clic : smooth scroll vers le haut
        scrollBtn.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});

