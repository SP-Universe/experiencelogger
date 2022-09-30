<?php
namespace App\Extensions;

use SilverStripe\ORM\DataExtension;
use App\ExperienceDatabase\ExperienceLocation;

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

    public function LogCount($id)
    {
        return 31;
    }
}
