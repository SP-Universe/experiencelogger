import React from "react";
import { useState, useEffect } from "react";
import ExperienceCard from "./ExperienceCard.jsx";

function ExperienceCardList( {experienceCards, filterSettings, locationID, userPos, baseurl} ) {



    return (
        <div className="experience_list">
            {experienceCards}
        </div>
    )
}

export default ExperienceCardList;
