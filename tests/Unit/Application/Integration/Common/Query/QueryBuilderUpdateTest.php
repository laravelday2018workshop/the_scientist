<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Common\Query;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use App\Integration\Common\Query\QueryBuilderUpdate;
use Illuminate\Database\Query\Builder;
use Tests\TestCase;

/**
 * @covers \App\Integration\Common\Query\QueryBuilderUpdate
 */
class QueryBuilderUpdateTest extends TestCase
{
    /**
     * @test
     */
    public function should_insert()
    {
        $data = [];

        /** @var AcademicRegistrationNumber $academicId */
        $academicId = $this->factoryFaker->instance(AcademicRegistrationNumber::class);

        $builder = $this->prophesize(Builder::class);

        $builder->where('id', (string) $academicId)->shouldBeCalledOnce()->willReturn($builder);
        $builder->update($data)->shouldBeCalledOnce();

        $query = new QueryBuilderUpdate($builder->reveal());

        $query($academicId, $data);
    }
}
