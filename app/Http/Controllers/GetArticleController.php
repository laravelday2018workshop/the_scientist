<?php

namespace App\Http\Controllers;

use Acme\Article\UseCase\GetArticle\GetArticleCommand;
use Acme\Article\UseCase\GetArticle\GetArticleHandler;
use Acme\Article\ValueObject\ArticleID;

class GetArticleController extends Controller
{
    /**
     * @var GetArticleHandler
     */
    private $handler;

    public function __construct(GetArticleHandler $handler)
    {
        $this->handler = $handler;
    }

    public function __invoke(string $id)
    {
        $command = new GetArticleCommand(ArticleID::fromUUID($id));

        $article = $this->handler->__invoke($command);

        return response()->json([
            'id'    => (string)$article->id(),
            'title' => (string)$article->title(),
            'body'  => (string)$article->body()
        ]);
    }
}
