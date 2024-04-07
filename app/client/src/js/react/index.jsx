import React from "react";
import ReactDOM from "react-dom/client";
import {
    createBrowserRouter,
    RouterProvider,
} from "react-router-dom";
import Root from "./routes/root";

import ErrorPage from "./error-page";
import DashboardPage from "./pages/DashboardPage.jsx";
import PlacesListPage from "./pages/PlacesListPage.jsx";
import TripsPage from "./pages/TripsPage.jsx";
import NewsPage from "./pages/NewsPage.jsx";
import SocialPage from "./pages/SocialPage.jsx";

const SiteTitle = "Home";

const router = createBrowserRouter([
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

ReactDOM.createRoot(document.getElementById("react-entry")).render(
    <React.StrictMode>
        <RouterProvider router={router} />
    </React.StrictMode>
);
