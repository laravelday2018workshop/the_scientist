<?php

declare(strict_types=1);

namespace Acme\Academic\Repository;

use Acme\Academic\Academic;
use Acme\Academic\AcademicCollection;
use Acme\Academic\Repository\Exception\AcademicNotFound;
use Acme\Academic\Repository\Exception\ImpossibleToRetrieveAcademics;
use Acme\Academic\Repository\Exception\ImpossibleToSaveAcademic;
use Acme\Academic\ValueObject\AcademicID;
use Acme\Common\Exception\UnexpectedError;

interface AcademicRepository
{
    public const DEFAULT_SKIP = 0;

    public const DEFAULT_TAKE = 10;

    public const MAX_SIZE = 20;

    /**
     * @throws AcademicNotFound
     * @throws ImpossibleToRetrieveAcademics
     */
    public function getById(AcademicID $academicID): Academic;

    /**
     * @throws ImpossibleToRetrieveAcademics
     */
    public function list(int $skip = self::DEFAULT_SKIP, int $take = self::DEFAULT_TAKE): AcademicCollection;

    /**
     * @throws UnexpectedError
     */
    public function nextID(): AcademicID;

    /**
     * @throws ImpossibleToSaveAcademic
     */
    public function add(Academic $academic): void;

    /**
     * @throws ImpossibleToSaveAcademic
     */
    public function update(Academic $academic): void;
}
