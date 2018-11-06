<?php

declare(strict_types=1);

namespace Acme\Article\Repository\Exception;

use Throwable;

final class ImpossibleToRetrieveArticles extends \Exception
{
    private const ERROR_MESSAGE_FORMAT = 'Impossible to retrieve articles';

    public function __construct(Throwable $previous = null)
    {
        parent::__construct(self::ERROR_MESSAGE_FORMAT, 0, $previous);
    }
}
