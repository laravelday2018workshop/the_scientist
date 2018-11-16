<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\ValueObject;

use Acme\Academic\ValueObject\Exception\InvalidLastName;
use Acme\Academic\ValueObject\LastName;
use Error;
use Faker\Generator as Faker;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\ValueObject\LastName
 */
final class LastNameTest extends TestCase
{
    /**
     * @test
     * @dataProvider validLastNameDataProvider
     */
    public function should_create_last_name(string $rawLastName): void
    {
        $lastName = new LastName($rawLastName);
        $this->assertTrue($lastName->isEquals($lastName));
        $this->assertSame(\trim($rawLastName), (string) $lastName);
    }

    /**
     * @test
     * @dataProvider invalidLastNameDataProvider
     */
    public function should_thrown_invalidLastName_exception(string $value): void
    {
        $this->expectException(InvalidLastName::class);
        $this->expectExceptionMessage(
            \sprintf(InvalidLastName::LENGTH_MESSAGE_FORMAT, \trim($value), LastName::MIN_LENGTH, LastName::MAX_LENGTH)
        );
        new LastName($value);
    }

    /**
     * @test
     * @dataProvider validLastNameDataProvider
     */
    public function should_throw_exception_on_clone(string $value): void
    {
        $this->expectException(Error::class);
        $lastName = new LastName($value);
        clone $lastName;
    }

    public function validLastNameDataProvider(): array
    {
        /** @var Faker $faker */
        $faker = $this->factoryFaker->instance(Faker::class);

        return [
            [$faker->lastName],
            ["                          {$faker->lastName}                          "],
        ];
    }

    public function invalidLastNameDataProvider(): array
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
