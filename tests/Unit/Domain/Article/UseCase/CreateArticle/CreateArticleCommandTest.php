<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Article\UseCase\CreateArticle;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Article\UseCase\CreateArticle\CreateArticleCommand;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use Acme\Reviewer\ValueObject\ReviewerID;
use Tests\TestCase;

/**
 * @covers \Acme\Article\UseCase\CreateArticle\CreateArticleCommand
 */
final class CreateArticleCommandTest extends TestCase
{
    /**
     * @test
     * @dataProvider argumentsDataProvider
     */
    public function command_should_be_created(Title $title, Body $body, ReviewerID $reviewerID, AcademicRegistrationNumber $academicID): void
    {
        $command = new CreateArticleCommand($title, $body, $reviewerID, $academicID);

        $this->assertSame($title, $command->getTitle());
        $this->assertSame($body, $command->getBody());
        $this->assertSame($reviewerID, $command->getReviewerID());
        $this->assertSame($academicID, $command->getAcademicID());
    }

    public function argumentsDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(Title::class),
                $this->factoryFaker->instance(Body::class),
                $this->factoryFaker->instance(ReviewerID::class),
                $this->factoryFaker->instance(AcademicRegistrationNumber::class),
            ],
        ];
    }
}
