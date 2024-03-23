<?php

namespace App\Admin;

use App\News\News;
use App\News\NewsCategory;
use SilverStripe\Admin\ModelAdmin;

/**
 * Class \NewsAdmin
 *
 */
class NewsAdmin extends ModelAdmin
{
    private static $managed_models = array (
            News::class,
            NewsCategory::class
    );

    private static $url_segment = "news";

    private static $menu_title = "News";

    private static $menu_icon_class = 'font-icon-news';
}
