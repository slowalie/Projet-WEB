// Script de recherche dynamique sur les offres
document.addEventListener('DOMContentLoaded', function () {
	const searchInput = document.querySelector('.search-bar input[type="text"]');
	const searchButton = document.querySelector('.search-bar button');
	const offerCards = document.querySelectorAll('.offers-grid .offer-card');

	function normalize(str) {
		return str.toLowerCase().normalize('NFD').replace(/\p{Diacritic}/gu, '');
	}

	function searchOffers() {
		const query = normalize(searchInput.value.trim());
		offerCards.forEach(card => {
			// Récupère les champs à rechercher
			const titre = card.querySelector('.offer-title h3')?.textContent || '';
			const entreprise = card.querySelector('.company-name')?.textContent || '';
			const skills = Array.from(card.querySelectorAll('.skills .skill')).map(s => s.textContent).join(' ');
			const ville = card.querySelector('.location')?.textContent || '';
			const contenu = normalize(titre + ' ' + entreprise + ' ' + skills + ' ' + ville);
			// Affiche ou masque selon la recherche
			card.style.display = (query === '' || contenu.includes(query)) ? '' : 'none';
		});
	}

	// Recherche en tapant (live)
	searchInput.addEventListener('input', searchOffers);
	// Recherche au clic sur le bouton
	searchButton.addEventListener('click', function(e) {
		e.preventDefault();
		searchOffers();
	});
});
