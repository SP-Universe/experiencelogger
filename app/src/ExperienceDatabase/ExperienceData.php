<?php

namespace App\ExperienceDatabase;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Security\Permission;

/**
 * Class \App\Database\ExperienceData
 *
 * @property string $AlternativeTitle
 * @property string $Description
 * @property string $MoreInfo
 * @property string $Source
 * @property string $SourceLink
 * @property int $SortOrder
 * @property int $ParentID
 * @property int $TypeID
 * @method \App\ExperienceDatabase\Experience Parent()
 * @method \App\ExperienceDatabase\ExperienceDataType Type()
 */
class ExperienceData extends DataObject
{
    private static $db = [
        "AlternativeTitle" => "Varchar(255)",
        "Description" => "HTMLText",
        "MoreInfo" => "Varchar(255)",
        "Source" => "Varchar(255)",
        "SourceLink" => "Varchar(255)",
        "SortOrder" => "Int",
    ];

    private static $has_one = [
        "Parent" => Experience::class,
        "Type" => ExperienceDataType::class,
    ];

    private static $api_access = true;

    private static $default_sort = "SortOrder ASC";

    private static $inline_editable = false;

    private static $field_labels = [
        "AlternativeTitle" => "Alternative Title (optional)",
        "Type" => "Type",
        "Description" => "Description",
        "MoreInfo" => "More Information",
        "Source" => "Source",
    ];

    private static $summary_fields = [
        "Type.Title" => "Type",
        "AlternativeTitle" => "Alternative Title",
        "Description" => "Description",
    ];

    private static $searchable_fields = [
        "AlternativeTitle",
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

        $fields->insertBefore('AlternativeTitle', new DropdownField('TypeID', 'Type', ExperienceDataType::get()->map('ID', 'Title')));

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
