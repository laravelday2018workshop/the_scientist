<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Article\UseCase\UpdateArticle;

use Acme\Article\Article;
use Acme\Article\Repository\ArticleRepository;
use Acme\Article\UseCase\UpdateArticle\UpdateArticleCommand;
use Acme\Article\UseCase\UpdateArticle\UpdateArticleHandler;
use Acme\Article\ValueObject\ArticleID;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use PHPUnit\Framework\Assert;
use Prophecy\Argument;
use Tests\TestCase;

/**
 * @covers \Acme\Article\UseCase\UpdateArticle\UpdateArticleHandler
 */
final class UpdateArticleHandlerTest extends TestCase
{
    /**
     * @test
     * @dataProvider articleDataProvider
     *
     * @param Article              $article
     * @param UpdateArticleCommand $command
     *
     * @throws \Acme\Article\Repository\Exception\ArticleNotFound
     * @throws \Acme\Article\Repository\Exception\ImpossibleToRetrieveAcademics
     * @throws \Acme\Article\Repository\Exception\ImpossibleToSaveAcademic
     */
    public function should_update_an_article(Article $article, UpdateArticleCommand $command): void
    {
        $repositoryProphecy = $this->prophesize(ArticleRepository::class);
        $repositoryProphecy->getById($command->getArticleID())->willReturn($article);
        $repositoryProphecy->update(Argument::type(Article::class))
            ->shouldBeCalledOnce()
            ->will(function (array $args) use ($command, $article) {
                [$updatedArticle] = $args;

                Assert::assertSame($article->id(), $updatedArticle->id());
                Assert::assertSame($command->getTitle(), $updatedArticle->title());
                Assert::assertSame($command->getBody(), $updatedArticle->body());
                Assert::assertSame($article->reviewerID(), $updatedArticle->reviewerID());
                Assert::assertSame($article->academicID(), $updatedArticle->academicID());
                Assert::assertNotNull($updatedArticle->lastUpdateDate());
            });

        $repository = $repositoryProphecy->reveal();

        $handler = new UpdateArticleHandler($repository);

        $handler($command);
    }

    public function articleDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(Article::class),
                new UpdateArticleCommand(
                    $this->factoryFaker->instance(ArticleID::class),
                    $this->factoryFaker->instance(Title::class),
                    $this->factoryFaker->instance(Body::class)
                ),
            ],
        ];
    }
}
