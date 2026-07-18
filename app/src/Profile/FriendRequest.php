<?php

namespace App\Profile;

use Override;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;

/**
 * Class \App\Database\Experience
 *
 * @property string $FriendshipStatus
 * @property string $FriendsSince
 * @property int $RequesterID
 * @property int $RequesteeID
 * @method Member Requester()
 * @method Member Requestee()
 */
class FriendRequest extends DataObject
{
    private static $db = [
        "FriendshipStatus" => "Enum('Pending, Accepted, Declined', 'Pending')",
        "FriendsSince" => "Datetime",
    ];

    private static $api_access = false;

    private static $has_one = [
        "Requester" => Member::class,
        "Requestee" => Member::class,
    ];

    private static $summary_fields = [
        "Requester.Nickname" => "Requester",
        "Requestee.Nickname" => "Requestee",
    ];

    private static $default_sort = "ID DESC";

    private static $table_name = "FriendRequest";

    private static $singular_name = "Friendrequest";
    private static $plural_name = "Friendrequests";

    private static $url_segment = "friendrequests";

    #[Override]
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }

    #[Override]
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
    }

    #[Override]
    public function canView($member = null)
    {
        return true;
    }

    #[Override]
    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_NewsAdmin', 'any', $member);
    }

    #[Override]
    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_NewsAdmin', 'any', $member);
    }

    #[Override]
    public function canCreate($member = null, $context = [])
    {
        return Permission::check('CMS_ACCESS_NewsAdmin', 'any', $member);
    }

    public function FormattedFriendsSince()
    {
        if (!$this->FriendsSince) {
            return "";
        }
        return date("d.m.y", strtotime($this->FriendsSince));
    }
}
