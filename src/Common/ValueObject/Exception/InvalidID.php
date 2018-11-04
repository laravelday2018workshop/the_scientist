<?php

declare(strict_types=1);

namespace Acme\Common\ValueObject\Exception;

use Throwable;

final class InvalidID extends \InvalidArgumentException
{
    public const ERROR_MESSAGE_FORMAT = 'The given value is not valid to create an articleID. Given "%s"';

    public function __construct($invalidValue, Throwable $previous = null)
    {
        $message = \sprintf(self::ERROR_MESSAGE_FORMAT, $invalidValue);
        parent::__construct($message, $code = 0, $previous);
    }
}
