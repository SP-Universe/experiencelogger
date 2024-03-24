<?php

namespace App\ExperienceDatabase;

use App\Food\Food;
use App\Overview\LocationPage;
use App\Helper\ExperienceHelper;
use App\Overview\StatisticsPage;
use SilverStripe\Forms\DateField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\GroupedList;
use SilverStripe\Security\Security;
use Colymba\BulkManager\BulkManager;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Security\Permission;
use App\ExperienceDatabase\ExperienceTrain;
use SilverStripe\Forms\GridField\GridField;
use App\ExperienceDatabase\ExperienceLocation;
use SilverStripe\View\Parsers\URLSegmentFilter;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\NumericField;
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;
use StevenPaw\DuplicateDataObject\Forms\GridField\GridFieldDuplicateAction;

/**
 * Class \App\Database\Experience
 *
 * @property string $Title
 * @property string $LinkTitle
 * @property string $State
 * @property string $Entrance
 * @property string $Coordinates
 * @property string $Traintype
 * @property string $CustomTrainType
 * @property bool $HasWagons
 * @property bool $HasRows
 * @property bool $HasSeats
 * @property string $HasScore
 * @property int $HasPodest
 * @property string $SeatOrientation
 * @property string $Description
 * @property string $ExperienceLink
 * @property string $JSONCode
 * @property float $Rating
 * @property int $NumberOfRatings
 * @property string $TrainNumberPosition
 * @property string $OpeningDate
 * @property string $ClosingDate
 * @property float $Height
 * @property float $Length
 * @property int $Duration
 * @property float $Speed
 * @property bool $HasSingleRider
 * @property bool $HasFastpass
 * @property bool $HasOnridePhoto
 * @property string $FastpassLink
 * @property bool $AccessibleToHandicapped
 * @property int $ParentID
 * @property int $TypeID
 * @property int $AreaID
 * @property int $StageID
 * @method \App\ExperienceDatabase\ExperienceLocation Parent()
 * @method \App\ExperienceDatabase\ExperienceType Type()
 * @method \App\ExperienceDatabase\Experience Area()
 * @method \App\ExperienceDatabase\Experience Stage()
 * @method \SilverStripe\ORM\DataList|\PurpleSpider\BasicGalleryExtension\PhotoGalleryImage[] PhotoGalleryImages()
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\ExperienceData[] ExperienceData()
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\ExperienceTrain[] ExperienceTrains()
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\ExperienceVariant[] Variants()
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\ExperienceVersion[] Versions()
 * @method \SilverStripe\ORM\ManyManyList|\App\ExperienceDatabase\Experience[] Characters()
 * @method \SilverStripe\ORM\ManyManyList|\App\Food\Food[] Food()
 * @mixin \PurpleSpider\BasicGalleryExtension\PhotoGalleryExtension
 */
class Experience extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "LinkTitle" => "Varchar(255)",
        "State" => "Enum('Active, In Maintenance, InActive, Coming Soon, Other, Defunct', 'Active')",
        "Entrance" => "Enum('None, Left, Right', 'None')",
        "Coordinates" => "Varchar(64)",
        "Traintype" => "Enum('Train, None, Boat, Car, Airplane, Balloon, Pony, Gondola, Slide', 'None')",
        "CustomTrainType" => "Varchar(255)",
        "HasWagons" => "Boolean",
        "HasRows" => "Boolean",
        "HasSeats" => "Boolean",
        "HasScore" => "Varchar(255)",
        "HasPodest" => "Int",
        "SeatOrientation" => "Varchar(255)",
        "Description" => "HTMLText",
        "ExperienceLink" => "Varchar(255)",
        "JSONCode" => "HTMLText",
        "Rating" => "Double",
        "NumberOfRatings" => "Int",
        "TrainNumberPosition" => "Varchar(255)",

        "OpeningDate" => "Date",
        "ClosingDate" => "Date",
        "Height" => "Double",
        "Length" => "Double",
        "Duration" => "Int",
        "Speed" => "Double",
        "HasSingleRider" => "Boolean",
        "HasFastpass" => "Boolean",
        "HasOnridePhoto" => "Boolean",
        "FastpassLink" => "Varchar(255)",
        "AccessibleToHandicapped" => "Boolean",
    ];

    private static $api_access = ['view' => ['Title', 'ExperienceType', 'ExperienceArea', 'ExperienceStage', 'State', 'Description', 'ExperienceImage', 'ParentID']];

    private static $has_one = [
        "Parent" => ExperienceLocation::class,
        "Type" => ExperienceType::class,
        "Area" => Experience::class,
        "Stage" => Experience::class,
    ];

    private static $has_many = [
        "ExperienceData" => ExperienceData::class,
        "ExperienceTrains" => ExperienceTrain::class,
        "Variants" => ExperienceVariant::class,
        "Versions" => ExperienceVersion::class,
    ];

    private static $many_many = [
        "Characters" => Experience::class,
        "Food" => Food::class,
    ];

    private static $owns = [
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
        "HasGeneralSeats" => false,
        "HasScore" => false,
        "HasPodest" => false,
        "HasWagons" => false,
        "HasRows" => false,
        "HasSeats" => false,
        "AllTrainsTheSame" => false,
    ];

    private static $url_segment = "experience";

    public function getExperienceImage()
    {
        if ($this->PhotoGalleryImages->Count() > 0) {
            return $this->PhotoGalleryImages()->first()->Image()->getAbsoluteURL();
        } else {
            return $this->Image()->exists() ? $this->Image()->getAbsoluteURL() : null;
        }
    }

    public function getExperienceType()
    {
        return $this->Type()->exists() ? $this->Type()->Title : null;
    }

    public function getExperienceArea()
    {
        return $this->Area()->exists() ? $this->Area()->Title : null;
    }

    public function getExperienceStage()
    {
        return $this->Stage()->exists() ? $this->Stage()->Title : null;
    }

    public function getLogs()
    {
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            return GroupedList::create(LogEntry::get()->filter(
                [
                    "ExperienceID" => $this->ID,
                    "UserID" => $currentUser->ID,
                ]
            )->sort('VisitTime DESC'));
        }
    }

    public function getTotalLogCount()
    {
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            return LogEntry::get()->filter(
                [
                    "ExperienceID" => $this->ID,
                ]
            )->count();
        }
    }

    public function getGroupedFood()
    {
        return GroupedList::create($this->Food())->GroupedBy("FoodTypeID");
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName("ParentID");
        $fields->insertAfter('Title', new DropdownField('TypeID', 'Type', ExperienceType::get()->map('ID', 'Title')));
        $fields->insertAfter('Title', new TextField('LinkTitle', 'URL-Segment'));

        //Areas and Stages
        $parentID = $this->ParentID;
        $areatypeID = ExperienceType::get()->filter('Title', 'Area')->first()->ID;
        if ($areatypeID) {
            $experiencemap = Experience::get()->filter([
                'TypeID' => $areatypeID,
                'ParentID' => $parentID,
            ])->map('ID', 'Title');

            $fields->insertAfter('TypeID', new DropdownField('AreaID', 'Area', $experiencemap))->setHasEmptyDefault(true)->setEmptyString("- Not inside Area -");
        }

        $stagetypeID = ExperienceType::get()->filter('Title', 'Stage')->first()->ID;
        if ($stagetypeID) {
            $experiencemap = Experience::get()->filter([
                'TypeID' => $stagetypeID,
                'ParentID' => $parentID,
            ])->map('ID', 'Title');

            $fields->insertAfter('AreaID', new DropdownField('StageID', 'Stage', $experiencemap))->setHasEmptyDefault(true)->setEmptyString("- Not inside Stage -");
        }

        //Experience Data
        $fields->addFieldToTab('Root.Data', new DateField('OpeningDate', 'Opening Date'));
        $fields->addFieldToTab('Root.Data', new DateField('ClosingDate', 'Closing Date'));
        $fields->addFieldToTab('Root.Data', new NumericField('Height', 'Height (in m)'));
        $fields->addFieldToTab('Root.Data', new NumericField('Length', 'Length (in m)'));
        $fields->addFieldToTab('Root.Data', new NumericField('Duration', 'Duration (in seconds)'));
        $fields->addFieldToTab('Root.Data', new NumericField('Speed', 'Speed (in km/h)'));
        $fields->addFieldToTab('Root.Data', new CheckboxField('HasSingleRider', 'Has Single Rider'));
        $fields->addFieldToTab('Root.Data', new CheckboxField('HasFastpass', 'Has Fastpass'));
        $fields->addFieldToTab('Root.Data', new TextField('FastpassLink', 'Link to Fastpass-System'));
        $fields->addFieldToTab('Root.Data', new CheckboxField('HasOnridePhoto', 'Has Onride Photos'));
        $fields->addFieldToTab('Root.Data', new CheckboxField('AccessibleToHandicapped', 'Accessible to Handicapped'));
        $fields->removeByName("ExperienceData");
        $gridFieldConfig = GridFieldConfig_RecordEditor::create(200);
        $gridFieldConfig->addComponent(new GridFieldSortableRows('SortOrder'));
        $gridfield = new GridField("ExperienceData", "More Data", $this->ExperienceData(), $gridFieldConfig);
        $fields->addFieldToTab('Root.Data', $gridfield);

        //Experience Trains
        $fields->removeByName([
            'HasWagons',
            'HasRows',
            'HasSeats',
            "Traintype",
            "CustomTrainType",
            "SeatOrientation",
            "HasScore",
            "Entrance",
        ]);
        $fields->addFieldToTab('Root.Trains & Seats', new DropdownField('Traintype', 'Train Type', array(
            "None" => "None",
            "Train" => "Train",
            "Boat" => "Boat",
            "Car" => "Car",
            "Airplane" => "Airplane",
            "Balloon" => "Balloon",
            "Pony" => "Pony",
            "Gondola" => "Gondola",
            "Slide" => "Slide"
        )));
        $fields->addFieldToTab('Root.Trains & Seats', new TextField('CustomTrainType', 'Custom Train Type Title'));
        $fields->addFieldToTab('Root.Trains & Seats', new CheckboxField('HasWagons', 'Has Wagons'));
        $fields->addFieldToTab('Root.Trains & Seats', new CheckboxField('HasRows', 'Has Rows'));
        $fields->addFieldToTab('Root.Trains & Seats', new CheckboxField('HasSeats', 'Has Seats'));
        $fields->addFieldToTab('Root.Trains & Seats', new DropdownField('SeatOrientation', 'Seat Orientation', array(
            "Standard",
            "Circular",
            "Wings"
        )));
        $fields->addFieldToTab('Root.Trains & Seats', new DropdownField('Entrance', 'Entrance', array(
            "None" => "None",
            "Front" => "Front",
            "Back" => "Back",
            "Left" => "Left",
            "Right" => "Right",
        )));
        $fields->addFieldToTab('Root.Trains & Seats', new TextField('TrainNumberPosition', 'Position of Train Number'));

        $fields->addFieldToTab('Root.Other', new DropdownField('HasScore', 'Has Score', array(
            "0" => "No Score",
            "numeric" => "Numeric Score",
            "text" => "Text Score",
            "time" => "Time Score"
        )));
        $fields->addFieldToTab('Root.Other', new DropdownField('HasPodest', 'Number of Podest places', array(
            "0" => "No Podest",
            "1" => "1 Position",
            "2" => "2 Positions",
            "3" => "3 Positions",
            "4" => "4 Positions",
            "5" => "5 Positions",
            "6" => "6 Positions",
            "7" => "7 Positions",
            "8" => "8 Positions",
            "9" => "9 Positions",
            "10" => "10 Positions",
            "11" => "11 Positions",
            "12" => "12 Positions",
            "13" => "13 Positions",
            "14" => "14 Positions",
            "15" => "15 Positions",
            "16" => "16 Positions",
            "17" => "17 Positions",
            "18" => "18 Positions",
            "19" => "19 Positions",
            "20" => "20 Positions",
            "21" => "21 Positions",
            "22" => "22 Positions",
            "23" => "23 Positions",
            "24" => "24 Positions",
            "25" => "25 Positions",
            "26" => "26 Positions",
            "27" => "27 Positions",
            "28" => "28 Positions",
            "29" => "29 Positions",
            "30" => "30 Positions",
        )));

        $fields->removeByName("ExperienceTrains");
        $gridFieldConfig = GridFieldConfig_RecordEditor::create(200);
        $gridFieldConfig->addComponent(new GridFieldSortableRows('SortOrder'));
        $gridFieldConfig->addComponent(new BulkManager());
        $gridFieldConfig->addComponent(new GridFieldDuplicateAction());
        $gridfield = new GridField("ExperienceTrains", "Advanced Trains", $this->ExperienceTrains(), $gridFieldConfig);
        $fields->addFieldToTab('Root.Trains & Seats', $gridfield);

        //Variants and Versions
        $fields->removeByName("Variants");
        $gridFieldConfig = GridFieldConfig_RecordEditor::create(200);
        $gridFieldConfig->addComponent(new GridFieldSortableRows('SortOrder'));
        $gridfield = new GridField("Variants", "Variants", $this->Variants(), $gridFieldConfig);
        $fields->addFieldToTab('Root.Variants & Versions', $gridfield);

        $fields->removeByName("Versions");
        $gridFieldConfig = GridFieldConfig_RecordEditor::create(200);
        $gridFieldConfig->addComponent(new GridFieldSortableRows('SortOrder'));
        $gridfield = new GridField("Versions", "Versions", $this->Versions(), $gridFieldConfig);
        $fields->addFieldToTab('Root.Variants & Versions', $gridfield);

        //JSON Code
        $fields->removeByName("JSONCode");
        $fields->addFieldToTab('Root.Other', new ReadonlyField('JSONCode', 'JSON Code'));

        return $fields;
    }

    public function onBeforeWrite()
    {
        //Generate Link
        if ($this->LinkTitle == "") {
            $filter = URLSegmentFilter::create();
            $filteredTitle = $filter->filter($this->Title);
            if (Experience::get()->filter("LinkTitle", $filteredTitle)->count() > 0) {
                $filteredTitle = $filteredTitle . "-" . $this->ID;
            }
            $this->LinkTitle = $filteredTitle;
        }

        $output = $this->toMap();

        $output["ExperienceType"] = $this->Type()->Title;
        $output["ExperienceArea"] = $this->Area()->Title;
        $output["ExperienceStage"] = $this->Stage()->Title;
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

    public function getSubExperiences()
    {
        $experiences = Experience::get()->filter("AreaID", $this->ID);
        return $experiences;
    }

    public function getSubShows()
    {
        $showtypeID = ExperienceType::get()->filter('Title', 'Show')->first()->ID;
        $experiences = Experience::get()->filter([
            'TypeID' => $showtypeID,
            'StageID' => $this->ID,
        ]);
        return $experiences;
    }

    public function getIsCompletedByUser()
    {
        $log = $this->getLatestLog();
        if ($log) {
            return true;
        }
        return false;
    }

    public function getWillLinkLogArea()
    {
        /*$currentUser = Security::getCurrentUser();
        if ($this->AreaID == 0 || !$currentUser) {
            return false;
        }

        $lastLoggedArea = $currentUser->LastLoggedArea();
        $lastLoggedDate = $currentUser->LastLogDate;

        if ($lastLoggedArea && $lastLoggedArea->ID == $this->AreaID && $lastLoggedDate == date("Y-m-d")) {
            return false;
        }*/

        return ExperienceHelper::getWillLinkLogArea($this, date("Y-m-d"));
    }

    public function getStatisticsLink()
    {
        $statisticsPage = StatisticsPage::get()->first();
        if ($statisticsPage) {
            return $statisticsPage->Link("experience/" . $this->Parent()->LinkTitle . "---" . $this->LinkTitle);
        }
    }

    public function getLink()
    {
        $locationsHolder = LocationPage::get()->first();
        return $locationsHolder->Link("experience\/") . $this->Parent()->LinkTitle . "---" . $this->LinkTitle;
    }

    public function getAddLogLink()
    {
        $locationsHolder = LocationPage::get()->first();
        return $locationsHolder->Link("addLog\/") . $this->Parent()->LinkTitle . "---" . $this->LinkTitle;
    }

    public function getFinishLogLink()
    {
        $locationsHolder = LocationPage::get()->first();
        return $locationsHolder->Link("finishLog\/") . $this->Parent()->LinkTitle . "---" . $this->LinkTitle;
    }

    public function getSeatchartLink()
    {
        $locationsHolder = LocationPage::get()->first();
        return $locationsHolder->Link("seatchart\/") . $this->Parent()->LinkTitle . "---" . $this->LinkTitle;
    }
}
