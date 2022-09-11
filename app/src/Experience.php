<?php

namespace App\Docs;

use SilverStripe\Assets\File;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

/**
 * Class \App\Database\Experience
 *
 * @property string $Title
 * @property string $Type
 * @property string $Description
 * @property int $SortOrder
 * @property int $LayoutSVGID
 * @property int $ImageID
 * @property int $LocationID
 * @method \SilverStripe\Assets\File LayoutSVG()
 * @method \SilverStripe\Assets\Image Image()
 * @method \App\Docs\Location Location()
 * @method \SilverStripe\ORM\ManyManyList|\App\Docs\ExperienceData[] ExperienceData()
 * @method \SilverStripe\ORM\ManyManyList|\App\Docs\ExperienceSeat[] ExperienceSeats()
 */
class Experience extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "Type" => "Enum('Coaster, Flatride, Waterride, Trackride, Show, Walkthrough, Convention, Scare House, Escaperoom, Other', 'Other')",
        "Description" => "HTMLText",
        "SortOrder" => "Int",
    ];

    private static $has_one = [
        "LayoutSVG" => File::class,
        "Image" => Image::class,
        "Location" => Location::class,
    ];

    private static $many_many = [
        "ExperienceData" => ExperienceData::class,
        "ExperienceSeats" => ExperienceSeat::class,
    ];

    private static $owns = [
        "Image",
        "LayoutSVG",
        "ExperienceData",
        "ExperienceSeats",
    ];

    private static $default_sort = "SortOrder ASC";

    private static $field_labels = [
        "Title" => "Titel",
        "Type" => "Typ",
        "Description" => "Beschreibung",
        "LayoutSVG" => "Sitz-Layout",
        "Image" => "Bild",
        "Location" => "Standort",
        "Seats" => "Sitze",
        "Data" => "Daten",
    ];

    private static $table_name = "Experience";

    private static $singular_name = "Experience";
    private static $plural_name = "Experiences";

    private static $url_segment = "experience";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName("SortOrder");
        $fields->removeByName("ExperienceData");

        $gridFieldConfig = GridFieldConfig_RecordEditor::create(200);
        $sorter = new GridFieldSortableRows('SortOrder');
        $gridFieldConfig->addComponent($sorter);
        $gridfield = new GridField("ExperienceData", "Infos", $this->ExperienceData(), $gridFieldConfig);
        $fields->addFieldToTab('Root.Infos', $gridfield);

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
