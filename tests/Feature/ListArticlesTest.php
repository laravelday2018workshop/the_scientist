<?php

namespace Tests\Feature;

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
    public function shouldListArticles()
    {
        $response = $this->get('api/articles');
        $response->assertStatus(200);
        $response->assertJson([
            [
                'title' => 'Articolo 1',
                'body' => 'Questo è un articolo',
                'creationDate' => '2018-11-29 00:00:00'
            ]
        ]);

        //$this->assertTrue(true);
    }
}
