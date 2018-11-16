<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject;

use Acme\Academic\ValueObject\Exception\InvalidLastName;

final class LastName
{
    public const MIN_LENGTH = 3;
    public const MAX_LENGTH = 100;

    /**
     * @var string
     */
    private $value;

    /**
     * @throws InvalidLastName
     */
    public function __construct(string $value)
    {
        $value = \trim($value);
        $length = \mb_strlen($value);
        if ($length < self::MIN_LENGTH || $length >= self::MAX_LENGTH) {
            throw new InvalidLastName($value);
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isEquals(self $firstName): bool
    {
        return $this->value === (string) $firstName;
    }

    private function __clone()
    {
    }
}
