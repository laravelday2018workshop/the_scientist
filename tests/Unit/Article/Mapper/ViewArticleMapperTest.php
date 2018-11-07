<?php

declare(strict_types=1);

namespace Tests\Unit\Article\Mapper;

use Acme\Article\Article;
use App\Integration\Article\Mapper\ViewArticleMapper;
use Error;
use Tests\TestCase;
use const DATE_ATOM;

final class ViewArticleMapperTest extends TestCase
{
    /**
     * @test
     */
    public function should_throw_an_exception_due_to_empty_implementation(): void
    {
        $this->expectException(Error::class);
        $mapper = new ViewArticleMapper();
        $mapper->fromArray([]);
    }

    /**
     * @test
     * @dataProvider articleDataProvider
     */
    public function should_return_an_array(Article $article): void
    {
        $mapper = new ViewArticleMapper();
        $data = $mapper->fromArticle($article);
        $this->assertSame((string) $article->id(), $data['id']);
        $this->assertSame((string) $article->title(), $data['title']);
        $this->assertSame((string) $article->body(), $data['body']);
        $this->assertSame((string) $article->academicID(), $data['academic_id']);
        $this->assertSame((string) $article->reviewerID(), $data['reviewer_id']);
        $this->assertSame($article->publishDate()->format(DATE_ATOM), $data['published_at']);
        $this->assertSame($article->creationDate()->format(DATE_ATOM), $data['created_at']);
        $this->assertSame($article->lastUpdateDate()->format(DATE_ATOM), $data['created_at']);
    }

    public function articleDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(Article::class),
            ],
        ];
    }
}
