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

//Get the progress of a location asynchronously
async function getLocationProgress(locationID, locationProgressHolder, locationProgressBar, locationProgressText) {
    console.log("Getting progress for location " + locationID);
    const response = await fetch('./app-api/locationprogress/?ID=' + locationID);
    if(response.status == 200){
        console.log("Loading progress: " + response + " - " + response.url);
        const data = await response.json();

        locationProgressBar.style.width = data["LocationProgress"]["ProgressPercent"] + "%";
        if(data["LocationProgress"]["Defunct"] > 0){
            locationProgressText.textContent = data["LocationProgress"]["Progress"] + " / " + data["LocationProgress"]["Total"] + " Experiences (+" + data["LocationProgress"]["Defunct"] + " not active)";
        } else {
            locationProgressText.textContent = data["LocationProgress"]["Progress"] + " / " + data["LocationProgress"]["Total"] + " Experiences";
        }
        locationProgressHolder.classList.remove("loading");
    } else {
        locationProgressBar.style.width = "0%";
        locationProgressText.textContent = "Error loading progress. API didnt answer " + " (Response: " + response.status + ")";
        locationProgressHolder.classList.remove("loading");
    }
}

