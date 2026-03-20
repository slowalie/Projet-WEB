// Script pour mettre à jour dynamiquement le nombre d'offres trouvées
// À inclure après les scripts de filtre et de recherche

document.addEventListener('DOMContentLoaded', function () {
    const offersGrid = document.querySelector('.offers-grid');
    const headerCount = document.querySelector('.main-content .header h2');

    function updateOfferCount() {
        const visibleOffers = offersGrid.querySelectorAll('.offer-card:not([style*="display: none"])').length;
        headerCount.textContent = visibleOffers + (visibleOffers > 1 ? ' offres trouvées' : ' offre trouvée');
    }

    // Observe les changements de style display sur les offres
    const observer = new MutationObserver(updateOfferCount);
    offersGrid.querySelectorAll('.offer-card').forEach(card => {
        observer.observe(card, { attributes: true, attributeFilter: ['style'] });
    });

    // Met à jour au chargement
    updateOfferCount();

    // Pour compatibilité avec d'autres scripts qui filtrent
    window.updateOfferCount = updateOfferCount;

    // Optionnel : expose la fonction pour appel manuel si besoin
});
