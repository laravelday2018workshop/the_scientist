<?php

declare(strict_types=1);

namespace Tests\Unit\Article\Mapper;

use Acme\Article\Article;
use Acme\Article\Mapper\DatabaseArticleMapper;
use DateTimeImmutable;
use Error;
use Faker\Generator as Faker;
use Tests\TestCase;
use const DATE_ATOM;

final class DatabaseArticleMapperTest extends TestCase
{
    /**
     * @test
     * @dataProvider articleDataProvider
     */
    public function should_throw_an_exception_due_to_empty_implementation(Article $article): void
    {
        $this->expectException(Error::class);
        $mapper = new DatabaseArticleMapper();
        $mapper->fromArticle($article);
    }

    /**
     * @test
     * @dataProvider databaseRecordDataProvider
     */
    public function should_return_an_article(array $rawArticle): void
    {
        $mapper = new DatabaseArticleMapper();
        $article = $mapper->fromArray($rawArticle);
        $this->assertSame($rawArticle['id'], (string) $article->id());
        $this->assertSame($rawArticle['title'], (string) $article->title());
        $this->assertSame($rawArticle['body'], (string) $article->body());
        $this->assertSame($rawArticle['academic_id'], (string) $article->academicID());
        $this->assertSame($rawArticle['reviewer_id'], (string) $article->reviewerID());
        $this->assertNull($article->publishDate());
        $this->assertSame($rawArticle['created_at'], $article->creationDate()->format(DATE_ATOM));
        $this->assertNull($article->lastUpdateDate());
    }

    /**
     * @test
     * @dataProvider databaseRecordWithDatesDataProvider
     */
    public function should_return_an_article_withDates(array $rawArticle): void
    {
        $mapper = new DatabaseArticleMapper();
        $article = $mapper->fromArray($rawArticle);
        $this->assertSame($rawArticle['id'], (string) $article->id());
        $this->assertSame($rawArticle['title'], (string) $article->title());
        $this->assertSame($rawArticle['body'], (string) $article->body());
        $this->assertSame($rawArticle['academic_id'], (string) $article->academicID());
        $this->assertSame($rawArticle['reviewer_id'], (string) $article->reviewerID());
        $this->assertSame($rawArticle['published_at'], $article->publishDate()->format(DATE_ATOM));
        $this->assertSame($rawArticle['created_at'], $article->creationDate()->format(DATE_ATOM));
        $this->assertSame($rawArticle['updated_at'], $article->lastUpdateDate()->format(DATE_ATOM));
    }

    public function articleDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(Article::class),
            ],
        ];
    }

    public function databaseRecordDataProvider(): array
    {
        /** @var Faker $faker */
        $faker = $this->factoryFaker->instance(Faker::class);

        return [
            [
                [
                    'id' => $faker->uuid,
                    'title' => $faker->sentence(3, 10),
                    'body' => $faker->sentence(3, 10),
                    'academic_id' => $faker->uuid,
                    'reviewer_id' => $faker->uuid,
                    'published_at' => null,
                    'created_at' => (new DateTimeImmutable())->format(DATE_ATOM),
                    'updated_at' => null,
                ],
            ],
        ];
    }

    public function databaseRecordWithDatesDataProvider(): array
    {
        /** @var Faker $faker */
        $faker = $this->factoryFaker->instance(Faker::class);

        return [
            [
                [
                    'id' => $faker->uuid,
                    'title' => $faker->sentence(3, 10),
                    'body' => $faker->sentence(3, 10),
                    'academic_id' => $faker->uuid,
                    'reviewer_id' => $faker->uuid,
                    'published_at' => (new DateTimeImmutable())->format(DATE_ATOM),
                    'created_at' => (new DateTimeImmutable())->format(DATE_ATOM),
                    'updated_at' => (new DateTimeImmutable())->format(DATE_ATOM),
                ],
            ],
        ];
    }
}
