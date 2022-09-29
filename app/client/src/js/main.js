import GLightbox from "glightbox";

document.addEventListener("DOMContentLoaded", function (event) {

    const lightbox = GLightbox({
        selector: '[data-gallery="gallery"]',
        touchNavigation: true,
        loop: true,
    });

    //Search Tool
    const searchBar = document.querySelector('#search-location');
    if(searchBar){
        searchBar.addEventListener('keyup', searchLocation);
    }

    function searchLocation(e) {
        const searchValue = e.target.value.toLowerCase();
        const locations = document.querySelectorAll('.location_entry');
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
        const experiences = document.querySelectorAll('.experience_entry');
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
