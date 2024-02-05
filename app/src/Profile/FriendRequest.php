<?php

namespace App\Profile;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use App\ExperienceDatabase\LogEntry;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Security\Permission;
use App\ExperienceDatabase\Experience;

/**
 * Class \App\Database\Experience
 *
 * @property string $FriendshipStatus
 * @property int $RequesterID
 * @property int $RequesteeID
 * @method \SilverStripe\Security\Member Requester()
 * @method \SilverStripe\Security\Member Requestee()
 */
class FriendRequest extends DataObject
{
    private static $db = [
        "FriendshipStatus" => "Enum('Pending, Accepted, Declined', 'Pending')",
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

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
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
