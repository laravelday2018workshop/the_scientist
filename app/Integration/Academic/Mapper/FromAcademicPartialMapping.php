<?php

declare(strict_types=1);

namespace App\Integration\Academic\Mapper;

use Acme\Academic\Academic;

final class FromAcademicPartialMapping
{
    public function __invoke(Academic $academic): array
    {
        return [
            'id' => (string) $academic->id(),
        ];
    }
}
