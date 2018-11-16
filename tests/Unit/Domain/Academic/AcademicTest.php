<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 11/14/18
 * Time: 11:48 AM.
 */

namespace Tests\Unit\Domain\Academic;

use Acme\Academic\Academic;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\Academic
 */
class AcademicTest extends TestCase
{
    /**
     * @test
     */
    public function should_create_an_academic()
    {
        /** @var AcademicRegistrationNumber $academicId */
        $academicId = $this->factoryFaker->instance(AcademicRegistrationNumber::class);

        $academic = new Academic($academicId);

        $this->assertSame($academicId, $academic->registrationNumber());
    }
}
