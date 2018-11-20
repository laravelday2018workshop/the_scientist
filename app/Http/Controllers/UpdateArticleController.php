<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Acme\Article\UseCase\UpdateArticle\UpdateArticleCommand;
use Acme\Article\UseCase\UpdateArticle\UpdateArticleHandler;
use Acme\Article\ValueObject\ArticleID;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use App\Http\Requests\UpdateArticleRequest;
use App\Integration\Article\Mapper\Serializer\SerializeArticle;

final class UpdateArticleController extends Controller
{
    /**
     * @var UpdateArticleHandler
     */
    private $handler;
    /**
     * @var SerializeArticle
     */
    private $fromArticleMapper;

    public function __construct(UpdateArticleHandler $handler, SerializeArticle $fromArticleMapper)
    {
        $this->handler = $handler;
        $this->fromArticleMapper = $fromArticleMapper;
    }

    public function __invoke(UpdateArticleRequest $request)
    {
        $command = new UpdateArticleCommand(
            ArticleID::fromUUID($request->route()->parameter('id')),
            new Title($request->get('title')),
            new Body($request->get('body'))
        );
        $article = ($this->handler)($command);

        return response()->json(($this->fromArticleMapper)($article));
    }
}
