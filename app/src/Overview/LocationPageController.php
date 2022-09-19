<?php
namespace App\Overview;

use App\ExperienceDatabase\Experience;
use PageController;
use App\ExperienceDatabase\ExperienceLocation;

/**
 * Class \App\Docs\DocsPageController
 *
 * @property \App\Overview\LocationPage dataRecord
 * @method \App\Overview\LocationPage data()
 * @mixin \App\Overview\LocationPage
 */
class LocationPageController extends PageController
{

    private static $allowed_actions = [
        "location",
        "experience"
    ];

    public function login($data, $form)
    {
        $session = $this->getRequest()->getSession();
        $session->set("PWD" . $this->URLSegment, $data["Password"]);
        return $this->redirect($this->Link());
    }

    public function logout($request)
    {
        $session = $this->getRequest()->getSession();
        $session->set("PWD" . $this->URLSegment, "");
        return $this->redirect($this->Link());
    }

    public function location()
    {
        $id = $this->getRequest()->param("ID");
        $deformatted = str_replace('_', ' ', $id);
        $deformatted = str_replace('%ae', 'ä', $deformatted);
        $deformatted = str_replace('%oe', 'ö', $deformatted);
        $deformatted = str_replace('%ue', 'ü', $deformatted);
        $article = ExperienceLocation::get()->filter("Title", $deformatted)->first();
        return array(
            "Location" => $article,
        );
    }

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

    public function getLocations()
    {
        return ExperienceLocation::get();
    }
}
