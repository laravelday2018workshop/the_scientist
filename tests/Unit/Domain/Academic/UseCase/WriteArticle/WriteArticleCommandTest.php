<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Article\UseCase\CreateArticle;

use Acme\Academic\UseCase\WriteArticle\WriteArticleCommand;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\UseCase\WriteArticle\WriteArticleCommand
 */
final class WriteArticleCommandTest extends TestCase
{
    /**
     * @test
     * @dataProvider argumentsDataProvider
     */
    public function command_should_be_created(Title $title, Body $body, AcademicRegistrationNumber $academicID): void
    {
        $command = new WriteArticleCommand($title, $body, $academicID);

        $this->assertSame($title, $command->getTitle());
        $this->assertSame($body, $command->getBody());
        $this->assertSame($academicID, $command->getAcademicRegistrationNumber());
    }

    public function argumentsDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(Title::class),
                $this->factoryFaker->instance(Body::class),
                $this->factoryFaker->instance(AcademicRegistrationNumber::class),
            ],
        ];
    }
}
