<?php
namespace App\Extensions;

use SilverStripe\ORM\DataExtension;
use App\ExperienceDatabase\ExperienceLocation;
use App\ExperienceDatabase\LogEntry;

/**
 * Class \App\Extensions\ExperienceMemberExtension
 *
 * @property \SilverStripe\Security\Member|\App\Extensions\ExperienceMemberExtension $owner
 * @property string $DateOfBirth
 * @property string $Nickname
 * @method \SilverStripe\ORM\ManyManyList|\App\ExperienceDatabase\ExperienceLocation[] FavouritePlaces()
 */
class ExperienceMemberExtension extends DataExtension
{
    // define additional properties
    private static $db = [
        'DateOfBirth' => 'Date',
        'Nickname' => 'Varchar(255)',
    ];

    private static $many_many = [
        "FavouritePlaces" => ExperienceLocation::class,
    ];

    private static $belongs_many = [
        "LogEntries" => LogEntry::class,
    ];

    public function LogCount($id)
    {
        return LogEntry::get()->filter([
            'UserID' => $this->owner->ID,
            'ExperienceID' => $id,
        ])->count();
    }
}
