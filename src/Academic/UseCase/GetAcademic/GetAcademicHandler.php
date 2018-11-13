<?php

declare(strict_types=1);

namespace Acme\Academic\UseCase\GetAcademic;

use Acme\Academic\Academic;
use Acme\Academic\Repository\AcademicRepository;
use Acme\Academic\Repository\Exception\AcademicNotFound;
use Acme\Academic\Repository\Exception\ImpossibleToRetrieveAcademics;

final class GetAcademicHandler
{
    /**
     * @var AcademicRepository
     */
    private $academicRepository;

    public function __construct(AcademicRepository $academicRepository)
    {
        $this->academicRepository = $academicRepository;
    }

    /**
     * @throws AcademicNotFound
     * @throws ImpossibleToRetrieveAcademics
     */
    public function __invoke(GetAcademicCommand $command): Academic
    {
        return $this->academicRepository->getById($command->getAcademicID());
    }
}
