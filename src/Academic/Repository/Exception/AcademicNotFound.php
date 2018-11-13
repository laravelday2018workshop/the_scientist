<?php

declare(strict_types=1);

namespace Acme\Academic\Repository\Exception;

use Acme\Academic\Academic;
use Acme\Academic\ValueObject\AcademicID;
use Acme\Common\Exception\EntityNotFound;
use Throwable;

final class AcademicNotFound extends \Exception implements EntityNotFound
{
    private const ERROR_MESSAGE_FORMAT = 'Academic with ID: "%s" was not found';

    /**
     * @var AcademicID
     */
    private $academicID;

    public function __construct(AcademicID $academicID, Throwable $previous = null)
    {
        $message = \sprintf(self::ERROR_MESSAGE_FORMAT, (string) $academicID);
        parent::__construct($message, 0, $previous);

        $this->academicID = $academicID;
    }

    public function getEntityName(): string
    {
        return Academic::class;
    }

    public function getEntityId(): string
    {
        return (string) $this->academicID;
    }
}
