<?php
namespace App\ExperienceDatabase;

use SilverStripe\Admin\ModelAdmin;

/**
 * Class \App\Database\ExperienceAdmin
 *
 */
class LocationAdmin extends ModelAdmin
{

    private static $managed_models = array (
        ExperienceLocation::class,
    );

    private static $url_segment = "locations";

    private static $menu_title = "Orte";

    private static $menu_icon = "app/client/icons/location.svg";

    public function init()
    {
        parent::init();
    }
}
