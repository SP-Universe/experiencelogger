<?php

namespace App\Api\ApiActions {

    use App\Ratings\Rating;
    use App\ExperienceDatabase\Experience;

    use App\ExperienceDatabase\LogEntry;

    use App\User\AuthToken;

    use App\User\User;
    use DateTime;
    use SilverStripe\Control\HTTPRequest;

    class ApiAction_addLog
    {
        public static function addLog(HTTPRequest $request)
        {
            if (!$request->isPOST()) {
                $data['Success'] = false;
                $data['Error'] = 'No POST request';
            } else {
                $username = $request->postVar('Username');
                $authToken = $request->postVar('Token');

                if (!$username) {
                    $data['Success'] = false;
                    $data['Error'] = 'No Username';
                    return json_encode($data);
                } elseif (!$authToken) {
                    $data['Success'] = false;
                    $data['Error'] = 'No Password';
                    return json_encode($data);
                }

                $user = User::get()->filter(['Username' => $username])->first();
                $token = AuthToken::get()->filter(['Token' => $authToken])->first();

                if (!$user) {
                    $data['Success'] = false;
                    $data['Error'] = 'Username does not exist';
                    return json_encode($data);
                }
                if (!$token) {
                    $data['Success'] = false;
                    $data['Error'] = 'Token incorrect';
                    return json_encode($data);
                }
                if ($token->Parent->ID != $user->ID) {
                    $data['Success'] = false;
                    $data['Error'] = 'Token does not belong to user';
                    return json_encode($data);
                }

                //ADD LOG ENTRY
                // Get Data from POST
                $weather = $request->postVar('Weather');
                $seat = $request->postVar('Seat');
                $row = $request->postVar('Row');
                $train = $request->postVar('Train');
                $wagon = $request->postVar('Wagon');
                $time = $request->postVar('Time');
                $date = $request->postVar('Date');
                $rating = $request->postVar('Rating');
                $notes = $request->postVar('Notes');
                $friends = $request->postVar('Friends');
                $experience = $request->postVar('ExperienceID');

                // Check if all required fields are set
                if (!$time || !$date || !$experience) {
                    $data['Success'] = false;
                    $data['Error'] = 'Not all required fields are set';
                    return json_encode($data);
                }

                // Check if Experience exists
                $experience = Experience::get()->filter(['ID' => $experience])->first();
                if (!$experience) {
                    $data['Success'] = false;
                    $data['Error'] = 'Experience does not exist';
                    return json_encode($data);
                }

                // Create the LogEntry but keep fields empty if not set
                $logEntry = new LogEntry();
                $logEntry->ExperienceID = $experience->ID;
                $logEntry->UserID = $user->ID;
                if ($weather) {
                    $logEntry->Weather = $weather;
                }
                if ($seat) {
                    $logEntry->Seat = $seat;
                }
                if ($row) {
                    $logEntry->Row = $row;
                }
                if ($train) {
                    $logEntry->Train = $train;
                }
                if ($wagon) {
                    $logEntry->Wagon = $wagon;
                }
                $datetime = new DateTime($date . ' ' . $time);

                //Check if datetime is in future
                if ($datetime > new DateTime()) {
                    $data['Success'] = false;
                    $data['Error'] = 'Date and Time are in the future';
                    return json_encode($data);
                }

                $logEntry->VisitTime = $datetime->format('Y-m-d H:i:s');

                if ($rating) {
                    if ($rating > 0) {
                        $newrating = Rating::create();
                        $newrating->ExperienceID = $experience->ID;
                        $newrating->UserID = $user->ID;
                        $newrating->Stars = $rating;
                        $newrating->LogEntries()->add($logEntry);
                        $logEntry->Votings()->add($newrating);
                        $totalratings = $experience->NumberOfRatings;
                        $experiencerating = $experience->Rating;
                        $calculatedrating = (($experiencerating * $totalratings) + $rating) / ($totalratings + 1);
                        $experience->Rating = $calculatedrating;
                        $experience->NumberOfRatings = $totalratings + 1;
                        $experience->write();
                        $newrating->write();
                    }
                }
                if ($notes) {
                    $logEntry->Notes = $notes;
                }
                if ($friends) {
                    $friends = explode(',', $friends);
                    $friends = User::get()->filter('Username', $friends);
                    $logEntry->Friends()->addMany($friends);
                }
                $logEntry->write();

                $data['Success'] = true;
                $data['Message'] = 'Log added succesfully!';
                $data['LogID'] = $logEntry->ID;

                $data['Log']['ID'] = $logEntry->ID;
                $data['Log']['ExperienceID'] = $logEntry->ExperienceID;
                $data['Log']['UserID'] = $logEntry->UserID;
                $data['Log']['DateTime'] = $logEntry->VisitTime;
                $data['Log']['Weather'] = $logEntry->Weather;
                $data['Log']['Seat'] = $logEntry->Seat;
                $data['Log']['Row'] = $logEntry->Row;
                $data['Log']['Train'] = $logEntry->Train;
                $data['Log']['Wagon'] = $logEntry->Wagon;
                $data['Log']['Rating'] = $logEntry->Rating;
                $data['Log']['Notes'] = $logEntry->Notes;
                if ($logEntry->Friends()->count() > 0) {
                    $data['Log']['Friends'] = $logEntry->Friends()->map(function ($friend) {
                        return [
                            "ID" => $friend->ID,
                            "Username" => $friend->Username,
                        ];
                    });
                } else {
                    $data['Log']['Friends'] = [];
                }

                $data['Token'] = $authToken;
            }

            return json_encode($data);
        }
    }
}
