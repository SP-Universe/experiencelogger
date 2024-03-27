import Swiper, {Autoplay, EffectCoverflow, EffectFade, Navigation, Pagination} from 'swiper';

// init Swipers:
const sliders = document.querySelectorAll('.swiper');

sliders.forEach(function (slider) {
    const autoSwiper = slider.classList.contains('swiper--auto');
    const swiper = new Swiper(slider, {
        // configure Swiper to use modules
        modules: [Navigation, Autoplay, EffectFade],
        effect: 'slide',
        fadeEffect: {
            crossFade: true
        },
        direction: 'horizontal',
        loop: true,

        autoplay: autoSwiper ? {
            delay: 5000,
        } : false,

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
});

// init Itemswipers
const itemsliders = document.querySelectorAll('.itemswiper');

itemsliders.forEach(function (slider) {
    const autoSwiper = slider.classList.contains('swiper--auto');
    const swiper = new Swiper(slider, {
        // configure Swiper to use modules
        modules: [Navigation, Autoplay, EffectFade],
        effect: 'slide',
        fadeEffect: {
            crossFade: true
        },
        direction: 'horizontal',
        loop: true,

        autoplay: autoSwiper ? {
            delay: 5000,
        } : false,

        // Navigation arrows
        navigation: {
            nextEl: '.itemswiper-button-next',
            prevEl: '.itemswiper-button-prev',
        },
    });

});
