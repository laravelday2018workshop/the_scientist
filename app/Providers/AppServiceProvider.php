<?php

declare(strict_types=1);

namespace App\Providers;

use Acme\Article\Repository\ArticleRepository;
use App\Integration\Article\Mapper\DatabaseArticleMapper;
use App\Integration\Article\Mapper\FromArticlePartialMapping;
use App\Integration\Article\Repository\ArticleQueryBuilderRepository;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;
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
                $this->app->get(DB::class),
                new DatabaseArticleMapper(new FromArticlePartialMapping()),
                $this->app->get(Logger::class)
            );
        });
    }
}
