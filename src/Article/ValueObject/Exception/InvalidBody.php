<?php

declare(strict_types=1);

namespace Acme\Article\ValueObject\Exception;

use Acme\Article\ValueObject\Body;
use Throwable;

final class InvalidBody extends \InvalidArgumentException
{
    public const LENGTH_MESSAGE_FORMAT = 'The given value "%s" is not valid to create a Body. Min length is %d';

    public function __construct($invalidValue, Throwable $previous = null)
    {
        $message = \sprintf(self::LENGTH_MESSAGE_FORMAT, $invalidValue, Body::MIN_LENGTH);
        parent::__construct($message, $code = 0, $previous);
    }
}
