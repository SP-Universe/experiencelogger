<?php

namespace App\Api\ApiActions {

    use App\ExperienceDatabase\ExperienceLocation;
    use SilverStripe\Control\HTTPRequest;

    class ApiAction_allplaces
    {
        public static function allplaces(HTTPRequest $request)
        {
            $places = ExperienceLocation::get()->sort('ID ASC');
            $data = [];

            $groupedPlaces = [];

            //Add each news to the json data array
            foreach ($places as $place) {
                $groupedPlaces[$place->ID]['ID'] = $place->ID;
                $groupedPlaces[$place->ID]['Title'] = $place->Title;
                if ($place->Description != null) {
                    $description = strip_tags($place->Description);
                    $description = str_replace("\n", "", $description);
                    $description = str_replace("\r", "", $description);
                    $description = str_replace("&amp;", "&", $description);
                    $description = str_replace("&nbsp;", " ", $description);
                    $description = str_replace("&quot;", "\"", $description);
                    $description = str_replace("&#039;", "'", $description);
                    $description = str_replace("&#39;", "'", $description);
                    $groupedPlaces[$place->ID]['Description'] = $description;
                }
                if ($place->Image && $place->Image()->exists()) {
                    $groupedPlaces[$place->ID]['ImageLink'] = $place->Image()->Link();
                }
                $groupedPlaces[$place->ID]['Type'] = $place->Type()->Title;
                $groupedPlaces[$place->ID]['TypeID'] = $place->TypeID;
                if ($place->Coordinates != null && $place->Coordinates != "") {
                    $coordinates = $place->Coordinates;
                    $coordinates = explode(", ", $coordinates);
                    $lat = $coordinates[0];
                    $lon = $coordinates[1] ?? 0.0;
                    $groupedPlaces[$place->ID]['Latitude'] = $lat;
                    $groupedPlaces[$place->ID]['Longitude'] = $lon;
                }
                $groupedPlaces[$place->ID]['LastEdited'] = $place->LastEdited;
                $groupedPlaces[$place->ID]['Created'] = $place->Created;
                $groupedPlaces[$place->ID]['Link'] = $place->Link;
                $groupedPlaces[$place->ID]['ExperienceCount'] = $place->Experiences()->count();
                $groupedPlaces[$place->ID]['ExperienceIDs'] = $place->Experiences()->column('ID');
                if ($place->Address != null) {
                    $groupedPlaces[$place->ID]['Address'] = $place->Address;
                }
                if ($place->OpeningDate != null) {
                    $groupedPlaces[$place->ID]['OpeningDate'] = $place->OpeningDate;
                }
                if ($place->Website != null) {
                    $groupedPlaces[$place->ID]['Website'] = $place->Website;
                }
                if ($place->Phone != null) {
                    $groupedPlaces[$place->ID]['Phone'] = $place->Phone;
                }
                if ($place->Email != null) {
                    $groupedPlaces[$place->ID]['Email'] = $place->Email;
                }
            }

            foreach ($groupedPlaces as $place) {
                $data[] = $place;
            }

            return json_encode($data);
        }
    }
}
