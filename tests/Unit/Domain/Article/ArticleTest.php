<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Article;

use LaravelDay\Article\Article;
use LaravelDay\Article\ValueObject\Body;
use LaravelDay\Article\ValueObject\Title;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @test
     */
    public function shouldCreateAnArticle()
    {
        $id = 1;
        $title = 'Titolo di test';
        $body = 'Body di test';
        $creationDate = new \DateTimeImmutable();

        $article = new Article($id, new Title($title), new Body($body), $creationDate);
        $this->assertSame($id, $article->getId());
        $this->assertSame($title, (string) $article->getTitle());
        $this->assertSame($body, (string) $article->getBody());
        $this->assertSame($creationDate, $article->getCreationDate());
    }
}
