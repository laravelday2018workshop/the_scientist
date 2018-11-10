<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Article\UseCase\GetArticle;

use Acme\Article\UseCase\GetArticle\GetArticleCommand;
use Acme\Article\ValueObject\ArticleID;
use Tests\TestCase;

/**
 * @covers \Acme\Article\UseCase\GetArticle\GetArticleCommand
 */
class GetArticleCommandTest extends TestCase
{
    /**
     * @test
     * @dataProvider argumentsDataProvider
     */
    public function command_should_be_created(ArticleID $articleID)
    {
        $command = new GetArticleCommand($articleID);

        $this->assertSame($articleID, $command->getArticleID());
    }

    public function argumentsDataProvider()
    {
        return [
            [
                $this->factoryFaker->instance(ArticleID::class),
            ],
        ];
    }
}
