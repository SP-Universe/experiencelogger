<?php

namespace App\Api\ApiActions {

    use App\User\AuthToken;
    use SilverStripe\Control\HTTPRequest;

    class ApiAction_logoutUser
    {
        public static function logoutUser(HTTPRequest $request)
        {
            if (!$request->isPOST()) {
                $data['Success'] = false;
                $data['Error'] = 'No POST request';
            } else {
                $token = $request->postVar('Token');

                if (!$token) {
                    $data['Success'] = false;
                    $data['Error'] = 'No Token';
                    return json_encode($data);
                }

                $authToken = AuthToken::get()->filter(['Token' => $token])->first();

                if (!$authToken) {
                    $data['Success'] = false;
                    $data['Error'] = 'Token not found';
                    return json_encode($data);
                }

                $authToken->delete();

                $data['Success'] = true;
                $data['Message'] = 'Logged out succesfully!';

                $data['AuthToken'] = $authToken->Token;
            }

            return json_encode($data);
        }
    }
}
