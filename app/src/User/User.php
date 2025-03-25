<?php

namespace App\User;

use App\ExperienceDatabase\Experience;
use App\ExperienceDatabase\ExperienceLocation;
use App\ExperienceDatabase\LogEntry;
use App\Helper\StatisticsHelper;
use App\Overview\StatisticsPage;
use App\Profile\FriendRequest;
use App\Profile\ProfilePage;
use App\User\AuthToken;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;

/**
 * Class \App\User\User
 *
 * @property string $Email
 * @property string $Username
 * @property string $Nickname
 * @property string $Password
 * @property bool $HasPremium
 * @property bool $LinkedLogging
 * @property string $ProfilePrivacy
 * @property string $DateOfBirth
 * @property int $AvatarID
 * @method \SilverStripe\Assets\Image Avatar()
 * @method \SilverStripe\ORM\DataList|\App\User\AuthToken[] AuthTokens()
 * @method \SilverStripe\ORM\ManyManyList|\App\ExperienceDatabase\ExperienceLocation[] FavouritePlaces()
 */
class User extends DataObject
{
    private static $db = [
        "Email" => "Varchar(255)",
        "Username" => "Varchar(255)", //The Username used to login
        "Nickname" => "Varchar(255)", //The Nickname visible to other users
        "Password" => "Varchar(255)",
        "HasPremium" => "Boolean",
        "LinkedLogging" => "Boolean",
        'ProfilePrivacy' => 'Enum("Public, Friends, Private", "Public")',
        'DateOfBirth' => 'Date',
    ];

    private static $has_one = [
        'Avatar' => Image::class,
    ];

    private static $has_many = [
        "AuthTokens" => AuthToken::class,
    ];

    private static $owns = [
        'Avatar',
    ];

    private static $many_many = [
        "FavouritePlaces" => ExperienceLocation::class,
        // "Friends" => FriendRequest::class,
    ];

    private static $default_sort = "Username ASC";

    private static $field_labels = [
        "Email" => "E-Mail",
        "Username" => "Username",
        "Nickname" => "Nickname",
        "Password" => "Password",
    ];

    private static $summary_fields = [
        "Username",
    ];

    private static $searchable_fields = [
        "Username"
    ];

    private static $table_name = "User";

    private static $singular_name = "User";
    private static $plural_name = "Users";

    private static $url_segment = "user";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        return $fields;
    }

    public function canView($member = null)
    {
        return true;
    }

    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_NewsAdmin', 'any', $member);
    }

    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_NewsAdmin', 'any', $member);
    }

    public function canCreate($member = null, $context = [])
    {
        return Permission::check('CMS_ACCESS_NewsAdmin', 'any', $member);
    }

    public function getTitle()
    {
        return $this->Nickname;
    }

    public function getProfileImage($size = 200)
    {
        if ($this->Avatar()->Fill($size, $size) != null) {
            return $this->Avatar()->Fill($size, $size)->Url;
        } else {
            return $this->getGravatar($size);
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
        $url .= md5(strtolower(trim($this->Email)));
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

    public function LogCount($id)
    {
        return LogEntry::get()->filter([
            'UserID' => $this->ID,
            'ExperienceID' => $id,
        ])->count();
    }

    public function getLogs($id = 0)
    {
        if ($id == 0) {
            $id = $this->ID;
        }
        return StatisticsHelper::getLogsOfUser($id);
    }

    //Berechne Logs pro Jahr für eine Experience
    public function getRideCounterPerYear($experienceId)
    {
        return StatisticsHelper::getRideCounterPerYear($this->ID, $experienceId);
    }

    //Berechne Besuche pro Jahr für eine Location
    public function getVisitCounterPerYear($locationID)
    {
        return StatisticsHelper::getVisitCounterPerYear($this->ID, $locationID);
    }

    public function getPaginatedLogs()
    {
        $logs = $this->getLogs($this->ID);
        $paginated = new PaginatedList($logs, $this->getRequest());
        $paginated->setPageLength(10);
        return $paginated;
    }

    public function getFriends()
    {
        $acceptedRequests = $this->Friends()->filter([
            "FriendshipStatus" => "Accepted",
        ]);
        $friends = new ArrayList();
        foreach ($acceptedRequests as $request) {
            if ($request->RequesterID == $this->ID) {
                $friends->push($request->Requestee());
            } else {
                $friends->push($request->Requester());
            }
        }
        return $friends;
    }

    public function getFriendRequests()
    {
        $acceptedRequests = $this->Friends()->filter([
            "FriendshipStatus" => "Pending",
        ]);
        $friends = new ArrayList();
        foreach ($acceptedRequests as $request) {
            if ($request->RequesterID == $this->ID) {
                //Not a friend request, but a request from the user
            } else {
                $friends->push($request);
            }
        }
        return $friends;
    }

    public function getPendingFriendRequests()
    {
        $acceptedRequests = $this->Friends()->filter([
            "FriendshipStatus" => "Pending",
        ]);
        $friends = new ArrayList();
        foreach ($acceptedRequests as $request) {
            if ($request->RequesterID == $this->ID) {
                $friends->push($request);
            } else {
                //Not a friend request from the user but from another user
            }
        }
        return $friends;
    }

    public function getLoggedParksCount()
    {
        //get a list of all logs of user with unique ExperienceIDs
        $userLogs = LogEntry::get()->filter([
            'UserID' => $this->ID,
        ])->columnUnique('ExperienceID');
        $uniqueParentIDs = [];
        foreach ($userLogs as $experienceID) {
            $experience = Experience::get()->byID($experienceID);
            if ($experience && $experience->ParentID) {
                $uniqueParentIDs[$experience->ParentID] = true; // Using associative array to keep track of unique ParentIDs
            }
        }

        $uniqueParentIDs = array_keys($uniqueParentIDs); // Get the unique ParentIDs

        return count($uniqueParentIDs);
    }

    public function getLoggedDefunctExperiencesPerPark($id)
    {
        $userLogs = LogEntry::get()->filter([
            'UserID' => $this->ID,
        ]);
        $defunctExperiences = [];
        foreach ($userLogs as $log) {
            $experience = Experience::get()->byID($log->ExperienceID);
            if ($experience && $experience->ParentID == $id && $experience->State == "Defunct") {
                $defunctExperiences[] = $experience;
            }
        }
        return $defunctExperiences;
    }

    public function IsFriendWithCurrentUser()
    {
        $acceptedRequests = $this->Friends()->filter([
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

    public function getFriendshipWithCurrentUser()
    {
        $currentUser = Security::getCurrentUser();

        $friendRequest = FriendRequest::get()->filter([
            "RequesterID" => $this->ID,
            "RequesteeID" => $currentUser->ID,
        ])->first();
        if (!$friendRequest) {
            $friendRequest = FriendRequest::get()->filter([
                "RequesterID" => $currentUser->ID,
                "RequesteeID" => $this->ID,
            ])->first();
        }

        if ($friendRequest) {
            return $friendRequest;
        }
    }

    public function getFriendStatusToCurrentUser()
    {
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            //First check incoming
            $friendRequest = FriendRequest::get()->filter([
                "RequesterID" => $this->ID,
                "RequesteeID" => $currentUser->ID,
            ])->first();
            if ($friendRequest) {
                if ($friendRequest->FriendshipStatus == "Pending") {
                    return "IncomingPending";
                } else {
                    return "Accepted";
                }
            }

            //Then check outgoing
            if (!$friendRequest) {
                $friendRequest = FriendRequest::get()->filter([
                    "RequesterID" => $currentUser->ID,
                    "RequesteeID" => $this->ID,
                ])->first();
            }
            if ($friendRequest) {
                if ($friendRequest->FriendshipStatus == "Pending") {
                    return "OutgoingPending";
                } else {
                    return "Accepted";
                }
            }
        }
        return "NotFriends";
    }

    public function getCoasterCount()
    {
        $userID = $this->ID;
        return StatisticsHelper::getCounts($userID);
    }

    public function getStatisticsLink()
    {
        $statisticsPage = StatisticsPage::get()->first();
        if ($statisticsPage) {
            return $statisticsPage->Link("user/" . $this->Nickname);
        }
    }

    public function getProfileLink()
    {
        $profilePage = ProfilePage::get()->first();
        return $profilePage->Link("user/" . $this->Nickname);
    }
}
