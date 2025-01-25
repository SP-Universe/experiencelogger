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

        $data = self::getUserData($user);
        $data['token'] = $token->Token;

        return $data;
    }

    public static function getUserData($user)
    {
        // Get user data from database
        if (!$user) {
            return null;
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
        ];

        return $userData;
    }
}
