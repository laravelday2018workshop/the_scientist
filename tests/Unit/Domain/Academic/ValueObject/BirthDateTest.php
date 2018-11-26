<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\ValueObject;

use Acme\Academic\ValueObject\BirthDate;
use Acme\Academic\ValueObject\Exception\InvalidBirthDate;
use DateInterval;
use DateTimeImmutable;
use Error;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\ValueObject\BirthDate
 */
final class BirthDateTest extends TestCase
{
    /**
     * @test
     * @dataProvider validBirthDateDataProvider
     */
    public function should_create_birth_date_from_string(string $rawBirthDate): void
    {
        $birthDate = BirthDate::fromString($rawBirthDate);
        $this->assertTrue($birthDate->isEquals($birthDate));
        $this->assertSame($rawBirthDate, (string) $birthDate);
    }

    /**
     * @test
     * @dataProvider invalidValueBirthDateDataProvider
     */
    public function should_thrown_invalid_birth_date_exception_due_to_invalid_format($date): void
    {
        $this->expectException(InvalidBirthDate::class);
        $this->expectExceptionMessage(
            \sprintf(InvalidBirthDate::ERROR_VALUE_MESSAGE_FORMAT, BirthDate::DATE_FORMAT, $date)
        );
        BirthDate::fromString($date);
    }

    /**
     * @test
     * @dataProvider invalidAgeBirthDateDataProvider
     */
    public function should_thrown_invalid_birth_date_exception_due_to_invalid_age(string $date): void
    {
        $this->expectException(InvalidBirthDate::class);
        $this->expectExceptionMessageRegExp('/The academic must be at lease \d{1,2} years old. Given: \d{0,2} years/');
        BirthDate::fromString($date);
    }

    /**
     * @test
     * @dataProvider validBirthDateDataProvider
     */
    public function should_throw_exception_on_clone(string $date): void
    {
        $this->expectException(Error::class);
        $birthDate = new BirthDate($date);
        clone $birthDate;
    }

    public function validBirthDateDataProvider(): array
    {
        return [
            ['1990-10-10'],
        ];
    }

    public function invalidValueBirthDateDataProvider(): array
    {
        return [
            [''],
            ['1990-10-10 10:1'],
            ['2100-10-10 10:10:10'],
        ];
    }

    public function invalidAgeBirthDateDataProvider(): array
    {
        $now = new DateTimeImmutable();

        return [
            ['lessTenYears' => $now->sub(new DateInterval('P10Y'))->format(BirthDate::DATE_FORMAT)],
        ];
    }
}
