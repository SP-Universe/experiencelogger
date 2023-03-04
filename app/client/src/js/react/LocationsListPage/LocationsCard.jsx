import React from "react";
import { useState, useEffect } from "react";
import ReactDOM from "react-dom/client";
import Distance from "../helpers/Distance";

const LocationsCard = ( {userPos, location, filterSettings} ) => {

    const data = JSON.parse(location);

    if(filterSettings.type === "all" || filterSettings.type === data.Type) {
        return (
            <div className="location_entry_wrap">
                <a href={"./places/" + data.LinkTitle} className="location_entry">
                    <div className="location_entry_image"></div>
                    <div className="location_entry_content">
                        <h2 className="location_title">{data.Title}</h2>
                        <h3>{data.Type}</h3>
                        <p>{data.Address}</p>
                        <p>{data.Experiences.Count} Experiences </p>
                        <Distance userPos={userPos} Coordinates={data.Coordinates} />
                    </div>
                </a>
            </div>
        )
    }
}

export default LocationsCard;
