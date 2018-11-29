<?php

namespace Tests\Unit\Domain\Article;

use LaravelDay\Article\Article;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @test
     *
     * @return void
     */
    public function shouldCreateAnArticle()
    {

        $id = 1;
        $title = 'Titolo per articolo 1';
        $body = 'Bodybodybodyyy';
        $creationDate = new \DateTimeImmutable();

        $article = new Article($id, $title, $body, $creationDate);

        $this->assertSame($id, $article->getId());
        $this->assertSame($title, $article->getTitle());
        $this->assertSame($body, $article->getBody());
        $this->assertSame($creationDate, $article->getCreationDate());

    }
}
