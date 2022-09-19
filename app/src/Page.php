<?php

namespace {

    use SilverStripe\Forms\DropdownField;
    use SilverStripe\CMS\Model\SiteTree;

    /**
 * Class \Page
 *
 * @property string $MenuPosition
 * @property int $HeaderImageID
 * @method \Image HeaderImage()
 */
    class Page extends SiteTree
    {
        private static $db = [
            "MenuPosition" => "Enum('main,footer', 'main')",
        ];

        private static $has_one = [
            "HeaderImage" => Image::class,
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();
            $fields->addFieldToTab("Root.Main", new DropdownField("MenuPosition", "Menü", [
                "main" => "Hauptmenü",
                "footer" => "Footer",
            ]), "Content");
            return $fields;
        }
    }
}
