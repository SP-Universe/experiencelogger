<?php

namespace App\Helper;

use Exception;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use App\ExperienceDatabase\LogEntry;
use App\ExperienceDatabase\Experience;

class ExperienceHelper
{
    /**
     * Get Experience with Id
     * @param integer $experienceId ID of the wanted Experience
     * @return Experience Experience with the checked ID
     */
    public static function getExperienceById($experienceId)
    {
        $experience = Experience::get()->byID($experienceId);
        if ($experience) {
            return $experience;
        } else {
            throw new Exception('No correct ExperienceID given');
        }
    }

    /**
     * Get Experience with LinkTitle
     * @param string $linkTitle linkTitle of the wanted Experience
     * @return Experience Experience with the checked linkTitle
     */
    public static function getExperienceByLinkTitle($linkTitle)
    {
        $experience = Experience::get()->filter('LinkTitle', $linkTitle)->first();
        if ($experience) {
            return $experience;
        } else {
            throw new Exception('No Experience with that LinkTitle found');
        }
    }

    /**
     * Get Logs of Experience from a defined User
     * @param integer $userId Id of the searching User
     * @param integer $experienceId Id of the wanted Experience
     * @return DataList Logs of the Experience from the User
     */
    public static function getLogsOfExperienceFromUser($userId, $experienceId)
    {
        $checkedUser = Member::get()->byID($userId);
        $checkedExperience = self::getExperienceById($experienceId);
        if ($checkedUser && $checkedExperience) {
            return LogEntry::get()->filter([
                'UserID' => $checkedUser->ID,
                'ExperienceID' => $checkedExperience->ID,
            ]);
        } else {
            throw new Exception('No correct UserID or ExperienceID given');
        }
    }

    /**
     * Get Logs of Experience from the current User
     * @param integer $experienceId Id of the wanted Experience
     * @return DataList Logs of the Experience from the current User
     */
    public static function getLogsOfExperienceFromCurrentUser($experienceId)
    {
        $checkedUser = Security::getCurrentUser();
        $checkedExperience = self::getExperienceById($experienceId);
        if ($checkedUser && $checkedExperience) {
            return LogEntry::get()->filter([
                'UserID' => $checkedUser->ID,
                'ExperienceID' => $checkedExperience->ID,
            ]);
        } else {
            throw new Exception('No correct ExperienceID given or user is not logged in');
        }
    }

    /**
     * Return if the linked area will be linked log at a given date
     * @param Experience $experience Experience to check
     * @param string $loggedDate Date to check
     * @return bool True if the area will be linked log at the given date
     */
    public static function getWillLinkLogArea($experience, $loggedDate)
    {
        $currentUser = Security::getCurrentUser();
        if ($experience->AreaID == 0 || !$currentUser) {
            return false;
        }

        $lastLoggedArea = $currentUser->LastLoggedArea();
        $lastLoggedDate = $currentUser->LastLogDate;

        if ($lastLoggedArea && $lastLoggedArea->ID == $experience->AreaID && $lastLoggedDate == $loggedDate) {
            return false;
        }

        return true;
    }
}
