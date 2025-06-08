// Sản phẩm bán chạy (Bestseller Section)
const bestsellerSwiper = new Swiper('.bestseller-swiper', {
    loop: false,
    autoplay: {
        delay: 4000,
    },
    navigation: {
        nextEl: '.bestseller-next',
        prevEl: '.bestseller-prev',
    },
    slidesPerView: 4,
});

// Cập nhật chiều cao của các promo card trong phần bán chạy
function equalizeBestsellerCardHeight() {
    const cards = document.querySelectorAll('.bestseller-section .promo-card');
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
window.addEventListener('load', equalizeBestsellerCardHeight);
// Gọi lại khi resize
window.addEventListener('resize', equalizeBestsellerCardHeight);
