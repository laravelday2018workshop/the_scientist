<?php

declare(strict_types=1);

namespace Acme\Article\UseCase\CreateArticle;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use Acme\Reviewer\ValueObject\ReviewerID;

final class CreateArticleCommand
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
     * @var ReviewerID
     */
    private $reviewerID;

    /**
     * @var AcademicRegistrationNumber
     */
    private $academicID;

    public function __construct(Title $title, Body $body, ReviewerID $reviewerID, AcademicRegistrationNumber $academicID)
    {
        $this->title = $title;
        $this->body = $body;
        $this->reviewerID = $reviewerID;
        $this->academicID = $academicID;
    }

    public function getTitle(): Title
    {
        return $this->title;
    }

    public function getBody(): Body
    {
        return $this->body;
    }

    public function getReviewerID(): ReviewerID
    {
        return $this->reviewerID;
    }

    public function getAcademicID(): AcademicRegistrationNumber
    {
        return $this->academicID;
    }
}
