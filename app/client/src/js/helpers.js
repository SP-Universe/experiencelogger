console.log("helpers loaded");
let locationData = "";

window.degreesToRadians = function degreesToRadians(degrees) {
    var radians = (degrees * Math.PI)/180;
    return radians;
}


window.getCurrentLocationData = async function getCurrentLocationData() {
    const response = await fetch('./app-api/placesnew/');
    if(response.status == 200){
        const data = await response.json();
        return data;
    } else {
        console.log("ERROR: Could not fetch location data at " + response.url + " with status " + response.status);
    }
}

window.currentLocationData = async function currentLocationData() {
    if(locationData == ""){
        locationData = await getCurrentLocationData();
        return locationData;
    } else {
        return locationData;
    }
}

window.getImagelink = async function getImagelink(imageID) {
    const response = await fetch('./app-api/image/' + imageID);
    if(response.status == 200){
        const data = await response.json();
        return data["Link"];
    } else {
        console.log("ERROR: Could not fetch iamge data at " + response.url + " with status " + response.status);
    }
}
