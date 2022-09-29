<?php
namespace App\Overview;

use App\ExperienceDatabase\Experience;
use App\ExperienceDatabase\ExperienceType;
use PageController;

/**
 * Class \App\Docs\DocsPageController
 *
 * @property \App\Overview\ExperiencesPage dataRecord
 * @method \App\Overview\ExperiencesPage data()
 * @mixin \App\Overview\ExperiencesPage
 */
class ExperiencesPageController extends PageController
{

    private static $allowed_actions = [
        "experience"
    ];

    public function experience()
    {
        $id = $this->getRequest()->param("ID");
        $deformatted = str_replace('_', ' ', $id);
        $deformatted = str_replace('%ae', 'ä', $deformatted);
        $deformatted = str_replace('%oe', 'ö', $deformatted);
        $deformatted = str_replace('%ue', 'ü', $deformatted);
        $article = Experience::get()->filter("Title", $deformatted)->first();
        return array(
            "Experience" => $article,
        );
    }

    public function getExperiences()
    {
        return Experience::get();
    }

    public function getTypes()
    {
        return ExperienceType::get();
    }
}
