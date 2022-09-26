<?php

use SilverStripe\i18n\i18n;
use SilverStripe\CampaignAdmin\CampaignAdmin;
use SilverStripe\Admin\CMSMenu;
use SilverStripe\Admin\SecurityAdmin;
use SilverStripe\CMS\Controllers\CMSPageSettingsController;
use SilverStripe\Reports\ReportAdmin;
use SilverStripe\Security\Member;
use SilverStripe\Security\PasswordValidator;
use SilverStripe\VersionedAdmin\ArchiveAdmin;

// remove PasswordValidator for SilverStripe 5.0
$validator = PasswordValidator::create();
// Settings are registered via Injector configuration - see passwords.yml in framework
Member::set_password_validator($validator);
i18n::set_locale('de_DE');

//CMSMenu::remove_menu_class(CampaignAdmin::class);
//CMSMenu::remove_menu_class(ReportAdmin::class);
//CMSMenu::remove_menu_class(ArchiveAdmin::class);
