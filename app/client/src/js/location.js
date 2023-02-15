console.log("locationsystem loaded");

document.addEventListener("DOMContentLoaded", function (event) {
    let distanceFields = document.querySelectorAll('[data-behaviour="distance"]');

    const locationTracker = document.querySelector('[data-behaviour="locationTracker"]');
    var userPos = {
        lat: 0.0,
        lon: 0.0
    };

    if(locationTracker){
        locationTracker.addEventListener('click', function(e) {
            e.preventDefault();
            if(distanceFields.length){
                distanceFields.forEach(distanceField => {
                    var lat = distanceField.getAttribute('data-lat');
                    var lon = distanceField.getAttribute('data-lon');
                    writeDistance(distanceField, lat, lon);
                });
            }
        });
    }

    function writeDistance(field, lat, lon) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                savePosition(position)
                field.innerHTML = "<p>" + distance(lat, lon, userPos.lat, userPos.lon) + "km</p>";
            });
        } else {
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

        return parseFloat(distanceInKilometers).toFixed(2);
    }
});
