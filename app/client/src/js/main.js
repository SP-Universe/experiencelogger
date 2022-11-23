import GLightbox from "glightbox";
import { tns } from "tiny-slider/src/tiny-slider";
import "tiny-slider/dist/tiny-slider.css";

if (navigator && navigator.serviceWorker) {
    navigator.serviceWorker.register('service-worker.js');
}

document.addEventListener("DOMContentLoaded", function (event) {

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
    if(personalNavButton){
        personalNavButton.addEventListener("click", function (event) {
            event.preventDefault();
            document.body.classList.toggle("personalnav_active");
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
});
