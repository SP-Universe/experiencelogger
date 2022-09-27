<?php

namespace App\ExperienceDatabase;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Security\Permission;

/**
 * Class \App\Database\Location
 *
 * @property string $Title
 * @property string $OpeningDate
 * @property string $Address
 * @property string $Description
 * @property int $TypeID
 * @property int $ImageID
 * @property int $IconID
 * @method \App\ExperienceDatabase\ExperienceLocationType Type()
 * @method \SilverStripe\Assets\Image Image()
 * @method \SilverStripe\Assets\Image Icon()
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\Experience[] Experiences()
 */
class ExperienceLocation extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "OpeningDate" => "Date",
        "Address" => "Varchar(255)",
        "Description" => "HTMLText",
    ];

    private static $has_many = [
        "Experiences" => Experience::class,
    ];

    private static $has_one = [
        "Type" => ExperienceLocationType::class,
        "Image" => Image::class,
        "Icon" => Image::class,
    ];

    private static $owns = [
        "Experiences",
        "Image",
        "Icon",
    ];

    private static $api_access = ['view' => ['Title', 'LocationType', 'OpeningDate', 'Address', 'Description', 'Experiences', 'LocationImage', 'LocationIcon']];

    private static $default_sort = "Title ASC";

    private static $field_labels = [
        "Title" => "Title",
        "Type.Title" => "Type",
        "OpeningDate" => "Opening Date",
        "Address" => "Adress",
        "Description" => "Description",
    ];

    private static $summary_fields = [
        "Title" => "Title",
        "LocationType" => "Type",
        "Address" => "Adress",
    ];

    private static $searchable_fields = [
        "Title", "Type.Title", "Description", "Experiences.Title"
    ];

    private static $table_name = "ExperienceLocation";

    private static $singular_name = "Location";
    private static $plural_name = "Locations";

    private static $url_segment = "location";

    public function getLocationImage()
    {
        return $this->Image()->exists() ? $this->Image()->getAbsoluteURL() : null;
    }

    public function getLocationIcon()
    {
        return $this->Icon()->exists() ? $this->Icon()->getAbsoluteURL() : null;
    }

    public function getLocationType()
    {
        return $this->Type()->exists() ? $this->Type()->Title : null;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->insertAfter('Title', new DropdownField('TypeID', 'Type', ExperienceLocationType::get()->map('ID', 'Title')));
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
