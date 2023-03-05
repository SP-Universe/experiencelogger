import React from 'react';

export const getDistance = (from, to) => {
    if(!from){
        return "";
    }

    if(!to){
        return "";
    }

    let fromCoords = {
        "Lat": 0.0,
        "Lon": 0.0
    }

    let toCoords = {
        "Lat": 0.0,
        "Lon": 0.0
    }

    if(typeof from === "string"){
        fromCoords = {
            "Lat": from.split(",")[0],
            "Lon": from.split(",")[1]
        }
    } else {
        fromCoords = from;
    }

    if(typeof to === "string"){
        toCoords = {
            "Lat": to.split(",")[0],
            "Lon": to.split(",")[1]
        }
    } else {
        toCoords = to;
    }

    let startingLat = degreesToRadians(fromCoords.Lat);
    let startingLong = degreesToRadians(fromCoords.Lon);
    let destinationLat = degreesToRadians(toCoords.Lat);
    let destinationLong = degreesToRadians(toCoords.Lon);

    // Radius of the Earth in kilometers
    let radius = 6571;

    // Haversine equation
    let distanceInKilometers = Math.acos(Math.sin(startingLat) * Math.sin(destinationLat) +
    Math.cos(startingLat) * Math.cos(destinationLat) *
    Math.cos(startingLong - destinationLong)) * radius;

    return distanceInKilometers;
}

export const getStyledDistance = (distance) => {
    if(distance > 2000.00){
        return (
            <p> \>2000 km </p>
        )
    } else if (distance < 1){
        return (
            <p>{parseFloat(distance * 1000).toFixed(0)}m</p>
        )
    } else {
        return (
            <p>{parseFloat(distance).toFixed(2)}km</p>
        )
    }
}

const Distance = ({ userPos, Coordinates }) => {
    if(!Coordinates){
        return "";
    }

    if(!userPos){
        return "...";
    }

    return (
        <p>{getStyledDistance(getDistance(Coordinates, userPos))}</p>
    )
}

export default Distance;
