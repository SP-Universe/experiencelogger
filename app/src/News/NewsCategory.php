<?php

namespace App\News;

use App\Models\School;
use SilverStripe\ORM\DataObject;

/**
 * Class \SchoolCategory
 *
 * @property string $Title
 * @method \SilverStripe\ORM\ManyManyList|\App\News\News[] News()
 */
class NewsCategory extends DataObject {
    private static $db = array(
        "Title" => "Varchar(255)"
    );

    private static $belongs_many_many = array(
        "News" => News::class
    );

    private static $singular_name = "News-Kategorie";
    private static $plural_name = "News-Kategorien";
    private static $table_name = "NewsCategory";

    private static $field_labels = array(
        "Title" => "Name"
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }
}
