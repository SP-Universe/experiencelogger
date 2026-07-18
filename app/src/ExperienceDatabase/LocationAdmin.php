<?php
namespace App\ExperienceDatabase;

use Override;
use SilverStripe\Admin\ModelAdmin;

/**
 * Class \App\Database\ExperienceAdmin
 *
 */
class LocationAdmin extends ModelAdmin
{

    private static $managed_models =  [
        ExperienceLocation::class,
    ];

    private static $url_segment = "places";

    private static $menu_title = "Places";

    private static $menu_icon = "app/client/icons/admin/PlacesAdmin.svg";

    #[Override]
    public function init()
    {
        parent::init();
    }
}
