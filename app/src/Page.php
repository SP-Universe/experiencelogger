<?php

namespace {

use SilverStripe\Control\Cookie;

    use SilverStripe\Assets\Image;
    use SilverStripe\AssetAdmin\Forms\UploadField;

    use SilverStripe\Assets\File;
    use SilverStripe\Forms\CheckboxField;

    use SilverStripe\Forms\DropdownField;
    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\Security\Security;
    use Symbiote\MemberProfiles\Pages\MemberProfilePage;

    /**
 * Class \Page
 *
 * @property string $MenuPosition
 * @property int $MenuIconID
 * @method \SilverStripe\Assets\File MenuIcon()
 */
    class Page extends SiteTree
    {
        private static $db = [
            "MenuPosition" => "Enum('main, footer, more', 'main')",
        ];

        private static $has_one = [
            "MenuIcon" => File::class,
        ];

        private static $owns = [
            "MenuIcon",
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();
            $fields->addFieldToTab("Root.Main", new DropdownField("MenuPosition", "Menü", [
                "main" => "Mainmenu",
                "footer" => "Footer",
                "more" => "More",
            ]), "Content");
            $fields->addFieldToTab("Root.Images", new UploadField("MenuIcon", "Menuicon"), "Content");
            return $fields;
        }

        public function getHasAcceptedCookies()
        {
            return Cookie::get('acceptedCookieConsent');
        }

        public function getDarkmode()
        {

            if (Cookie::get('darkmode') == "true") {
                return true;
            } else {
                return false;
            }
        }
    }
}
