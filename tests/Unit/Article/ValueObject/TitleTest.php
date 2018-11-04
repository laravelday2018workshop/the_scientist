<?php

declare(strict_types=1);

namespace Tests\Unit\Article\ValueObject;

use Acme\Article\ValueObject\Exception\InvalidTitle;
use Acme\Article\ValueObject\Title;
use Error;
use PHPUnit\Framework\TestCase;

final class TitleTest extends TestCase
{
    /**
     * @test
     * @dataProvider validTitleDataProvider
     */
    public function should_create_title(string $rawTitle): void
    {
        $title = new Title($rawTitle);
        $this->assertTrue($title->isEquals($title));
        $this->assertSame(\trim($rawTitle), (string) $title);
    }

    /**
     * @test
     * @dataProvider invalidTitleDataProvider
     */
    public function should_thrown_invalidTitle_exception(string $uuid): void
    {
        $this->expectException(InvalidTitle::class);
        $this->expectExceptionMessage(
            \sprintf(InvalidTitle::LENGTH_MESSAGE_FORMAT, \trim($uuid), Title::MIN_LENGTH, Title::MAX_LENGTH)
        );
        new Title($uuid);
    }

    /**
     * @test
     * @dataProvider validTitleDataProvider
     */
    public function should_throw_exception_on_clone(string $uuid): void
    {
        $this->expectException(Error::class);
        $title = new Title($uuid);
        clone $title;
    }

    public function validTitleDataProvider(): array
    {
        return [
            ['Genetic research'],
            ['How sexuality influence human habits'],
        ];
    }

    public function invalidTitleDataProvider(): array
    {
        return [
            [''],
            ['No 3'],
            ['                                       a                             '],
            ['1'],
        ];
    }
}
