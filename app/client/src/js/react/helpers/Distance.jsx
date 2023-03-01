import React, { useState } from 'react';

class Distance extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            ...props,
            text: "...",
            userPos: {
                Lat: 0.0,
                Lon: 0.0
            }
        };
    }

    getDistance = () => {
        if(this.props.Coordinates == undefined){
            return "";
        }
        console.log("Experience Location: " + this.props.Coordinates + " | User Location: " + this.state.userPos.Lat + ", " + this.state.userPos.Lon);

        const coordsstring = this.props.Coordinates;
        let coords = coordsstring.split(",");

        let startingLat = degreesToRadians(coords[0]);
        let startingLong = degreesToRadians(coords[1]);
        let destinationLat = degreesToRadians(this.state.userPos.Lat);
        let destinationLong = degreesToRadians(this.state.userPos.Lon);

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

    componentDidMount = () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const Lat = position.coords.latitude;
                const Lon = position.coords.longitude;

                this.setState({
                    userPos: {
                        Lat,
                        Lon
                    }
                });
                console.log("User Position set: " + position.coords.latitude + ", " + position.coords.longitude);
            });
        }
    }

    render (){
        return (
            <p>HUHU{this.getDistance()}</p>
        );
    }
}

export default Distance;
