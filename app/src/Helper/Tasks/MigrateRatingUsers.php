<?php

namespace App\Helper\Tasks;

use App\Ratings\Rating;
use App\User\User;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;

class MigrateRatingUsers extends BuildTask
{
    private static $segment = 'MigrateRatingUsers';

    protected $title = 'Migrate Rating Users';
    protected $description = 'Sets UserID on all Ratings where the UserID does not match a valid User, by looking up the mapping via LogEntry.OldUserID → NewUserID.';
    protected $enabled = true;

    public function run($request)
    {
        // Build a mapping from old Member IDs to new User IDs via LogEntry migration history
        $mappingRows = DB::query('
            SELECT DISTINCT le.OldUserID, le.NewUserID
            FROM LogEntry le
            WHERE le.OldUserID > 0 AND le.NewUserID > 0
        ');

        $mapping = [];
        foreach ($mappingRows as $row) {
            $mapping[$row['OldUserID']] = $row['NewUserID'];
        }

        echo 'Found ' . count($mapping) . ' OldUserID → NewUserID mappings.<br>';

        $fixed = 0;
        $skipped = 0;
        $notFound = 0;

        $ratings = Rating::get();
        foreach ($ratings as $rating) {
            // Check if UserID already points to a valid User
            if ($rating->UserID > 0 && User::get()->byID($rating->UserID)) {
                $skipped++;
                continue;
            }

            $oldUserID = $rating->UserID;

            if (isset($mapping[$oldUserID])) {
                $newUserID = $mapping[$oldUserID];
                $rating->UserID = $newUserID;
                $rating->write();
                echo 'Fixed Rating #' . $rating->ID . ': OldUserID ' . $oldUserID . ' → UserID ' . $newUserID . '<br>';
                $fixed++;
            } else {
                echo 'No mapping found for Rating #' . $rating->ID . ' (UserID=' . $oldUserID . ')<br>';
                $notFound++;
            }
        }

        echo '<br>Done! Fixed: ' . $fixed . ' | Already valid: ' . $skipped . ' | No mapping found: ' . $notFound;
    }
}
