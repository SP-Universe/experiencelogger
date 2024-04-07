import React from "react";
import {
    Outlet,
    Link,
    useLoaderData,
    Form,
} from "react-router-dom";
import { useState } from "react";

import Header from "../molecules/Header";
import { Children } from "react";
import Page from "../components/Page";
import Navigation from "../molecules/Navigation";

class Root extends React.Component {
    render() {
        return (
            <div>
                <Page>
                    <Outlet />
                </Page>
                <Navigation />
                <div className="footer" />
            </div>
        );
    }
}

export default Root;
