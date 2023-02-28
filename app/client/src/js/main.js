import GLightbox from "glightbox";
import { tns } from "tiny-slider/src/tiny-slider";
import "tiny-slider/dist/tiny-slider.css";

import "../js/helpers.js";
import "../js/location.js";
import "../js/jsonloader.js";

window.addEventListener("load", () => {
    if ("serviceWorker" in navigator) {
      navigator.serviceWorker.register("service-worker.js");
    }
});

/*if (navigator && navigator.serviceWorker) {
    navigator.serviceWorker.register('service-worker.js');
}*/



document.addEventListener("DOMContentLoaded", function (event) {
    //Load Data
    if(navigator.onLine) {
        loadOnlineData();
    } else {
        loadOfflineData();
    }

    //Glightbox
    const lightbox = GLightbox({
        selector: '[data-gallery="gallery"]',
        touchNavigation: true,
        loop: true,
    });

    //Slider
    var sliders = document.querySelectorAll('[data-behaviour="slider"]');

    if (sliders.length) {
        [...sliders].map(slider => {
                return tns({
                    mode: 'carousel',
                    container: slider,
                    items: 1,
                    slideBy: 'page',
                    navAsThumbnails: true,
                    nav: true,
                    controls: true,
                    controlsText: ['&lt;', '&gt;'],
                    mouseDrag: true,
                    autoplay: false,
                    autoplayTimeout: 3000,
                    speed: 200,
                    autoplayHoverPause: true,
                    autoplayButtonOutput: false,
                });
        })
    }

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

    const advancedsearchbutton = document.querySelector('.advancedfilters_toggle');
    const advancedFilters = document.querySelector('.advancedfilters');

    if(advancedsearchbutton) {
        advancedsearchbutton.addEventListener('click', function() {
            advancedsearchbutton.classList.toggle('active');
            advancedFilters.classList.toggle('active');
        });
    }

    const experiences = document.querySelectorAll('.experience_entry');
    let searchExperienceFilters = document.querySelectorAll('.filterbutton');
    if(searchExperienceFilters) {
        searchExperienceFilters.forEach(filter => {
            const filterTypeValue = filter.getAttribute('data-filter').toLowerCase();
            filter.addEventListener('click', function(e) {
                e.preventDefault();
                filter.classList.toggle("inactive");

                experiences.forEach(experience => {
                    const experienceType = experience.querySelector('.experience_type').textContent.toLowerCase();
                    if (experienceType == filterTypeValue) {
                        experience.classList.toggle('hidebyfilter');
                    }
                })
            });
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

    //Showhide Filters
    let showhideFilters = document.querySelectorAll('[data-behaviour="showhide_filter"]');

    if(showhideFilters.length){
        showhideFilters.forEach(showhideFilter => {
            showhideFilter.addEventListener("click", function (event) {
                event.preventDefault();
                showhideFilter.parentElement.classList.toggle("filter-active");
            });
        });
    }

    //Dark Mode Toggle
    var checkbox = document.querySelector('input[name=darkmode]');
    if(checkbox){
        checkbox.addEventListener('change', function() {
            if(this.checked) {
                document.body.classList.add('theme--dark');
                if(hasAcceptedCookieConsent) {
                    setCookie("darkmode", "true", 30);
                }
            } else {
                document.body.classList.remove('theme--dark');
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
});
