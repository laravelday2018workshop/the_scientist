<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\ValueObject\Exception;

use Acme\Academic\ValueObject\BirthDate;
use Acme\Academic\ValueObject\Exception\InvalidBirthDate;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\ValueObject\Exception\InvalidBirthDate
 */
final class InvalidBirthDateTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidAgeDatProvider
     */
    public function should_set_age_error_message(int $invalidAge): void
    {
        $error = InvalidBirthDate::invalidAge($invalidAge);
        $expectedMessage = \sprintf(InvalidBirthDate::ERROR_AGE_MESSAGE_FORMAT, BirthDate::MIN_AGE, $invalidAge);
        $this->assertSame($expectedMessage, $error->getMessage());
    }

    public function invalidAgeDatProvider(): array
    {
        return [
            [16],
        ];
    }

    /**
     * @test
     * @dataProvider invalidValueDatProvider
     */
    public function should_set_error_message(string $invalidAge): void
    {
        $error = InvalidBirthDate::invalidValue($invalidAge);
        $expectedMessage = \sprintf(InvalidBirthDate::ERROR_VALUE_MESSAGE_FORMAT, BirthDate::DATE_FORMAT, $invalidAge);
        $this->assertSame($expectedMessage, $error->getMessage());
    }

    public function invalidValueDatProvider(): array
    {
        return [
            ['0000-00-00'],
        ];
    }
}
