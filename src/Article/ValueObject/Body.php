<?php

declare(strict_types=1);

namespace Acme\Article\ValueObject;

use Acme\Article\ValueObject\Exception\InvalidBody;

final class Body
{
    public const MIN_LENGTH = 1;

    /**
     * @var string
     */
    private $value;

    /**
     * @throws InvalidBody
     */
    public function __construct(string $value)
    {
        $value = \trim($value);
        if (\mb_strlen($value) < self::MIN_LENGTH) {
            throw new InvalidBody($value);
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isEquals(self $body): bool
    {
        return $this->value === (string) $body;
    }

    private function __clone()
    {
    }
}
