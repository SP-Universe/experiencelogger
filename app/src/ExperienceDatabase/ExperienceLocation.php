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
 * @property int $IconID
 * @method \SilverStripe\Assets\Image Image()
 * @method \SilverStripe\Assets\Image Icon()
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
        "Icon" => Image::class,
    ];

    private static $owns = [
        "Experiences",
        "Image",
        "Icon",
    ];

    private static $api_access = ['view' => ['Title', 'Type', 'OpeningDate', 'Address', 'Description', 'Experiences', 'LocationImage', 'LocationIcon']];

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

    public function getLocationImage()
    {
        return $this->Image()->exists() ? $this->Image()->getAbsoluteURL() : null;
    }

    public function getLocationIcon()
    {
        return $this->Icon()->exists() ? $this->Icon()->getAbsoluteURL() : null;
    }

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
