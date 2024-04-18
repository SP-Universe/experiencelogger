import React from "react";
import Header from "../molecules/Header";
import PlacesList from "../atoms/PlacesList";

class PlacesListPage extends React.Component {
    render() {
        return (
            <div className="page--placeslistpage">
                <Header SiteTitle="Places"/>
                <PlacesList />
            </div>
        );
    }
}

export default PlacesListPage;
