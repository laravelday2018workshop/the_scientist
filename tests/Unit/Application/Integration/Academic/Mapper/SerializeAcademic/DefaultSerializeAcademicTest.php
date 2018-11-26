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
        $academicArray = $mapper->withPassword($academic);
        $this->assertSame($academicArray['id'], (string) $academic->registrationNumber());
        $this->assertSame($academicArray['first_name'], (string) $academic->firstName());
        $this->assertSame($academicArray['last_name'], (string) $academic->lastName());
        $this->assertSame($academicArray['email'], (string) $academic->email());
        $this->assertSame($academicArray['password'], (string) $academic->password());
        $this->assertSame($academicArray['major'], (string) $academic->major());
        $this->assertSame($academicArray['birth_date'], (string) $academic->birthDate());
        $this->assertSame([], $academicArray['articles']);
    }

    /**
     * @test
     * @dataProvider academicDataProvider
     */
    public function should_properly_map_the_academic_without_password(Academic $academic): void
    {
        $fromArticleMapper = $this->prophesize(SerializeArticle::class)->reveal();
        $mapper = new DefaultSerializeAcademic($fromArticleMapper);
        $academicArray = $mapper->withoutPassword($academic);
        $this->assertArrayNotHasKey('password', $academicArray);
        $this->assertSame($academicArray['id'], (string) $academic->registrationNumber());
        $this->assertSame($academicArray['first_name'], (string) $academic->firstName());
        $this->assertSame($academicArray['last_name'], (string) $academic->lastName());
        $this->assertSame($academicArray['email'], (string) $academic->email());
        $this->assertSame($academicArray['major'], (string) $academic->major());
        $this->assertSame($academicArray['birth_date'], (string) $academic->birthDate());
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
