// Script de recherche dynamique sur les offres
document.addEventListener('DOMContentLoaded', function () {
	const searchInput = document.querySelector('.search-bar input[type="text"]');
	const searchButton = document.querySelector('.search-bar button');
	const offerCards = document.querySelectorAll('.offers-grid .offer-card');
	const villeSelect = document.querySelector('.select-wrapper select');

	function normalize(str) {
		return str.toLowerCase().normalize('NFD').replace(/\p{Diacritic}/gu, '');
	}

	function searchOffers() {
		const query = normalize(searchInput.value.trim());
		const villeFiltre = villeSelect.value;
		offerCards.forEach(card => {
			// Récupère les champs à rechercher
			const titre = card.querySelector('.offer-title h3')?.textContent || '';
			const entreprise = card.querySelector('.company-name')?.textContent || '';
			const skills = Array.from(card.querySelectorAll('.skills .skill')).map(s => s.textContent).join(' ');
			const ville = card.querySelector('.location')?.textContent || '';
			const contenu = normalize(titre + ' ' + entreprise + ' ' + skills + ' ' + ville);
			// Filtrage par texte et ville
			let show = (query === '' || contenu.includes(query));
			if (villeFiltre !== 'Toutes') {
				show = show && ville.includes(villeFiltre);
			}
			card.style.display = show ? '' : 'none';
		});
		if (window.updateOfferCount) window.updateOfferCount();
	}

	// Recherche en tapant (live)
	searchInput.addEventListener('input', searchOffers);
	// Recherche au clic sur le bouton
	searchButton.addEventListener('click', function(e) {
		e.preventDefault();
		searchOffers();
	});
	// Filtrage au changement de ville
	villeSelect.addEventListener('change', searchOffers);
});
