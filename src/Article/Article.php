<?php

declare(strict_types=1);

namespace Acme\Article;

use Acme\Academic\Academic;
use Acme\Article\ValueObject\ArticleID;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use Acme\Reviewer\Reviewer;
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
     * @var Academic
     */
    private $academic;

    /**
     * @var Reviewer
     */
    private $reviewer;

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
        Academic $academic,
        Reviewer $reviewer,
        ?DateTimeImmutable $publishDate,
        DateTimeImmutable $creationDate,
        ?DateTimeImmutable $lastUpdateDate
    ) {
        $this->articleID = $articleID;
        $this->title = $title;
        $this->body = $body;
        $this->academic = $academic;
        $this->reviewer = $reviewer;
        $this->publishDate = $publishDate;
        $this->creationDate = $creationDate;
        $this->lastUpdateDate = $lastUpdateDate;
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

    public function academic(): Academic
    {
        return $this->academic;
    }

    public function reviewer(): Reviewer
    {
        return $this->reviewer;
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
}
