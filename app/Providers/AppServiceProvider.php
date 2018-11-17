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
use App\Integration\Common\Query\CrudFacadeDefault;
use App\Integration\Common\Query\QueryBuilderDelete;
use App\Integration\Common\Query\QueryBuilderInsert;
use App\Integration\Common\Query\QueryBuilderSelectAll;
use App\Integration\Common\Query\QueryBuilderSelectById;
use App\Integration\Common\Query\QueryBuilderUpdate;
use Illuminate\Database\DatabaseManager;
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
        $this->app->bind(ArticleRepository::class,
            function (): ArticleQueryBuilderRepository {
                return new ArticleQueryBuilderRepository(
                    $this->app->get('db'),
                    new DatabaseArticleMapper(new FromArticlePartialMapping()),
                    $this->app->get(Logger::class)
                );
            });

        $this->app->bind(AcademicRepository::class,
            function (): AcademicQueryBuilderRepository {
                /** @var DatabaseManager $databaseManager */
                $databaseManager = $this->app->get('db');

                return new AcademicQueryBuilderRepository(
                    $databaseManager,
                    $this->buildCrudFacade($databaseManager, AcademicQueryBuilderRepository::TABLE_NAME),
                    new DatabaseAcademicMapper(new FromAcademicPartialMapping()),
                    $this->app->get(Logger::class)
                );
            });
    }

    private function buildCrudFacade(DatabaseManager $databaseManager, string $table)
    {
        $builder = $databaseManager->table($table);

        return new CrudFacadeDefault(
            new QueryBuilderSelectById($builder),
            new QueryBuilderSelectAll($builder),
            new QueryBuilderInsert($builder),
            new QueryBuilderUpdate($builder),
            new QueryBuilderDelete($builder)
        );
    }
}
