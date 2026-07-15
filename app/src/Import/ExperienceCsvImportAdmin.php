<?php

namespace App\Import;

use SilverStripe\Admin\ModelAdmin;

/**
 * CMS admin section for attraction CSV imports. Completed imports are
 * deleted immediately after being applied (see
 * ExperienceImportPageController::confirmImport()), so this mainly surfaces
 * abandoned/unfinished (Pending) import sessions.
 *
 */
class ExperienceCsvImportAdmin extends ModelAdmin
{
    private static $managed_models = [
        ExperienceCsvImport::class,
    ];

    private static $url_segment = "csv-imports";

    private static $menu_title = "CSV Imports";

    private static $menu_icon_class = "font-icon-upload";

    public function init()
    {
        parent::init();
    }
}
