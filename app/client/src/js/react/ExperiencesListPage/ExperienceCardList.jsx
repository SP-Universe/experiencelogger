import React from "react";
import { useState, useEffect } from "react";
import ExperienceCard from "./ExperienceCard.jsx";

function ExperienceCardList( {userPos} ) {
    const cards = [];
    Object.keys(localStorage).forEach(function(key){
        if(key.startsWith("xpl-experience__")) {
            var data = localStorage.getItem(key);
            cards.push(<ExperienceCard userPos={userPos} jsondata={data} key={key}/>);
        }
    })
    return (
        <div className="experience_list">
            {cards}
        </div>
    )
}

export default ExperienceCardList;
