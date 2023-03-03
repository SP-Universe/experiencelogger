import React, { useState } from 'react';
import { useParams } from 'react-router-dom';
import ExperienceCardSidebar from './ExperienceCardSidebar';
import ExperienceCardFilter from './ExperienceCardFilter';
import ExperienceCardList from './ExperienceCardList';

const ExperiencesListPage = ( {userPos, baseurl} ) => {
    const { linkTitle, success } = useParams();

    console.log(success);

    const locationdata = JSON.parse(localStorage.getItem("locations"));
    console.log("locationdata", locationdata);

    return (
        <div className="section section--experiencesoverview">
            <div className="section_content">
                <ExperienceCardSidebar location={location}/>
                <div className="location_experiences">
                    <ExperienceCardFilter />
                    <ExperienceCardList userPos={userPos}/>
                </div>
            </div>
        </div>
    )
}

export default ExperiencesListPage;
