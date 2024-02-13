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
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;

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

    public function index()
    {
        return $this->redirect("profile/user/" . $this->getNickname());
    }

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

    public function EditForm()
    {
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            $upload = new FileField('avatar', 'Avatar');
            $textfieldNickname = new TextField("Nickname", "Nickname");
            $textfieldNickname->setValue($currentUser->Nickname);
            $textFieldFirstName = new TextField("FirstName", "First Name");
            $textFieldFirstName->setValue($currentUser->FirstName);
            $textFieldLastName = new TextField("LastName", "Last Name");
            $textFieldLastName->setValue($currentUser->LastName);
            $textFieldEmail = new TextField("Email", "Email");
            $textFieldEmail->setValue($currentUser->Email);
            $dateFieldBirthdate = new DateField("DateOfBirth", "Birthdate");
            $dateFieldBirthdate->setValue($currentUser->DateOfBirth);
            $dropdownFieldProfilePrivacy = new DropdownField("ProfilePrivacy", "Profile Privacy", array(
                "public" => "Public",
                "friends" => "Friends Only",
                "private" => "Private"
            ));
            $dropdownFieldProfilePrivacy->setValue($currentUser->ProfilePrivacy);
            $dropdownFieldLinkedLogging = new CheckboxField("LinkedLogging", "Linked Logging");
            $dropdownFieldLinkedLogging->setValue($currentUser->LinkedLogging);

            $fields = new FieldList(
                $upload,
                $textfieldNickname,
                $textFieldFirstName,
                $textFieldLastName,
                $textFieldEmail,
                $dateFieldBirthdate,
                $dropdownFieldProfilePrivacy,
                $dropdownFieldLinkedLogging
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
        $currentUser = Security::getCurrentUser();
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
        $members = Member::get();
        $memberlist = PaginatedList::create($members, $this->getRequest());
        $memberlist->setPageLength(30);
        return array(
            'MemberList' => $memberlist
        );
    }

    public function user()
    {
        $currentUser = Security::getCurrentUser();
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
        }
    }

    public function requestnewfriend()
    {
        $currentUser = Security::getCurrentUser();
        $requestee = Member::get()->filter("Nickname", $this->getRequest()->param("ID"))->first();
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
        $currentUser = Security::getCurrentUser();
        $friendRequest = FriendRequest::get()->byID($this->getRequest()->param("ID"));
        $requestee = Member::get()->byID($friendRequest->RequesteeID);
        $requester = Member::get()->byID($friendRequest->RequesterID);

        if ($friendRequest && $currentUser && $requestee && $requester) {
            if ($requestee == $currentUser && $friendRequest->FriendshipStatus == "Pending") {
                $requesterFriendRequest = $requester->Friends()->filter("RequesteeID", $requestee->ID)->first();
                $requesterFriendRequest->FriendshipStatus = "accepted";
                $friendRequest->FriendshipStatus = "accepted";
                $requesterFriendRequest->write();
                $friendRequest->write();
                return $this->redirect("profile/");
            }
        }
    }

    public function declinefriend()
    {
        $currentUser = Security::getCurrentUser();
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
