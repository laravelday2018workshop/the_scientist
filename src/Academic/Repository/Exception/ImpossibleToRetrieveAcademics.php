<?php

declare(strict_types=1);

namespace Acme\Academic\Repository\Exception;

use Throwable;

final class ImpossibleToRetrieveAcademics extends \Exception
{
    private const ERROR_MESSAGE_FORMAT = 'Impossible to retrieve academics';

    public function __construct(Throwable $previous = null)
    {
        parent::__construct(self::ERROR_MESSAGE_FORMAT, 0, $previous);
    }
}
