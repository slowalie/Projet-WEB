// Script de filtrage dynamique des offres
document.addEventListener('DOMContentLoaded', function () {
	// Sélecteurs des filtres
	const typeRadios = document.querySelectorAll('.sidebar input[name="type"]');
	const dureeRadios = document.querySelectorAll('.sidebar input[name="duree"]');
	const secteurButtons = document.querySelectorAll('.sidebar .tag-buttons .tag');
	const offerCards = document.querySelectorAll('.offers-grid .offer-card');

	let selectedType = document.querySelector('.sidebar input[name="type"]:checked').value;
	let selectedDuree = document.querySelector('.sidebar input[name="duree"]:checked').value;
	let selectedSecteur = document.querySelector('.sidebar .tag-buttons .tag.active').textContent.trim();

	function filterOffers() {
		offerCards.forEach(card => {
			// Type
			const type = card.querySelector('.tag-type')?.textContent.trim().toLowerCase();
			// Durée
			const duree = card.querySelector('.duration')?.textContent.replace(/[^0-9]/g, '') + 'mois';
			// Secteur (utilise data-sector)
			const secteur = card.getAttribute('data-sector');

			let show = true;
			// Filtre type
			if (selectedType !== 'tous' && type !== selectedType) show = false;
			// Filtre durée
			if (selectedDuree !== 'toutes' && duree !== selectedDuree) show = false;
			// Filtre secteur
			if (selectedSecteur !== 'Tous' && secteur !== selectedSecteur) show = false;

			card.style.display = show ? '' : 'none';
		});
		if (window.updateOfferCount) window.updateOfferCount();
	}

	// Listeners type
	typeRadios.forEach(radio => {
		radio.addEventListener('change', e => {
			selectedType = e.target.value;
			filterOffers();
		});
	});
	// Listeners durée
	dureeRadios.forEach(radio => {
		radio.addEventListener('change', e => {
			selectedDuree = e.target.value;
			filterOffers();
		});
	});
	// Listeners secteur
	secteurButtons.forEach(btn => {
		btn.addEventListener('click', e => {
			secteurButtons.forEach(b => b.classList.remove('active'));
			btn.classList.add('active');
			selectedSecteur = btn.textContent.trim();
			filterOffers();
		});
	});
});
