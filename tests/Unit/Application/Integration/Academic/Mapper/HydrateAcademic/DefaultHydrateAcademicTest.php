<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Integration\Academic\Mapper\HydrateAcademic;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Academic\ValueObject\BirthDate;
use Acme\Academic\ValueObject\Email;
use Acme\Academic\ValueObject\FirstName;
use Acme\Academic\ValueObject\LastName;
use Acme\Academic\ValueObject\Major;
use Acme\Academic\ValueObject\Password;
use Acme\Article\ArticleCollection;
use App\Integration\Academic\Mapper\Hydrator\DefaultHydrateAcademic;
use App\Integration\Article\Mapper\Hydrator\HydrateArticle;
use Prophecy\Argument;
use Tests\TestCase;

/**
 * @covers \App\Integration\Academic\Mapper\Hydrator\DefaultHydrateAcademic
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
        $this->assertSame($rawAcademic['first_name'], (string) $academic->firstName());
        $this->assertSame($rawAcademic['last_name'], (string) $academic->lastName());
        $this->assertSame($rawAcademic['email'], (string) $academic->email());
        $this->assertSame($rawAcademic['password'], (string) $academic->password());
        $this->assertSame($rawAcademic['major'], (string) $academic->major());
        $this->assertSame($rawAcademic['birth_date'], (string) $academic->birthDate());
        $this->assertEmpty($academic->articles()->toArray());
    }

    public function databaseRecordDataProvider(): array
    {
        return [
            [
                [
                    'id' => (string) $this->factoryFaker->instance(AcademicRegistrationNumber::class),
                    'first_name' => (string) $this->factoryFaker->instance(FirstName::class),
                    'last_name' => (string) $this->factoryFaker->instance(LastName::class),
                    'email' => (string) $this->factoryFaker->instance(Email::class),
                    'password' => (string) $this->factoryFaker->instance(Password::class),
                    'major' => (string) $this->factoryFaker->instance(Major::class),
                    'birth_date' => (string) $this->factoryFaker->instance(BirthDate::class),
                    'articles' => [],
                ],
            ],
        ];
    }
}
