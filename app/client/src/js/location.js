console.log("locationsystem loaded");

document.addEventListener("DOMContentLoaded", function (event) {
    let distanceFields = document.querySelectorAll('[data-behaviour="distance"]');

    const locationTracker = document.querySelector('[data-behaviour="locationTracker"]');
    let distanceActive = false;
    console.log("distanceActive: " + distanceActive);
    var userPos = {
        lat: 0.0,
        lon: 0.0
    };

    if(locationTracker){
        locationTracker.addEventListener('click', function(e) {
            e.preventDefault();
            distanceActive = true;
            updateDistances();
            locationTracker.remove();
        });
    }

    window.updateDistances = function updateDistances() {
        if(distanceFields.length){
            distanceFields.forEach(distanceField => {
                var loc = distanceField.getAttribute('data-loc');
                if(loc){
                    var coords = loc.split(",");
                    var lat = coords[0];
                    var lon = coords[1];
                    console.log("lat: " + lat + " lon: " + lon)
                    writeDistance(distanceField, lat, lon);
                } else {
                    distanceField.innerHTML = "? m";
                    distanceField.parentNode.parentNode.parentNode.setAttribute('data-distance', 999999999); //Set the distance on the parent Experiencecard
                }
            });
        }
    }


    function writeDistance(field, lat, lon) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                savePosition(position)
                var OutputDistance = distance(lat, lon, userPos.lat, userPos.lon);
                if(OutputDistance > 1){
                    field.parentNode.parentNode.parentNode.setAttribute('data-distance', OutputDistance); //Set the distance on the parent Experiencecard
                    field.innerHTML = "<p>" + OutputDistance + " km</p>";
                } else {
                    field.parentNode.parentNode.parentNode.setAttribute('data-distance', OutputDistance); //Set the distance on the parent Experiencecard
                    field.innerHTML = "<p>" + (OutputDistance * 100) + " m</p>";
                }
            });
        } else {
            field.parentNode.parentNode.parentNode.setAttribute('data-distance', 999999999); //Set the distance on the parent Experiencecard
            field.innerHTML = "???";
        }
    }

    function savePosition(position) {
        //console.log("Latitude: " + position.coords.latitude + " Longitude: " + position.coords.longitude);
        userPos.lat = position.coords.latitude;
        userPos.lon = position.coords.longitude;
    }

    function distance (lat1, lon1, lat2, lon2){
        let startingLat = degreesToRadians(lat1);
        let startingLong = degreesToRadians(lon1);
        let destinationLat = degreesToRadians(lat2);
        let destinationLong = degreesToRadians(lon2);

        // Radius of the Earth in kilometers
        let radius = 6571;

        // Haversine equation
        let distanceInKilometers = Math.acos(Math.sin(startingLat) * Math.sin(destinationLat) +
        Math.cos(startingLat) * Math.cos(destinationLat) *
        Math.cos(startingLong - destinationLong)) * radius;

        if(distanceInKilometers > 1000.00){
            return ">1000";
        } else {
            return parseFloat(distanceInKilometers).toFixed(2);
        }

    }

    setInterval(function(){
        if(distanceActive === true) {
            console.log("updating distances");
            updateDistances();
        }
    }, 20000);
});
