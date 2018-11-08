<?php

namespace Tests\Feature;

use Acme\Article\Article;
use Acme\Article\Repository\ArticleRepository;
use Acme\Article\Repository\Exception\ArticleNotFound;
use App\Integration\Article\Repository\InMemoryArticleRepository;
use Tests\TestCase;
use Faker\Generator as Faker;

class GetArticleTest extends TestCase
{
    /**
     * @test
     */
    public function should_get_an_article()
    {
        /** @var Faker $faker */
        //        $faker = $this->factoryFaker->instance(Faker::class);
        //        $id    = $faker->uuid;

        /** @var Article $article */
        $article = $this->factoryFaker->instance(Article::class);

        $repository = new InMemoryArticleRepository();
        $repository->add($article);

        $this->app->instance(ArticleRepository::class, $repository);

        $response = $this->get('api/articles/' . $article->id());

        $response->assertJson(
            [
                'id'    => (string)$article->id(),
                'title' => (string)$article->title(),
                'body'  => (string)$article->body(),
            ]
        );
    }
}