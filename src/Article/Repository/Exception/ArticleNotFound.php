<?php

declare(strict_types=1);

namespace Acme\Article\Repository\Exception;

use Acme\Article\ValueObject\ArticleID;
use Acme\Common\Exception\EntityNotFound;
use Throwable;

final class ArticleNotFound extends \Exception implements EntityNotFound
{
    private const ERROR_MESSAGE_FORMAT = 'Article with ID: "%s" was not found';

    /**
     * @var ArticleID
     */
    private $articleID;

    public function __construct(ArticleID $articleID, Throwable $previous = null)
    {
        $message = \sprintf(self::ERROR_MESSAGE_FORMAT, (string) $articleID);
        parent::__construct($message, 0, $previous);

        $this->articleID = $articleID;
    }

    public function getEntityName(): string
    {
        return 'Article';
    }

    public function getEntityId(): string
    {
        return (string)$this->articleID;
    }
}
