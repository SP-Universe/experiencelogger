<?php

namespace App\Api\ApiActions {

    use App\ExperienceDatabase\Experience;
    use SilverStripe\Control\HTTPRequest;

    class ApiAction_allexperiences
    {
        public static function allexperiences(HTTPRequest $request)
        {
            $experiences = Experience::get()->sort('ID ASC');
            $data = [];

            $groupedExperiences = [];

            //Add each news to the json data array
            foreach ($experiences as $experience) {

                $groupedExperiences[$experience->ID]['ID'] = $experience->ID;
                $groupedExperiences[$experience->ID]['Title'] = $experience->Title;
                if ($experience->Description != null) {
                    $description = strip_tags($experience->Description);
                    $description = str_replace("\n", "", $description);
                    $description = str_replace("\r", "", $description);
                    $description = str_replace("&amp;", "&", $description);
                    $description = str_replace("&nbsp;", " ", $description);
                    $description = str_replace("&quot;", "\"", $description);
                    $description = str_replace("&#039;", "'", $description);
                    $description = str_replace("&#39;", "'", $description);
                    $groupedExperiences[$experience->ID]['Description'] = $description;
                }
                if ($experience->PhotoGalleryImages()->count() > 0) {
                    $groupedExperiences[$experience->ID]['ImageLink'] = $experience->PhotoGalleryImages()->first()->Image()->Link();
                }
                $groupedExperiences[$experience->ID]['LocationTitle'] = $experience->Parent()->Title;
                $groupedExperiences[$experience->ID]['LocationId'] = $experience->ParentID;
                $groupedExperiences[$experience->ID]['Type'] = $experience->Type()->Title;
                $groupedExperiences[$experience->ID]['TypeID'] = $experience->TypeID;
                if ($experience->AreaID != 0) {
                    $groupedExperiences[$experience->ID]['Area'] = $experience->Area()->Title;
                    $groupedExperiences[$experience->ID]['AreaID'] = $experience->AreaID;
                }
                $groupedExperiences[$experience->ID]['State'] = $experience->State;

                if ($experience->Coordinates != null && $experience->Coordinates != "") {
                    $coordinates = $experience->Coordinates;
                    $coordinates = explode(", ", $coordinates);
                    $lat = $coordinates[0];
                    $lon = $coordinates[1] ?? 0.0;
                    $groupedExperiences[$experience->ID]['Latitude'] = $lat;
                    $groupedExperiences[$experience->ID]['Longitude'] = $lon;
                }

                $groupedExperiences[$experience->ID]['LastEdited'] = $experience->LastEdited;
                $groupedExperiences[$experience->ID]['Created'] = $experience->Created;
            }

            foreach ($groupedExperiences as $experience) {
                $data[] = $experience;
            }

            return json_encode($data);
        }
    }
}
