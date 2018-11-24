<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject\Exception;

use Acme\Academic\ValueObject\Email;
use Throwable;

final class InvalidEmail extends \InvalidArgumentException
{
    public const ERROR_MESSAGE_FORMAT = 'The given value "%s" is not valid to create an Email. It must be a vail email ending with %s';

    public const DOMAIN_MESSAGE_FORMAT = 'The given value "%s" is not valid to create an Email. It must end with %s';

    public static function fromInvalidDomain($invalidValue, Throwable $previous = null): self
    {
        $message = \sprintf(self::DOMAIN_MESSAGE_FORMAT, $invalidValue, Email::EMAIL_DOMAIN);

        return new self($message, $code = 0, $previous);
    }

    public static function fromInvalidValue($invalidValue, Throwable $previous = null): self
    {
        $message = \sprintf(self::ERROR_MESSAGE_FORMAT, $invalidValue, Email::EMAIL_DOMAIN);

        return new self($message, $code = 0, $previous);
    }
}
