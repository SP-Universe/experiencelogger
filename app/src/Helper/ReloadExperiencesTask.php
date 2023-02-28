<?php

namespace App\Helper;

use App\ExperienceDatabase\Experience;
use SilverStripe\Dev\BuildTask;

class ReloadExperiencesTask extends BuildTask
{
    private static $segment = 'ReloadExperiencesTask';

    protected $title = 'Reload all Experiences';
    protected $description = 'A task that reloads all experiences (mainly) to refresh the json-code';
    protected $enabled = true;

    public function run($request)
    {
        $amountOfExperiences = Experience::get()->count();
        $experiencesReloaded = 1;
        echo "<h1>Started reloading " . $amountOfExperiences . " Experiences</h1>";
        foreach (Experience::get()->sort("ID") as $experience) {
            $experience->write();
            echo "<hr></hr>";
            echo "<p>(" . $experiencesReloaded . "/" . $amountOfExperiences . ") Reloaded ID " . $experience->ID . " - " . $experience->Title . "</p>";
            $experiencesReloaded++;
        }
        exit('<h1>All Experiences were reloaded and data saved!</h1>');
    }
}
