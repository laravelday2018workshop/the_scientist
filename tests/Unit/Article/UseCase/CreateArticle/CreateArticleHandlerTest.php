<?php

declare(strict_types=1);

namespace Tests\Unit\Article\UseCase\CreateArticle;

use Acme\Academic\ValueObject\AcademicID;
use Acme\Article\Article;
use Acme\Article\Repository\ArticleRepository;
use Acme\Article\UseCase\CreateArticle\CreateArticleCommand;
use Acme\Article\UseCase\CreateArticle\CreateArticleHandler;
use Acme\Article\ValueObject\ArticleID;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use Acme\Reviewer\ValueObject\ReviewerID;
use PHPUnit\Framework\Assert;
use Prophecy\Argument;
use Tests\TestCase;

/**
 * @covers \Acme\Article\UseCase\CreateArticle\CreateArticleHandler
 */
final class CreateArticleHandlerTest extends TestCase
{
    /**
     * @test
     * @dataProvider articleDataProvider
     */
    public function should_create_an_article(ArticleID $articleID, CreateArticleCommand $command): void
    {
        $repositoryProphecy = $this->prophesize(ArticleRepository::class);
        $repositoryProphecy->nextID()
                           ->shouldBeCalledOnce()
                           ->willReturn($articleID);

        $repositoryProphecy->add(Argument::type(Article::class))
                           ->shouldBeCalledOnce()
                           ->will(function (array $args) use ($command) {
                               [$article] = $args;

                               Assert::assertSame($command->getTitle(), $article->title());
                               Assert::assertSame($command->getBody(), $article->body());
                               Assert::assertSame($command->getReviewerID(), $article->reviewerID());
                               Assert::assertSame($command->getAcademicID(), $article->academicID());
                           });

        $repository = $repositoryProphecy->reveal();

        $handler = new CreateArticleHandler($repository);

        $handler($command);
    }

    public function articleDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(ArticleID::class),
                new CreateArticleCommand(
                    $this->factoryFaker->instance(Title::class),
                    $this->factoryFaker->instance(Body::class),
                    $this->factoryFaker->instance(ReviewerID::class),
                    $this->factoryFaker->instance(AcademicID::class)
                ),
            ],
        ];
    }
}
