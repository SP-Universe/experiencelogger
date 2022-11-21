<?php

namespace App\Elements;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Assets\Image;

/**
 * Class \App\Elements\TextImageElement
 *
 * @property string $Text
 * @property int $ImageID
 * @method \SilverStripe\Assets\Image Image()
 */
class TextImageElement extends BaseElement
{

    private static $db = [
        "Text" => "HTMLText",
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
    ];

    private static $table_name = 'TextImageElement';
    private static $icon = 'font-icon-block-promo-3';

    public function getType()
    {
        return "Text + Image";
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }
}
