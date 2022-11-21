<?php

namespace App\ExperienceDatabase;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;

/**
 * Class \App\Database\ExperienceSeat
 *
 * @property string $Train
 * @property string $Wagon
 * @property string $Row
 * @property string $Seat
 * @property string $Info
 * @property string $Type
 * @property int $ParentID
 * @method \App\ExperienceDatabase\Experience Parent()
 */
class ExperienceSeat extends DataObject
{
    private static $db = [
        "Train" => "Varchar(255)",
        "Wagon" => "Varchar(255)",
        "Row" => "Varchar(255)",
        "Seat" => "Varchar(255)",
        "Info" => "Varchar(255)",
        "Type" => "Enum('Standard, XXL, Small, Long','Standard')",
    ];

    private static $api_access = true;

    private static $has_one = [
        "Parent" => Experience::class
    ];

    private static $default_sort = "Train ASC, Wagon ASC, Row ASC, Seat ASC";

    private static $field_labels = [
        "Row" => "Row",
        "Seat" => "Seat",
        "Wagon" => "Wagon",
        "Train" => "Train",
    ];

    private static $summary_fields = [
        "Train" => "Train",
        "Wagon" => "Wagon",
        "Row" => "Row",
        "Seat" => "Seat",
    ];

    private static $searchable_fields = [
        "Train", "Wagon", "Row", "Seat"
    ];

    private static $table_name = "ExperienceSeat";

    private static $singular_name = "Seat";
    private static $plural_name = "Seats";

    private static $url_segment = "seat";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName("ParentID");
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
