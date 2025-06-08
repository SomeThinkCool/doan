// Main Banner Slider (Slider chính)
const mainSwiper = new Swiper('.main-slider', {
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    navigation: {
        nextEl: '.main-next',
        prevEl: '.main-prev',
    },
});