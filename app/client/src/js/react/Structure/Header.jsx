import React from "react"

const Header = () => {
    return (
        <header>
            <div class="header_nav">
                <div class="leftmenu">
                        <a class="backbutton" onclick="window.history.back();">
                            <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path fill="currentColor" d="M24 40 8 24 24 8l2.1 2.1-12.4 12.4H40v3H13.7l12.4 12.4Z"/></svg>
                        </a>
                </div>
                <a href="$Top.HomepageLink" class="nav_brand">
                    <img width="80px" height="80px" src="_resources/app/client/icons/ExperienceLogger-Symbol.svg" alt="Logo of Experience Logger"/>
                </a>
            </div>
        </header>
    )
}

export default Header;
