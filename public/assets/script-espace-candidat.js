const navlinks = document.getElementById('toggle-link', 'toggle-link-candidatures', 'toggle-link-favoris', 'toggle-link-parametres');
const content = document.getElementById('candidatures', 'apercu', 'favoris', 'parametres');

navlinks.addEventListener('click', function(event) {
    event.preventDefault();
    content.classList.toggle('active');
});


// On récupère tous les liens de la navigation
const navLinks = document.querySelectorAll('nav a');

navLinks.forEach(link => {
    link.addEventListener('click', function() {
        // 1. On retire la classe active de tous les liens
        navLinks.forEach(l => l.classList.remove('active'));
        
        // 2. On ajoute la classe active au lien cliqué
        this.classList.add('active');
    });
});