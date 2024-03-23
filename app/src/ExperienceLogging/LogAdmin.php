<?php
namespace App\ExperienceDatabase;

use App\Profile\FriendRequest;
use SilverStripe\Admin\ModelAdmin;

/**
 * Class \App\Database\ExperienceAdmin
 *
 */
class LogAdmin extends ModelAdmin
{

    private static $managed_models = array (
        LogEntry::class,
        FriendRequest::class,
    );

    private static $url_segment = "logs";

    private static $menu_title = "Logs";

    private static $menu_icon = "app/client/icons/admin/LogsAdmin.svg";

    public function init()
    {
        parent::init();
    }
}
