<?php

declare(strict_types=1);

namespace App\Providers;

use Acme\Academic\Repository\AcademicRepository;
use Acme\Article\Repository\ArticleRepository;
use App\Integration\Academic\Mapper\FromArray\DefaultHydrateAcademic;
use App\Integration\Academic\Mapper\FromArray\HydrateAcademic;
use App\Integration\Academic\Mapper\Serializer\DefaultSerializeAcademic;
use App\Integration\Academic\Mapper\Serializer\SerializeAcademic;
use App\Integration\Academic\Repository\AcademicQueryBuilderRepository;
use App\Integration\Article\Mapper\Hydrator\DefaultHydrateArticle;
use App\Integration\Article\Mapper\Hydrator\HydrateArticle;
use App\Integration\Article\Mapper\Serializer\DefaultSerializeArticle;
use App\Integration\Article\Mapper\Serializer\SerializeArticle;
use App\Integration\Article\Repository\ArticleQueryBuilderRepository;
use App\Integration\Common\Query\CrudFacade;
use App\Integration\Common\Query\CrudFacadeDefaultBuilder;
use Illuminate\Log\Logger;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(AcademicRepository::class, function (): AcademicQueryBuilderRepository {
            return new AcademicQueryBuilderRepository(
                $this->app->get('db'),
                new DefaultSerializeAcademic(new DefaultSerializeArticle()),
                new DefaultHydrateAcademic(new DefaultHydrateArticle()),
                $this->app->get(Logger::class)
            );
        });

        $this->app->bind(ArticleRepository::class, ArticleQueryBuilderRepository::class);
        $this->app->when(ArticleRepository::class)->needs(CrudFacade::class)->give(function () {
            return $this->app->get(CrudFacadeDefaultBuilder::class)->build(ArticleQueryBuilderRepository::TABLE_NAME);
        });

        $this->app->bind(SerializeAcademic::class, DefaultSerializeAcademic::class);
        $this->app->bind(HydrateAcademic::class, DefaultHydrateAcademic::class);
        $this->app->bind(SerializeArticle::class, DefaultSerializeArticle::class);
        $this->app->bind(HydrateArticle::class, DefaultHydrateArticle::class);
    }
}
