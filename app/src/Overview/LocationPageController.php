<?php
namespace App\Overview;

use DateInterval;
use App\Food\Food;
use PageController;
use App\Ratings\Rating;
use SilverStripe\ORM\GroupedList;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use App\ExperienceDatabase\LogEntry;
use SilverStripe\Control\HTTPRequest;
use App\ExperienceDatabase\Experience;
use App\ExperienceDatabase\ExperienceSeat;
use App\ExperienceDatabase\ExperienceType;
use App\ExperienceDatabase\ExperienceLocation;

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
            if ($currentUser->FavouritePlaces()->filter("ID", $id)->count() > 0) {
                $currentUser->FavouritePlaces()->removeByID($id);
            } else {
                $currentUser->FavouritePlaces()->add($id);
            }
        }

        if (isset($_GET["backurl"])) {
            $backUrl = $_GET["backurl"];
            return $this->redirect($backUrl);
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

    public function getPercent($all, $from)
    {
        if ($all > 0) {
            return round(($from / $all) * 100) . "%";
        } else {
            return 0 . "%";
        }
    }

    public function finishLog()
    {
        $currentUser = Security::getCurrentUser();

        if (isset($currentUser)) {
            $title = $this->getRequest()->param("ID");
            $experience = Experience::get()->filter("LinkTitle", $title)->first();

            $newlogentry = LogEntry::create();
            $newlogentry->ExperienceID = $experience->ID;

            if (isset($_GET["weather"])) {
                $weather = $_GET["weather"];
            }
            if ($experience->StageID != 0) {
                $stage = $experience->Stage;
            }


            //Find out if the area should be logged
            if ($experience->AreaID != 0) {
                if ($currentUser->LastLogDate != null) {
                    $lastLogDate = date("Y-m-d", strtotime($currentUser->LastLogDate));

                    if ($lastLogDate === date("Y-m-d")) {
                        if (isset($currentUser->LastLoggedAreaID)) {
                            if ($experience->AreaID != 0 && $experience->AreaID != $currentUser->LastLoggedAreaID) {
                                $area = $experience->Area;
                            }
                        }
                    } else {
                        $area = $experience->Area;
                    }
                } else {
                    $area = $experience->Area;
                }
            }


            if (isset($experience)) {
                if (isset($weather)) {
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

                if (isset($_GET["food"])) {
                    $newlogentry->FoodID = $_GET["food"];
                }

                $newlogentry->UserID = $currentUser->ID;
                $hours = $experience->Parent->Timezone - 1;
                $newlogentry->VisitTime = date("Y-m-d H:i:s", strtotime('+' . $hours . ' hours'));
                $newlogentry->write();

                if ($currentUser->LinkedLogging) {
                    if (isset($area)) {
                        $newlogentryArea = LogEntry::create();
                        $newlogentryArea->ExperienceID = $area->ID;
                        if (isset($weather)) {
                            $newlogentryArea->Weather = implode(',', $_GET["weather"]);
                        }
                        $newlogentryArea->UserID = $currentUser->ID;
                        $newlogentryArea->VisitTime = date("Y-m-d H:i:s", strtotime('+' . $hours . ' hours'));
                        $newlogentryArea->write();
                    }

                    if (isset($stage)) {
                        $newlogentryStage = LogEntry::create();
                        $newlogentryStage->ExperienceID = $stage->ID;
                        if (isset($weather)) {
                            $newlogentryStage->Weather = implode(',', $_GET["weather"]);
                        }
                        $newlogentryStage->UserID = $currentUser->ID;
                        $newlogentryStage->VisitTime = date("Y-m-d H:i:s", strtotime('+' . $hours . ' hours'));
                        $newlogentryStage->write();
                    }

                    $currentUser->LastLoggedAreaID = $experience->AreaID;
                    $currentUser->LastLogDate = date("Y-m-d H:i:s");
                    $currentUser->write();
                }

                if (isset($_GET["rating"])) {
                    $rating = $_GET["rating"];
                    $newrating = Rating::create();
                    $newrating->ExperienceID = $experience->ID;
                    $newrating->UserID = $currentUser->ID;
                    $newrating->Stars = $rating;
                    $newrating->LogEntries()->add($newlogentry);
                    $newlogentry->Votings()->add($newrating);
                    $newrating->write();
                }

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
