function initializePromotionsCarousel() {
    const promoSection = document.querySelector('.promo-carousel');
    if (!promoSection) return;

    const promos = [
        '50% OFF on your 2nd Large Loaded Pizza!',
        'Buy 2 Get 1 Free on Main Dishes!',
        'Free Drink with Orders Over ৳500!'
    ];

    let currentPromo = 0;

    promoSection.innerHTML = `<p>${promos[currentPromo]}</p>`;

    setInterval(() => {
        currentPromo = (currentPromo + 1) % promos.length;
        promoSection.innerHTML = `<p>${promos[currentPromo]}</p>`;
        promoSection.style.opacity = 0;
        setTimeout(() => {
            promoSection.style.opacity = 1;
        }, 300);
    }, 5000);
}