<?php

namespace App\Ratings;

use Override;
use SilverStripe\ORM\ManyManyList;
use App\User\User;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use App\ExperienceDatabase\LogEntry;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Security\Permission;
use App\ExperienceDatabase\Experience;

/**
 * Class \App\Database\Experience
 *
 * @property int $Stars
 * @property string $Text
 * @property int $ExperienceID
 * @property int $LegacyUserID
 * @property int $UserID
 * @method Experience Experience()
 * @method Member LegacyUser()
 * @method User User()
 * @method ManyManyList|LogEntry[] LogEntries()
 */
class Rating extends DataObject
{
    private static $db = [
        "Stars" => "Int",
        "Text" => "Varchar(255)",
    ];

    private static $api_access = false;

    private static $has_one = [
        "Experience" => Experience::class,
        "LegacyUser" => Member::class,
        "User" => User::class,
    ];

    private static $belongs_many_many = [
        "LogEntries" => LogEntry::class,
    ];

    private static $summary_fields = [
        "ID" => "ID",
        "Stars" => "Stars",
        "Experience.Title" => "Experience",
        "User.Username" => "User",
    ];

    private static $field_labels = [
        "Stars" => "Stars",
        "Text" => "Text",
    ];

    private static $default_sort = "ID DESC";

    private static $table_name = "Rating";

    private static $singular_name = "Rating";
    private static $plural_name = "Ratings";

    private static $url_segment = "ratings";

    #[Override]
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab("Root.Main", LiteralField::create("ExperienceTitle", "Experience Title: " . $this->Experience()->Title), "Experience");

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
}
