import React, { useEffect, useState } from 'react';

export const getDistance = (from, to) => {
    if(!from){
        return "";
    }

    let coords = from.split(",");

    let startingLat = degreesToRadians(coords[0]);
    let startingLong = degreesToRadians(coords[1]);
    let destinationLat = degreesToRadians(to.Lat);
    let destinationLong = degreesToRadians(to.Lon);

    // Radius of the Earth in kilometers
    let radius = 6571;

    // Haversine equation
    let distanceInKilometers = Math.acos(Math.sin(startingLat) * Math.sin(destinationLat) +
    Math.cos(startingLat) * Math.cos(destinationLat) *
    Math.cos(startingLong - destinationLong)) * radius;

    if(distanceInKilometers > 2000.00){
        return ">2000 km";
    } else if (distanceInKilometers < 1){
        return parseFloat(distanceInKilometers * 1000).toFixed(0) + " m";
    } else {
        return parseFloat(distanceInKilometers).toFixed(2) + " km";
    }
}



const Distance = ({ userPos, Coordinates }) => {
    if(!userPos){
        return "...";
    }

    return (
        <p>{getDistance(Coordinates, userPos)}</p>
    )
}

export default Distance;
