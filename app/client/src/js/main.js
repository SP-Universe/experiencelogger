import "./helpers.js";
import "./location.js";
import "./jsonloader.js";
import "./ExperienceCard.js";
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


const loadingScreen = document.querySelector('.loading_screen');

document.addEventListener("DOMContentLoaded", function (event) {
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


    // FIXED HEADER
    window.addEventListener('scroll', () => {
        if (document.documentElement.scrollTop > 30 || document.body.scrollTop > 30){
            document.body.classList.add('menu--fixed');
        } else {
            document.body.classList.remove('menu--fixed');
        }
    });


    // Button to Loading Screen
    const buttons = document.querySelectorAll('a');
    buttons.forEach(button => {

        if(button.getAttribute('data-noloadingscreen') == "true"){
            return;
        }

        if(button.href != '' && button.href != '#')
        {
            button.addEventListener('click', e => {
                if(button.getAttribute('data-behaviour') == 'addlog_button') return;
                //e.preventDefault();
                //loadingScreen.classList.add('fadeout');
                setTimeout(() => {
                    window.location.href = button.href;
                }, 300);
            });
        }
    });

    window.addEventListener('popstate', function(event) {
        loadingScreen.classList.remove('fadeout');
    });

    //Add Log Button
    const addlogbutton = document.querySelector('[data-behaviour="addlog_button"]');
    const addlogloading = document.querySelector('[data-behaviour="addlog_loading"]');
    if (addlogbutton) {
        addlogbutton.addEventListener('click', function(e) {
            loadingScreen.classList.add('fadeout');
        });
    }

    //Backbutton
    const backbutton = document.querySelector('backbutton');
    if (backbutton && loadingScreen) {
        backbutton.addEventListener('click', function(e) {
            loadingScreen.classList.add('fadeout');
        });
    }
});

loadingScreen.classList.remove('fadeout');
