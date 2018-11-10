<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Article\UseCase\ListArticles;

use Acme\Article\UseCase\ListArticles\ListArticlesCommand;
use Tests\TestCase;

/**
 * @covers \Acme\Article\UseCase\ListArticles\ListArticlesCommand
 */
final class ListArticlesCommandTest extends TestCase
{
    /**
     * @test
     * @dataProvider argumentsDataProvider
     */
    public function command_should_be_created(?int $skip, ?int $take): void
    {
        $command = new ListArticlesCommand($skip, $take);

        $this->assertSame($skip, $command->getSkip());
        $this->assertSame($take, $command->getTake());
    }

    public function argumentsDataProvider(): array
    {
        return [
            [0, 0],
            [100, 1],
            [1, 100],
            [null, null],
        ];
    }
}
