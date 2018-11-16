<?php

declare(strict_types=1);

namespace App\Integration\Article\Mapper;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Article\Article;
use Acme\Article\ValueObject\ArticleID;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use Acme\Reviewer\ValueObject\ReviewerID;
use DateTimeImmutable;

final class DatabaseArticleMapper implements ArticleMapper
{
    /**
     * @var FromArticlePartialMapping
     */
    private $fromArticlePartialMapping;

    public function __construct(FromArticlePartialMapping $fromArticlePartialMapping)
    {
        $this->fromArticlePartialMapping = $fromArticlePartialMapping;
    }

    public function fromArray(array $rawArticle): Article
    {
        return new Article(
            ArticleID::fromUUID($rawArticle['id']),
            new Title($rawArticle['title']),
            new Body($rawArticle['body']),
            AcademicRegistrationNumber::fromString($rawArticle['academic_id']),
            ReviewerID::fromUUID($rawArticle['reviewer_id']),
            isset($rawArticle['published_at']) ? new DateTimeImmutable($rawArticle['published_at']) : null,
            new DateTimeImmutable($rawArticle['created_at']),
            isset($rawArticle['updated_at']) ? new DateTimeImmutable($rawArticle['updated_at']) : null
        );
    }

    public function fromArticle(Article $article): array
    {
        return ($this->fromArticlePartialMapping)($article);
    }
}
