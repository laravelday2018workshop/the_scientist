<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Acme\Article\Article;
use Acme\Article\UseCase\ListArticles\ListArticlesCommand;
use Acme\Article\UseCase\ListArticles\ListArticlesHandler;
use App\Http\Requests\ListArticlesRequest;
use App\Integration\Article\Mapper\ViewArticleMapper;

final class ListArticlesController extends Controller
{
    /**
     * @var ListArticlesHandler
     */
    private $handler;
    /**
     * @var ViewArticleMapper
     */
    private $viewArticleMapper;

    public function __construct(ListArticlesHandler $handler, ViewArticleMapper $viewArticleMapper)
    {
        $this->handler = $handler;
        $this->viewArticleMapper = $viewArticleMapper;
    }

    public function __invoke(ListArticlesRequest $request)
    {
        $articleCollection = ($this->handler)(new ListArticlesCommand(
            (int) $request->query('skip'),
            (int) $request->query('take')
        ));

        $articles = \array_map(function (Article $article) {
            return $this->viewArticleMapper->fromArticle($article);
        }, $articleCollection->toArray());

        return response()->json($articles);
    }
}
