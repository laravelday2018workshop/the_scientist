<?php

declare(strict_types=1);

namespace App\Integration\Academic\Mapper\Serializer;

use Acme\Academic\Academic;

interface SerializeAcademic
{
    public function withPassword(Academic $academic): array;

    public function withoutPassword(Academic $academic): array;
}
