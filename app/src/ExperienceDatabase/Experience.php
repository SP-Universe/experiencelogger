<?php

namespace App\ExperienceDatabase;

use SilverStripe\Assets\File;
use App\Overview\LocationPage;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\GroupedList;
use SilverStripe\Security\Security;
use Colymba\BulkManager\BulkManager;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Security\Permission;
use App\ExperienceDatabase\ExperienceTrain;
use SilverStripe\Forms\GridField\GridField;
use App\ExperienceDatabase\ExperienceLocation;
use SilverStripe\View\Parsers\URLSegmentFilter;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;
use SwiftDevLabs\DuplicateDataObject\Forms\GridField\GridFieldDuplicateAction;

/**
 * Class \App\Database\Experience
 *
 * @property string $Title
 * @property string $LinkTitle
 * @property string $State
 * @property string $Traintype
 * @property string $CustomTrainType
 * @property bool $HasGeneralSeats
 * @property bool $HasWagons
 * @property bool $HasRows
 * @property bool $HasSeats
 * @property bool $HasScore
 * @property bool $HasPodest
 * @property string $SeatOrientation
 * @property string $ExperienceLink
 * @property string $Description
 * @property string $Entrance
 * @property string $JSONCode
 * @property int $ImageID
 * @property int $ParentID
 * @property int $TypeID
 * @property int $AreaID
 * @method \SilverStripe\Assets\Image Image()
 * @method \App\ExperienceDatabase\ExperienceLocation Parent()
 * @method \App\ExperienceDatabase\ExperienceType Type()
 * @method \App\ExperienceDatabase\Experience Area()
 * @method \SilverStripe\ORM\DataList|\PurpleSpider\BasicGalleryExtension\PhotoGalleryImage[] PhotoGalleryImages()
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\ExperienceData[] ExperienceData()
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\ExperienceTrain[] ExperienceTrains()
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\ExperienceVariant[] Variants()
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\ExperienceVersion[] Versions()
 * @mixin \PurpleSpider\BasicGalleryExtension\PhotoGalleryExtension
 */
class Experience extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "LinkTitle" => "Varchar(255)",
        "State" => "Enum('Active, Defunct, In Maintenance, Coming Soon, Other', 'Active')",
        "Traintype" => "Enum('Train, None, Boat, Car, Airplane, Balloon, Pony, Gondola, Slide', 'Train')",
        "CustomTrainType" => "Varchar(255)",
        "HasGeneralSeats" => "Boolean",
        "HasWagons" => "Boolean",
        "HasRows" => "Boolean",
        "HasSeats" => "Boolean",
        "HasScore" => "Boolean",
        "HasPodest" => "Boolean",
        "SeatOrientation" => "Varchar(255)",
        "ExperienceLink" => "Varchar(255)",
        "Description" => "HTMLText",
        "Entrance" => "Enum('None, Left, Right', 'None')",
        "JSONCode" => "HTMLText",
    ];

    private static $api_access = ['view' => ['Title', 'ExperienceType', 'ExperienceArea', 'State', 'Description', 'ExperienceImage', 'ParentID']];

    private static $has_one = [
        "Image" => Image::class,
        "Parent" => ExperienceLocation::class,
        "Type" => ExperienceType::class,
        "Area" => Experience::class,
    ];

    private static $has_many = [
        "ExperienceData" => ExperienceData::class,
        "ExperienceTrains" => ExperienceTrain::class,
        "Variants" => ExperienceVariant::class,
        "Versions" => ExperienceVersion::class,
    ];

    private static $owns = [
        "Image",
        "ExperienceData",
        "ExperienceTrains",
    ];

    private static $summary_fields = [
        "ID" => "ID",
        "Title" => "Title",
        "Type.Title" => "Type",
        "Area.Title" => "Area",
        "State" => "Status",
    ];

    private static $field_labels = [
        "Title" => "Title",
        "ExperienceType" => "Type",
        "State" => "Status",
        "Description" => "Description",
        "LayoutSVG" => "Seat-Layout",
        "Image" => "Image",
        "Parent.Title" => "Location",
        "Area" => "Area",
        "HasScore" => "Has Score",
        "HasSeats" => "Has Seats",
        "HasTrains" => "Has Trains",
        "HasWagons" => "Has Wagons",
        "HasBoats" => "Has Boats",
        "LinkTitle" => "URL-Segment",
        "AllTrainsTheSame" => "All Trains have the same seats",
        "CustomTrainType" => "Custom Train Type Title",
    ];

    private static $default_sort = "State ASC, Title ASC, TypeID ASC, AreaID ASC";

    private static $table_name = "Experience";

    private static $singular_name = "Experience";
    private static $plural_name = "Experiences";

    private static $defaults = [
        "State" => "Active",
        "HasGeneralSeats" => true,
        "HasScore" => false,
        "HasPodest" => false,
        "HasWagons" => true,
        "HasRows" => true,
        "HasSeats" => true,
        "AllTrainsTheSame" => true,
    ];

    private static $url_segment = "experience";

    public function getExperienceImage()
    {
        return $this->Image()->exists() ? $this->Image()->getAbsoluteURL() : null;
    }

    public function getExperienceType()
    {
        return $this->Type()->exists() ? $this->Type()->Title : null;
    }

    public function getExperienceArea()
    {
        return $this->Area()->exists() ? $this->Area()->Title : null;
    }

    public function getLink()
    {
        $locationsHolder = LocationPage::get()->first();
        return $locationsHolder->Link("experience/") . $this->LinkTitle;
    }

    public function getAddLogLink()
    {
        $locationsHolder = LocationPage::get()->first();
        return $locationsHolder->Link("addLog/") . $this->LinkTitle;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName("ParentID");
        $fields->replaceField("SeatOrientation", new DropdownField("SeatOrientation", "Seat Orientation", [
            "standard" => "Standard",
            "circular" => "Circular",
            "wings" => "Wings"
        ]));
        $fields->insertAfter('Title', new DropdownField('TypeID', 'Type', ExperienceType::get()->map('ID', 'Title')));

        $areatypeID = ExperienceType::get()->filter('Title', 'Area')->first()->ID;
        $parentID = $this->ParentID;
        if ($areatypeID) {
            $experiencemap = Experience::get()->filter([
                'TypeID' => $areatypeID,
                'ParentID' => $parentID,
            ])->map('ID', 'Title');

            $fields->insertAfter('TypeID', new DropdownField('AreaID', 'Area', $experiencemap))->setHasEmptyDefault(true)->setEmptyString("- Not inside Area -");
        }

        $fields->removeByName("ExperienceData");
        $gridFieldConfig = GridFieldConfig_RecordEditor::create(200);
        $gridfield = new GridField("ExperienceData", "ExperienceData", $this->ExperienceData(), $gridFieldConfig);
        $fields->addFieldToTab('Root.Data', $gridfield);

        $fields->removeByName("ExperienceTrains");
        $gridFieldConfig = GridFieldConfig_RecordEditor::create(200);
        $gridFieldConfig->addComponent(new GridFieldSortableRows('SortOrder'));
        $gridFieldConfig->addComponent(new BulkManager());
        $gridFieldConfig->addComponent(new GridFieldDuplicateAction());
        $gridfield = new GridField("ExperienceTrains", "ExperienceTrains", $this->ExperienceTrains(), $gridFieldConfig);
        $fields->addFieldToTab('Root.Trains', $gridfield);

        $fields->removeByName("Variants");
        $gridFieldConfig = GridFieldConfig_RecordEditor::create(200);
        $gridfield = new GridField("Variants", "Variants", $this->Variants(), $gridFieldConfig);
        $fields->addFieldToTab('Root.Variants and Versions', $gridfield);

        $fields->removeByName("Versions");
        $gridFieldConfig = GridFieldConfig_RecordEditor::create(200);
        $gridfield = new GridField("Versions", "Versions", $this->Versions(), $gridFieldConfig);
        $fields->addFieldToTab('Root.Variants and Versions', $gridfield);

        return $fields;
    }

    public function onBeforeWrite()
    {
        //Generate Link
        if ($this->LinkTitle == "") {
            $filter = URLSegmentFilter::create();
            $filteredTitle = $filter->filter($this->Title);
            $this->LinkTitle = $filteredTitle;
        }

        $output = $this->toMap();

        $output["ExperienceType"] = $this->Type()->Title;
        $output["ExperienceArea"] = $this->Area()->Title;
        $output["Description"] = $this->getField("Description");
        $output["ExperienceLink"] = $this->getLink();
        if ($this->Image) {
            $image = $this->Image->FocusFill(200, 200);
            if ($image) {
                $output["ExperienceImage"] = $image->Link();
            }
        }
        unset($output["JSONCode"]);
        unset($output["ClassName"]);
        unset($output["Created"]);
        unset($output["RecordClassName"]);

        $this->JSONCode = json_encode($output);

        parent::onBeforeWrite();
    }

    public function getLatestLog()
    {
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            return LogEntry::get()->filter([
                "UserID" => $currentUser->ID,
                "ExperienceID" => $this->ID,
                ])->sort("VisitTime", "DESC")->first();
        }
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

    public function getSortedWagons($id)
    {
        return GroupedList::create($this->ExperienceTrains()->filter("ID", $id)->getSeats()->sort('Wagon ASC, Row ASC, Seat ASC'));
    }

    public function getTrainName($number)
    {
        if ($this->ExperienceTrains()->count() == 0) {
            return $number;
        } else {
            $train = $this->ExperienceTrains()->filter("SortOrder", $number)->first();
            if ($train) {
                return $train->Title;
            } else {
                return $number;
            }
        }
    }
}
