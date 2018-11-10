<?php

declare(strict_types=1);

namespace Acme\Academic;

use Acme\Academic\ValueObject\AcademicID;
use Acme\Article\ValueObject\ArticleID;

final class Academic
{
    /**
     * @var AcademicID
     */
    private $academicID;

    public function __construct(ArticleID $academicID)
    {
        $this->academicID = $academicID;
    }

    public function id(): AcademicID
    {
        return $this->academicID;
    }
}
