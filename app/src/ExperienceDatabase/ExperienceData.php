<?php

namespace App\ExperienceDatabase;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;

/**
 * Class \App\Database\ExperienceData
 *
 * @property string $Title
 * @property string $Type
 * @property string $Description
 * @property string $MoreInfo
 * @property string $Source
 * @property int $SortOrder
 * @property int $ParentID
 * @method \App\ExperienceDatabase\Experience Parent()
 */
class ExperienceData extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "Type" => "Enum('Opening Day, Closing Day, Manufacturer, Model, Max kmh, Max Gradient, Max Driving Time, Roomsize, Other', 'Other')",
        "Description" => "HTMLText",
        "MoreInfo" => "Varchar(255)",
        "Source" => "Varchar(255)",
        "SortOrder" => "Int",
    ];

    private static $api_access = true;

    private static $default_sort = "SortOrder ASC";

    private static $inline_editable = false;

    private static $field_labels = [
        "Title" => "Titel",
        "Type" => "Typ",
        "Description" => "Beschreibung",
        "MoreInfo" => "Weitere Informationen",
        "Source" => "Quelle",
    ];

    private static $has_one = [
        "Parent" => Experience::class
    ];

    private static $summary_fields = [
        "Title" => "Titel",
        "Type" => "Typ",
        "Description" => "Beschreibung",
    ];

    private static $searchable_fields = [
        "Title",
        "Type",
        "Description",
    ];

    private static $table_name = "ExperienceData";

    private static $singular_name = "Daten";
    private static $plural_name = "Daten";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeFieldFromTab("Root.Main", "ParentID");
        $fields->removeFieldFromTab("Root.Main", "SortOrder");
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
