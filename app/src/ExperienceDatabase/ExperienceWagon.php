<?php

namespace App\ExperienceDatabase;

use SilverStripe\ORM\DataObject;
use Colymba\BulkManager\BulkManager;
use SilverStripe\Security\Permission;
use App\ExperienceDatabase\ExperienceRow;
use App\ExperienceDatabase\ExperienceTrain;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;
use SwiftDevLabs\DuplicateDataObject\Forms\GridField\GridFieldDuplicateAction;

/**
 * Class \App\Database\ExperienceSeat
 *
 * @property string $Title
 * @property int $SortOrder
 * @property string $Color
 * @property int $ParentID
 * @method \App\ExperienceDatabase\ExperienceTrain Parent()
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\ExperienceRow[] Rows()
 */
class ExperienceWagon extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "SortOrder" => "Int",
        "Color" => "Varchar(7)",
    ];

    private static $api_access = true;

    private static $has_one = [
        "Parent" => ExperienceTrain::class
    ];

    private static $has_many = [
        "Rows" => ExperienceRow::class
    ];

    private static $owns = [
        "Rows"
    ];

    private static $default_sort = "SortOrder ASC, Title ASC";

    private static $field_labels = [
        "Title" => "Wagontitle",
        "Color" => "Color",
        "SortOrder" => "SortOrder",
    ];

    private static $summary_fields = [
        "Title" => "Wagontitle",
        "Color" => "Color",
        "Rows.Count" => "Rows",
    ];

    private static $searchable_fields = [
        "Title"
    ];

    private static $table_name = "ExperienceWagon";

    private static $singular_name = "Wagon";
    private static $plural_name = "Wagons";

    private static $url_segment = "wagon";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName("ParentID");

        $fields->removeByName("Rows");
        $gridFieldConfig = GridFieldConfig_RecordEditor::create(200);
        $gridFieldConfig->addComponent(new GridFieldSortableRows('SortOrder'));
        $gridFieldConfig->addComponent(new BulkManager());
        $gridFieldConfig->addComponent(new GridFieldDuplicateAction());
        $gridfield = new GridField("Rows", "Rows", $this->Rows(), $gridFieldConfig);
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
