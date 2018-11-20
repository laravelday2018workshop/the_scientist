<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Academic\Mapper\SerializeAcademic;

use Acme\Academic\Academic;
use App\Integration\Academic\Mapper\Serializer\DefaultSerializeAcademic;
use App\Integration\Article\Mapper\Serializer\SerializeArticle;
use Tests\TestCase;

/**
 * @covers \App\Integration\Academic\Mapper\Serializer\DefaultSerializeAcademic
 */
final class DefaultSerializeAcademicTest extends TestCase
{
    /**
     * @test
     * @dataProvider academicDataProvider
     */
    public function should_properly_map_the_academic(Academic $academic): void
    {
        $fromArticleMapper = $this->prophesize(SerializeArticle::class)->reveal();
        $mapper = new DefaultSerializeAcademic($fromArticleMapper);
        $academicArray = $mapper($academic);
        $this->assertSame($academicArray['id'], (string) $academic->registrationNumber());
        $this->assertSame([], $academicArray['articles']);
    }

    public function academicDataProvider(): array
    {
        return [
            [
                $this->factoryFaker->instance(Academic::class),
            ],
        ];
    }
}
