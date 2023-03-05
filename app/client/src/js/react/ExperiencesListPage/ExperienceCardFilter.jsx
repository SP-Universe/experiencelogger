import React from "react";
import { useState, useEffect } from "react";
import ExperienceCard from "./ExperienceCard.jsx";

const ExperienceCardFilter = ( {experienceStates, experienceTypes, filterSettings, setFilterSettings} ) => {
    const [filterEnabled, setFilterEnabled] = useState(false);

    const toggleFilter = () => {
        setFilterEnabled(!filterEnabled);
    };

    const changeSearchTerm = (e) => {
        setFilterSettings({
            ...filterSettings,
            searchTerm: e.target.value
        });
    };

    const changeSortBy = (e) => {
        setFilterSettings({
            ...filterSettings,
            sortBy: e.target.value
        });
    };

    const changeShowTypes = (e) => {
        setFilterSettings({
            ...filterSettings,
            showTypes: e.target.value
        });
    };

    const changeShowStates = (e) => {
        setFilterSettings({
            ...filterSettings,
            showStates: e.target.value
        });
    };

    const changeOrder = () => {
        setFilterSettings({
            ...filterSettings,
            isAsc: !filterSettings.isAsc
        });
    };

    const getTypeEntries = () => {
        const entries = [];
        entries.push(<option value="all" key="all">All Types</option>);
        experienceTypes.forEach((type) => {
            entries.push(<option value={type} key={type}>{type}</option>);
        });
        return entries;
    }

    const getStateEntries = () => {
        const states = [];
        states.push(<option value="all" key="all">All States</option>);
        experienceStates.forEach((state) => {
            states.push(<option value={state} key={state}>{state}</option>);
        });
        return states;
    }

    return (
        <div className="component--filter" id="search-experience-bar">
            <input type="text" name="search" id="search-experience" placeholder="Search for a experience" onChange={changeSearchTerm}/>
            <a className="search_filtertoggle" onClick={toggleFilter}>
                <svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 48 48"><path fill="currentColor" d="M22 40q-.85 0-1.425-.575Q20 38.85 20 38V26L8.05 10.75q-.7-.85-.2-1.8Q8.35 8 9.4 8h29.2q1.05 0 1.55.95t-.2 1.8L28 26v12q0 .85-.575 1.425Q26.85 40 26 40Z"/></svg>
            </a>
            {filterEnabled ? (
                <div className="search_filters">
                    <select name="type" onChange={changeShowTypes}>
                        {getTypeEntries()}
                    </select>
                    <select name="state" onChange={changeShowStates}>
                        {getStateEntries()}
                    </select>
                    <select name="sort" onChange={changeSortBy}>
                        <option value="name">Sort by Name</option>
                        <option value="state">Sort by State</option>
                        <option value="type">Sort by Type</option>
                        <option value="distance">Sort by Distance</option>
                    </select>
                    <button name="changeOrder" onClick={changeOrder}>
                        <svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 48 48"><path fill="currentColor" d="M22 40q-.85 0-1.425-.575Q20 38.85 20 38V26L8.05 10.75q-.7-.85-.2-1.8Q8.35 8 9.4 8h29.2q1.05 0 1.55.95t-.2 1.8L28 26v12q0 .85-.575 1.425Q26.85 40 26 40Z"/></svg>
                    </button>
                </div>
            ) : null}
        </div>
    )
}

export default ExperienceCardFilter;
