import React from "react";
import { useState, useEffect } from "react";
import ExperienceCard from "./ExperienceCard.jsx";

function ExperienceCardList( props ) {
    const cardstring = [];
    Object.keys(localStorage).forEach(function(key){
        if(key.startsWith("experience_")) {
            var data = localStorage.getItem(key);
            cardstring.push(<ExperienceCard jsondata={data} key={key}/>);
        }
    })
    return (
        <div className="experience_list">
            {cardstring}
        </div>
    )
}

export default ExperienceCardList;
