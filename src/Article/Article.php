<?php

declare(strict_types=1);

namespace Acme\Article;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Article\ValueObject\ArticleID;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use Acme\Reviewer\ValueObject\ReviewerID;
use DateTimeImmutable;

final class Article
{
    /**
     * @var ArticleID
     */
    private $articleID;

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

    /**
     * @var ReviewerID
     */
    private $reviewerID;

    /**
     * @var DateTimeImmutable|null
     */
    private $publishDate;

    /**
     * @var DateTimeImmutable
     */
    private $creationDate;

    /**
     * @var DateTimeImmutable|null
     */
    private $lastUpdateDate;

    public function __construct(
        ArticleID $articleID,
        Title $title,
        Body $body,
        AcademicRegistrationNumber $academicID,
        ReviewerID $reviewerID,
        ?DateTimeImmutable $publishDate,
        DateTimeImmutable $creationDate,
        ?DateTimeImmutable $lastUpdateDate
    ) {
        $this->articleID = $articleID;
        $this->title = $title;
        $this->body = $body;
        $this->academicRegistrationNumber = $academicID;
        $this->reviewerID = $reviewerID;
        $this->publishDate = $publishDate;
        $this->creationDate = $creationDate;
        $this->lastUpdateDate = $lastUpdateDate;
    }

    public static function create(
        ArticleID $articleID,
        Title $title,
        Body $body,
        AcademicRegistrationNumber $academicID,
        ReviewerID $reviewerID,
        DateTimeImmutable $creationDate)
    {
        return new self($articleID, $title, $body, $academicID, $reviewerID, null, $creationDate, null);
    }

    public function id(): ArticleID
    {
        return $this->articleID;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function body(): Body
    {
        return $this->body;
    }

    public function academicRegistrationNumber(): AcademicRegistrationNumber
    {
        return $this->academicRegistrationNumber;
    }

    public function reviewerID(): ReviewerID
    {
        return $this->reviewerID;
    }

    public function publishDate(): ?DateTimeImmutable
    {
        return $this->publishDate;
    }

    public function creationDate(): DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function lastUpdateDate(): ?DateTimeImmutable
    {
        return $this->lastUpdateDate;
    }

    public function updateWithReviewChanges(Title $title, Body $body): void
    {
        $this->title = $title;
        $this->body = $body;
        $this->lastUpdateDate = new DateTimeImmutable();
    }
}
