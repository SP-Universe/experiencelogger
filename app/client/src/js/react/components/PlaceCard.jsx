import React from "react";
import { Link } from "react-router-dom";
import FetchedImage from "../atoms/FetchedImage";

const PlaceCard = ({ place }) => {

    return (
        <div className='place_card' key={place.id}>
            <Link to={`./${place.LocationTitle}`} className='place_entry'>
                <div className="place_entry_image">
                    <FetchedImage imageID={place.LocationImageID} />
                </div>
                <div className="place_entry_content">
                    <h2 className="place_title">{place.LocationTitle}</h2>
                    <h3 className="place_type">{place.LocationTypeTitle}</h3>
                </div>
            </Link>
        </div>
    );
}

export default PlaceCard;
