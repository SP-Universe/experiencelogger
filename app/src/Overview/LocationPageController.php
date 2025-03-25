<?php

namespace App\Overview;

use App\User\User;
use PageController;
use App\Ratings\Rating;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\ArrayList;
use App\Helper\ExperienceHelper;
use App\Helper\StatisticsHelper;
use SilverStripe\ORM\GroupedList;
use SilverStripe\Security\Security;
use App\ExperienceDatabase\LogEntry;
use App\ExperienceDatabase\Experience;
use SilverStripe\ORM\Queries\SQLSelect;
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
        "addnewlog",
        "seatchart",
        "statistics",
        "finishLog",
    ];

    public function location()
    {
        $currentMember = Security::getCurrentUser();
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
        $logs = DataList::create(LogEntry::class);
        $ratings = DataList::create(Rating::class);
        if (!$currentUser) {
            $currentUser = null;
            $logs = null;
        } else {
            $logs = LogEntry::get()->filter("UserID", $currentUser->ID);
        }
        $ratings = Rating::get();

        $title = $this->getRequest()->param("ID");
        $sqlRequest = new SQLSelect("Location.Title AS Title");
        $sqlRequest->setFrom('ExperienceLocation AS Location');
        $sqlRequest->addWhere(["Location.LinkTitle" => $title]);

        $sqlRequest->addLeftJoin('ExperienceLocationType', '"Location"."TypeID" = "LocationType"."ID"', 'LocationType');
        $sqlRequest->addLeftJoin('Experience', '"Experience"."ParentID" = "Location"."ID"', 'Experience');
        $sqlRequest->addLeftJoin('ExperienceType', '"ExperienceType"."ID" = "Experience"."TypeID"', 'ExperienceType');

        $sqlRequest->addSelect('Location.Title AS LocationTitle');
        $sqlRequest->addSelect('Location.ID AS LocationID');
        $sqlRequest->addSelect('Location.Description AS LocationDescription');
        $sqlRequest->addSelect('Location.Address AS LocationAddress');
        $sqlRequest->addSelect('Location.Coordinates AS LocationCoordinates');
        $sqlRequest->addSelect('Location.Website AS LocationWebsite');
        $sqlRequest->addSelect('Location.ImageID AS LocationImageID');
        $sqlRequest->addSelect('Location.LinkTitle AS LocationLinkTitle');

        $sqlRequest->addSelect('Experience.ID AS ExperienceID');
        $sqlRequest->addSelect('Experience.Title AS ExperienceTitle');
        $sqlRequest->addSelect('Experience.LinkTitle AS ExperienceLinkTitle');
        $sqlRequest->addSelect('Experience.State AS ExperienceState');
        $sqlRequest->addSelect('Experience.JSONCode AS ExperienceJSONCode');
        $sqlRequest->addSelect('Experience.Coordinates AS ExperienceCoordinates');
        $sqlRequest->addSelect('Experience.HasOnridePhoto AS ExperienceHasOnridePhoto');
        $sqlRequest->addSelect('Experience.HasFastpass AS ExperienceHasFastpass');
        $sqlRequest->addSelect('Experience.HasSingleRider AS ExperienceHasSingleRider');
        $sqlRequest->addSelect('Experience.AccessibleToHandicapped AS ExperienceAccessibleToHandicapped');
        $sqlRequest->addSelect('Experience.AreaID AS AreaID');

        $sqlRequest->addSelect('ExperienceType.ID AS ExperienceTypeID');
        $sqlRequest->addSelect('ExperienceType.Title AS ExperienceTypeTitle');

        $sqlRequest->addSelect('LocationType.Title AS LocationTypeTitle');

        $sqlResult = $sqlRequest->execute();

        $data = [];

        //debug display all data in echo
        //echo "<pre>";
        //print_r($sqlResult);

        foreach ($sqlResult as $row) {
            $data[] = $row;
        }

        //Create the location object
        $location = ExperienceLocation::create();
        $location->Title = $data[0]["LocationTitle"];
        $location->LinkTitle = $data[0]["LocationLinkTitle"];
        $location->ID = $data[0]["LocationID"];
        $location->Description = $data[0]["LocationDescription"];
        $location->Address = $data[0]["LocationAddress"];
        $location->Coordinates = $data[0]["LocationCoordinates"];
        $location->Website = $data[0]["LocationWebsite"];
        $location->ImageID = $data[0]["LocationImageID"];

        //Create the location type object
        $locationType = ExperienceType::create();
        $locationType->Title = $data[0]["LocationTypeTitle"];

        //Create all experience types
        $experienceTypes = ArrayList::create();
        $experiences = ArrayList::create();

        foreach ($data as $row) {
            if ($row["ExperienceTypeID"] != null) {
                //ExperienceTypes
                $experienceType = ExperienceType::create();
                $experienceType->Title = $row["ExperienceTypeTitle"];
                $experienceType->ID = $row["ExperienceTypeID"];
                $experienceTypes->push($experienceType);
            }

            if ($row["ExperienceID"] != null) {
                //Experiences
                $experience = Experience::create();
                $experience->Title = $row["ExperienceTitle"];
                $experience->ID = $row["ExperienceID"];
                $experience->State = $row["ExperienceState"];
                $experience->TypeTitle = $experienceTypes->find('ID', $row["ExperienceTypeID"])->Title;
                $experience->JSONCode = $row["ExperienceJSONCode"];
                $experience->TypeID = $row["ExperienceTypeID"];
                $experience->Coordinates = $row["ExperienceCoordinates"];
                $experience->LinkTitle = $row["ExperienceLinkTitle"];
                $experience->ExperienceLink = $this->Link() . "/experience/" . $title . "---" . $experience->LinkTitle;
                $experience->ExperienceAddLogLink = $this->Link() . "/addnewlog/" . $title . "---" . $experience->LinkTitle;
                $experience->HasOnridePhoto = $row["ExperienceHasOnridePhoto"];
                $experience->HasFastpass = $row["ExperienceHasFastpass"];
                $experience->HasSingleRider = $row["ExperienceHasSingleRider"];
                $experience->AccessibleToHandicapped = $row["ExperienceAccessibleToHandicapped"];
                $experience->AreaID = $row["AreaID"];

                $experiences->push($experience);
            }
        }

        $characterTypeID = ExperienceType::get()->find('Title', 'Character')->ID;

        $experiencesNotCharacters = ArrayList::create();
        $characters = ArrayList::create();
        foreach ($experiences as $experience) {
            if ($experience->AreaID != 0) {
                $area = $experiences->find('ID', $experience->AreaID);
                $experience->AreaTitle = $area->Title;
                $experience->AreaLinkTitle = $area->LinkTitle;
                $experience->AreaLink = $this->Link() . "/experience/" . $title . "---" . $experience->AreaLinkTitle;
            }

            if ($experience->TypeID != $characterTypeID) {
                //check if similar experienceTitle is already in the list (Bad Fix!)
                $similarExperience = $experiencesNotCharacters->find('Title', $experience->Title);
                if (!$similarExperience) {
                    $experiencesNotCharacters->push($experience);
                }
            } else {
                $similarExperience = $characters->find('Title', $experience->Title);
                if (!$similarExperience) {
                    $characters->push($experience);
                }
            }
        }
        $experiencesNotCharacters = $experiencesNotCharacters->sort('State ASC');
        $groupedExperiences = GroupedList::create($experiencesNotCharacters);

        $success = false;
        if (isset($_GET["success"])) {
            $success = $_GET["success"];
        }

        return array(
            "Location" => $location,
            "LocationType" => $locationType,
            "Experiences" => $experiences,
            "GroupedExperiences" => $groupedExperiences,
            "Characters" => $characters,
            "Success" => $success,
        );
    }

    public function experience()
    {
        $title = $this->getRequest()->param("ID");
        $park = ExperienceLocation::get()->filter("LinkTitle", explode("---", $title)[0])->first();
        $title = explode("---", $title)[1];
        $experience = Experience::get()->filter(array(
            "LinkTitle" => $title,
            "ParentID" => $park->ID
        ))->first();

        return array(
            "Experience" => $experience,
        );
    }

    public function seatchart()
    {
        $title = $this->getRequest()->param("ID");
        $park = ExperienceLocation::get()->filter("LinkTitle", explode("---", $title)[0])->first();
        $title = explode("---", $title)[1];
        $experience = Experience::get()->filter(array(
            "LinkTitle" => $title,
            "ParentID" => $park->ID
        ))->first();

        return array(
            "Experience" => $experience,
        );
    }

    public function statistics()
    {
        $title = $this->getRequest()->param("ID");
        $park = ExperienceLocation::get()->filter("LinkTitle", explode("---", $title)[0])->first();
        $title = explode("---", $title)[1];
        $experience = Experience::get()->filter(array(
            "LinkTitle" => $title,
            "ParentID" => $park->ID
        ))->first();

        $percentOfLogs = StatisticsHelper::getPercentAsNumber($experience->TotalLogCount, $experience->Logs->Count(), 2);

        $currentUser = Security::getCurrentUser();
        $averageLogsPerVisit = StatisticsHelper::getAverageLogsOfExperiencePerVisit($currentUser->ID, $experience);

        return array(
            "Experience" => $experience,
            "PercentOfLogs" => $percentOfLogs,
            "AverageLogsPerVisit" => $averageLogsPerVisit,
        );
    }

    public function getLogCountForSeat($train, $wagon, $row, $seat)
    {
        $title = $this->getRequest()->param("ID");
        $park = ExperienceLocation::get()->filter("LinkTitle", explode("---", $title)[0])->first();
        $title = explode("---", $title)[1];
        $experience = Experience::get()->filter(array(
            "LinkTitle" => $title,
            "ParentID" => $park->ID
        ))->first();

        $id = $experience->ID;
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
        $currentMember = Security::getCurrentUser();
        if (!$currentMember) {
            return;
        }
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();

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

    public function addnewlog()
    {
        $title = $this->getRequest()->param("ID");
        $park = ExperienceLocation::get()->filter("LinkTitle", explode("---", $title)[0])->first();
        $title = explode("---", $title)[1];
        $experience = Experience::get()->filter(array(
            "LinkTitle" => $title,
            "ParentID" => $park->ID
        ))->first();

        $now = date("Y-m-d H:i:s");
        $currentDate = date("Y-m-d", strtotime($now));
        $currentTime = date("H:i", strtotime($now));

        return array(
            "Experience" => $experience,
            "CurrentDate" => $currentDate,
            "CurrentTime" => $currentTime,
        );
    }

    public function finishLog()
    {
        $currentMember = Security::getCurrentUser();
        $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();

        if (isset($currentUser)) {
            $title = $this->getRequest()->param("ID");
            $park = ExperienceLocation::get()->filter("LinkTitle", explode("---", $title)[0])->first();
            $title = explode("---", $title)[1];
            $experience = Experience::get()->filter(array(
                "LinkTitle" => $title,
                "ParentID" => $park->ID
            ))->first();

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
                //Only try logging area if there is an AreaID
                if (isset($_GET["date"])) {
                    if (ExperienceHelper::getWillLinkLogArea($experience, $_GET["date"])) {
                        $area = $experience->Area;
                    }
                } else {
                    if (ExperienceHelper::getWillLinkLogArea($experience, date("Y-m-d"))) {
                        $area = $experience->Area;
                    }
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

                if (isset($_GET["date"])) {
                    $newlogentry->VisitTime = date("Y-m-d H:i:s", strtotime($_GET["date"] . " " . $_GET["time"]));
                } else {
                    $newlogentry->VisitTime = date("Y-m-d H:i:s", strtotime('+' . $hours . ' hours'));
                }

                if ($currentUser->LinkedLogging) {
                    if (isset($area)) {
                        $newlogentryArea = LogEntry::create();
                        $newlogentryArea->ExperienceID = $area->ID;
                        if (isset($weather)) {
                            $newlogentryArea->Weather = implode(',', $_GET["weather"]);
                        }
                        $newlogentryArea->UserID = $currentUser->ID;
                        if (isset($_GET["date"])) {
                            $newlogentryArea->VisitTime = date("Y-m-d H:i:s", strtotime($_GET["date"] . " " . $_GET["time"]));
                        } else {
                            $newlogentryArea->VisitTime = date("Y-m-d H:i:s", strtotime('+' . $hours . ' hours'));
                        }
                        $newlogentryArea->IsLinkedLogged = true;
                        $newlogentryArea->write();
                        $currentUser->LastLoggedArea = $newlogentryArea->Experience;
                    }


                    if (isset($stage)) {
                        $newlogentryStage = LogEntry::create();
                        $newlogentryStage->ExperienceID = $stage->ID;
                        if (isset($weather)) {
                            $newlogentryStage->Weather = implode(',', $_GET["weather"]);
                        }
                        $newlogentryStage->UserID = $currentUser->ID;

                        if (isset($_GET["date"])) {
                            $newlogentryStage->VisitTime = date("Y-m-d H:i:s", strtotime($_GET["date"] . " " . $_GET["time"]));
                        } else {
                            $newlogentryStage->VisitTime = date("Y-m-d H:i:s", strtotime('+' . $hours . ' hours'));
                        }
                        $newlogentryStage->IsLinkedLogged = true;
                        $newlogentryStage->write();
                    }

                    //Always use current time for last logged:
                    $currentUser->LastLogDate = date("Y-m-d H:i:s", strtotime('+' . $hours . ' hours'));

                    $currentUser->write();
                }

                if (isset($_GET["rating"])) {
                    $rating = $_GET["rating"];
                    if ($rating > 0) {
                        $newrating = Rating::create();
                        $newrating->ExperienceID = $experience->ID;
                        $newrating->UserID = $currentUser->ID;
                        $newrating->Stars = $rating;
                        $newrating->LogEntries()->add($newlogentry);
                        $newlogentry->Votings()->add($newrating);
                        $totalratings = $experience->NumberOfRatings;
                        $experiencerating = $experience->Rating;
                        $calculatedrating = (($experiencerating * $totalratings) + $rating) / ($totalratings + 1);
                        $experience->Rating = $calculatedrating;
                        $experience->NumberOfRatings = $totalratings + 1;
                        $experience->write();
                        $newrating->write();
                    }
                }

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
        return User::get();
    }
}
