import React from "react";
import Header from "../molecules/Header";
import { useParams } from "react-router-dom";
import { useState, useEffect } from 'react';
import ExperiencesList from "../atoms/ExperiencesList";

const PlaceDetailPage = () => {

    const { placeTitle } = useParams();

    return (
        <div>
            <Header SiteTitle={placeTitle}/>
            <ExperiencesList placeTitle={placeTitle}/>
        </div>
    );
};

export default PlaceDetailPage;
