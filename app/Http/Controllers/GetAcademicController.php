<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Acme\Academic\Repository\Exception\AcademicNotFound;
use Acme\Academic\UseCase\GetAcademic\GetAcademicCommand;
use Acme\Academic\UseCase\GetAcademic\GetAcademicHandler;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use App\Http\Requests\GetAcademicRequest;
use App\Integration\Academic\Mapper\Serializer\SerializeAcademic;

final class GetAcademicController extends Controller
{
    /**
     * @var GetAcademicHandler
     */
    private $handler;
    /**
     * @var SerializeAcademic
     */
    private $serializeAcademic;

    public function __construct(GetAcademicHandler $handler, SerializeAcademic $serializeAcademic)
    {
        $this->handler = $handler;
        $this->serializeAcademic = $serializeAcademic;
    }

    /**
     * @throws AcademicNotFound
     */
    public function __invoke(GetAcademicRequest $request)
    {
        $id = $request->route()->parameter('id');
        $command = new GetAcademicCommand(AcademicRegistrationNumber::fromString($id));
        $academic = ($this->handler)($command);
        $response = $this->serializeAcademic->withoutPassword($academic);

        return response()->json($response);
    }
}
