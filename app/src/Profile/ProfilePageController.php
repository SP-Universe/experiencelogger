<?php

namespace App\Profile;

//use jamesbolitho\frontenduploadfield\UploadField;
use PageController;
use SilverStripe\ORM\DB;
use SilverStripe\Forms\Form;
use SilverStripe\Assets\File;
use App\Profile\FriendRequest;
use SilverStripe\ORM\ArrayList;
use App\Overview\StatisticsPage;
use SilverStripe\ORM\DataObject;
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
use App\User\User;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use HudhaifaS\Forms\FrontendImageField;
use SilverStripe\ORM\Queries\SQLSelect;
use SilverStripe\ORM\Connect\MySQLQuery;
use SilverStripe\Control\Middleware\HTTPCacheControlMiddleware;

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
        'EditForm',
        'editProfile',
        'memberlist',
        'user',
        'acceptfriend',
        'declinefriend',
        'requestnewfriend'
    ];

    public function init()
    {
        HTTPCacheControlMiddleware::singleton()
            ->enableCache()
            // 1 minute
            ->setMaxAge(60);

        parent::init();
    }

    public function index()
    {
        return $this->redirect("profile/user/" . $this->getNickname());
    }

    public function getNickname()
    {
        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            return;
        }
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
        if ($currentUser) {
            return $currentUser->Nickname;
        }
    }

    public function getBirthdate()
    {
        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            return;
        }
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
        if ($currentUser) {
            return $currentUser->DateOfBirth;
        }
    }

    public function getFriends()
    {
        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            return;
        }
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
        if ($currentUser) {
            return $currentUser->Friends();
        }
    }

    public function getFavouritePlaces()
    {
        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            return;
        }
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
        if ($currentUser) {
            return $currentUser->FavouritePlaces();
        }
    }

    public function getLogs()
    {
        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            return;
        }
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
        if ($currentUser) {
            return GroupedList::create(LogEntry::get()->filter("UserID", $currentUser->ID)->sort("VisitTime", "DESC"));
        }
    }

    public function EditForm()
    {
        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            return;
        }
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
        if ($currentUser) {
            $upload = new FileField('Avatar', 'Avatar');
            $upload->setFolderName('user_avatars');
            $textfieldNickname = new TextField("Displayname", "Displayname");
            $textFieldFirstName = new TextField("FirstName", "First Name");
            $textFieldLastName = new TextField("Surname", "Last Name");
            $textFieldEmail = new TextField("Email", "Email");
            $dateFieldBirthdate = new DateField("DateOfBirth", "Birthdate");
            $dateFieldBirthdate->setAttribute("readonly", "readonly")->addExtraClass("readonly");
            $dropdownFieldProfilePrivacy = new DropdownField("ProfilePrivacy", "Profile Privacy", array(
                "public" => "Public",
                "friends" => "Friends Only",
                "private" => "Private"
            ));
            $dropdownFieldProfilePrivacy->setValue($currentUser->ProfilePrivacy);
            $checkboxFieldLinkedLogging = new CheckboxField("LinkedLogging", "Linked Logging");
            $checkboxFieldLinkedLogging->setValue($currentUser->LinkedLogging);

            $fields = new FieldList(
                $upload,
                $textfieldNickname,
                new TextField("FirstName", "First Name"),
                new TextField("Surname", "Last Name"),
                new TextField("Email", "Email"),
                new DateField("DateOfBirth", "Birthdate"),
                $dropdownFieldProfilePrivacy,
                $checkboxFieldLinkedLogging
            );

            $actions = new FieldList(
                new FormAction("editProfile", "Save")
            );

            $form = new Form($this, "EditForm", $fields, $actions);
            $form->loadDataFrom($currentUser);
            return $form;
        }
    }

    public function editProfile($data, Form $form)
    {
        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            return;
        }
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
        if ($currentUser) {
            $form->saveInto($currentUser);

            $currentUser->write();
        }
        return $this->redirect("profile/");
    }

    public function getStatisticsLink()
    {
        $statisticsPage = StatisticsPage::get()->first();
        if ($statisticsPage) {
            return $statisticsPage->Link("user/" . $this->getNickname());
        }
    }

    public function memberlist()
    {
        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            return;
        }
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();

        //Get all members
        /*$memberTable = DB::get_conn()->escapeIdentifier(
            DataObject::getSchema()->tableName(Member::class)
        );
        $sqlQuery = new SQLSelect();
        $sqlQuery->setFrom('Member');
        $sqlQuery->setSelect("*");
        $sqlQuery->addWhere([
            "{$memberTable}.\"Nickname\" != 'admin'",
            "{$memberTable}.\"ID\" != {$currentUser->ID}"
        ]);
        $sqlQuery->addLeftJoin(
            'FriendRequest',
            "\"FriendRequest\".\"RequesterID\" = \"Member\".\"ID\" OR \"FriendRequest\".\"RequesteeID\" = \"Member\".\"ID\"",
            'FriendRequest'
        );
        $sqlQuery->addLeftJoin(
            'LogEntry',
            "\"LogEntry\".\"UserID\" = \"Member\".\"ID\"",
            'LogEntry'
        );
        $result = $sqlQuery->execute();*/

        $members = Member::get()->filter(
            array(
                "ID:not" => $currentUser->ID,
                "Nickname:not" => "admin"
            )
        );

        /*$listOfMembers = new ArrayList();
        foreach ($result as $row) {
            $listOfMembers->push(Member::create($row));
            //print_r($row);
        }
        $paginatedList = new PaginatedList($listOfMembers, $this->getRequest());*/



        $memberlist = PaginatedList::create($members, $this->getRequest());
        $memberlist->setPageLength(30);
        return array(
            'MemberList' => $memberlist
        );
    }

    public function user()
    {
        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            return;
        }
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
        $viewedUser = Member::get()->filter("Nickname", $this->getRequest()->param("ID"))->first();

        if (!$viewedUser) {
            return array(
                "CurrentUser" => $currentUser
            );
        }

        if ($currentUser) {
            return array(
                "UserProfile" => $viewedUser,
                "CurrentUser" => $currentUser
            );
        } else {
            if ($viewedUser->ProfilePrivacy == "public") {
                return array(
                    "UserProfile" => $viewedUser
                );
            } else {
                return array(
                    "Error" => "You need to be logged in to view this page."
                );
            }
        }
    }

    public function requestnewfriend()
    {
        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            return;
        }
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
        $requestee = Member::get()->filter("ID", $this->getRequest()->param("ID"))->first();

        $existingFriendRequest = FriendRequest::get()->filter(array(
            "RequesteeID" => $currentUser->ID,
            "RequesterID" => $requestee->ID
        ))->first();
        if ($existingFriendRequest) {
            $existingFriendRequest->FriendshipStatus = "Accepted";
            $now = new \DateTime();
            $existingFriendRequest->FriendsSince = $now->format("Y-m-d H:i:s");
            $existingFriendRequest->write();
            return $this->redirect("profile/");
        }

        $friendRequest = new FriendRequest();
        $friendRequest->RequesterID = $currentUser->ID;
        $friendRequest->RequesteeID = $requestee->ID;
        $friendRequest->write();
        $currentUser->Friends()->add($friendRequest);
        $requestee->Friends()->add($friendRequest);
        return $this->redirect("profile/");
    }

    public function acceptfriend()
    {
        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            return;
        }
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
        $friendRequest = FriendRequest::get()->byID($this->getRequest()->param("ID"));
        $requestee = Member::get()->byID($friendRequest->RequesteeID);
        $requester = Member::get()->byID($friendRequest->RequesterID);

        if ($friendRequest && $currentUser && $requestee && $requester) {
            if ($requestee == $currentUser && $friendRequest->FriendshipStatus == "Pending") {
                $requesterFriendRequest = $requester->Friends()->filter("RequesteeID", $requestee->ID)->first();
                $requesterFriendRequest->FriendshipStatus = "Accepted";
                $friendRequest->FriendshipStatus = "Accepted";
                $now = new \DateTime();
                $friendRequest->FriendsSince = $now->format("Y-m-d H:i:s");
                $requesterFriendRequest->write();
                $friendRequest->write();
                return $this->redirect("profile/");
            }
        }
    }

    public function declinefriend()
    {
        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            return;
        }
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
        $friendRequest = FriendRequest::get()->byID($this->getRequest()->param("ID"));
        if ($friendRequest && $currentUser) {
            $requestee = Member::get()->byID($friendRequest->RequesteeID);
            $requester = Member::get()->byID($friendRequest->RequesterID);

            if ($friendRequest && $currentUser && $requestee && $requester) {
                if ($requestee == $currentUser || $requester == $currentUser) {
                    $requesterFriendRequest = $requester->Friends()->filter("RequesteeID", $requestee->ID)->first();
                    $requesteeFriendRequest = $requestee->Friends()->filter("RequesterID", $requester->ID)->first();
                    $requesterFriendRequest->delete();
                    $requesteeFriendRequest->delete();
                    return $this->redirect("profile/");
                }
            }
        } else {
            return $this->redirect("profile/");
        }
    }
}
