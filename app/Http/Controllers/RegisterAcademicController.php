<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Acme\Academic\UseCase\RegisterAcademic\RegisterAcademicCommand;
use Acme\Academic\UseCase\RegisterAcademic\RegisterAcademicHandler;
use Acme\Academic\UseCase\WriteArticle\WriteArticleHandler;
use Acme\Academic\ValueObject\BirthDate;
use Acme\Academic\ValueObject\Email;
use Acme\Academic\ValueObject\FirstName;
use Acme\Academic\ValueObject\LastName;
use Acme\Academic\ValueObject\Major;
use Acme\Academic\ValueObject\Password;
use App\Http\Requests\RegisterAcademicRequest;
use App\Integration\Academic\Mapper\Serializer\SerializeAcademic;

final class RegisterAcademicController extends Controller
{
    /**
     * @var WriteArticleHandler
     */
    private $handler;

    /**
     * @var SerializeAcademic
     */
    private $serializeAcademic;

    public function __construct(RegisterAcademicHandler $handler, SerializeAcademic $serializeAcademic)
    {
        $this->handler = $handler;
        $this->serializeAcademic = $serializeAcademic;
    }

    public function __invoke(RegisterAcademicRequest $request)
    {
        $command = new RegisterAcademicCommand(
            new FirstName($request->get('firstName')),
            new LastName($request->get('lastName')),
            new Email($request->get('email')),
            Password::fromClearPassword($request->get('password')),
            BirthDate::fromString($request->get('birthDate')),
            new Major($request->get('major'))
        );
        $academic = ($this->handler)($command);

        return response()->json($this->serializeAcademic->withoutPassword($academic), 201);
    }
}
