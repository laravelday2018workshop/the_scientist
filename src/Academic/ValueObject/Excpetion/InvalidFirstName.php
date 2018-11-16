<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject\Exception;

use Acme\Academic\ValueObject\FirstName;
use Throwable;

final class InvalidFirstName extends \InvalidArgumentException
{
    public const LENGTH_MESSAGE_FORMAT = 'The given value "%s" is not valid to create a First name. Min length is %d and max length is %d';

    public function __construct($invalidValue, Throwable $previous = null)
    {
        $message = \sprintf(self::LENGTH_MESSAGE_FORMAT, $invalidValue, FirstName::MIN_LENGTH, FirstName::MAX_LENGTH);
        parent::__construct($message, $code = 0, $previous);
    }
}
