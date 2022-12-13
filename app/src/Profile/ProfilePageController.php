<?php
namespace App\Profile;

//use jamesbolitho\frontenduploadfield\UploadField;
use PageController;
use SilverStripe\Forms\Form;
use SilverStripe\Assets\File;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\GroupedList;
use SilverStripe\Forms\FormAction;
use SilverStripe\Security\Security;
use App\ExperienceDatabase\LogEntry;
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
            /*$upload = UploadField::create('avatar', 'Avatar');
            $upload->setFolderName('avatars');
            $upload->getValidator()->setAllowedExtensions(array('png','jpg','jpeg','gif'));
            $sizeMB = 1; // 1 MB
            $size = $sizeMB * 1024 * 1024; // 1 MB in bytes
            $upload->getValidator()->setAllowedMaxFileSize($size);
            $upload->setRemoveFiles(true);*/

            $upload = new UploadField('avatar', 'Avatar');
            $textfieldNickname = new TextField("nickname", "Nickname");
            $textfieldNickname->setValue($currentUser->Nickname);
            $textFieldFirstName = new TextField("firstname", "First Name");
            $textFieldFirstName->setValue($currentUser->FirstName);
            $textFieldLastName = new TextField("lastname", "Last Name");
            $textFieldLastName->setValue($currentUser->LastName);
            $textFieldEmail = new TextField("email", "Email");
            $textFieldEmail->setValue($currentUser->Email);
            $dateFieldBirthdate = new DateField("birthdate", "Birthdate");
            $dateFieldBirthdate->setValue($currentUser->DateOfBirth);
            $dropdownFieldProfilePrivacy = new DropdownField("profileprivacy", "Profile Privacy", array(
                "public" => "Public",
                "friends" => "Friends Only",
                "private" => "Private"
            ));
            $dropdownFieldProfilePrivacy->setValue($currentUser->ProfilePrivacy);

            $fields = new FieldList(
                $upload,
                $textfieldNickname,
                $textFieldFirstName,
                $textFieldLastName,
                $textFieldEmail,
                $dateFieldBirthdate,
                $dropdownFieldProfilePrivacy
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
            if (isset($data["avatar"]) && $data["avatar"] != "") {
                $content = file_get_contents($data["avatar"]);
                $file = File::create();
                $file->setFromString($content, $data["avatar"]);
                $file->write();
                $currentUser->AvatarID = $file->ID;
            }
            if (isset($data["nickname"]) && $data["nickname"] != "") {
                $currentUser->Nickname = $data["nickname"];
            }
            if (isset($data["firstname"]) && $data["firstname"] != "") {
                $currentUser->FirstName = $data["firstname"];
            }
            if (isset($data["lastname"]) && $data["lastname"] != "") {
                $currentUser->LastName = $data["lastname"];
            }
            if (isset($data["email"]) && $data["email"] != "") {
                $currentUser->Email = $data["email"];
            }
            if (isset($data["birthdate"]) && $data["birthdate"] != "") {
                $currentUser->DateOfBirth = $data["birthdate"];
            }
            if (isset($data["profileprivacy"]) && $data["profileprivacy"] != "") {
                $currentUser->ProfilePrivacy = $data["profileprivacy"];
            }

            $currentUser->write();
        }
        return $this->redirectBack();
    }
}
