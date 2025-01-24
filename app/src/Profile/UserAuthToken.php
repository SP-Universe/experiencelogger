<?php

namespace App\ExperienceDatabase;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;

/**
 * Class \App\Database\ExperienceSeat
 *
 * @property string $DeviceName
 * @property string $Token
 * @property string $CreationDate
 * @property int $ParentID
 * @method \SilverStripe\Security\Member Parent()
 */
class UserAuthToken extends DataObject
{
    private static $db = [
        "DeviceName" => "Varchar(255)",
        "Token" => "Varchar(512)",
        "CreationDate" => "Datetime",
    ];

    private static $has_one = [
        "Parent" => Member::class,
    ];

    private static $api_access = false;

    private static $default_sort = "DeviceName ASC";

    private static $field_labels = [
        "DeviceName" => "Name of device",
        "Token" => "Token",
        "CreationDate" => "Creation Date",
    ];

    private static $summary_fields = [
        "DeviceName" => "Device",
    ];

    private static $searchable_fields = [
        "DeviceName",
    ];

    private static $table_name = "UserAuthToken";

    private static $singular_name = "User Auth Token";
    private static $plural_name = "User Auth Tokens";

    private static $url_segment = "userauthtokens";

    /**
     * Event handler called before writing to the database.
     *
     * @uses DataExtension->onAfterWrite()
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if (!$this->CreationDate) {
            $this->CreationDate = date("Y-m-d H:i:s");
        }
        if (!$this->Token) {
            $this->Token = bin2hex(random_bytes(64));
        }
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName("ParentID");
        return $fields;
    }

    public function canView($member = null)
    {
        return true;
    }

    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_NewsAdmin', 'any', $member);
    }

    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_NewsAdmin', 'any', $member);
    }

    public function canCreate($member = null, $context = [])
    {
        return Permission::check('CMS_ACCESS_NewsAdmin', 'any', $member);
    }
}
