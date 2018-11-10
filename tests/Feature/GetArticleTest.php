<?php

declare(strict_types=1);

namespace Tests\Feature;

use Acme\Article\Article;
use Acme\Article\Repository\ArticleRepository;
use App\Integration\Article\Repository\InMemoryArticleRepository;
use Tests\TestCase;

class GetArticleTest extends TestCase
{
    /** @var ArticleRepository */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemoryArticleRepository();
        $this->app->instance(ArticleRepository::class, $this->repository);
    }

    /**
     * @test
     */
    public function should_get_an_article(): void
    {
        /** @var Article $article */
        $article = $this->factoryFaker->instance(Article::class);

        $this->repository->add($article);

        $response = $this->get("articles/{$article->id()}");

        $response->assertStatus(200);
        $response->assertJson(
            [
                'id' => (string) $article->id(),
                'title' => (string) $article->title(),
                'body' => (string) $article->body(),
                'reviewer_id' => (string) $article->reviewerID(),
                'academic_id' => (string) $article->academicID(),
                'published_at' => $article->publishDate()->format('Y-m-d H:i:s'),
                'created_at' => $article->creationDate()->format('Y-m-d H:i:s'),
                'updated_at' => $article->lastUpdateDate()->format('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * @test
     */
    public function should_not_found_an_article(): void
    {
        /** @var Article $article */
        $article = $this->factoryFaker->instance(Article::class);
        $response = $this->get("articles/{$article->id()}");
        $response->assertStatus(404);
        $response->assertJson(['message' => "Article with ID: \"{$article->id()}\" was not found"]);
    }

    /**
     * @test
     */
    public function should_throw_invalid_uuid_exception(): void
    {
        $badUUID = 'bad-uuid';
        $response = $this->get("articles/$badUUID");

        $response->assertStatus(404);
        $response->assertJson(['id' => ["The given value is not valid. Given \"$badUUID\""]]);
    }
}
