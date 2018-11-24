<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\UseCase\GetAcademic;

use Acme\Academic\Academic;
use Acme\Academic\Event\AcademicWasCreatedEvent;
use Acme\Academic\Repository\AcademicRepository;
use Acme\Academic\Repository\Exception\ImpossibleToSaveAcademic;
use Acme\Academic\UseCase\RegisterAcademic\RegisterAcademicCommand;
use Acme\Academic\UseCase\RegisterAcademic\RegisterAcademicHandler;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Academic\ValueObject\BirthDate;
use Acme\Academic\ValueObject\Email;
use Acme\Academic\ValueObject\FirstName;
use Acme\Academic\ValueObject\LastName;
use Acme\Academic\ValueObject\Major;
use Acme\Academic\ValueObject\Password;
use Acme\Common\EventHandler\EventDispatcher;
use Exception;
use Prophecy\Argument;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\UseCase\GetAcademic\GetAcademicHandler
 */
final class RegisterArticleHandlerTest extends TestCase
{
    /**
     * @test
     * @dataProvider academicDataProvider
     */
    public function should_register_an_academic(
        AcademicRegistrationNumber $registrationNumber,
        RegisterAcademicCommand $command
    ): void {
        $repositoryProphecy = $this->prophesize(AcademicRepository::class);
        $repositoryProphecy->nextRegistrationNumber()->shouldBeCalledOnce()->willReturn($registrationNumber);
        $repositoryProphecy->add(Argument::type(Academic::class))->shouldBeCalledOnce();
        $repository = $repositoryProphecy->reveal();

        $dispatcherProphecy = $this->prophesize(EventDispatcher::class);
        $dispatcherProphecy->__invoke(Argument::type(AcademicWasCreatedEvent::class))->shouldBeCalledOnce();
        $dispatcher = $dispatcherProphecy->reveal();

        $handler = new RegisterAcademicHandler($repository, $dispatcher);
        $academic = $handler($command);
        $this->assertSame($registrationNumber, $academic->registrationNumber());
        $this->assertSame($command->getFirstName(), $academic->firstName());
        $this->assertSame($command->getLastName(), $academic->lastName());
        $this->assertSame($command->getEmail(), $academic->email());
        $this->assertSame($command->getPassword(), $academic->password());
        $this->assertSame($command->getMajor(), $academic->major());
        $this->assertSame($command->getBirthDate(), $academic->birthDate());
        $this->assertSame([], $academic->articles()->toArray());
    }

    /**
     * @test
     * @dataProvider commandDataProvider
     */
    public function should_fail_when_not_retrieving_net_registration_number(RegisterAcademicCommand $command): void
    {
        $this->expectException(Exception::class);
        $repositoryProphecy = $this->prophesize(AcademicRepository::class);
        $repositoryProphecy->nextRegistrationNumber()->shouldBeCalledOnce()->willThrow(new Exception());
        $repository = $repositoryProphecy->reveal();

        $dispatcher = $this->prophesize(EventDispatcher::class)->reveal();
        $handler = new RegisterAcademicHandler($repository, $dispatcher);
        $handler($command);
    }

    /**
     * @test
     * @dataProvider academicDataProvider
     */
    public function should_fail_when_not_able_to_store_the_academic(
        AcademicRegistrationNumber $registrationNumber,
        RegisterAcademicCommand $command
    ): void {
        $this->expectException(ImpossibleToSaveAcademic::class);
        $repositoryProphecy = $this->prophesize(AcademicRepository::class);
        $repositoryProphecy->nextRegistrationNumber()->shouldBeCalledOnce()->willReturn($registrationNumber);
        $repositoryProphecy->add(Argument::type(Academic::class))->willThrow(new ImpossibleToSaveAcademic());
        $repository = $repositoryProphecy->reveal();

        $dispatcher = $this->prophesize(EventDispatcher::class)->reveal();
        $handler = new RegisterAcademicHandler($repository, $dispatcher);
        $handler($command);
    }

    public function academicDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(AcademicRegistrationNumber::class),
                new RegisterAcademicCommand(
                    $this->factoryFaker->instance(FirstName ::class),
                    $this->factoryFaker->instance(LastName ::class),
                    $this->factoryFaker->instance(Email ::class),
                    $this->factoryFaker->instance(Password ::class),
                    $this->factoryFaker->instance(BirthDate ::class),
                    $this->factoryFaker->instance(Major ::class)
                ),
            ],
        ];
    }

    public function commandDataProvider(): array
    {
        return [
            [
                new RegisterAcademicCommand(
                    $this->factoryFaker->instance(FirstName ::class),
                    $this->factoryFaker->instance(LastName ::class),
                    $this->factoryFaker->instance(Email ::class),
                    $this->factoryFaker->instance(Password ::class),
                    $this->factoryFaker->instance(BirthDate ::class),
                    $this->factoryFaker->instance(Major ::class)
                ),
            ],
        ];
    }
}
