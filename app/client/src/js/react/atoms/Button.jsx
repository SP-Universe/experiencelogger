import React from "react";
import { Link } from "react-router-dom";

class Button extends React.Component {
    render() {
        return (
            <Link to={this.props.to} className="button">{this.props.text}</Link>
        );
    }
}

export default Button;
