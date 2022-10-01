<?php

namespace App\Elements;

use SilverStripe\Security\Security;
use App\ExperienceDatabase\LogEntry;
use DNADesign\Elemental\Models\BaseElement;
use Symbiote\MemberProfiles\Pages\MemberProfilePage;

/**
 * Class \App\Elements\TextImageElement
 *
 * @property string $Text
 */
class LastLoggedElement extends BaseElement
{

    private static $db = [
        "Text" => "HTMLText",
    ];

    private static $field_labels = [
        "Text" => "Text",
    ];

    private static $table_name = 'LastLoggedElement';
    private static $icon = 'font-icon-block-promo-3';

    public function getType()
    {
        return "Last Logged Experiences";
    }

    public function getProfilePage()
    {
        return MemberProfilePage::get()->first();
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }

    public function getLastLogged()
    {
        $member = Security::getCurrentUser();
        if ($member) {
            $logs = LogEntry::get()->filter("UserID", $member->ID)->sort("VisitTime DESC")->limit(5);
            return $logs;
        }
    }
}
