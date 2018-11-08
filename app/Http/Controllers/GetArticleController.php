<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Acme\Article\Article;
use Acme\Article\Repository\Exception\ArticleNotFound;
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

        try {
            $article = $this->handler->__invoke($command);
        } catch (ArticleNotFound $e) {
            $response = [
                'message' => 'Article not found',
            ];

            return response()->json($response, 404);
        }

        $response = $this->serialize($article);

        return response()->json($response);
    }

    private function serialize(Article $article)
    {
        return [
            'id' => (string) $article->id(),
            'title' => (string) $article->title(),
            'body' => (string) $article->body(),
        ];
    }
}
