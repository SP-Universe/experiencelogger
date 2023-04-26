<?php

namespace {

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
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
            "checklogin",
            "addLog",
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
            try {
                $payload = JWTUtils::inst()->byBasicAuth($request);
                return json_encode($payload);
            } catch (JWTUtilsException $e) {
                return json_encode("Error! " . $e->getMessage());
            }
        }

        public function experiences(HTTPRequest $request)
        {
            $id = $this->getRequest()->param("ID");

            if (isset($_GET['ID'])) {
                $id = intval($_GET['ID']);
                $experiences = Experience::get()->filter("ID", $id)->sort('Title', 'ASC');
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
                    if ($experience->Image->AbsoluteURL) {
                        $data['items'][$key]['Image'] = $experience->Image->AbsoluteURL;
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
            $this->response->addHeader('Access-Control-Allow-Origin', '*');
            return json_encode($data);
        }

        public function places(HTTPRequest $request)
        {
            $places = ExperienceLocation::get()->sort('Title', 'ASC');

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
            $this->response->addHeader('Access-Control-Allow-Origin', '*');
            return json_encode($data);
        }

        public function addLog(HTTPRequest $request)
        {
            $this->response->addHeader('Access-Control-Allow-Origin', '*');
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

        public function token(HTTPRequest $request)
        {
            try {
                $payload = JWTUtils::inst()->byBasicAuth($request);

                return json_encode($payload);
            } catch (JWTUtilsException $e) {
                return $this->httpError(403, $e->getMessage());
            }
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
    }
}
