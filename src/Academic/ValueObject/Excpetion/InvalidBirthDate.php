<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject\Exception;

use Acme\Academic\ValueObject\BirthDate;
use Throwable;

final class InvalidBirthDate extends \InvalidArgumentException
{
    public const ERROR_AGE_MESSAGE_FORMAT = 'The academic must be at lease %d years old. Given: %d years';
    public const ERROR_VALUE_MESSAGE_FORMAT = 'The birth date is invalid or in the future, expected format %s. Given: %s years';

    public static function invalidAge(int $invalidValue, Throwable $previous = null): self
    {
        $message = \sprintf(self::ERROR_AGE_MESSAGE_FORMAT, BirthDate::MIN_AGE, $invalidValue);

        return new self($message, 0, $previous);
    }

    public static function invalidValue(string $invalidValue, Throwable $previous = null): self
    {
        $message = \sprintf(self::ERROR_VALUE_MESSAGE_FORMAT, BirthDate::DATE_FORMAT, $invalidValue);

        return new self($message, 0, $previous);
    }
}
