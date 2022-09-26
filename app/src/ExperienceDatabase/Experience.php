<?php

namespace App\ExperienceDatabase;

use SilverStripe\Assets\File;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Security\Permission;

/**
 * Class \App\Database\Experience
 *
 * @property string $Title
 * @property string $State
 * @property string $Description
 * @property int $LayoutSVGID
 * @property int $ImageID
 * @property int $ParentID
 * @property int $TypeID
 * @method \SilverStripe\Assets\File LayoutSVG()
 * @method \SilverStripe\Assets\Image Image()
 * @method \App\ExperienceDatabase\ExperienceLocation Parent()
 * @method \App\ExperienceDatabase\ExperienceType Type()
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\ExperienceData[] ExperienceData()
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\ExperienceSeat[] ExperienceSeats()
 */
class Experience extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "State" => "Enum('Active, Defunct, In Maintenance, Other', 'Active')",
        "Description" => "HTMLText",
    ];

    private static $api_access = ['view' => ['Title', 'Type.SingularName', 'State', 'Description', 'ExperienceImage', 'ParentID']];

    private static $has_one = [
        "Image" => Image::class,
        "Parent" => ExperienceLocation::class,
        "Type" => ExperienceType::class,
        "LayoutSVG" => File::class,
    ];

    private static $has_many = [
        "ExperienceData" => ExperienceData::class,
        "ExperienceSeats" => ExperienceSeat::class,
    ];

    private static $owns = [
        "Image",
        "LayoutSVG",
        "ExperienceData",
        "ExperienceSeats",
    ];

    private static $summary_fields = [
        "Title" => "Titel",
        "Type.Title" => "Typ",
        "State" => "Status",
        "Parent.Title" => "Location",
    ];

    private static $field_labels = [
        "Title" => "Titel",
        "Type.Title" => "Typ",
        "State" => "Status",
        "Description" => "Beschreibung",
        "LayoutSVG" => "Sitz-Layout",
        "Image" => "Bild",
        "Parent.Title" => "Ort",
    ];

    private static $default_sort = "State ASC, Title ASC";

    private static $table_name = "Experience";

    private static $singular_name = "Experience";
    private static $plural_name = "Experiences";

    private static $url_segment = "experience";

    public function getExperienceImage()
    {
        return $this->Image()->exists() ? $this->Image()->getAbsoluteURL() : null;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName("ParentID");
        $fields->insertAfter('Title', new DropdownField('TypeID', 'Type', ExperienceType::get()->map('ID', 'Title')));

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
