<?php

namespace App\Profile;

use Override;
use Page;

/**
 * Class \App\Docs\DocsHolder
 *
 */
class ProfilePage extends Page
{
    private static $table_name = 'ProfilePage';

    private static $db = [];

    private static $cms_icon = "app/client/icons/profile.svg";

    #[Override]
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }

    #[Override]
    public function getControllerName()
    {
        return ProfilePageController::class;
    }
}
