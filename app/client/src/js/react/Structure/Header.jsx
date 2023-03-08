import React from "react"
import Gravatar from "../helpers/Gravatar";

const Header = () => {

    const email = "test@test.de";
    const asImage = true;

    return (
        <header>
            <div className="header_nav">
                <div className="leftmenu">
                        <a className="backbutton" onClick={window.history.back}>
                            <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path fill="currentColor" d="M24 40 8 24 24 8l2.1 2.1-12.4 12.4H40v3H13.7l12.4 12.4Z"/></svg>
                        </a>
                </div>
                <a href="$Top.HomepageLink" className="nav_brand">
                    <img width="80px" height="80px" src="_resources/app/client/icons/ExperienceLogger-Symbol.svg" alt="Logo of Experience Logger"/>
                </a>
                <div className="userimage" data-behaviour="open_personalnav">
                    <div className="avatar_image">
                        <Gravatar email={email} img={asImage} />
                    </div>
                </div>
            </div>
        </header>
    )
}

export default Header;
