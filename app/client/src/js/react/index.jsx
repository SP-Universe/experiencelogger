import React from "react";
import ReactDOM from "react-dom/client";
import {
    createBrowserRouter,
    RouterProvider,
    HashRouter,
    createHashRouter,
    useParams
} from "react-router-dom";
import Root from "./routes/root";

import ErrorPage from "./error-page";
import DashboardPage from "./pages/DashboardPage.jsx";
import PlacesListPage from "./pages/PlacesListPage.jsx";
import TripsPage from "./pages/TripsPage.jsx";
import NewsPage from "./pages/NewsPage.jsx";
import SocialPage from "./pages/SocialPage.jsx";
import PlaceDetailPage from "./pages/PlaceDetailPage.jsx";

const SiteTitle = "Home";

let isLoggedIn = false;

const router = createHashRouter([
    {
        path: "/",
        element: <Root/>,
        errorElement: <ErrorPage/>,
        children: [
            {
                path: "/",
                element: <DashboardPage/>,
            },
            {
                path: "/trips",
                element: <TripsPage/>,
            },
            {
                path: "/places",
                element: <PlacesListPage/>,
            },
            {
                path: "/places/:placeTitle",
                element: <PlaceDetailPage />,
            },
            {
                path: "/news",
                element: <NewsPage/>,
            },
            {
                path: "/social",
                element: <SocialPage/>,
            },
        ],
    },
]);

const entryPoint = document.getElementById("react-entry");
if(entryPoint != null) {
    isLoggedIn = entryPoint.getAttribute("data-loggedin") === "true";
    if(isLoggedIn) {
        ReactDOM.createRoot(document.getElementById("react-entry")).render(
            <React.StrictMode>
                <RouterProvider router={router} />
            </React.StrictMode>
        );
    } else {
        window.location.href = "./home/login";
    }
}
