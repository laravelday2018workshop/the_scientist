<?php

declare(strict_types=1);

namespace App\Integration\Common\Query;

interface Insert
{
    public function __invoke(array $data): void;
}
