import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import ExperienceCardSidebar from './ExperienceCardSidebar';
import ExperienceCardFilter from './ExperienceCardFilter';
import ExperienceCardList from './ExperienceCardList';
import ExperienceCard from './ExperienceCard';
import Distance, { getDistance } from '../helpers/Distance';

const ExperiencesListPage = ( {userPos, baseurl} ) => {
    const { linkTitle, success } = useParams();

    const [filterSettings, setFilterSettings] = useState({
        "sortBy": "name",
        "searchTerm": "",
        "showTypes": "all",
        "showStates": "all",
        "isAsc": "true"
    });

    const [experienceTypes, setExperienceTypes] = useState([]);
    const [experienceStates, setExperienceStates] = useState([]);
    const [experiences, setExperiences] = useState([]);
    const [cards, setCards] = useState([]);

    const locationdata = JSON.parse(localStorage.getItem("xpl-location__" + linkTitle));


    ///Gets all Experiences from localStorage and filters them
    const getDataFromStorage = (userPos) => {
        Object.keys(localStorage).forEach((key) => {
            if(key.startsWith("xpl-experience__")) {

                var data = JSON.parse(localStorage.getItem(key));
                if(data !== null && data.Parent !== null && data.Parent.id == locationdata.ID) {
                    if(filterSettings.showTypes === "all" || data.ExperienceType == filterSettings.showTypes)
                    {
                        //Collect all ExperienceTypes
                        if(data.ExperienceType) {
                            if(experienceTypes.filter(e => e === data.ExperienceType).length === 0) {
                                experienceTypes.push(data.ExperienceType);
                            }
                        }

                        //Collect all ExperienceStates
                        if(experienceStates.filter(e => e === data.State).length === 0) {
                            experienceStates.push(data.State);
                        }

                        //Collect all Experiences
                        if (experiences.filter(e => e.linkTitle === data.LinkTitle).length === 0) {
                            experiences.push(
                                {
                                    "linkTitle": data.LinkTitle,
                                    "title": data.Title,
                                    "type": data.ExperienceType,
                                    "state": data.State,
                                    "distance": getDistance(userPos, data.Coordinates)
                                }
                            );
                        }
                    }

                }
            }
        });
    }

    useEffect(() => {

        if(userPos !== "") {
            getDataFromStorage(userPos);
        }

        //Sort Experiences
        if(filterSettings.sortBy === "name")
            experiences.sort((a, b) => {
                if(!a.title) {
                    return 1;
                } else if(!b.title) {
                    return -1;
                } else {
                    return (a.title > b.title) ? filterSettings.isAsc ? 1 : -1 : !filterSettings.isAsc ? 1 : -1
                }
            });
        else if(filterSettings.sortBy === "type")
        experiences.sort((a, b) => {
            if(!a.type) {
                return 1;
            } else if(!b.type) {
                return -1;
            } else {
                return (a.type > b.type) ? filterSettings.isAsc ? 1 : -1 : !filterSettings.isAsc ? 1 : -1
            }
        });
        else if(filterSettings.sortBy === "state")
            experiences.sort((a, b) => {
                if(!a.state) {
                    return 1;
                } else if(!b.state) {
                    return -1;
                } else {
                    return (a.state > b.state) ? filterSettings.isAsc ? 1 : -1 : !filterSettings.isAsc ? 1 : -1
                }
            });
        else if(filterSettings.sortBy === "distance") {
            experiences.sort((a, b) => {
                if(!a.distance){
                    return 1;
                } else if(!b.distance) {
                    return -1;
                } else {
                    return (a.distance > b.distance) ? filterSettings.isAsc ? 1 : -1 : !filterSettings.isAsc ? 1 : -1;
                }
            });
        }

        //Filter Experiences
        const filteredExperiences = experiences.filter((experience) => {
            if(filterSettings.searchTerm === "") {
                return true;
            } else {
                return experience.title.toLowerCase().includes(filterSettings.searchTerm.toLowerCase());
            }
        }).filter((experience) => {
            if(filterSettings.showTypes === "all") {
                return true;
            } else {
                return experience.type === filterSettings.showTypes;
            }
        }).filter((experience) => {
            if(filterSettings.showStates === "all") {
                return true;
            } else {
                return experience.state === filterSettings.showStates;
            }
        });

        setCards(filteredExperiences.map((experience) => <ExperienceCard key={experience.linkTitle} baseurl={baseurl} experience={experience} />));

    }, [userPos, filterSettings]);

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
            <div className="section_content">
                <ExperienceCardSidebar experiences={experiences} locationdata={locationdata}/>
                <div className="location_experiences">
                    <ExperienceCardFilter experienceStates={experienceStates} experienceTypes={experienceTypes} filterSettings={filterSettings} setFilterSettings={setFilterSettings}/>
                    <ExperienceCardList experienceCards={cards} filterSettings={filterSettings} locationID={locationdata.ID} userPos={userPos} baseurl={baseurl}/>
                </div>
            </div>
        </div>
    )
}

export default ExperiencesListPage;
