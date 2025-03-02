<?php

namespace App\Helper\Tasks;

use App\User\User;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Security\Member;
use App\ExperienceDatabase\LogEntry;

class TransformMembersToUsers extends BuildTask
{
    private static $segment = 'TransformMembersToUsers';

    protected $title = 'Transform all Members into Users';
    protected $description = 'A task that will rewrite all LogEntries in the database.';
    protected $enabled = true;

    public function run($request)
    {
        $logentries = LogEntry::get();
        foreach ($logentries as $log) {
            echo 'Rewriting log: ' . $log->VisitTime . '<br>';

            if ($log->UserID) {
                $member = Member::get()->byID($log->UserID);
                if ($member) {
                    $user = User::get()->filter('Email', $member->Email)->first();
                    if (!$user) {
                        $user = User::create();
                        $user->Email = $member->Email;
                        $user->Username = $member->Username;
                        $user->Nickname = $member->Nickname;
                        $user->HasPremium = $member->HasPremium;
                        $user->LinkedLogging = $member->LinkedLogging;
                        $user->ProfilePrivacy = $member->ProfilePrivacy;
                        $user->DateOfBirth = $member->DateOfBirth;
                        $user->write();
                    }
                    $log->OldUserID = $member->ID;
                    $log->NewUserID = $user->ID;
                    if ($member->Email != "admin") {
                        $member->UserID = $user->ID;
                        $member->write();
                    }
                }
            }

            $log->write();
        }
        exit('Done run!');
    }
}
