<?php

namespace App\ExperienceDatabase;

use App\Overview\LocationPage;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\GroupedList;
use SilverStripe\Security\Security;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Security\Permission;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\View\Parsers\URLSegmentFilter;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;

/**
 * Class \App\Database\Location
 *
 * @property string $Title
 * @property string $LinkTitle
 * @property string $OpeningDate
 * @property string $Address
 * @property string $Description
 * @property string $Coordinates
 * @property string $Website
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
        "LinkTitle" => "Varchar(255)",
        "OpeningDate" => "Date",
        "Address" => "Varchar(255)",
        "Description" => "HTMLText",
        "Coordinates" => "Varchar(64)",
        "Website" => "Varchar(255)",
        "Phone" => "Varchar(255)",
        "Email" => "Varchar(255)",
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
        "LinkTitle" => "URL-Segment",
        "Type.Title" => "Type",
        "OpeningDate" => "Opening Date",
        "Address" => "Address",
        "Description" => "Description",
    ];

    private static $summary_fields = [
        "Title" => "Title",
        "Type.Title" => "Type",
        "Address" => "Address",
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

        $fields->removeByName("Experiences");
        $gridFieldConfig = GridFieldConfig_RecordEditor::create(200);
        $gridfield = new GridField("Experiences", "Experiences", $this->Experiences(), $gridFieldConfig);
        $fields->addFieldToTab('Root.Experiences', $gridfield);

        return $fields;
    }

    public function onBeforeWrite()
    {
        if ($this->LinkTitle == "") {
            $filter = URLSegmentFilter::create();
            $filteredTitle = $filter->filter($this->Title);
            $this->LinkTitle = $filteredTitle;
        }
        foreach ($this->Experiences() as $key => $value) {
            $value->write();
        }
        parent::onBeforeWrite();
    }

    //FUNCTIONS

    public function getIsFavourite()
    {
        $member = Security::getCurrentUser();
        if ($member) {
            return $member->FavouritePlaces()->find('ID', $this->ID) ? true : false;
        }
        return false;
    }

    public function getGroupedExperiences()
    {
        return GroupedList::create($this->Experiences())->GroupedBy("TypeID");
    }

    public function getLink()
    {
        $locationsHolder = LocationPage::get()->first();
        return $locationsHolder->Link("location/") . $this->LinkTitle;
    }

    public function getAbsoluteLink()
    {
        $locationsHolder = LocationPage::get()->first();
        return $locationsHolder->AbsoluteLink("location/") . $this->LinkTitle;
    }


    //PERMISSIONS
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
