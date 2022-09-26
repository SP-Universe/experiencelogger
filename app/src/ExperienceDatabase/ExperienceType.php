<?php

namespace App\ExperienceDatabase;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;

/**
 * Class \App\Database\ExperienceSeat
 *
 * @property string $SingularName
 * @property string $PluralName
 * @property int $IconID
 * @method \SilverStripe\Assets\Image Icon()
 */
class ExperienceType extends DataObject
{
    private static $db = [
        "SingularName" => "Varchar(255)",
        "PluralName" => "Varchar(255)",
    ];

    private static $api_access = true;

    private static $default_sort = "SingularName ASC";

    private static $field_labels = [
        "SingularName" => "Singular Name",
        "PluralName" => "Plural Name",
    ];

    private static $has_one = [
        "Icon" => Image::class
    ];

    private static $summary_fields = [
        "SingularName" => "Name",
    ];

    private static $searchable_fields = [
        "SingularName", "PluralName"
    ];

    private static $table_name = "ExperienceType";

    private static $singular_name = "Experience Type";
    private static $plural_name = "Experience Types";

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
