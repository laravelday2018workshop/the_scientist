<?php

declare(strict_types=1);

namespace Tests\Feature;

use Acme\Academic\Academic;
use Acme\Academic\Repository\AcademicRepository;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Article\Article;
use Acme\Article\Repository\ArticleRepository;
use App\Integration\Academic\Repository\InMemoryAcademicRepository;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\WriteArticleController
 * @covers \App\Http\Requests\WriteArticleRequest
 */
class WriteArticleTest extends TestCase
{
    /** @var ArticleRepository */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new InMemoryAcademicRepository();
        $this->app->instance(AcademicRepository::class, $this->repository);
    }

    /**
     * @test
     */
    public function should_create_an_article(): void
    {
        /** @var Academic $academic */
        $academic = $this->factoryFaker->instance(Academic::class);
        /** @var Article $article */
        $article = $this->factoryFaker->instance(Article::class);

        $this->repository->add($academic);
        $initialArticlesNumber = \count($academic->articles()->toArray());

        $response = $this->post("academics/{$academic->registrationNumber()}/articles", [
            'title' => (string) $article->title(),
            'body' => (string) $article->body(),
        ]);

        $articleCollection = $this->repository->list()->toArray();
        /** @var Academic $storedAcademic */
        $storedAcademic = \array_shift($articleCollection);
        $response->assertStatus(201);
        $jsonResponse = \json_decode($response->content(), true);
        $this->assertCount($initialArticlesNumber + 1, $jsonResponse['articles']);
        $response->assertJson(
            [
                'id' => (string) $academic->registrationNumber(),
                'articles' => [[
                    'id' => (string) $storedAcademic->articles()->toArray()[0]->id(),
                    'title' => (string) $storedAcademic->articles()->toArray()[0]->title(),
                    'body' => (string) $storedAcademic->articles()->toArray()[0]->body(),
                    'reviewer_id' => (string) $storedAcademic->articles()->toArray()[0]->reviewerID(),
                    'academic_id' => (string) $storedAcademic->articles()->toArray()[0]->academicRegistrationNumber(),
                    'published_at' => null,
                    'created_at' => $storedAcademic->articles()->toArray()[0]->creationDate()->format('Y-m-d H:i:s'),
                    'updated_at' => null,
                ]],
            ]
        );
    }

    /**
     * @test
     */
    public function should_fail_due_to_empty_body(): void
    {
        $registrationNumber = $this->factoryFaker->instance(AcademicRegistrationNumber::class);
        $response = $this->post("academics/{$registrationNumber}/articles");
        $response->assertStatus(422);
        $response->assertJson([
            'title' => ['The title field is required.'],
            'body' => ['The body field is required.'],
        ]);
    }

    /**
     * @test
     */
    public function should_fail_due_to_invalid_values(): void
    {
        $response = $this->post('academics/x/articles', [
            'title' => 'x',
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'title' => [
                'The given value "x" is not valid to create a Title. Min length is 5 and max length is 100',
            ],
            'id' => [
                'The given value is not valid. Given "x"',
            ],
        ]);
    }
}
