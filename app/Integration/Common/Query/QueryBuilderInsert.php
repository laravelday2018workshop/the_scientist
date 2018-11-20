<?php

declare(strict_types=1);

namespace App\Integration\Common\Query;

use Illuminate\Database\Query\Builder;

final class QueryBuilderInsert implements Insert
{
    /**
     * @var Builder
     */
    private $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function __invoke(array $data): void
    {
        $this->query->insert($data);
    }
}
