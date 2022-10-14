<?php

namespace App\ExperienceDatabase;

use SilverStripe\Assets\File;
use App\Overview\LocationPage;
use SilverStripe\Assets\Image;
use function PHPSTORM_META\map;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Security\Member;
use SilverStripe\Forms\DropdownField;

use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Security\Permission;
use SilverStripe\View\ArrayData;

/**
 * Class \App\Database\Experience
 *
 * @property string $VisitTime
 * @property string $Weather
 * @property string $Notes
 * @property string $Score
 * @property int $Podest
 * @property string $Train
 * @property int $Wagon
 * @property int $Row
 * @property int $Seat
 * @property string $Variant
 * @property string $Version
 * @property int $UserID
 * @property int $ExperienceID
 * @method \SilverStripe\Security\Member User()
 * @method \App\ExperienceDatabase\Experience Experience()
 * @method \SilverStripe\ORM\ManyManyList|\SilverStripe\Security\Member[] Friends()
 */
class LogEntry extends DataObject
{
    private static $db = [
        "VisitTime" => "Datetime",
        "Weather" => "Varchar(255)",
        "Notes" => "Varchar(500)",
        "Score" => "Varchar(255)",
        "Podest" => "Int",
        "Train" => "Varchar(255)",
        "Wagon" => "Int",
        "Row" => "Int",
        "Seat" => "Int",
        "Variant" => "Varchar(255)",
        "Version" => "Varchar(255)",
        "Notes" => "Varchar(500)",
    ];

    private static $api_access = false;

    private static $has_one = [
        "User" => Member::class,
        "Experience" => Experience::class,
    ];

    private static $many_many = [
        "Friends" => Member::class,
    ];

    private static $summary_fields = [
        "FormattedDate" => "Time",
        "User.Nickname" => "User",
        "Experience.Title" => "Experience",
        "SeatName" => "Seat",
    ];

    private static $field_labels = [
        "Environment" => "Environment",
        "Notes" => "Notes",
        "Score" => "Score",
        "Friends" => "Friends",
        "User" => "User",
    ];

    private static $default_sort = "VisitTime DESC, UserID ASC";

    private static $table_name = "LogEntry";

    private static $singular_name = "Log Entry";
    private static $plural_name = "Log Entries";

    private static $url_segment = "logentry";

    public function getSeatName()
    {
        $train = $this->Train;
        $wagon = $this->Wagon;
        $row = $this->Row;
        $seat = $this->Seat;
        return "Train " . $train . " | Wagon " . $wagon . " | Row " . $row . " | Seat " . $seat;
    }

    public function getFormattedDate()
    {
        $time = $this->dbObject('VisitTime');
        if ($time) {
            return $time->Format("dd.MM.yyyy | HH:mm:ss");
        }
    }

    public function getWeathers()
    {
        $cutted = explode(",", $this->Weather);
        $weathers = new ArrayList();
        foreach ($cutted as $weather) {
            $weathers->push(new ArrayData(array(
                "Weather" => $weather,
            )));
        }
        return $weathers;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }

    public function getVisitDate()
    {
        return date("d.m.Y", strtotime($this->VisitTime));
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
