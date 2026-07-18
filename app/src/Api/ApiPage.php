<?php

namespace App\Api {

    use Override;
    use SilverStripe\CMS\Model\SiteTree;

    /**
 * Class \Page
 *
 */
    class ApiPage extends SiteTree
    {
        private static $table_name = 'ApiPage';

        private static $db = [];

        #[Override]
        public function getCMSFields()
        {
            $fields = parent::getCMSFields();
            return $fields;
        }
    }
}
