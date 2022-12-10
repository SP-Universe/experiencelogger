<?php

namespace App\ExperienceDatabase;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;
use App\ExperienceDatabase\ExperienceRow;

/**
 * Class \App\Database\ExperienceSeat
 *
 * @property string $Title
 * @property int $SortOrder
 * @property string $Type
 * @property int $ParentID
 * @method \App\ExperienceDatabase\ExperienceRow Parent()
 */
class ExperienceSeat extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "SortOrder" => "Int",
        "Type" => "Enum('Standard, XXL, Small, Long','Standard')",
    ];

    private static $api_access = true;

    private static $has_one = [
        "Parent" => ExperienceRow::class
    ];

    private static $default_sort = "SortOrder ASC, Title ASC";

    private static $field_labels = [
        "Title" => "Title",
        "SortOrder" => "SortOrder",
        "Type" => "Type",
    ];

    private static $summary_fields = [
        "Title" => "Title",
        "Type" => "Type",
    ];

    private static $searchable_fields = [
        "Title"
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
