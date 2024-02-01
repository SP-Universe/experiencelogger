<?php

namespace App\Overview;

use Page;

/**
 * Class \App\Docs\DocsHolder
 *
 */
class StatisticsPage extends Page
{
    private static $table_name = 'StatisticsPage';

    private static $db = array();

    private static $icon = "app/client/icons/docs.svg";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }
}
