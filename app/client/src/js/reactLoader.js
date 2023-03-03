console.log("jsonloader loaded");

import React, { useState } from 'react';
import ReactDOM from 'react-dom/client';
import App from './react/App.jsx';

const innerpage = document.getElementById('innerpage');
const baseurl = innerpage.getAttribute('data-baseurl');

const root = ReactDOM.createRoot(innerpage);

window.loadOnlineData = function loadOnlineData() {
    console.log("User is online. Reading from server");

    const isOnline = true;

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
    }

    root.render(
        <App isOnline={isOnline} baseurl={baseurl}/>
    );
}


window.loadOfflineData = function loadOfflineData() {
    console.log("User is offline. Reading from local storage");

    const isOnline = false;

    root.render(
        <App isOnline={isOnline} baseurl={baseurl}/>
    );
}
