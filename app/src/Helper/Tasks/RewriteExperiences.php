<?php
namespace App\Helper\Tasks;

use SilverStripe\Dev\BuildTask;
use App\ExperienceDatabase\Experience;

class RewriteExperiences extends BuildTask
{
    private static $segment = 'RewriteExperiences';

    protected $title = 'Rewrite all experiences';
    protected $description = 'A task that will rewrite all experiences in the database.';
    protected $enabled = true;

    public function run($request)
    {
        $experiences = Experience::get();
        foreach ($experiences as $experience) {
            echo 'Rewriting experience: ' . $experience->Title . '<br>';
            $experience->write();
        }
        exit('Done run!');
    }
}
