<?php

namespace App\Api {

    use App\Api\ApiActions\ApiAction_authenticateUser;
    use App\Api\ApiActions\ApiAction_loginUser;
    use App\Api\ApiActions\ApiAction_logoutUser;
    use App\Api\ApiActions\ApiAction_registerUser;
    use App\Api\ApiActions\ApiAction_allexperiences;
    use App\Api\ApiActions\ApiAction_allplaces;
    use App\Api\ApiActions\ApiAction_news;
    use App\Api\ApiActions\ApiAction_alllogs;
    use App\Api\ApiActions\ApiAction_addLog;
    use SilverStripe\Assets\Image;
    use App\ExperienceDatabase\Experience;
    use App\ExperienceDatabase\ExperienceData;
    use App\ExperienceDatabase\ExperienceLocation;
    use App\ExperienceDatabase\LogEntry;
    use App\Overview\LocationPage;
    use App\Ratings\Rating;
    use App\User\User;
    use SilverStripe\Control\HTTPRequest;
    use SilverStripe\Core\Injector\Injector;
    use SilverStripe\Security\IdentityStore;
    use SilverStripe\Security\Security;
    use SilverStripe\CMS\Controllers\ContentController;
    use SilverStripe\ORM\Queries\SQLSelect;

    /**
     * Class \PageController
     *
     * @property \App\Api\ApiPage $dataRecord
     * @method \App\Api\ApiPage data()
     * @mixin \App\Api\ApiPage
     */
    class ApiPageController extends ContentController
    {
        private static $allowed_actions = [
            "login",
            "experiences",
            "locationprogress",
            "logCountForExperience",
            "ratingForExperience",
            "places",
            "addnewlog",
            "profile",
            "checkLogin",
            "imagebyid",

            "authenticateUser",
            "news",
            "loginUser",
            "logoutUser",
            "registerUser",
            "allexperiences",
            "allplaces",
            "alllogs",
            "addLog"
        ];

        public function logout(HTTPRequest $request)
        {
            if ($member = Security::getCurrentUser()) {
                Injector::inst()->get(IdentityStore::class)->logOut($request);
            }
            return $this->redirect('home');
        }

        public function experiences(HTTPRequest $request)
        {
            $id = $this->getRequest()->param("ID");

            $currentMember = Security::getCurrentUser();
            if ($currentMember) {
                $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
            }

            if (isset($_GET['ID'])) {
                $id = intval($_GET['ID']);
                $experiences = Experience::get()->filter("ID", $id)->sort('Title', 'ASC');
            } elseif (isset($_GET['ParkID'])) {
                $id = intval($_GET['ParkID']);
                $experiences = Experience::get()->filter("ParentID", $id)->sort('Title', 'ASC');
            } else {
                $experiences = Experience::get()->sort('Title', 'ASC');

                if (isset($_GET['Title'])) {
                    $experiences = $experiences->filter("Title:PartialMatch", $_GET['Title']);
                }
                if (isset($_GET['LocationID'])) {
                    $experiences = $experiences->filter("ParentID", $_GET['LocationID']);
                }
                if (isset($_GET['LocationTitle'])) {
                    $location = ExperienceLocation::get()->filter("Title:PartialMatch", $_GET['LocationTitle'])->first();
                    if ($location) {
                        $experiences = $experiences->filter("ParentID", $location->ID);
                    }
                }
                if (isset($_GET['Type'])) {
                    $experiences = $experiences->filter("Type", $_GET['Type']);
                }
            }

            if ($experiences) {
                $lastedited = $this->getLastEdited();
                $data['LastEdited']['US'] = date("Y-m-d H:i:s", $lastedited);
                $data['LastEdited']['EU'] = date("d.m.Y H:i:s", $lastedited);
                $data['LastEdited']['Timestamp'] = $lastedited;

                $data["Count"] = count($experiences);
                $data["LoggedIn"] = $currentUser ? true : false;

                foreach ($experiences as $experience) {
                    $key = $experience->Title;
                    $data['items'][$key]['ID'] = $experience->ID;
                    $data['items'][$key]['Title'] = $experience->Title;
                    $data['items'][$key]['LinkTitle'] = $experience->LinkTitle;
                    $data['items'][$key]['DetailsLink'] = $experience->getLink();
                    $data['items'][$key]['LoggingLink'] = $experience->getAddLogLink();
                    if ($experience->Description) {
                        $data['items'][$key]['Description'] = $experience->Description;
                    }
                    if ($experience->PhotoGalleryImages()->first()) {
                        $data['items'][$key]['Image'] = $experience->PhotoGalleryImages()->first()->Image()->getAbsoluteURL();
                    }
                    $data['items'][$key]['Location'] = $experience->Parent->Title;
                    $data['items'][$key]['LocationID'] = $experience->ParentID;
                    $data['items'][$key]['Type'] = $experience->Type->Title;
                    $data['items'][$key]['Area'] = $experience->Area->Title;
                    $data['items'][$key]['State'] = $experience->State;
                    $data['items'][$key]['Coordinates'] = $experience->Coordinates;
                    $data['items'][$key]['LastEdited'] = $experience->LastEdited;
                    $data['items'][$key]['Parent']['ID'] = $experience->ParentID;

                    //Load Experience Datas:
                    $experiencedata = ExperienceData::get()->filter("ParentID", $experience->ID)->sort('SortOrder', 'ASC');
                    if ($experiencedata) {
                        foreach ($experiencedata as $experiencedata_entry) {
                            if ($experiencedata_entry->AlternativeTitle) {
                                $data['items'][$key]['Data'][$experiencedata_entry->Type->Title]['AlternativeTitle'] = $experiencedata_entry->AlternativeTitle;
                            }
                            if ($experiencedata_entry->Description) {
                                $data['items'][$key]['Data'][$experiencedata_entry->Type->Title]['Content'] = $experiencedata_entry->Description;
                            }
                            if ($experiencedata_entry->MoreInfo) {
                                $data['items'][$key]['Data'][$experiencedata_entry->Type->Title]['MoreInfo'] = $experiencedata_entry->MoreInfo;
                            }
                            if ($experiencedata_entry->Source) {
                                $data['items'][$key]['Data'][$experiencedata_entry->Type->Title]['Source'] = $experiencedata_entry->Source;
                            }
                            if ($experiencedata_entry->SourceLink) {
                                $data['items'][$key]['Data'][$experiencedata_entry->Type->Title]['SourceLink'] = $experiencedata_entry->SourceLink;
                            }
                        }
                    }
                }
            } else {
                $data['Error'] = "No experiences found.";
            }

            $this->response->addHeader('Content-Type', 'application/json');
            return json_encode($data);
        }

        public function places(HTTPRequest $request)
        {
            $currentMember = Security::getCurrentUser();
            $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();

            if (isset($_GET['ParkID'])) {
                $parkid = intval($_GET['ParkID']);
            }

            //BAUSTELLE!
            $sqlRequest = new SQLSelect("Location.Title AS Title");
            $sqlRequest->setFrom('ExperienceLocation AS Location');
            $sqlRequest->addLeftJoin('ExperienceLocationType', '"Location"."TypeID" = "LocationType"."ID"', 'LocationType');
            $sqlRequest->addLeftJoin('Experience', '"Experience"."ParentID" = "Location"."ID"', 'Experience');
            $sqlRequest->addLeftJoin('ExperienceType', '"ExperienceType"."ID" = "Experience"."TypeID"', 'ExperienceType');
            $sqlRequest->addLeftJoin('LogEntry', '"LogEntry"."ExperienceID" = "Experience"."ID"', 'LogEntry');
            if (isset($parkid)) {
                $sqlRequest->addWhere([
                    'Location.ID' => $parkid
                ]);
            }

            $sqlRequest->addSelect('Location.Title AS LocationTitle');
            $sqlRequest->addSelect('Location.ID AS LocationID');
            $sqlRequest->addSelect('Location.Description AS LocationDescription');
            $sqlRequest->addSelect('Location.Address AS LocationAddress');
            $sqlRequest->addSelect('Location.Coordinates AS LocationCoordinates');
            $sqlRequest->addSelect('Location.Website AS LocationWebsite');
            $sqlRequest->addSelect('Location.Phone AS LocationPhone');
            $sqlRequest->addSelect('Location.Email AS LocationEmail');
            $sqlRequest->addSelect('Location.OpeningDate AS LocationOpeningDate');
            $sqlRequest->addSelect('Location.LastEdited AS LocationLastEdited');
            $sqlRequest->addSelect('Location.ImageID AS LocationImageID');
            $sqlRequest->addSelect('Location.IconID AS LocationIconID');
            $sqlRequest->addSelect('Location.LinkTitle AS LocationLinkTitle');

            $sqlRequest->addSelect('Experience.ID AS ExperienceID');
            $sqlRequest->addSelect('Experience.Title AS ExperienceTitle');
            $sqlRequest->addSelect('Experience.State AS ExperienceState');
            $sqlRequest->addSelect('Experience.ImageID AS ExperienceImageID');
            $sqlRequest->addSelect('Experience.TypeID AS ExperienceType');
            $sqlRequest->addSelect('Experience.HasOnridePhoto AS ExperienceHasOnridePhoto');
            $sqlRequest->addSelect('Experience.HasFastpass AS ExperienceHasFastpass');
            $sqlRequest->addSelect('Experience.HasSingleRider AS ExperienceHasSingleRider');
            $sqlRequest->addSelect('Experience.AccessibleToHandicapped AS ExperienceAccessibleToHandicapped');
            $sqlRequest->addSelect('Experience.LinkTitle AS ExperienceLinkTitle');

            $sqlRequest->addSelect('LocationType.Title AS LocationTypeTitle');
            $sqlRequest->addSelect('ExperienceType.Title AS ExperienceTypeTitle');

            $sqlRequest->addSelect('LogEntry.ID AS LogEntryID');
            $sqlRequest->addSelect('LogEntry.VisitTime AS LogEntryVisitTime');
            $sqlRequest->addSelect('LogEntry.Weather AS LogEntryWeather');
            $sqlRequest->addSelect('LogEntry.Train AS LogEntryTrain');
            $sqlRequest->addSelect('LogEntry.Wagon AS LogEntryWagon');
            $sqlRequest->addSelect('LogEntry.Row AS LogEntryRow');
            $sqlRequest->addSelect('LogEntry.Seat AS LogEntrySeat');
            $sqlRequest->addSelect('LogEntry.Score AS LogEntryScore');
            $sqlRequest->addSelect('LogEntry.Podest AS LogEntryPodest');
            $sqlRequest->addSelect('LogEntry.Variant AS LogEntryVariant');
            $sqlRequest->addSelect('LogEntry.Version AS LogEntryVersion');
            $sqlRequest->addSelect('LogEntry.Notes AS LogEntryNotes');
            $sqlRequest->addSelect('LogEntry.UserID AS LogEntryUserID');

            $sqlResult = $sqlRequest->execute();

            $data = [];
            foreach ($sqlResult as $row) {
                $data[] = $row;
            }

            //Get Locations Overview Page
            $locationsHolder = LocationPage::get()->first();

            //Group Experiences by Location:
            $groupedData = [];
            foreach ($data as $row) {
                if ($currentUser) {
                    if ($row['LocationID'] == $currentUser->FavouritePark) {
                        $row['FavouritePark'] = true;
                    } else {
                        $row['FavouritePark'] = false;
                    }
                }

                $groupedData[$row['LocationID']]['Title'] = $row['LocationTitle'];
                $groupedData[$row['LocationID']]['LinkTitle'] = $row['LocationLinkTitle'];
                $groupedData[$row['LocationID']]['Link'] = $locationsHolder->AbsoluteLink("location\/") . $row['LocationLinkTitle'];
                if ($row['LocationDescription']) {
                    $groupedData[$row['LocationID']]['Description'] = strip_tags($row['LocationDescription']);
                } else {
                    $groupedData[$row['LocationID']]['Description'] = "";
                }
                if ($row['LocationAddress']) {
                    $groupedData[$row['LocationID']]['Address'] = $row['LocationAddress'];
                }
                if ($row['LocationCoordinates']) {
                    $groupedData[$row['LocationID']]['Coordinates'] = $row['LocationCoordinates'];
                }
                if ($row['LocationWebsite']) {
                    $groupedData[$row['LocationID']]['Website'] = $row['LocationWebsite'];
                }
                if ($row['LocationPhone']) {
                    $groupedData[$row['LocationID']]['Phone'] = $row['LocationPhone'];
                }
                if ($row['LocationEmail']) {
                    $groupedData[$row['LocationID']]['Email'] = $row['LocationEmail'];
                }
                if ($row['LocationOpeningDate']) {
                    $groupedData[$row['LocationID']]['OpeningDate'] = $row['LocationOpeningDate'];
                }
                if ($row['LocationImageID']) {
                    $groupedData[$row['LocationID']]['ImageID'] = $row['LocationImageID'];
                }
                if ($row['LocationIconID']) {
                    $groupedData[$row['LocationID']]['IconID'] = $row['LocationIconID'];
                }
                $groupedData[$row['LocationID']]['LastEdited'] = $row['LocationLastEdited'];
                $groupedData[$row['LocationID']]['Type'] = $row['LocationTypeTitle'];
                $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['ID'] = $row['ExperienceID'];
                $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['DetailsLink'] = $locationsHolder->AbsoluteLink("experience\/") . $row['LocationLinkTitle'] . "---" . $row['ExperienceLinkTitle'];
                $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LoggingLink'] = $locationsHolder->AbsoluteLink("addnewlog\/") . $row['LocationLinkTitle'] . "---" . $row['ExperienceLinkTitle'];
                $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['Title'] = $row['ExperienceTitle'];
                $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['State'] = $row['ExperienceState'];
                $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['Type'] = $row['ExperienceTypeTitle'];
                $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['ImageID'] = $row['ExperienceImageID'];
                $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['HasOnridePhoto'] = $row['ExperienceHasOnridePhoto'];
                $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['HasFastpass'] = $row['ExperienceHasFastpass'];
                $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['HasSingleRider'] = $row['ExperienceHasSingleRider'];
                $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['AccessibleToHandicapped'] = $row['ExperienceAccessibleToHandicapped'];

                $groupedData[$row['LocationID']]['ExperienceCount'] = count($groupedData[$row['LocationID']]['Experiences']);

                if ($currentUser) {
                    if ($row['LogEntryID'] && $row['LogEntryUserID'] == $currentUser->ID) {
                        $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['VisitTime'] = $row['LogEntryVisitTime'];
                        $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['Weather'] = $row['LogEntryWeather'];
                        $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['Train'] = $row['LogEntryTrain'];
                        $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['Wagon'] = $row['LogEntryWagon'];
                        $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['Row'] = $row['LogEntryRow'];
                        $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['Seat'] = $row['LogEntrySeat'];
                        $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['Score'] = $row['LogEntryScore'];
                        $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['Podest'] = $row['LogEntryPodest'];
                        $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['Variant'] = $row['LogEntryVariant'];
                        $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['Version'] = $row['LogEntryVersion'];
                        $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['Notes'] = $row['LogEntryNotes'];
                        $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['UserID'] = $row['LogEntryUserID'];
                    }
                }
            }

            $data = [];
            if (count($groupedData) > 0) {
                $data["Count"] = count($groupedData);
                if ($currentUser) {
                    $data["LoggedIn"] = true;
                } else {
                    $data["LoggedIn"] = false;
                }

                foreach ($groupedData as $row) {
                    $data['Items'][] = $row;
                }

                $lastedited = $this->getLastEdited();
                $data['LastEdited']['US'] = date("Y-m-d H:i:s", $lastedited);
                $data['LastEdited']['EU'] = date("d.m.Y H:i:s", $lastedited);
                $data['LastEdited']['Timestamp'] = $lastedited;
            } else {
                $data['Error'] = "No places found.";
            }

            $this->response->addHeader('Content-Type', 'application/json');
            return json_encode($data);
        }

        public function addnewlog(HTTPRequest $request)
        {
            $this->response->addHeader('Access-Control-Allow-Headers', '*');
            $this->response->addHeader('Content-Type', 'application/json');

            //getBody funktioniert auf dem Request
            $currentMember = Security::getCurrentUser();
            if ($currentMember) {
                $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
            }

            if (isset($currentUser)) {
                $data['UserID'] = $currentUser->ID;
                $title = $this->getRequest()->param("ID");
                $experience = Experience::get()->filter("LinkTitle", $title)->first();

                if (isset($experience)) {
                    $newlogentry = LogEntry::create();
                    $newlogentry->ExperienceID = $experience->ID;

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

                    $data['Status'] = true;
                    $data['Content'] = "Success: " . "The log entry was added successfully.";
                } else {
                    $data['Status'] = false;
                    $data['Content'] = "Error: " . "The experience couldn't be found...";
                }
            } else {
                $data['Status'] = false;
                $data['Content'] = "Error: " . "You need to be logged in to add a log entry.";
            }

            return json_encode($data);
        }

        public function logCountForExperience(HTTPRequest $request)
        {
            $currentMember = Security::getCurrentUser();
            if ($currentMember) {
                $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
            }

            if (!$currentUser) {
                $data['Status'] = false;
                $data['Content'] = "Error: " . "You need to be logged in to use this feature.";
            } else {
                $experience = Experience::get()->filter("ID", $request->param("ID"))->first();
                $logs = LogEntry::get()->filter([
                    "ExperienceID" => $experience->ID,
                    "UserID" => $currentUser->ID
                ]);
                $data['Status'] = true;
                $data['Experience'] = $experience->Title;
                $data['LogCount'] = $logs->count();
            }

            $this->response->addHeader('Content-Type', 'application/json');
            return json_encode($data);
        }

        public function ratingForExperience(HTTPRequest $request)
        {
            $experience = Experience::get()->filter("ID", $request->param("ID"))->first();
            if (!$experience) {
                $data['Status'] = false;
                $data['Content'] = "Error: " . "The experience couldn't be found...";
            } else {
                $ratings = Rating::get()->filter([
                    "ExperienceID" => $experience->ID
                ]);

                //Get the average stars from all ratings
                $totalStars = 0;
                $totalRatings = 0;
                foreach ($ratings as $rating) {
                    $totalStars += $rating->Stars;
                    $totalRatings++;
                }

                if ($totalStars > 0) {
                    $averageStars = $totalStars / $totalRatings;
                    $averageStars = round($averageStars, 2);
                } else {
                    $averageStars = 0;
                }

                $data['Status'] = false;
                $data['Experience'] = $experience->Title;
                $data['Rating'] = $averageStars;
            }

            $this->response->addHeader('Content-Type', 'application/json');
            return json_encode($data);
        }

        public function index(HTTPRequest $request)
        {
            $data['API_Title'] = "Experiencelogger API";
            $data['API_Description'] = "This API enables devs to use gathered information about theme parks and other experiences to use in their apps.";
            $data['API_Version'] = "1.0.0";

            $data['Places'] = $this->AbsoluteLink() . "/places";
            $data['Experiences'] = $this->AbsoluteLink() . "/experiences";
            $data['News'] = $this->AbsoluteLink() . "/news";
            $data['User'] = $this->AbsoluteLink() . "/user";

            $data['Copyright'] = "This API is developed and maintained by SP Universe. All rights reserved.";

            //Get the last edited item:
            $lastedited = $this->getLastEdited();
            $data['LastEdited']['US'] = date("Y-m-d H:i:s", $lastedited);
            $data['LastEdited']['EU'] = date("d.m.Y H:i:s", $lastedited);
            $data['LastEdited']['Timestamp'] = $lastedited;

            $this->response->addHeader('Content-Type', 'application/json');
            return json_encode($data);
        }

        public function getLastEdited()
        {
            $lastedited = strtotime("2019-01-01 00:00:00");
            $last_edited_experience = Experience::get()->sort('LastEdited', 'DESC')->first();
            if ($last_edited_experience) {
                $lastedited = strtotime($last_edited_experience->LastEdited);
            }
            $last_edited_location = ExperienceLocation::get()->sort('LastEdited', 'DESC')->first();
            if ($last_edited_location && strtotime($last_edited_location->LastEdited) > $lastedited) {
                $lastedited = strtotime($last_edited_location->LastEdited);
            }
            $last_edited_log = LogEntry::get()->sort('LastEdited', 'DESC')->first();
            if ($last_edited_log && strtotime($last_edited_log->LastEdited) > $lastedited) {
                $lastedited = strtotime($last_edited_log->LastEdited);
            }
            return $lastedited;
        }

        public function getCurrentUser()
        {
            $currentMember = Security::getCurrentUser();
            if ($currentMember) {
                $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
            }
            if ($currentUser) {
                return $currentUser;
            } else {
                return false;
            }
        }

        protected function init()
        {
            parent::init();
        }

        public function profile()
        {
            $currentMember = Security::getCurrentUser();
            if ($currentMember) {
                $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
            }
            if ($currentUser) {
                //get logs with unique experience IDs
                $logs = LogEntry::get()->filter("UserID", $currentUser->ID)->sort('LastEdited', 'DESC');
                $experiences = [];
                foreach ($logs as $log) {
                    $experiences[$log->ExperienceID] = $log->Experience();
                }

                return json_encode([
                    "ID" => $currentUser->ID,
                    "Email" => $currentUser->Email,
                    "FirstName" => $currentUser->FirstName,
                    "Surname" => $currentUser->Surname,
                    "Nickname" => $currentUser->Nickname,
                    "UniqueExperiences" => $experiences
                ]);
            }
        }

        public function locationprogress(HTTPRequest $request)
        {
            $currentMember = Security::getCurrentUser();
            if ($currentMember) {
                $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
            }
            if ($currentUser) {
                $id = $_GET['ID'];
                $data['API_Title'] = "Experiencelogger API";
                $data['API_Description'] = "This API enables devs to use gathered information about theme parks and other experiences to use in their apps.";
                $data['API_Version'] = "1.0.0";

                if (isset($_GET['ID'])) {
                    $location = ExperienceLocation::get()->filter("ID", $id)->first();

                    if (isset($location)) {
                        //Calculate Defuncts
                        $defunctExperiences = $location->Experiences()->filter(array(
                            "State:not" => "Active",
                        ));

                        $defunctExperienceCount = 0;
                        foreach ($defunctExperiences as $defunctExperience) {
                            if ($defunctExperience->getIsCompletedByUser($currentUser)) {
                                $defunctExperienceCount++;
                            }
                        }

                        $data['LocationProgress']['Title'] = $location->Title;
                        $data['LocationProgress']['ID'] = $location->ID;
                        $data['LocationProgress']['Total'] = $location->Experiences()->filter("State", "Active")->count();
                        $data['LocationProgress']['Defunct'] = $defunctExperienceCount;
                        $data['LocationProgress']['Progress'] = $location->getLocationProgress();
                        $data['LocationProgress']['ProgressPercent'] = $location->getLocationProgressInPercent();
                    } else {
                        $data['Error'] = "No location found.";
                        $data['RequestedID'] = $id;
                    }
                }

                $data['Copyright'] = "This API is developed and maintained by SP Universe. All rights reserved.";
                //Get the last edited item:
                $lastedited = $this->getLastEdited();
                $data['LastEdited']['US'] = date("Y-m-d H:i:s", $lastedited);
                $data['LastEdited']['EU'] = date("d.m.Y H:i:s", $lastedited);
                $data['LastEdited']['Timestamp'] = $lastedited;
                $this->response->addHeader('Content-Type', 'application/json');
                $this->response->setStatusCode(200);
                return json_encode($data);
            }
        }

        public function CheckLogin(HTTPRequest $request)
        {
            $currentMember = Security::getCurrentUser();
            if ($currentMember) {
                $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
            }

            if (!$currentUser) {
                $data['LoggedIn'] = false;
            } else {
                $data['LoggedIn'] = true;
            }

            $this->response->addHeader('Content-Type', 'application/json');
            return json_encode($data);
        }

        public function imagebyidOLD(HTTPRequest $request)
        {
            $id = $request->param('ID');
            if (!$id) {
                $data['Result'] = false;
                $data['Error'] = "No ID provided.";
                $this->response->addHeader('Content-Type', 'application/json');
                return json_encode($data);
            } else {
                $image = Image::get()->byID($id);
                if ($image && $image != null && $image->exists()) {
                    $width = intval(2000);
                    $height = intval(2000);

                    if (isset($_GET['width'])) {
                        $width = intval($_GET['width']);
                    }

                    if (isset($_GET['height'])) {
                        $height = intval($_GET['height']);
                    }

                    if (isset($_GET['json'])) {
                        $data['Result'] = true;
                        $data['Image'] = $image->FocusFill($width, $height)->AbsoluteLink();
                        $this->response->addHeader('Content-Type', 'application/json');
                        return json_encode($data);
                    } else {
                        return $image->FocusFill($width, $height)->AbsoluteLink();
                    }
                } else {
                    $data['Result'] = false;
                    $data['Error'] = "Image not found.";
                    $this->response->addHeader('Content-Type', 'application/json');
                    return json_encode($data);
                }
            }
        }

        public function imagebyid(HTTPRequest $request)
        {
            $id = $request->param('ID');
            if (!$id) {
                $data['Result'] = false;
                $data['Error'] = "No ID provided.";
                $this->response->addHeader('Content-Type', 'application/json');
                return json_encode($data);
            } else {
                $experience = Experience::get()->byID($id);
                $firstgalleryimage = $experience->PhotoGalleryImages()->first();
                $experienceimage = null;
                if ($firstgalleryimage) {
                    $experienceimage = $firstgalleryimage->Image();
                }
                if ($experience && $experienceimage && $experienceimage->exists()) {
                    $width = 2000;
                    $height = 2000;

                    if (isset($_GET['width'])) {
                        $width = intval($_GET['width']);
                    }

                    if (isset($_GET['height'])) {
                        $height = intval($_GET['height']);
                    }
                    $imagelink = $experienceimage->FocusFill($width, $height)->AbsoluteLink();

                    $data['Result'] = true;
                    $data['Image'] = $imagelink;
                    $this->response->addHeader('Content-Type', 'application/json');

                    return json_encode($data);
                } else {
                    $data['Result'] = false;
                    $data['Error'] = "Image not found.";
                    $this->response->addHeader('Content-Type', 'application/json');
                    return json_encode($data);
                }
            }
        }







        //API-Parts for Flutter-App:

        //APICall for getting all news
        public function news(HTTPRequest $request)
        {
            $this->response->addHeader('Access-Control-Allow-Headers', '*');
            $this->response->addHeader('Content-Type', 'application/json');
            return ApiAction_news::news($request);
        }

        //APICall for getting data of a tokenized user
        public function authenticateUser(HTTPRequest $request)
        {
            $this->response->addHeader('Access-Control-Allow-Headers', '*');
            $this->response->addHeader('Content-Type', 'application/json');
            return ApiAction_authenticateUser::authenticateUser($request);
        }

        public function logoutUser(HTTPRequest $request)
        {
            $this->response->addHeader('Access-Control-Allow-Headers', '*');
            $this->response->addHeader('Content-Type', 'application/json');
            return ApiAction_logoutUser::logoutUser($request);
        }

        public function loginUser(HTTPRequest $request)
        {
            $this->response->addHeader('Access-Control-Allow-Headers', '*');
            $this->response->addHeader('Content-Type', 'application/json');
            return ApiAction_loginUser::loginUser($request);
        }

        public function registerUser(HTTPRequest $request)
        {
            $this->response->addHeader('Access-Control-Allow-Headers', '*');
            $this->response->addHeader('Content-Type', 'application/json');
            return ApiAction_registerUser::registerUser($request);
        }

        public function allexperiences(HTTPRequest $request)
        {
            $this->response->addHeader('Access-Control-Allow-Headers', '*');
            $this->response->addHeader('Content-Type', 'application/json');
            return ApiAction_allexperiences::allexperiences($request);
        }

        public function allplaces(HTTPRequest $request)
        {
            $this->response->addHeader('Access-Control-Allow-Headers', '*');
            $this->response->addHeader('Content-Type', 'application/json');
            return ApiAction_allplaces::allplaces($request);
        }

        public function alllogs(HTTPRequest $request)
        {
            $this->response->addHeader('Access-Control-Allow-Headers', '*');
            $this->response->addHeader('Content-Type', 'application/json');
            return ApiAction_alllogs::alllogs($request);
        }

        public function addLog(HTTPRequest $request)
        {
            $this->response->addHeader('Access-Control-Allow-Headers', '*');
            $this->response->addHeader('Content-Type', 'application/json');
            return ApiAction_addLog::addLog($request);
        }
    }
}
