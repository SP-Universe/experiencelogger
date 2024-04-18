import React from "react";
import Header from "../molecules/Header";
import { useParams } from "react-router-dom";
import { useState, useEffect } from 'react';
import ExperienceCard from "../components/ExperienceCard";

const ExperiencesList = ({placeTitle}) => {

    const [experiences, setExperiences] = useState([]);

    useEffect(() => {
        //TODO: Change for correct server when released
        fetch('http://experiencelogger.test/app-api/experiences/?LocationTitle=' + placeTitle)
            .then((res) => {
                return res.json();
            })
            .then((data) => {
                setExperiences(data.items);
                console.log(data.items);
            });
    }, []);

    return (
        <div>
            <p>Coming soon...</p>
            {experiences.forEach(experience => {
                <ExperienceCard experience={experience} key={experience.Title}/>
            })}
        </div>
    );
};

export default ExperiencesList;
