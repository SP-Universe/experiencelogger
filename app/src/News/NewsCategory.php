<?php

namespace App\News;

use Override;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\ORM\DataObject;

/**
 * Class \SchoolCategory
 *
 * @property string $Title
 * @method ManyManyList|News[] News()
 */
class NewsCategory extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)"
    ];

    private static $belongs_many_many = [
        "News" => News::class
    ];

    private static $singular_name = "News-Kategorie";
    private static $plural_name = "News-Kategorien";
    private static $table_name = "NewsCategory";

    private static $field_labels = [
        "Title" => "Name"
    ];

    #[Override]
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }
}
