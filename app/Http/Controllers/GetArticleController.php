<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Acme\Article\Repository\Exception\ArticleNotFound;
use Acme\Article\UseCase\GetArticle\GetArticleCommand;
use Acme\Article\UseCase\GetArticle\GetArticleHandler;
use Acme\Article\ValueObject\ArticleID;
use App\Http\Requests\GetArticleRequest;
use App\Integration\Article\Mapper\ViewArticleMapper;

final class GetArticleController extends Controller
{
    /**
     * @var GetArticleHandler
     */
    private $handler;
    /**
     * @var ViewArticleMapper
     */
    private $viewArticleMapper;

    public function __construct(GetArticleHandler $handler, ViewArticleMapper $viewArticleMapper)
    {
        $this->handler = $handler;
        $this->viewArticleMapper = $viewArticleMapper;
    }

    public function __invoke(GetArticleRequest $request)
    {
        $id = $request->route()->parameter('id');
        try {
            $article = ($this->handler)(new GetArticleCommand(ArticleID::fromUUID($id)));
        } catch (ArticleNotFound $e) {
            $response = ['message' => $e->getMessage()];

            return response()->json($response, 404);
        }
        $response = $this->viewArticleMapper->fromArticle($article);

        return response()->json($response);
    }
}
