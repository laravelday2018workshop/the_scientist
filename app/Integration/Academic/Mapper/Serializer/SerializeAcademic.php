<?php

declare(strict_types=1);

namespace App\Integration\Academic\Mapper\Serializer;

use Acme\Academic\Academic;

interface SerializeAcademic
{
    public function __invoke(Academic $academic): array;
}
