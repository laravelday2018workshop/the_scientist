<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\ValueObject\Exception;

use Acme\Academic\ValueObject\Excpetion\InvalidAcademicRegistrationNumber;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\ValueObject\Excpetion\InvalidAcademicRegistrationNumber
 */
final class InvalidAcademicRegistrationNumberTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidValueDatProvider
     */
    public function should_set_error_message($invalidValue): void
    {
        $error = new InvalidAcademicRegistrationNumber($invalidValue);
        $expectedMessage = \sprintf(InvalidAcademicRegistrationNumber::ERROR_MESSAGE_FORMAT, $invalidValue);
        $this->assertSame($expectedMessage, $error->getMessage());
    }

    public function invalidValueDatProvider(): array
    {
        return [
            [1],
            ['an invalid number'],
            [''],
        ];
    }
}
