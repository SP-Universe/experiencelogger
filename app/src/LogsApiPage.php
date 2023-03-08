<?php

namespace {

    use SilverStripe\Assets\Image;
    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\CMS\Model\SiteTree;

    /**
 * Class \Page
 *
 */
    class LogsApiPage extends SiteTree
    {
        private static $db = [
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();
            return $fields;
        }
    }
}
