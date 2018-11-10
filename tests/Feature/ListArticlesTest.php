<?php

declare(strict_types=1);

namespace Tests\Feature;

use Acme\Article\Article;
use Acme\Article\Repository\ArticleRepository;
use App\Integration\Article\Repository\InMemoryArticleRepository;
use Tests\TestCase;

class ListArticlesTest extends TestCase
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
    public function should_get_the_list_of_articles(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $article = $this->factoryFaker->instance(Article::class);
            $this->repository->add($article);
        }
        $response = $this->get('articles');
        $response->assertStatus(200);
        $response->assertJsonCount(10);
        $response->assertJsonStructure(
           [['id', 'title', 'body', 'reviewer_id', 'academic_id', 'published_at', 'created_at', 'updated_at']]
        );
    }

    /**
     * @test
     * @dataProvider paginatedRequestDataProvider
     */
    public function should_properly_skip_and_take_the_list_of_articles(int $numberOfArticles, int $take, int $skip, int $expectedNumber): void
    {
        for ($i = 0; $i < $numberOfArticles; ++$i) {
            $article = $this->factoryFaker->instance(Article::class);
            $this->repository->add($article);
        }
        $response = $this->get("articles?take=$take&skip=$skip");
        $response->assertStatus(200);
        $response->assertJsonCount($expectedNumber);
        $response->assertJsonStructure(
            [['id', 'title', 'body', 'reviewer_id', 'academic_id', 'published_at', 'created_at', 'updated_at']]
        );
    }

    /**
     * @test
     */
    public function should_not_found_any_article(): void
    {
        $response = $this->get('articles');
        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    public function paginatedRequestDataProvider(): array
    {
        return [
            [$numberOfArticles = 10, $take = 5, $skip = 5, $expectedNumber = 5],
            [$numberOfArticles = 1, $take = 1, $skip = 0, $expectedNumber = 1],
            [$numberOfArticles = 30, $take = 30, $skip = 0, $expectedNumber = 20],
        ];
    }
}
