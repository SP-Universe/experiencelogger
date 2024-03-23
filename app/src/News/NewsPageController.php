<?php
namespace App\News;

use App\News\News;
use PageController;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\Control\HTTPRequest;

/**
 * Class \App\Pages\DataPageController
 *
 * @property \App\News\NewsPage $dataRecord
 * @method \App\News\NewsPage data()
 * @mixin \App\News\NewsPage
 */
class NewsPageController extends PageController
{
    private static $allowed_actions = [
        "news",
    ];

    public function index(HTTPRequest $request)
    {
        $news = News::get();
        $paginatedList = new PaginatedList($news, $request);
        $paginatedList->setPageLength(10);

        return [
            "News" => $paginatedList,
        ];
    }

    public function news(HTTPRequest $request)
    {
        $id = $request->param("ID");
        $news = News::get()->byID($id);
        if (!$news) {
            return $this->httpError(404, "News not found");
        }
        return [
            "News" => $news,
        ];
    }
}
