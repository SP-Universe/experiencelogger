<?php
namespace App\Extensions;

use App\ExperienceDatabase\Experience;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\Security\Member;
use SilverStripe\ORM\DataExtension;
use App\ExperienceDatabase\LogEntry;
use App\ExperienceDatabase\ExperienceLocation;
use DateTime;

/**
 * Class \App\Extensions\ExperienceMemberExtension
 *
 * @property \SilverStripe\Security\Member|\App\Extensions\ExperienceMemberExtension $owner
 * @property string $DateOfBirth
 * @property string $Nickname
 * @property string $ProfilePrivacy
 * @property bool $LinkedLogging
 * @property string $LastLogDate
 * @property int $AvatarID
 * @property int $LastLoggedAreaID
 * @method \SilverStripe\Assets\Image Avatar()
 * @method \App\ExperienceDatabase\Experience LastLoggedArea()
 * @method \SilverStripe\ORM\ManyManyList|\App\ExperienceDatabase\ExperienceLocation[] FavouritePlaces()
 * @method \SilverStripe\ORM\ManyManyList|\SilverStripe\Security\Member[] Friends()
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
        "Friends" => Member::class,
    ];

    private static $belongs_many = [
        "LogEntries" => LogEntry::class,
        "Friends" => Member::class,
    ];

    private static $searchable_fields = [
        "Nickname",
    ];

    public function LogCount($id)
    {
        return LogEntry::get()->filter([
            'UserID' => $this->owner->ID,
            'ExperienceID' => $id,
        ])->count();
    }

    public function getLogs($id)
    {
        $checkedUser = Member::get()->byID($id);
        if ($checkedUser) {
            return LogEntry::get()->filter([
                'UserID' => $checkedUser->ID,
            ]);
        }
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
}
