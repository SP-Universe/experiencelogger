<?php
namespace App\Overview;

use PageController;
use SilverStripe\ORM\GroupedList;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use App\ExperienceDatabase\LogEntry;
use SilverStripe\Control\HTTPRequest;
use App\ExperienceDatabase\Experience;
use App\ExperienceDatabase\ExperienceType;
use App\ExperienceDatabase\ExperienceLocation;
use App\ExperienceDatabase\ExperienceSeat;

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
        "seatchart",
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

    public function seatchart()
    {
        $id = $this->getRequest()->param("ID");
        $exploded = explode("--", $id);

        $article = Experience::get()->filter("ID", $exploded[0])->first();
        return array(
            "Experience" => $article,
        );
    }

    public function getLogs()
    {
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            return GroupedList::create(LogEntry::get()->filter("UserID", $currentUser->ID)->sort("VisitTime", "DESC"));
        }
    }

    public function getLogCountForSeat($train, $wagon, $row, $seat)
    {
        $id = $this->getRequest()->param("ID");
        $currentUser = Security::getCurrentUser();
        if ($currentUser) {
            return LogEntry::get()->filter([
                "ExperienceID" => $id,
                "UserID" => $currentUser->ID,
                "Train" => $train,
                "Wagon" => $wagon,
                "Row" => $row,
                "Seat" => $seat,
            ])->count();
        }
    }

    public function getTypeForSeat($train, $wagon, $row, $seat)
    {
        $id = $this->getRequest()->param("ID");
        return ExperienceSeat::get()->filter([
            "ParentID" => $id,
            "Train" => $train,
            "Wagon" => $wagon,
            "Row" => $row,
            "Seat" => $seat,
        ])->first()->Type;
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

            $newlogentry = LogEntry::create();
            $newlogentry->ExperienceID = $id;

            if (isset($experience)) {
                if (isset($_GET["weather"])) {
                    $newlogentry->Weather = implode(',', $_GET["weather"]);
                }
                if (isset($_GET["train"])) {
                    $newlogentry->Train = $_GET["train"];
                }
                if (isset($_GET["wagon"])) {
                    $newlogentry->Wagon = $_GET["wagon"];
                }
                if (isset($_GET["row"])) {
                    $newlogentry->Row = $_GET["row"];
                }
                if (isset($_GET["seat"])) {
                    $newlogentry->Seat = $_GET["seat"];
                }
                if (isset($_GET["score"])) {
                    $newlogentry->Score = $_GET["score"];
                }
                if (isset($_GET["podest"])) {
                    $newlogentry->Podest = $_GET["podest"];
                }
                if (isset($_GET["variant"])) {
                    $newlogentry->Variant = $_GET["variant"];
                }
                if (isset($_GET["version"])) {
                    $newlogentry->Version = $_GET["version"];
                }
                if (isset($_GET["notes"])) {
                    $newlogentry->Notes = $_GET["notes"];
                }

                $newlogentry->UserID = $currentUser->ID;
                $newlogentry->VisitTime = date("Y-m-d H:i:s");
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
