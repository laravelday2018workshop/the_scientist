<?php

declare(strict_types=1);

namespace App\Integration\Common\Query;

use Acme\Common\ValueObject\EntityID;
use Illuminate\Database\Query\Builder;

final class QueryBuilderUpdate implements Update
{
    /**
     * @var Builder
     */
    private $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function __invoke(EntityID $entityID, array $data): void
    {
        $this->query->where('id', $entityID)->update($data);
    }
}
