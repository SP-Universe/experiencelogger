import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import ExperienceCardSidebar from './ExperienceCardSidebar';
import ExperienceCardFilter from './ExperienceCardFilter';
import ExperienceCardList from './ExperienceCardList';
import ExperienceCard from './ExperienceCard';

const ExperiencesListPage = ( {userPos, baseurl} ) => {
    const { linkTitle, success } = useParams();

    const [filterSettings, setFilterSettings] = useState({
        "sortBy": "name",
        "searchTerm": "",
        "showTypes": "all"
    });

    const [experienceTypes, setExperienceTypes] = useState([]);
    const [experienceStates, setExperienceStates] = useState([]);
    const [experiences, setExperiences] = useState([]);
    const [cards, setCards] = useState([]);

    const locationdata = JSON.parse(localStorage.getItem("xpl-location__" + linkTitle));


    Object.keys(localStorage).forEach((key) => {
        if(key.startsWith("xpl-experience__")) {

            var data = JSON.parse(localStorage.getItem(key));
            if(data !== null && data.Parent !== null && data.Parent.id == locationdata.ID) {
                if(filterSettings.showTypes === "all" || data.ExperienceType == filterSettings.showTypes)
                {
                    //Collect all ExperienceTypes
                    if(experienceTypes.indexOf(data.ExperienceType) === -1) {
                        experienceTypes.push(data.ExperienceType);
                    }

                    //Collect all ExperienceStates
                    if(experienceStates.indexOf(data.ExperienceState) === -1) {
                        experienceStates.push(data.ExperienceState);
                    }

                    //Collect all Experiences
                    if (experiences.filter(e => e.linkTitle === data.LinkTitle).length === 0) {
                        experiences.push(
                            {
                                "linkTitle": data.LinkTitle,
                                "title": data.Title,
                                "type": data.ExperienceType,
                                "state": data.ExperienceState,
                            }
                        );
                    }
                }

            }
        }
    });

    useEffect(() => {
        //Sort Experiences by name
        experiences.sort((a, b) => (a.title > b.title) ? 1 : -1);

        //Sort Experiences by type
        if(filterSettings.sortBy === "type") {
            experiences.sort((a, b) => (a.type > b.type) ? 1 : -1);
        }

        //Sort Experiences by state
        if(filterSettings.sortBy === "state") {
            experiences.sort((a, b) => (a.state > b.state) ? 1 : -1);
        }

        //Filter Experiences by search term
        if(filterSettings.searchTerm !== "") {
            experiences = experiences.filter((experience) => {
                return experience.title.toLowerCase().includes(filterSettings.searchTerm.toLowerCase());
            });
        }

        //Filter Experiences by type
        if(filterSettings.showTypes !== "all") {
            experiences = experiences.filter((experience) => {
                return experience.type === filterSettings.showTypes;
            });
        }

        setCards(experiences.map((experience) => <ExperienceCard key={experience.linkTitle} linkTitle={experience.linkTitle} userPos={userPos} />));


    console.log(filterSettings.searchTerm);

    }, [filterSettings]);

    if(locationdata === null) {
        return (
            <div className="section section--experiencesoverview">
                <h1>Experiences in {linkTitle}</h1>
                <div className="section_content">
                    <p>Location not found</p>
                </div>
            </div>
        )
    }

    return (
        <div className="section section--experiencesoverview">
            <h1>Experiences in {locationdata.Title}</h1>
            <div className="section_content">
                <ExperienceCardSidebar location={locationdata}/>
                <div className="location_experiences">
                    <ExperienceCardFilter experienceTypes={experienceTypes} filterSettings={filterSettings}/>
                    <ExperienceCardList experienceCards={cards} filterSettings={filterSettings} locationID={locationdata.ID} userPos={userPos} baseurl={baseurl}/>
                </div>
            </div>
        </div>
    )
}

export default ExperiencesListPage;
