import React, { useState } from 'react';
import { useParams } from 'react-router-dom';
import ExperienceCardSidebar from './ExperienceCardSidebar';
import ExperienceCardFilter from './ExperienceCardFilter';
import ExperienceCardList from './ExperienceCardList';

const ExperiencesListPage = ( {baseurl} ) => {

    const [location, setLocation] = React.useState(null);
    const { linkTitle } = useParams();

    React.useEffect(() => {
        fetch(baseurl + "api/v1/App-ExperienceDatabase-ExperienceLocation?LinkTitle=" + linkTitle)
            .then((response) => response.json())
            .then((data) => {
                setLocation(data);
            });
    }, [linkTitle]);

    console.log(location);

    return (
        <div className="section section--experiencesoverview">
            <div className="section_content">
                <ExperienceCardSidebar title={linkTitle}/>
                <div className="location_experiences">
                    <ExperienceCardFilter />
                    <ExperienceCardList />
                </div>
            </div>
        </div>
    )
}

export default ExperiencesListPage;
