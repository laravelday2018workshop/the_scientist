<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Common\ValueObject;

use Acme\Common\ValueObject\Exception\InvalidID;
use Error;
use Tests\Fixture\UUIDFixture;
use Tests\TestCase;

/**
 * @covers \Acme\Article\ValueObject\ArticleID
 * @covers \Acme\Academic\ValueObject\AcademicID
 * @covers \Acme\Reviewer\ValueObject\ReviewerID
 * @covers \Acme\Common\ValueObject\UUIDTrait
 */
final class UUIDTraitTest extends TestCase
{
    /**
     * @test
     * @dataProvider validUUIDDataProvider
     */
    public function should_create_articleID(string $uuid): void
    {
        $fake = UUIDFixture::fromUUID($uuid);
        $this->assertTrue($fake->isEquals($fake));
        $this->assertSame($uuid, (string) $fake);
    }

    /**
     * @test
     * @dataProvider invalidUUIDDataProvider
     */
    public function should_throw_invalidArticleID_exception(string $uuid): void
    {
        $this->expectException(InvalidID::class);
        $this->expectExceptionMessage(\sprintf(InvalidID::ERROR_MESSAGE_FORMAT, $uuid));
        UUIDFixture::fromUUID($uuid);
    }

    /**
     * @test
     * @dataProvider validUUIDDataProvider
     */
    public function should_throw_exception_on_clone(string $uuid): void
    {
        $this->expectException(Error::class);
        $fake = UUIDFixture::fromUUID($uuid);
        clone $fake;
    }

    public function validUUIDDataProvider(): array
    {
        return [
            ['96b9c4f8-4196-461a-a5f3-d058d5c60bc5'],
            ['41daa803-5d0e-4b68-a092-ee1c561aa39b'],
            ['45d16fcb-8ccf-4f1b-aa5b-aad78cd476f6'],
        ];
    }

    public function invalidUUIDDataProvider(): array
    {
        return [
            [''],
            ['none'],
            ['?'],
            ['1'],
            ['41daa803-5d0e-4b68-a2-ee1c561aa39b'],
            ['45d16fcb-8ccf-4f1b-aa5b-aad78cd476f'],
        ];
    }
}
