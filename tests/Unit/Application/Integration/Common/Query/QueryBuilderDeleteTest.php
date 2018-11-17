<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Common\Query;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use App\Integration\Common\Query\QueryBuilderDelete;
use Illuminate\Database\Query\Builder;
use Tests\TestCase;

/**
 * @covers \App\Integration\Common\Query\QueryBuilderDelete
 */
class QueryBuilderDeleteTest extends TestCase
{
    /**
     * @test
     */
    public function should_insert()
    {
        /** @var AcademicRegistrationNumber $academicId */
        $academicId = $this->factoryFaker->instance(AcademicRegistrationNumber::class);

        $builder = $this->prophesize(Builder::class);

        $builder->where('id', (string) $academicId)->shouldBeCalledOnce()->willReturn($builder);
        $builder->delete()->shouldBeCalledOnce();

        $query = new QueryBuilderDelete($builder->reveal());

        $query($academicId);
    }
}
