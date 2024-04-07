import React, { Children } from "react";
import { Outlet } from "react-router-dom";

class Page extends React.Component {
    render() {
        return (
            <div className="page">
                <Outlet />
            </div>
        );
    }
}

export default Page;
