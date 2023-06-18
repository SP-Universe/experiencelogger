<?php

namespace App\Food;

use App\Food\FoodType;
use App\Overview\LocationPage;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Security\Permission;
use App\ExperienceDatabase\Experience;
use SilverStripe\View\Parsers\URLSegmentFilter;

/**
 * Class \App\Database\Experience
 *
 * @property string $Title
 * @property string $Description
 * @property int $FoodTypeID
 * @property int $ImageID
 * @method \App\Food\FoodType FoodType()
 * @method \SilverStripe\Assets\Image Image()
 */
class Food extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "Description" => "HTMLText",
    ];

    private static $api_access = ['view' => ['Title', 'Description', 'Image']];

    private static $has_one = [
        "FoodType" => FoodType::class,
        "Image" => Image::class,
    ];

    private static $owns = [
        "Image",
    ];

    private static $belongs_many = [
        "Experience" => Experience::class,
    ];

    private static $summary_fields = [
        "ID" => "ID",
        "Title" => "Title",
        "FoodType.Title" => "Food Type",
    ];

    private static $field_labels = [
        "Title" => "Title",
        "Description" => "Description",
    ];

    private static $default_sort = "Title ASC, FoodTypeID ASC";

    private static $table_name = "Food";

    private static $singular_name = "Food";
    private static $plural_name = "Foods";

    private static $url_segment = "food";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->insertAfter('Title', new DropdownField('FoodTypeID', 'Type', FoodType::get()->map('ID', 'Title')));

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
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
