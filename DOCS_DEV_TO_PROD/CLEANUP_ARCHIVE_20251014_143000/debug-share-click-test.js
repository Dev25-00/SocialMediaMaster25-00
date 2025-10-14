/**
 * SMM Mastery - Quick Debug: Share Button Click
 * Date: 13 Octobre 2025
 * 
 * INSTRUCTIONS:
 * 1. Ouvrir modal
 * 2. Ouvrir Console (F12)
 * 3. Copier-coller ce script
 * 4. Il teste immédiatement le bouton Share
 */

console.log('🔍 Quick Share Button Debug');
console.log('============================\n');

// Test 1: Vérifier le bouton
const shareBtn = document.getElementById('orderBtnShare');
const shareMenu = document.getElementById('shareMenu');

console.log('1️⃣ Button Check:');
console.log('   Share button exists:', !!shareBtn);
console.log('   Share menu exists:', !!shareMenu);

if (shareBtn) {
    console.log('   Button visible:', shareBtn.offsetParent !== null);
    console.log('   Button disabled:', shareBtn.disabled);
    console.log('   Button classes:', shareBtn.className);
    console.log('   Button parent:', shareBtn.parentElement?.className);

    // Vérifier z-index et pointer-events
    const btnStyles = window.getComputedStyle(shareBtn);
    console.log('   Button z-index:', btnStyles.zIndex);
    console.log('   Button pointer-events:', btnStyles.pointerEvents);
    console.log('   Button position:', btnStyles.position);
}

if (shareMenu) {
    console.log('\n2️⃣ Menu Check:');
    console.log('   Menu classes:', shareMenu.className);
    console.log('   Menu has .active:', shareMenu.classList.contains('active'));

    const menuStyles = window.getComputedStyle(shareMenu);
    console.log('   Menu visibility:', menuStyles.visibility);
    console.log('   Menu opacity:', menuStyles.opacity);
    console.log('   Menu display:', menuStyles.display);
    console.log('   Menu pointer-events:', menuStyles.pointerEvents);
}

// Test 2: Vérifier les event listeners
console.log('\n3️⃣ Event Listeners:');
if (typeof getEventListeners === 'function') {
    const listeners = getEventListeners(shareBtn);
    console.log('   Click listeners:', listeners?.click?.length || 0);
} else {
    console.log('   ⚠️ getEventListeners not available (Chrome DevTools only)');
}

// Test 3: Essayer de cliquer programmatiquement
console.log('\n4️⃣ Programmatic Click Test:');
if (shareBtn) {
    console.log('   Attempting to click Share button...');
    try {
        shareBtn.click();

        setTimeout(() => {
            const isActive = shareMenu?.classList.contains('active');
            console.log('   Result: Menu active =', isActive);

            if (isActive) {
                console.log('   ✅ SUCCESS! Menu opened programmatically');
                console.log('   💡 Button works - check if manual click is blocked');

                // Fermer le menu
                setTimeout(() => {
                    shareBtn.click();
                    console.log('   Menu closed for cleanup');
                }, 1500);
            } else {
                console.error('   ❌ FAILED! Menu did not open');
                console.log('   💡 Check toggleShareMenu() function');
            }
        }, 100);
    } catch (error) {
        console.error('   ❌ Error clicking:', error);
    }
}

// Test 4: Vérifier orderModal instance
console.log('\n5️⃣ Modal Instance:');
if (typeof orderModal !== 'undefined') {
    console.log('   ✅ orderModal exists');
    console.log('   Current service:', orderModal.currentService?.id || 'none');
    console.log('   shareListenersSetup:', orderModal.shareListenersSetup);

    // Test direct de toggleShareMenu
    console.log('\n6️⃣ Direct Function Test:');
    console.log('   Calling orderModal.toggleShareMenu()...');
    try {
        orderModal.toggleShareMenu();
        setTimeout(() => {
            const isActive = shareMenu?.classList.contains('active');
            console.log('   Result: Menu active =', isActive);
            if (isActive) {
                console.log('   ✅ Function works directly!');
                setTimeout(() => {
                    orderModal.toggleShareMenu();
                    console.log('   Menu toggled off');
                }, 1500);
            }
        }, 100);
    } catch (error) {
        console.error('   ❌ Error:', error);
    }
} else {
    console.error('   ❌ orderModal NOT FOUND');
}

// Helper: Tester manuellement
window.testShareClick = function () {
    console.log('\n🧪 Manual Click Test');
    const btn = document.getElementById('orderBtnShare');
    if (!btn) {
        console.error('❌ Button not found');
        return;
    }

    console.log('Simulating click event...');
    const event = new MouseEvent('click', {
        bubbles: true,
        cancelable: true,
        view: window
    });
    btn.dispatchEvent(event);

    setTimeout(() => {
        const menu = document.getElementById('shareMenu');
        const active = menu?.classList.contains('active');
        console.log('Result:', active ? '✅ Menu opened' : '❌ Menu still closed');
    }, 100);
};

window.forceToggleMenu = function () {
    console.log('\n🔧 Force Toggle Menu');
    if (typeof orderModal !== 'undefined') {
        orderModal.toggleShareMenu();
        console.log('✅ Called orderModal.toggleShareMenu()');
    } else {
        const menu = document.getElementById('shareMenu');
        if (menu) {
            menu.classList.toggle('active');
            console.log('✅ Toggled menu class directly');
        }
    }
};

console.log('\n📋 Helper Functions:');
console.log('   testShareClick()   - Simulate click event');
console.log('   forceToggleMenu()  - Force toggle menu');
console.log('\n============================');
console.log('🔍 Quick debug complete!');
