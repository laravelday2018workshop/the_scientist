<?php

namespace Tests\Unit\Domain\Article\ValueObject;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use LaravelDay\Article\ValueObject\Body;
use Tests\TestCase;

class BodyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @test
     * @return void
     */
    public function shoudCreateABody()
    {
        $stringBody = 'Titolo di test';
        $body = new Body($stringBody);
        $this->assertSame($stringBody, (string) $body);
    }
}
