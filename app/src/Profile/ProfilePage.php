<?php

namespace App\Profile;

use Page;

/**
 * Class \App\Docs\DocsHolder
 *
 */
class ProfilePage extends Page
{
    private static $table_name = 'ProfilePage';

    private static $db = array();

    private static $icon = "app/client/icons/profile.svg";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }

    public function getControllerName()
    {
        return ProfilePageController::class;
    }
}
