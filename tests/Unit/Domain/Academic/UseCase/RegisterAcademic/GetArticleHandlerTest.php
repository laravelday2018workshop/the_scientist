<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\UseCase\GetAcademic;

use Acme\Academic\Academic;
use Acme\Academic\Repository\AcademicRepository;
use Acme\Academic\Repository\Exception\AcademicNotFound;
use Acme\Academic\UseCase\GetAcademic\GetAcademicCommand;
use Acme\Academic\UseCase\GetAcademic\GetAcademicHandler;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Prophecy\Argument;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\UseCase\GetAcademic\GetAcademicHandler
 */
final class GetArticleHandlerTest extends TestCase
{
    /**
     * @test
     * @dataProvider academicDataProvider
     */
    public function should_create_an_academic(Academic $expectedAcademic): void
    {
        $repositoryProphecy = $this->prophesize(AcademicRepository::class);

        $repositoryProphecy->getById(Argument::type(AcademicRegistrationNumber::class))
                           ->shouldBeCalledOnce()
                           ->willReturn($expectedAcademic);

        $repository = $repositoryProphecy->reveal();

        $handler = new GetAcademicHandler($repository);

        $command = new GetAcademicCommand($expectedAcademic->registrationNumber());

        $academic = $handler($command);

        $this->assertSame($expectedAcademic, $academic);
    }

    /**
     * @test
     */
    public function should_not_found_an_academic()
    {
        $this->expectException(AcademicNotFound::class);

        /** @var AcademicRegistrationNumber $academicId */
        $academicId = $this->factoryFaker->instance(AcademicRegistrationNumber::class);

        $repository = $this->prophesize(AcademicRepository::class);

        $repository->getById($academicId)
                   ->willThrow(new AcademicNotFound($academicId));

        $handler = new GetAcademicHandler($repository->reveal());

        $handler(new GetAcademicCommand($academicId));
    }

    public function academicDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(Academic::class),
            ],
        ];
    }
}
