<?php
namespace App\News;

use SilverStripe\Model\List\PaginatedList;
use App\News\News;
use PageController;
use SilverStripe\Control\HTTPRequest;

/**
 * Class \App\Pages\DataPageController
 *
 * @property NewsPage $dataRecord
 * @method NewsPage data()
 * @mixin NewsPage
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
