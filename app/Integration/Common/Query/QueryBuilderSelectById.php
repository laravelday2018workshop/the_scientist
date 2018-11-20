<?php

declare(strict_types=1);

namespace App\Integration\Common\Query;

use Acme\Common\ValueObject\EntityID;
use Illuminate\Database\Query\Builder;

final class QueryBuilderSelectById implements SelectById
{
    /**
     * @var Builder
     */
    private $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function __invoke(EntityID $entityID): array
    {
        return $this->query->select()->where('id', '=', $entityID)->first()->toArray();
    }
}
