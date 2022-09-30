<?php
namespace App\Overview;

use PageController;
use SilverStripe\Security\Security;
use SilverStripe\Control\HTTPRequest;
use App\ExperienceDatabase\Experience;
use App\ExperienceDatabase\ExperienceType;
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
        "experience",
        "changeFavourite",
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
        $exploded = explode("--", $id);

        $article = Experience::get()->filter("ID", $exploded[0])->first();
        return array(
            "Experience" => $article,
        );
    }

    public function changeFavourite()
    {
        $id = $this->getRequest()->param("ID");
        $currentUser = Security::getCurrentUser();

        if ($currentUser) {
            if ($currentUser->FavouritePlaces()->find("ID", $id)) {
                $currentUser->FavouritePlaces()->removeByID($id);
            } else {
                $currentUser->FavouritePlaces()->add($id);
            }
        }

        return $this->redirect($this->Link());
    }

    public function getLocations()
    {
        return ExperienceLocation::get();
    }
}
