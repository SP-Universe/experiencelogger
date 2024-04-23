console.log("helpers loaded");

window.degreesToRadians = function degreesToRadians(degrees) {
    var radians = (degrees * Math.PI)/180;
    return radians;
}
