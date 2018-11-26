<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject\Exception;

use Acme\Academic\ValueObject\Password;
use Throwable;

final class InvalidPassword extends \InvalidArgumentException
{
    public const ERROR_MESSAGE_FORMAT = 'The given value value is not valid to create a Password';

    public const LENGTH_MESSAGE_FORMAT = 'The given value value is not valid to create a Password, it must be length at least %d chars';

    public static function fromError(Throwable $previous = null): self
    {
        return new self(self::ERROR_MESSAGE_FORMAT, $code = 0, $previous);
    }

    public static function fromLength(Throwable $previous = null): self
    {
        $message = \sprintf(self::LENGTH_MESSAGE_FORMAT, Password::MIN_LENGTH);

        return new self($message, $code = 0, $previous);
    }
}
