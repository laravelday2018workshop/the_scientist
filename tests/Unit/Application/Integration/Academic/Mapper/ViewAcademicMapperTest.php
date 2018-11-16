<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Academic\Mapper;

use Acme\Academic\Academic;
use App\Integration\Academic\Mapper\FromAcademicPartialMapping;
use App\Integration\Academic\Mapper\ViewAcademicMapper;
use Error;
use Tests\TestCase;

/**
 * @covers \App\Integration\Academic\Mapper\ViewAcademicMapper
 */
final class ViewAcademicMapperTest extends TestCase
{
    /**
     * @test
     */
    public function should_throw_an_exception_due_to_empty_implementation(): void
    {
        $this->expectException(Error::class);
        $mapper = new ViewAcademicMapper(new FromAcademicPartialMapping());
        $mapper->fromArray([]);
    }

    /**
     * @test
     * @dataProvider academicDataProvider
     */
    public function should_return_an_array(Academic $academic): void
    {
        $mapper = new ViewAcademicMapper(new FromAcademicPartialMapping());
        $data = $mapper->fromAcademic($academic);
        $this->assertSame((string) $academic->registrationNumber(), $data['id']);
    }

    public function academicDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(Academic::class),
            ],
        ];
    }
}
