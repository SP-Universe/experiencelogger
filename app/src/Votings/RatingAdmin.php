<?php
namespace App\Ratings;

use Override;
use App\Ratings\Rating;
use SilverStripe\Admin\ModelAdmin;

/**
 * Class \App\Database\ExperienceAdmin
 *
 */
class RatingAdmin extends ModelAdmin
{

    private static $managed_models =  [
        Rating::class,
    ];

    private static $url_segment = "ratings";

    private static $menu_title = "Ratings";

    private static $menu_icon = "app/client/icons/admin/RatingsAdmin.svg";

    #[Override]
    public function init()
    {
        parent::init();
    }
}
