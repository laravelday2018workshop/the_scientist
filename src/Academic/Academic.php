<?php

declare(strict_types=1);

namespace Acme\Academic;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;

final class Academic
{
    /**
     * @var AcademicRegistrationNumber
     */
    private $id;

    public function __construct(
        AcademicRegistrationNumber $registrationNumber
    ) {
        $this->registrationNumber = $registrationNumber;
    }

    public function registrationNumber(): AcademicRegistrationNumber
    {
        return $this->registrationNumber;
    }
}
