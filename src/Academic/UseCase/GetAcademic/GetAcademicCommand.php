<?php

namespace Acme\Academic\UseCase\GetAcademic;

use Acme\Academic\ValueObject\AcademicID;

final class GetAcademicCommand
{
    /**
     * @var AcademicID
     */
    private $academicID;

    public function __construct(AcademicID $academicID)
    {
        $this->academicID = $academicID;
    }

    public function getAcademicID(): AcademicID
    {
        return $this->academicID;
    }
}