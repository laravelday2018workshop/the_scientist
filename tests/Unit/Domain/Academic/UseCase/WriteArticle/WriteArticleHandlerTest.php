<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Article\UseCase\CreateArticle;

use Acme\Academic\Academic;
use Acme\Academic\Repository\AcademicRepository;
use Acme\Academic\UseCase\WriteArticle\WriteArticleCommand;
use Acme\Academic\UseCase\WriteArticle\WriteArticleHandler;
use Acme\Article\ValueObject\ArticleID;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use PHPUnit\Framework\Assert;
use Prophecy\Argument;
use Tests\TestCase;

/**
 * @covers \Acme\Academic\UseCase\WriteArticle\WriteArticleCommand
 */
final class WriteArticleHandlerTest extends TestCase
{
    /**
     * @test
     * @dataProvider articleDataProvider
     */
    public function should_create_an_article(
        ArticleID $articleID,
        Academic $academic,
        WriteArticleCommand $command
    ): void {
        $repositoryProphecy = $this->prophesize(AcademicRepository::class);
        $repositoryProphecy->getById($command->getAcademicRegistrationNumber())->shouldBeCalledOnce()->willReturn($academic);
        $repositoryProphecy->nextArticleID()->shouldBeCalledOnce()->willReturn($articleID);

        /* @var WriteArticleCommand $command */
        $repositoryProphecy->update(Argument::type(Academic::class))->shouldBeCalledOnce()
            ->will(function (array $args) use ($command) {
                /** @var Academic $academic */
                [$academic] = $args;

                $expectedArticle = $academic->articles()->toArray()[0];
                Assert::assertSame($command->getTitle(), $expectedArticle->title());
                Assert::assertSame($command->getBody(), $expectedArticle->body());
                Assert::assertTrue($academic->registrationNumber()->isEquals($command->getAcademicRegistrationNumber()));
            });

        $repository = $repositoryProphecy->reveal();
        $handler = new WriteArticleHandler($repository);
        $handler($command);
    }

    public function articleDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(ArticleID::class),
                $academic = $this->factoryFaker->instance(Academic::class),
                new WriteArticleCommand(
                    $this->factoryFaker->instance(Title::class),
                    $this->factoryFaker->instance(Body::class),
                    $academic->registrationNumber()
                ),
            ],
        ];
    }
}
