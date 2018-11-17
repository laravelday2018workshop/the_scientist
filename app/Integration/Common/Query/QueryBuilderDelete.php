<?php

declare(strict_types=1);

namespace App\Integration\Common\Query;

use Acme\Common\Query\Delete;
use Acme\Common\ValueObject\EntityID;
use Illuminate\Database\Query\Builder;

final class QueryBuilderDelete implements Delete
{
    /**
     * @var Builder
     */
    private $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function __invoke(EntityID $entityID): void
    {
        $this->query->where('id', (string) $entityID)->delete();
    }
}
