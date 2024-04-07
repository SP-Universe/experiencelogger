import React from "react";
import Header from "../molecules/Header";
import PlacesList from "../atoms/PlacesList";

class PlacesListPage extends React.Component {
    render() {
        return (
            <div>
                <Header SiteTitle="Places"/>
                <p>Coming soon...</p>
                <p>Test2</p>
                <PlacesList />
            </div>
        );
    }
}

export default PlacesListPage;
