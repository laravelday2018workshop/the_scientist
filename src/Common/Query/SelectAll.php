<?php

declare(strict_types=1);

namespace Acme\Common\Query;

interface SelectAll
{
    public function __invoke(Pagination $pagination): array;
}
