import React from "react";

const PlaceProgressBar = ({ placeID }) => {

    placeID = parseInt(placeID);

    return (
        <div className="progress_handler loading" data-behaviour="location_progress" data-locationid={placeID}>
            <p className="location_progress_text">Loading...</p>
            <div className="location_progress">
                <div className="location_progress_bar" style="width: 0%"></div>
            </div>
        </div>
    );
}

export default PlaceProgressBar;
