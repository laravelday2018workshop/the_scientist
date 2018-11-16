<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject\Excpetion;

use Acme\Common\Exception\InvalidInput;
use Throwable;

final class InvalidAcademicRegistrationNumber extends \InvalidArgumentException implements InvalidInput
{
    public const ERROR_MESSAGE_FORMAT = 'The given value is not valid. Given "%s"';

    public function __construct($invalidValue, Throwable $previous = null)
    {
        $message = \sprintf(self::ERROR_MESSAGE_FORMAT, $invalidValue);
        parent::__construct($message, $code = 0, $previous);
    }
}
