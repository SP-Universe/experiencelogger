import React from 'react';
import { getMD5 } from './MD5';

const Gravatar = ( {size = 200, email, img} ) => {
        //Generate a Gravatar for the user
        const s = size; //Size in pixels (max 2048)
        const d = 'identicon'; //Default replacement for missing image
        const r = 'g'; //Rating
        email = "" + email;

        let url = 'https://www.gravatar.com/avatar/';
        url += getMD5(email.toLowerCase().trim());
        url += "?s=" + s + "&d=" + d + "&r=" + r;
        if (img) {
            return (
                <img src={url} alt={"Gravatar of " + email} />
            )
        }
        return url;
}

export default Gravatar;
