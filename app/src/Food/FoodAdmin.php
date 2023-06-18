<?php
namespace App\Food;

use App\Food\Food;
use SilverStripe\Admin\ModelAdmin;

/**
 * Class \App\Database\ExperienceAdmin
 *
 */
class FoodAdmin extends ModelAdmin
{

    private static $managed_models = array (
        Food::class,
    );

    private static $url_segment = "food";

    private static $menu_title = "Food";

    private static $menu_icon = "app/client/icons/location.svg";

    public function init()
    {
        parent::init();
    }
}
