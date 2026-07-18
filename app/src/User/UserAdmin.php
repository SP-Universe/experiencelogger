<?php

namespace App\ExperienceDatabase;

use Override;
use App\User\User;
use SilverStripe\Admin\ModelAdmin;

/**
 * Class \App\Database\ExperienceAdmin
 *
 */
class UserAdmin extends ModelAdmin
{

    private static $managed_models = [
        User::class,
    ];

    private static $url_segment = "users";

    private static $menu_title = "Users";

    #[Override]
    public function init()
    {
        parent::init();
    }
}
