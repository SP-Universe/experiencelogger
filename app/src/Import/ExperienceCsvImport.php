<?php

namespace App\Import;

use App\ExperienceDatabase\ExperienceLocation;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;

/**
 * Staging record for a CSV import: holds the parsed diff plan between the
 * upload step and the review/confirm step.
 *
 * @property string $OriginalFilename
 * @property string $PlanJSON
 * @property string $Status
 * @property int $LocationID
 * @property int $SubmittedByID
 * @method \App\ExperienceDatabase\ExperienceLocation Location()
 * @method \SilverStripe\Security\Member SubmittedBy()
 */
class ExperienceCsvImport extends DataObject
{
    private static $db = [
        'OriginalFilename' => 'Varchar(255)',
        'PlanJSON' => 'Text',
        'Status' => "Enum('Pending,Confirmed', 'Pending')",
    ];

    private static $has_one = [
        'Location' => ExperienceLocation::class,
        'SubmittedBy' => Member::class,
    ];

    private static $table_name = 'ExperienceCsvImport';

    private static $singular_name = 'Attraction CSV Import';
    private static $plural_name = 'Attraction CSV Imports';

    private static $default_sort = 'Created DESC';

    private static $summary_fields = [
        'OriginalFilename' => 'File',
        'Location.Title' => 'Place',
        'Status' => 'Status',
        'SubmittedBy.Email' => 'Submitted by',
        'Created' => 'Uploaded',
    ];

    public function canView($member = null)
    {
        return Permission::check('CMS_ACCESS_NewsAdmin', 'any', $member);
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
