<?php

namespace App\ExperienceDatabase;

use SilverStripe\ORM\DataObject;

/**
 * Class \App\Database\Location
 *
 * @property string $Title
 * @property string $Type
 * @property string $OpeningDate
 * @property string $Address
 * @property string $Description
 */
class ExperienceLocation extends DataObject
{
    private static $db = [
        "Title" => "Varchar(255)",
        "Type" => "Enum('Themepark, Escaperoom, Roadside-Attraction, ScareHouse, Water Park, Convention, Other', 'Other')",
        "OpeningDate" => "Date",
        "Address" => "Varchar(255)",
        "Description" => "HTMLText",
    ];

    private static $default_sort = "Title ASC";

    private static $field_labels = [
        "Title" => "Titel",
        "Type" => "Typ",
        "OpeningDate" => "Eröffnung",
        "Address" => "Adresse",
        "Description" => "Beschreibung",
    ];

    private static $summary_fields = [
        "Title" => "Titel",
    ];

    private static $searchable_fields = [
        "Title", "Type", "Description",
    ];

    private static $table_name = "ExperienceLocation";

    private static $singular_name = "Location";
    private static $plural_name = "Locations";

    private static $url_segment = "location";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }
}
