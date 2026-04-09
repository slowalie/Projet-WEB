//------Systeme de défillage de commentaires------//

document.addEventListener('DOMContentLoaded', function() {
    const carouselGrid = document.querySelector('.temoignage-grid');
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    const cards = document.querySelectorAll('.temoignage-grid > div');
    
    let currentIndex = 0;
    const cardsPerView = 3;
    const totalCards = cards.length;
    
    function updateCarousel() {
        const translateValue = -currentIndex * (100 / cardsPerView);
        carouselGrid.style.transform = `translateX(${translateValue}%)`;
    }
    
    prevBtn.addEventListener('click', function() {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = Math.max(0, totalCards - cardsPerView);
        }
        updateCarousel();
    });
    
    nextBtn.addEventListener('click', function() {
        if (currentIndex < totalCards - cardsPerView) {
            currentIndex++;
        } else {
            currentIndex = 0;
        }
        updateCarousel();
    });
});


