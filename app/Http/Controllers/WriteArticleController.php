<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Acme\Academic\UseCase\WriteArticle\WriteArticleCommand;
use Acme\Academic\UseCase\WriteArticle\WriteArticleHandler;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use App\Http\Requests\WriteArticleRequest;
use App\Integration\Academic\Mapper\Serializer\SerializeAcademic;

final class WriteArticleController extends Controller
{
    /**
     * @var WriteArticleHandler
     */
    private $handler;
    /**
     * @var SerializeAcademic
     */
    private $serializeAcademic;

    public function __construct(WriteArticleHandler $handler, SerializeAcademic $serializeAcademic)
    {
        $this->handler = $handler;
        $this->serializeAcademic = $serializeAcademic;
    }

    public function __invoke(WriteArticleRequest $request)
    {
        $command = new WriteArticleCommand(
            new Title($request->get('title')),
            new Body($request->get('body')),
            AcademicRegistrationNumber::fromString($request->route()->parameter('id'))
        );
        $academic = ($this->handler)($command);

        return response()->json(($this->serializeAcademic)($academic), 201);
    }
}
