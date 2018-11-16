<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject\Exception;

use Acme\Academic\ValueObject\LastName;
use Throwable;

final class InvalidLastName extends \InvalidArgumentException
{
    public const LENGTH_MESSAGE_FORMAT = 'The given value "%s" is not valid to create a Last name. Min length is %d and max length is %d';

    public function __construct($invalidValue, Throwable $previous = null)
    {
        $message = \sprintf(self::LENGTH_MESSAGE_FORMAT, $invalidValue, LastName::MIN_LENGTH, LastName::MAX_LENGTH);
        parent::__construct($message, $code = 0, $previous);
    }
}
