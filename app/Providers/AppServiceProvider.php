<?php

declare(strict_types=1);

namespace App\Providers;

use Acme\Academic\Repository\AcademicRepository;
use Acme\Article\Repository\ArticleRepository;
use App\Integration\Academic\Mapper\DatabaseAcademicMapper;
use App\Integration\Academic\Mapper\FromAcademicPartialMapping;
use App\Integration\Academic\Repository\AcademicQueryBuilderRepository;
use App\Integration\Article\Mapper\DatabaseArticleMapper;
use App\Integration\Article\Mapper\FromArticlePartialMapping;
use App\Integration\Article\Repository\ArticleQueryBuilderRepository;
use App\Integration\Common\Query\CrudFacade;
use App\Integration\Common\Query\CrudFacadeDefault;
use Illuminate\Log\Logger;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(ArticleRepository::class, function (): ArticleQueryBuilderRepository {
            return new ArticleQueryBuilderRepository(
                $this->app->get('db'),
                new DatabaseArticleMapper(new FromArticlePartialMapping()),
                $this->app->get(Logger::class)
            );
        });

        $this->app->bind(AcademicRepository::class, function (): AcademicQueryBuilderRepository {
            return new AcademicQueryBuilderRepository(
                $this->app->get('db'),
                $this->app->get(CrudFacade::class),
                new DatabaseAcademicMapper(new FromAcademicPartialMapping()),
                $this->app->get(Logger::class)
            );
        });

        $this->app->bind(CrudFacade::class, CrudFacadeDefault::class);
    }
}
