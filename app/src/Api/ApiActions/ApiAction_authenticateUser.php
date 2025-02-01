<?php

namespace App\Api\ApiActions {

    use App\User\AuthToken;

    use App\Api\Helper\ApiUserDataHelper;
    use App\ExperienceDatabase\UserAuthToken;
    use SilverStripe\Control\HTTPRequest;

    class ApiAction_authenticateUser
    {
        public static function authenticateUser(HTTPRequest $request)
        {
            if (!$request->isPOST()) {
                $data['Success'] = false;
                $data['Error'] = 'No POST request';
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
                        $data['Username'] = $user->Username;
                        $data['Nickname'] = $user->Nickname;
                        $data['Avatar'] = $user->Avatar()->getAbsoluteURL();
                        $data['Success'] = true;
                        $data['HasPremium'] = $user->HasPremium ? true : false;
                        $data['LinkedLogging'] = $user->LinkedLogging ? true : false;

                        $allauthtokens = $user->AuthTokens();
                        $data['AuthTokens'] = [];
                        foreach ($allauthtokens as $otherauthtoken) {
                            if (is_object($otherauthtoken)) {
                                $data['AuthTokens'][] = [
                                    'DeviceName' => $otherauthtoken->DeviceName,
                                    'LastLogin' => $otherauthtoken->LastLogin,
                                    'CurrentlyActive' => $otherauthtoken->ID == $authToken->ID,
                                ];
                            }
                        }

                        $authToken->updateLastLogin();
                    }
                } else {
                    $data['Success'] = false;
                    $data['Error'] = "You need to provide your Auth Token to access this data";
                }
            }

            return json_encode($data);
        }
    }
}
