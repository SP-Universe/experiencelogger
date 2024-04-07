import React from "react";
import { Link } from "react-router-dom";
import HomeNavigationIcon from "../icons/home.svg";

class Navigation extends React.Component {
    render() {
        return(
            <nav>
                <ul>
                    <li>
                        <Link to="./">
                            <svg width="100%" height="100%" viewBox="0 0 48 48" version="1.1">
                                <path fill="currentColor" d="M8,42L8,18L24.1,6L40,18L40,42L28.3,42L28.3,27.75L19.65,27.75L19.65,42L8,42Z"/>
                            </svg>
                            <p>Home</p>
                        </Link>
                    </li>
                    <li>
                        <Link to="./trips">Trips</Link>
                    </li>
                    <li>
                        <Link to="./places">Places</Link>
                    </li>
                    <li>
                        <Link to="./news">News</Link>
                    </li>
                    <li>
                        <Link to="./social">Social</Link>
                    </li>
                </ul>
            </nav>
        );
    }
}

export default Navigation;
