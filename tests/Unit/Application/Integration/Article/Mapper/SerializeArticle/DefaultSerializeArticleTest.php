<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Article\Mapper;

use Acme\Article\Article;
use App\Integration\Article\Mapper\Serializer\DefaultSerializeArticle;
use Error;
use Tests\TestCase;

/**
 * @covers \App\Integration\Article\Mapper\Serializer\DefaultSerializeArticle
 */
final class DefaultSerializeArticleTest extends TestCase
{
    /**
     * @test
     */
    public function should_throw_an_exception_due_to_empty_implementation(): void
    {
        $this->expectException(Error::class);
        $mapper = new DefaultSerializeArticle();
        $mapper->__invoke([]);
    }

    /**
     * @test
     * @dataProvider articleDataProvider
     */
    public function should_return_an_array(Article $article): void
    {
        $mapper = new DefaultSerializeArticle();
        $data = $mapper->__invoke($article);
        $this->assertSame((string) $article->id(), $data['id']);
        $this->assertSame((string) $article->title(), $data['title']);
        $this->assertSame((string) $article->body(), $data['body']);
        $this->assertSame((string) $article->academicRegistrationNumber(), $data['academic_id']);
        $this->assertSame((string) $article->reviewerID(), $data['reviewer_id']);
        $this->assertSame($article->publishDate()->format('Y-m-d H:i:s'), $data['published_at']);
        $this->assertSame($article->creationDate()->format('Y-m-d H:i:s'), $data['created_at']);
        $this->assertSame($article->lastUpdateDate()->format('Y-m-d H:i:s'), $data['created_at']);
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
