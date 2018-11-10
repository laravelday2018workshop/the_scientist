<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Article\UseCase\ListArticles;

use Acme\Article\ArticleCollection;
use Acme\Article\Repository\ArticleRepository;
use Acme\Article\UseCase\ListArticles\ListArticlesCommand;
use Acme\Article\UseCase\ListArticles\ListArticlesHandler;
use Tests\TestCase;

/**
 * @covers \Acme\Article\UseCase\ListArticles\ListArticlesHandler
 */
final class ListCreatesArticleHandlerTest extends TestCase
{
    /**
     * @test
     * @dataProvider listArticleCommandDataProvider
     */
    public function should_list_articles(ListArticlesCommand $command, int $expectedSkip, int $expectedTake): void
    {
        $repositoryProphecy = $this->prophesize(ArticleRepository::class);
        $repositoryProphecy->list($expectedSkip, $expectedTake)->shouldBeCalledOnce()->willReturn(new ArticleCollection());
        $repository = $repositoryProphecy->reveal();

        $handler = new ListArticlesHandler($repository);
        $handler($command);
    }

    public function listArticleCommandDataProvider(): array
    {
        return [
            [new ListArticlesCommand(1, 0), 1, ArticleRepository::DEFAULT_TAKE],
            [new ListArticlesCommand(0, 0), ArticleRepository::DEFAULT_SKIP, ArticleRepository::DEFAULT_TAKE],
            [new ListArticlesCommand(0, 1), ArticleRepository::DEFAULT_SKIP, 1],
            [new ListArticlesCommand(100, 990), 100, 990],
            [new ListArticlesCommand(null, null), ArticleRepository::DEFAULT_SKIP, ArticleRepository::DEFAULT_TAKE],
        ];
    }
}
