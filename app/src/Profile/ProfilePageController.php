<?php
namespace App\Profile;

//use jamesbolitho\frontenduploadfield\UploadField;
use PageController;
use SilverStripe\Forms\Form;
use SilverStripe\Assets\File;
use App\Overview\StatisticsPage;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FileField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\GroupedList;
use SilverStripe\Forms\FormAction;
use SilverStripe\Security\Security;
use App\ExperienceDatabase\LogEntry;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\AssetAdmin\Forms\UploadField;

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
        'editProfile'
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
}
