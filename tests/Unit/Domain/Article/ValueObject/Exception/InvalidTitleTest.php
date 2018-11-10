<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Article\ValueObject\Exception;

use Acme\Article\ValueObject\Exception\InvalidTitle;
use Acme\Article\ValueObject\Title;
use Tests\TestCase;

/**
 * @covers \Acme\Article\ValueObject\Exception\InvalidTitle
 */
final class InvalidTitleTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidValueDatProvider
     */
    public function should_set_error_message($invalidValue): void
    {
        $error = new InvalidTitle($invalidValue);
        $expectedMessage = \sprintf(InvalidTitle::LENGTH_MESSAGE_FORMAT, $invalidValue, Title::MIN_LENGTH, Title::MAX_LENGTH);
        $this->assertSame($expectedMessage, $error->getMessage());
    }

    public function invalidValueDatProvider(): array
    {
        return [
            [1],
            ['an invalid title'],
            [''],
        ];
    }
}
