<?php

namespace App\ExperienceDatabase;

use SilverStripe\ORM\DataObject;
use Colymba\BulkManager\BulkManager;
use SilverStripe\Security\Permission;
use App\ExperienceDatabase\ExperienceWagon;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridFieldConfig_Base;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use Symbiote\GridFieldExtensions\GridFieldEditableColumns;
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
 * @method \App\ExperienceDatabase\Experience Parent()
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\ExperienceWagon[] Wagons()
 */
class ExperienceTrain extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "SortOrder" => "Int",
        "Color" => "Varchar(7)",
    ];

    private static $api_access = true;

    private static $has_one = [
        "Parent" => Experience::class
    ];

    private static $has_many = [
        "Wagons" => ExperienceWagon::class
    ];

    private static $owns = [
        "Wagons"
    ];

    private static $default_sort = "SortOrder ASC, Title ASC";

    private static $field_labels = [
        "Title" => "Traintitle",
        "Color" => "Color",
        "SortOrder" => "SortOrder",
    ];

    private static $summary_fields = [
        "Title" => "Traintitle",
        "Color" => "Color",
        "Wagons.Count" => "Wagons",
    ];

    private static $searchable_fields = [
        "Title"
    ];

    private static $table_name = "ExperienceTrain";

    private static $singular_name = "Train";
    private static $plural_name = "Trains";

    private static $url_segment = "train";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName("ParentID");

        $fields->removeByName("Wagons");
        $gridFieldConfig = GridFieldConfig_RecordEditor::create(200);
        $gridFieldConfig->addComponent(new GridFieldSortableRows('SortOrder'));
        $gridFieldConfig->addComponent(new BulkManager());
        $gridFieldConfig->addComponent(new GridFieldDuplicateAction());
        $gridfield = new GridField("Wagons", "Wagons", $this->Wagons(), $gridFieldConfig);
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
