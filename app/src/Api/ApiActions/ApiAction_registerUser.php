<?php

namespace App\Api\ApiActions {

    use App\User\User;
    use App\User\AuthToken;

    use SilverStripe\Control\HTTPRequest;

    class ApiAction_registerUser
    {
        public static function registerUser(HTTPRequest $request)
        {
            if (!$request->isPOST()) {
                $data['Success'] = false;
                $data['Error'] = 'No POST request';
            } else {
                $username = $request->postVar('Username');
                $password = $request->postVar('Password');
                $deviceName = $request->postVar('DeviceName');
                $emailadress = $request->postVar('EmailAdress');
                $dateOfBirth = $request->postVar('DateOfBirth');

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
                } elseif (!$emailadress) {
                    $data['Success'] = false;
                    $data['Error'] = 'No EmailAdress';
                    return json_encode($data);
                } elseif (!$dateOfBirth || $dateOfBirth == '0000-00-00') {
                    $data['Success'] = false;
                    $data['Error'] = 'No Date Of Birth';
                    return json_encode($data);
                }

                $userWithSameUsername = User::get()->filter(['Username' => $username])->first();
                $userWithSameEmail = User::get()->filter(['Email' => $emailadress])->first();

                if ($userWithSameUsername) {
                    $data['Success'] = false;
                    $data['Error'] = 'Username already exists';
                    return json_encode($data);
                } elseif ($userWithSameEmail) {
                    $data['Success'] = false;
                    $data['Error'] = 'EmailAdress already exists';
                    return json_encode($data);
                }

                $user = User::create();

                $user->Username = $username;
                $user->Nickname = $username;
                $user->Email = $emailadress;
                $user->Password = md5($password);
                $user->DateOfBirth = $dateOfBirth;
                $user->LinkedLogging = true;

                $data['Success'] = true;
                $data['Message'] = 'User created';
                $data['Username'] = $username;

                $authToken = AuthToken::create();
                $authToken->DeviceName = $deviceName;
                $authToken->ParentID = $user->ID;
                $authToken->LastLogin = date('Y-m-d H:i:s');
                $authToken->write();

                $user->AuthTokens()->add($authToken);
                $user->write();

                $data['Token'] = $authToken->Token;
            }

            return json_encode($data);
        }
    }
}
