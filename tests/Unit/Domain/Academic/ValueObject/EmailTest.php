<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\ValueObject;

use Acme\Academic\ValueObject\Email;
use Acme\Academic\ValueObject\Exception\InvalidEmail;
use Error;
use Faker\Generator as Faker;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\ValueObject\Email
 */
final class EmailTest extends TestCase
{
    /**
     * @test
     * @dataProvider validEmailDataProvider
     */
    public function should_create_email(string $rawEmail): void
    {
        $email = new Email($rawEmail);
        $this->assertTrue($email->isEquals($email));
        $this->assertSame(\trim($rawEmail), (string) $email);
    }

    /**
     * @test
     * @dataProvider invalidEmailDataProvider
     */
    public function should_thrown_invalidEmail_exception_due_to_an_invalid_value(string $value): void
    {
        $this->expectException(InvalidEmail::class);
        $this->expectExceptionMessage(
            \sprintf(InvalidEmail::ERROR_MESSAGE_FORMAT, \trim($value), Email::EMAIL_DOMAIN)
        );
        new Email($value);
    }

    /**
     * @test
     * @dataProvider invalidDomainEmailDataProvider
     */
    public function should_thrown_invalidEmail_exception_due_to_an_invalid_domain(string $value): void
    {
        $this->expectException(InvalidEmail::class);
        $this->expectExceptionMessage(
            \sprintf(InvalidEmail::DOMAIN_MESSAGE_FORMAT, \trim($value), Email::EMAIL_DOMAIN)
        );
        new Email($value);
    }

    /**
     * @test
     * @dataProvider validEmailDataProvider
     */
    public function should_throw_exception_on_clone(string $value): void
    {
        $this->expectException(Error::class);
        $email = new Email($value);
        clone $email;
    }

    public function validEmailDataProvider(): array
    {
        /** @var Faker $faker */
        $faker = $this->factoryFaker->instance(Faker::class);

        return [
            ["{$faker->firstName}.{$faker->lastName}".Email::EMAIL_DOMAIN],
            ["                          {$faker->firstName}.{$faker->lastName}".Email::EMAIL_DOMAIN],
        ];
    }

    public function invalidEmailDataProvider(): array
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

    public function invalidDomainEmailDataProvider(): array
    {
        /** @var Faker $faker */
        $faker = $this->factoryFaker->instance(Faker::class);

        return [
            [$faker->email],
            ["                                       {$faker->email}                             "],
        ];
    }
}
