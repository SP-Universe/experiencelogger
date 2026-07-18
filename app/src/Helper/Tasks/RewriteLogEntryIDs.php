<?php

namespace App\Helper\Tasks;

use Override;
use Symfony\Component\Console\Input\InputInterface;
use SilverStripe\PolyExecution\PolyOutput;
use Symfony\Component\Console\Command\Command;
use SilverStripe\Dev\BuildTask;
use App\ExperienceDatabase\LogEntry;

class RewriteLogEntryIDs extends BuildTask
{
    protected static string $commandName = 'RewriteLogEntryIDs';

    protected string $title = 'Rewrite all LogEntries in the database';
    protected static string $description = 'A task that will rewrite all LogEntries in the database.';
    protected $enabled = true;

    #[Override]
    protected function execute(InputInterface $input, PolyOutput $output): int
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
        return Command::SUCCESS;
    }
}
