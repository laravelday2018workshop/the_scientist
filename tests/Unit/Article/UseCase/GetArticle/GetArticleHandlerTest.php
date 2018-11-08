<?php

declare(strict_types=1);

namespace Tests\Unit\Article\UseCase\GetArticle;

use Acme\Article\Article;
use Acme\Article\Repository\ArticleRepository;
use Acme\Article\Repository\Exception\ArticleNotFound;
use Acme\Article\UseCase\GetArticle\GetArticleCommand;
use Acme\Article\UseCase\GetArticle\GetArticleHandler;
use Acme\Article\ValueObject\ArticleID;
use Prophecy\Argument;
use Tests\TestCase;

/**
 * @covers \Acme\Article\UseCase\CreateArticle\CreateArticleHandler
 */
final class GetArticleHandlerTest extends TestCase
{
    /**
     * @test
     * @dataProvider articleDataProvider
     */
    public function should_create_an_article(Article $expectedArticle): void
    {
        $repositoryProphecy = $this->prophesize(ArticleRepository::class);

        $repositoryProphecy->getById(Argument::type(ArticleID::class))
                           ->shouldBeCalledOnce()
                           ->willReturn($expectedArticle);

        $repository = $repositoryProphecy->reveal();

        $handler = new GetArticleHandler($repository);

        $command = new GetArticleCommand($expectedArticle->id());

        $article = $handler($command);

        $this->assertSame($expectedArticle, $article);
    }

    /**
     * @test
     */
    public function should_not_found_an_article()
    {
        $this->setExpectedException(\Acme\Article\Repository\Exception\ArticleNotFound::class);

        /** @var ArticleID $articleId */
        $articleId = $this->factoryFaker->instance(ArticleID::class);

        $repository = $this->prophesize(ArticleRepository::class);

        $repository->getById($articleId)
                   ->willThrow(new ArticleNotFound($articleId));

        $handler = new GetArticleHandler($repository->reveal());

        $handler(new GetArticleCommand($articleId));
    }

    public function articleDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(Article::class),
            ],
        ];
    }
}
