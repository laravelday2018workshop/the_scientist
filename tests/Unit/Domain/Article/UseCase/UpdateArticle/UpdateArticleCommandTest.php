<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Article\UseCase\UpdateArticle;

use Acme\Article\UseCase\UpdateArticle\UpdateArticleCommand;
use Acme\Article\ValueObject\ArticleID;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use Tests\TestCase;

/**
 * @covers \Acme\Article\UseCase\UpdateArticle\UpdateArticleCommand
 */
final class UpdateArticleCommandTest extends TestCase
{
    /**
     * @test
     * @dataProvider argumentsDataProvider
     */
    public function command_should_be_created(ArticleID $articleID, Title $title, Body $body): void
    {
        $command = new UpdateArticleCommand($articleID, $title, $body);

        $this->assertSame($articleID, $command->getArticleID());
        $this->assertSame($title, $command->getTitle());
        $this->assertSame($body, $command->getBody());
    }

    public function argumentsDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(ArticleID::class),
                $this->factoryFaker->instance(Title::class),
                $this->factoryFaker->instance(Body::class),
            ],
        ];
    }
}
