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
        $response = $this->get('api/articles');

        $response->assertStatus(200);
        $response->assertJson([
            [
                'body' => 'Questo è il body',
                'creationDate' => '2018-11-29 00:00:00',
                'lastUpdate' => '2018-11-29 00:00:00',
                'publishDate' => '2018-11-29 00:00:00',
            ]
        ]);
    }
}
