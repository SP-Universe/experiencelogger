<?php

namespace App\Profile;

//use jamesbolitho\frontenduploadfield\UploadField;
use App\News\News;
use App\News\NewsPage;
use PageController;
use SilverStripe\Security\Security;
use App\ExperienceDatabase\LogEntry;
use App\User\User;

/**
 * Class \App\Docs\DocsPageController
 *
 * @property DashboardPage $dataRecord
 * @method DashboardPage data()
 * @mixin DashboardPage
 */
class DashboardPageController extends PageController
{

    private static $allowed_actions = [];

    public function getUser()
    {
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            return $currentUser;
        }
    }

    public function getLastLogged()
    {
        $currentMember = Security::getCurrentUser();
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();


        if ($currentUser) {
            $logs = LogEntry::get()->filter("UserID", $currentUser->ID)->sort("VisitTime DESC")->limit(5);
            return $logs;
        }
    }

    public function getNews()
    {
        $news = News::get()->sort("Date DESC")->limit(5);
        return $news;
    }

    public function getAllNewsLink()
    {
        return NewsPage::get()->first()->Link();
    }
}
