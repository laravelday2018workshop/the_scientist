<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\UseCase\RegisterAcademic;

use Acme\Academic\UseCase\RegisterAcademic\RegisterAcademicCommand;
use Acme\Academic\ValueObject\BirthDate;
use Acme\Academic\ValueObject\Email;
use Acme\Academic\ValueObject\FirstName;
use Acme\Academic\ValueObject\LastName;
use Acme\Academic\ValueObject\Major;
use Acme\Academic\ValueObject\Password;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\UseCase\RegisterAcademic\RegisterAcademicCommand
 */
class RegisterAcademicCommandTest extends TestCase
{
    /**
     * @test
     * @dataProvider argumentsDataProvider
     */
    public function command_should_be_created(
        FirstName $firstName,
        LastName $lastName,
        Email $email,
        Password $password,
        BirthDate $birthDate,
        Major $major
    ): void {
        $command = new RegisterAcademicCommand(
            $firstName,
            $lastName,
            $email,
            $password,
            $birthDate,
            $major
        );

        $this->assertSame($firstName, $command->getFirstName());
        $this->assertSame($lastName, $command->getLastName());
        $this->assertSame($email, $command->getEmail());
        $this->assertSame($password, $command->getPassword());
        $this->assertSame($birthDate, $command->getBirthDate());
        $this->assertSame($major, $command->getMajor());
    }

    public function argumentsDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(FirstName::class),
                $this->factoryFaker->instance(LastName::class),
                $this->factoryFaker->instance(Email::class),
                $this->factoryFaker->instance(Password::class),
                $this->factoryFaker->instance(BirthDate::class),
                $this->factoryFaker->instance(Major::class),
            ],
        ];
    }
}
