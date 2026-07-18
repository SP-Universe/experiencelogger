<?php
namespace App\Food;

use Override;
use App\Food\Food;
use SilverStripe\Admin\ModelAdmin;

/**
 * Class \App\Database\ExperienceAdmin
 *
 */
class FoodAdmin extends ModelAdmin
{

    private static $managed_models =  [
        Food::class,
    ];

    private static $url_segment = "food";

    private static $menu_title = "Food";

    private static $menu_icon = "app/client/icons/admin/FoodAdmin.svg";

    #[Override]
    public function init()
    {
        parent::init();
    }
}
