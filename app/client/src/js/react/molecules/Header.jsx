import React from "react";

class Header extends React.Component {
    render() {
        return (
            <header>
                <a className="header_backbutton" onClick={() => window.history.back()}>
                    <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path fill="currentColor" d="M24 40 8 24 24 8l2.1 2.1-12.4 12.4H40v3H13.7l12.4 12.4Z"/></svg>
                </a>
                <div className="header_title">
                    <p className="header_title_content">{this.props.SiteTitle}</p>
                </div>
                <div className="header_avatar">
                    <img src="https://via.placeholder.com/150x150" alt="Avatar of $CurrentUser.Nickname" />
                </div>
            </header>
        );
    }
}

export default Header;
