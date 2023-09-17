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
 * @property string $Phone
 * @property string $Email
 * @property int $Timezone
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
        "Timezone" => "Int",
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

        $fields->replaceField('Timezone', new DropdownField('Timezone', 'Timezone', [
            "-12" => "UTC-12 (USA, Baker Island)",
            "-11" => "UTC-11 (USA, American Samoa)",
            "-10" => "UTC-10 (USA, Hawaii)",
            "-9" => "UTC-9 (USA, Alaska)",
            "-8" => "UTC-8 (USA, Pacific)",
            "-7" => "UTC-7 (USA, Mountain)",
            "-6" => "UTC-6 (USA, Central)",
            "-5" => "UTC-5 (USA, Eastern)",
            "-4" => "UTC-4 (USA, Atlantic)",
            "-3" => "UTC-3 (USA, Brazil)",
            "-2" => "UTC-2 (USA, Brazil)",
            "-1" => "UTC-1 (USA, Brazil)",
            "0" => "UTC",
            "1" => "UTC+1 (Europe, Germany)",
            "2" => "UTC+2 (Europe, Greece)",
            "3" => "UTC+3 (East Afrika)",
            "4" => "UTC+4 (Europe, Russia)",
            "5" => "UTC+5 (Europe, Russia)",
            "6" => "UTC+6 (Europe, Russia)",
            "7" => "UTC+7 (Asia, Thailand)",
            "8" => "UTC+8 (Asia, China)",
            "9" => "UTC+9 (Asia, Japan)",
            "10" => "UTC+10 (Australia, Sydney)",
            "11" => "UTC+11 (Australia, Sydney)",
            "12" => "UTC+12 (New Zealand)",
        ]));

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

    public function getGroupedExperiencesByState()
    {
        $characterType = ExperienceType::get()->find('Title', 'Character');
        return GroupedList::create($this->Experiences()->Filter("TypeID:not", $characterType->ID))->GroupedBy("State");
    }

    public function getGroupedCharacters()
    {
        $characterType = ExperienceType::get()->find('Title', 'Character');
        return GroupedList::create($this->Experiences()->Filter("TypeID", $characterType->ID))->GroupedBy("State");
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

    public function getLocationProgress()
    {
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            $completed = 0;
            foreach ($this->Experiences() as $experience) {
                if ($experience->getIsCompletedByUser($currentUser)) {
                    $completed++;
                }
            }
            return $completed;
        }
        return "?";
    }

    public function getLocationProgressInPercent()
    {
        $total = $this->Experiences()->count();
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            $completed = 0;
            foreach ($this->Experiences() as $experience) {
                if ($experience->getIsCompletedByUser()) {
                    $completed++;
                }
            }
            if ($total > 0) {
                return (100 / $total) * $completed;
            }
        }
        return 0;
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
