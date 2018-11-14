<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\Repository\Exception;

use Acme\Academic\Repository\Exception\ImpossibleToSaveAcademic;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acme\Academic\Repository\Exception\ImpossibleToSaveAcademic
 */
class ImpossibleToSaveAcademicTest extends TestCase
{
    public function test__construct()
    {
        $previousException = new \Exception();
        $exception = new ImpossibleToSaveAcademic($previousException);

        $this->assertEquals('Impossible to save academic', $exception->getMessage());
        $this->assertSame($previousException, $exception->getPrevious());
    }
}
