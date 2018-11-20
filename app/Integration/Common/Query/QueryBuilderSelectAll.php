<?php

declare(strict_types=1);

namespace App\Integration\Common\Query;

use Illuminate\Database\Query\Builder;

final class QueryBuilderSelectAll implements SelectAll
{
    /**
     * @var Builder
     */
    private $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function __invoke(Pagination $pagination): array
    {
        $collection = $this->query->select()
                                  ->skip($pagination->skip())
                                  ->take($pagination->take())
                                  ->get();

        return $collection->toArray();
    }
}
