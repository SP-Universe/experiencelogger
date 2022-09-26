<?php

namespace App\ExperienceDatabase;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;

/**
 * Class \App\Database\ExperienceSeat
 *
 * @property string $Title
 * @property string $PluralName
 * @property int $IconID
 * @method \SilverStripe\Assets\Image Icon()
 */
class ExperienceDataType extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "PluralName" => "Varchar(255)",
    ];

    private static $belongs_many = [
        "ExperienceData" => ExperienceData::class,
    ];

    private static $api_access = true;

    private static $default_sort = "Title ASC";

    private static $field_labels = [
        "Title" => "Singular Name",
        "PluralName" => "Plural Name",
    ];

    private static $has_one = [
        "Icon" => Image::class
    ];

    private static $summary_fields = [
        "Title" => "Name",
    ];

    private static $searchable_fields = [
        "Title", "PluralName"
    ];

    private static $table_name = "ExperienceDataType";

    private static $singular_name = "Data Type";
    private static $plural_name = "Data Types";

    private static $url_segment = "datatype";

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
