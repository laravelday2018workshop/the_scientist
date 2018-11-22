<?php

namespace App\Integration\Article;

use Acme\Article\Repository\ArticleRepository;
use App\Integration\Article\Mapper\Hydrator\DefaultHydrateArticle;
use App\Integration\Article\Mapper\Hydrator\HydrateArticle;
use App\Integration\Article\Mapper\Serializer\DefaultSerializeArticle;
use App\Integration\Article\Mapper\Serializer\SerializeArticle;
use App\Integration\Article\Repository\ArticleQueryBuilderRepository;
use App\Integration\Common\Query\CrudFacade;
use App\Integration\Common\Query\CrudFacadeDefaultBuilder;
use Illuminate\Support\ServiceProvider;

class ArticleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ArticleRepository::class, ArticleQueryBuilderRepository::class);
        $this->app->when(ArticleRepository::class)->needs(CrudFacade::class)->give(function () {
            return $this->app->get(CrudFacadeDefaultBuilder::class)->build(ArticleQueryBuilderRepository::TABLE_NAME);
        });


        $this->app->bind(SerializeArticle::class, DefaultSerializeArticle::class);
        $this->app->bind(HydrateArticle::class, DefaultHydrateArticle::class);
    }
}
