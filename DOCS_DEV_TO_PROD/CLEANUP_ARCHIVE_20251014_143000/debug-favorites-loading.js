// DEBUG: Insérer ce code dans la console browser (F12)

// 1. Vérifier si les favoris sont chargés via API
fetch('/smm/api/favorites/list.php')
    .then(r => r.json())
    .then(data => {
        console.log('📊 API Response:', data);
        console.log(`Total favoris: ${data.total}`);

        if (data.favorites && data.favorites.length > 0) {
            data.favorites.forEach((fav, i) => {
                console.log(`  ${i + 1}. Service ID: ${fav.service.id} - ${fav.service.name.substring(0, 50)}`);

                // Chercher la card correspondante
                const card = document.querySelector(`[data-service-id="${fav.service.id}"]`);
                console.log(`     Card trouvé:`, card ? '✅ OUI' : '❌ NON');

                if (card) {
                    const btn = card.querySelector('.service-favorite-btn');
                    console.log(`     Bouton favori:`, btn ? '✅ OUI' : '❌ NON');
                    console.log(`     Bouton actif:`, btn?.classList.contains('active') ? '✅ OUI' : '❌ NON');
                }
            });
        }

        // Compter combien de cards existent
        const allCards = document.querySelectorAll('[data-service-id]');
        console.log(`\n📋 Total service cards dans le DOM: ${allCards.length}`);

        if (allCards.length > 0) {
            console.log(`Exemples IDs: ${Array.from(allCards).slice(0, 5).map(c => c.dataset.serviceId).join(', ')}`);
        }
    })
    .catch(err => console.error('❌ Erreur:', err));
