<?php

namespace Tests\Unit\Domain\Article\ValueObject;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use LaravelDay\Article\ValueObject\Title;
use Tests\TestCase;

class TitleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @test
     * @return void
     */
    public function shoudCreateATitle()
    {
        $stringTitle = 'Titolo di test';
        $title = new Title($stringTitle);
        $this->assertSame($stringTitle, (string) $title);
    }
}
