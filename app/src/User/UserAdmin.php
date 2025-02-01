<?php

namespace App\ExperienceDatabase;

use App\Profile\FriendRequest;
use App\User\User;
use SilverStripe\Admin\ModelAdmin;

/**
 * Class \App\Database\ExperienceAdmin
 *
 */
class UserAdmin extends ModelAdmin
{

    private static $managed_models = array(
        User::class,
    );

    private static $url_segment = "users";

    private static $menu_title = "Users";

    public function init()
    {
        parent::init();
    }
}
