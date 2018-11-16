<?php

declare(strict_types=1);

namespace App\Integration\Academic\Mapper;

use Acme\Academic\Academic;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;

final class DatabaseAcademicMapper implements AcademicMapper
{
    /**
     * @var FromAcademicPartialMapping
     */
    private $fromAcademicPartialMapping;

    public function __construct(FromAcademicPartialMapping $fromAcademicPartialMapping)
    {
        $this->fromAcademicPartialMapping = $fromAcademicPartialMapping;
    }

    public function fromArray(array $rawAcademic): Academic
    {
        return new Academic(
            AcademicRegistrationNumber::fromString($rawAcademic['id'])
        );
    }

    public function fromAcademic(Academic $academic): array
    {
        return ($this->fromAcademicPartialMapping)($academic);
    }
}
