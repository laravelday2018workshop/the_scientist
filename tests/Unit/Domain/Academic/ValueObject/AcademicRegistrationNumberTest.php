<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\ValueObject;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Academic\ValueObject\Exception\InvalidAcademicRegistrationNumber;
use Error;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acme\Academic\ValueObject\AcademicRegistrationNumber
 */
final class AcademicRegistrationNumberTest extends TestCase
{
    /**
     * @test
     * @dataProvider validStringAcademicRegistrationNumberDataProvider
     */
    public function should_create_registration_number_from_a_string(string $rawAcademicRegistrationNumber): void
    {
        $registrationNumber = AcademicRegistrationNumber::fromString($rawAcademicRegistrationNumber);
        $this->assertTrue($registrationNumber->isEquals($registrationNumber));
        $this->assertSame($rawAcademicRegistrationNumber, (string) $registrationNumber);
    }

    /**
     * @test
     * @dataProvider validIntegerAcademicRegistrationNumberDataProvider
     */
    public function should_create_registration_number_from_an_integer(int $rawAcademicRegistrationNumber): void
    {
        $registrationNumber = AcademicRegistrationNumber::fromInteger($rawAcademicRegistrationNumber);
        $this->assertTrue($registrationNumber->isEquals($registrationNumber));
        $this->assertSame("ACC-{$rawAcademicRegistrationNumber}-DZZ", (string) $registrationNumber);
    }

    /**
     * @test
     * @dataProvider invalidStringAcademicRegistrationNumberDataProvider
     */
    public function should_thrown_invalidAcademicRegistrationNumber_exception_from_string(string $registrationNumber): void
    {
        $this->expectException(InvalidAcademicRegistrationNumber::class);
        $this->expectExceptionMessage(\sprintf(InvalidAcademicRegistrationNumber::ERROR_MESSAGE_FORMAT, $registrationNumber));
        AcademicRegistrationNumber::fromString($registrationNumber);
    }

    /**
     * @test
     * @dataProvider invalidIntegerAcademicRegistrationNumberDataProvider
     */
    public function should_thrown_invalidAcademicRegistrationNumber_exception_from_integer(int $registrationNumber): void
    {
        $this->expectException(InvalidAcademicRegistrationNumber::class);
        $this->expectExceptionMessage(\sprintf(InvalidAcademicRegistrationNumber::ERROR_MESSAGE_FORMAT, "ACC-{$registrationNumber}-DZZ"));
        AcademicRegistrationNumber::fromInteger($registrationNumber);
    }

    /**
     * @test
     * @dataProvider validStringAcademicRegistrationNumberDataProvider
     */
    public function should_throw_exception_on_clone(string $registrationNumber): void
    {
        $this->expectException(Error::class);
        $registrationNumber = AcademicRegistrationNumber::fromString($registrationNumber);
        clone $registrationNumber;
    }

    public function validStringAcademicRegistrationNumberDataProvider(): array
    {
        return [
            ['ACC-1234567890-DZZ'],
            ['ACC-1987654321-DZZ'],
        ];
    }

    public function validIntegerAcademicRegistrationNumberDataProvider(): array
    {
        return [
            [1234567890],
            [1987654320],
        ];
    }

    public function invalidStringAcademicRegistrationNumberDataProvider(): array
    {
        return [
            ['-1234567890-DZZ'],
            ['ACC-1234567890-'],
            ['ACC-789123-DZZ'],
        ];
    }

    public function invalidIntegerAcademicRegistrationNumberDataProvider(): array
    {
        return [
            [123456789],
            [638],
            [1234567],
        ];
    }
}
