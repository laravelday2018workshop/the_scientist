<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Common\Query;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use App\Integration\Common\Query\CrudFacadeDefault;
use App\Integration\Common\Query\Delete;
use App\Integration\Common\Query\Insert;
use App\Integration\Common\Query\Pagination;
use App\Integration\Common\Query\SelectAll;
use App\Integration\Common\Query\SelectById;
use App\Integration\Common\Query\Update;
use Tests\TestCase;

/**
 * @covers \App\Integration\Common\Query\CrudFacadeDefault
 */
class CrudFacadeDefaultTest extends TestCase
{
    /** @var SelectById */
    private $selectById;

    /** @var SelectAll */
    private $selectAll;

    /** @var Update */
    private $update;

    /** @var Delete */
    private $delete;

    /** @var Insert */
    private $insert;

    public function setUp()
    {
        parent::setUp();

        $this->selectById = $this->prophesize(SelectById::class);
        $this->selectAll = $this->prophesize(SelectAll::class);
        $this->insert = $this->prophesize(Insert::class);
        $this->update = $this->prophesize(Update::class);
        $this->delete = $this->prophesize(Delete::class);
    }

    private function makeCrudFacade(): CrudFacadeDefault
    {
        return new CrudFacadeDefault(
            $this->selectById->reveal(),
            $this->selectAll->reveal(),
            $this->insert->reveal(),
            $this->update->reveal(),
            $this->delete->reveal()
        );
    }

    /**
     * @test
     */
    public function should_getById()
    {
        /** @var AcademicRegistrationNumber $academicId */
        $academicId = $this->factoryFaker->instance(AcademicRegistrationNumber::class);

        $expectedResult = ['id' => 1];

        $this->selectById->__invoke($academicId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedResult);

        $crud = $this->makeCrudFacade();

        $result = $crud->getById($academicId);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     */
    public function should_get_all()
    {
        $pagination = new Pagination(2, 10);

        $expectedResult = [['id' => 1], ['id' => 1]];

        $this->selectAll->__invoke($pagination)
            ->shouldBeCalledOnce()
            ->willReturn($expectedResult);

        $crud = $this->makeCrudFacade();

        $result = $crud->getAll($pagination);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     */
    public function should_save()
    {
        $data = ['id' => 1, 'name' => 'Pippo'];

        $this->insert->__invoke($data)
            ->shouldBeCalledOnce()
            ->hasReturnVoid();

        $crud = $this->makeCrudFacade();

        $crud->save($data);
    }

    /**
     * @test
     */
    public function should_update()
    {
        $data = ['id' => 1, 'name' => 'Pippo'];

        /** @var AcademicRegistrationNumber $academicId */
        $academicId = $this->factoryFaker->instance(AcademicRegistrationNumber::class);

        $this->update->__invoke($academicId, $data)
            ->shouldBeCalledOnce()
            ->hasReturnVoid();

        $crud = $this->makeCrudFacade();

        $crud->update($academicId, $data);
    }

    /**
     * @test
     */
    public function should_remove()
    {
        /** @var AcademicRegistrationNumber $academicId */
        $academicId = $this->factoryFaker->instance(AcademicRegistrationNumber::class);

        $this->delete->__invoke($academicId)
            ->shouldBeCalledOnce()
            ->hasReturnVoid();

        $crud = $this->makeCrudFacade();

        $crud->remove($academicId);
    }
}
