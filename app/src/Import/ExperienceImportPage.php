<?php

namespace App\Import;

use Page;
use SilverStripe\Security\Permission;

/**
 * Page type that lets staff pick an existing Place and upload a CSV file of
 * attraction data to import (create missing attractions, fill in empty
 * fields, and flag conflicting fields for manual review).
 *
 */
class ExperienceImportPage extends Page
{
    private static $table_name = 'ExperienceImportPage';

    private static $db = [];

    private static $icon = "app/client/icons/admin/PlacesAdmin.svg";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }

    public function canView($member = null)
    {
        return Permission::check('CMS_ACCESS_NewsAdmin', 'any', $member);
    }
}
