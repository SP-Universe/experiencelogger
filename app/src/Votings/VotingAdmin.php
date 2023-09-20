<?php
namespace App\Votings;

use App\Votings\Voting;
use SilverStripe\Admin\ModelAdmin;

/**
 * Class \App\Database\ExperienceAdmin
 *
 */
class FoodAdmin extends ModelAdmin
{

    private static $managed_models = array (
        Voting::class,
    );

    private static $url_segment = "votings";

    private static $menu_title = "Votings";

    private static $menu_icon = "app/client/icons/star_filled.svg";

    public function init()
    {
        parent::init();
    }
}
