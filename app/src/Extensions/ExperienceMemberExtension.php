<?php
namespace App\Extensions;

use SilverStripe\Assets\Image;
use SilverStripe\Security\Member;
use SilverStripe\ORM\DataExtension;
use App\ExperienceDatabase\LogEntry;
use App\ExperienceDatabase\ExperienceLocation;

/**
 * Class \App\Extensions\ExperienceMemberExtension
 *
 * @property string $DateOfBirth
 * @property string $Nickname
 * @property string $ProfilePrivacy
 * @property int $AvatarID
 * @method \SilverStripe\Assets\Image Avatar()
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
    ];

    private static $has_one = [
        'Avatar' => Image::class,
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
}
