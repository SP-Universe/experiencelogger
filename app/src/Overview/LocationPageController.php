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
        $currentUser = Security::getCurrentUser();

        if (isset($currentUser)) {
            $id = $this->getRequest()->param("ID");
            $exploded = explode("--", $id);
            $experience = Experience::get()->filter("ID", $exploded[0])->first();

            if (isset($experience)) {
                if (isset($_GET["weather"])) {
                    $weather = implode(',', $_GET["weather"]);
                }
                if (isset($_GET["train"])) {
                    $train = $_GET["train"];
                }
                if (isset($_GET["wagon"])) {
                    $wagon = $_GET["wagon"];
                }
                if (isset($_GET["row"])) {
                    $row = $_GET["row"];
                }
                if (isset($_GET["boat"])) {
                    $boat = $_GET["boat"];
                }
                if (isset($_GET["seat"])) {
                    $seat = $_GET["seat"];
                }
                if (isset($_GET["score"])) {
                    $score = $_GET["score"];
                }
                if (isset($_GET["podest"])) {
                    $podest = $_GET["podest"];
                }
                if (isset($_GET["variant"])) {
                    $variant = $_GET["variant"];
                }
                if (isset($_GET["version"])) {
                    $version = $_GET["version"];
                }
                if (isset($_GET["notes"])) {
                    $notes = $_GET["notes"];
                }

                $newlogentry = LogEntry::create();
                $newlogentry->ExperienceID = $id;

                if (isset($weather)) {
                    $newlogentry->Weather = $weather;
                }
                if (isset($train)) {
                    $newlogentry->Train = $train;
                }
                if (isset($wagon)) {
                    $newlogentry->Wagon = $wagon;
                }
                if (isset($row)) {
                    $newlogentry->Row = $row;
                }
                if (isset($boat)) {
                    $newlogentry->Boat = $boat;
                }
                if (isset($seat)) {
                    $newlogentry->Seat = $seat;
                }
                if (isset($score)) {
                    $newlogentry->Score = $score;
                }
                if (isset($podest)) {
                    $newlogentry->Podest = $podest;
                }
                if (isset($variant)) {
                    $newlogentry->Variant = $variant;
                }
                if (isset($version)) {
                    $newlogentry->Version = $version;
                }
                if (isset($notes)) {
                    $newlogentry->Notes = $notes;
                }
                $newlogentry->UserID = $currentUser->ID;
                $newlogentry->VisitTime = date("Y-m-d H:i:s");
                $newlogentry->Notes = $notes;
                $newlogentry->write();

                return $this->redirect($experience->Parent->Link);
            } else {
                echo "ERROR - The experience couldn't be found...";
            }
        } else {
            echo "You need to be logged in to add a log entry!";
        }
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
