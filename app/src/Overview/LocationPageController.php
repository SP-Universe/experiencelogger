<?php
namespace App\Overview;

use PageController;
use SilverStripe\Security\Security;
use SilverStripe\Control\HTTPRequest;
use App\ExperienceDatabase\Experience;
use App\ExperienceDatabase\ExperienceType;
use App\ExperienceDatabase\ExperienceLocation;
use App\ExperienceDatabase\LogEntry;
use SilverStripe\Security\Member;

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
        "addLog",
        "finishLog",
    ];

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

    public function addLog()
    {
        $id = $this->getRequest()->param("ID");
        $exploded = explode("--", $id);
        $article = Experience::get()->filter("ID", $exploded[0])->first();

        return array(
            "Experience" => $article,
        );
    }

    public function finishLog()
    {
        $id = $this->getRequest()->param("ID");

        $exploded = explode("--", $id);
        $experience = Experience::get()->filter("ID", $exploded[0])->first();

        $currentUser = Security::getCurrentUser();

        if (isset($_GET["weather"])) {
            $weather = $_GET["weather"];
        } else {
            $weather = "Unknown";
        }
        if (isset($_GET["train"])) {
            $train = $_GET["train"];
        } else {
            $train = "-1";
        }
        if (isset($_GET["wagon"])) {
            $wagon = $_GET["wagon"];
        } else {
            $wagon = "-1";
        }
        if (isset($_GET["row"])) {
            $row = $_GET["row"];
        } else {
            $row = "-1";
        }
        if (isset($_GET["seat"])) {
            $seat = $_GET["seat"];
        } else {
            $seat = "-1";
        }
        if (isset($_GET["score"])) {
            $score = $_GET["score"];
        } else {
            $score = "-1";
        }
        if (isset($_GET["notes"])) {
            $notes = $_GET["notes"];
        } else {
            $notes = "-1";
        }

        $newlogentry = LogEntry::create();
        $newlogentry->ExperienceID = $id;
        $newlogentry->Weather = $weather;
        $newlogentry->Train = $train;
        $newlogentry->Wagon = $wagon;
        $newlogentry->Row = $row;
        $newlogentry->Seat = $seat;
        $newlogentry->Score = $score;
        $newlogentry->UserID = $currentUser->ID;
        $newlogentry->VisitTime = date("Y-m-d H:i:s");
        $newlogentry->Notes = $notes;
        $newlogentry->write();

        return $this->redirect($experience->Parent->Link);
    }

    public function getLocations()
    {
        return ExperienceLocation::get();
    }

    public function getUsers()
    {
        return Member::get();
    }
}
