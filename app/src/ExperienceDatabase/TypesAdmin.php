<?php
namespace App\ExperienceDatabase;

use Override;
use SilverStripe\Admin\ModelAdmin;
use App\Food\FoodType;

/**
 * Class \App\Database\ExperienceAdmin
 *
 */
class TypesAdmin extends ModelAdmin
{

    private static $managed_models =  [
        ExperienceType::class,
        ExperienceDataType::class,
        ExperienceLocationType::class,
        FoodType::class,
    ];

    private static $url_segment = "types";

    private static $menu_title = "Types";

    private static $menu_icon = "app/client/icons/admin/TypesAdmin.svg";

    #[Override]
    public function init()
    {
        parent::init();
    }
}
