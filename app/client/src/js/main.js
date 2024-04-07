//import "../js/helpers.js";
//import "../js/location.js";
//import "../js/jsonloader.js";
//import "./pages/ExperienceListPage.js";
//import "./pages/LocationListPage.js";
//import "./pages/LogsListPage.js";
//import "./pages/ProfilePage.js";
import "./tools/Cookies.js";
import "./tools/Swiper.js";
import "./tools/Lightbox.js";
import "./tools/Darkmode.js";
import "./react/index.jsx";

if (navigator && navigator.serviceWorker) {
    navigator.serviceWorker.register('service-worker.js');
}

/*
document.addEventListener("DOMContentLoaded", function (event) {

    //Personal Nav
    const personalNavButton = document.querySelector('[data-behaviour="open_personalnav"]');
    const personalNavDiv = document.querySelector('[data-behaviour="personalnav"]');
    if(personalNavButton){
        personalNavButton.addEventListener("click", function (event) {
            event.preventDefault();
            document.body.classList.toggle("personalnav_active");
        });
        document.addEventListener('click', function(e){
            if (!personalNavDiv.contains(e.target) && !personalNavButton.contains(e.target)){
                document.body.classList.remove("personalnav_active");
            }
        });
    }

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
});*/
