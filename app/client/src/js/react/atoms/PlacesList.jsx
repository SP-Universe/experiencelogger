import React from "react";
import { useState, useEffect } from 'react';
import PlaceCard from "../components/PlaceCard";

const PlacesList = () => {

    const [places, setPlaces] = useState([]);
    const [query, setQuery] = useState([]);

    useEffect(() => {
        //TODO: Change for correct server when released
        fetch('http://experiencelogger.test/app-api/placesnew')
            .then((res) => {
                return res.json();
            })
            .then((data) => {
                    setPlaces(data);
            });
    }, []);

    return (
        <div className='places_list'>
            <form>
                <input type="text" placeholder="Search..." onChange={e => setQuery(e.target.value)} />
            </form>
            {places.filter(place => place.LocationTitle.includes(query)).map((place) => (
                <PlaceCard place={place} key={place.LocationTitle}/>
            ))}
        </div>
    );
};

export default PlacesList;
