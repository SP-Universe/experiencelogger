<?php

namespace App\Overview;

use Page;

/**
 * Class \App\Docs\DocsHolder
 *
 */
class ExperiencesPage extends Page
{
    private static $table_name = 'ExperiencesPage';

    private static $db = array();

    private static $icon = "app/client/icons/docs.svg";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }
}
