<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject\Exception;

use Throwable;

final class InvalidMajor extends \InvalidArgumentException
{
    public const ERROR_MESSAGE_FORMAT = 'The given value %s is not a valid major';

    public function __construct(string $invalidValue, Throwable $previous = null)
    {
        $message = \sprintf(self::ERROR_MESSAGE_FORMAT, $invalidValue);

        parent::__construct($message, 0, $previous);
    }
}
