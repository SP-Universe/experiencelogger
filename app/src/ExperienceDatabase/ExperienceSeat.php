<?php

namespace App\ExperienceDatabase;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;

/**
 * Class \App\Database\ExperienceSeat
 *
 * @property string $Row
 * @property string $Seat
 * @property int $Coord1
 * @property int $Coord2
 * @property int $ParentID
 * @method \App\ExperienceDatabase\Experience Parent()
 */
class ExperienceSeat extends DataObject
{
    private static $db = [
        "Row" => "Varchar(255)",
        "Seat" => "Varchar(255)",
        "Wagon" => "Varchar(255)",
        "Train" => "Varchar(255)",
        "Coord1" => "Int",
        "Coord2" => "Int",
        "Info" => "Varchar(255)",
    ];

    private static $api_access = true;

    private static $has_one = [
        "Parent" => Experience::class
    ];

    private static $default_sort = "Train ASC, Wagon ASC, Row ASC, Seat ASC";

    private static $field_labels = [
        "Row" => "Reihe",
        "Seat" => "Sitz",
        "Wagon" => "Wagen",
        "Train" => "Zug",
        "Coord1" => "X-Koordinate",
        "Coord2" => "Y-Koordinate",
    ];

    private static $summary_fields = [
        "Train" => "Zug",
        "Wagon" => "Wagen",
        "Row" => "Reihe",
        "Seat" => "Sitz",
    ];

    private static $searchable_fields = [
        "Train", "Wagon", "Row", "Seat"
    ];

    private static $table_name = "ExperienceSeat";

    private static $singular_name = "Sitzplatz";
    private static $plural_name = "Sitzplätze";

    private static $url_segment = "seat";

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
}
