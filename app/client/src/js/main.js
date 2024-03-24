import Swiper, {Autoplay, EffectCoverflow, EffectFade, Navigation, Pagination} from 'swiper';
import GLightbox from "glightbox";

import "../js/helpers.js";
import "../js/location.js";
import "../js/jsonloader.js";

if (navigator && navigator.serviceWorker) {
    navigator.serviceWorker.register('service-worker.js');
}

document.addEventListener("DOMContentLoaded", function (event) {

    const lightbox = GLightbox({
        selector: '[data-gallery="gallery"]',
        touchNavigation: true,
        loop: true,
    });

    const sliders = document.querySelectorAll('.swiper');

    // init Swiper:
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

    const itemsliders = document.querySelectorAll('.itemswiper');

    // init Swiper:
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

    //Experience menu popups
    let popupbuttons = document.querySelectorAll('[data-behaviour="popup-open"]');
    let popups = document.querySelectorAll('[data-behaviour="popup"]');
    let popupcloses = document.querySelectorAll('[data-behaviour="popup-close"]');
    if(popupbuttons.length && popups.length && popupcloses.length){
        popupbuttons.forEach(popupbutton => {
            popupbutton.addEventListener("click", function (event) {
                event.preventDefault();
                popups.forEach(popup => {
                    popup.classList.remove("active");
                    if(popup.dataset.popupid === popupbutton.dataset.popupid){
                        popup.classList.add("active");
                    }
                });
            });
        });

        popupcloses.forEach(popupclose => {
            popupclose.addEventListener("click", function (event) {
                event.preventDefault();
                popups.forEach(popup => {
                    if(popup.dataset.popupid === popupclose.dataset.popupid){
                        popup.classList.remove("active");
                    }
                });
            });
        });
    }


    //Showhide Logs
    let showhide = document.querySelectorAll('[data-behaviour="showhide"]');
    if(showhide.length){
        showhide.forEach(showhideItem => {
            showhideItem.style.height = "0px";
            showhideItem.style.opacity = "0";

            showhideItem.addEventListener("click", function (event) {
                event.preventDefault();
                showhideItem.classList.toggle("active");
                if (showhideItem.classList.contains("active")){
                    showhideItem.style.height = showhideItem.scrollHeight + "px";
                    showhideItem.style.opacity = "1";
                } else {
                    showhideItem.style.height = "0px";
                    showhideItem.style.opacity = "0";
                }
            });
        });
    }

    //Showhide Logs
    let showhide_numbers = document.querySelectorAll('[data-behaviour="showhide_numbers"]');
    if(showhide_numbers.length){
        showhide_numbers.forEach(showhideItem => {

            showhideItem.addEventListener("click", function (event) {
                event.preventDefault();
                showhideItem.classList.toggle("active");
            });
        });
    }

    //Showhide Logs
    let showhideLogs = document.querySelectorAll('[data-behaviour="showhide_log"]');
    if(showhideLogs.length){
        showhideLogs.forEach(showhideLog => {

            const seemoreOnce = showhideLog.querySelector(".seemore");
            seemoreOnce.style.height = "0px";
            seemoreOnce.style.opacity = "0";

            showhideLog.addEventListener("click", function (event) {
                event.preventDefault();
                showhideLog.classList.toggle("active");
                const seemore = showhideLog.querySelector(".seemore");
                if (showhideLog.classList.contains("active")){
                    seemore.style.height = seemore.scrollHeight + "px";
                    seemoreOnce.style.opacity = "1";
                } else {
                    seemore.style.height = "0px";
                    seemoreOnce.style.opacity = "0";
                }
            });
        });
    }


    //Search Tool
    const searchBar = document.querySelector('#search-location');
    if(searchBar){
        searchBar.addEventListener('keyup', searchLocation);
    }

    function searchLocation(e) {
        const searchValue = e.target.value.toLowerCase();
        const locations = document.querySelectorAll('.location_entry_wrap');
        locations.forEach(location => {
            const locationName = location.querySelector('.location_title').textContent.toLowerCase();
            if (locationName.indexOf(searchValue) != -1) {
                location.classList.add('show');
                location.classList.remove('hide');
            } else {
                location.classList.add('hide');
                location.classList.remove('show');
            }
        })
    }

    const searchExperienceBar = document.querySelector('#search-experience');
    if(searchExperienceBar) {
        searchExperienceBar.addEventListener('keyup', searchExperience);
    }

    function searchExperience(e) {
        const searchValue = e.target.value.toLowerCase();
        const experiences = document.querySelectorAll('.experience_card');
        experiences.forEach(experience => {
            const experienceName = experience.querySelector('.experience_title').textContent.toLowerCase();
            if (experienceName.indexOf(searchValue) != -1) {
                experience.classList.add('show');
                experience.classList.remove('hide');
            } else {
                experience.classList.add('hide');
                experience.classList.remove('show');
            }
        })
    }

    //Experience Overlist List Filter toggle
    const experiencelistfilterToggle = document.querySelector('[data-behaviour="experiencelist_filter"]');
    const experiencelistFilters = document.querySelector('[data-behaviour="experiencelist_filters"]');
    if(experiencelistfilterToggle && experiencelistFilters){
        experiencelistfilterToggle.addEventListener('click', function(e) {
            experiencelistFilters.classList.toggle('active');
        });
    }

    //Experience Overlist List Filters
    const experiences = document.querySelectorAll('[data-behaviour="experiencecard"]');
    let searchExperienceFilters = document.querySelectorAll('[data-behaviour="experiencelist_filter"]');
    if (experiences && searchExperienceFilters) {
        searchExperienceFilters.forEach(filter => {
            filter.addEventListener('change', function(e) {
                recalculateExperienceListFilters(experiences, searchExperienceFilters);
            });
        });
    }

    function recalculateExperienceListFilters($experiences, $filters) {

        $experiences.forEach(experience => {
            experience.classList.remove('hidebyfilter');
        });

        $filters.forEach(filter => {
            const $filterType = filter.getAttribute('data-filtertype');
            switch ($filterType) {
                case "type":
                    experiences.forEach(experience => {
                        const $experiencedataJSON = experience.querySelector('.experiencedata');
                        const $experiencedata = JSON.parse($experiencedataJSON.textContent);
                        if (filter.value != "all") {
                            if ($experiencedata.ExperienceType != filter.value) {
                                experience.classList.add('hidebyfilter');
                                console.log($experiencedata.ExperienceType);
                                console.log(filter.value);
                            }
                        }
                    });
                    break;
                case "state":
                    experiences.forEach(experience => {
                        const $experiencedataJSON = experience.querySelector('.experiencedata');
                        const $experiencedata = JSON.parse($experiencedataJSON.textContent);
                        if (filter.value != "all") {
                            if ($experiencedata.State != filter.value) {
                                experience.classList.add('hidebyfilter');
                                console.log($experiencedata.State);
                                console.log(filter.value);
                            }
                        }
                    });
                default:
                    break;
            }
        })
    }

    const addlogbutton = document.querySelector('[data-behaviour="addlog_button"]');
    const addlogloading = document.querySelector('[data-behaviour="addlog_loading"]');
    if (addlogbutton) {
        addlogbutton.addEventListener('click', function(e) {
            addlogbutton.classList.add('clicked');
            addlogloading.classList.add('clicked');
        });
    }

    //Profile edit button
    const profile_edit_button = document.querySelector('[data-behaviour="profile_edit_button"]');
    const profile_canceledit_button = document.querySelector('[data-behaviour="profile_canceledit_button"]');
    if(profile_edit_button) {
        profile_edit_button.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('profile_edit_active');
        });
        profile_canceledit_button.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('profile_edit_active');
        });
    }



    //Dark Mode Toggle
    var checkbox = document.querySelector('input[name=darkmode]');
    if(checkbox){
        checkbox.addEventListener('change', function() {
            if(this.checked) {
                document.body.classList.add('theme--dark');
                document.body.classList.remove('theme--light');
                if(hasAcceptedCookieConsent) {
                    setCookie("darkmode", "true", 30);
                }
            } else {
                document.body.classList.remove('theme--dark');
                document.body.classList.add('theme--light');
                if(hasAcceptedCookieConsent) {
                    setCookie("darkmode", "false", 30);
                }
            }
        });
    }

    //Cookie Stuff
    function setCookie(name,value,days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/" + "; SameSite=Strict";
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }

    //Cookie Consent for Youtube Videos
    let ytelements = document.querySelectorAll('[data-behaviour="youtube_wrap"]');

    if(hasAcceptedCookieConsent()){
        ytelements.forEach(element => {

            var yturl = element.children[0].getAttribute('data-src');
            console.log("accepted Cookie found " + yturl);
            element.innerHTML = '<iframe width="560" height="315" src="' + yturl + '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
        });
    } else {
        ytelements.forEach(element => {
            var yturl = element.getAttribute('data-src');
            element.innerHTML = `
            <div class="youtube_consent_missing">
                <p><b>Du hast unsere Cookies noch nicht akzeptiert!</p></b>
                <p>Deshalb können wir Dir hier kein Youtube-Video anzeigen</p>
                <a class="link--button hollow white" data-behaviour="youtube_accept">Cookies akzeptieren</a>
            </div>
            `;
        });
    }

    var cookieAcceptButton = document.querySelector('[data-behaviour="cookie_accept_button"]');
    if(cookieAcceptButton){
        cookieAcceptButton.addEventListener("click", function() {
            setCookie('acceptedCookieConsent', 'yes', 30);
            window.location.reload();
            console.log("Cookies accepted!");
        }, false);
    }

    function hasAcceptedCookieConsent(){
        var hasCookie = false;

        if (document.cookie.split(';').some((item) => item.trim().startsWith('acceptedCookieConsent='))) {
            hasCookie = true;
        }
        return hasCookie;
    }

    //change_partselector for experience list toggles
    const partselector = document.querySelectorAll('[data-behaviour="change_partselector"]');
    if(partselector){
        partselector.forEach(element => {
            element.addEventListener('click', function(e) {
                partselector.forEach(element => {
                    element.classList.remove('selected');
                });
                element.classList.add('selected');
            });
        });
    }

    //logsfilter
    const logsfilter = document.querySelector('[data-behaviour="filter_logs"]');
    const filteredlogs = document.querySelectorAll('[data-behaviour="filtered_log"]');

    if(logsfilter && filteredlogs){
        logsfilter.addEventListener("change", function(e) {
            e.preventDefault();
            filteredlogs.forEach(element => {
                if(e.target.value === "All"){
                    element.classList.remove('hide');
                    return;
                } else {
                    element.classList.add('hide');
                    if(element.dataset.experiencetype === e.target.value) {
                        element.classList.remove('hide');
                    }
                }
            });
        });
    }


    //Range input
    for (let e of document.querySelectorAll('input[type="range"].rating')) {
        e.style.setProperty('--value', e.value);
        e.addEventListener('input', () => e.style.setProperty('--value', e.value));
    }


    //Location Progress rendering
    let locationProgressBars = document.querySelectorAll('[data-behaviour="location_progress"]');
    if(locationProgressBars){
        locationProgressBars.forEach(locationProgress => {
            const locationID = locationProgress.getAttribute('data-locationid');
            const locationProgressBar = locationProgress.querySelector('.location_progress_bar');
            const locationProgressText = locationProgress.querySelector('.location_progress_text');
            getLocationProgress(locationID, locationProgress, locationProgressBar, locationProgressText);
        });
    }

    async function getLocationProgress(locationID, locationProgressHolder, locationProgressBar, locationProgressText) {
        const response = await fetch('./app-api/locationprogress/?ID=' + locationID);
        const data = await response.json();

        locationProgressBar.style.width = data["LocationProgress"]["ProgressPercent"] + "%";
        if(data["LocationProgress"]["Defunct"] > 0){
            locationProgressText.textContent = data["LocationProgress"]["Progress"] + " / " + data["LocationProgress"]["Total"] + " Experiences (+" + data["LocationProgress"]["Defunct"] + " not active)";
        } else {
            locationProgressText.textContent = data["LocationProgress"]["Progress"] + " / " + data["LocationProgress"]["Total"] + " Experiences";
        }
        locationProgressHolder.classList.remove("loading");
    }
});
