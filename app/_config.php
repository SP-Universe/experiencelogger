<?php

use App\ExperienceDatabase\Experience;
use SilverStripe\i18n\i18n;
use SilverStripe\Security\Member;
use SilverStripe\Security\PasswordValidator;
use Wilr\GoogleSitemaps\GoogleSitemap;

// remove PasswordValidator for SilverStripe 5.0
$validator = PasswordValidator::create();
// Settings are registered via Injector configuration - see passwords.yml in framework
Member::set_password_validator($validator);
i18n::set_locale('en_EN');
GoogleSitemap::register_dataobject(Experience::class);
