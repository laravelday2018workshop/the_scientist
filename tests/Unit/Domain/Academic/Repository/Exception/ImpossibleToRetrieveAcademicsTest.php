<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\Repository\Exception;

use Acme\Academic\Repository\Exception\ImpossibleToRetrieveAcademics;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\Repository\Exception\ImpossibleToRetrieveAcademics
 */
class ImpossibleToRetrieveAcademicsTest extends TestCase
{
    public function test__construct()
    {
        $previousException = new \Exception();
        $exception = new ImpossibleToRetrieveAcademics($previousException);

        $this->assertEquals('Impossible to retrieve academics', $exception->getMessage());
        $this->assertSame($previousException, $exception->getPrevious());
    }
}
