<?php

declare(strict_types=1);

namespace Tests\Unit\Article\ValueObject\Exception;

use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Exception\InvalidBody;
use Tests\TestCase;

/**
 * @covers \Acme\Article\ValueObject\Exception\InvalidBody
 */
final class InvalidBodyTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidValueDatProvider
     */
    public function should_set_error_message($invalidValue): void
    {
        $error = new InvalidBody($invalidValue);
        $expectedMessage = \sprintf(InvalidBody::LENGTH_MESSAGE_FORMAT, $invalidValue, Body::MIN_LENGTH);
        $this->assertSame($expectedMessage, $error->getMessage());
    }

    public function invalidValueDatProvider(): array
    {
        return [
            [1],
            ['an invalid body'],
            [''],
        ];
    }
}
