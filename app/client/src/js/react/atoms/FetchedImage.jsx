import React from "react";
import { useState, useEffect } from 'react';

const FetchedImage = ({imageID}) => {

    const [image, setImage] = useState([]);
    const parsedImageID = parseInt(imageID);

    useEffect(() => {
        //TODO: Change for correct server when released
        fetch('http://experiencelogger.test/app-api/image/' + parsedImageID)
            .then((res) => {
                return res.json();
            })
            .then((data) => {
                    setImage(data);
            });
    }, []);

    if(image.Link === undefined || image.Link === null || image.Link === "") {
        return (
            <div className="image_placeholder" />
        );
    } else {
        return (
            <img src={image.Link} alt={image.Title} loading="lazy" />
        );
    }
};

export default FetchedImage;
