<?php

namespace Tests\Unit\Domain\Academic;

use Acme\Academic\Academic;
use Acme\Academic\AcademicCollection;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\AcademicCollection
 */
class AcademicCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function should_create_academic_collection()
    {
        $academics = [
            $this->factoryFaker->instance(Academic::class),
            $this->factoryFaker->instance(Academic::class),
            $this->factoryFaker->instance(Academic::class),
        ];

        $collection = new AcademicCollection(...$academics);

        $this->assertSame($academics, $collection->toArray());
    }
}
