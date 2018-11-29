<?php

declare(strict_types=1);

namespace LaravelDay\Article\ValueObject;

final class Id
{
    /** string */
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function isEqual(self $id)
    {
        return (int) $id == $this->id;
    }

    public function value()
    {
        return $this->id;
    }

    public function __clone()
    {
    }
}
