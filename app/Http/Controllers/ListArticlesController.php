<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Acme\Article\Article;
use Acme\Article\UseCase\ListArticles\ListArticlesCommand;
use Acme\Article\UseCase\ListArticles\ListArticlesHandler;
use App\Http\Requests\ListArticlesRequest;
use App\Integration\Article\Mapper\Serializer\SerializeArticle;

final class ListArticlesController extends Controller
{
    /**
     * @var ListArticlesHandler
     */
    private $handler;
    /**
     * @var SerializeArticle
     */
    private $fromArticleMapper;

    public function __construct(ListArticlesHandler $handler, SerializeArticle $fromArticleMapper)
    {
        $this->handler = $handler;
        $this->fromArticleMapper = $fromArticleMapper;
    }

    public function __invoke(ListArticlesRequest $request)
    {
        $articleCollection = ($this->handler)(new ListArticlesCommand(
            (int) $request->query('skip'),
            (int) $request->query('take')
        ));

        $articles = \array_map(function (Article $article) {
            return ($this->fromArticleMapper)($article);
        }, $articleCollection->toArray());

        return response()->json($articles);
    }
}
