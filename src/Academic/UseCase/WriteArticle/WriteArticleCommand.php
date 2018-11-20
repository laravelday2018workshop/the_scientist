<?php

declare(strict_types=1);

namespace Acme\Academic\UseCase\WriteArticle;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;

final class WriteArticleCommand
{
    /**
     * @var Title
     */
    private $title;

    /**
     * @var Body
     */
    private $body;

    /**
     * @var AcademicRegistrationNumber
     */
    private $academicRegistrationNumber;

    public function __construct(Title $title, Body $body, AcademicRegistrationNumber $academicRegistrationNumber)
    {
        $this->title = $title;
        $this->body = $body;
        $this->academicRegistrationNumber = $academicRegistrationNumber;
    }

    public function getTitle(): Title
    {
        return $this->title;
    }

    public function getBody(): Body
    {
        return $this->body;
    }

    public function getAcademicRegistrationNumber(): AcademicRegistrationNumber
    {
        return $this->academicRegistrationNumber;
    }
}
