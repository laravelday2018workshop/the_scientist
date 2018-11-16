<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\Repository\Exception;

use Acme\Academic\Academic;
use Acme\Academic\Repository\Exception\AcademicNotFound;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Tests\TestCase;

/**
 * Class AcademicNotFoundTest.
 *
 * @covers \Acme\Academic\Repository\Exception\AcademicNotFound
 */
class AcademicNotFoundTest extends TestCase
{
    /**
     * @test
     */
    public function should_create_an_exception(): void
    {
        /** @var AcademicRegistrationNumber $academicID */
        $academicID = $this->factoryFaker->instance(AcademicRegistrationNumber::class);
        $previousException = new \Exception();
        $expectdMessage = \sprintf('Academic with ID: "%s" was not found', $academicID);
        $exception = new AcademicNotFound($academicID, $previousException);

        $this->assertEquals($academicID, $exception->getEntityId());
        $this->assertEquals(Academic::class, $exception->getEntityName());
        $this->assertEquals($expectdMessage, $exception->getMessage());
        $this->assertSame($previousException, $exception->getPrevious());
    }
}
