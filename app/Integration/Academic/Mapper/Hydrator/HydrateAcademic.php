<?php

declare(strict_types=1);

namespace App\Integration\Academic\Mapper\Hydrator;

use Acme\Academic\Academic;

interface HydrateAcademic
{
    public function __invoke(array $rawAcademic): Academic;
}
