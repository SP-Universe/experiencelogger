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
        $currentUserId = Security::getCurrentUser()->ID;
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

        $currentUser = Security::getCurrentUser();
        if (!$currentUser) {
            $currentUser = Member::get()->filter("Nickname", "CoasterBot")->first();
        }

        return array(
            "Experience" => $experience,
            "Location" => $park,
            "AverageLogsPerVisit" => StatisticsHelper::getAverageLogsOfExperiencePerVisit($currentUser->Id, $experience),
            "AverageScore" => StatisticsHelper::getAverageScoreOfExperience($currentUser->Id, $experience),
            "HighestScoreOfExperienceAllTime" => StatisticsHelper::getHighestScoreOfExperienceAllTime($currentUser->Id, $experience),
            "HighestScoreOfExperiencePerYear" => StatisticsHelper::getHighestScoreOfExperiencePerYear($currentUser->Id, $experience),
            "LogsPerYear" => StatisticsHelper::getRideCounterPerYear($currentUser->Id, $experience->ID),
        );
    }

    public function user()
    {
        $currentUser = Security::getCurrentUser();
        $title = $this->getRequest()->param("ID");
        $user = Member::get()->filter("Nickname", $title)->first();

        if ($user) {
            $currentUser = $user;
        }

        return array(
            "User" => $currentUser,
            "MostLoggedExperienceAllTime" => StatisticsHelper::getMostLoggedExperienceOfUserAllTime($currentUser->ID),
            "MostLoggedExperiencePerYear" => StatisticsHelper::getMostLoggedExperienceOfUserPerYear($currentUser->ID),
        );
    }
}
