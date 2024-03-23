<?php
namespace App\Extensions;

use DateTime;
use App\Profile\FriendRequest;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\ArrayList;
use App\Helper\StatisticsHelper;
use SilverStripe\ORM\GroupedList;
use SilverStripe\Security\Member;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\Security\Security;
use App\ExperienceDatabase\LogEntry;
use App\ExperienceDatabase\Experience;
use SilverStripe\ORM\ValidationResult;
use App\ExperienceDatabase\ExperienceLocation;

/**
 * Class \App\Extensions\ExperienceMemberExtension
 *
 * @property \SilverStripe\Security\Member|\App\Extensions\ExperienceMemberExtension $owner
 * @property string $DateOfBirth
 * @property string $Nickname
 * @property string $ProfilePrivacy
 * @property bool $LinkedLogging
 * @property string $LastLogDate
 * @property bool $HasPremium
 * @property int $AvatarID
 * @property int $LastLoggedAreaID
 * @method \SilverStripe\Assets\Image Avatar()
 * @method \App\ExperienceDatabase\Experience LastLoggedArea()
 * @method \SilverStripe\ORM\ManyManyList|\App\ExperienceDatabase\ExperienceLocation[] FavouritePlaces()
 * @method \SilverStripe\ORM\ManyManyList|\App\Profile\FriendRequest[] Friends()
 */
class ExperienceMemberExtension extends DataExtension
{
    // define additional properties
    private static $db = [
        'DateOfBirth' => 'Date',
        'Nickname' => 'Varchar(255)',
        'ProfilePrivacy' => 'Enum("Public, Friends, Private", "Public")',
        "LinkedLogging" => "Boolean",
        "LastLogDate" => "Date",
        "HasPremium" => "Boolean",
    ];

    private static $has_one = [
        'Avatar' => Image::class,
        "LastLoggedArea" => Experience::class,
    ];

    private static $owns = [
        'Avatar',
    ];

    private static $many_many = [
        "FavouritePlaces" => ExperienceLocation::class,
        "Friends" => FriendRequest::class,
    ];

    private static $belongs_many = [
        "LogEntries" => LogEntry::class,
    ];

    private static $searchable_fields = [
        "Nickname",
    ];

    private static $summary_fields = [
        "Nickname",
        "FirstName",
        "Surname",
        "Email",
    ];

    private static $defaults = [
        'ProfilePrivacy' => 'Public',
        'LinkedLogging' => true,
        'HasPremium' => false,
    ];

    public function populateDefaults()
    {
        $this->owner->ProfilePrivacy = 'Public';
        $this->owner->LinkedLogging = true;
        $this->owner->HasPremium = false;
    }

    public function LogCount($id)
    {
        return LogEntry::get()->filter([
            'UserID' => $this->owner->ID,
            'ExperienceID' => $id,
        ])->count();
    }

    public function getLogs($id = 0)
    {
        if ($id == 0) {
            $id = $this->owner->ID;
        }
        return StatisticsHelper::getLogsOfUser($id);
    }

    //Berechne Logs pro Jahr für eine Experience
    public function getRideCounterPerYear($experienceId)
    {
        return StatisticsHelper::getRideCounterPerYear($this->owner->ID, $experienceId);
    }

    //Berechne Besuche pro Jahr für eine Location
    public function getVisitCounterPerYear($locationID)
    {
        return StatisticsHelper::getVisitCounterPerYear($this->owner->ID, $locationID);
    }

    public function getProfileImage($size = 200)
    {
        if ($this->owner->AvatarID) {
            return $this->owner->Avatar()->Fill($size, $size)->Url;
        } else {
            return $this->owner->getGravatar($size);
        }
    }

    public function getGravatar($size = 200)
    {
        //Generate a Gravatar for the user
        $s = $size; //Size in pixels (max 2048)
        $d = 'identicon'; //Default replacement for missing image
        $r = 'g'; //Rating
        $img = false; //Returning full image tag
        $atts = array(); //Extra attributes to add

        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($this->owner->Email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }
        return $url;
    }

    public function getPaginatedLogs()
    {
        $logs = $this->getLogs($this->owner->ID);
        $paginated = new PaginatedList($logs, $this->owner->getRequest());
        $paginated->setPageLength(10);
        return $paginated;
    }

    public function getFriends()
    {
        $acceptedRequests = $this->owner->Friends()->filter([
            "FriendshipStatus" => "Accepted",
        ]);
        $friends = new ArrayList();
        foreach ($acceptedRequests as $request) {
            if ($request->RequesterID == $this->owner->ID) {
                $friends->push($request->Requestee());
            } else {
                $friends->push($request->Requester());
            }
        }
        return $friends;
    }

    public function getFriendRequests()
    {
        $acceptedRequests = $this->owner->Friends()->filter([
            "FriendshipStatus" => "Pending",
        ]);
        $friends = new ArrayList();
        foreach ($acceptedRequests as $request) {
            if ($request->RequesterID == $this->owner->ID) {
                //Not a friend request, but a request from the user
            } else {
                $friends->push($request);
            }
        }
        return $friends;
    }

    public function getPendingFriendRequests()
    {
        $acceptedRequests = $this->owner->Friends()->filter([
            "FriendshipStatus" => "Pending",
        ]);
        $friends = new ArrayList();
        foreach ($acceptedRequests as $request) {
            if ($request->RequesterID == $this->owner->ID) {
                $friends->push($request);
            } else {
                //Not a friend request from the user but from another user
            }
        }
        return $friends;
    }

    public function getLoggedParksCount()
    {
        //get unique ParentIDs of all LogEntries
        $userLogs = LogEntry::get()->filter([
            'UserID' => $this->owner->ID,
        ]);
        foreach ($userLogs as $log) {
            $visitedExperiences[] = $log->ExperienceID;
        }
        if (!isset($visitedExperiences)) {
            return 0;
        }
        $uniqueExperiences = array_unique($visitedExperiences);
        foreach ($uniqueExperiences as $experienceID) {
            $places[] = Experience::get()->byID($experienceID);
        }
        $uniquePlaces = array_unique($places);
        return count($uniquePlaces);
    }

    public function IsFriendWithCurrentUser()
    {
        $acceptedRequests = $this->owner->Friends()->filter([
            "FriendshipStatus" => "Accepted",
        ]);
        $currentUser = Security::getCurrentUser();
        foreach ($acceptedRequests as $request) {
            if ($request->RequesterID == $currentUser->ID || $request->RequesteeID == $currentUser->ID) {
                return true;
            }
        }
        return false;
    }

    public function validate(ValidationResult $validationResult)
    {
        if ($this->owner->DateOfBirth) {
            $date = new DateTime($this->owner->DateOfBirth);
            $now = new DateTime();
            $age = $now->diff($date)->y;
            if ($age < 13) {
                $validationResult->addFieldError('DateOfBirth', 'You must be at least 13 years old to register.');
            }
        }
        if ($this->owner->Nickname == "admin") {
            $validationResult->addFieldError('Nickname', 'This nickname is not allowed.');
        }
        if (!ctype_alnum($this->owner->Nickname)) {
            $validationResult->addFieldError('Nickname', 'Only alphanumeric characters are allowed.');
        }
    }
}
