<?php

namespace App\Api\ApiActions {

    use App\News\News;
    use SilverStripe\Control\HTTPRequest;

    class ApiAction_news
    {
        public static function news(HTTPRequest $request)
        {
            $news = News::get()->sort('Date', 'DESC');
            $data = [];

            $groupedNews = [];

            //Add each news to the json data array
            foreach ($news as $newsitem) {
                if ($newsitem->Date > date("Y-m-d H:i:s")) {
                    continue;
                }

                $groupedNews[$newsitem->ID]['ID'] = $newsitem->ID;
                $groupedNews[$newsitem->ID]['ReleaseDate'] = $newsitem->Date;
                $groupedNews[$newsitem->ID]['Title'] = $newsitem->Title;
                $newscontent = $newsitem->Content;
                $groupedNews[$newsitem->ID]['FormattedContent'] = $newscontent;
                $filteredContent = strip_tags($newscontent);
                $filteredContent = preg_replace('/\s+/', ' ', $filteredContent);
                $groupedNews[$newsitem->ID]['TextContent'] = $filteredContent;

                if ($newsitem->ShortDescription) {
                    $groupedNews[$newsitem->ID]['Summary'] = $newsitem->ShortDescription;
                } else {
                    $groupedNews[$newsitem->ID]['Summary'] = substr($filteredContent, 0, 200) . "...";
                }
                $groupedNews[$newsitem->ID]['Summary'] = $newsitem->ShortDescription;
                $groupedNews[$newsitem->ID]['Link'] = $newsitem->getLink();
                if ($newsitem->Image() && $newsitem->Image()->exists()) {
                    $groupedNews[$newsitem->ID]['Image'] = $newsitem->Image()->FocusFill(2000, 2000)->AbsoluteLink();
                }
                $groupedNews[$newsitem->ID]['Categories'] = [];
                foreach ($newsitem->Category() as $category) {
                    $groupedNews[$newsitem->ID]['Categories'][] = $category->Title;
                }
            }

            foreach ($groupedNews as $newsEntry) {
                $data[] = $newsEntry;
            }

            return json_encode($data);
        }
    }
}
