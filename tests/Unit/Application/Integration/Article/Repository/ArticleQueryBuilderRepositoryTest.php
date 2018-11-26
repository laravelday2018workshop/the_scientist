<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Article\Repository;

use Acme\Article\Article;
use Acme\Article\ArticleCollection;
use Acme\Article\Repository\ArticleRepository;
use Acme\Article\Repository\Exception\ArticleNotFound;
use Acme\Article\Repository\Exception\ImpossibleToRetrieveArticles;
use Acme\Article\Repository\Exception\ImpossibleToSaveArticle;
use Acme\Article\ValueObject\ArticleID;
use App\Integration\Article\Mapper\Hydrator\HydrateArticle;
use App\Integration\Article\Mapper\Serializer\SerializeArticle;
use App\Integration\Article\Repository\ArticleQueryBuilderRepository;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;
use stdClass;
use Tests\TestCase;

/**
 * @covers \App\Integration\Article\Repository\ArticleQueryBuilderRepository
 */
final class ArticleQueryBuilderRepositoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider getByIdFoundDataProvider
     */
    public function should_find_an_article_in_the_database(stdClass $rawArticle, Article $article, ArticleID $articleID): void
    {
        DB::shouldReceive('table')->with('articles')->once()->andReturnSelf();
        DB::shouldReceive('select')->with()->once()->andReturnSelf();
        DB::shouldReceive('where')->with('id', '=', (string) $articleID)->once()->andReturnSelf();
        DB::shouldReceive('first')->with()->once()->andReturn($rawArticle);
        $db = $this->app->get('db');

        $fromArrayMapperProphecy = $this->prophesize(HydrateArticle::class);
        $fromArrayMapperProphecy->__invoke((array) $rawArticle)->shouldBeCalledOnce()->willReturn($article);
        $fromArticleMapper = $fromArrayMapperProphecy->reveal();
        $loggerProphecy = $this->prophesize(LoggerInterface::class);
        $logger = $loggerProphecy->reveal();

        $repository = new ArticleQueryBuilderRepository(
            $db,
            $this->prophesize(SerializeArticle::class)->reveal(),
            $fromArticleMapper,
            $logger
        );
        $retrievedArticle = $repository->getById($articleID);
        $this->assertSame($article, $retrievedArticle);
    }

    public function getByIdFoundDataProvider(): array
    {
        return [
            [new stdClass(), $this->factoryFaker->instance(Article::class), $this->factoryFaker->instance(ArticleID::class)],
        ];
    }

    /**
     * @test
     * @dataProvider getByIdConnectionExceptionDataProvider
     */
    public function should_throw_an_connection_error_on_get_article(ArticleID $articleID, QueryException $exception): void
    {
        $this->expectException(ImpossibleToRetrieveArticles::class);
        DB::shouldReceive('table')->with('articles')->andThrow($exception);
        $db = $this->app->get('db');

        $loggerProphecy = $this->prophesize(LoggerInterface::class);
        $loggerProphecy->error('database failure', ['exception' => $exception, 'article_id' => (string) $articleID]);
        $logger = $loggerProphecy->reveal();

        $repository = new ArticleQueryBuilderRepository(
            $db,
            $this->prophesize(SerializeArticle::class)->reveal(),
            $this->prophesize(HydrateArticle::class)->reveal(),
            $logger
        );
        $repository->getById($articleID);
    }

    public function getByIdConnectionExceptionDataProvider(): array
    {
        return [
            [$this->factoryFaker->instance(ArticleID::class), new QueryException('', [], new Exception())],
        ];
    }

    /**
     * @test
     * @dataProvider getByIdNotFoundExceptionDataProvider
     */
    public function should_throw_a_not_found_exception(ArticleID $articleID): void
    {
        $this->expectException(ArticleNotFound::class);
        DB::shouldReceive('table')->with('articles')->once()->andReturnSelf();
        DB::shouldReceive('select')->with()->once()->andReturnSelf();
        DB::shouldReceive('where')->with('id', '=', (string) $articleID)->once()->andReturnSelf();
        DB::shouldReceive('first')->with()->once()->andReturn(null);
        $db = $this->app->get('db');

        $loggerProphecy = $this->prophesize(LoggerInterface::class);
        $loggerProphecy->warning('article not found', ['article_id' => (string) $articleID]);
        $logger = $loggerProphecy->reveal();

        $repository = new ArticleQueryBuilderRepository(
            $db,
            $this->prophesize(SerializeArticle::class)->reveal(),
            $this->prophesize(HydrateArticle::class)->reveal(),
            $logger
        );
        $repository->getById($articleID);
    }

    public function getByIdNotFoundExceptionDataProvider(): array
    {
        return [
            [$this->factoryFaker->instance(ArticleID::class)],
        ];
    }

    /**
     * @test
     * @dataProvider listDataProvider
     */
    public function should_list_articles_from_the_database(Collection $rawArticlesColection, ArticleCollection $collection, $skip, $take, $expectedTake): void
    {
        DB::shouldReceive('table')->with('articles')->once()->andReturnSelf();
        DB::shouldReceive('select')->with()->once()->andReturnSelf();
        DB::shouldReceive('skip')->with($skip)->once()->andReturnSelf();
        DB::shouldReceive('take')->with($expectedTake)->once()->andReturnSelf();
        DB::shouldReceive('get')->with()->once()->andReturn($rawArticlesColection);
        $db = $this->app->get('db');

        $rawArticlesArray = $rawArticlesColection->toArray();
        $fromArticleMapperProphecy = $this->prophesize(HydrateArticle::class);
        foreach ($collection->toArray() as $index => $article) {
            $fromArticleMapperProphecy->__invoke((array) $rawArticlesArray[$index])->shouldBeCalledOnce()->willReturn($article);
        }
        $fromArticleMapper = $fromArticleMapperProphecy->reveal();
        $loggerProphecy = $this->prophesize(LoggerInterface::class);
        $logger = $loggerProphecy->reveal();

        $repository = new ArticleQueryBuilderRepository(
            $db,
            $this->prophesize(SerializeArticle::class)->reveal(),
            $fromArticleMapper,
            $logger
        );
        $retrievedCollection = $repository->list($skip, $take);
        $this->assertSame($collection->toArray(), $retrievedCollection->toArray());
    }

    public function listDataProvider(): array
    {
        $stdOne = new stdClass();
        $stdOne->a = 1;
        $stdTwo = new stdClass();
        $stdTwo->a = 2;
        $stdThree = new stdClass();
        $stdThree->a = 3;

        return [
            [
                $rawArticles = new Collection([$stdOne]),
                $collection = new ArticleCollection(
                    $this->factoryFaker->instance(Article::class)
                ),
                $skip = 0,
                $take = 10,
                $expectedTake = 10,
            ],
            [
                $rawArticles = new Collection([$stdTwo, $stdThree]),
                $collection = new ArticleCollection(
                    $this->factoryFaker->instance(Article::class),
                    $this->factoryFaker->instance(Article::class)
                ),
                $skip = 10,
                $take = 100,
                $expectedTake = ArticleRepository::MAX_SIZE,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider listConnectionErrorDataProvider
     */
    public function should_throw_an_connection_error_on_list_articles(QueryException $exception, $skip, $take): void
    {
        $this->expectException(ImpossibleToRetrieveArticles::class);
        DB::shouldReceive('table')->with('articles')->once()->andThrow($exception);
        $db = $this->app->get('db');

        $loggerProphecy = $this->prophesize(LoggerInterface::class);
        $loggerProphecy->warning('database failure', ['exception' => $exception]);
        $logger = $loggerProphecy->reveal();

        $repository = new ArticleQueryBuilderRepository(
            $db,
            $this->prophesize(SerializeArticle::class)->reveal(),
            $this->prophesize(HydrateArticle::class)->reveal(),
            $logger
        );
        $repository->list($skip, $take);
    }

    public function listConnectionErrorDataProvider(): array
    {
        return [
            [new QueryException('', [], new Exception()), 0, 10],
            [new QueryException('', [], new Exception()), 10, 10],
        ];
    }

    /**
     * @test
     * @dataProvider addDataProvider
     */
    public function should_store_an_article_in_the_database(Article $article, array $rawArticle): void
    {
        DB::shouldReceive('table')->with('articles')->once()->andReturnSelf();
        DB::shouldReceive('insert')->with($rawArticle)->once()->andReturn(1);
        $db = $this->app->get('db');

        $fromArticleMapperProphecy = $this->prophesize(SerializeArticle::class);
        $fromArticleMapperProphecy->__invoke($article)->shouldBeCalledOnce()->willReturn($rawArticle);
        $fromArticleMapper = $fromArticleMapperProphecy->reveal();
        $loggerProphecy = $this->prophesize(LoggerInterface::class);
        $logger = $loggerProphecy->reveal();

        $repository = new ArticleQueryBuilderRepository(
            $db,
            $fromArticleMapper,
            $this->prophesize(HydrateArticle::class)->reveal(),
            $logger
        );
        $repository->add($article);
    }

    public function addDataProvider(): array
    {
        return [
            [$this->factoryFaker->instance(Article::class), ['id' => 1]],
        ];
    }

    /**
     * @test
     * @dataProvider addConnectionErrorDataProvider
     */
    public function should_throw_an_connection_error_on_store_article(Article $article, array $rawArticle, QueryException $exception): void
    {
        $this->expectException(ImpossibleToSaveArticle::class);
        DB::shouldReceive('table')->with('articles')->once()->andThrow($exception);
        $db = $this->app->get('db');

        $fromArticleMapperProphecy = $this->prophesize(SerializeArticle::class);
        $fromArticleMapperProphecy->__invoke($article)->shouldBeCalledOnce()->willReturn([]);
        $fromArticleMapper = $fromArticleMapperProphecy->reveal();
        $loggerProphecy = $this->prophesize(LoggerInterface::class);
        $loggerProphecy->error('database failure', ['exception' => $exception, 'article' => $rawArticle]);
        $logger = $loggerProphecy->reveal();

        $repository = new ArticleQueryBuilderRepository(
            $db,
            $fromArticleMapper,
            $this->prophesize(HydrateArticle::class)->reveal(),
            $logger
        );
        $repository->add($article);
    }

    public function addConnectionErrorDataProvider(): array
    {
        return [
            [$this->factoryFaker->instance(Article::class), [], new QueryException('', [], new Exception())],
        ];
    }

    /**
     * @test
     * @dataProvider addDataProvider
     */
    public function should_thow_an_error_when_article_not_saved(Article $article, array $rawArticle): void
    {
        $this->expectException(ImpossibleToSaveArticle::class);
        DB::shouldReceive('table')->with('articles')->once()->andReturnSelf();
        DB::shouldReceive('insert')->with($rawArticle)->once()->andReturn(false);
        $db = $this->app->get('db');

        $fromArticleMapperProphecy = $this->prophesize(SerializeArticle::class);
        $fromArticleMapperProphecy->__invoke($article)->shouldBeCalledOnce()->willReturn($rawArticle);
        $fromArticleMapper = $fromArticleMapperProphecy->reveal();
        $loggerProphecy = $this->prophesize(LoggerInterface::class);
        $loggerProphecy->warning('impossible to add article', ['article' => $rawArticle]);
        $logger = $loggerProphecy->reveal();

        $repository = new ArticleQueryBuilderRepository(
            $db,
            $fromArticleMapper,
            $this->prophesize(HydrateArticle::class)->reveal(),
            $logger
        );
        $repository->add($article);
    }

    /**
     * @test
     * @dataProvider addDataProvider
     */
    public function should_update_an_article_in_the_database(Article $article, array $rawArticle): void
    {
        DB::shouldReceive('table')->with('articles')->once()->andReturnSelf();
        DB::shouldReceive('where')->with('id', $rawArticle['id'])->once()->andReturnSelf();
        DB::shouldReceive('update')->with($rawArticle)->once()->andReturn(1);
        $db = $this->app->get('db');

        $fromArticleMapperProphecy = $this->prophesize(SerializeArticle::class);
        $fromArticleMapperProphecy->__invoke($article)->shouldBeCalledOnce()->willReturn($rawArticle);
        $fromArticleMapper = $fromArticleMapperProphecy->reveal();
        $loggerProphecy = $this->prophesize(LoggerInterface::class);
        $logger = $loggerProphecy->reveal();

        $repository = new ArticleQueryBuilderRepository(
            $db,
            $fromArticleMapper,
            $this->prophesize(HydrateArticle::class)->reveal(),
            $logger
        );
        $repository->update($article);
    }

    /**
     * @test
     * @dataProvider addConnectionErrorDataProvider
     */
    public function should_throw_an_connection_error_on_update_article(Article $article, array $rawArticle, QueryException $exception): void
    {
        $this->expectException(ImpossibleToSaveArticle::class);
        DB::shouldReceive('table')->with('articles')->once()->andThrow($exception);
        $db = $this->app->get('db');

        $fromArticleMapperProphecy = $this->prophesize(SerializeArticle::class);
        $fromArticleMapperProphecy->__invoke($article)->shouldBeCalledOnce()->willReturn([]);
        $fromArticleMapper = $fromArticleMapperProphecy->reveal();
        $loggerProphecy = $this->prophesize(LoggerInterface::class);
        $loggerProphecy->error('database failure', ['exception' => $exception, 'article' => $rawArticle]);
        $logger = $loggerProphecy->reveal();

        $repository = new ArticleQueryBuilderRepository(
            $db,
            $fromArticleMapper,
            $this->prophesize(HydrateArticle::class)->reveal(),
            $logger
        );
        $repository->update($article);
    }

    /**
     * @test
     * @dataProvider addDataProvider
     */
    public function should_thow_an_error_when_article_not_updated(Article $article, array $rawArticle): void
    {
        $this->expectException(ImpossibleToSaveArticle::class);
        DB::shouldReceive('table')->with('articles')->once()->andReturnSelf();
        DB::shouldReceive('where')->with('id', $rawArticle['id'])->once()->andReturnSelf();
        DB::shouldReceive('update')->with($rawArticle)->once()->andReturn(0);
        $db = $this->app->get('db');

        $fromArticleMapperProphecy = $this->prophesize(SerializeArticle::class);
        $fromArticleMapperProphecy->__invoke($article)->shouldBeCalledOnce()->willReturn($rawArticle);
        $fromArticleMapper = $fromArticleMapperProphecy->reveal();
        $loggerProphecy = $this->prophesize(LoggerInterface::class);
        $loggerProphecy->warning('impossible to add article', ['article' => $rawArticle]);
        $logger = $loggerProphecy->reveal();

        $repository = new ArticleQueryBuilderRepository(
            $db,
            $fromArticleMapper,
            $this->prophesize(HydrateArticle::class)->reveal(),
            $logger
        );
        $repository->update($article);
    }
}
