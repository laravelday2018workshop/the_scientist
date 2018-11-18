<?php

namespace Tests\Unit\Application\Integration\Common\Query;

use App\Integration\Common\Query\CrudFacadeDefault;
use App\Integration\Common\Query\CrudFacadeDefaultBuilder;
use Illuminate\Database\Query\Builder;
use Tests\TestCase;

class CrudFacadeDefaultBuilderTest extends TestCase
{

    /**
     * @test
     */
    public function should_build()
    {
        $tableName = 'users';

        $builder = $this->prophesize(Builder::class)->reveal();

        $crudFacadeBuilder = $this->app->get(CrudFacadeDefaultBuilder::class);

        $crudFacade = $crudFacadeBuilder->build($tableName);

        $this->assertNotNull($crudFacade);
        $this->assertInstanceOf(CrudFacadeDefault::class, $crudFacade);
    }
}
