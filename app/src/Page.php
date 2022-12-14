<?php

namespace {

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
 * @property int $ElementalAreaID
 * @property int $HeaderImageID
 * @property int $MenuIconID
 * @method \DNADesign\Elemental\Models\ElementalArea ElementalArea()
 * @method \SilverStripe\Assets\Image HeaderImage()
 * @method \SilverStripe\Assets\File MenuIcon()
 * @mixin \DNADesign\Elemental\Extensions\ElementalPageExtension
 */
    class Page extends SiteTree
    {
        private static $db = [
            "MenuPosition" => "Enum('main, footer, personal', 'main')",
        ];

        private static $has_one = [
            "HeaderImage" => Image::class,
            "MenuIcon" => File::class,
        ];

        private static $owns = [
            "HeaderImage",
            "MenuIcon",
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();
            $fields->addFieldToTab("Root.Main", new DropdownField("MenuPosition", "Menü", [
                "main" => "Mainmenu",
                "footer" => "Footer",
                "personal" => "Personal",
            ]), "Content");
            $fields->addFieldToTab("Root.Images", new UploadField("HeaderImage", "Headerimage"), "Content");
            $fields->addFieldToTab("Root.Images", new UploadField("MenuIcon", "Menuicon"), "Content");
            return $fields;
        }
    }
}
