<?php
namespace App\Profile;

//use jamesbolitho\frontenduploadfield\UploadField;
use PageController;
use SilverStripe\Forms\Form;
use SilverStripe\Assets\File;
use App\Profile\FriendRequest;
use App\Overview\StatisticsPage;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FileField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\GroupedList;
use SilverStripe\Security\Member;
use SilverStripe\Forms\FormAction;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\Security\Security;
use App\ExperienceDatabase\LogEntry;
use HudhaifaS\Forms\FrontendImageField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;

/**
 * Class \App\Docs\DocsPageController
 *
 * @property \App\Profile\DashboardPage $dataRecord
 * @method \App\Profile\DashboardPage data()
 * @mixin \App\Profile\DashboardPage
 */
class DashboardPageController extends PageController
{

    private static $allowed_actions = [
    ];

    public function getUser()
    {
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            return $currentUser;
        }
    }

    public function getLastLogged()
    {
        $member = Security::getCurrentUser();
        if ($member) {
            $logs = LogEntry::get()->filter("UserID", $member->ID)->sort("VisitTime DESC")->limit(5);
            return $logs;
        }
    }
}
