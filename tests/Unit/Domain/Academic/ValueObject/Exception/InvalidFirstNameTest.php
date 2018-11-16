<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\ValueObject\Exception;

use Acme\Academic\ValueObject\Exception\InvalidFirstName;
use Acme\Academic\ValueObject\FirstName;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\ValueObject\Exception\InvalidFirstName
 */
final class InvalidFirstNameTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidValueDatProvider
     */
    public function should_set_error_message($invalidValue): void
    {
        $error = new InvalidFirstName($invalidValue);
        $expectedMessage = \sprintf(InvalidFirstName::LENGTH_MESSAGE_FORMAT, $invalidValue, FirstName::MIN_LENGTH, FirstName::MAX_LENGTH);
        $this->assertSame($expectedMessage, $error->getMessage());
    }

    public function invalidValueDatProvider(): array
    {
        return [
            [1],
            ['a'],
            [''],
        ];
    }
}
