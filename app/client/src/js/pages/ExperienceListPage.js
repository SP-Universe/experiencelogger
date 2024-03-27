// Toggle the visibility of filters on Experience List
const experiencelistfilterToggle = document.querySelector('[data-behaviour="experiencelist_filter"]');
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
        filter.addEventListener('change', function(e) {
            recalculateExperienceListFilters(experiences, searchExperienceFilters);
        });
    });
}

function recalculateExperienceListFilters($experiences, $filters) {
    const experienceArray = Array.from($experiences);
    $experiences.forEach(experience => {
        experience.classList.remove('hidebyfilter');
    });

    $filters.forEach(filter => {
        const filterType = filter.getAttribute('data-filtertype');
        switch (filterType) {
            case "type":
                experiences.forEach(experience => {
                    const experiencedataJSON = experience.querySelector('.experiencedata');
                    const experiencedata = JSON.parse(experiencedataJSON.textContent);
                    if (filter.value != "all") {
                        if (experiencedata.ExperienceType != filter.value) {
                            experience.classList.add('hidebyfilter');
                            console.log(experiencedata.ExperienceType);
                            console.log(filter.value);
                        }
                    }
                });
                break;
            case "state":
                experiences.forEach(experience => {
                    const experiencedataJSON = experience.querySelector('.experiencedata');
                    const experiencedata = JSON.parse(experiencedataJSON.textContent);
                    if (filter.value != "all") {
                        if (experiencedata.State != filter.value) {
                            experience.classList.add('hidebyfilter');
                            console.log(experiencedata.State);
                            console.log(filter.value);
                        }
                    }
                });
            case "sort":
                if(filter.value == "distance"){
                    experienceArray.sort(function(a, b) {
                        return a.getAttribute('data-distance') - b.getAttribute('data-distance');
                    }).forEach(function(node) {
                        node.parentNode.appendChild(node);
                    });
                } else if(filter.value == "title"){
                    experienceArray.sort(function(a, b) {
                        a = a.getAttribute("data-title");
                        b = b.getAttribute("data-title");

                        return a.localeCompare(b);
                    }).forEach(function(node) {
                        node.parentNode.appendChild(node);
                    });
                } else if(filter.value == "type"){
                    experienceArray.sort(function(a, b) {
                        a = a.getAttribute("data-type");
                        b = b.getAttribute("data-type");

                        return a.localeCompare(b);
                    }).forEach(function(node) {
                        node.parentNode.appendChild(node);
                    });
                }
            default:
                break;
        }
    })
}


// Searchbar for Experiences
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

// Change the visible Part of the List by button press
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
