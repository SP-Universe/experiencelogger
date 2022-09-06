<?php

namespace App\Elements;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\DropdownField;

/**
 * Class \App\Elements\TextImageElement
 *
 * @property string $Text
 * @property string $Variant
 * @property string $Highlight
 * @property string $ImgWidth
 * @property string $ButtonText
 * @property string $ButtonLink
 * @property int $ImageID
 * @method \SilverStripe\Assets\Image Image()
 */
class TextImageElement extends BaseElement
{

    private static $db = [
        "Text" => "HTMLText",
        "Variant" => "Varchar(20)",
        "Highlight" => "Varchar(20)",
        "ImgWidth" => "Varchar(20)",
        "ButtonText" => "Varchar(50)",
        "ButtonLink" => "Varchar(500)"
    ];

    private static $has_one = [
        "Image" => Image::class,
    ];

    private static $owns = [
        "Image"
    ];

    private static $field_labels = [
        "Text" => "Text",
        "Image" => "Bild",
        "ButtonText" => "Button Text",
        "ButtonLink" => "Button Link"
    ];

    private static $table_name = 'TextImageElement';
    private static $icon = 'font-icon-block-promo-3';

    public function getType()
    {
        return "Text+Bild";
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->replaceField('Variant', new DropdownField('Variant', 'Variante', [
            "" => "Bild links",
            "image-right" => "Bild rechts",
        ]));
        $fields->replaceField('ImgWidth', new DropdownField('ImgWidth', 'Bildbreite', [
            "image-30" => "30%",
            "image-40" => "40%",
            "image-50" => "50%",
            "image-60" => "60%",
            "image-70" => "70%",
        ]));
        $fields->replaceField('Highlight', new DropdownField('Highlight', 'Highlight', [
            "" => "Kein Highlight",
            "highlighted" => "Highlight",
        ]));
        return $fields;
    }
}
