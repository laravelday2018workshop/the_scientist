<?php

declare(strict_types=1);

namespace App\Integration\Academic;

use Acme\Academic\Repository\AcademicRepository;
use App\Integration\Academic\Mapper\FromArray\DefaultHydrateAcademic;
use App\Integration\Academic\Mapper\FromArray\HydrateAcademic;
use App\Integration\Academic\Mapper\Serializer\DefaultSerializeAcademic;
use App\Integration\Academic\Mapper\Serializer\SerializeAcademic;
use App\Integration\Academic\Repository\AcademicQueryBuilderRepository;
use App\Integration\Article\Mapper\Hydrator\DefaultHydrateArticle;
use App\Integration\Article\Mapper\Serializer\DefaultSerializeArticle;
use Illuminate\Log\Logger;
use Illuminate\Support\ServiceProvider;

class AcademicServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot()
    {
    }

    /**
     * Register services.
     */
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

        $this->app->bind(SerializeAcademic::class, DefaultSerializeAcademic::class);
        $this->app->bind(HydrateAcademic::class, DefaultHydrateAcademic::class);
    }
}
