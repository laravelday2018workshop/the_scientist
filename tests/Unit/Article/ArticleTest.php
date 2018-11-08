<?php

declare(strict_types=1);

namespace Tests\Unit\Article;

use Acme\Academic\ValueObject\AcademicID;
use Acme\Article\Article;
use Acme\Article\ValueObject\ArticleID;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use Acme\Reviewer\ValueObject\ReviewerID;
use DateTimeImmutable;
use Tests\TestCase;

/**
 * @covers \Acme\Article\Article
 */
final class ArticleTest extends TestCase
{
    /**
     * @test
     * @dataProvider valueObjectDataProvider
     */
    public function should_create_an_article(
        ArticleID $articleID,
        Title $title,
        Body $body,
        AcademicID $academicID,
        ReviewerID $reviewerID,
        ?DateTimeImmutable $publishDate,
        DateTimeImmutable $creationDate,
        ?DateTimeImmutable $lastUpdateDate
    ): void {
        $article = new Article(
            $articleID,
            $title,
            $body,
            $academicID,
            $reviewerID,
            $publishDate,
            $creationDate,
            $lastUpdateDate
        );
        $this->assertSame($articleID, $article->id());
        $this->assertSame($title, $article->title());
        $this->assertSame($body, $article->body());
        $this->assertSame($academicID, $article->academicID());
        $this->assertSame($reviewerID, $article->reviewerID());
        $this->assertSame($publishDate, $article->publishDate());
        $this->assertSame($creationDate, $article->creationDate());
        $this->assertSame($lastUpdateDate, $article->lastUpdateDate());
    }

    public function valueObjectDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(ArticleID::class),
                $this->factoryFaker->instance(Title::class),
                $this->factoryFaker->instance(Body::class),
                $this->factoryFaker->instance(AcademicID::class),
                $this->factoryFaker->instance(ReviewerID::class),
                new DateTimeImmutable(),
                new DateTimeImmutable(),
                new DateTimeImmutable(),
            ], [
                $this->factoryFaker->instance(ArticleID::class),
                $this->factoryFaker->instance(Title::class),
                $this->factoryFaker->instance(Body::class),
                $this->factoryFaker->instance(AcademicID::class),
                $this->factoryFaker->instance(ReviewerID::class),
                null,
                new DateTimeImmutable(),
                null,
            ],
        ];
    }
}
