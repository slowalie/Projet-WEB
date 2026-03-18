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

//------Systeme de défillage de commentaires------//

document.addEventListener('DOMContentLoaded', function() {
    const carouselGrid = document.querySelector('.temoignage-grid');
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    const cards = document.querySelectorAll('.temoignage-grid > div');
    
    if (!carouselGrid || !prevBtn || !nextBtn) return;
    
    let currentIndex = 0;
    const totalCards = cards.length;
    const cardWidth = 320;
    const gap = 20;
    
    function updateCarousel() {
        const offset = currentIndex * (cardWidth + gap);
        carouselGrid.style.transform = `translateX(-${offset}px)`;
    }
    
    prevBtn.addEventListener('click', function() {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = totalCards - 3;
        }
        updateCarousel();
    });
    
    nextBtn.addEventListener('click', function() {
        if (currentIndex < totalCards - 3) {
            currentIndex++;
        } else {
            currentIndex = 0;
        }
        updateCarousel();
    });
});

