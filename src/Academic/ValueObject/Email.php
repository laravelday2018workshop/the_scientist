<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject;

use Acme\Academic\ValueObject\Exception\InvalidEmail;

final class Email
{
    public const EMAIL_DOMAIN = '@the.com';

    /**
     * @var string
     */
    private $value;

    /**
     * @throws InvalidEmail
     */
    public function __construct(string $value)
    {
        $value = \trim($value);
        if (!\filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw InvalidEmail::fromInvalidValue($value);
        }

        if (false === \mb_strpos($value, self::EMAIL_DOMAIN)) {
            throw InvalidEmail::fromInvalidDomain($value);
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isEquals(self $email): bool
    {
        return $this->value === $email->value;
    }

    private function __clone()
    {
    }
}
