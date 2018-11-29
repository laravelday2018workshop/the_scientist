<?php

namespace Tests\Unit\Domain\Article\ValueObject;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use LaravelDay\Article\ValueObject\Id;
use Tests\TestCase;

class IdTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @test
     * @return void
     */
    public function shoudCreateAnId()
    {
        $nativeId = 1234;
        $id = new Id($nativeId);
       
        $this->assertSame($nativeId, $id->value());
    }
}
