<?php

namespace {

use SilverStripe\ORM\DB;
use SilverStripe\Assets\Image;

    use App\ExperienceDatabase\Experience;
    use App\ExperienceDatabase\ExperienceData;
    use App\ExperienceDatabase\ExperienceLocation;
    use App\ExperienceDatabase\LogEntry;
    use SilverStripe\Security\Member;
    use SilverStripe\Control\HTTPRequest;
    use SilverStripe\Core\Injector\Injector;
    use SilverStripe\Security\IdentityStore;
    use Level51\JWTUtils\JWTUtils;
    use Level51\JWTUtils\JWTUtilsException;
    use SilverStripe\Security\Security;
    use SilverStripe\CMS\Controllers\ContentController;
    use SilverStripe\ORM\Queries\SQLSelect;

    /**
 * Class \PageController
 *
 * @property \ApiPage $dataRecord
 * @method \ApiPage data()
 * @mixin \ApiPage
 */
    class ApiPageController extends ContentController
    {
        private static $allowed_actions = [
            "login",
            "logout",
            "experiences",
            "places",
            "placesnew",
            "addLog",
            "profile",
            "locationprogress"
        ];

        public function logout(HTTPRequest $request)
        {
            if ($member = Security::getCurrentUser()) {
                Injector::inst()->get(IdentityStore::class)->logOut($request);
            }
            return $this->redirect('home');
        }


        public function login(HTTPRequest $request)
        {
            $this->response->addHeader('Access-Control-Allow-Headers', '*');
            if (!$request->isPOST()) {
                // Probably preflight request
                return 'Ok';
            }

            $payload = json_decode($request->getBody(), true);

            $user = Member::get()->filter('Email', $payload['Username'])->first();

            Injector::inst()->get(IdentityStore::class)->logIn($user, false, $request);

            return "ok";
            // try {
            //     $payload = JWTUtils::inst()->byBasicAuth($request);
            //     return json_encode($payload);
            // } catch (JWTUtilsException $e) {
            //     return json_encode("Error! " . $e->getMessage());
            // }
        }

        public function experiences(HTTPRequest $request)
        {
            $id = $this->getRequest()->param("ID");

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

                foreach ($experiences as $experience) {
                    $key = $experience->Title;
                    $data['items'][$key]['ID'] = $experience->ID;
                    $data['items'][$key]['Title'] = $experience->Title;
                    $data['items'][$key]['Link'] = $experience->AbsoluteLink;
                    $data['items'][$key]['LinkTitle'] = $experience->LinkTitle;
                    if ($experience->Description) {
                        $data['items'][$key]['Description'] = $experience->Description;
                    }
                    if ($experience->PhotoGalleryImages()->first()) {
                        $data['items'][$key]['Image'] = $experience->PhotoGalleryImages()->first()->Image()->getAbsoluteURL();
                    }
                    $data['items'][$key]['Location'] = $experience->Parent->Title;
                    $data['items'][$key]['LocationID'] = $experience->ParentID;
                    $data['items'][$key]['Type'] = $experience->Type->Title;
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
            $places = ExperienceLocation::get()->sort('Title', 'ASC');

            $data['Count'] = count($places);

            if ($places) {
                foreach ($places as $place) {
                    $data['items'][$place->Title]['ID'] = $place->ID;
                    $data['items'][$place->Title]['Title'] = $place->Title;
                    $data['items'][$place->Title]['Link'] = $place->AbsoluteLink;
                    $data['items'][$place->Title]['Type'] = $place->Type->Title;
                    $data['items'][$place->Title]['LinkTitle'] = $place->LinkTitle;
                    if ($place->Description) {
                        $data['items'][$place->Title]['Description'] = $place->Description;
                    }
                    if ($place->Image->AbsoluteURL) {
                        $data['items'][$place->Title]['Image'] = $place->Image->AbsoluteURL;
                    }
                    if ($place->Icon->AbsoluteURL) {
                        $data['items'][$place->Title]['Icon'] = $place->Icon->AbsoluteURL;
                    }
                    if ($place->Address) {
                        $data['items'][$place->Title]['Address'] = $place->Address;
                    }
                    if ($place->Coordinates) {
                        $data['items'][$place->Title]['Coordinates'] = $place->Coordinates;
                    }
                    if ($place->Website) {
                        $data['items'][$place->Title]['Website'] = $place->Website;
                    }
                    if ($place->Phone) {
                        $data['items'][$place->Title]['Phone'] = $place->Phone;
                    }
                    if ($place->Email) {
                        $data['items'][$place->Title]['Email'] = $place->Email;
                    }
                    if ($place->OpeningDate) {
                        $data['items'][$place->Title]['OpeningDate'] = $place->OpeningDate;
                    }
                    $data['items'][$place->Title]['LastEdited'] = $place->LastEdited;
                }
            } else {
                $data['Error'] = "No places found.";
            }

            $this->response->addHeader('Content-Type', 'application/json');
            return json_encode($data);
        }

        public function placesnew(HTTPRequest $request)
        {
            $currentUser = Security::getCurrentUser();

            //BAUSTELLE!
            $sqlRequest = new SQLSelect("Location.Title AS Title");
            $sqlRequest->setFrom('ExperienceLocation AS Location');
            $sqlRequest->addLeftJoin('ExperienceLocationType', '"Location"."TypeID" = "LocationType"."ID"', 'LocationType');
            $sqlRequest->addLeftJoin('Experience', '"Experience"."ParentID" = "Location"."ID"', 'Experience');
            $sqlRequest->addLeftJoin('ExperienceType', '"ExperienceType"."ID" = "Experience"."TypeID"', 'ExperienceType');
            $sqlRequest->addLeftJoin('LogEntry', '"LogEntry"."ExperienceID" = "Experience"."ID"', 'LogEntry');

            $sqlRequest->addSelect('Location.Title AS LocationTitle');
            $sqlRequest->addSelect('Location.ID AS LocationID');
            $sqlRequest->addSelect('Location.Description AS LocationDescription');
            $sqlRequest->addSelect('Location.Address AS LocationAddress');
            $sqlRequest->addSelect('Location.Coordinates AS LocationCoordinates');
            $sqlRequest->addSelect('Location.Website AS LocationWebsite');

            $sqlRequest->addSelect('Experience.ID AS ExperienceID');
            $sqlRequest->addSelect('Experience.Title AS ExperienceTitle');
            $sqlRequest->addSelect('Experience.State AS ExperienceState');

            $sqlRequest->addSelect('LocationType.Title AS TypeTitle');

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

            Image::class;

            //Group Experiences by Location:
            $groupedData = [];
            foreach ($data as $row) {
                if ($row['LocationID'] == $currentUser->FavouritePark) {
                    $row['FavouritePark'] = true;
                } else {
                    $row['FavouritePark'] = false;
                }

                $groupedData[$row['LocationID']]['LocationTitle'] = $row['LocationTitle'];
                $groupedData[$row['LocationID']]['LocationDescription'] = $row['LocationDescription'];
                $groupedData[$row['LocationID']]['LocationAddress'] = $row['LocationAddress'];
                $groupedData[$row['LocationID']]['LocationCoordinates'] = $row['LocationCoordinates'];
                $groupedData[$row['LocationID']]['LocationWebsite'] = $row['LocationWebsite'];
                $groupedData[$row['LocationID']]['TypeTitle'] = $row['TypeTitle'];
                $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['ExperienceTitle'] = $row['ExperienceTitle'];
                $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['ExperienceState'] = $row['ExperienceState'];

                if ($row['LogEntryID'] && $row['LogEntryUserID'] == $currentUser->ID) {
                    $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['LogEntryVisitTime'] = $row['LogEntryVisitTime'];
                    $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['LogEntryWeather'] = $row['LogEntryWeather'];
                    $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['LogEntryTrain'] = $row['LogEntryTrain'];
                    $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['LogEntryWagon'] = $row['LogEntryWagon'];
                    $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['LogEntryRow'] = $row['LogEntryRow'];
                    $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['LogEntrySeat'] = $row['LogEntrySeat'];
                    $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['LogEntryScore'] = $row['LogEntryScore'];
                    $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['LogEntryPodest'] = $row['LogEntryPodest'];
                    $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['LogEntryVariant'] = $row['LogEntryVariant'];
                    $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['LogEntryVersion'] = $row['LogEntryVersion'];
                    $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['LogEntryNotes'] = $row['LogEntryNotes'];
                    $groupedData[$row['LocationID']]['Experiences'][$row['ExperienceID']]['LogEntries'][$row['LogEntryID']]['LogEntryUserID'] = $row['LogEntryUserID'];
                }
            }

            $data = [];
            foreach ($groupedData as $row) {
                $data[] = $row;
            }



            /*$items = DB::Query("
                SELECT
                    ExperienceLocation.Title AS LocationTitle,
                    ExperienceLocationType.Title AS LocationTypeTitle
                FROM ExperienceLocation
                LEFT JOIN ExperienceLocationType ON ExperienceLocationType.ID = ExperienceLocation.TypeID
            ");*/

            $this->response->addHeader('Content-Type', 'application/json');
            return json_encode($data);
        }

        public function addLog(HTTPRequest $request)
        {
            $this->response->addHeader('Access-Control-Allow-Headers', '*');
            $this->response->addHeader('Content-Type', 'application/json');

            //getBody funktioniert auf dem Request
            $currentUser = Security::getCurrentUser();

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

        public function index(HTTPRequest $request)
        {
            $data['API_Title'] = "Experiencelogger API";
            $data['API_Description'] = "This API enables devs to use gathered information about theme parks and other experiences to use in their apps.";
            $data['API_Version'] = "1.0.0";

            $data['Places'] = $this->AbsoluteLink() . "places";
            $data['Experiences'] = $this->AbsoluteLink() . "experiences";

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
            return Security::getCurrentUser();
        }

        protected function init()
        {
            parent::init();
        }

        public function profile()
        {
            if (Security::getCurrentUser()) {
                //get logs with unique experience IDs
                $logs = LogEntry::get()->filter("UserID", Security::getCurrentUser()->ID)->sort('LastEdited', 'DESC');
                $experiences = [];
                foreach ($logs as $log) {
                    $experiences[$log->ExperienceID] = $log->Experience();
                }

                $member = Security::getCurrentUser();
                return json_encode([
                    "ID" => $member->ID,
                    "Email" => $member->Email,
                    "FirstName" => $member->FirstName,
                    "Surname" => $member->Surname,
                    "Nickname" => $member->Nickname,
                    "UniqueExperiences" => $experiences
                ]);
            }
        }

        public function locationprogress(HTTPRequest $request)
        {
            $currentUser = Security::getCurrentUser();
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
                return json_encode($data);
            }
        }
    }
}
