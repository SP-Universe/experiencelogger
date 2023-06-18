<?php
namespace App\ExperienceDatabase;

use SilverStripe\Admin\ModelAdmin;
use App\Food\FoodType;

/**
 * Class \App\Database\ExperienceAdmin
 *
 */
class TypesAdmin extends ModelAdmin
{

    private static $managed_models = array (
        ExperienceType::class,
        ExperienceDataType::class,
        ExperienceLocationType::class,
        FoodType::class,
    );

    private static $url_segment = "types";

    private static $menu_title = "Types";

    private static $menu_icon = "app/client/icons/types.svg";

    public function init()
    {
        parent::init();
    }
}
