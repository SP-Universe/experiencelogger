<?php

namespace App\Api\ApiActions {

    use App\ExperienceDatabase\Experience;
    use SilverStripe\Control\HTTPRequest;

    class ApiAction_allexperiences
    {
        public static function allexperiences(HTTPRequest $request)
        {
            $experiences = Experience::get()->sort('Date', 'DESC');
            $data = [];

            $groupedExperiences = [];

            //Add each news to the json data array
            foreach ($experiences as $experience) {
                if ($experience->Date > date("Y-m-d H:i:s")) {
                    continue;
                }

                $groupedExperiences[$experience->ID]['ID'] = $experience->ID;
                $groupedExperiences[$experience->ID]['Title'] = $experience->Title;
            }

            foreach ($groupedExperiences as $experience) {
                $data[] = $experience;
            }

            return json_encode($data);
        }
    }
}
