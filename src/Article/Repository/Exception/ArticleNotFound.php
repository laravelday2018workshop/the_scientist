<?php

declare(strict_types=1);

namespace Acme\Article\Repository\Exception;

use Acme\Article\ValueObject\ArticleID;
use Throwable;

final class ArticleNotFound extends \Exception
{
    private const ERROR_MESSAGE_FORMAT = 'Article with ID: "%s" was not found';

    public function __construct(ArticleID $articleID, Throwable $previous = null)
    {
        $message = \sprintf(self::ERROR_MESSAGE_FORMAT, (string) $articleID);
        parent::__construct($message, 0, $previous);
    }
}
