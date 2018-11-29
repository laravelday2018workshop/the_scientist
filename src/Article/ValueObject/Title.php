<?php

declare(strict_types=1);

namespace LaravelDay\Article\ValueObject;

use LaravelDay\Article\ValueObject\Exception\TitleTooShort;

final class Title
{
    /** string */
    private $title;

    public function __construct(string $title)
    {
        if (\mb_strlen($title) < 10) {
            throw new TitleTooShort('Title too short');
        }

        $this->title = $title;
    }

    public function isEqual(self $title)
    {
        return (string) $title === $this->__toString();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function __clone()
    {
    }
}
