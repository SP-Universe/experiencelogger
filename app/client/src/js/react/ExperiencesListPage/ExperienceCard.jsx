import React, { useState } from 'react';
import Distance from '../helpers/Distance';

function ExperienceCard(props) {
    var data = JSON.parse(props.jsondata);

    return (
        <div className="experience_card" data-behaviour="experiencecard">
            <a href={data.ExperienceLink} className="experience_entry">
                <div className="experience_entry_image">
                </div>
                <div className="experience_entry_content">
                    <h2 className="experience_title"> {data.Title} </h2>
                    <h4 className="experience_type" data-filter="" data-status="">{data.Type}</h4>
                    <p className="experience_state">{data.State}</p>
                    <Distance Coordinates={data.Coordinates} node={this}/>
                </div>
            </a>

            <div className="experience_logging">
                <a className="logging_link notnew" href={data.AddLogLink}>

                </a>
                <p className="logcount">0</p>
            </div>
        </div>
    )
}

export default ExperienceCard;
