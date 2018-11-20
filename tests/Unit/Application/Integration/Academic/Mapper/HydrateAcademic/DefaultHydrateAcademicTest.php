<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Academic\Mapper\HydrateAcademic;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Article\ArticleCollection;
use App\Integration\Academic\Mapper\FromArray\DefaultHydrateAcademic;
use App\Integration\Article\Mapper\Hydrator\HydrateArticle;
use Prophecy\Argument;
use Tests\TestCase;

/**
 * @covers \App\Integration\Academic\Mapper\FromArray\DefaultHydrateAcademic
 */
final class DefaultHydrateAcademicTest extends TestCase
{
    /**
     * @test
     * @dataProvider databaseRecordDataProvider
     */
    public function should_return_an_academic(array $rawAcademic): void
    {
        $articleMapperProphecy = $this->prophesize(HydrateArticle::class);
        $articleMapperProphecy->__invoke(Argument::any())->willReturn(ArticleCollection::class);
        $articleMapper = $articleMapperProphecy->reveal();

        $mapper = new DefaultHydrateAcademic($articleMapper);
        $academic = $mapper($rawAcademic);
        $this->assertSame($rawAcademic['id'], (string) $academic->registrationNumber());
    }

    /**
     * @test
     * @dataProvider databaseRecordWithArticlesDataProvider
     */
    public function should_return_an_academic_withDates(array $rawAcademic, ArticleCollection $articles): void
    {
        $articleMapperProphecy = $this->prophesize(HydrateArticle::class);
        foreach ($articles as $article) {
            $articleMapperProphecy->__invoke(Argument::any())->willReturn($article);
        }
        $articleMapper = $articleMapperProphecy->reveal();

        $mapper = new DefaultHydrateAcademic($articleMapper);
        $academic = $mapper($rawAcademic);
        $this->assertSame($rawAcademic['id'], (string) $academic->registrationNumber());
    }

    public function databaseRecordDataProvider(): array
    {
        return [
            [
                [
                    'id' => (string) $this->factoryFaker->instance(AcademicRegistrationNumber::class),
                    'articles' => [],
                ],
            ],
        ];
    }

    public function databaseRecordWithArticlesDataProvider(): array
    {
        return [
            [
                [
                    'id' => (string) $this->factoryFaker->instance(AcademicRegistrationNumber::class),
                    'articles' => [],
                ],
                $this->factoryFaker->instance(ArticleCollection::class),
            ],
        ];
    }
}
