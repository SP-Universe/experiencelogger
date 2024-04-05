//Search Tool
const searchBar = document.querySelector('#search-location');
if(searchBar){
    searchBar.addEventListener('keyup', searchLocation);
}

async function loadLocations() {
    const locationHolder = document.querySelector('.location_list');
    const data = await currentLocationData();
    console.log(data);
    if(data != ""){
        for(let i = 0; i < data.length; i++){
            if(data[i]["LocationTitle"] != ""){
                const locationCard = document.createElement('div');
                locationCard.classList.add('location_card');
                locationCard.innerHTML = `
                    <a href="${data[i]["LocationLink"]}" class="location_entry">
                        <div class="location_entry_image">
                            <img src="${data[i]["Image"]}" alt="${data[i]["LocationTitle"]}">
                        </div>
                        <div class="location_entry_content">
                            <h2 class="location_title">${data[i]["LocationTitle"]}</h2>
                            <h3 class="location_type">${data[i]["LocationTypeTitle"]}</h3>
                            <div class="progress_handler loading" data-behaviour="location_progress" data-locationid="$ID">
                                <p class="location_progress_text">Loading...</p>
                                <div class="location_progress">
                                    <div class="location_progress_bar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                `;
                const locationFavouriteButton = document.createElement('a');
                locationFavouriteButton.href = "#";
                locationFavouriteButton.classList.add('location_favouritemarker');
                locationFavouriteButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" height="48px" width="48px"><path fill="currentColor" d="m24 41.95-2.05-1.85q-5.3-4.85-8.75-8.375-3.45-3.525-5.5-6.3T4.825 20.4Q4 18.15 4 15.85q0-4.5 3.025-7.525Q10.05 5.3 14.5 5.3q2.85 0 5.275 1.35Q22.2 8 24 10.55q2.1-2.7 4.45-3.975T33.5 5.3q4.45 0 7.475 3.025Q44 11.35 44 15.85q0 2.3-.825 4.55T40.3 25.425q-2.05 2.775-5.5 6.3T26.05 40.1Z"/></svg>
                `;
                locationCard.appendChild(locationFavouriteButton);
                locationHolder.appendChild(locationCard);
            }
        }
    }
}

loadLocations();

function searchLocation(e) {
    const searchValue = e.target.value.toLowerCase();
    const locations = document.querySelectorAll('.location_card');
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
    const response = await fetch('./app-api/locationprogress/?ID=' + locationID);
    if(response.status != 200){
        const data = await response.json();

        locationProgressBar.style.width = data["LocationProgress"]["ProgressPercent"] + "%";
        if(data["LocationProgress"]["Defunct"] > 0){
            locationProgressText.textContent = data["LocationProgress"]["Progress"] + " / " + data["LocationProgress"]["Total"] + " Experiences (+" + data["LocationProgress"]["Defunct"] + " not active)";
        } else {
            locationProgressText.textContent = data["LocationProgress"]["Progress"] + " / " + data["LocationProgress"]["Total"] + " Experiences";
        }
        locationProgressHolder.classList.remove("loading");
    }
}
