<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Acme\Article\Article;
use Acme\Article\Repository\Exception\ArticleNotFound;
use Acme\Article\UseCase\GetArticle\GetArticleCommand;
use Acme\Article\UseCase\GetArticle\GetArticleHandler;
use Acme\Article\ValueObject\ArticleID;
use Acme\Common\ValueObject\Exception\InvalidID;
use Illuminate\Http\JsonResponse;

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

    /**
     * @throws ArticleNotFound
     */
    public function __invoke(string $id):JsonResponse
    {
        try {
            $command = new GetArticleCommand(ArticleID::fromUUID($id));
            $article = $this->handler->__invoke($command);
        } catch (InvalidID $ex) {
            $response = [
                'message' => 'Invalid id given',
            ];

            return response()->json($response, 400);
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
