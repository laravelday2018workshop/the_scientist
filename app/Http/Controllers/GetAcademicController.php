<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Acme\Academic\Repository\Exception\AcademicNotFound;
use Acme\Academic\UseCase\GetAcademic\GetAcademicCommand;
use Acme\Academic\UseCase\GetAcademic\GetAcademicHandler;
use Acme\Academic\ValueObject\AcademicID;
use App\Http\Requests\GetAcademicRequest;
use App\Integration\Academic\Mapper\ViewAcademicMapper;

final class GetAcademicController extends Controller
{
    /**
     * @var GetAcademicHandler
     */
    private $handler;
    /**
     * @var ViewAcademicMapper
     */
    private $viewAcademicMapper;

    public function __construct(GetAcademicHandler $handler, ViewAcademicMapper $viewAcademicMapper)
    {
        $this->handler = $handler;
        $this->viewAcademicMapper = $viewAcademicMapper;
    }

    /**
     * @throws AcademicNotFound
     */
    public function __invoke(GetAcademicRequest $request)
    {
        $id = $request->route()->parameter('id');
        $command = new GetAcademicCommand(AcademicID::fromUUID($id));
        $academic = $this->handler->__invoke($command);

        $response = $this->viewAcademicMapper->fromAcademic($academic);

        return response()->json($response);
    }
}
