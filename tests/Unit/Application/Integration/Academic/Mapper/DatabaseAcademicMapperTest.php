<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Academic\Mapper;

use Acme\Academic\Academic;
use App\Integration\Academic\Mapper\DatabaseAcademicMapper;
use App\Integration\Academic\Mapper\FromAcademicPartialMapping;
use Error;
use Faker\Generator as Faker;
use Tests\TestCase;

/**
 * @covers \App\Integration\Academic\Mapper\DatabaseAcademicMapper
 */
final class DatabaseAcademicMapperTest extends TestCase
{
    /**
     * @test
     * @dataProvider academicDataProvider
     */
    public function should_throw_an_exception_due_to_empty_implementation(Academic $academic): void
    {
        $this->expectException(Error::class);
        $mapper = new DatabaseAcademicMapper();
        $mapper->fromAcademic($academic);
    }

    /**
     * @test
     * @dataProvider databaseRecordDataProvider
     */
    public function should_return_an_academic(array $rawAcademic): void
    {
        $mapper = new DatabaseAcademicMapper(new FromAcademicPartialMapping());
        $academic = $mapper->fromArray($rawAcademic);
        $this->assertSame($rawAcademic['id'], (string) $academic->id());
    }

    /**
     * @test
     * @dataProvider databaseRecordWithDatesDataProvider
     */
    public function should_return_an_academic_withDates(array $rawAcademic): void
    {
        $mapper = new DatabaseAcademicMapper(new FromAcademicPartialMapping());
        $academic = $mapper->fromArray($rawAcademic);
        $this->assertSame($rawAcademic['id'], (string) $academic->id());
    }

    public function academicDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(Academic::class),
            ],
        ];
    }

    public function databaseRecordDataProvider(): array
    {
        /** @var Faker $faker */
        $faker = $this->factoryFaker->instance(Faker::class);

        return [
            [
                [
                    'id' => $faker->uuid,
                ],
            ],
        ];
    }

    public function databaseRecordWithDatesDataProvider(): array
    {
        /** @var Faker $faker */
        $faker = $this->factoryFaker->instance(Faker::class);

        return [
            [
                [
                    'id' => $faker->uuid,
                ],
            ],
        ];
    }
}
