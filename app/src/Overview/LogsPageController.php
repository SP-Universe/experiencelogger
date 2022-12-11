<?php
namespace App\Overview;

use PageController;
use SilverStripe\ORM\GroupedList;
use SilverStripe\Security\Security;
use App\ExperienceDatabase\LogEntry;

/**
 * Class \App\Docs\DocsPageController
 *
 * @property \App\Overview\LogsPage $dataRecord
 * @method \App\Overview\LogsPage data()
 * @mixin \App\Overview\LogsPage
 */
class LogsPageController extends PageController
{

    private static $allowed_actions = [
        "detail",
    ];

    public function getLogs()
    {
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            return GroupedList::create(LogEntry::get()->filter("UserID", $currentUser->ID)->sort("VisitTime", "DESC"));
        }
    }
}
