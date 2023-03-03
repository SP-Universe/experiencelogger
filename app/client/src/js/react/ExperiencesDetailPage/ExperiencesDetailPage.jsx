import React, { useState, useEffect } from 'react';

const ExperiencesDetailPage = ( {userPos, baseurl} ) => {

    const experiencesdata = JSON.parse(localStorage.getItem("experiences"));

    return (
        <div className="section section--experiencesdetails">
            <div className="section_content">
                <h1>Details to be added...</h1>
            </div>
        </div>
    )
}

export default ExperiencesDetailPage;
