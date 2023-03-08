import React from "react";
import { useState, useEffect } from "react";
import ReactDOM from "react-dom/client";
import {
    createBrowserRouter,
    RouterProvider,
  } from "react-router-dom";
import ExperiencesListPage from "./ExperiencesListPage/ExperiencesListPage";
import HomePage from "./HomePage/HomePage";
import LocationsListPage from "./LocationsListPage/LocationsListPage";
import ExperiencesDetailPage from "./ExperiencesDetailPage/ExperiencesDetailPage";
import Header from "./Structure/Header";
import Footer from "./Structure/Footer";

const App = ( {isOnline, baseurl} ) => {

    const [locations, setLocations] = useState("");
    const [experiences, setExperiences] = useState("");
    const [logs, setLogs] = useState("");
    const [userPos, setUserPos] = useState("");
    const activeLink = window.location.pathname.split("/")[window.location.pathname.split("/").length - 1];

    const updateData = () => {
        fetch(baseurl + "api/v1/App-ExperienceDatabase-ExperienceLocation.json")
        .then((response) => response.json())
        .then((data) => {

            const locations = data.items;

            locations.forEach((location) => {
                localStorage.setItem("xpl-location__" + location.LinkTitle, JSON.stringify(location));
            });
            console.log("Locations updated", locations);
        });

        fetch(baseurl + "api/v1/App-ExperienceDatabase-Experience.json")
            .then((response) => response.json())
            .then((data) => {
                const experiences = data.items;

                experiences.forEach((experience) => {
                    localStorage.setItem("xpl-experience__" + experience.LinkTitle, JSON.stringify(experience));
                });
                console.log("Experiences updated", experiences);
        });
        localStorage.setItem("data-updated", Date.now());
    };

    const updateLogs = () => {
        fetch(baseurl + "api/v1/App-ExperienceLogging-LogEntry.json")
            .then((response) => response.json())
            .then((data) => {

                setLogs(data.items);

                locations.forEach((location) => {
                    localStorage.setItem("xpl-location__" + location.LinkTitle, JSON.stringify(location));
                });
                console.log("Locations updated", locations);
            });
    }

    useEffect(() => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const Lat = position.coords.latitude;
                const Lon = position.coords.longitude;

                setUserPos(
                    {
                        Lat,
                        Lon
                    }
                );
                console.log("User Position set: " + position.coords.latitude + ", " + position.coords.longitude);
            });
        }

        if(isOnline) {
            updateLogs();
            //If the user is online, check if the data is up to date
            if (localStorage.getItem("data-updated") == null || localStorage.getItem("data-updated") < Date.now() - 360000) {
                updateData();
            } else {
                console.log("Data is up to date");
            }
        }

    }, [baseurl]);

    const router = createBrowserRouter([
        {
          path: baseurl,
          element: <HomePage />,
        },
        {
            path: baseurl + "places/",
            element: <LocationsListPage userPos={userPos}/>,
        },
        {
            path: baseurl + "places/:linkTitle",
            element: <ExperiencesListPage userPos={userPos} baseurl={baseurl}/>,
        },
        {
            path: baseurl + "experience/:linkTitle",
            element: <ExperiencesDetailPage userPos={userPos} baseurl={baseurl}/>,
        },
    ]);

    return (
        <html lang="en">
            <body>
                <Header baseurl={baseurl} />
                <React.StrictMode>
                    <RouterProvider router={router} />
                </React.StrictMode>
                <Footer baseurl={baseurl} activeLink={activeLink} />
            </body>
        </html>
    );
}

export default App;
