<?php
namespace App\Helper\Tasks;

use Override;
use Symfony\Component\Console\Input\InputInterface;
use SilverStripe\PolyExecution\PolyOutput;
use Symfony\Component\Console\Command\Command;
use SilverStripe\Dev\BuildTask;
use App\ExperienceDatabase\Experience;

class RewriteExperiences extends BuildTask
{
    protected static string $commandName = 'RewriteExperiences';

    protected string $title = 'Rewrite all experiences';
    protected static string $description = 'A task that will rewrite all experiences in the database.';
    protected $enabled = true;

    #[Override]
    protected function execute(InputInterface $input, PolyOutput $output): int
    {
        $experiences = Experience::get();
        foreach ($experiences as $experience) {
            echo 'Rewriting experience: ' . $experience->Title . '<br>';
            $experience->write();
        }
        exit('Done run!');
        return Command::SUCCESS;
    }
}
