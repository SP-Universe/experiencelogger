<?php

namespace App\Api\ApiActions {

    use App\ExperienceDatabase\Experience;
    use SilverStripe\Control\HTTPRequest;

    class ApiAction_allexperiences
    {
        private static function cleanText($text)
        {
            $text = strip_tags($text);
            $text = str_replace("\n", "", $text);
            $text = str_replace("\r", "", $text);
            $text = str_replace("&amp;", "&", $text);
            $text = str_replace("&nbsp;", " ", $text);
            $text = str_replace("&quot;", "\"", $text);
            $text = str_replace("&#039;", "'", $text);
            $text = str_replace("&#39;", "'", $text);
            return $text;
        }

        public static function allexperiences(HTTPRequest $request)
        {
            $experiences = Experience::get()->sort('ID ASC');
            if ($request->getVar('LocationID') !== null) {
                $experiences = $experiences->filter('ParentID', intval($request->getVar('LocationID')));
            }
            $data = [];

            $groupedExperiences = [];

            //Add each news to the json data array
            foreach ($experiences as $experience) {
                $groupedExperiences[$experience->ID]['ID'] = $experience->ID;
                $groupedExperiences[$experience->ID]['Title'] = $experience->Title;
                if ($experience->Description != null) {
                    $groupedExperiences[$experience->ID]['Description'] = self::cleanText($experience->Description);
                }
                if ($experience->PhotoGalleryImages()->count() > 0) {
                    $groupedExperiences[$experience->ID]['ImageLink'] = $experience->PhotoGalleryImages()->first()->Image()->AbsoluteLink();
                }
                $groupedExperiences[$experience->ID]['LocationTitle'] = $experience->Parent()->Title;
                $groupedExperiences[$experience->ID]['LocationId'] = $experience->ParentID;
                $groupedExperiences[$experience->ID]['Type'] = $experience->Type()->Title;
                $groupedExperiences[$experience->ID]['TypeID'] = $experience->TypeID;
                if ($experience->AreaID != 0) {
                    $groupedExperiences[$experience->ID]['Area'] = $experience->Area()->Title;
                    $groupedExperiences[$experience->ID]['AreaID'] = $experience->AreaID;
                }
                $groupedExperiences[$experience->ID]['State'] = $experience->State;

                if ($experience->Coordinates != null && $experience->Coordinates != "") {
                    $coordinates = $experience->Coordinates;
                    $coordinates = explode(", ", $coordinates);
                    $lat = $coordinates[0];
                    $lon = $coordinates[1] ?? 0.0;
                    $groupedExperiences[$experience->ID]['Latitude'] = $lat;
                    $groupedExperiences[$experience->ID]['Longitude'] = $lon;
                }

                $groupedExperiences[$experience->ID]['LastEdited'] = $experience->LastEdited;
                $groupedExperiences[$experience->ID]['Created'] = $experience->Created;

                if ($experience->OpeningDate != null && $experience->OpeningDate != "") {
                    $groupedExperiences[$experience->ID]['OpeningDate'] = $experience->OpeningDate;
                }
                if ($experience->ClosingDate != null && $experience->ClosingDate != "") {
                    $groupedExperiences[$experience->ID]['ClosingDate'] = $experience->ClosingDate;
                }
                if ($experience->Height != null && $experience->Height != 0) {
                    $groupedExperiences[$experience->ID]['Height'] = $experience->Height;
                }
                if ($experience->Length != null && $experience->Length != 0) {
                    $groupedExperiences[$experience->ID]['Length'] = $experience->Length;
                }
                if ($experience->Duration != null && $experience->Duration != 0) {
                    $groupedExperiences[$experience->ID]['Duration'] = $experience->Duration;
                }
                if ($experience->Speed != null && $experience->Speed != 0) {
                    $groupedExperiences[$experience->ID]['Speed'] = $experience->Speed;
                }
                $groupedExperiences[$experience->ID]['HasSingleRider'] = (bool) $experience->HasSingleRider;
                $groupedExperiences[$experience->ID]['HasFastpass'] = (bool) $experience->HasFastpass;
                if ($experience->FastpassLink != null && $experience->FastpassLink != "") {
                    $groupedExperiences[$experience->ID]['FastpassLink'] = $experience->FastpassLink;
                }
                $groupedExperiences[$experience->ID]['HasOnridePhoto'] = (bool) $experience->HasOnridePhoto;
                $groupedExperiences[$experience->ID]['AccessibleToHandicapped'] = (bool) $experience->AccessibleToHandicapped;

                $groupedExperiences[$experience->ID]['ExperienceData'] = [];
                foreach ($experience->ExperienceData() as $experienceData) {
                    $data = [
                        'ID' => $experienceData->ID,
                        'Type' => $experienceData->Type()->Title,
                        'TypeID' => $experienceData->TypeID,
                        'SortOrder' => $experienceData->SortOrder,
                    ];
                    if ($experienceData->AlternativeTitle != null && $experienceData->AlternativeTitle != "") {
                        $data['AlternativeTitle'] = $experienceData->AlternativeTitle;
                    }
                    if ($experienceData->Description != null && $experienceData->Description != "") {
                        $data['Description'] = self::cleanText($experienceData->Description);
                    }
                    if ($experienceData->MoreInfo != null && $experienceData->MoreInfo != "") {
                        $data['MoreInfo'] = $experienceData->MoreInfo;
                    }
                    if ($experienceData->Source != null && $experienceData->Source != "") {
                        $data['Source'] = $experienceData->Source;
                    }
                    if ($experienceData->SourceLink != null && $experienceData->SourceLink != "") {
                        $data['SourceLink'] = $experienceData->SourceLink;
                    }
                    $groupedExperiences[$experience->ID]['ExperienceData'][] = $data;
                }

                foreach ($experience->ExperienceTrains() as $train) {
                    $wagons = [];
                    foreach ($train->Wagons() as $wagon) {
                        $rows = [];
                        foreach ($wagon->Rows() as $row) {
                            $seats = [];
                            foreach ($row->Seats() as $seat) {
                                $seats[] = [
                                    'Title' => $seat->Title,
                                    'SortOrder' => $seat->SortOrder,
                                    'Rotation' => $seat->Rotation,
                                    'Type' => $seat->Type,
                                ];
                            }
                            $rows[] = [
                                'Title' => $row->Title,
                                'SortOrder' => $row->SortOrder,
                                'Seats' => $seats,
                            ];
                        }
                        $wagons[] = [
                            'Title' => $wagon->Title,
                            'Color' => $wagon->Color,
                            'SortOrder' => $wagon->SortOrder,
                            'Rows' => $rows,
                        ];
                    }
                    $groupedExperiences[$experience->ID]['Seatchart'][] = [
                        'Title' => $train->Title,
                        'Color' => $train->Color,
                        'SortOrder' => $train->SortOrder,
                        'Wagons' => $wagons,
                    ];
                }
            }

            foreach ($groupedExperiences as $experience) {
                $data[] = $experience;
            }

            return json_encode($data);
        }
    }
}
