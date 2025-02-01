<?php

namespace App\User;

use App\User\User;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;

/**
 * Class \App\User\AuthToken
 *
 * @property string $Token
 * @property string $LastLogin
 * @property string $DeviceName
 * @property int $ParentID
 * @method \App\User\User Parent()
 */
class AuthToken extends DataObject
{
    private static $db = [
        "Token" => "Varchar(255)",
        "LastLogin" => "Datetime",
        "DeviceName" => "Varchar(255)",
    ];

    private static $has_one = [
        "Parent" => User::class
    ];

    private static $default_sort = "LastLogin ASC";

    private static $field_labels = [
        "DeviceName" => "Device Name",
        "LastLogin" => "Last Login",
    ];

    private static $summary_fields = [
        "DeviceName",
        "LastLogin",
    ];

    private static $searchable_fields = [
        "DeviceName",
    ];

    private static $table_name = "AuthToken";

    private static $singular_name = "Auth-Token";
    private static $plural_name = "Auth-Tokens";

    private static $url_segment = "authtoken";

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

    public function updateLastLogin()
    {
        $this->LastLogin = date("Y-m-d H:i:s");
        $this->write();
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if (!$this->Token) {
            $this->Token = bin2hex(random_bytes(64));
        }
    }
}
