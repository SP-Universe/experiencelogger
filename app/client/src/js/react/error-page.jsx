import { useRouteError } from "react-router-dom";
import Page from "./components/Page";
import Header from "./molecules/Header";
import Navigation from "./molecules/Navigation";

export default function ErrorPage() {
    const error = useRouteError();
    console.error(error);

    return (
        <div className="error-page">
            <Header />
            <h1>Oops!</h1>
            <p>Sorry, an unexpected error has occurred.</p>
            <p>
                <i>{error.statusText || error.message}</i>
            </p>
            <Navigation />
        </div>
    );
}
