<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @test
     */
    public function shouldListArticles()
    {
        //$this->assertTrue(true);
        $response = $this->get('api/articles');
        $response->assertStatus(200);
        $response->assertJson([]);
    }
}

//php artisan make:test ListArticles
