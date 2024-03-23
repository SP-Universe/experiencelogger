<?php

namespace App\News;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Versioned\Versioned;

/**
 * Class \News
 *
 * @property int $Version
 * @property string $Title
 * @property string $ShortDescription
 * @property string $Date
 * @property string $Content
 * @property int $ImageID
 * @method \SilverStripe\Assets\Image Image()
 * @method \SilverStripe\ORM\ManyManyList|\App\News\NewsCategory[] Category()
 * @mixin \SilverStripe\Versioned\Versioned
 */
class News extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "ShortDescription" => "Varchar(255)",
        "Date" => "Datetime",
        "Content" => "HTMLText"
    ];

    private static $has_one = [
        "Image" => Image::class
    ];

    private static $many_many = [
        "Category" => NewsCategory::class
    ];

    private static $owns = [
        "Image",
    ];

    private static $extensions = [
        Versioned::class,
    ];

    private static $default_sort = "Date DESC";
    private static $singular_name = "News";
    private static $plural_name = "News";
    private static $table_name = "News";

    private static $field_labels = [
        "Title" => "Title",
        "Date" => "Date",
        "Description" => "Short Description",
        "Content" => "Content",
        "Image" => "Image",
    ];

    private static $summary_fields = [
        "Date" => "Date",
        "CMSThumbnail" => "Image",
        "Title" => "Title"
    ];

    public function getLink()
    {
        $page = NewsPage::get()->first();
        if (!$page) {
            return "";
        }
        if ($page) {
            return $page->Link("news/" . $this->ID);
        }
    }

    public function RenderDescription()
    {
        return $this->dbObject('Date')->Format('dd.MM.yyyy') . " - " . $this->dbObject("Description")->forTemplate();
    }

    public function getThumb()
    {
        try {
            if ($image = $this->Image()) {
                if ($thumb = $image->Fill(180, 180)) {
                    return $thumb->getAbsoluteURL();
                }
            }
        } catch (\Exception $e) {
            // Ignore
        }
        return "";
    }

    // this function creates the thumnail for the summary fields to use
    public function getCMSThumbnail()
    {
        if ($image = $this->Image()) {
            return $image->CMSThumbnail();
        }
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->dataFieldByName("Image")->setFolderName("News");
        $categories = NewsCategory::get()->map("ID", "Title")->toArray();
        $fields->addFieldToTab("Root.Main", CheckboxSetField::create("Category", "Category", $categories));
        return $fields;
    }

    public function getFormattedDate()
    {
        return date("d.m.Y", strtotime($this->Date));
    }

    public function getFormattedCategories()
    {
        $categories = $this->Category();
        $categoryList = "";
        foreach ($categories as $category) {
            $categoryList .= $category->Title . ", ";
        }
        return rtrim($categoryList, ", ");
    }
}
