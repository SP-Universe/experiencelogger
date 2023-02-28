console.log("jsonloader loaded");

import React from 'react';
import ReactDOM from 'react-dom/client';
import ExperienceCard from './react/ExperienceCard.jsx';
import ExperienceCardList from './react/ExperienceCardList.jsx';
import ExperienceCardFilter from './react/ExperienceCardFilter.jsx';
import ExperienceCardSidebar from './react/ExperienceCardSidebar.jsx';

const innerpage = document.getElementById('innerpage');
const root = ReactDOM.createRoot(innerpage);

window.loadOnlineData = function loadOnlineData() {
    console.log("User is online. Reading from server");

    //Load Experiencecard
    let experiencedata = document.querySelectorAll('[data-behaviour="experiencedata"]');

    if(experiencedata.length){
        experiencedata.forEach(experiencedata => {
            if(experiencedata.textContent) {
                var data = JSON.parse(experiencedata.textContent);

                localStorage.setItem("experience_" + data["Title"], experiencedata.textContent);

                experiencedata.remove();
            }
        });

        root.render(
            <div className="section section--experiencesoverview">
                <div className="section_content">
                    <div className="location_sidebar">
                        <ExperienceCardSidebar />
                    </div>
                    <div className="location_experiences">
                        <ExperienceCardFilter />
                        <ExperienceCardList />
                    </div>
                </div>
            </div>
        );
    }
}

window.loadOfflineData = function loadOfflineData() {
    console.log("User is offline. Reading from local storage");

    root.render(
        Object.keys(localStorage).forEach(function(key){
            data = localStorage.getItem(key);
            <ExperienceCard json={data}/>
        })
    );
}
