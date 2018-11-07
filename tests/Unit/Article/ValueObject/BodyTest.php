<?php

declare(strict_types=1);

namespace Tests\Unit\Article\ValueObject;

use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Exception\InvalidBody;
use Error;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acme\Article\ValueObject\Body
 */
final class BodyTest extends TestCase
{
    /**
     * @test
     * @dataProvider validBodyDataProvider
     */
    public function should_create_body(string $rawBody): void
    {
        $body = new Body($rawBody);
        $this->assertTrue($body->isEquals($body));
        $this->assertSame(\trim($rawBody), (string) $body);
    }

    /**
     * @test
     * @dataProvider invalidBodyDataProvider
     */
    public function should_thrown_invalidBody_exception(string $uuid): void
    {
        $this->expectException(InvalidBody::class);
        $this->expectExceptionMessage(\sprintf(InvalidBody::LENGTH_MESSAGE_FORMAT, \trim($uuid), Body::MIN_LENGTH));
        new Body($uuid);
    }

    /**
     * @test
     * @dataProvider validBodyDataProvider
     */
    public function should_throw_exception_on_clone(string $uuid): void
    {
        $this->expectException(Error::class);
        $body = new Body($uuid);
        clone $body;
    }

    public function validBodyDataProvider(): array
    {
        return [
            ['This is a very long content'],
            ['          This is the body of an article!'],
        ];
    }

    public function invalidBodyDataProvider(): array
    {
        return [
            [''],
            ['                                       '],
        ];
    }
}
