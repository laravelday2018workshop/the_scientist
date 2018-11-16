<?php

declare(strict_types=1);

namespace Tests\Feature;

use Acme\Article\Article;
use Acme\Article\Repository\ArticleRepository;
use Acme\Article\ValueObject\ArticleID;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use App\Integration\Article\Repository\InMemoryArticleRepository;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\UpdateArticleController
 * @covers \App\Http\Requests\UpdateArticleRequest
 */
class UpdateArticleTest extends TestCase
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
    public function should_update_an_article(): void
    {
        /** @var Article $article */
        $article = $this->factoryFaker->instance(Article::class);
        $newTitle = $this->factoryFaker->instance(Title::class);
        $newBody = $this->factoryFaker->instance(Body::class);
        $this->repository->add($article);
        $response = $this->patch("articles/{$article->id()}", [
            'title' => (string) $newTitle,
            'body' => (string) $newBody,
        ]);

        $response->assertStatus(200);
        $response->assertJson(
            [
                'id' => (string) $article->id(),
                'title' => (string) $article->title(),
                'body' => (string) $article->body(),
                'reviewer_id' => (string) $article->reviewerID(),
                'academic_id' => (string) $article->academicRegistrationNumber(),
                'created_at' => $article->creationDate()->format('Y-m-d H:i:s'),
            ]
        );
        $responseContent = \json_decode($response->getContent(), true);
        $this->assertNotNull($responseContent['updated_at']);
    }

    /**
     * @test
     */
    public function should_fail_due_to_empty_body(): void
    {
        $invalidUUID = 'x';
        $response = $this->patch("articles/$invalidUUID");
        $response->assertStatus(422);
        $response->assertJson([
            'id' => ['The given value is not valid. Given "x"'],
            'title' => ['The title field is required.'],
            'body' => ['The body field is required.'],
        ]);
    }

    /**
     * @test
     */
    public function should_fail_due_to_invalid_body(): void
    {
        $articleID = $this->factoryFaker->instance(ArticleID::class);
        $response = $this->patch("articles/$articleID", [
            'title' => 'k',
            'body' => '',
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'title' => ['The given value "k" is not valid to create a Title. Min length is 5 and max length is 100'],
            'body' => ['The body field is required.'],
        ]);
    }
}
