<?php

namespace App\ExperienceDatabase;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;

/**
 * Class \App\Database\Location
 *
 * @property string $Title
 * @property string $Type
 * @property string $OpeningDate
 * @property string $Address
 * @property string $Description
 * @property int $ImageID
 * @method \SilverStripe\Assets\Image Image()
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\Experience[] Experiences()
 */
class ExperienceLocation extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "Type" => "Enum('Themepark, Escaperoom, Roadside-Attraction, ScareHouse, Water Park, Convention, Other', 'Other')",
        "OpeningDate" => "Date",
        "Address" => "Varchar(255)",
        "Description" => "HTMLText",
    ];

    private static $has_many = [
        "Experiences" => Experience::class,
    ];

    private static $has_one = [
        "Image" => Image::class,
    ];

    private static $owns = [
        "Experiences",
        "Image"
    ];

    private static $api_access = true;

    private static $default_sort = "Type ASC, Title ASC";

    private static $field_labels = [
        "Title" => "Titel",
        "Type" => "Typ",
        "OpeningDate" => "Eröffnung",
        "Address" => "Adresse",
        "Description" => "Beschreibung",
    ];

    private static $summary_fields = [
        "Title" => "Titel",
        "Type" => "Typ",
        "Address" => "Adresse",
    ];

    private static $searchable_fields = [
        "Title", "Type", "Description", "Experiences.Title"
    ];

    private static $table_name = "ExperienceLocation";

    private static $singular_name = "Ort";
    private static $plural_name = "Orte";

    private static $url_segment = "location";

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

    public function getFormattedName()
    {
        return str_replace(' ', '_', $this->Title);
    }
}
