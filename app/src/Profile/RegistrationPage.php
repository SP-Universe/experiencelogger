<?php

namespace App\Profile;

use Override;
use App\Profile\RegistrationPageController;
use Page;

/**
 * Class \App\Profile\RegistrationPage
 *
 */
class RegistrationPage extends Page
{
    private static $table_name = 'RegistrationPage';

    private static $db = [];

    private static $cms_icon = "app/client/icons/profile.svg";

    #[Override]
    public function getControllerName()
    {
        return RegistrationPageController::class;
    }
}
