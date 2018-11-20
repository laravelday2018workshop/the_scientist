<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Acme\Article\Repository\Exception\ArticleNotFound;
use Acme\Article\UseCase\GetArticle\GetArticleCommand;
use Acme\Article\UseCase\GetArticle\GetArticleHandler;
use Acme\Article\ValueObject\ArticleID;
use App\Http\Requests\GetArticleRequest;
use App\Integration\Article\Mapper\Serializer\SerializeArticle;

final class GetArticleController extends Controller
{
    /**
     * @var GetArticleHandler
     */
    private $handler;
    /**
     * @var SerializeArticle
     */
    private $fromArticleMapper;

    public function __construct(GetArticleHandler $handler, SerializeArticle $fromArticleMapper)
    {
        $this->handler = $handler;
        $this->fromArticleMapper = $fromArticleMapper;
    }

    /**
     * @throws ArticleNotFound
     */
    public function __invoke(GetArticleRequest $request)
    {
        $id = $request->route()->parameter('id');
        $command = new GetArticleCommand(ArticleID::fromUUID($id));
        $article = ($this->handler)($command);

        $response = ($this->fromArticleMapper)($article);

        return response()->json($response);
    }
}
