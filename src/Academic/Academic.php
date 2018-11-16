<?php

declare(strict_types=1);

namespace Acme\Academic;

use Acme\Academic\ValueObject\AcademicID;

final class Academic
{
    /**
     * @var AcademicID
     */
    private $academicID;

    public function __construct(
        AcademicID $academicID
    ) {
        $this->academicID = $academicID;
    }

    public function id(): AcademicID
    {
        return $this->academicID;
    }
}
