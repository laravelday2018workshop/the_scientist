<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\ValueObject;

use Acme\Academic\ValueObject\Major;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\ValueObject\Major
 */
final class MajorTest extends TestCase
{
    /**
     * @test
     */
    public function should_create_major(): void
    {
        $this->assertInstanceOf(Major::class, Major::BIOLOGICAL_SCIENCE());
        $this->assertInstanceOf(Major::class, Major::COMPUTER_SCIENCE());
    }
}
