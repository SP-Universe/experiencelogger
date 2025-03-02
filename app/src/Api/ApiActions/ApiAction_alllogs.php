<?php

namespace App\Api\ApiActions {

    use App\User\AuthToken;

    use App\ExperienceDatabase\ExperienceLocation;
    use App\ExperienceDatabase\LogEntry;
    use SilverStripe\Control\HTTPRequest;
    use SilverStripe\Security\Member;

    class ApiAction_alllogs
    {
        public static function alllogs(HTTPRequest $request)
        {
            if (!$request->isPOST()) {
                $data['Success'] = false;
                $data['Error'] = 'No POST request';
                return json_encode($data);
            } else {
                $token = $request->postVar('Token');
                if (isset($token)) {
                    $authToken = AuthToken::get()->filter('Token', $token)->first();

                    if (!$authToken) {
                        $data['Success'] = false;
                        $data['Error'] = "Unknown Auth Token";
                        return json_encode($data);
                    }

                    $user = $authToken->Parent();

                    if (!$user) {
                        $data['Success'] = false;
                        $data['Error'] = "Unknown User";
                        return json_encode($data);
                    } else {
                        $data['Success'] = true;
                        $data['Logs'] = [];

                        $logs = LogEntry::get()->filter('LogUserID', $user->ID);
                        foreach ($logs as $log) {
                            $friends = $log->Friends();

                            $data['Logs'][count($data['Logs'])] = [
                                "ID" => $log->ID,
                                "VisitTime" => $log->VisitTime,
                                "Notes" => $log->Notes,
                                "Score" => $log->Score,
                                "Podest" => $log->Podest,
                                "Train" => $log->Train,
                                "Wagon" => $log->Wagon,
                                "Row" => $log->Row,
                                "Seat" => $log->Seat,
                                "Variant" => $log->Variant,
                                "Version" => $log->Version,
                                "IsLinkedLogged" => $log->IsLinkedLogged,
                                "Created" => $log->Created,
                                "ExperienceID" => $log->ExperienceID,
                                "FoodID" => $log->FoodID,
                                "Friends" => $friends->map(function ($friend) {
                                    return [
                                        "ID" => $friend->ID,
                                        "Nickname" => $friend->Nickname,
                                        "Avatar" => $friend->Avatar,
                                    ];
                                }),
                            ];
                        }

                        return json_encode($data);
                    }
                } else {
                    $data['Success'] = false;
                    $data['Error'] = "You need to provide your Auth Token to access this data";
                }
            }
        }
    }
}
