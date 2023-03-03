import React from "react";
import { useState, useEffect } from "react";

function LocationsCardFilter( {filterSettings} ) {
    const [filterEnabled, setFilterEnabled] = useState(false);

    const toggleFilter = () => {
        setFilterEnabled(!filterEnabled);
    };

    return (
        <div className="component--filter" id="search-experience-bar">
            <input type="text" name="search" id="search-experience" placeholder="Search for a experience" />
            <a className="search_filtertoggle" onClick={toggleFilter}>
                <svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 48 48"><path fill="currentColor" d="M22 40q-.85 0-1.425-.575Q20 38.85 20 38V26L8.05 10.75q-.7-.85-.2-1.8Q8.35 8 9.4 8h29.2q1.05 0 1.55.95t-.2 1.8L28 26v12q0 .85-.575 1.425Q26.85 40 26 40Z"/></svg>
            </a>
            {filterEnabled ? (
                <div className="search_filters">
                    <select name="type" id="search-filter-type">
                        <option value="all">All Types</option>
                    </select>
                    <select name="sort" id="search-filter-sort">
                        <option value="name">Sort by Name</option>
                        <option value="type">Sort by Type</option>
                        <option value="type">Sort by Distance</option>
                    </select>
                </div>
            ) : null}
        </div>
    )
}

export default LocationsCardFilter;
