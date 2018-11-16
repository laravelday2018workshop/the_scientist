<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Common\Query;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use App\Integration\Common\Query\QueryBuilderSelectById;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * @covers \App\Integration\Common\Query\QueryBuilderSelectById
 */
class QueryBuilderSelectByIdTest extends TestCase
{
    /**
     * @test
     */
    public function should_select()
    {
        /** @var AcademicRegistrationNumber $academicId */
        $academicId = $this->factoryFaker->instance(AcademicRegistrationNumber::class);

        $expectedResult = ['id' => 1];

        $builder = $this->prophesize(Builder::class);

        $builder->select()->shouldBeCalledOnce()->willReturn($builder);
        $builder->where('id', '=', (string) $academicId)->shouldBeCalledOnce()->willReturn($builder);
        $builder->first()->shouldBeCalledOnce()->willReturn(new Collection($expectedResult));

        $query = new QueryBuilderSelectById($builder->reveal());

        $result = $query($academicId);

        $this->assertEquals($expectedResult, $result);
    }
}
