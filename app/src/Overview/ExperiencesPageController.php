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
        $exploded = explode("--", $id);

        $article = Experience::get()->filter("ID", $exploded[0])->first();
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
