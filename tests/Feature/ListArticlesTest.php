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
        $response->assertJson([]);

        //$this->assertTrue(true);
    }
}
