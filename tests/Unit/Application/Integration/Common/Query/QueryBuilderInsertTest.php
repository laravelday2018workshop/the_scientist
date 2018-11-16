<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Common\Query;

use App\Integration\Common\Query\QueryBuilderInsert;
use Illuminate\Database\Query\Builder;
use Tests\TestCase;

/**
 * @covers \App\Integration\Common\Query\QueryBuilderInsert
 */
class QueryBuilderInsertTest extends TestCase
{
    /**
     * @test
     */
    public function should_insert()
    {
        $data = [];

        $builder = $this->prophesize(Builder::class);

        $builder->insert($data)->shouldBeCalledOnce()->hasReturnVoid();

        $query = new QueryBuilderInsert($builder->reveal());

        $query($data);
    }
}
