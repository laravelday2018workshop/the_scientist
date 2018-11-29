<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListArticlesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @test
     *
     * @return void
     */
    public function shouldListArticle()
    {
        $handler = new ListArticles();

        $expectedData = [
            [
                'title' => 'Articolo 1',
                'body' => 'Questo è un articolo',
                'creationDate' => '2018-11-29 00:00:00'
            ]
        ];

    }
}
