import { useState } from "react";
import LocationsCardFilter from "./LocationsCardFilter";
import LocationsCardList from "./LocationsCardList";

const LocationsListPage = ( {userPos} ) => {

    const filterSettings = useState({
        "sortBy": "name",
        "searchTerm": "",
        "showTypes": "all"
    });

    return (
        <div className="section section--locationsoverview">
            <div className="section_content">
                <h1>Places</h1>
                <LocationsCardFilter filterSettings={filterSettings} />
                <LocationsCardList userPos={userPos} filterSettings={filterSettings} />
            </div>
        </div>

    );
};

export default LocationsListPage;
