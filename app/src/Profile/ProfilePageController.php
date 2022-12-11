<?php
namespace App\Profile;

use PageController;
use SilverStripe\ORM\GroupedList;
use SilverStripe\Security\Security;
use App\ExperienceDatabase\LogEntry;

/**
 * Class \App\Docs\DocsPageController
 *
 * @property \App\Profile\ProfilePage $dataRecord
 * @method \App\Profile\ProfilePage data()
 * @mixin \App\Profile\ProfilePage
 */
class ProfilePageController extends PageController
{

    private static $allowed_actions = [
    ];

    public function getNickname()
    {
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            return $currentUser->Nickname;
        }
    }

    public function getBirthdate()
    {
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            return $currentUser->DateOfBirth;
        }
    }

    public function getFriends()
    {
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            return $currentUser->Friends();
        }
    }

    public function getFavouritePlaces()
    {
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            return $currentUser->FavouritePlaces();
        }
    }

    public function getLogs()
    {
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            return GroupedList::create(LogEntry::get()->filter("UserID", $currentUser->ID)->sort("VisitTime", "DESC"));
        }
    }
}
