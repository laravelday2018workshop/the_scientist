<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Article\Mapper\HydrateArticle;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Article\Article;
use App\Integration\Article\Mapper\Hydrator\DefaultHydrateArticle;
use DateTimeImmutable;
use Faker\Generator as Faker;
use Tests\TestCase;

/**
 * @covers \App\Integration\Article\Mapper\Hydrator\DefaultHydrateArticle
 */
final class DefaultHydrateArticleTest extends TestCase
{
    /**
     * @test
     * @dataProvider databaseRecordDataProvider
     */
    public function should_return_an_article(array $rawArticle): void
    {
        $mapper = new DefaultHydrateArticle();
        $article = $mapper->__invoke($rawArticle);
        $this->assertSame($rawArticle['id'], (string) $article->id());
        $this->assertSame($rawArticle['title'], (string) $article->title());
        $this->assertSame($rawArticle['body'], (string) $article->body());
        $this->assertSame($rawArticle['academic_id'], (string) $article->academicRegistrationNumber());
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
        $mapper = new DefaultHydrateArticle();
        $article = $mapper->__invoke($rawArticle);
        $this->assertSame($rawArticle['id'], (string) $article->id());
        $this->assertSame($rawArticle['title'], (string) $article->title());
        $this->assertSame($rawArticle['body'], (string) $article->body());
        $this->assertSame($rawArticle['academic_id'], (string) $article->academicRegistrationNumber());
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
                    'academic_id' => (string) $this->factoryFaker->instance(AcademicRegistrationNumber::class),
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
                    'academic_id' => (string) $this->factoryFaker->instance(AcademicRegistrationNumber::class),
                    'reviewer_id' => $faker->uuid,
                    'published_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
                    'created_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
                    'updated_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
                ],
            ],
        ];
    }
}
