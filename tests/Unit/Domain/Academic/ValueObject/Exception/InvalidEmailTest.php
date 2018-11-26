<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\ValueObject\Exception;

use Acme\Academic\ValueObject\Email;
use Acme\Academic\ValueObject\Exception\InvalidEmail;
use Faker\Generator as Faker;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\ValueObject\Exception\InvalidEmail
 */
final class InvalidEmailTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidDomainDatProvider
     */
    public function should_set_domain_error_message($invalidValue): void
    {
        $error = InvalidEmail::fromInvalidDomain($invalidValue);
        $expectedMessage = \sprintf(InvalidEmail::DOMAIN_MESSAGE_FORMAT, $invalidValue, Email::EMAIL_DOMAIN);
        $this->assertSame($expectedMessage, $error->getMessage());
    }

    /**
     * @test
     * @dataProvider invalidErrorValueDatProvider
     */
    public function should_set_invalid_email_error_message($invalidValue): void
    {
        $error = InvalidEmail::fromInvalidValue($invalidValue);
        $expectedMessage = \sprintf(InvalidEmail::ERROR_MESSAGE_FORMAT, $invalidValue, Email::EMAIL_DOMAIN);
        $this->assertSame($expectedMessage, $error->getMessage());
    }

    public function invalidErrorValueDatProvider(): array
    {
        return [
            [1],
            ['a'],
            [''],
            ['sadhioj@djia'],
        ];
    }

    public function invalidDomainDatProvider(): array
    {
        /** @var Faker $faker */
        $faker = $this->factoryFaker->instance(Faker::class);

        return [
            [$faker->email],
            [$faker->email],
            [$faker->email],
        ];
    }
}
