<?php

namespace App\Helper\Tasks;

use App\User\User;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Security\Member;
use App\ExperienceDatabase\LogEntry;

class RewriteLogEntryIDs extends BuildTask
{
    private static $segment = 'RewriteLogEntryIDs';

    protected $title = 'Rewrite all LogEntries in the database';
    protected $description = 'A task that will rewrite all LogEntries in the database.';
    protected $enabled = true;

    public function run($request)
    {
        $logentries = LogEntry::get();
        foreach ($logentries as $log) {
            echo 'Rewriting log: ' . $log->VisitTime . '<br>';

            if ($log->NewUserID && $log->UserID) {
                $log->UserID = $log->NewUserID;
                $log->write();
            }

            $log->write();
        }
        exit('Done run!');
    }
}
