//const apiEndpoint = "https://experiencelogger.com/app-api"
const apiEndpoint = "./app-api";

const listholder = document.querySelector('[data-behaviour="experienceList"]');
let parkID = 0;

let experiencecards = [];
let experienceCardData;
let loggedIn = true;
let timeout = null;

if(listholder) {
    parkID = listholder.getAttribute('data-parkID');
    checkLogin();

    //if place is saved in browser storage and not older than 1 day, load from storage
    if(localStorage.getItem("place-" + parkID) && Date.now() - localStorage.getItem("lastLoaded-" + parkID) < 86400000){
        //fetch experiences from local storage and only update logs and ratings
        loadExperiencesFromLocalStorage();
        console.log("Loaded from local storage");
    } else {
        //fetch experiences from api and update cards after
        fetchExperiencesFromServer();
        console.log("Loaded from server");
    }
}

function loadExperiencesFromLocalStorage() {
    experienceCardData = JSON.parse(localStorage.getItem("place-" + parkID));
    loggedIn = experienceCardData.LoggedIn;
    experienceCardData.forEach(experience => {
        CalculateLogs(experience);
        CalculateRating(experience);
    });
    renderCards();
}

function fetchExperiencesFromServer(){
    fetch(apiEndpoint + `/experiences?ParkID=${parkID}`)
        .then(response => response.json())
        .then(data => {
            //create an array of experience objects
            experienceCardData = [];

            loggedIn = data.LoggedIn;

            //iterate over json data to get array of experiences
            Object.entries(data.items).forEach(item => {
                var newExperience = {
                    ID: item[1].ID,
                    Title: item[1].Title,
                    Description: item[1].Description,
                    Image: item[1].Image,
                    State: item[1].State,
                    Type: item[1].Type,
                    DetailsLink: item[1].DetailsLink,
                    LogLink: item[1].LoggingLink,
                    Coordinates: item[1].Coordinates,
                    LastEdited: item[1].LastEdited,
                    Area: item[1].Area,
                    LogCount: -1,
                    Rating: -1,
                    VisibleByCurrentFilter: true,
                };
                //save experience to browser storage
                localStorage.setItem("experience-" + newExperience.ID, JSON.stringify(newExperience));

                experienceCardData.push(newExperience);
            });

            localStorage.setItem("place-" + parkID, JSON.stringify(experienceCardData));
            localStorage.setItem("lastLoaded-" + parkID, Date.now());

            experienceCardData.forEach(experience => {
                CalculateLogs(experience);
                CalculateRating(experience);
            });

            renderCards();
        });
}

// RENDER EXPERIENCE CARDS ==========================================================================================
// Renders all experience cards from the current experienceCardData
function renderCards(){
    console.log("Updating cards...");

    //First make sure the ordering is correct:
    SortExperienceList();

    //clear listholder
    listholder.innerHTML = "";

    //iterate over experienceCardData and create experience cards
    if (experienceCardData) {
        experienceCardData.forEach(experience => {
            if(!experience.VisibleByCurrentFilter){
                return;
            }
            const experienceCard = document.createElement('experience-card');
            experienceCard.experience = experience;
            experienceCard.dataset.behaviour = "experiencecard";
            experienceCard.dataset.title = experience.Title;
            experienceCard.dataset.type = experience.Type;
            var cleanedState = experience.State.replace(/\s/g, '');
            experienceCard.classList.add(`state-${cleanedState}`);
            experienceCard.classList.add("experience_card");

            let typeTitle = experience.Type;
            if (experience.Area) {
                typeTitle += ` in ` + experience.Area;
            }

            //Generate experiencecard
            experienceCard.innerHTML = `
                <a href="${experience.DetailsLink}" class="experience_entry">
                    <div class="experience_entry_image" style="background-image: url(${experience.Image ? experience.Image : ''})">
                        <!--<div class="experience_markers">
                            <% if $HasOnridePhoto %>
                                <div class="experience_marker">
                                    <svg width="100%" height="100%" viewBox="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><path d="M50,6.25c24.146,0 43.75,19.604 43.75,43.75c0,24.146 -19.604,43.75 -43.75,43.75c-24.146,0 -43.75,-19.604 -43.75,-43.75c0,-24.146 19.604,-43.75 43.75,-43.75Zm33.104,35.474l-13.242,0c-0.914,0 -1.655,-0.741 -1.655,-1.655l0,-13.242l-48,0c-1.821,0 -3.311,1.49 -3.311,3.311l0,39.724c0,1.821 1.49,3.311 3.311,3.311l59.586,-0c1.821,-0 3.311,-1.49 3.311,-3.311l-0,-28.138Zm-33.104,19.862c-6.399,0 -11.586,-5.187 -11.586,-11.586c-0,-6.399 5.187,-11.586 11.586,-11.586c6.399,-0 11.586,5.187 11.586,11.586c0,6.399 -5.187,11.586 -11.586,11.586Zm0,-3.31c4.571,-0 8.276,-3.705 8.276,-8.276c-0,-4.571 -3.705,-8.276 -8.276,-8.276c-4.571,0 -8.276,3.705 -8.276,8.276c0,4.571 3.705,8.276 8.276,8.276Zm-21.517,-16.552c-1.829,0 -3.311,-1.482 -3.311,-3.31c0,-1.829 1.482,-3.311 3.311,-3.311c1.828,0 3.31,1.482 3.31,3.311c-0,1.828 -1.482,3.31 -3.31,3.31Zm43.034,-14.897l0.001,11.587l11.586,-0l-0,-8.276c-0,-1.821 -1.49,-3.311 -3.311,-3.311l-8.276,0Z" style="fill:currentColor"/></svg>
                                </div>
                            <% end_if %>
                            <% if $HasFastpass %>
                                <div class="experience_marker">
                                    <svg width="100%" height="100%" viewBox="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><path d="M50,6.25c24.146,0 43.75,19.604 43.75,43.75c0,24.146 -19.604,43.75 -43.75,43.75c-24.146,0 -43.75,-19.604 -43.75,-43.75c0,-24.146 19.604,-43.75 43.75,-43.75Zm-27.298,16.452c-1.224,1.224 -1.224,3.209 0,4.434l22.864,22.864l-22.864,22.864c-1.224,1.224 -1.224,3.21 0,4.434c1.224,1.224 3.209,1.224 4.434,-0l25.081,-25.081c1.224,-1.224 1.224,-3.21 -0,-4.434l-25.081,-25.081c-1.225,-1.224 -3.21,-1.224 -4.434,0Zm25.081,0c-1.224,1.224 -1.224,3.209 0,4.434l22.864,22.864l-22.864,22.864c-1.224,1.224 -1.224,3.21 0,4.434c1.224,1.224 3.21,1.224 4.434,-0l25.081,-25.081c1.224,-1.224 1.224,-3.21 -0,-4.434l-25.081,-25.081c-1.224,-1.224 -3.21,-1.224 -4.434,0Z" style="fill:currentColor"/></svg>
                                </div>
                            <% end_if %>
                            <% if $HasSingleRider %>
                                <div class="experience_marker">
                                    <svg width="100%" height="100%" viewBox="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><path d="M50,6.248c24.147,-0 43.752,19.605 43.752,43.752c-0,24.147 -19.605,43.752 -43.752,43.752c-24.147,-0 -43.752,-19.605 -43.752,-43.752c-0,-24.147 19.605,-43.752 43.752,-43.752Zm-27.839,71.878c7.153,7.081 16.989,11.456 27.839,11.456c10.85,-0 20.686,-4.375 27.839,-11.456c-5.289,-9.184 -15.753,-15.443 -27.839,-15.443c-12.086,0 -22.551,6.259 -27.839,15.443Zm27.839,-18.838c12.896,-0 23.351,-10.455 23.351,-23.351c-0,-12.896 -10.455,-23.351 -23.351,-23.351c-12.896,-0 -23.351,10.455 -23.351,23.351c-0,12.896 10.455,23.351 23.351,23.351Zm4.203,-5.548l-5.757,-0c0.247,-4.735 0.371,-9.65 0.371,-14.744c-0,-5.903 -0.124,-10.256 -0.371,-13.062l-7.675,3.838l-1.582,-2.929l12.119,-8.921l2.895,-0c-0.225,7.249 -0.337,14.274 -0.337,21.074c-0,4.802 0.112,9.717 0.337,14.744Z" style="fill:currentColor"/></svg>
                                </div>
                            <% end_if %>
                            <% if $AccessibleToHandicapped %>
                                <div class="experience_marker">
                                    <svg width="100%" height="100%" viewBox="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><path d="M50,6.25c24.146,0 43.75,19.604 43.75,43.75c0,24.146 -19.604,43.75 -43.75,43.75c-24.146,0 -43.75,-19.604 -43.75,-43.75c0,-24.146 19.604,-43.75 43.75,-43.75Zm13.333,66.783l-4.201,-8.598c-1.01,8.543 -8.409,15.203 -17.087,15.203c-9.485,-0 -17.221,-7.736 -17.221,-17.221c0,-5.853 3.027,-11.436 7.938,-14.53l-0.537,-7.012c-8.303,3.782 -13.866,12.313 -13.866,21.494c-0,13.075 10.664,23.739 23.739,23.739c8.902,-0 17.247,-5.193 21.235,-13.075Zm-23.76,-46.29c3.269,-0.302 5.809,-3.119 5.809,-6.413c-0,-3.546 -2.892,-6.438 -6.438,-6.438c-3.546,0 -6.438,2.892 -6.438,6.438c0,1.081 0.302,2.188 0.805,3.118l2.294,32.278l23.625,0.006l9.689,22.704l12.722,-4.989l-1.97,-4.691l-7.119,2.57l-9.376,-21.645l-21.966,0.147l-0.301,-4.087l15.901,0.006l0,-6.048l-16.508,-0.007l-0.729,-12.949Z" style="fill:currentColor"/></svg>
                                </div>
                            <% end_if %>
                        </div>-->
                    </div>
                    <div class="experience_entry_content">
                        <h2 class="experience_title">${experience.Title}</h2>
                        <div class="experience_type" data-filter="" data-status="${experience.Status}">
                            <p>${typeTitle}</p>
                            <p>|</p>
                        </div>
                        <p class="experience_distance" data-behaviour="distance" data-loc="${experience.Coordinates}"></p>
                    </div>
                </a>
            `;
            var experienceTypeText = experienceCard.querySelector('.experience_type');
            if (experienceTypeText) {
                experienceTypeText.appendChild(getRatingStars(experience));
            }
            experienceCard.appendChild(getLogButton(experience));
            listholder.appendChild(experienceCard);

            //add experiencecard to experiencecards array
            experiencecards.push(experienceCard);
        });
    }
}

function getLogButton(experience){
    const logButton = document.createElement('div');
    if(!loggedIn) {
        return logButton;
    }

    logButton.innerHTML = `
        <div class="experience_logging">
            <a class="logging_link" href="${experience.LogLink}" data-behaviour="logCounter" data-loggingid="${experience.ID}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" height="100%" width="100%"><path fill="currentColor" d="M22.65 34h3v-8.3H34v-3h-8.35V14h-3v8.7H14v3h8.65ZM24 44q-4.1 0-7.75-1.575-3.65-1.575-6.375-4.3-2.725-2.725-4.3-6.375Q4 28.1 4 23.95q0-4.1 1.575-7.75 1.575-3.65 4.3-6.35 2.725-2.7 6.375-4.275Q19.9 4 24.05 4q4.1 0 7.75 1.575 3.65 1.575 6.35 4.275 2.7 2.7 4.275 6.35Q44 19.85 44 24q0 4.1-1.575 7.75-1.575 3.65-4.275 6.375t-6.35 4.3Q28.15 44 24 44Z"/></svg>
                <p class="logcount" data-behavior="logCounterValue">${experience.LogCount > -1 ? experience.LogCount : '...'}</p>
            </a>
        </div>
    `;

    //CalculateLogCount(experience);
    //CalculateRatings(experience);
    return logButton;
}

function getRatingStars(experience) {
    const starRatingDiv = document.createElement('div');
    starRatingDiv.classList.add('ratingdisplay');
    starRatingDiv.classList.add('ratingdisplay--inline');
    starRatingDiv.dataset.behaviour = "ratingStars";
    starRatingDiv.dataset.ratingid = experience.ID;

    if (experience.Rating == -1) {
        starRatingDiv.classList.add('ratingdisplay--loading');
        starRatingDiv.title = "Loading Rating...";
        starRatingDiv.style.setProperty('--stars', 0);
    } else {
        starRatingDiv.style.setProperty('--stars', experience.Rating);
        starRatingDiv.title = experience.Rating + " Stars";
    }
    return starRatingDiv;
}

function CalculateLogs(experience) {
    fetch(apiEndpoint + `/logCountForExperience/` + experience.ID)
        .then(response => response.json())
        .then(data => {
            logCount = data.LogCount;
            experience.LogCount = logCount;
            localStorage.setItem("experience-" + experience.ID, JSON.stringify(experience));
            localStorage.setItem("place-" + parkID, JSON.stringify(experienceCardData));

            //if there is a visible log button, update the log count
            const loggingLink = document.querySelector('[data-loggingid="' + experience.ID + '"]');
            if(loggingLink){
                const logCountDisplay = loggingLink.querySelector('[data-behavior="logCounterValue"]');
                logCountDisplay.textContent = logCount;
            }
        });
}

function CalculateRating(experience) {
    fetch(apiEndpoint + `/ratingForExperience/` + experience.ID)
        .then(response => response.json())
        .then(data => {
            ratingScore = data.Rating;
            experience.Rating = ratingScore;
            localStorage.setItem("experience-" + experience.ID, JSON.stringify(experience));
            localStorage.setItem("place-" + parkID, JSON.stringify(experienceCardData));

            //if there is a visible rating display, update the rating
            const ratingDisplay = document.querySelector('[data-ratingid="' + experience.ID + '"]');
            if (ratingDisplay) {
                if (ratingScore > 0) {
                    ratingDisplay.style.setProperty('--stars', ratingScore);
                    ratingDisplay.title = ratingScore + " Stars";
                    ratingDisplay.classList.remove('ratingdisplay--loading');
                } else {
                    ratingDisplay.previousElementSibling.remove();
                    ratingDisplay.remove();
                }
            }
        });
}

function checkLogin(){
    fetch(apiEndpoint + `/checkLogin`)
        .then(response => response.json())
        .then(data => {
            loggedIn = data.LoggedIn;
            renderCards();
            console.log("LoggedIn", loggedIn);
        });
}


//TOGGLE TAB MENU =================================================================================================
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


//EXPERIENCE FILTERING =================================================================================================
window.type = 'all';

// Toggle the visibility of filters on Experience List
const experiencelistfilterToggle = document.querySelector('[data-behaviour="experiencelist_togglefilter"]');
const experiencelistFilters = document.querySelector('[data-behaviour="experiencelist_filters"]');
if(experiencelistfilterToggle && experiencelistFilters){
    experiencelistfilterToggle.addEventListener('click', function(e) {
        experiencelistFilters.classList.toggle('active');
    });
}

//subscribe to edit event on the filters
const experiences = document.querySelectorAll('[data-behaviour="experiencecard"]');
let searchExperienceFilters = document.querySelectorAll('[data-behaviour="experiencelist_filter"]');
if (experiences && searchExperienceFilters) {
    searchExperienceFilters.forEach(filter => {
        if(filter.getAttribute('data-filtertype') === "query"){
            filter.addEventListener('keyup', function(e) {
                recalculateExperienceListFilters(experienceCardData, searchExperienceFilters);
            });
        } else {
            filter.addEventListener('change', function(e) {
                recalculateExperienceListFilters(experienceCardData, searchExperienceFilters);
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

    //Delay the filtering to prevent too many requests
    clearTimeout(timeout);

    timeout = setTimeout(function () {
        filters.forEach(filter => {
            const filterType = filter.getAttribute('data-filtertype');
            switch (filterType) {
                case "query":
                        window.filter_query = filter.value;
                    break;
                case "type":
                    // Filter by type
                    window.filter_type = filter.value;
                    break;
                case "state":
                    // Filter by state
                    window.filter_state = filter.value;
                case "sort":
                    // Sort by
                    window.sort = filter.value;
                default:
                    break;
            }
        });
        applyCardsFilter();
    }, 100);
}

function applyCardsFilter() {
    console.log("Applying filters...", window.filter_query, window.filter_type, window.filter_state, window.sort);
    experienceCardData.forEach(experience => {
        let show = true;
        if (window.filter_query && window.filter_query != '') {
            if (!experience.Title.toLowerCase().includes(window.filter_query.toLowerCase())) {
                show = false;
            }
        }
        if (window.filter_type && window.filter_type !== 'all') {
            if (experience.Type.toLowerCase() != window.filter_type.toLowerCase()) {
                show = false;
            }
        }
        if (window.filter_state && window.filter_state !== 'all') {
            if (experience.State.toLowerCase() != window.filter_state.toLowerCase()) {
                show = false;
            }
        }
        experience.VisibleByCurrentFilter = show;
    });
    renderCards();
}

function SortExperienceList() {
    //reorder experiencecards
    switch (window.sort) {
        case "rating":
            experienceCardData.sort((a, b) => {
                let ratingA = a.Rating;
                let ratingB = b.Rating;
                return ratingB - ratingA;
            });
            break;
        case "logcount":
            experienceCardData.sort((a, b) => {
                let logCountA = a.LogCount;
                let logCountB = b.LogCount;
                return logCountB - logCountA;
            });
            break;
        case "type":
            experienceCardData.sort((a, b) => {
                let typeA = a.Type;
                let typeB = b.Type;
                return typeA.localeCompare(typeB);
            });
            break;
        case "title":
            experienceCardData.sort((a, b) => {
                let titleA = a.Title;
                let titleB = b.Title;
                return titleA.localeCompare(titleB);
            });
            break;
        default:
            //First order by state, then by title
            experienceCardData.sort((a, b) => {
                let stateA = a.State;
                let stateB = b.State;
                let titleA = a.Title;
                let titleB = b.Title;
                return stateA.localeCompare(stateB) || titleA.localeCompare(titleB);
            });
            break;
    }
}
