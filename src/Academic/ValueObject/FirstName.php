<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject;

use Acme\Academic\ValueObject\Exception\InvalidFirstName;

final class FirstName
{
    public const MIN_LENGTH = 2;
    public const MAX_LENGTH = 100;

    /**
     * @var string
     */
    private $value;

    /**
     * @throws InvalidFirstName
     */
    public function __construct(string $value)
    {
        $value = \trim($value);
        $length = \mb_strlen($value);
        if ($length < self::MIN_LENGTH || $length >= self::MAX_LENGTH) {
            throw new InvalidFirstName($value);
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
