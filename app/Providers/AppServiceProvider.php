<?php

declare(strict_types=1);

namespace App\Providers;

use Acme\Academic\Repository\AcademicRepository;
use Acme\Article\Repository\ArticleRepository;
use App\Integration\Academic\Mapper\AcademicMapper;
use App\Integration\Academic\Mapper\DatabaseAcademicMapper;
use App\Integration\Academic\Repository\AcademicQueryBuilderRepository;
use App\Integration\Article\Mapper\ArticleMapper;
use App\Integration\Article\Mapper\DatabaseArticleMapper;
use App\Integration\Article\Repository\ArticleQueryBuilderRepository;
use App\Integration\Common\Query\CrudFacade;
use App\Integration\Common\Query\CrudFacadeDefaultBuilder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerArticleRepository();
        $this->registerAcademicRepository();
    }

    private function registerArticleRepository(): void
    {
        $this->app->bind(ArticleRepository::class, ArticleQueryBuilderRepository::class);

        $this->app->when(ArticleRepository::class)
                  ->needs(ArticleMapper::class)
                  ->give(DatabaseArticleMapper::class);
    }

    private function registerAcademicRepository(): void
    {
        $this->app->bind(AcademicRepository::class, AcademicQueryBuilderRepository::class);

        $this->app->when(AcademicRepository::class)
                  ->needs(AcademicMapper::class)
                  ->give(DatabaseAcademicMapper::class);

        $this->app->when(AcademicRepository::class)
                  ->needs(CrudFacade::class)
                  ->give(function () {
                      return $this->app->get(CrudFacadeDefaultBuilder::class)
                                       ->build(AcademicQueryBuilderRepository::TABLE_NAME);
                  });
    }
}
