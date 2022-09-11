<?php
namespace App\ExperienceDatabase;

use SilverStripe\Admin\ModelAdmin;

/**
 * Class \App\Database\ExperienceAdmin
 *
 */
class ExperienceAdmin extends ModelAdmin
{

    private static $managed_models = array (
        Experience::class,
        ExperienceLocation::class,
    );

    private static $url_segment = "experiences";

    private static $menu_title = "Experiences";

    private static $menu_icon = "app/client/icons/docs.svg";

    public function init()
    {
        parent::init();
    }
}
