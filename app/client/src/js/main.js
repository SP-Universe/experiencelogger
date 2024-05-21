import "../js/helpers.js";
import "../js/location.js";
import "../js/jsonloader.js";
import "./pages/ExperienceListPage.js";
import "./pages/LocationListPage.js";
import "./pages/LogsListPage.js";
import "./pages/ProfilePage.js";
import "./tools/Cookies.js";
import "./tools/Swiper.js";
import "./tools/Lightbox.js";
import "./tools/Darkmode.js";
import "./tools/CardEffect.js";

if (navigator && navigator.serviceWorker) {
    navigator.serviceWorker.register('service-worker.js');
}

document.addEventListener("DOMContentLoaded", function (event) {

    //Add Log Button
    const addlogbutton = document.querySelector('[data-behaviour="addlog_button"]');
    const addlogloading = document.querySelector('[data-behaviour="addlog_loading"]');
    if (addlogbutton) {
        addlogbutton.addEventListener('click', function(e) {
            addlogbutton.classList.add('clicked');
            addlogloading.classList.add('clicked');
        });
    }

    //Range input for ratings
    for (let e of document.querySelectorAll('input[type="range"].rating')) {
        e.style.setProperty('--value', e.value);
        e.addEventListener('input', () => e.style.setProperty('--value', e.value));
    }

    //More Menu Toggle
    const moreMenuButton = document.querySelector('[data-behaviour="moremenu--toggle"]');

    if(moreMenuButton){
        moreMenuButton.addEventListener("click", function (event) {
            event.preventDefault();
            document.body.classList.toggle("moremenu--active");
        });
    }

    //Share Button
    const shareButton = document.querySelector('[data-behaviour="share_button"]');
    if(shareButton){
        shareButton.addEventListener('click', event => {
            const sharedlink = shareButton.getAttribute('data-link');
            if (navigator.share) {
                navigator.share({
                    title: 'Experiencelogger',
                    url: sharedlink
                }).then(() => {
                    console.log('Thanks for sharing!');
                })
                .catch(console.error);
            } else {
                // fallback
            }
        });
    }
});
