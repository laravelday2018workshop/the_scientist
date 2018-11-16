<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\ValueObject\Exception;

use Acme\Academic\ValueObject\Exception\InvalidLastName;
use Acme\Academic\ValueObject\LastName;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\ValueObject\Exception\InvalidLastName
 */
final class InvalidLastNameTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidValueDatProvider
     */
    public function should_set_error_message($invalidValue): void
    {
        $error = new InvalidLastName($invalidValue);
        $expectedMessage = \sprintf(InvalidLastName::LENGTH_MESSAGE_FORMAT, $invalidValue, LastName::MIN_LENGTH, LastName::MAX_LENGTH);
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
