<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\ValueObject;

use Acme\Academic\ValueObject\Exception\InvalidPassword;
use Acme\Academic\ValueObject\Password;
use Error;
use Faker\Generator as Faker;
use Tests\TestCase;
use const PASSWORD_DEFAULT;

/**
 * @covers \Acme\Academic\ValueObject\Password
 */
final class PasswordTest extends TestCase
{
    /**
     * @test
     * @dataProvider validPasswordDataProvider
     */
    public function should_create_password_from_clear_value(string $rawPassword): void
    {
        $password = Password::fromClearPassword($rawPassword);
        $this->assertTrue($password->isEqual($rawPassword));
        $this->assertNotSame(\trim($rawPassword), (string) $password);
    }

    /**
     * @test
     * @dataProvider validHashedPasswordDataProvider
     */
    public function should_create_password_from_hashed_value(string $rawPassword): void
    {
        $password = Password::fromHashedPassword($rawPassword);
        $this->assertInstanceOf(Password::class, $password);
    }

    /**
     * @test
     * @dataProvider invalidPasswordDataProvider
     */
    public function should_thrown_invalidPassword_exception(string $value): void
    {
        $this->expectException(InvalidPassword::class);
        $this->expectExceptionMessage(
            \sprintf(InvalidPassword::LENGTH_MESSAGE_FORMAT, Password::MIN_LENGTH)
        );
        Password::fromClearPassword($value);
    }

    /**
     * @test
     * @dataProvider validPasswordDataProvider
     */
    public function should_thrown_invalidPassword_exception_due_to_uncrypted_password(string $value): void
    {
        $this->expectException(InvalidPassword::class);
        $this->expectExceptionMessage(
            \sprintf(InvalidPassword::ERROR_MESSAGE_FORMAT)
        );
        Password::fromHashedPassword($value);
    }

    /**
     * @test
     * @dataProvider validPasswordDataProvider
     */
    public function should_throw_exception_on_clone(string $value): void
    {
        $this->expectException(Error::class);
        $password = new Password($value);
        clone $password;
    }

    public function validPasswordDataProvider(): array
    {
        /** @var Faker $faker */
        $faker = $this->factoryFaker->instance(Faker::class);

        return [
            [$faker->password()],
        ];
    }

    public function validHashedPasswordDataProvider(): array
    {
        /** @var Faker $faker */
        $faker = $this->factoryFaker->instance(Faker::class);

        return [
            [\password_hash($faker->password(), PASSWORD_DEFAULT)],
        ];
    }

    public function invalidPasswordDataProvider(): array
    {
        /** @var Faker $faker */
        $faker = $this->factoryFaker->instance(Faker::class);

        return [
            [''],
            [$faker->password(2, 5)],
            ['12345'],
        ];
    }
}
