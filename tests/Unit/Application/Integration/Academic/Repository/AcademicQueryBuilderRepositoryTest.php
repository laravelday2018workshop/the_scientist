<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Academic\Repository;

use Acme\Academic\Academic;
use Acme\Academic\AcademicCollection;
use Acme\Academic\Repository\AcademicRepository;
use Acme\Academic\Repository\Exception\AcademicNotFound;
use Acme\Academic\Repository\Exception\ImpossibleToRetrieveAcademics;
use Acme\Academic\Repository\Exception\ImpossibleToSaveAcademic;
use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use App\Integration\Academic\Mapper\FromArray\HydrateAcademic;
use App\Integration\Academic\Mapper\Serializer\SerializeAcademic;
use App\Integration\Academic\Repository\AcademicQueryBuilderRepository;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use stdClass;
use Tests\TestCase;

/**
 * @covers \App\Integration\Academic\Repository\AcademicQueryBuilderRepository
 */
final class AcademicQueryBuilderRepositoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider getByIdFoundDataProvider
     */
    public function should_find_an_academic_in_the_database(array $rawAcademic, array $rawArticles, Academic $academic): void
    {
        $collectionProphecy = $this->prophesize(Collection::class);
        $collectionProphecy->toArray()->willReturn($rawArticles);
        $collection = $collectionProphecy->reveal();

        $expectedAcademic = $rawAcademic;
        $expectedAcademic['articles'] = \array_map(function (stdClass $article) {
            return (array) $article;
        }, $rawArticles);

        $hydrateAcademicProphecy = $this->prophesize(HydrateAcademic::class);
        $hydrateAcademicProphecy->__invoke($expectedAcademic)->willReturn($academic);
        $hydrateAcademic = $hydrateAcademicProphecy->reveal();

        DB::shouldReceive('table')->with('academics')->once()->andReturnSelf();
        DB::shouldReceive('table')->with('articles')->once()->andReturnSelf();
        DB::shouldReceive('select')->with()->twice()->andReturnSelf();
        DB::shouldReceive('where')->with('id', '=', (string) $academic->registrationNumber())->once()->andReturnSelf();
        DB::shouldReceive('where')->with('academic_id', '=', (string) $academic->registrationNumber())->once()->andReturnSelf();
        DB::shouldReceive('first')->with()->once()->andReturn($rawAcademic);
        DB::shouldReceive('limit')->with(5)->once()->andReturnSelf();
        DB::shouldReceive('get')->with()->once()->andReturn($collection);
        $db = $this->app->get('db');

        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->log(Argument::any(), Argument::any())->shouldNotBeCalled();

        $repository = new AcademicQueryBuilderRepository(
            $db,
            $this->prophesize(SerializeAcademic::class)->reveal(),
            $hydrateAcademic,
            $logger->reveal()
        );

        $retrievedAcademic = $repository->getById($academic->registrationNumber());

        $this->assertSame($academic, $retrievedAcademic);
    }

    public function getByIdFoundDataProvider(): array
    {
        return [
            [
                ['id' => 1],
                [new stdClass()],
                $this->factoryFaker->instance(Academic::class),
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getByIdConnectionExceptionDataProvider
     */
    public function should_throw_an_connection_error_on_get_academic(
        AcademicRegistrationNumber $academicID,
        QueryException $exception
    ): void {
        $this->expectException(ImpossibleToRetrieveAcademics::class);
        DB::shouldReceive('table')->with('academics')->andThrow($exception);
        $db = $this->app->get('db');

        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->error('database failure', ['exception' => $exception, 'academic_id' => (string) $academicID])
            ->shouldBeCalledOnce();

        $repository = new AcademicQueryBuilderRepository(
            $db,
            $this->prophesize(SerializeAcademic::class)->reveal(),
            $this->prophesize(HydrateAcademic::class)->reveal(),
            $logger->reveal()
        );

        $repository->getById($academicID);
    }

    public function getByIdConnectionExceptionDataProvider(): array
    {
        return [
            [$this->factoryFaker->instance(AcademicRegistrationNumber::class), new QueryException('', [], new Exception())],
        ];
    }

    /**
     * @test
     * @dataProvider getByIdNotFoundExceptionDataProvider
     */
    public function should_throw_a_not_found_exception(AcademicRegistrationNumber $academicID): void
    {
        $this->expectException(AcademicNotFound::class);

        $collectionProphecy = $this->prophesize(Collection::class);
        $collectionProphecy->toArray()->willReturn([]);
        $collection = $collectionProphecy->reveal();

        DB::shouldReceive('table')->with('academics')->once()->andReturnSelf();
        DB::shouldReceive('table')->with('articles')->once()->andReturnSelf();
        DB::shouldReceive('select')->with()->twice()->andReturnSelf();
        DB::shouldReceive('limit')->with(5)->once()->andReturnSelf();
        DB::shouldReceive('where')->with('id', '=', (string) $academicID)->once()->andReturnSelf();
        DB::shouldReceive('where')->with('academic_id', '=', (string) $academicID)->once()->andReturnSelf();
        DB::shouldReceive('first')->with()->once()->andReturn(null);
        DB::shouldReceive('get')->with()->once()->andReturn($collection);
        $db = $this->app->get('db');

        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->warning('academic not found', ['academic_id' => (string) $academicID])
            ->shouldBeCalledOnce();

        $repository = new AcademicQueryBuilderRepository(
            $db,
            $this->prophesize(SerializeAcademic::class)->reveal(),
            $this->prophesize(HydrateAcademic::class)->reveal(),
            $logger->reveal()
        );

        $repository->getById($academicID);
    }

    public function getByIdNotFoundExceptionDataProvider(): array
    {
        return [
            [$this->factoryFaker->instance(AcademicRegistrationNumber::class)],
        ];
    }

    /**
     * @test
     * @dataProvider listDataProvider
     */
    public function should_list_academics_from_the_database(
        Collection $academicsCollection,
        AcademicCollection $collection,
        $skip,
        $take,
        $expectedTake
    ): void {
        DB::shouldReceive('table')->with('academics')->once()->andReturnSelf();
        DB::shouldReceive('select')->with()->once()->andReturnSelf();
        DB::shouldReceive('skip')->with($skip)->once()->andReturnSelf();
        DB::shouldReceive('take')->with($expectedTake)->once()->andReturnSelf();
        DB::shouldReceive('get')->with()->once()->andReturn($academicsCollection);
        $db = $this->app->get('db');

        $hydrateAcademicProphecy = $this->prophesize(HydrateAcademic::class);
        foreach ($collection->toArray() as $index => $academic) {
            $hydrateAcademicProphecy->__invoke((array) $academicsCollection[$index])
                ->shouldBeCalledOnce()
                ->willReturn($academic);
        }
        $hydrateAcademic = $hydrateAcademicProphecy->reveal();

        $repository = new AcademicQueryBuilderRepository(
            $db,
            $this->prophesize(SerializeAcademic::class)->reveal(),
            $hydrateAcademic,
            $this->prophesize(LoggerInterface::class)->reveal()
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
                $academicsCollection = new Collection([$stdOne]),
                new AcademicCollection(
                    $this->factoryFaker->instance(Academic::class)
                ),
                0,
                10,
                10,
            ],
            [
                $academicsCollection = new Collection([$stdTwo, $stdThree]),
                new AcademicCollection(
                    $this->factoryFaker->instance(Academic::class),
                    $this->factoryFaker->instance(Academic::class)
                ),
                10,
                100,
                AcademicRepository::MAX_SIZE,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider listConnectionErrorDataProvider
     */
    public function should_throw_an_connection_error_on_list_academics(QueryException $exception, $skip, $take): void
    {
        $this->expectException(ImpossibleToRetrieveAcademics::class);
        DB::shouldReceive('table')->with('academics')->once()->andThrow($exception);
        $db = $this->app->get('db');

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->warning('database failure', ['exception' => $exception])->shouldBeCalledOnce();

        $repository = new AcademicQueryBuilderRepository(
            $db,
            $this->prophesize(SerializeAcademic::class)->reveal(),
            $this->prophesize(HydrateAcademic::class)->reveal(),
            $logger->reveal()
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
     * @dataProvider listConnectionErrorDataProvider
     */
    public function should_generate_next_uuid(): void
    {
        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);

        DB::shouldReceive('table')->with('sequence_academic_id')->once()->andReturnSelf();
        DB::shouldReceive('increment')->with('id')->once()->andReturn(1000000000);
        $db = $this->app->get('db');
        $repository = new AcademicQueryBuilderRepository(
            $db,
            $this->prophesize(SerializeAcademic::class)->reveal(),
            $this->prophesize(HydrateAcademic::class)->reveal(),
            $logger->reveal()
        );

        $this->assertInstanceOf(AcademicRegistrationNumber::class, $repository->nextID());
    }

    /**
     * @test
     * @dataProvider addDataProvider
     */
    public function should_store_an_academic_in_the_database(Academic $academic, array $rawAcademic): void
    {
        $rawArticles = $rawAcademic['articles'];
        $rawAcademicWithoutArticles = $rawAcademic;
        unset($rawAcademicWithoutArticles['articles']);
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('table')->with('articles')->andReturnSelf();
        DB::shouldReceive('table')->with('academics')->once()->andReturnSelf();
        DB::shouldReceive('insert')->with($rawAcademicWithoutArticles)->once()->andReturn(1);
        foreach ($rawArticles as $rawArticle) {
            DB::shouldReceive('insert')->with($rawArticle)->once()->andReturn(1);
        }
        $db = $this->app->get('db');

        // Mocking Academic Mapper
        $serializeAcademicProphecy = $this->prophesize(SerializeAcademic::class);
        $serializeAcademicProphecy->__invoke($academic)->shouldBeCalledOnce()->willReturn($rawAcademic);
        $serializeAcademic = $serializeAcademicProphecy->reveal();

        $repository = new AcademicQueryBuilderRepository(
            $db,
            $serializeAcademic,
            $this->prophesize(HydrateAcademic::class)->reveal(),
            $this->prophesize(LoggerInterface::class)->reveal()
        );

        $repository->add($academic);
    }

    public function addDataProvider(): array
    {
        return [
            [$this->factoryFaker->instance(Academic::class), ['id' => 1, 'articles' => [['id' => 2], ['id' => 3]]]],
        ];
    }

    /**
     * @test
     * @dataProvider addConnectionErrorDataProvider
     */
    public function should_throw_an_connection_error_on_store_academic(
        Academic $academic,
        array $rawAcademic,
        QueryException $exception
    ): void {
        $rawAcademicWithoutArticles = $rawAcademic;
        unset($rawAcademicWithoutArticles['articles']);
        $this->expectException(ImpossibleToSaveAcademic::class);
        DB::shouldReceive('beginTransaction')->with()->once()->andThrow($exception);
        $db = $this->app->get('db');

        // Mocking Academic Mapper
        $serializeAcademicProphecy = $this->prophesize(SerializeAcademic::class);
        $serializeAcademicProphecy->__invoke($academic)->shouldBeCalledOnce()->willReturn($rawAcademic);

        // Mocking Logger
        $loggerProphecy = $this->prophesize(LoggerInterface::class);
        $loggerProphecy->error('database failure', ['exception' => $exception, 'academic' => $rawAcademicWithoutArticles])->shouldBeCalledOnce();

        $repository = new AcademicQueryBuilderRepository(
            $db,
            $serializeAcademicProphecy->reveal(),
            $this->prophesize(HydrateAcademic::class)->reveal(),
            $loggerProphecy->reveal()
        );

        $repository->add($academic);
    }

    public function addConnectionErrorDataProvider(): array
    {
        return [
            [$this->factoryFaker->instance(Academic::class), ['id' => 1, 'articles' => [['id' => 2], ['id' => 3]]], new QueryException('', [], new Exception())],
        ];
    }

    /**
     * @test
     * @dataProvider addDataProvider
     */
    public function should_update_an_academic_in_the_database(Academic $academic, array $rawAcademic): void
    {
        $rawAcademicWithoutArticles = $rawAcademic;
        unset($rawAcademicWithoutArticles['articles']);
        DB::shouldReceive('beginTransaction')->with()->once()->andReturnSelf();
        DB::shouldReceive('commit')->with()->once()->andReturnSelf();
        DB::shouldReceive('table')->with('academics')->once()->andReturnSelf();
        DB::shouldReceive('table')->with('articles')->andReturnSelf();
        DB::shouldReceive('where')->with('id', '=', (string) $academic->registrationNumber())->once()->andReturnSelf();
        DB::shouldReceive('update')->with($rawAcademicWithoutArticles)->once();
        foreach ($rawAcademic['articles'] as $rawArticle) {
            DB::shouldReceive('updateOrInsert')->with(['id' => $rawArticle['id']], $rawArticle)->once();
        }
        $db = $this->app->get('db');

        // Mocking Academic Mapper
        $serializeAcademicProphecy = $this->prophesize(SerializeAcademic::class);
        $serializeAcademicProphecy->__invoke($academic)->shouldBeCalledOnce()->willReturn($rawAcademic);

        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);

        $repository = new AcademicQueryBuilderRepository(
            $db,
            $serializeAcademicProphecy->reveal(),
            $this->prophesize(HydrateAcademic::class)->reveal(),
            $logger->reveal()
        );

        $repository->update($academic);
    }

    /**
     * @test
     * @dataProvider addConnectionErrorDataProvider
     */
    public function should_throw_an_connection_error_on_update_academic(
        Academic $academic,
        array $rawAcademic,
        QueryException $exception
    ): void {
        $this->expectException(ImpossibleToSaveAcademic::class);
        DB::shouldReceive('beginTransaction')->with()->once()->andThrow($exception);
        $db = $this->app->get('db');

        // Mocking Academic Mapper
        $serilizeAcademicProphecy = $this->prophesize(SerializeAcademic::class);
        $serilizeAcademicProphecy->__invoke($academic)->shouldBeCalledOnce()->willReturn($rawAcademic);

        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->error('database failure', ['exception' => $exception, 'academic' => $rawAcademic])->shouldBeCalledOnce();

        $repository = new AcademicQueryBuilderRepository(
            $db,
            $serilizeAcademicProphecy->reveal(),
            $this->prophesize(HydrateAcademic::class)->reveal(),
            $logger->reveal()
        );

        $repository->update($academic);
    }
}
