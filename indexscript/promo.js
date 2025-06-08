// Khuyến mãi (Promo Section)
const promoSwiper = new Swiper('.promo-swiper', {
    loop: false,
    autoplay: {
        delay: 4000,
    },
    navigation: {
        nextEl: '.promo-next',
        prevEl: '.promo-prev',
    },
    slidesPerView: 4,
});

// Cập nhật chiều cao của các promo card
function equalizePromoCardHeight() {
    const cards = document.querySelectorAll('.promo-card');
    let maxHeight = 0;

    cards.forEach(card => {
        card.style.height = 'auto'; // reset trước
        maxHeight = Math.max(maxHeight, card.offsetHeight);
    });

    cards.forEach(card => {
        card.style.height = maxHeight + 'px';
    });
}

// Gọi khi trang tải xong
window.addEventListener('load', equalizePromoCardHeight);
// Gọi lại khi resize
window.addEventListener('resize', equalizePromoCardHeight);
