<?php

namespace App\Profile;

use App\Profile\RegistrationPageController;
use Page;

/**
 * Class \App\Profile\RegistrationPage
 *
 */
class RegistrationPage extends Page
{
    private static $table_name = 'RegistrationPage';

    private static $db = array();

    private static $icon = "app/client/icons/profile.svg";

    public function getControllerName()
    {
        return RegistrationPageController::class;
    }
}
