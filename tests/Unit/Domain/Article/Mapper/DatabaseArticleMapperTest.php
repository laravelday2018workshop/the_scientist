<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Article\Mapper;

use Acme\Article\Article;
use App\Integration\Article\Mapper\DatabaseArticleMapper;
use App\Integration\Article\Mapper\FromArticlePartialMapping;
use DateTimeImmutable;
use Error;
use Faker\Generator as Faker;
use Tests\TestCase;

/**
 * @covers \App\Integration\Article\Mapper\DatabaseArticleMapper
 */
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
        $mapper = new DatabaseArticleMapper(new FromArticlePartialMapping());
        $article = $mapper->fromArray($rawArticle);
        $this->assertSame($rawArticle['id'], (string) $article->id());
        $this->assertSame($rawArticle['title'], (string) $article->title());
        $this->assertSame($rawArticle['body'], (string) $article->body());
        $this->assertSame($rawArticle['academic_id'], (string) $article->academicID());
        $this->assertSame($rawArticle['reviewer_id'], (string) $article->reviewerID());
        $this->assertNull($article->publishDate());
        $this->assertSame($rawArticle['created_at'], $article->creationDate()->format('Y-m-d H:i:s'));
        $this->assertNull($article->lastUpdateDate());
    }

    /**
     * @test
     * @dataProvider databaseRecordWithDatesDataProvider
     */
    public function should_return_an_article_withDates(array $rawArticle): void
    {
        $mapper = new DatabaseArticleMapper(new FromArticlePartialMapping());
        $article = $mapper->fromArray($rawArticle);
        $this->assertSame($rawArticle['id'], (string) $article->id());
        $this->assertSame($rawArticle['title'], (string) $article->title());
        $this->assertSame($rawArticle['body'], (string) $article->body());
        $this->assertSame($rawArticle['academic_id'], (string) $article->academicID());
        $this->assertSame($rawArticle['reviewer_id'], (string) $article->reviewerID());
        $this->assertSame($rawArticle['published_at'], $article->publishDate()->format('Y-m-d H:i:s'));
        $this->assertSame($rawArticle['created_at'], $article->creationDate()->format('Y-m-d H:i:s'));
        $this->assertSame($rawArticle['updated_at'], $article->lastUpdateDate()->format('Y-m-d H:i:s'));
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
                    'created_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
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
                    'published_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
                    'created_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
                    'updated_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
                ],
            ],
        ];
    }
}
