<?php

declare(strict_types=1);

namespace App\Integration\Academic\Mapper;

use Acme\Academic\Academic;

final class ViewAcademicMapper implements AcademicMapper
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
    }

    public function fromAcademic(Academic $academic): array
    {
        return ($this->fromAcademicPartialMapping)($academic);
    }
}
