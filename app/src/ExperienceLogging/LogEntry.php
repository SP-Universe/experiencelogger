<?php

namespace App\ExperienceDatabase;

use SilverStripe\Assets\File;
use App\Overview\LocationPage;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;

/**
 * Class \App\Database\Experience
 *
 * @property string $VisitTime
 * @property string $Weather
 * @property string $Notes
 * @property string $Score
 * @property string $Train
 * @property int $Wagon
 * @property int $Row
 * @property int $Seat
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
        "Train" => "Varchar(255)",
        "Wagon" => "Int",
        "Row" => "Int",
        "Seat" => "Int",
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
            return $time->Format("dd.MM.yyyy | hh:mm:ss");
        }
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
}