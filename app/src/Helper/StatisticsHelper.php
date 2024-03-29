<?php

namespace App\Helper;

use Exception;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Security\Member;
use App\ExperienceDatabase\LogEntry;
use App\ExperienceDatabase\Experience;
use SilverStripe\Security\Security;

class StatisticsHelper
{
    /**
     * Get All Logs of User
     * @param integer $id ID of the checked user
     * @return DataList LogEntries with the checked user
     */
    public static function getLogsOfUser($id)
    {
        $checkedUser = Member::get()->byID($id);
        if ($checkedUser) {
            return LogEntry::get()->filter([
                'UserID' => $checkedUser->ID,
            ]);
        } else {
            throw new Exception('No correct UserID given');
        }
    }

    /**
     * Get logs count of a experience per year
     * @param integer $userId ID of the checked user
     * @param integer $experienceId ID of the checked experience
     * @return ArrayList 2D-Array of the year and the count of logs
     */
    public static function getRideCounterPerYear($userId, $experienceId)
    {
        $logs = ExperienceHelper::getLogsOfExperienceFromUser($userId, $experienceId);
        $yearCounts = [];

        foreach ($logs as $item) {
            $visitTime = strtotime($item->VisitTime);
            $year = date('Y', $visitTime);
            if (!isset($yearCounts[$year])) {
                $yearCounts[$year] = 0;
            }
            $yearCounts[$year] += 1;
        }

        foreach ($yearCounts as $year => $count) {
            $yearCounts[$year] = array(
                "year" => $year,
                "logs" => $count,
            );
        }

        return ArrayList::create($yearCounts);
    }


    /**
     * Get count of visits on unique days at a Location per year
     * @param integer $userId ID of the checked user
     * @param integer $locationID ID of the checked location
     * @return ArrayList 2D-Array of the year and the count of visits
     */
    public static function getVisitCounterPerYear($userId, $locationID)
    {
        $experiences = Experience::get()->filter("ParentID", $locationID);
        $logs = self::getLogsOfUser($userId)->filter("ExperienceID", $experiences->column("ID"));

        $uniqueDates = [];
        $yearCounts = [];
        foreach ($logs as $item) {
            $visitTime = strtotime($item->VisitTime);

            $date = date('Y-m-d', $visitTime);

            if (!in_array($date, $uniqueDates)) {
                $uniqueDates[] = $date;
                $year = date('Y', strtotime($date));
                if (!isset($yearCounts[$year])) {
                    $yearCounts[$year] = 0;
                }
                $yearCounts[$year] += 1;
            }
        }

        $years = array();
        foreach ($uniqueDates as $log) {
            $year = date("Y", strtotime($log));
            $construction = array(
                "year" => $year,
                "logs" => $yearCounts[$year],
            );
            if (!in_array($construction, $years)) {
                array_push($years, $construction);
            }
        }

        return ArrayList::create($years);
    }

    /**
     * Get count of visits on unique days at a Location per year
     * @param integer $userId ID of the checked user
     * @param integer $locationID ID of the checked location
     * @return ArrayList 2D-Array of the year and the count of visits
     */
    public static function getVisitCounterPerYearFromCurrentUser($locationID)
    {
        $userId = Security::getCurrentUser()->ID;
        $experiences = Experience::get()->filter("ParentID", $locationID);
        $logs = self::getLogsOfUser($userId)->filter("ExperienceID", $experiences->column("ID"));

        $uniqueDates = [];
        $yearCounts = [];
        foreach ($logs as $item) {
            $visitTime = strtotime($item->VisitTime);

            $date = date('Y-m-d', $visitTime);

            if (!in_array($date, $uniqueDates)) {
                $uniqueDates[] = $date;
                $year = date('Y', strtotime($date));
                if (!isset($yearCounts[$year])) {
                    $yearCounts[$year] = 0;
                }
                $yearCounts[$year] += 1;
            }
        }

        $years = array();
        foreach ($uniqueDates as $log) {
            $year = date("Y", strtotime($log));
            $construction = array(
                "year" => $year,
                "logs" => $yearCounts[$year],
            );
            if (!in_array($construction, $years)) {
                array_push($years, $construction);
            }
        }

        return ArrayList::create($years);
    }

    /**
     * Get percent of x from y
     * @param integer $all The total number
     * @param integer $from Part of the total number
     * @return string Percent as string
     */
    public static function getPercentAsString($all, $from, $accuracy = 0)
    {
        return self::getPercentAsNumber($all, $from, $accuracy) . "%";
    }

    /**
     * Get percent of x from y
     * @param integer $all The total number
     * @param integer $from Part of the total number
     * @param integer $accuracy Accuracy of the percent
     * @return string Percent as number
     */
    public static function getPercentAsNumber($all, $from, $accuracy = 0)
    {
        //Check for preventing dividing by zero
        if ($all > 0) {
            return round(($from / $all) * 100, $accuracy);
        } else {
            return 0;
        }
    }

    /**
     * Get Average amount of logs of experience per park visit
     * @param integer $userId ID of the checked user
     * @param Experience $experience Experience to check
     * @return double Average amount as double
     */
    public static function getAverageLogsOfExperiencePerVisit($userId, $experience, $accuracy = 2)
    {
        $visitsPerYear = self::getVisitCounterPerYear($userId, $experience->ParentID);
        $logsInExperience = LogEntry::get()->filter(["ExperienceID" => $experience->ID, "UserID" => $userId]);

        $visitsAllTime = 0;
        foreach ($visitsPerYear as $visit) {
            $visitsAllTime += $visit->logs;
        }

        return round($logsInExperience->Count() / $visitsAllTime, $accuracy);
    }

    /**
     * Get Average amount of logs of experience per year
     * @param integer $userId ID of the checked user
     * @param Experience $experience Experience to check
     * @return double Average amount as double
     */
    public static function getAverageLogsOfExperiencePerYear($userId, $experience, $accuracy = 2)
    {
        $logsInExperience = ExperienceHelper::getLogsOfExperienceFromUser($userId, $experience->ID);
        $visitsPerYear = self::getVisitCounterPerYear($userId, $experience->ParentID);

        return round($logsInExperience->Count() / count($visitsPerYear), $accuracy);
    }

    /**
     * Get Average amount of visits of location per year
     * @param integer $userId ID of the checked user
     * @param integer $locationID ID of the checked location
     * @param integer $accuracy Accuracy of the Average amount
     * @return double Average amount as double
     */
    public static function getAverageVisitsPerYear($userId, $locationID, $accuracy = 2)
    {
        $visitsPerYear = self::getVisitCounterPerYear($userId, $locationID);
        $visitsAllTime = 0;
        foreach ($visitsPerYear as $visit) {
            $visitsAllTime += $visit->logs;
        }

        return round($visitsAllTime / count($visitsPerYear), $accuracy);
    }

    /**
     * Get Average amount of visits of location per year
     * @param integer $locationID ID of the checked location
     * @param integer $accuracy Accuracy of the Average amount
     * @return double Average amount as double
     */
    public static function getAverageVisitsPerYearFromCurrentUser($locationID, $accuracy = 2)
    {
        $userId = Security::getCurrentUser()->ID;
        $visitsPerYear = self::getVisitCounterPerYear($userId, $locationID);
        $visitsAllTime = 0;
        foreach ($visitsPerYear as $visit) {
            $visitsAllTime += $visit->logs;
        }

        return round($visitsAllTime / count($visitsPerYear), $accuracy);
    }

    /**
     * Get Average amount of logs per visit of location
     * @param integer $userId ID of the checked user
     * @param integer $locationID ID of the checked location
     * @param integer $accuracy Accuracy of the Average amount
     * @return double Average amount as double
     */
    public static function getAverageLogCountPerVisit($userId, $locationID, $accuracy = 2)
    {
        $visitsPerYear = self::getVisitCounterPerYear($userId, $locationID);
        $logs = self::getLogsOfUser($userId)->filter("ExperienceID", Experience::get()->filter("ParentID", $locationID)->column("ID"));

        $visitsAllTime = 0;
        foreach ($visitsPerYear as $visit) {
            $visitsAllTime += $visit->logs;
        }

        return round($logs->Count() / $visitsAllTime, $accuracy);
    }

    /**
     * Get Average amount of logs per visit of location
     * @param integer $locationID ID of the checked location
     * @param integer $accuracy Accuracy of the Average amount
     * @return double Average amount as double
     */
    public static function getAverageLogCountPerVisitFromCurrentUser($locationID, $accuracy = 2)
    {
        $userId = Security::getCurrentUser()->ID;
        $visitsPerYear = self::getVisitCounterPerYear($userId, $locationID);
        $logs = self::getLogsOfUser($userId)->filter("ExperienceID", Experience::get()->filter("ParentID", $locationID)->column("ID"));

        $visitsAllTime = 0;
        foreach ($visitsPerYear as $visit) {
            $visitsAllTime += $visit->logs;
        }

        return round($logs->Count() / $visitsAllTime, $accuracy);
    }

    /**
     * Get Most Logged Experience per visit of location
     * @param integer $userId ID of the checked user
     * @param integer $locationID ID of the checked location
     * @return Experience Most logged Experience
     */
    public static function getMostLoggedExperiencePerVisitAllTime($userId, $locationID)
    {
        $experiences = Experience::get()->filter("ParentID", $locationID);
        $logs = self::getLogsOfUser($userId)->filter("ExperienceID", $experiences->column("ID"));

        $experienceCounts = [];
        foreach ($logs as $item) {
            $experienceId = $item->ExperienceID;
            if (!isset($experienceCounts[$experienceId])) {
                $experienceCounts[$experienceId] = 0;
            }
            $experienceCounts[$experienceId] += 1;
        }

        $mostLoggedExperience = null;
        $mostLoggedExperienceCount = 0;
        foreach ($experienceCounts as $experienceId => $count) {
            if ($count > $mostLoggedExperienceCount) {
                $mostLoggedExperience = Experience::get()->byID($experienceId);
                $mostLoggedExperienceCount = $count;
            }
        }

        return $mostLoggedExperience;
    }

    /**
     * Get Most Logged Experience per visit of location per year
     * @param integer $userId ID of the checked user
     * @param integer $locationID ID of the checked location
     * @return ArrayList 2D-List of Most logged Experiences per year
     */
    public static function getMostLoggedExperiencePerVisitPerYear($userId, $locationID)
    {
        $experiences = Experience::get()->filter("ParentID", $locationID);
        $logs = self::getLogsOfUser($userId)->filter("ExperienceID", $experiences->column("ID"));

        $uniqueDates = [];
        $yearCounts = [];
        foreach ($logs as $item) {
            $visitTime = strtotime($item->VisitTime);

            $date = date('Y-m-d', $visitTime);

            if (!in_array($date, $uniqueDates)) {
                $uniqueDates[] = $date;
                $year = date('Y', strtotime($date));
                if (!isset($yearCounts[$year])) {
                    $yearCounts[$year] = [];
                }
                if (!isset($yearCounts[$year][$item->ExperienceID])) {
                    $yearCounts[$year][$item->ExperienceID] = 0;
                }
                $yearCounts[$year][$item->ExperienceID] += 1;
            }
        }

        $years = array();
        foreach ($uniqueDates as $log) {
            $year = date("Y", strtotime($log));
            $construction = array(
                "year" => $year,
                "experience" => Experience::get()->byID(array_search(max($yearCounts[$year]), $yearCounts[$year])),
            );
            if (!in_array($construction, $years)) {
                array_push($years, $construction);
            }
        }

        return ArrayList::create($years);
    }

    /**
     * Get Most Logged Experience of user
     * @param integer $userId ID of the checked user
     * @return Experience Most logged Experience
     */
    public static function getMostLoggedExperienceOfUserAllTime($userId)
    {
        $logs = self::getLogsOfUser($userId);

        $experienceCounts = [];
        foreach ($logs as $item) {
            $experienceId = $item->ExperienceID;
            if (!isset($experienceCounts[$experienceId])) {
                $experienceCounts[$experienceId] = 0;
            }
            $experienceCounts[$experienceId] += 1;
        }

        $mostLoggedExperience = null;
        $mostLoggedExperienceCount = 0;
        foreach ($experienceCounts as $experienceId => $count) {
            if ($count > $mostLoggedExperienceCount) {
                $mostLoggedExperience = Experience::get()->byID($experienceId);
                $mostLoggedExperienceCount = $count;
            }
        }

        return $mostLoggedExperience;
    }

    /**
     * Get Most Logged Experience of user per year
     * @param integer $userId ID of the checked user
     * @return ArrayList 2D-List of Most logged Experiences with year
     */
    public static function getMostLoggedExperienceOfUserPerYear($userId)
    {
        $logs = self::getLogsOfUser($userId);

        $uniqueDates = [];
        $yearCounts = [];
        foreach ($logs as $item) {
            $visitTime = strtotime($item->VisitTime);

            $date = date('Y-m-d', $visitTime);

            if (!in_array($date, $uniqueDates)) {
                $uniqueDates[] = $date;
                $year = date('Y', strtotime($date));
                if (!isset($yearCounts[$year])) {
                    $yearCounts[$year] = [];
                }
                if (!isset($yearCounts[$year][$item->ExperienceID])) {
                    $yearCounts[$year][$item->ExperienceID] = 0;
                }
                $yearCounts[$year][$item->ExperienceID] += 1;
            }
        }

        $years = array();
        foreach ($uniqueDates as $log) {
            $year = date("Y", strtotime($log));
            $construction = array(
                "year" => $year,
                "experience" => Experience::get()->byID(array_search(max($yearCounts[$year]), $yearCounts[$year])),
            );
            if (!in_array($construction, $years)) {
                array_push($years, $construction);
            }
        }

        return ArrayList::create($years);
    }
}
