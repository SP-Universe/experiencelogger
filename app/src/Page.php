<?php

namespace {

use SilverStripe\Forms\CheckboxField;

    use SilverStripe\Forms\DropdownField;
    use SilverStripe\CMS\Model\SiteTree;

    /**
 * Class \Page
 *
 * @property string $MenuPosition
 * @property bool $ShowTitle
 * @property int $ElementalAreaID
 * @property int $HeaderImageID
 * @method \DNADesign\Elemental\Models\ElementalArea ElementalArea()
 * @method \Image HeaderImage()
 * @mixin \DNADesign\Elemental\Extensions\ElementalPageExtension
 */
    class Page extends SiteTree
    {
        private static $db = [
            "MenuPosition" => "Enum('main,footer', 'main')",
            "ShowTitle" => "Boolean",
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
            $fields->addFieldToTab("Root.Main", new CheckboxField("ShowTitle", "Titel anzeigen"), "Content");
            return $fields;
        }
    }
}
