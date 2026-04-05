<?php

namespace App\Api\ApiActions {

    use App\ExperienceDatabase\Experience;
    use App\Ratings\Rating;
    use App\User\AuthToken;
    use SilverStripe\Control\HTTPRequest;

    class ApiAction_ratings
    {
        public static function ratings(HTTPRequest $request)
        {
            $token = $request->postVar('Token') ?? $request->getVar('token') ?? $request->getVar('Token');
            if (!$token) {
                $data['Success'] = false;
                $data['Error'] = 'No Token provided';
                return json_encode($data);
            }

            $authToken = AuthToken::get()->filter('Token', $token)->first();
            if (!$authToken) {
                $data['Success'] = false;
                $data['Error'] = 'Unknown Auth Token';
                return json_encode($data);
            }

            $requestingUser = $authToken->Parent();
            if (!$requestingUser) {
                $data['Success'] = false;
                $data['Error'] = 'Unknown User';
                return json_encode($data);
            }

            // Filter by ExperienceID if provided
            $experienceID = $request->postVar('ExperienceID') ?? $request->getVar('ExperienceID') ?? $request->getVar('experienceID');
            $filter = ['UserID' => $requestingUser->ID];

            if ($experienceID) {
                $experience = Experience::get()->byID((int) $experienceID);
                if (!$experience) {
                    $data['Success'] = false;
                    $data['Error'] = 'Experience not found';
                    return json_encode($data);
                }
                $filter['ExperienceID'] = $experience->ID;
            }

            $ratings = Rating::get()->filter($filter);

            $items = [];
            foreach ($ratings as $rating) {
                $items[] = [
                    'ID'           => $rating->ID,
                    'Stars'        => $rating->Stars,
                    'Text'         => $rating->Text,
                    'UserID'       => $rating->UserID,
                    'ExperienceID' => $rating->ExperienceID,
                    'Created'      => $rating->Created,
                ];
            }

            $data['Success'] = true;
            $data['UserID']  = $requestingUser->ID;
            $data['Count']   = count($items);
            $data['Ratings'] = $items;

            return json_encode($data);
        }
    }
}
