console.log('ExperienceListPage.js loaded');

// Toggle the visibility of filters on Experience List
const experiencelistfilterToggle = document.querySelector('[data-behaviour="experiencelist_togglefilter"]');
const experiencelistFilters = document.querySelector('[data-behaviour="experiencelist_filters"]');
if(experiencelistfilterToggle && experiencelistFilters){
    experiencelistfilterToggle.addEventListener('click', function(e) {
        experiencelistFilters.classList.toggle('active');
    });
}

// Filters for Experience List
const experiences = document.querySelectorAll('[data-behaviour="experiencecard"]');
let searchExperienceFilters = document.querySelectorAll('[data-behaviour="experiencelist_filter"]');
if (experiences && searchExperienceFilters) {
    searchExperienceFilters.forEach(filter => {
        if(filter.getAttribute('data-filtertype') === "query"){
            filter.addEventListener('keyup', function(e) {
                recalculateExperienceListFilters(experiences, searchExperienceFilters);
            });
        } else {
            filter.addEventListener('change', function(e) {
                recalculateExperienceListFilters(experiences, searchExperienceFilters);
            });
        }
    });
}
