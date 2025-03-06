<?php

namespace App\User;

use App\User\AuthToken;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;

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
}
