<?php

declare(strict_types=1);

namespace App\Integration\Common\Query;

interface SelectAll
{
    public function __invoke(Pagination $pagination): array;
}
