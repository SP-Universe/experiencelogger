import React from "react";
import { useState, useEffect } from "react";
import ReactDOM from "react-dom/client";
import {
    createBrowserRouter,
    RouterProvider,
  } from "react-router-dom";
import HomePage from "./HomePage/HomePage";



const App = ( {baseurl} ) => {

    console.log(baseurl);
    console.log({baseurl} + "places/");
    console.log(baseurl + "placesB/");

    const router = createBrowserRouter([
        {
          path: baseurl,
          element: <HomePage />,
        },
        {
            path: baseurl + "places/",
            element: <HomePage />,
        },
    ]);

    return (
        <React.StrictMode>
            <RouterProvider router={router} />
        </React.StrictMode>
    );
}

export default App;
