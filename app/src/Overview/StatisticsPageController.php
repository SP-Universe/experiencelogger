<?php

namespace App\Overview;

use PageController;
use App\Ratings\Rating;
use SilverStripe\ORM\GroupedList;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use App\ExperienceDatabase\LogEntry;
use App\ExperienceDatabase\Experience;
use App\ExperienceDatabase\ExperienceSeat;
use App\ExperienceDatabase\ExperienceLocation;
use App\Helper\StatisticsHelper;
use App\User\User;

/**
 * Class \App\Docs\DocsPageController
 *
 * @property \App\Overview\StatisticsPage $dataRecord
 * @method \App\Overview\StatisticsPage data()
 * @mixin \App\Overview\StatisticsPage
 */
class StatisticsPageController extends PageController
{

    private static $allowed_actions = [
        "location",
        "experience",
        "user"
    ];

    public function location()
    {

        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            return;
        }
        $currentUserId = User::get()->filter("ID", $currentMember->UserID)->first()->ID;

        $title = $this->getRequest()->param("ID");
        $location = ExperienceLocation::get()->filter("LinkTitle", $title)->first();
        $visitsPerYear = StatisticsHelper::getVisitCounterPerYear($currentUserId, $location->ID); //returns array with year and visit count
        $averageVisitsPerYear = StatisticsHelper::getAverageVisitsPerYear($currentUserId, $location->ID);
        $averageLogsPerVisit = StatisticsHelper::getAverageLogCountPerVisit($currentUserId, $location->ID);
        $mostLoggedExperiencePerVisitAllTime = StatisticsHelper::getMostLoggedExperiencePerVisitAllTime($currentUserId, $location->ID); //returns experience
        $mostLoggedExperiencePerYear = StatisticsHelper::getMostLoggedExperiencePerYear($currentUserId, $location->ID); //returns array with year and experience

        return array(
            "Location" => $location,
            "VisitsPerYear" => $visitsPerYear,
            "AverageVisitsPerYear" => $averageVisitsPerYear,
            "AverageLogsPerVisit" => $averageLogsPerVisit,
            "MostLoggedExperiencePerVisitAllTime" => $mostLoggedExperiencePerVisitAllTime,
            "MostLoggedExperiencePerYear" => $mostLoggedExperiencePerYear,
        );
    }

    public function experience()
    {

        $title = $this->getRequest()->param("ID");
        $park = ExperienceLocation::get()->filter("LinkTitle", explode("---", $title)[0])->first();
        $title = explode("---", $title)[1];
        $experience = Experience::get()->filter(array(
            "LinkTitle" => $title,
            "ParentID" => $park->ID
        ))->first();

        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            $currentMember = Member::get()->first();
        }
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();

        return array(
            "Experience" => $experience,
            "Location" => $park,
            "AverageLogsPerVisit" => StatisticsHelper::getAverageLogsOfExperiencePerVisit($currentUser->ID, $experience),
            "AverageScore" => StatisticsHelper::getAverageScoreOfExperience($currentUser->ID, $experience),
            "HighestScoreOfExperienceAllTime" => StatisticsHelper::getHighestScoreOfExperienceAllTime($currentUser->ID, $experience),
            "HighestScoreOfExperiencePerYear" => StatisticsHelper::getHighestScoreOfExperiencePerYear($currentUser->ID, $experience),
            "LogsPerYear" => StatisticsHelper::getRideCounterPerYear($currentUser->ID, $experience->ID),
        );
    }

    public function user()
    {
        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            $currentMember = Member::get()->first();
        }
        $title = $this->getRequest()->param("ID");
        $currentMember = Member::get()->filter("Nickname", $title)->first();
        if (!$currentMember) {
            return;
        }
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();

        return array(
            "User" => $currentUser,
            "MostLoggedExperienceAllTime" => StatisticsHelper::getMostLoggedExperienceOfUserAllTime($currentUser->ID),
            "MostLoggedExperiencePerYear" => StatisticsHelper::getMostLoggedExperienceOfUserPerYear($currentUser->ID),
            "Counts" => StatisticsHelper::getCounts($currentUser->ID),
        );
    }
}
