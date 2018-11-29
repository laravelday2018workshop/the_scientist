<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Article\UseCase;

use LaravelDay\Article\UseCase\ListArticles\ListArticles;
use Tests\TestCase;

class ListArticleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @test
     */
    public function shouldListArticles()
    {
        $handler = new ListArticles();

        $expectedData = [
            [
                'body' => 'Questo è il body',
                'creationDate' => '2018-11-29 00:00:00',
                'lastUpdate' => '2018-11-29 00:00:00',
                'publishDate' => '2018-11-29 00:00:00',
            ],
        ];

        $data = $handler();

        $this->assertSame($expectedData, $data);
    }
}
