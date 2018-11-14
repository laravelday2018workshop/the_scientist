<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 11/14/18
 * Time: 11:48 AM
 */

namespace Tests\Unit\Domain\Academic;

use Acme\Academic\Academic;
use Acme\Academic\ValueObject\AcademicID;
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
        /** @var AcademicID $academicId */
        $academicId = $this->factoryFaker->instance(AcademicID::class);

        $academic = new Academic($academicId);

        $this->assertSame($academicId, $academic->id());
    }
}
