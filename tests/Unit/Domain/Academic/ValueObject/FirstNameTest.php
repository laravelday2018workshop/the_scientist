<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\ValueObject;

use Acme\Academic\ValueObject\Exception\InvalidFirstName;
use Acme\Academic\ValueObject\FirstName;
use Error;
use Faker\Generator as Faker;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\ValueObject\FirstName
 */
final class FirstNameTest extends TestCase
{
    /**
     * @test
     * @dataProvider validFirstNameDataProvider
     */
    public function should_create_first_name(string $rawFirstName): void
    {
        $firstName = new FirstName($rawFirstName);
        $this->assertTrue($firstName->isEquals($firstName));
        $this->assertSame(\trim($rawFirstName), (string) $firstName);
    }

    /**
     * @test
     * @dataProvider invalidFirstNameDataProvider
     */
    public function should_thrown_invalidFirstName_exception(string $value): void
    {
        $this->expectException(InvalidFirstName::class);
        $this->expectExceptionMessage(
            \sprintf(InvalidFirstName::LENGTH_MESSAGE_FORMAT, \trim($value), FirstName::MIN_LENGTH, FirstName::MAX_LENGTH)
        );
        new FirstName($value);
    }

    /**
     * @test
     * @dataProvider validFirstNameDataProvider
     */
    public function should_throw_exception_on_clone(string $value): void
    {
        $this->expectException(Error::class);
        $firstName = new FirstName($value);
        clone $firstName;
    }

    public function validFirstNameDataProvider(): array
    {
        /** @var Faker $faker */
        $faker = $this->factoryFaker->instance(Faker::class);

        return [
            [$faker->firstName()],
            ["                          {$faker->firstName()}                          "],
        ];
    }

    public function invalidFirstNameDataProvider(): array
    {
        /** @var Faker $faker */
        $faker = $this->factoryFaker->instance(Faker::class);

        return [
            [''],
            [$faker->sentence(500)],
            ['                                       a                             '],
            ['1'],
        ];
    }
}
