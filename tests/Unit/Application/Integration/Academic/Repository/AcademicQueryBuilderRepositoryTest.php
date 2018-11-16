<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Academic\Repository;

use Acme\Academic\Academic;
use Acme\Academic\AcademicCollection;
use Acme\Academic\Repository\AcademicRepository;
use Acme\Academic\Repository\Exception\AcademicNotFound;
use Acme\Academic\Repository\Exception\ImpossibleToRetrieveAcademics;
use Acme\Academic\Repository\Exception\ImpossibleToSaveAcademic;
use Acme\Academic\ValueObject\AcademicID;
use Acme\Common\Query\Pagination;
use App\Integration\Academic\Mapper\AcademicMapper;
use App\Integration\Academic\Repository\AcademicQueryBuilderRepository;
use App\Integration\Common\Query\CrudFacade;
use Exception;
use Illuminate\Database\QueryException;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
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
    public function should_find_an_academic_in_the_database(array $rawAcademic, Academic $academic): void
    {
        // Mocking Crud Facade
        $crudFacade = $this->prophesize(CrudFacade::class);
        $crudFacade->getById($academic->id())
                   ->shouldBeCalledOnce()
                   ->willReturn($rawAcademic);

        // Mocking Academic Mapper
        $academicMapper = $this->prophesize(AcademicMapper::class);
        $academicMapper->fromArray($rawAcademic)
                       ->shouldBeCalledOnce()
                       ->willReturn($academic);

        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->log(Argument::any(), Argument::any())
               ->shouldNotBeCalled();

        $repository = new AcademicQueryBuilderRepository(
            $crudFacade->reveal(),
            $academicMapper->reveal(),
            $logger->reveal()
        );

        $retrievedAcademic = $repository->getById($academic->id());

        $this->assertSame($academic, $retrievedAcademic);
    }

    public function getByIdFoundDataProvider(): array
    {
        return [
            [
                ['id' => 1],
                $this->factoryFaker->instance(Academic::class),
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getByIdConnectionExceptionDataProvider
     */
    public function should_throw_an_connection_error_on_get_academic(AcademicID $academicID,
                                                                     QueryException $exception
    ): void {
        $this->expectException(ImpossibleToRetrieveAcademics::class);

        // Mocking Crud Facade
        $crudFacade = $this->prophesize(CrudFacade::class);
        $crudFacade->getById($academicID)
                   ->shouldBeCalledOnce()
                   ->willThrow($exception);

        // Mocking Academic Mapper
        $academicMapper = $this->prophesize(AcademicMapper::class);
        $academicMapper->fromArray(Argument::any())->shouldNotBeCalled();

        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->error('database failure', ['exception' => $exception, 'academic_id' => (string) $academicID])
               ->shouldBeCalledOnce();

        $repository = new AcademicQueryBuilderRepository(
            $crudFacade->reveal(),
            $academicMapper->reveal(),
            $logger->reveal()
        );

        $repository->getById($academicID);
    }

    public function getByIdConnectionExceptionDataProvider(): array
    {
        return [
            [$this->factoryFaker->instance(AcademicID::class), new QueryException('', [], new Exception())],
        ];
    }

    /**
     * @test
     * @dataProvider getByIdNotFoundExceptionDataProvider
     */
    public function should_throw_a_not_found_exception(AcademicID $academicID): void
    {
        $this->expectException(AcademicNotFound::class);

        // Mocking Crud Facade
        $crudFacade = $this->prophesize(CrudFacade::class);
        $crudFacade->getById($academicID)
                   ->shouldBeCalledOnce()
                   ->willReturn(null);

        // Mocking Academic Mapper
        $academicMapper = $this->prophesize(AcademicMapper::class);
        $academicMapper->fromArray(Argument::any())->shouldNotBeCalled();

        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->warning('academic not found', ['academic_id' => (string) $academicID])
               ->shouldBeCalledOnce();

        $repository = new AcademicQueryBuilderRepository(
            $crudFacade->reveal(),
            $academicMapper->reveal(),
            $logger->reveal()
        );

        $repository->getById($academicID);
    }

    public function getByIdNotFoundExceptionDataProvider(): array
    {
        return [
            [$this->factoryFaker->instance(AcademicID::class)],
        ];
    }

    /**
     * @test
     * @dataProvider listDataProvider
     */
    public function should_list_academics_from_the_database(array $rawAcademics,
                                                            AcademicCollection $collection,
                                                            $skip,
                                                            $take,
                                                            $expectedTake
    ): void {
        // Mocking Academic Mapper
        $academicMapper = $this->prophesize(AcademicMapper::class);

        foreach ($collection->toArray() as $index => $academic) {
            $academicMapper->fromArray((array) $rawAcademics[$index])
                           ->shouldBeCalledOnce()
                           ->willReturn($academic);
        }

        // Mocking Crud Facade
        $crudFacade = $this->prophesize(CrudFacade::class);
        $crudFacade->getAll(new Pagination($skip, $expectedTake))
                   ->shouldBeCalledOnce()
                   ->willReturn($rawAcademics);

        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->log(Argument::any(), Argument::any())
               ->shouldNotBeCalled();

        $repository = new AcademicQueryBuilderRepository(
            $crudFacade->reveal(),
            $academicMapper->reveal(),
            $logger->reveal()
        );

        $retrievedCollection = $repository->list($skip, $take);

        $this->assertSame($collection->toArray(), $retrievedCollection->toArray());
    }

    public function listDataProvider(): array
    {
        return [
            [
                [['id' => 1]],
                new AcademicCollection(
                    $this->factoryFaker->instance(Academic::class)
                ),
                0,
                10,
                10,
            ],
            [
                [['id' => 1], ['id' => 2]],
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

        // Mocking Academic Mapper
        $academicMapper = $this->prophesize(AcademicMapper::class);
        $academicMapper->fromArray(Argument::any())
                       ->shouldNotBeCalled();

        // Mocking Crud Facade
        $crudFacade = $this->prophesize(CrudFacade::class);
        $crudFacade->getAll(new Pagination($skip, $take))
                   ->shouldBeCalledOnce()
                   ->willThrow($exception);

        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->warning('database failure', ['exception' => $exception])
               ->shouldBeCalledOnce();

        $repository = new AcademicQueryBuilderRepository(
            $crudFacade->reveal(),
            $academicMapper->reveal(),
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
        // Mocking Crud Facade
        $crudFacade = $this->prophesize(CrudFacade::class);

        // Mocking Academic Mapper
        $academicMapper = $this->prophesize(AcademicMapper::class);

        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);

        $repository = new AcademicQueryBuilderRepository(
            $crudFacade->reveal(),
            $academicMapper->reveal(),
            $logger->reveal()
        );

        $this->assertInstanceOf(AcademicID::class, $repository->nextID());
    }

    /**
     * @test
     * @dataProvider addDataProvider
     */
    public function should_store_an_academic_in_the_database(Academic $academic, array $rawAcademic): void
    {
        // Mocking Crud Facade
        $crudFacade = $this->prophesize(CrudFacade::class);
        $crudFacade->save($rawAcademic)
                   ->shouldBeCalledOnce();

        // Mocking Academic Mapper
        $academicMapper = $this->prophesize(AcademicMapper::class);
        $academicMapper->fromAcademic($academic)
                       ->shouldBeCalledOnce()
                       ->willReturn($rawAcademic);

        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);

        $repository = new AcademicQueryBuilderRepository(
            $crudFacade->reveal(),
            $academicMapper->reveal(),
            $logger->reveal()
        );

        $repository->add($academic);
    }

    public function addDataProvider(): array
    {
        return [
            [$this->factoryFaker->instance(Academic::class), []],
        ];
    }

    /**
     * @test
     * @dataProvider addConnectionErrorDataProvider
     */
    public function should_throw_an_connection_error_on_store_academic(Academic $academic,
                                                                       array $rawAcademic,
                                                                       QueryException $exception
    ): void {
        $this->expectException(ImpossibleToSaveAcademic::class);

        // Mocking Crud Facade
        $crudFacade = $this->prophesize(CrudFacade::class);
        $crudFacade->save($rawAcademic)
                   ->willThrow($exception);

        // Mocking Academic Mapper
        $academicMapper = $this->prophesize(AcademicMapper::class);
        $academicMapper->fromAcademic($academic)
                       ->shouldBeCalledOnce()
                       ->willReturn($rawAcademic);

        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->error('database failure', ['exception' => $exception, 'academic' => $rawAcademic])
               ->shouldBeCalledOnce();

        $repository = new AcademicQueryBuilderRepository(
            $crudFacade->reveal(),
            $academicMapper->reveal(),
            $logger->reveal()
        );

        $repository->add($academic);
    }

    public function addConnectionErrorDataProvider(): array
    {
        return [
            [$this->factoryFaker->instance(Academic::class), [], new QueryException('', [], new Exception())],
        ];
    }

    /**
     * @test
     * @dataProvider addDataProvider
     */
    public function should_update_an_academic_in_the_database(Academic $academic, array $rawAcademic): void
    {
        // Mocking Crud Facade
        $crudFacade = $this->prophesize(CrudFacade::class);
        $crudFacade->update($academic->id(), $rawAcademic)
                   ->shouldBeCalledOnce();

        // Mocking Academic Mapper
        $academicMapper = $this->prophesize(AcademicMapper::class);
        $academicMapper->fromAcademic($academic)
                       ->shouldBeCalledOnce()
                       ->willReturn($rawAcademic);

        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);

        $repository = new AcademicQueryBuilderRepository(
            $crudFacade->reveal(),
            $academicMapper->reveal(),
            $logger->reveal()
        );

        $repository->update($academic);
    }

    /**
     * @test
     * @dataProvider addConnectionErrorDataProvider
     */
    public function should_throw_an_connection_error_on_update_academic(Academic $academic,
                                                                        array $rawAcademic,
                                                                        QueryException $exception
    ): void {
        $this->expectException(ImpossibleToSaveAcademic::class);

        // Mocking Crud Facade
        $crudFacade = $this->prophesize(CrudFacade::class);
        $crudFacade->update($academic->id(), $rawAcademic)
                   ->willThrow($exception);

        // Mocking Academic Mapper
        $academicMapper = $this->prophesize(AcademicMapper::class);
        $academicMapper->fromAcademic($academic)
                       ->shouldBeCalledOnce()
                       ->willReturn($rawAcademic);

        // Mocking Logger
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->error('database failure', ['exception' => $exception, 'academic' => $rawAcademic])
               ->shouldBeCalledOnce();

        $repository = new AcademicQueryBuilderRepository(
            $crudFacade->reveal(),
            $academicMapper->reveal(),
            $logger->reveal()
        );

        $repository->update($academic);
    }
}
