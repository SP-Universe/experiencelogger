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
 * @property \App\Overview\LocationPage $dataRecord
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
        $title = $this->getRequest()->param("ID");
        $article = ExperienceLocation::get()->filter("LinkTitle", $title)->first();
        $success = false;

        //if get is set, then we are coming from the search page
        if (isset($_GET["success"])) {
            $success = $_GET["success"];
        }

        return array(
            "Success" => $success,
            "Location" => $article,
        );
    }

    public function experience()
    {
        $title = $this->getRequest()->param("ID");
        $article = Experience::get()->filter("LinkTitle", $title)->first();
        return array(
            "Experience" => $article,
        );
    }

    public function seatchart()
    {
        $title = $this->getRequest()->param("ID");

        $article = Experience::get()->filter("LinkTitle", $title)->first();
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
        $title = $this->getRequest()->param("ID");
        $id = Experience::get()->filter("LinkTitle", $title)->first()->ID;
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
        $title = $this->getRequest()->param("ID");
        $id = ExperienceLocation::get()->filter("LinkTitle", $title)->first()->ID;
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
        $title = $this->getRequest()->param("ID");
        $article = Experience::get()->filter("LinkTitle", $title)->first();

        return array(
            "Experience" => $article,
        );
    }

    public function finishLog()
    {
        $currentUser = Security::getCurrentUser();

        if (isset($currentUser)) {
            $title = $this->getRequest()->param("ID");
            $experience = Experience::get()->filter("LinkTitle", $title)->first();

            $newlogentry = LogEntry::create();
            $newlogentry->ExperienceID = $experience->ID;

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

                return $this->redirect($experience->Parent->Link . "?success=true");
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
