<?php

namespace App\ExperienceDatabase;

use SilverStripe\ORM\DataObject;
use Colymba\BulkManager\BulkManager;
use SilverStripe\Security\Permission;
use App\ExperienceDatabase\ExperienceSeat;
use App\ExperienceDatabase\ExperienceWagon;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;
use StevenPaw\DuplicateDataObject\Forms\GridField\GridFieldDuplicateAction;

/**
 * Class \App\Database\ExperienceSeat
 *
 * @property string $Title
 * @property int $SortOrder
 * @property int $ParentID
 * @method \App\ExperienceDatabase\ExperienceWagon Parent()
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\ExperienceSeat[] Seats()
 */
class ExperienceRow extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "SortOrder" => "Int",
    ];

    private static $api_access = true;

    private static $has_one = [
        "Parent" => ExperienceWagon::class
    ];

    private static $has_many = [
        "Seats" => ExperienceSeat::class
    ];

    private static $owns = [
        "Seats"
    ];

    private static $default_sort = "SortOrder ASC, Title ASC";

    private static $field_labels = [
        "Title" => "Rowtitle",
        "SortOrder" => "SortOrder",
    ];

    private static $summary_fields = [
        "Title" => "Rowtitle",
        "Seats.Count" => "Seats",
    ];

    private static $searchable_fields = [
        "Title"
    ];

    private static $table_name = "ExperienceRow";

    private static $singular_name = "Row";
    private static $plural_name = "Rows";

    private static $url_segment = "row";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName("ParentID");

        $fields->removeByName("Seats");
        $gridFieldConfig = GridFieldConfig_RecordEditor::create(200);
        $gridFieldConfig->addComponent(new GridFieldSortableRows('SortOrder'));
        $gridFieldConfig->addComponent(new BulkManager());
        $gridFieldConfig->addComponent(new GridFieldDuplicateAction());
        $gridfield = new GridField("Seats", "Seats", $this->Seats(), $gridFieldConfig);
        $fields->addFieldToTab('Root.Main', $gridfield);

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
