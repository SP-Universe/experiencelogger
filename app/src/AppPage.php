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
 */
    class AppPage extends SiteTree
    {
        private static $db = [
        ];

        private static $has_one = [
        ];

        private static $owns = [
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();
            return $fields;
        }
    }
}
