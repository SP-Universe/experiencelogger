<?php

namespace App\Api\Helper;

use SilverStripe\Security\Member;
use App\ExperienceDatabase\UserAuthToken;
use App\Helper\StatisticsHelper;

class ApiUserDataHelper
{
    /**
     * Get User from a Token
     * @param string $token Auth Token of a user
     * @return Member User of the given Auth Token
     */
    public static function getUserFromToken($token)
    {
        $savedToken = UserAuthToken::get()->filter('Token', $token)->first();
        $user = null;
        if (!$savedToken) {
            return null;
        }

        $user = $savedToken->Parent();
        if (!$user) {
            return null;
        }
        $user->LastOnline = date('Y-m-d H:i:s');
        $savedToken->updateLastLogin();
        $user->write();

        return $user;
    }

    public static function loginUserAndCreateToken($user, $deviceName)
    {
        // Create a new token for the user
        $token = UserAuthToken::create();
        $token->ParentID = $user->ID;
        $token->DeviceName = $deviceName;
        $token->write();

        $token->updateLastLogin();

        $data = self::getUserData($user);
        $data['token'] = $token->Token;
        $data['tokenID'] = $token->ID;

        return $data;
    }

    public static function getUserData($user)
    {
        // Get user data from database
        if (!$user) {
            return null;
        }
        $tokens = UserAuthToken::get()->filter('ParentID', $user->ID);
        $loggedInDevices = [];
        //List all devices where the user is logged in with the token, device name and last login date
        foreach ($tokens as $token) {
            $loggedInDevices[] = [
                'id' => $token->ID,
                'deviceName' => $token->DeviceName,
                'creationDate' => $token->CreationDate,
                'lastLogin' => $token->LastLogin
            ];
        }

        $userData = [
            'nickname' => $user->Nickname,
            'displayname' => $user->Displayname,
            'email' => $user->Email,
            'avatar' => $user->Avatar()->AbsoluteLink(),
            'profileprivacy' => $user->ProfilePrivacy,
            'lastonline' => $user->LastOnline,
            'lastlogged' => $user->LastLogDate,
            'registrationdate' => $user->Created,
            'premium' => $user->HasPremium,
            'logCount' => StatisticsHelper::getLogsOfUser($user->ID)->count(),
            'placesCount' => count(StatisticsHelper::getVisitedPlacesOfUser($user->ID)),
            'loggedInDevices' => $loggedInDevices,
        ];

        return $userData;
    }
}
