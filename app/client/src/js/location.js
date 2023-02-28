console.log("locationsystem loaded");

document.addEventListener("DOMContentLoaded", function (event) {
    let distanceFields = document.querySelectorAll('[data-behaviour="distance"]');

    const locationTracker = document.querySelector('[data-behaviour="locationTracker"]');
    var userPos = {
        lat: 0.0,
        lon: 0.0
    };

    if(distanceFields.length){
        distanceFields.forEach(distanceField => {
            var loc = distanceField.getAttribute('data-loc');
            if(loc){
                var coords = loc.split(",");
                var lat = coords[0];
                var lon = coords[1];
                console.log("1 lat: " + lat + " lon: " + lon)
                writeDistance(distanceField, lat, lon);
            } else {
                distanceField.innerHTML = "";
            }
        });
    }

    if(locationTracker){
        locationTracker.addEventListener('click', function(e) {
            e.preventDefault();
            if(distanceFields.length){
                distanceFields.forEach(distanceField => {
                    var loc = distanceField.getAttribute('data-loc');
                    if(loc){
                        var coords = loc.split(",");
                        var lat = coords[0];
                        var lon = coords[1];
                        console.log("2 lat: " + lat + " lon: " + lon)
                        writeDistance(distanceField, lat, lon);
                    } else {
                        distanceField.innerHTML = "";
                    }
                });
            }
            locationTracker.remove();
        });
    }

    function writeDistance(field, lat, lon) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                savePosition(position)
                field.innerHTML = "<p>" + distance(lat, lon, userPos.lat, userPos.lon) + "</p>";
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

        if(distanceInKilometers > 1000.00){
            return ">1000";
        } else if (distanceInKilometers < 1){
            return parseFloat(distanceInKilometers * 1000).toFixed(0) + " m";
        } else {
            return parseFloat(distanceInKilometers).toFixed(2) + " km";
        }

    }
});
