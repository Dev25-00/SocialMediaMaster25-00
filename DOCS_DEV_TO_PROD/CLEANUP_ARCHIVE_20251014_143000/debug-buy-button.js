/**
 * DEBUG SCRIPT - Buy Button Click
 * Usage: Copier-coller dans la console F12
 * Date: 13 Octobre 2025
 */

console.log('🔍 === BUY BUTTON DEBUG SCRIPT START ===\n');

// ============================================
// 1. VÉRIFIER MODAL INSTANCE
// ============================================
console.log('1️⃣ Checking Modal Instance...');
if (typeof orderModal === 'undefined') {
    console.error('❌ orderModal not found! Modal not initialized.');
} else {
    console.log('✅ orderModal exists:', orderModal);
    console.log('   - Type:', typeof orderModal);
    console.log('   - Constructor:', orderModal.constructor.name);
    console.log('   - Has open method?', typeof orderModal.open === 'function');
}

// ============================================
// 2. VÉRIFIER BUY BUTTONS
// ============================================
console.log('\n2️⃣ Checking Buy Buttons...');

const buyButtons = document.querySelectorAll('.service-order-btn');
console.log(`   Found ${buyButtons.length} buy buttons`);

if (buyButtons.length > 0) {
    console.log('✅ First buy button:', buyButtons[0]);
    console.log('   - Tag:', buyButtons[0].tagName);
    console.log('   - Href:', buyButtons[0].href);
    console.log('   - Classes:', buyButtons[0].className);
    console.log('   - Parent card:', buyButtons[0].closest('.service-card-modern'));
} else {
    console.error('❌ No buy buttons found!');
}

// ============================================
// 3. VÉRIFIER SERVICE CARDS
// ============================================
console.log('\n3️⃣ Checking Service Cards...');

const serviceCards = document.querySelectorAll('.service-card-modern');
console.log(`   Found ${serviceCards.length} service cards`);

if (serviceCards.length > 0) {
    const firstCard = serviceCards[0];
    console.log('✅ First service card:', firstCard);
    console.log('   - Service ID:', firstCard.dataset.serviceId);
    console.log('   - Provider ID:', firstCard.dataset.providerId);
    console.log('   - Price:', firstCard.dataset.price);
    console.log('   - Min Qty:', firstCard.dataset.minQuantity);
    console.log('   - Max Qty:', firstCard.dataset.maxQuantity);
    console.log('   - Has buy button?', firstCard.querySelector('.service-order-btn') !== null);
} else {
    console.error('❌ No service cards found!');
}

// ============================================
// 4. TESTER CLICK PROGRAMMATIQUE
// ============================================
console.log('\n4️⃣ Testing Programmatic Click...');

if (buyButtons.length > 0) {
    console.log('   Clicking first buy button programmatically...');

    // Add temporary listener to see if click is detected
    const tempListener = (e) => {
        console.log('✅ Click detected by temp listener!', e);
        document.removeEventListener('click', tempListener);
    };

    document.addEventListener('click', tempListener);

    setTimeout(() => {
        buyButtons[0].click();
        console.log('   Click triggered on:', buyButtons[0]);
    }, 100);

    setTimeout(() => {
        document.removeEventListener('click', tempListener);
    }, 1000);
}

// ============================================
// 5. VÉRIFIER EVENT LISTENERS
// ============================================
console.log('\n5️⃣ Checking Event Listeners...');

// Check if click listener is attached to document
console.log('   Document click listeners:', getEventListeners ? getEventListeners(document).click : 'getEventListeners not available (use Chrome)');

// ============================================
// 6. SIMULER EXTRACTION DONNÉES
// ============================================
console.log('\n6️⃣ Simulating Data Extraction...');

if (serviceCards.length > 0) {
    const testCard = serviceCards[0];
    const testData = {
        id: testCard.dataset.serviceId,
        provider_id: testCard.dataset.providerId,
        platform: testCard.querySelector('.platform-name')?.textContent.trim() || '',
        name: testCard.querySelector('.service-card-title')?.textContent.trim() || '',
        description: testCard.dataset.description || '',
        location: testCard.dataset.location || '',
        price: parseFloat(testCard.dataset.price || 0),
        min_quantity: parseInt(testCard.dataset.minQuantity || 1000),
        max_quantity: parseInt(testCard.dataset.maxQuantity || 10000),
    };

    console.log('✅ Test data extracted from first card:', testData);

    // Test if we can open modal manually
    if (typeof orderModal !== 'undefined' && orderModal.open) {
        console.log('\n💡 You can manually open modal with:');
        console.log('   orderModal.open(' + JSON.stringify(testData, null, 2) + ')');
    }
}

// ============================================
// 7. VÉRIFIER MODAL DOM
// ============================================
console.log('\n7️⃣ Checking Modal DOM...');

const modalElement = document.getElementById('orderModal');
if (!modalElement) {
    console.error('❌ Modal element #orderModal not found in DOM!');
} else {
    console.log('✅ Modal element found');
    console.log('   - Classes:', modalElement.className);
    console.log('   - Active?', modalElement.classList.contains('active'));
    console.log('   - Display:', getComputedStyle(modalElement).display);
}

// ============================================
// 8. TESTER MANUELLEMENT
// ============================================
console.log('\n8️⃣ Manual Test Function...');

window.testBuyButton = function () {
    console.log('🧪 Testing buy button click manually...');

    const btn = document.querySelector('.service-order-btn');
    if (!btn) {
        console.error('❌ No buy button found');
        return;
    }

    const card = btn.closest('.service-card-modern');
    if (!card) {
        console.error('❌ No service card found');
        return;
    }

    const serviceData = {
        id: card.dataset.serviceId,
        provider_id: card.dataset.providerId,
        platform: card.querySelector('.platform-name')?.textContent.trim() || '',
        name: card.querySelector('.service-card-title')?.textContent.trim() || '',
        price: parseFloat(card.dataset.price || 0),
        min_quantity: parseInt(card.dataset.minQuantity || 1000),
        max_quantity: parseInt(card.dataset.maxQuantity || 10000),
    };

    console.log('Service data:', serviceData);

    if (typeof orderModal !== 'undefined' && orderModal.open) {
        console.log('Opening modal...');
        orderModal.open(serviceData);
        console.log('✅ Modal opened!');
    } else {
        console.error('❌ Modal or open method not available');
    }
};

console.log('✅ Manual test function created: testBuyButton()');

// ============================================
// 9. RÉSUMÉ
// ============================================
setTimeout(() => {
    console.log('\n🎯 === DEBUG SUMMARY ===');

    const issues = [];

    if (typeof orderModal === 'undefined') {
        issues.push('❌ Modal not initialized');
    }

    if (buyButtons.length === 0) {
        issues.push('❌ No buy buttons found');
    }

    if (serviceCards.length === 0) {
        issues.push('❌ No service cards found');
    }

    if (!document.getElementById('orderModal')) {
        issues.push('❌ Modal element not in DOM');
    }

    if (issues.length === 0) {
        console.log('✅ No critical issues found!');
        console.log('   All components present and ready');
        console.log('\n💡 Try clicking a buy button manually');
        console.log('   Or run: testBuyButton()');
    } else {
        console.log('❌ Issues found:');
        issues.forEach(issue => console.log('   ' + issue));
    }

    console.log('\n📊 Statistics:');
    console.log(`   - Service cards: ${serviceCards.length}`);
    console.log(`   - Buy buttons: ${buyButtons.length}`);
    console.log(`   - Modal initialized: ${typeof orderModal !== 'undefined'}`);
    console.log(`   - Modal in DOM: ${document.getElementById('orderModal') !== null}`);

}, 1500);

console.log('\n🔍 === DEBUG SCRIPT END ===');
console.log('⏱️ Waiting for operations... Check summary in 1.5 seconds');
