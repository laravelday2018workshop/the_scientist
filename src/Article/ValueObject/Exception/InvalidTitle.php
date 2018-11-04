<?php

declare(strict_types=1);

namespace Acme\Article\ValueObject\Exception;

use Acme\Article\ValueObject\Title;
use Throwable;

final class InvalidTitle extends \InvalidArgumentException
{
    public const LENGTH_MESSAGE_FORMAT = 'The given value "%s" is not valid to create a Title. Min length is %d and max length is %d';

    public function __construct($invalidValue, Throwable $previous = null)
    {
        $message = \sprintf(self::LENGTH_MESSAGE_FORMAT, $invalidValue, Title::MIN_LENGTH, Title::MAX_LENGTH);
        parent::__construct($message, $code = 0, $previous);
    }
}
