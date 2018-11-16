<?php

declare(strict_types=1);

namespace Acme\Common\Query;

interface Insert
{
    public function __invoke(array $data): void;
}
