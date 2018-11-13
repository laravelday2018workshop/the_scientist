<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Academic\UseCase\GetAcademic;

use Acme\Academic\UseCase\GetAcademic\GetAcademicCommand;
use Acme\Academic\ValueObject\AcademicID;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\UseCase\GetAcademic\GetAcademicCommand
 */
class GetAcademicCommandTest extends TestCase
{
    /**
     * @test
     * @dataProvider argumentsDataProvider
     */
    public function command_should_be_created(AcademicID $academicID)
    {
        $command = new GetAcademicCommand($academicID);

        $this->assertSame($academicID, $command->getAcademicID());
    }

    public function argumentsDataProvider()
    {
        return [
            [
                $this->factoryFaker->instance(AcademicID::class),
            ],
        ];
    }
}
