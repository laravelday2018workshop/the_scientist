<?php

declare(strict_types=1);

namespace Acme\Article\Repository\Exception;

use Throwable;

class ImpossibleToSaveArticle extends \Exception
{
    private const ERROR_MESSAGE_FORMAT = 'Impossible to save article';

    public function __construct(Throwable $previous = null)
    {
        parent::__construct(self::ERROR_MESSAGE_FORMAT, 0, $previous);
    }
}
