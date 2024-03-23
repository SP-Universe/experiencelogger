<?php
namespace App\News;

use Page;

/**
 * Class \App\Pages\DataPage
 *
 */
class NewsPage extends Page
{
    private static $db = [
    ];

    private static $table_name = 'NewsPage';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }
}
