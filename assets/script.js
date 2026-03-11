document.querySelector('.hamburger').addEventListener('click', function() {
    document.querySelector('.nav-links').classList.toggle('active');
});

// Fermer le menu quand on clique sur un lien
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', function() {
        document.querySelector('.nav-links').classList.remove('active');
    });
});

// Fermer le menu quand on clique sur un bouton
document.querySelectorAll('.nav-links button').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelector('.nav-links').classList.remove('active');
    });
});


