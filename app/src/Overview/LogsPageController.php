<?php

namespace App\Overview;

use SilverStripe\Model\List\GroupedList;
use App\User\User;
use PageController;
use SilverStripe\Security\Security;
use App\ExperienceDatabase\LogEntry;

/**
 * Class \App\Docs\DocsPageController
 *
 * @property LogsPage $dataRecord
 * @method LogsPage data()
 * @mixin LogsPage
 */
class LogsPageController extends PageController
{

    private static $allowed_actions = [
        "month",
        "date",
        "detail",
        "all"
    ];

    public function getLogs()
    {
        $currentMember = Security::getCurrentUser();
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
        if ($currentUser) {
            return GroupedList::create(LogEntry::get()->filter("UserID", $currentUser->ID)->sort("VisitTime", "DESC"));
        }
    }

    public function getLogsForMonth($month, $year)
    {
        $currentMember = Security::getCurrentUser();
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
        if ($currentUser) {
            $day    = 1;
            $endDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            return GroupedList::create(LogEntry::get()->filter(
                [
                    'UserID' => $currentUser->ID,
                    'VisitTime:GreaterThan' => $year . '-' . $month . '-' . $day . ' 00:00:00',
                    'VisitTime:LessThan' => $year . '-' . $month . '-' . $endDay . ' 23:59:59',
                ]
            )->sort("VisitTime", "DESC"));
        }
    }

    public function getLogsForDay($day, $month, $year)
    {
        $currentMember = Security::getCurrentUser();
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
        if ($currentUser) {
            return GroupedList::create(LogEntry::get()->filter(
                [
                    'UserID' => $currentUser->ID,
                    'VisitTime:GreaterThan' => $year . '-' . $month . '-' . $day . ' 00:00:00',
                    'VisitTime:LessThan' => $year . '-' . $month . '-' . $day . ' 23:59:59',
                ]
            )->sort("VisitTime", "DESC"));
        }
    }

    public function month()
    {
        $currentMember = Security::getCurrentUser();
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();

        if ($currentUser) {
            $date = $this->getRequest()->param("ID");
            $month = explode("-", $date)[0];
            $year = explode("-", $date)[1];
            return [
                "MonthText" => date("F", mktime(0, 0, 0, $month, 10)),
                "Month" => $month,
                "Year" => $year,
            ];
        }
    }

    public function date()
    {
        $currentMember = Security::getCurrentUser();
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();

        if ($currentUser) {
            $date = $this->getRequest()->param("ID");
            $day = explode("-", $date)[0];
            $month = explode("-", $date)[1];
            $year = explode("-", $date)[2];
            return [
                "Day" => $day,
                "Month" => $month,
                "Year" => $year,
            ];
        }
    }

    public function all()
    {
        $currentMember = Security::getCurrentUser();
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();

        if ($currentUser) {
            return [
                "Logs" => $this->getLogs(),
            ];
        }
    }
}
