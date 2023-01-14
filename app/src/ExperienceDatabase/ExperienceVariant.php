<?php

namespace App\ExperienceDatabase;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Security\Permission;

/**
 * Class \App\Database\ExperienceData
 *
 * @property string $Title
 * @property string $Description
 * @property int $SortOrder
 * @property int $ParentID
 * @method \App\ExperienceDatabase\Experience Parent()
 */
class ExperienceVariant extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "Description" => "HTMLText",
        "SortOrder" => "Int",
        "Defunct" => "Boolean",
    ];

    private static $has_one = [
        "Parent" => Experience::class,
    ];

    private static $api_access = true;

    private static $default_sort = "SortOrder ASC";

    private static $inline_editable = false;

    private static $field_labels = [
        "Title" => "Title",
        "Description" => "Description",
    ];

    private static $summary_fields = [
        "Title" => "Title",
    ];

    private static $searchable_fields = [
        "Title",
    ];

    private static $table_name = "ExperienceVariant";

    private static $singular_name = "Variant";
    private static $plural_name = "Variants";

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
