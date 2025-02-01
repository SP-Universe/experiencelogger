<?php

namespace App\Api\ApiActions {

    use App\User\AuthToken;

    use App\User\User;

    use SilverStripe\Security\Member;
    use App\ExperienceDatabase\UserAuthToken;
    use SilverStripe\Core\Injector\Injector;
    use SilverStripe\Security\MemberAuthenticator\MemberAuthenticator;
    use SilverStripe\Control\HTTPRequest;

    class ApiAction_loginUser
    {
        public static function loginUser(HTTPRequest $request)
        {
            if (!$request->isPOST()) {
                $data['Success'] = false;
                $data['Error'] = 'No POST request';
            } else {
                $username = $request->postVar('Username');
                $password = $request->postVar('Password');
                $deviceName = $request->postVar('DeviceName');

                if (!$username) {
                    $data['Success'] = false;
                    $data['Error'] = 'No Username';
                    return json_encode($data);
                } elseif (!$password) {
                    $data['Success'] = false;
                    $data['Error'] = 'No Password';
                    return json_encode($data);
                } elseif (!$deviceName) {
                    $data['Success'] = false;
                    $data['Error'] = 'No DeviceName';
                    return json_encode($data);
                }

                $hashedPassword = md5($password);
                $user = User::get()->filter(['Username' => $username])->first();

                if (!$user) {
                    $data['Success'] = false;
                    $data['Error'] = 'Username does not exist';
                    return json_encode($data);
                }
                if ($user->Password != $hashedPassword) {
                    $data['Success'] = false;
                    $data['Error'] = 'Password incorrect';
                    return json_encode($data);
                }

                //Check if deviceName is already in use
                $authToken = $user->AuthTokens()->filter(['DeviceName' => $deviceName])->first();
                if ($authToken) {
                    $data['Success'] = false;
                    $data['Error'] = 'DeviceName already in use';
                    return json_encode($data);
                }

                $data['Success'] = true;
                $data['Message'] = 'Logged in succesfully!';
                $data['Username'] = $username;
                $data['Avatar'] = $user->Avatar()->getAbsoluteURL();

                $authToken = AuthToken::create();
                $authToken->DeviceName = $deviceName;
                $authToken->ParentID = $user->ID;
                $authToken->LastLogin = date('Y-m-d H:i:s');
                $authToken->write();

                $user->AuthTokens()->add($authToken);
                $user->write();

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

                $data['AuthToken'] = $authToken->Token;
            }

            return json_encode($data);
        }
    }
}
