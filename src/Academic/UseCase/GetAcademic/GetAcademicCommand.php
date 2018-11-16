<?php

declare(strict_types=1);

namespace Acme\Academic\UseCase\GetAcademic;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;

final class GetAcademicCommand
{
    /**
     * @var AcademicRegistrationNumber
     */
    private $academicID;

    public function __construct(AcademicRegistrationNumber $academicID)
    {
        $this->academicID = $academicID;
    }

    public function getAcademicID(): AcademicRegistrationNumber
    {
        return $this->academicID;
    }
}
