<?php

namespace App\ExperienceDatabase;

use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\GroupedList;
use Colymba\BulkManager\BulkManager;
use SilverStripe\Security\Permission;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldButtonRow;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use Symbiote\GridFieldExtensions\GridFieldTitleHeader;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use Symbiote\GridFieldExtensions\GridFieldEditableColumns;
use Symbiote\GridFieldExtensions\GridFieldAddNewInlineButton;
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
 * @method \SilverStripe\ORM\DataList|\App\ExperienceDatabase\ExperienceSeat[] Seats()
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
        "Seats" => ExperienceSeat::class
    ];

    private static $owns = [
        "Seats"
    ];

    private static $default_sort = "Title ASC";

    private static $field_labels = [
        "Title" => "Traintitle",
        "Color" => "Color",
        "SortOrder" => "SortOrder",
    ];

    private static $summary_fields = [
        "SortOrder" => "SortOrder",
        "Title" => "Traintitle",
        "Color" => "Color",
        "Seats.Count" => "Seats Amount",
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

        $grid = GridField::create(
            'ExperienceSeats',
            'Seats',
            $this->Seats(),
            GridFieldConfig::create()
                ->addComponent(GridFieldButtonRow::create('before'))
                ->addComponent(GridFieldToolbarHeader::create())
                ->addComponent(GridFieldTitleHeader::create())
                ->addComponent(GridFieldEditableColumns::create())
                ->addComponent(GridFieldDeleteAction::create())
                ->addComponent(GridFieldAddNewInlineButton::create())
                ->addComponent(new GridFieldDuplicateAction())
                ->addComponent(GridFieldEditButton::create())
        );

        /*
        $fields->removeByName("Seats");
        $gridFieldConfig = GridFieldConfig_RecordEditor::create(200);
        $gridFieldConfig->addComponent(new BulkManager());
        $gridFieldConfig->addComponent(new GridFieldDuplicateAction());
        $gridFieldConfig->addComponent(new GridFieldEditableColumns);
        $gridFieldConfig->addComponent(new GridFieldAddNewInlineButton);
        $gridfield = new GridField("ExperienceSeats", "Seats", $this->Seats(), $gridFieldConfig);
        */

        $fields->addFieldToTab('Root.Main', $grid);

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

    public function getSortedSeats()
    {
        return GroupedList::create($this->Seats()->sort('Wagon ASC, Row ASC, Seat ASC'))->GroupedBy('Wagon');
    }
}
