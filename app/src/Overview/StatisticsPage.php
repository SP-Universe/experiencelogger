<?php

namespace App\Overview;

use Override;
use Page;

/**
 * Class \App\Docs\DocsHolder
 *
 */
class StatisticsPage extends Page
{
    private static $table_name = 'StatisticsPage';

    private static $db = [];

    private static $cms_icon = "app/client/icons/docs.svg";

    #[Override]
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }
}
