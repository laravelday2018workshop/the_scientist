<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Article\UseCase\CreateArticle\CreateArticleCommand;
use Acme\Article\UseCase\CreateArticle\CreateArticleHandler;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use Acme\Reviewer\ValueObject\ReviewerID;
use App\Http\Requests\CreateArticleRequest;
use App\Integration\Article\Mapper\ViewArticleMapper;

final class CreateArticleController extends Controller
{
    /**
     * @var CreateArticleHandler
     */
    private $handler;
    /**
     * @var ViewArticleMapper
     */
    private $viewArticleMapper;

    public function __construct(CreateArticleHandler $handler, ViewArticleMapper $viewArticleMapper)
    {
        $this->handler = $handler;
        $this->viewArticleMapper = $viewArticleMapper;
    }

    public function __invoke(CreateArticleRequest $request)
    {
        $command = new CreateArticleCommand(
            new Title($request->get('title')),
            new Body($request->get('body')),
            ReviewerID::fromUUID($request->get('reviewer_id')),
            AcademicRegistrationNumber::fromString($request->get('academic_id'))
        );
        $article = ($this->handler)($command);

        return response()->json($this->viewArticleMapper->fromArticle($article), 201);
    }
}
