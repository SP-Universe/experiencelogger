<?php
namespace App\Extensions;

use App\ExperienceDatabase\ExperienceLocation;
use SilverStripe\ORM\DataExtension;

/**
 * Class \App\Extensions\ExperienceMemberExtension
 *
 * @property \SilverStripe\Security\Member|\App\Extensions\ExperienceMemberExtension $owner
 * @property string $Birthdate
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\ExperienceLocation[] FavoritePlaces()
 */
class ExperienceMemberExtension extends DataExtension
{
    // define additional properties
    private static $db = [
        "Birthdate" => "Date",
    ];
    private static $has_one = [];
    private static $has_many = [
        "FavoritePlaces" => ExperienceLocation::class,
    ];
    private static $many_many = [];
    private static $belongs_many_many = [];

    public function somethingElse()
    {
        // You can add any other methods you like, which you can call directly on the member object.
    }
}
