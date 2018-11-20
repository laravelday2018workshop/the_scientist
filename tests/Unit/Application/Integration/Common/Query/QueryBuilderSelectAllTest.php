<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Common\Query;

use App\Integration\Common\Query\Pagination;
use App\Integration\Common\Query\QueryBuilderSelectAll;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * @covers \App\Integration\Common\Query\QueryBuilderSelectAll
 */
class QueryBuilderSelectAllTest extends TestCase
{
    /**
     * @test
     */
    public function should_select()
    {
        $pagination = new Pagination(10, 4);
        $expectedResult = [['id' => 1], ['id' => 2]];

        $builder = $this->prophesize(Builder::class);

        $builder->select()->shouldBeCalledOnce()->willReturn($builder);
        $builder->skip($pagination->skip())->shouldBeCalledOnce()->willReturn($builder);
        $builder->take($pagination->take())->shouldBeCalledOnce()->willReturn($builder);
        $builder->get()->shouldBeCalledOnce()->willReturn(new Collection($expectedResult));

        $query = new QueryBuilderSelectAll($builder->reveal());

        $result = $query($pagination);

        $this->assertEquals($expectedResult, $result);
    }
}
