<?php

declare(strict_types=1);

namespace Tests\Feature;

use Acme\Article\Article;
use Acme\Article\Repository\ArticleRepository;
use App\Integration\Article\Repository\InMemoryArticleRepository;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\CreateArticleController
 * @covers \App\Http\Requests\CreateArticleRequest
 */
class CreateArticleTest extends TestCase
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
    public function should_create_an_article(): void
    {
        /** @var Article $article */
        $article = $this->factoryFaker->instance(Article::class);

        $response = $this->post('articles', [
            'title' => (string) $article->title(),
            'body' => (string) $article->body(),
            'academic_id' => (string) $article->academicID(),
            'reviewer_id' => (string) $article->reviewerID(),
        ]);
        $articleCollection = $this->repository->list()->toArray();
        $storedArticle = \array_shift($articleCollection);
        $response->assertStatus(201);
        $response->assertJson(
            [
                'id' => (string) $storedArticle->id(),
                'title' => (string) $storedArticle->title(),
                'body' => (string) $storedArticle->body(),
                'reviewer_id' => (string) $storedArticle->reviewerID(),
                'academic_id' => (string) $storedArticle->academicID(),
                'published_at' => null,
                'created_at' => $storedArticle->creationDate()->format('Y-m-d H:i:s'),
                'updated_at' => null,
            ]
        );
    }

    /**
     * @test
     */
    public function should_fail_due_to_empty_body(): void
    {
        $response = $this->post('articles');
        $response->assertStatus(422);
        $response->assertJson([
            'title' => ['The title field is required.'],
            'body' => ['The body field is required.'],
            'reviewer_id' => ['The reviewer id field is required.'],
            'academic_id' => ['The academic id field is required.'],
        ]);
    }

    /**
     * @test
     */
    public function should_fail_due_to_invalid_values(): void
    {
        $response = $this->post('articles', [
            'title' => 'x',
            'reviewer_id' => 'x',
            'academic_id' => 'x',
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'title' => [
                'The given value "x" is not valid to create a Title. Min length is 5 and max length is 100',
            ],
            'reviewer_id' => [
                'The given value is not valid. Given "x"',
            ],
            'academic_id' => [
                'The given value is not valid. Given "x"',
            ],
        ]);
    }
}
