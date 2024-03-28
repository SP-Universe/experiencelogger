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

function recalculateExperienceListFilters(experiences, filters) {

    let headlines = document.querySelectorAll('[data-behavior="experience-group-headline"]');
    if(headlines){
        headlines.forEach(headline => {
            headline.remove();
        });
    }

    const experienceArray = Array.from(experiences);
    experiences.forEach(experience => {
        experience.classList.remove('hidebyfilter');
    });

    filters.forEach(filter => {
        const filterType = filter.getAttribute('data-filtertype');
        switch (filterType) {
            case "query":
                // Filter by search query
                experiences.forEach(experience => {
                    let experienceName = experience.querySelector('.experience_title').textContent.toLowerCase();
                    let filtervalue = filter.value.toLowerCase();
                    experienceName = experienceName.normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[^A-Za-z0-9]/g, '');
                    filtervalue = filtervalue.normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[^A-Za-z0-9]/g, '');
                    if (!experienceName.indexOf(filtervalue) == "") {
                        if (experienceName.indexOf(filtervalue) != -1) {
                        } else {
                            experience.classList.add('hidebyfilter');
                        }
                    }
                });
                break;
            case "type":
                // Filter by type
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
                // Filter by state
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
                // Sort by distance, title or type
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
