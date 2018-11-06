<?php

declare(strict_types=1);

namespace Acme\Academic\Mapper;

use Acme\Academic\Academic;

interface AcademicMapper
{
    public function fromArray(array $rawAcademic): Academic;

    public function fromAcademic(Academic $academic): array;
}
